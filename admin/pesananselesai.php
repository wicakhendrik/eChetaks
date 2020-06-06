<?php

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