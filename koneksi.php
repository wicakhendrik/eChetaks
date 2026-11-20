<?php
	// Konfigurasi database. Bisa di-override lewat environment variable
	// (DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS) -> lihat DEPLOY.md.
	// Default di bawah = setting lokal (Laragon/XAMPP).
	$dbHost = getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost';
	$dbPort = getenv('DB_PORT') !== false ? getenv('DB_PORT') : '3306';
	$dbName = getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'chetaks';
	$dbUser = getenv('DB_USER') !== false ? getenv('DB_USER') : 'root';
	$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

	try
	{
	$db = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass); //mengkoneksikan dengan database

  	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (PDOException $e)
	{
		// tampilkan pesan kesalahan jika koneksi gagal
		echo "Koneksi atau query bermasalah: " . $e->getMessage() . "<br/>";
		die();
	}
?>
