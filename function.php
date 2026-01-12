<?php
session_start();

// Konfigurasi Database
$conn = mysqli_connect("localhost", "root", "", "stokbarang");

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ==========================================================================
// FUNGSI 1: KELOLA DATA STOK (MASTER BARANG)
// ==========================================================================

// Tambah Barang Baru
if (isset($_POST['addnewbarang'])) {
    // MENANGKAP INPUT BARU (KODE & HARGA)
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $namabarang  = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $jenis       = mysqli_real_escape_string($conn, $_POST['deskripsi']); 
    $harga       = (int)$_POST['harga']; // Menangkap harga sebagai integer
    $stok        = (int)$_POST['stok'];

    // Konfigurasi Upload Gambar
    $allowed_extensions = array('png', 'jpg', 'jpeg');
    $name           = $_FILES['filegambar']['name'];
    $dot            = explode('.', $name);
    $extension      = strtolower(end($dot));
    $size           = $_FILES['filegambar']['size'];
    $file_tmp       = $_FILES['filegambar']['tmp_name'];

    $image_name = md5(uniqid($name, true) . time()) . '.' . $extension;

    // Cek apakah barang sudah ada (bisa ditambah cek berdasarkan kode_barang juga jika mau)
    $cek = mysqli_query($conn, "SELECT * FROM stok WHERE namabarang='$namabarang'");
    
    if (mysqli_num_rows($cek) < 1) {
        if (in_array($extension, $allowed_extensions) === true) {
            if ($size < 15000000) { 
                move_uploaded_file($file_tmp, 'images/' . $image_name);
                
                // QUERY INSERT DIPERBARUI: Menambahkan kode_barang dan harga
                $addtotable = mysqli_query($conn, "INSERT INTO stok (kode_barang, namabarang, deskripsi, harga, stok, image) VALUES ('$kode_barang', '$namabarang', '$jenis', '$harga', '$stok', '$image_name')");
                
                if ($addtotable) { header('location:index.php?status=success'); } else { header('location:index.php?status=gagal'); }
            } else { echo "<script>alert('Ukuran file terlalu besar!'); window.location.href='index.php';</script>"; }
        } else { echo "<script>alert('Format file tidak didukung!'); window.location.href='index.php';</script>"; }
    } else { echo "<script>alert('Nama barang sudah ada!'); window.location.href='index.php';</script>"; }
}

// Update Info Barang
if (isset($_POST['updatebarang'])) {
    $idbarang    = $_POST['idbarang'];
    // MENANGKAP INPUT BARU (KODE & HARGA)
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $namabarang  = mysqli_real_escape_string($conn, $_POST['namabarang']);
    $jenis       = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga       = (int)$_POST['harga'];

    $name           = $_FILES['filegambar']['name'];
    $dot            = explode('.', $name);
    $extension      = strtolower(end($dot));
    $size           = $_FILES['filegambar']['size'];
    $file_tmp       = $_FILES['filegambar']['tmp_name'];

    if ($size == 0) {
        // QUERY UPDATE DIPERBARUI (Tanpa ganti gambar): Update kode_barang dan harga
        $update = mysqli_query($conn, "UPDATE stok SET kode_barang='$kode_barang', namabarang='$namabarang', deskripsi='$jenis', harga='$harga' WHERE idbarang='$idbarang'");
        header('location:index.php');
    } else {
        $image_name = md5(uniqid($name, true) . time()) . '.' . $extension;
        move_uploaded_file($file_tmp, 'images/' . $image_name);
        
        $cekgambar = mysqli_query($conn, "SELECT image FROM stok WHERE idbarang='$idbarang'");
        $gambarlama = mysqli_fetch_array($cekgambar);
        if($gambarlama['image'] != 'No Photo' && file_exists('images/'.$gambarlama['image'])){
            unlink("images/".$gambarlama['image']);
        }
        
        // QUERY UPDATE DIPERBARUI (Dengan ganti gambar): Update kode_barang dan harga
        $update = mysqli_query($conn, "UPDATE stok SET kode_barang='$kode_barang', namabarang='$namabarang', deskripsi='$jenis', harga='$harga', image='$image_name' WHERE idbarang='$idbarang'");
        header('location:index.php');
    }
}

