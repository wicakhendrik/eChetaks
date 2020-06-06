<?php
	try
	{
	$db = new PDO('mysql:host=localhost;dbname=chetaks', "root", ""); //mengkoneksikan dengan database
	
  	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (PDOException $e) 
	{
		// tampilkan pesan kesalahan jika koneksi gagal
		echo "Koneksi atau query bermasalah: " . $e->getMessage() . "<br/>";
		die();
	}
?>