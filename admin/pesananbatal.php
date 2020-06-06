<?php

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