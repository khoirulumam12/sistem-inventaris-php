<?php
require 'function.php';

// Validasi jika tidak ada token di URL, kembalikan ke login
if(!isset($_GET['key']) || !isset($_GET['token'])){
    header('location:login.php');
    exit();
}

$email = mysqli_real_escape_string($conn, $_GET['key']);
$token = mysqli_real_escape_string($conn, $_GET['token']);
$curDate = date("Y-m-d H:i:s");

// Cek Kecocokan Token di Database
$query = mysqli_query($conn, "SELECT * FROM password_resets WHERE email='$email' AND token='$token'");
$data = mysqli_fetch_array($query);
$count = mysqli_num_rows($query);

if ($count == 0) {
    $msg = "Token tidak valid atau link sudah kedaluwarsa.";
    $alert_type = "danger";
    $icon_alert = "fa-exclamation-triangle";
} else if ($data['expDate'] < $curDate){
    $msg = "Link reset password sudah expired (melewati 1 jam).";
    $alert_type = "warning";
    $icon_alert = "fa-clock";
} else {
    $valid_token = true;
}

// Proses Simpan Password Baru
if(isset($_POST['submit_password']) && isset($valid_token)){
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    // Validasi panjang password
    if(strlen($pass1) < 6){
        echo "<script>alert('Password minimal 6 karakter!');</script>";
    } else if($pass1 != $pass2){
        echo "<script>alert('Konfirmasi password tidak sama!');</script>";
    } else {
        // Enkripsi Password Baru
        $new_password = password_hash($pass1, PASSWORD_DEFAULT);
        
        // Update Password User
        $update = mysqli_query($conn, "UPDATE login SET password='$new_password' WHERE email='$email'");
        
        if($update){
            // Hapus token agar tidak bisa dipakai lagi (One time use)
            mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");
            
            // Redirect dengan pesan sukses
            echo "
            <script>
                alert('Berhasil! Password Anda telah diperbarui. Silakan Login.');
                window.location='login.php';
            </script>";
        } else {
            echo "<script>alert('Terjadi kesalahan sistem database.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-reset {
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: none;
            position: relative;
            overflow: hidden;
        }
        .card-reset::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 5px;
            background: linear-gradient(90deg, #4e73df, #224abe);
        }
        .header-icon {
            background: #eef2ff;
            color: #4e73df;
            width: 70px;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto 20px;
            font-size: 30px;
        }
        .form-floating label {
            color: #999;
            font-size: 0.9rem;
        }
        .form-floating > .form-control:focus ~ label,
        .form-floating > .form-control:not(:placeholder-shown) ~ label {
            color: #4e73df;
            font-weight: 600;
        }
        .form-control {
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            padding-right: 45px; /* Space for eye icon */
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
            border-color: #4e73df;
        }
        .btn-save {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            border: none;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-top: 10px;
            transition: all 0.3s;
        }
        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
            color: white;
        }
        .password-toggle {
            position: absolute;
            top: 50%;
            right: 15px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #aaa;
            z-index: 10;
        }
        .password-toggle:hover {
            color: #4e73df;
        }
        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
            border: none;
        }
    </style>
</head>
<body>

    <div class="card-reset">
        
        <?php if(!isset($msg)) { ?>
            <div class="header-icon">
                <i class="fas fa-lock-open"></i>
            </div>
            <h4 class="text-center fw-bold mb-1">Set Password Baru</h4>
            <p class="text-center text-muted small mb-4">Silakan buat password baru untuk akun Anda.</p>
        <?php } else { ?>
            <div class="header-icon text-danger" style="background: #ffebee;">
                <i class="fas <?php echo $icon_alert; ?>"></i>
            </div>
            <h4 class="text-center fw-bold mb-3 text-danger">Akses Ditolak</h4>
        <?php } ?>

        <?php if(isset($msg)) { ?>
            <div class="alert alert-<?php echo $alert_type; ?> d-flex align-items-center" role="alert">
                <i class="fas <?php echo $icon_alert; ?> me-2"></i>
                <div>
                    <?php echo $msg; ?>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="forgot.php" class="btn btn-outline-primary rounded-pill px-4">Kirim Ulang Link</a>
                <div class="mt-3">
                    <a href="login.php" class="text-decoration-none small text-muted">Kembali ke Login</a>
                </div>
            </div>
        <?php } ?>

        <?php if(isset($valid_token)) { ?>
        <form method="post">
            <input type="hidden" name="email" value="<?php echo $email; ?>">
            <input type="hidden" name="token" value="<?php echo $token; ?>">

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" name="pass1" id="pass1" placeholder="Password Baru" required>
                <label for="pass1">Password Baru</label>
                <i class="fas fa-eye password-toggle" id="togglePass1" onclick="togglePassword('pass1', 'togglePass1')"></i>
            </div>

            <div class="form-floating mb-4 position-relative">
                <input type="password" class="form-control" name="pass2" id="pass2" placeholder="Ulangi Password" required>
                <label for="pass2">Konfirmasi Password</label>
                <i class="fas fa-eye password-toggle" id="togglePass2" onclick="togglePassword('pass2', 'togglePass2')"></i>
            </div>

            <button class="btn-save" name="submit_password">
                <i class="fas fa-save me-2"></i> Simpan Password
            </button>

            <div class="text-center mt-4">
                <a href="login.php" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i> Batal, kembali ke login
                </a>
            </div>
        </form>
        <?php } ?>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>