<?php

require '../function/function.php';
$id_bayar = $_GET["id_bayar"];
$id_pesan = $_GET["id_pesan"];

if ( terimabayar($id_bayar, $id_pesan) > 0) {
    echo "
        <script>
            alert('pembayaran berhasil diterima!');
            document.location.href = 'daftarpembayaran.php';
        </script>
    "; 
} else {
    echo "
        <script>
            alert('pembayaran tidak berhasil diterima!');
            document.location.href = 'daftarpembayaran.php';
        </script>
    ";
}