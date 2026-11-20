<?php
// Hanya admin yang sudah login yang boleh menjalankan aksi ini.
session_start();
if (!isset($_SESSION['login'])) { header('Location: ../admin/login.php'); exit(); }
	include "../koneksi.php";
	$id_produk = (int) ($_GET['id'] ?? 0);
	$query=$db->prepare("DELETE FROM produk where id_produk = :id_produk"); $query->bindValue(':id_produk', $id_produk, PDO::PARAM_INT);
	try { $query->execute(); } catch (PDOException $e) { /* produk masih dipakai pesanan (foreign key) -> tidak dihapus */ }

	header('location: ../admin/daftarproduk.php');
?>