<?php
    session_start();
    if (!isset($_SESSION['login']))
    {
      //user akan diarahkan ke halam login jika user belum login
      header("Location: login.php");
    }
    include "../koneksi.php"; //mengkoneksikan dengan database
    include "../function/function.php";
    $query = $db->prepare("SELECT * FROM pemesanan p, jasakirim j, produk, status_pemesanan sp where p.id_produk=produk.id_produk and p.id_jasakirim = j.id_jasakirim and p.status_pemesanan = sp.id_status_pemesanan");
    $query->execute();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dasbor Admin</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
      <img src="../img/xlogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Chetak's</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview">
            <a href="index.php" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="daftarproduk.php" class="nav-link">
              <i class="nav-icon fas fa-list-ul"></i>
              <p>
                Daftar Produk
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="daftarpesanan.php" class="nav-link active">
              <i class="nav-icon fas fa-shopping-cart"></i>
              <p>
                Daftar Pesanan
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="daftarpembayaran.php" class="nav-link">
              <i class="nav-icon fas fa-receipt"></i>
              <p>
                Daftar Pembayaran
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Daftar Pesanan</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Daftar Pesanan</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Daftar Pemesanan</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0" style="height: 400px;">
                <table class="table table-head-fixed text-nowrap">
                  <thead>
                    <tr>
                      <th>ID Pemesanan</th>
                      <th>Nama Pemesan</th>
                      <th>Tanggal Pemesanan</th>
                      <th>No. Telp. Pemesan</th>
                      <th>Alamat Pemesan</th>
                      <th>Produk yang dipesan</th>
                      <th>Jumlah Pemesanan</th>
                      <th>Keterangan Tambahan</th>
                      <th>Jasa Kirim</th>
                      <th>Total Bayar</th>
                      <th>Status Pemesanan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($query as $data){ ?>
                    <tr>
                      <td><?php echo $data['id_pemesanan']; ?></td>
                      <td><?php echo $data['nama_pemesan']; ?></td>
                      <td><?php echo $data['tgl_pemesanan']; ?></td>
                      <td><?php echo $data['no_telp']; ?></td>
                      <td><?php echo $data['alamat']; ?></td>
                      <td><?php echo $data['nama_produk']; ?></td>
                      <td><?php echo $data['jumlah']; ?></td>
                      <td><?php echo $data['keterangan']; ?></td>
                      <td><?php echo $data['nama_jaskir']; ?></td>
                      <td><?php echo $data['jumlah_bayar']; ?></td>
                      <td><?php echo $data['ket']; ?></td>
                      <?php if ($data['status_pemesanan'] == 3 or $data['status_pemesanan'] == 4) { ?>
                      <td>                        
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary" disabled>Selesaikan Pesanan</button>
                        </div> 
                        <div class="btn-group">
                          <button type="button" class="btn btn-danger" disabled>Batalkan Pesanan</button>
                        </div>
                      </td>
                      <?php } ?>
                      <?php if ($data['status_pemesanan'] == 1) { ?>
                      <td>                        
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary" disabled>Selesaikan Pesanan</button>
                        </div> 
                        <div class="btn-group">
                          <a type="button" class="btn btn-danger" href="pesananbatal.php?id=<?= $data["id_pemesanan"]; ?>">Batalkan Pesanan</a>
                        </div>
                      </td>
                      <?php } ?>
                      <?php if ($data['status_pemesanan'] == 2) { ?>
                      <td>                        
                        <div class="btn-group">
                          <a type="button" class="btn btn-primary" href="pesananselesai.php?id=<?= $data["id_pemesanan"]; ?>">Selesaikan Pesanan</a>
                        </div> 
                        <div class="btn-group">
                          <button type="button" class="btn btn-danger" disabled>Batalkan Pesanan</button>
                        </div>
                      </td>
                      <?php } ?>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.0.4
    </div>
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">E-Chetak's</a>.</strong> All rights
    reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