// Hapus Barang Master
if (isset($_POST['hapusbarang'])) {
    $idbarang = $_POST['idbarang'];

    $gambar = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idbarang'");
    $get = mysqli_fetch_array($gambar);
    $img = 'images/' . $get['image'];
    if (file_exists($img)) { unlink($img); }

    mysqli_query($conn, "DELETE FROM masuk WHERE idbarang='$idbarang'");
    mysqli_query($conn, "DELETE FROM keluar WHERE idbarang='$idbarang'");
    mysqli_query($conn, "DELETE FROM returnbarang WHERE idbarang='$idbarang'");
    mysqli_query($conn, "DELETE FROM requestbarang WHERE idbarang='$idbarang'");
    
    $hapus = mysqli_query($conn, "DELETE FROM stok WHERE idbarang='$idbarang'");
    if ($hapus) { header('location:index.php'); } else { echo "<script>alert('Gagal menghapus data!'); window.location.href='index.php';</script>"; }
}

// ==========================================================================
// FUNGSI 2: BARANG MASUK
// ==========================================================================

if (isset($_POST['addbarangmasuk'])) {
    $barangnya  = $_POST['barangnya'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $qty        = (int)$_POST['qty'];

    $cekstok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang = '$barangnya'");
    $ambil = mysqli_fetch_array($cekstok);
    $stoksekarang = $ambil['stok'];
    $stokbaru     = $stoksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES ('$barangnya','$keterangan','$qty')");
    $updatestok = mysqli_query($conn, "UPDATE stok SET stok = '$stokbaru' WHERE idbarang = '$barangnya'");

    if ($addtomasuk && $updatestok) { header('location:masuk.php'); } else { header('location:masuk.php'); }
}

if (isset($_POST['updatebarangmasuk'])) {
    $idbarang   = $_POST['idbarang'];
    $idmasuk    = $_POST['idmasuk'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $qty_baru   = (int)$_POST['qty'];

    $lihatmasuk = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idmasuk'");
    $datamasuk  = mysqli_fetch_array($lihatmasuk);
    $qty_lama   = $datamasuk['qty'];

    $lihatstok  = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idbarang'");
    $datastok   = mysqli_fetch_array($lihatstok);
    $stok_saat_ini = $datastok['stok'];

    $stok_update = ($stok_saat_ini - $qty_lama) + $qty_baru;

    $update_stok_master = mysqli_query($conn, "UPDATE stok SET stok='$stok_update' WHERE idbarang='$idbarang'");
    $update_data_masuk  = mysqli_query($conn, "UPDATE masuk SET qty='$qty_baru', keterangan='$keterangan' WHERE idmasuk='$idmasuk'");

    if ($update_stok_master && $update_data_masuk) { header('location:masuk.php'); } else { header('location:masuk.php'); }
}

if (isset($_POST['hapusbarangmasuk'])) {
    $idbarang = $_POST['idbarang'];
    $idmasuk  = $_POST['idmasuk'];
    $qty      = (int)$_POST['qty'];

    $getstok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idbarang'");
    $data    = mysqli_fetch_array($getstok);
    $stok    = $data['stok'];
    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "UPDATE stok SET stok='$selisih' WHERE idbarang='$idbarang'");
    $hapus  = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idmasuk'");

    if ($update && $hapus) { header('location:masuk.php'); } else { header('location:masuk.php'); }
}

// ==========================================================================
// FUNGSI 3: BARANG KELUAR
// ==========================================================================

if (isset($_POST['addbarangkeluar'])) {
    $barangnya = $_POST['barangnya'];
    $penerima  = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty       = (int)$_POST['qty'];

    $cekstok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang = '$barangnya'");
    $ambil   = mysqli_fetch_array($cekstok);
    $stoksekarang = $ambil['stok'];

    if ($stoksekarang >= $qty) {
        $stokbaru = $stoksekarang - $qty;
        $masukkeluar = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) VALUES ('$barangnya','$penerima','$qty')");
        $updatestok  = mysqli_query($conn, "UPDATE stok SET stok = '$stokbaru' WHERE idbarang = '$barangnya'");
        if ($masukkeluar && $updatestok) { header('location:keluar.php'); } else { header('location:keluar.php'); }
    } else { echo "<script>alert('Stok saat ini tidak mencukupi!'); window.location.href='keluar.php';</script>"; }
}

