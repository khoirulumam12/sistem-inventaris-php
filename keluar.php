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
    <title>Barang Keluar - Toko Cahaya Subur</title>
    
    <link href="css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #969eaaff; }
        .sb-sidenav { background: linear-gradient(180deg, #2c3e50 0%, #000000 100%) !important; box-shadow: 2px 0 10px rgba(0,0,0,0.2); }
        .sb-sidenav-menu-heading { color: #adb5bd !important; font-size: 0.8rem; font-weight: bold; letter-spacing: 1px; }
        .nav-link { color: rgba(255,255,255,0.7) !important; transition: 0.3s; }
        .nav-link:hover, .nav-link.active { color: #fff !important; background: rgba(255,255,255,0.1); border-radius: 5px; margin: 0 10px; }
        .sb-topnav { background-color: #fff !important; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .navbar-brand { color: #2c3e50 !important; font-weight: 700; }
        .card { border: none; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; }
        .card-header { background: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 15px 20px; border-radius: 12px 12px 0 0 !important; }
        .zoomable { width: 60px; height: 60px; object-fit: cover; border-radius: 8px; transition: transform 0.3s; cursor: pointer; }
        .zoomable:hover { transform: scale(3.5); z-index: 999; box-shadow: 0 10px 20px rgba(0,0,0,0.3); position: absolute; border: 2px solid white; }
        .table thead th { border-top: none; background: #f8f9fc; color: #5a5c69; font-weight: 600; font-size: 0.9rem; }
        .btn-rounded { border-radius: 50px; padding: 5px 15px; font-size: 0.85rem; }
        .modal-content { background-color: #ffffff !important; opacity: 1 !important; border: none; box-shadow: 0 5px 25px rgba(0,0,0,0.3); color: #333 !important; }
        .modal-header, .modal-footer { background-color: #f8f9fc; border-color: #e3e6f0; }
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
                        <a class="nav-link" href="index.php"><div class="sb-nav-link-icon"><i class="fas fa-boxes"></i></div> Stok Barang</a>
                        <a class="nav-link" href="masuk.php"><div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-down"></i></div> Barang Masuk</a>
                        <a class="nav-link active" href="keluar.php"><div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-up"></i></div> Barang Keluar</a>
                        <a class="nav-link" href="return.php"><div class="sb-nav-link-icon"><i class="fas fa-undo-alt"></i></div> Return Barang</a>
                        <a class="nav-link" href="request.php"><div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div> Request Barang</a>
                        <div class="sb-sidenav-menu-heading">Pengaturan</div>
                        <a class="nav-link" href="admin.php"><div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin</a>
                        <a class="nav-link" href="logout.php"><div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div> Logout</a>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4 mb-4 text-gray-800">Barang Keluar</h1>

                    <?php if(isset($_GET['status'])): ?>
                        <?php if($_GET['status'] == 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Berhasil!</strong> Data barang keluar telah disimpan.
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Gagal!</strong> Terjadi kesalahan atau stok tidak cukup.
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-table mr-1"></i> Riwayat Pengeluaran</h6>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm btn-rounded shadow-sm" data-toggle="modal" data-target="#myModal">
                                    <i class="fas fa-plus"></i> Tambah Keluar
                                </button>
                                <a href="exportkeluar.php" class="btn btn-success btn-sm btn-rounded shadow-sm">
                                    <i class="fas fa-file-excel"></i> Export
                                </a>
                                <a href="exportlaporan-keluar.php" class="btn btn-warning btn-sm btn-rounded shadow-sm">
                                    <i class="fas fa-print"></i> Laporan
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            
                            <form method="post" class="form-inline mb-4">
                                <div class="form-group mr-2">
                                    <input type="date" name="tgl_mulai" class="form-control" placeholder="Tanggal Mulai" required>
                                </div>
                                <div class="form-group mr-2">
                                    <input type="date" name="tgl_selesai" class="form-control" placeholder="Tanggal Selesai" required>
                                </div>
                                <button type="submit" name="filter_tgl" class="btn btn-info">Filter</button>
                                <a href="keluar.php" class="btn btn-secondary ml-2">Reset</a>
                            </form>

                            <?php
                                // PERUBAHAN DISINI: ORDER BY idkeluar ASC (Input baru di bawah)
                                if(isset($_POST['filter_tgl'])){
                                    $mulai = $_POST['tgl_mulai'];
                                    $selesai = $_POST['tgl_selesai'];
                                    
                                    if($mulai!=null || $selesai!=null){
                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT k.*, s.namabarang, s.image, s.kode_barang, s.harga FROM keluar k JOIN stok s ON s.idbarang = k.idbarang WHERE DATE(k.tanggal) BETWEEN '$mulai' AND '$selesai' ORDER BY k.idkeluar ASC");
                                    } else {
                                        $ambilsemuadatastok = mysqli_query($conn, "SELECT k.*, s.namabarang, s.image, s.kode_barang, s.harga FROM keluar k JOIN stok s ON s.idbarang = k.idbarang ORDER BY k.idkeluar ASC");
                                    }
                                } else {
                                    $ambilsemuadatastok = mysqli_query($conn, "SELECT k.*, s.namabarang, s.image, s.kode_barang, s.harga FROM keluar k JOIN stok s ON s.idbarang = k.idbarang ORDER BY k.idkeluar ASC");
                                }

                                $data_keluar_array = [];
                                while($row = mysqli_fetch_array($ambilsemuadatastok)){
                                    $data_keluar_array[] = $row;
                                }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Gambar</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Harga (Satuan)</th>
                                            <th>Jumlah</th>
                                            <th>Penerima</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach($data_keluar_array as $data):
                                            $idbarang = $data['idbarang'];
                                            $idkeluar = $data['idkeluar'];
                                            $tanggal = $data['tanggal'];
                                            $namabarang = $data['namabarang'];
                                            $qty = $data['qty'];
                                            $penerima = $data['penerima'];
                                            $gambar = $data['image'];
                                            
                                            $kode_barang = isset($data['kode_barang']) ? $data['kode_barang'] : '-';
                                            $harga = isset($data['harga']) ? $data['harga'] : 0;
                                            $harga_view = "Rp " . number_format($harga,0,',','.');

                                            $image = ($gambar == null || $gambar == "" || $gambar == "No Photo") ? 
                                            '<span class="badge badge-secondary">No Image</span>' : 
                                            '<img src="images/'.$gambar.'" class="zoomable">';
                                        ?>
                                        <tr>
                                            <td><?= date('d-m-Y H:i', strtotime($tanggal)); ?></td>
                                            <td class="text-center"><?=$image;?></td>
                                            <td><?=$kode_barang;?></td>
                                            <td style="font-weight: 500;"><?=$namabarang;?></td>
                                            <td><?=$harga_view;?></td>
                                            <td><span class="badge badge-danger">-<?=$qty;?></span></td>
                                            <td><?=$penerima;?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm btn-rounded" data-toggle="modal" data-target="#edit<?=$idkeluar;?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-rounded" data-toggle="modal" data-target="#delete<?=$idkeluar;?>">
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
                    <div class="text-muted small">Copyright &copy; Toko Cahaya Subur <?= date('Y'); ?></div>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <label>Pilih Barang</label>
                        <select name="barangnya" class="form-control mb-3" required>
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            <?php
                            $ambilsemuadatanya = mysqli_query($conn, "SELECT * FROM stok ORDER BY namabarang ASC");
                            while($fetcharray = mysqli_fetch_array($ambilsemuadatanya)) {
                                $namabarangnya = $fetcharray['namabarang'];
                                $idbarangnya = $fetcharray['idbarang'];
                                $stoknya = $fetcharray['stok'];
                                $kodenya = isset($fetcharray['kode_barang']) ? $fetcharray['kode_barang'] : '-';
                                $harganya = isset($fetcharray['harga']) ? "Rp ".number_format($fetcharray['harga'],0,',','.') : 'Rp 0';
                            ?>
                            <option value="<?=$idbarangnya;?>">[<?=$kodenya;?>] <?=$namabarangnya;?> - <?=$harganya;?> (Stok: <?=$stoknya;?>)</option>
                            <?php } ?>
                        </select>
                        <label>Jumlah</label>
                        <input type="number" name="qty" placeholder="Quantity" class="form-control mb-3" required min="1">
                        <label>Penerima</label>
                        <input type="text" name="penerima" placeholder="Penerima / Tujuan" class="form-control mb-3" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="addbarangkeluar">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    foreach($data_keluar_array as $data):
        $idbarang = $data['idbarang'];
        $idkeluar = $data['idkeluar'];
        $namabarang = $data['namabarang'];
        $qty = $data['qty'];
        $penerima = $data['penerima'];
    ?>
    
    <div class="modal fade" id="edit<?=$idkeluar;?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Barang Keluar</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="text" value="<?=$namabarang;?>" class="form-control mb-3" disabled>
                        <label>Penerima</label>
                        <input type="text" name="penerima" value="<?=$penerima;?>" class="form-control mb-3" required>
                        <label>Jumlah</label>
                        <input type="number" name="qty" value="<?=$qty;?>" class="form-control mb-3" required min="1">
                        <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                        <input type="hidden" name="idkeluar" value="<?=$idkeluar;?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="updatebarangkeluar">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete<?=$idkeluar;?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Data</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="text-center">
                            <i class="fas fa-trash fa-4x text-danger mb-3"></i>
                            <p>Apakah Anda yakin ingin menghapus data keluar:</p>
                            <h5 class="font-weight-bold"><?=$namabarang;?></h5>
                            <p>Sejumlah: <b><?=$qty;?></b></p>
                            <p class="small text-success">Stok akan otomatis bertambah kembali.</p>
                            
                            <input type="hidden" name="idbarang" value="<?=$idbarang;?>">
                            <input type="hidden" name="idkeluar" value="<?=$idkeluar;?>">
                            <input type="hidden" name="qty" value="<?=$qty;?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" name="hapusbarangkeluar">Hapus</button>
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