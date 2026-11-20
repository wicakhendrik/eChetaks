<?php
// Hanya admin yang sudah login yang boleh menjalankan aksi ini.
session_start();
if (!isset($_SESSION['login'])) { header('Location: ../admin/login.php'); exit(); }
	include "../koneksi.php";
	$id_produk = $_GET['id'];
	$query=$db->prepare("DELETE FROM produk where id_produk=$id_produk");
	$query->execute();

	header('location: ../admin/daftarproduk.php');
?>