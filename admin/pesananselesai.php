<?php
// Hanya admin yang sudah login yang boleh menjalankan aksi ini.
session_start();
if (!isset($_SESSION['login'])) { header('Location: login.php'); exit(); }

require '../function/function.php';
$id = $_GET["id"];

if ( pesananselesai($id) > 0) {
    echo "
        <script>
            alert('pesanan selesai!');
            document.location.href = 'daftarpesanan.php';
        </script>
    "; 
} else {
    echo "
        <script>
            alert('pesanan belum selesai!');
            document.location.href = 'daftarpesanan.php';
        </script>
    ";
}
?>