<?php
require 'function.php';
require 'cek.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title> Dashboard Stok Barang - Toko Cahaya Subur</title>
    
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    
    <style>
        /* GLOBAL STYLES */
        body { font-family: 'Poppins', sans-serif; background-color: #969eaaff; }
        .sb-sidenav {
            background: linear-gradient(180deg, #2c3e50 0%, #000000 100%) !important;
            box-shadow: 2px 0 10px rgba(0,0,0,0.2);
        }
        .sb-sidenav-menu-heading { color: #adb5bd !important; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px; }
        .nav-link { color: rgba(255,255,255,0.7) !important; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: #fff !important; background: rgba(255,255,255,0.1); border-radius: 5px; margin: 0 10px; }
        .sb-topnav { background-color: #fff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .navbar-brand { color: #2c3e50 !important; font-weight: 700; }
        .btn-link { color: #2c3e50 !important; }
        .card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.1); }
        .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 15px 20px; border-radius: 12px 12px 0 0 !important; }
        .bg-gradient-primary { background: linear-gradient(45deg, #4e73df, #224abe); color: white; }
        .bg-gradient-success { background: linear-gradient(45deg, #1cc88a, #13855c); color: white; }
        .bg-gradient-warning { background: linear-gradient(45deg, #f6c23e, #dda20a); color: white; }
        .bg-gradient-danger { background: linear-gradient(45deg, #e74a3b, #be2617); color: white; }
        .zoomable { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; transition: transform 0.3s; cursor: pointer; }
        .zoomable:hover { transform: scale(3.5); z-index: 999; box-shadow: 0 10px 20px rgba(0,0,0,0.3); position: absolute; border: 2px solid white; }
        .table thead th { border-top: none; background: #f8f9fc; color: #5a5c69; font-weight: 600; font-size: 0.9rem; }
        .table-hover tbody tr:hover { background-color: #424242ff; }
        .btn-rounded { border-radius: 50px; padding: 5px 15px; font-size: 0.85rem; }
        .detail-link { color: #4e73df; text-decoration: none; font-weight: 600; transition: 0.2s; cursor: pointer; }
        .detail-link:hover { color: #224abe; text-decoration: underline; }
        .modal-content {
            background-color: #ffffff !important; opacity: 1 !important; border: none;
            box-shadow: 0 5px 25px rgba(0,0,0,0.3); color: #333 !important;
        }
        .modal-header { background-color: #f8f9fc; border-bottom: 1px solid #e3e6f0; }
        .modal-footer { background-color: #f8f9fc; border-top: 1px solid #e3e6f0; }
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
                        <a class="nav-link active" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div> Stok Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div> Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-up"></i></div> Barang Keluar
                        </a>
                        <a class="nav-link" href="return.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-undo-alt"></i></div> Return Barang
                        </a>
                        <a class="nav-link" href="request.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div> Request Barang
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
                    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Stok Barang</h1>
                        <a href="exportbarang.php" class="btn btn-sm btn-success shadow-sm btn-rounded"><i class="fas fa-download fa-sm text-white-50"></i> Export Report</a>
                    </div>

                    <?php
                        $data_stok = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stok"));
                        $data_masuk = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM masuk"));
                        $data_keluar = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM keluar"));
                        $data_habis = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stok WHERE stok <= 1"));
                    ?>
                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-gradient-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Jenis Barang</div>
                                            <div class="h5 mb-0 font-weight-bold"><?= $data_stok; ?> Items</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-box fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-gradient-success h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">Transaksi Masuk</div>
                                            <div class="h5 mb-0 font-weight-bold"><?= $data_masuk; ?> Kali</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-arrow-down fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-gradient-warning h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">Transaksi Keluar</div>
                                            <div class="h5 mb-0 font-weight-bold"><?= $data_keluar; ?> Kali</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-arrow-up fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card bg-gradient-danger h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1">Stok Menipis</div>
                                            <div class="h5 mb-0 font-weight-bold"><?= $data_habis; ?> Barang</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if(isset($_GET['status'])): ?>
                        <?php if($_GET['status'] == 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Berhasil!</strong> Data berhasil disimpan.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table mr-1"></i> Data Stok Barang</h6>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm btn-rounded shadow-sm" data-toggle="modal" data-target="#myModal">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </button>
                                <a href="exportlaporan-stok.php" class="btn btn-info btn-sm btn-rounded shadow-sm">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            
                             <?php
                                $ambildatastok = mysqli_query($conn, "SELECT * FROM stok WHERE stok <= 1");
                                while($fetch = mysqli_fetch_array($ambildatastok)){
                                    $barang = $fetch['namabarang'];
                            ?>
                            <div class="alert alert-danger border-left-danger alert-dismissible fade show" role="alert">
                                <strong><i class="fas fa-bell"></i> Perhatian!</strong> Stok <u><?=$barang;?></u> sudah habis atau kritis.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <?php } ?>

                            <?php
                                // PERUBAHAN DISINI: ORDER BY idbarang ASC
                                $ambilsemuadatastok = mysqli_query($conn, "SELECT * FROM stok ORDER BY idbarang ASC");
                                $data_stok_array = [];
                                while($row = mysqli_fetch_array($ambilsemuadatastok)){
                                    $data_stok_array[] = $row;
                                }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Gambar</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Jenis</th> 
                                            <th>Harga</th>
                                            <th>Stok</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach($data_stok_array as $data):
                                            $namabarang = $data['namabarang'];
                                            $deskripsi = $data['deskripsi'];
                                            $stok = $data['stok'];
                                            $idbarang = $data['idbarang'];
                                            $gambar = $data['image'];
                                            
                                            $kode_barang = isset($data['kode_barang']) ? $data['kode_barang'] : '-';
                                            $harga = isset($data['harga']) ? $data['harga'] : 0;
                                            $harga_view = "Rp " . number_format($harga,0,',','.');

                                            $image = ($gambar == null || $gambar == "" || $gambar == "No Photo") ? 
                                                '<img src="https://dummyimage.com/100x100/dee2e6/6c757d.jpg&text=No+Img" class="zoomable">' : 
                                                '<img src="images/'.$gambar.'" class="zoomable">';
                                        ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td class="text-center"><?=$image;?></td>
                                            <td><?=$kode_barang;?></td>
                                            <td>
                                                <a href="#" class="detail-link" data-toggle="modal" data-target="#detail<?=$idbarang;?>">
                                                    <?=$namabarang;?> <i class="fas fa-external-link-alt fa-xs ml-1 text-gray-400"></i>
                                                </a>
                                            </td>
                                            <td><?=$deskripsi;?></td>
                                            <td><?=$harga_view;?></td>
                                            <td>
                                                <?php if($stok <= 1): ?>
                                                    <span class="badge badge-danger px-3 py-2">Habis (<?=$stok;?>)</span>
                                                <?php else: ?>
                                                    <span class="badge badge-primary px-3 py-2"><?=$stok;?> pcs</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm btn-rounded" data-toggle="modal" data-target="#edit<?=$idbarang;?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-rounded" data-toggle="modal" data-target="#delete<?=$idbarang;?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
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

    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label>Kode Barang</label>
                        <input type="text" name="kode_barang" placeholder="Contoh: BRG001" class="form-control mb-3" required>

                        <label>Nama Barang</label>
                        <input type="text" name="namabarang" placeholder="Contoh: Televisi" class="form-control mb-3" required>
                        
                        <label>Jenis</label>
                        <input type="text" name="deskripsi" placeholder="Masukkan Jenis/Kategori Barang" class="form-control mb-3" required>
                        
                        <label>Harga (Rp)</label>
                        <input type="number" name="harga" placeholder="Harga satuan" class="form-control mb-3" required>

                        <label>Stok Awal</label>
                        <input type="number" name="stok" placeholder="Jumlah stok" class="form-control mb-3" required min="0">

                        <label>Gambar</label>
                        <input type="file" name="filegambar" class="form-control mb-3" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="addnewbarang">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php 
    foreach($data_stok_array as $data): 
        $namabarang = $data['namabarang'];
        $deskripsi = $data['deskripsi'];
        $stok = $data['stok'];
        $idbarang = $data['idbarang'];
        $gambar = $data['image'];
        
        $kode_barang = isset($data['kode_barang']) ? $data['kode_barang'] : '-';
        $harga = isset($data['harga']) ? $data['harga'] : 0;
        $harga_view = "Rp " . number_format($harga,0,',','.');

        $imageLarge = ($gambar == null || $gambar == "" || $gambar == "No Photo") ? 
        '<img src="https://dummyimage.com/400x400/dee2e6/6c757d.jpg&text=No+Img" class="img-fluid rounded">' : 
        '<img src="images/'.$gambar.'" class="img-fluid rounded" style="width: 100%; object-fit: contain; max-height: 300px;">';
    ?>

        <div class="modal fade" id="detail<?=$idbarang;?>">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Detail Barang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-5 mb-3 text-center">
                                <?=$imageLarge;?>
                            </div>
                            <div class="col-md-7">
                                <h3 class="font-weight-bold text-primary"><?=$namabarang;?></h3>
                                <hr>
                                <h6 class="font-weight-bold">Kode Barang:</h6>
                                <p class="text-muted"><?=$kode_barang;?></p>
                                
                                <h6 class="font-weight-bold">Jenis:</h6>
                                <p class="text-muted"><?=$deskripsi;?></p>
                                
                                <h6 class="font-weight-bold">Harga:</h6>
                                <p class="text-success font-weight-bold" style="font-size: 1.2rem;"><?=$harga_view;?></p>

                                <h6 class="font-weight-bold">Status Stok:</h6>
                                <?php if($stok <= 1): ?>
                                    <div class="alert alert-danger p-2 d-inline-block">
                                        <i class="fas fa-exclamation-circle"></i> Stok Menipis/Habis: <b><?=$stok;?></b>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-success p-2 d-inline-block">
                                        <i class="fas fa-check-circle"></i> Stok Aman: <b><?=$stok;?></b>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="edit<?=$idbarang;?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Barang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group text-left">
                                <label>Kode Barang</label>
                                <input type="text" name="kode_barang" value="<?=$kode_barang;?>" class="form-control" required>
                            </div>

                            <div class="form-group text-left">
                                <label>Nama Barang</label>
                                <input type="text" name="namabarang" value="<?=$namabarang;?>" class="form-control" required>
                            </div>
                            <div class="form-group text-left">
                                <label>Jenis</label>
                                <input type="text" name="deskripsi" value="<?=$deskripsi;?>" class="form-control" required>
                            </div>
                            
                            <div class="form-group text-left">
                                <label>Harga (Rp)</label>
                                <input type="number" name="harga" value="<?=$harga;?>" class="form-control" required>
                            </div>

                            <div class="form-group text-left">
                                <label class="small text-muted">Ganti Gambar (Opsional)</label>
                                <input type="file" name="filegambar" class="form-control">
                            </div>
                            <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning" name="updatebarang">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete<?=$idbarang;?>">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hapus Barang</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form method="post">
                        <div class="modal-body">
                            <div class="text-center">
                                <i class="fas fa-trash fa-4x text-danger mb-3"></i>
                                <p>Apakah Anda yakin ingin menghapus barang:</p>
                                <h5 class="font-weight-bold"><?=$namabarang;?></h5>
                                <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger" name="hapusbarang">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php endforeach; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/datatables-demo.js"></script>
</body>
</html>