if (isset($_POST['updatebarangkeluar'])) {
    $idbarang   = $_POST['idbarang'];
    $idkeluar   = $_POST['idkeluar'];
    $penerima   = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty_baru   = (int)$_POST['qty'];

    $lihatkeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idkeluar'");
    $datakeluar  = mysqli_fetch_array($lihatkeluar);
    $qty_lama    = $datakeluar['qty'];

    $lihatstok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idbarang'");
    $datastok  = mysqli_fetch_array($lihatstok);
    $stok_saat_ini = $datastok['stok'];
    $stok_murni = $stok_saat_ini + $qty_lama;

    if ($stok_murni >= $qty_baru) {
        $stok_final = $stok_murni - $qty_baru;
        $update_stok    = mysqli_query($conn, "UPDATE stok SET stok='$stok_final' WHERE idbarang='$idbarang'");
        $update_keluar = mysqli_query($conn, "UPDATE keluar SET qty='$qty_baru', penerima='$penerima' WHERE idkeluar='$idkeluar'");
        if ($update_stok && $update_keluar) { header('location:keluar.php'); } else { header('location:keluar.php'); }
    } else { echo "<script>alert('Stok tidak mencukupi!'); window.location.href='keluar.php';</script>"; }
}

if (isset($_POST['hapusbarangkeluar'])) {
    $idbarang = $_POST['idbarang'];
    $idkeluar = $_POST['idkeluar'];
    $qty      = (int)$_POST['qty'];

    $getstok = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idbarang'");
    $data    = mysqli_fetch_array($getstok);
    $stok    = $data['stok'];
    $stok_kembali = $stok + $qty;

    $update = mysqli_query($conn, "UPDATE stok SET stok='$stok_kembali' WHERE idbarang='$idbarang'");
    $hapus  = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idkeluar'");
    if ($update && $hapus) { header('location:keluar.php'); } else { header('location:keluar.php'); }
}

// ==========================================================================
// FUNGSI 4: MANAJEMEN ADMIN
// ==========================================================================

