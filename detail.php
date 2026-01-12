<?php
require 'function.php';
require 'cek.php';

// Mendapatkan ID barang dari URL
$idbarang = $_GET['id'];

// Mengambil data barang berdasarkan ID
$get = mysqli_query($conn, "SELECT * FROM stok WHERE idbarang='$idbarang'");
$data = mysqli_fetch_array($get);

if(!$data){
    echo "<script>alert('Data tidak ditemukan!'); window.location.href='index.php';</script>";
    exit;
}

// Variabel data barang
$namabarang = $data['namabarang'];
$deskripsi  = $data['deskripsi'];
$stok       = $data['stok'];
$gambar     = $data['image'];

// Handling Gambar
if ($gambar == null || $gambar == "" || $gambar == "No Photo") {
    $img_src = "https://dummyimage.com/300x300/dee2e6/6c757d.jpg&text=No+Image";
} else {
    $img_src = "images/" . $gambar;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Detail Barang - Toko Cahaya Subur</title>
    
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    
    <style>
        /* STYLE GLOBAL (Sama dengan index.php) */
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }
        
        .sb-sidenav {
            background: linear-gradient(180deg, #2c3e50 0%, #000000 100%) !important;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }
        .sb-sidenav-menu-heading { color: #adb5bd !important; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px; }
        .nav-link { color: rgba(255,255,255,0.7) !important; transition: 0.3s; }
        .nav-link:hover { color: #fff !important; background: rgba(255,255,255,0.1); border-radius: 5px; margin: 0 10px; }
        .sb-topnav { background-color: #fff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .navbar-brand { color: #2c3e50 !important; font-weight: 700; }
        .btn-link { color: #2c3e50 !important; }

        /* Card Styles */
        .card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 15px 20px; border-radius: 12px 12px 0 0 !important; }
        
        /* Detail Page Specific */
        .detail-img {
            width: 100%;
            height: 300px;
            object-fit: cover; /* Agar gambar rapi mengisi kotak */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .badge-stok { font-size: 1rem; padding: 10px 20px; border-radius: 30px; }
        .table-sm td, .table-sm th { font-size: 0.9rem; }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-light">
        <a class="navbar-brand pl-3" href="index.php"><i class="fas fa-warehouse mr-2"></i>Cahaya Subur</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    </nav>
    
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Utama</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div> Stok Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div> Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-up"></i></div> Barang Keluar
                        </a>
                        <div class="sb-sidenav-menu-heading">Pengaturan</div>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div> Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4 mb-4 text-gray-800">Detail Barang</h1>

                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body text-center">
                                    <img src="<?=$img_src;?>" class="detail-img mb-3" alt="Gambar Barang">
                                    <hr>
                                    <h5 class="text-primary font-weight-bold"><?=$namabarang;?></h5>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8 mb-4">
                            <div class="card h-100">
                                <div class="card-header">
                                    Informasi Detail
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Nama Barang</th>
                                            <td>: <?=$namabarang;?></td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi</th>
                                            <td>: <?=$deskripsi;?></td>
                                        </tr>
                                        <tr>
                                            <th>Stok Saat Ini</th>
                                            <td>: 
                                                <?php if($stok <= 1): ?>
                                                    <span class="badge badge-danger badge-stok">Habis / Kritis (<?=$stok;?>)</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success badge-stok"><?=$stok;?> Pcs</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>
                                    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-arrow-down mr-1"></i> Riwayat Barang Masuk
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Keterangan</th>
                                                    <th>Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ambildatamasuk = mysqli_query($conn, "SELECT * FROM masuk WHERE idbarang='$idbarang' ORDER BY tanggal DESC LIMIT 10");
                                                if(mysqli_num_rows($ambildatamasuk) > 0){
                                                    while($fetch=mysqli_fetch_array($ambildatamasuk)){
                                                ?>
                                                <tr>
                                                    <td><?=date('d-m-Y', strtotime($fetch['tanggal']));?></td>
                                                    <td><?=$fetch['keterangan'];?></td>
                                                    <td>+<?=$fetch['qty'];?></td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada riwayat masuk</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">
                                    <i class="fas fa-arrow-up mr-1"></i> Riwayat Barang Keluar
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Tanggal</th>
                                                    <th>Penerima</th>
                                                    <th>Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $ambildatakeluar = mysqli_query($conn, "SELECT * FROM keluar WHERE idbarang='$idbarang' ORDER BY tanggal DESC LIMIT 10");
                                                if(mysqli_num_rows($ambildatakeluar) > 0){
                                                    while($fetch=mysqli_fetch_array($ambildatakeluar)){
                                                ?>
                                                <tr>
                                                    <td><?=date('d-m-Y', strtotime($fetch['tanggal']));?></td>
                                                    <td><?=$fetch['penerima'];?></td>
                                                    <td>-<?=$fetch['qty'];?></td>
                                                </tr>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<tr><td colspan='3' class='text-center text-muted'>Belum ada riwayat keluar</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
            </main>
            
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Toko Cahaya Subur <?= date('Y'); ?></div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>