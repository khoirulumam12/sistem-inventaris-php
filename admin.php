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
    <title>Kelola Admin - Toko Cahaya Subur</title>
    
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
        .table thead th { border-top: none; background: #f8f9fc; color: #5a5c69; font-weight: 600; font-size: 0.9rem; }
        .btn-rounded { border-radius: 50px; padding: 5px 15px; font-size: 0.85rem; }
        .avatar-placeholder { width: 40px; height: 40px; background-color: #e2e6ea; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6c757d; }
        
        /* Modal CSS */
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
                        <a class="nav-link" href="keluar.php"><div class="sb-nav-link-icon"><i class="fas fa-arrow-circle-up"></i></div> Barang Keluar</a>
                        
                        <a class="nav-link" href="return.php"><div class="sb-nav-link-icon"><i class="fas fa-undo-alt"></i></div> Return Barang</a>
                        <a class="nav-link" href="request.php"><div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div> Request Barang</a>

                        <div class="sb-sidenav-menu-heading">Pengaturan</div>
                        <a class="nav-link active" href="admin.php"><div class="sb-nav-link-icon"><i class="fas fa-user-cog"></i></div> Kelola Admin</a>
                        
                        <a class="nav-link" href="logout.php"><div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div> Logout</a>
                    </div>
                </div>
            </nav>
        </div>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid">
                    <h1 class="mt-4 mb-4 text-gray-800">Kelola Admin</h1>
                    
                    <?php if(isset($_GET['status'])): ?>
                        <?php if($_GET['status'] == 'success'): ?>
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Berhasil!</strong> Data admin telah diperbarui.
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(isset($_GET['error'])): ?>
                         <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                <strong>Gagal!</strong> Terjadi kesalahan (Mungkin email sudah digunakan).
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                    <?php endif; ?>

                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-users-cog mr-1"></i> Daftar Pengguna</h6>
                            <button type="button" class="btn btn-primary btn-sm btn-rounded shadow-sm" data-toggle="modal" data-target="#myModal">
                                <i class="fas fa-user-plus"></i> Tambah Admin
                            </button>
                        </div>
                        <div class="card-body">
                            
                            <?php
                                $ambilsemuadataadmin = mysqli_query($conn, "SELECT * FROM login");
                                $data_admin_array = [];
                                while($row = mysqli_fetch_array($ambilsemuadataadmin)){
                                    $data_admin_array[] = $row;
                                }
                            ?>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="10%">Avatar</th>
                                            <th>Email Admin</th>
                                            <th width="20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach($data_admin_array as $data):
                                            $email = $data['email'];
                                            $iduser = $data['iduser'];
                                        ?>
                                        <tr>
                                            <td><?=$i++;?></td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <div class="avatar-placeholder">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?=htmlspecialchars($email);?></td>
                                            <td>
                                                <button type="button" class="btn btn-warning btn-sm btn-rounded" data-toggle="modal" data-target="#edit<?=$iduser;?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-rounded" data-toggle="modal" data-target="#delete<?=$iduser;?>">
                                                    <i class="fas fa-trash-alt"></i> Hapus
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
                    <h4 class="modal-title">Tambah Admin Baru</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" placeholder="contoh@email.com" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-lock"></i> Password</label>
                            <input type="password" name="password" placeholder="Password" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="addadmin">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    foreach($data_admin_array as $data):
        $email = $data['email'];
        $iduser = $data['iduser'];
    ?>
    
    <div class="modal fade" id="edit<?=$iduser;?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email Admin</label>
                            <input type="email" name="emailadmin" value="<?=$email;?>" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password Baru</label>
                            <input type="password" name="passwordbaru" class="form-control" placeholder="Kosongkan jika tidak ingin mengubah password">
                            <small class="text-muted">Isi hanya jika Anda ingin mengganti password login.</small>
                        </div>
                        <input type="hidden" name="iduser" value="<?=$iduser;?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" name="updateadmin">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete<?=$iduser;?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Hapus Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <div class="alert alert-danger">
                            Apakah Anda yakin ingin menghapus admin <strong><?=$email;?></strong>?
                            <br><small>Tindakan ini tidak dapat dibatalkan.</small>
                        </div>
                        <input type="hidden" name="iduser" value="<?=$iduser;?>">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger" name="hapusadmin">Hapus</button>
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