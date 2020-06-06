<?php
	include "../koneksi.php";
	$id_produk = $_GET['id'];
	$query=$db->prepare("DELETE FROM produk where id_produk=$id_produk");
	$query->execute();

	header('location: ../admin/daftarproduk.php');
?>