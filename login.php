<?php
require 'function.php';

// Cek Login
if(isset($_POST['login'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $cekdatabase = mysqli_query($conn, "SELECT * FROM login WHERE email='$email'");
    $hitung = mysqli_num_rows($cekdatabase);
    $data = mysqli_fetch_array($cekdatabase);

    if($hitung > 0){
        // Verifikasi Password (Jika pakai hash)
        if(password_verify($password, $data['password'])){
            $_SESSION['log'] = 'True';
            header('location:index.php');
        } else {
            // Fallback jika masih pakai password biasa (non-hash) untuk data lama
            if($password == $data['password']){
                 $_SESSION['log'] = 'True';
                 header('location:index.php');
            } else {
                echo "<script>alert('Password salah!');</script>";
            }
        }
    } else {
        echo "<script>alert('Email tidak ditemukan!');</script>";
    }
};

if(!isset($_SESSION['log'])){
    // Biarkan di halaman login
} else {
    header('location:index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - Toko Cahaya Subur</title>
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6); /* Gelapkan background */
            z-index: 1;
        }
        .card-login {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 400px;
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            text-align: center;
            padding: 30px 20px;
            border: none;
        }
        .card-header h3 {
            font-weight: 600;
            margin: 0;
            font-size: 24px;
        }
        .card-body {
            padding: 40px 30px;
            background: white;
        }
        .form-control {
            border-radius: 30px;
            padding: 25px 20px;
            background: #f8f9fc;
            border: 1px solid #ddd;
        }
        .btn-login {
            border-radius: 30px;
            padding: 12px;
            font-weight: bold;
            background: linear-gradient(45deg, #4e73df, #224abe);
            border: none;
            transition: 0.3s;
        }
        .btn-login:hover {
            transform: scale(1.02);
            background: linear-gradient(45deg, #224abe, #4e73df);
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="card card-login">
        <div class="card-header">
            <i class="fas fa-boxes fa-3x mb-3"></i>
            <h3>Inventory System</h3>
            <p class="small mb-0">Toko Cahaya Subur</p>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="Masukkan Email Address" required />
                </div>
                <div class="form-group">
                    <input class="form-control" name="password" type="password" placeholder="Masukkan Password" required />
                </div>
                <div class="form-group d-flex justify-content-end">
                    <a href="forgot.php" class="small text-primary">Lupa Password?</a>
                </div>
                <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
                    <button class="btn btn-primary btn-block btn-login" name="login">Login Sekarang</button>
                </div>
            </form>
        </div>
        <div class="card-footer text-center py-3 bg-white">
            <div class="small">
            </div>
        </div>
    </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>