<?php
require 'function.php'; // Koneksi database

// Memanggil Library PHPMailer secara manual
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['reset_request'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Cek apakah email terdaftar di database
    $check = mysqli_query($conn, "SELECT * FROM login WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        
        $token = bin2hex(random_bytes(50)); 
        $expDate = date("Y-m-d H:i:s", strtotime('+1 hour')); 

        // Hapus request reset lama jika ada
        mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");

        // Simpan token baru
        $query = "INSERT INTO password_resets (email, token, expDate) VALUES ('$email', '$token', '$expDate')";
        mysqli_query($conn, $query);

        // --- SETTING URL WEBSITE ---
        // Ganti 'localhost/nama_folder' sesuai folder project Anda
        $base_url = "http://localhost/toko-cahaya-subur"; 
        $link = $base_url . "/reset.php?key=".$email."&token=".$token;

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'khoirulumam1217@gmail.com'; // Email Anda
            
            // --- MASUKKAN SANDI APLIKASI DI BAWAH INI ---
            $mail->Password   = 'poer skym mifx vtld'; 
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Pengirim & Penerima
            $mail->setFrom('khoirulumam1217@gmail.com', 'Admin Toko Cahaya Subur');
            $mail->addAddress($email);

            // Isi Email
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password - Toko Cahaya Subur';
            $mail->Body    = '<h3>Permintaan Reset Password</h3>
                              <p>Seseorang baru saja meminta reset password untuk akun ini.</p>
                              <p>Silahkan klik link di bawah untuk membuat password baru:</p>
                              <a href="'.$link.'" style="background:#4e73df; color:white; padding:10px 20px; text-decoration:none; border-radius:5px;">Reset Password</a>
                              <br><br>
                              <p>Link ini akan kedaluwarsa dalam 1 jam.</p>';

            $mail->send();
            echo "<script>alert('Link reset telah dikirim ke email: $email. Silahkan cek Inbox atau Spam.');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Gagal mengirim email. Error: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar dalam sistem!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lupa Password - Toko Cahaya Subur</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f0f2f5; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card-reset { width: 100%; max-width: 400px; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .btn-kirim { background: #4e73df; color: white; border: none; width: 100%; padding: 12px; border-radius: 30px; font-weight: bold; transition: 0.3s; }
        .btn-kirim:hover { background: #224abe; }
    </style>
</head>
<body>
    <div class="card-reset">
        <h3 class="text-center mb-4">Lupa Password?</h3>
        <p class="text-center small text-muted mb-4">Masukkan email Anda, kami akan mengirimkan link untuk mereset password.</p>
        <form method="post">
            <div class="form-group mb-3">
                <input class="form-control" name="email" type="email" placeholder="Masukkan Email Anda" required style="border-radius: 30px; padding: 20px;" />
            </div>
            <button class="btn-kirim" name="reset_request">Kirim Link Reset</button>
            <div class="text-center mt-3">
                <a href="login.php" style="text-decoration: none;">&larr; Kembali Login</a>
            </div>
        </form>
    </div>
</body>
</html>