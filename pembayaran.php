<?php
    include "koneksi.php"; //mengkoneksikan dengan database
    include "function/function.php"; //memanggil function.php 
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Pembayaran</title>
  <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- CSS here -->
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/owl.carousel.min.css">
  <link rel="stylesheet" href="css/magnific-popup.css">
  <link rel="stylesheet" href="css/font-awesome.min.css">
  <link rel="stylesheet" href="css/themify-icons.css">
  <link rel="stylesheet" href="css/nice-select.css">
  <link rel="stylesheet" href="css/flaticon.css">
  <link rel="stylesheet" href="css/gijgo.css">
  <link rel="stylesheet" href="css/animate.css">
  <link rel="stylesheet" href="css/slicknav.css">
  <link rel="stylesheet" href="css/style.css">
  <!-- <link rel="stylesheet" href="css/responsive.css"> -->
  <style type="text/css">
    body {
      background: #ffdb13 !important;
    }

    /* Adding !important forces the browser to overwrite the default style applied by Bootstrap */
  </style>

</head>

<body class="bg-gradient-warning">
  <header>
    <div class="header-area ">
      <div id="sticky-header" class="main-header-area">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-xl-3 col-lg-3">
              <div class="logo">
                <a href="index.php">
                  <h3>Chetak's</h3>
                </a>
              </div>
            </div>
            <div class="col-xl-9 col-lg-9">
              <div class="main-menu  d-none d-lg-block">
                <nav>
                  <ul id="navigation">
                    <li><a href="index.php">home</a></li>
                    <li><a href="product.php">product</a></li>
                    <li><a href="pembayaran.php">pembayaran</a></li>
                    <li><a href="contact.php">contact</a></li>
                    <li><a href="about.php">about</a></li>
                  </ul>
                </nav>
              </div>
            </div>
            <div class="col-12">
              <div class="mobile_menu d-block d-lg-none"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  <div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body">
        <!-- Nested Row within Card Body -->
        <div class="row justify-content-md-center">
          <div class="col-md-12 col-lg-6">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Pembayaran</h1>
              </div>
              <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <input type="text" class="form-control form-control-user" id="nama" name="nama" placeholder="Nama Customer" required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control form-control-user" id="idorder" name="idorder"
                    placeholder="ID Order (Masukkan nomor ID Order anda)" required>
                </div>
                <div class="form-group">
                  <input type="number" class="form-control form-control-user" id="jumlah" name="jumlah"
                    placeholder="Jumlah Yang Dibayarkan" required>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="bukti" id="bukti" required>
                      <label class="custom-file-label" for="bukti">Masukkan Bukti Pembayaran...</label>
                    </div>
                  </div>
                </div>
                <button type="submit" class="btn boxed-btn3 btn-user btn-block" name="kirimpembayaran" id="kirimpembayaran">Kirim</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>