<?php
// Hanya admin yang sudah login yang boleh menjalankan aksi ini.
session_start();
if (!isset($_SESSION['login'])) { header('Location: login.php'); exit(); }

require '../function/function.php';
$id = $_GET["id"];

if ( pesananbatal($id) > 0) {
    echo "
        <script>
            alert('pesanan dibatalkan!');
            document.location.href = 'daftarpesanan.php';
        </script>
    "; 
} else {
    echo "
        <script>
            alert('pesanan tidak dibatalkan!');
            document.location.href = 'daftarpesanan.php';
        </script>
    ";
}