if (isset($_POST['addadmin'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 
    $password_encrypted = password_hash($password, PASSWORD_DEFAULT);
    $queryinsert = mysqli_query($conn, "INSERT INTO login (email, password) VALUES ('$email', '$password_encrypted')");
    if ($queryinsert) { header('location:admin.php'); } else { header('location:admin.php?error=gagal'); }
}

if (isset($_POST['updateadmin'])) {
    $iduser       = $_POST['iduser'];
    $emailbaru    = mysqli_real_escape_string($conn, $_POST['emailadmin']);
    $passwordbaru = $_POST['passwordbaru'];

    if(!empty($passwordbaru)){
        $password_encrypted = password_hash($passwordbaru, PASSWORD_DEFAULT);
        $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru', password='$password_encrypted' WHERE iduser='$iduser'");
    } else {
        $queryupdate = mysqli_query($conn, "UPDATE login SET email='$emailbaru' WHERE iduser='$iduser'");
    }
    if ($queryupdate) { header('location:admin.php'); } else { header('location:admin.php'); }
}

if (isset($_POST['hapusadmin'])) {
    $iduser = $_POST['iduser'];
    $queryhapus = mysqli_query($conn, "DELETE FROM login WHERE iduser='$iduser'");
    if ($queryhapus) { header('location:admin.php'); } else { header('location:admin.php'); }
}

// ==========================================================================
// FUNGSI 5: RETURN & REQUEST
// ==========================================================================

if(isset($_POST['addreturn'])){
    $barangnya  = $_POST['barangnya'];
    $penerima   = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty        = (int)$_POST['qty'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $cekstoksekarang = mysqli_query($conn, "select * from stok where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);
    $stoksekarang = $ambildatanya['stok'];
    $tambahkanstoksekarangdenganquantity = $stoksekarang + $qty;

    $addtoreturn = mysqli_query($conn, "insert into returnbarang (idbarang, keterangan, qty, customer) values('$barangnya','$keterangan','$qty','$penerima')");
    $updatestokmasuk = mysqli_query($conn, "update stok set stok='$tambahkanstoksekarangdenganquantity' where idbarang='$barangnya'");
    if($addtoreturn && $updatestokmasuk){ header('location:return.php?status=success'); } else { header('location:return.php?status=failed'); }
}

if(isset($_POST['updatereturn'])){
    $idreturn   = $_POST['idreturn'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $customer   = mysqli_real_escape_string($conn, $_POST['customer']);
    $update = mysqli_query($conn, "update returnbarang set keterangan='$keterangan', customer='$customer' where idreturn='$idreturn'");
    if($update){ header('location:return.php?status=success'); } else { header('location:return.php?status=failed'); }
}

if(isset($_POST['hapusreturn'])){
    $idreturn = $_POST['idreturn'];
    $idbarang = $_POST['idbarang'];
    $qty      = (int)$_POST['qty'];
    $getdatastok = mysqli_query($conn, "select * from stok where idbarang='$idbarang'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];
    $selisih = $stok - $qty;

    $update = mysqli_query($conn, "update stok set stok='$selisih' where idbarang='$idbarang'");
    $hapusdata = mysqli_query($conn, "delete from returnbarang where idreturn='$idreturn'");
    if($update && $hapusdata){ header('location:return.php?status=success'); } else { header('location:return.php?status=failed'); }
}

if(isset($_POST['addrequest'])){
    $barangnya  = $_POST['barangnya'];
    $penerima   = mysqli_real_escape_string($conn, $_POST['penerima']);
    $qty        = (int)$_POST['qty'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $cekstoksekarang = mysqli_query($conn, "select * from stok where idbarang='$barangnya'");
    $ambildatanya = mysqli_fetch_array($cekstoksekarang);
    $stoksekarang = $ambildatanya['stok'];

    if($stoksekarang >= $qty){
        $kurangistok = $stoksekarang - $qty;
        $addtorequest = mysqli_query($conn, "insert into requestbarang (idbarang, keterangan, qty, penerima) values('$barangnya','$keterangan','$qty','$penerima')");
        $updatestok = mysqli_query($conn, "update stok set stok='$kurangistok' where idbarang='$barangnya'");
        if($addtorequest && $updatestok){ header('location:request.php?status=success'); } else { header('location:request.php?status=failed'); }
    } else { header('location:request.php?status=gagal_stok_kurang'); }
}

if(isset($_POST['updaterequest'])){
    $idrequest  = $_POST['idrequest'];
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $penerima   = mysqli_real_escape_string($conn, $_POST['penerima']);
    $update = mysqli_query($conn, "update requestbarang set keterangan='$keterangan', penerima='$penerima' where idrequest='$idrequest'");
    if($update){ header('location:request.php?status=success'); } else { header('location:request.php?status=failed'); }
}

if(isset($_POST['hapusrequest'])){
    $idrequest = $_POST['idrequest'];
    $idbarang  = $_POST['idbarang'];
    $qty       = (int)$_POST['qty'];
    $getdatastok = mysqli_query($conn, "select * from stok where idbarang='$idbarang'");
    $data = mysqli_fetch_array($getdatastok);
    $stok = $data['stok'];
    $selisih = $stok + $qty;

    $update = mysqli_query($conn, "update stok set stok='$selisih' where idbarang='$idbarang'");
    $hapusdata = mysqli_query($conn, "delete from requestbarang where idrequest='$idrequest'");
    if($update && $hapusdata){ header('location:request.php?status=success'); } else { header('location:request.php?status=failed'); }
}

// ==========================================================================
// FUNGSI 6: FITUR RESET PASSWORD
// ==========================================================================

// 6.1: PERMINTAAN RESET (Kirim Email)
// Dipanggil dari forgot.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(isset($_POST['reset_request'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Cek apakah email terdaftar
    $check = mysqli_query($conn, "SELECT * FROM login WHERE email='$email'");
    if(mysqli_num_rows($check) > 0){
        
        // Memanggil Library PHPMailer hanya saat dibutuhkan
        // Pastikan folder PHPMailer ada di direktori yang sama dengan function.php
        if(file_exists('PHPMailer/PHPMailer.php')){
            require 'PHPMailer/Exception.php';
            require 'PHPMailer/PHPMailer.php';
            require 'PHPMailer/SMTP.php';
        } else {
            die('Error: Folder Library PHPMailer tidak ditemukan!');
        }
        
        $token = bin2hex(random_bytes(50)); 
        $expDate = date("Y-m-d H:i:s", strtotime('+1 hour')); 

        // Bersihkan token lama
        mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");

        // Simpan token baru
        $query = "INSERT INTO password_resets (email, token, expDate) VALUES ('$email', '$token', '$expDate')";
        mysqli_query($conn, $query);

        // Link Reset
        // GANTI 'localhost/toko' dengan domain asli Anda saat sudah online
        $link = "http://localhost/stokbarang//reset.php?key=".$email."&token=".$token;

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'khoirulumam1217@gmail.com'; 
            
            // PASSWORD APLIKASI (Gunakan App Password Google Anda di sini)
            $mail->Password   = 'poer skym mifx vtld'; 
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('khoirulumam1217@gmail.com', 'Admin Inventory');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password - Inventory System';
            $mail->Body    = 'Klik link ini untuk reset password Anda: <a href="'.$link.'">Reset Password</a>. Link berlaku 1 jam.';

            $mail->send();
            echo "<script>alert('Link reset berhasil dikirim ke email!'); window.location='forgot.php';</script>";
        } catch (Exception $e) {
            echo "<script>alert('Gagal mengirim email: {$mail->ErrorInfo}'); window.location='forgot.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!'); window.location='forgot.php';</script>";
    }
}

// 6.2: PROSES SIMPAN PASSWORD BARU
// Dipanggil dari reset.php
if(isset($_POST['konfirmasi_password_baru'])){
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $token = mysqli_real_escape_string($conn, $_POST['token']);
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    // Cek Token lagi untuk keamanan ganda
    $curDate = date("Y-m-d H:i:s");
    $checkToken = mysqli_query($conn, "SELECT * FROM password_resets WHERE email='$email' AND token='$token' AND expDate >= '$curDate'");
    
    if(mysqli_num_rows($checkToken) > 0){
        if($pass1 === $pass2){
            $new_password = password_hash($pass1, PASSWORD_DEFAULT);
            
            // Update tabel login
            $update = mysqli_query($conn, "UPDATE login SET password='$new_password' WHERE email='$email'");
            
            if($update){
                // Hapus token bekas
                mysqli_query($conn, "DELETE FROM password_resets WHERE email='$email'");
                echo "<script>alert('Password berhasil diubah! Silahkan Login.'); window.location='login.php';</script>";
            } else {
                echo "<script>alert('Gagal update database.'); window.location='login.php';</script>";
            }
        } else {
            echo "<script>alert('Password konfirmasi tidak sama!'); history.back();</script>";
        }
    } else {
        echo "<script>alert('Token kedaluwarsa atau tidak valid!'); window.location='login.php';</script>";
    }
}
?>