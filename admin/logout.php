<?php 
	session_start(); //start session
	unset($_SESSION['login']); //unset session login
	header("Location: login.php"); //mengalihkan ke halaman login
	exit();
 ?>
