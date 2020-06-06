<?php
	try
	{
	$db = new PDO('mysql:host=localhost;dbname=chetaks', "root", ""); //mengkonaksikan dengan database
	
  	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}
	catch (PDOException $e) 
	{
		// tampilkan pesan kesalahan jika koneksi gagal
		echo "Koneksi atau query bermasalah: " . $e->getMessage() . "<br/>";
		die();
	}
	include 'validate.php';

	function uploadgambar() { 

		$namaFile = $_FILES['gambarproduk']['name'];
		$error = $_FILES['gambarproduk']['error'];
		$tmpName = $_FILES['gambarproduk']['tmp_name'];

		// cek apakah yang diupload adalah gambar
		$ekstensigambarValid = ['jpg', 'jpeg', 'png'];
		$ekstensiGambar = explode('.', $namaFile);
		$ekstensiGambar = strtolower(end($ekstensiGambar));
		if ( !in_array($ekstensiGambar, $ekstensigambarValid) ) {
			echo "<script>
					alert('yang anda upload bukan gambar');
				</script>";
			return false; 
		}

		//lolos pengecekan, gambar siap diupload
		move_uploaded_file($tmpName, '../img/product/' . $namaFile);

		return $namaFile;
	}

	function uploaddesain() { 

		$namaFile = $_FILES['desain']['name'];
		$error = $_FILES['desain']['error'];
		$tmpName = $_FILES['desain']['tmp_name'];

		// cek apakah yang diupload adalah gambar
		$ekstensigambarValid = ['jpg', 'jpeg', 'png'];
		$ekstensiGambar = explode('.', $namaFile);
		$ekstensiGambar = strtolower(end($ekstensiGambar));
		if ( !in_array($ekstensiGambar, $ekstensigambarValid) ) {
			echo "<script>
					alert('yang anda upload bukan gambar');
				</script>";
			return false; 
		}

		

		//lolos pengecekan, gambar siap diupload
		move_uploaded_file($tmpName, 'desain/' . $namaFile);

		return $namaFile;
	}

	function uploadpembayaran() { 

		$namaFile = $_FILES['bukti']['name'];
		$error = $_FILES['bukti']['error'];
		$tmpName = $_FILES['bukti']['tmp_name'];

		// cek apakah yang diupload adalah gambar
		$ekstensigambarValid = ['jpg', 'jpeg', 'png'];
		$ekstensiGambar = explode('.', $namaFile);
		$ekstensiGambar = strtolower(end($ekstensiGambar));
		if ( !in_array($ekstensiGambar, $ekstensigambarValid) ) {
			echo "<script>
					alert('yang anda upload bukan gambar');
				</script>";
			return false; 
		}

		

		//lolos pengecekan, gambar siap diupload
		move_uploaded_file($tmpName, 'buktibayar/' . $namaFile);

		return $namaFile;
	}
	
	function tambahproduk($data) { //fungsi memnambah produk
		global $db; //mengkoneksikan dengan database

		$namaproduk = htmlspecialchars($data["namaproduk"]);
		$hargaproduk = htmlspecialchars($data["hargaproduk"]);
		$gambarproduk = uploadgambar();

		try{ 
			$insert = $db->prepare("INSERT INTO produk (id_produk, nama_produk, harga, gambar_produk) values ('', :namaproduk, :hargaproduk, :gambarproduk)"); 
			$insert->bindValue(':namaproduk', $namaproduk); 
			$insert->bindValue(':hargaproduk', $hargaproduk);
			$insert->bindValue(':gambarproduk', $gambarproduk);
			$insert->execute(); //mengeksekusi query tambah produk

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}

	if( isset($_POST["tambahproduk"]) ) {    
		if( tambahproduk($_POST) > 0 ) { 
			echo "
			<script>
			alert('berhasil menambahkan produk!');
			document.location.href = 'daftarproduk.php';
			</script>
			"; 
		} else {
			echo "
			<script>
			alert('gagal menambah produk!');
			document.location.href = 'daftarproduk.php';
			</script>
			";
		}
	}

	function editproduk($data) { 
		global $db; //mengkoneksikan dengan database

		$idproduk = htmlspecialchars($data["idproduk"]);
		$namaproduk = htmlspecialchars($data["namaproduk"]);
		$hargaproduk = htmlspecialchars($data["hargaproduk"]);
		$gambarLama = htmlspecialchars($data["gambarLama"]); 

		//cek apakah user pilih gambar baru atau tidak
		if ( $_FILES['gambarproduk']['error'] === 4 ) {
			$gambar = $gambarLama; //jika tidak maka akan tetap menggunakan foto yang lama
		}
		else {
	 		$gambar = uploadgambar(); //jika mengupload gambar baru akan menggunakan fungsi upload untuk upload gambar
		}
	
		try{
			$update = $db->prepare("UPDATE produk SET nama_produk = :nama_produk, harga = :harga_produk, gambar_produk = :gambar WHERE id_produk = :id_produk"); //query untuk mengupdate dat user
			$update->bindValue(':id_produk', $idproduk); 
			$update->bindValue(':nama_produk', $namaproduk); 
			$update->bindValue(':harga_produk', $hargaproduk);
			$update->bindValue(':gambar', $gambar);
			$update->execute(); //mengeksekusi query

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}

	if( isset($_POST["editproduk"]) ) { 
        	if( editproduk($_POST) > 0 ) { 
			echo "
			<script>
			alert('data berhasil diubah!');
			document.location.href = 'daftarproduk.php';
			</script>
			"; 
			} else {
				echo "
				<script>
				alert('data gagal diubah!');
				document.location.href = 'daftarproduk.php';
				</script>
				";
			}
	}

	function pemesanan($data) { 
		global $db; 

		$id = htmlspecialchars($data["id"]);
		$produk = htmlspecialchars($data["id_produk"]);
		$namapemesan = htmlspecialchars($data["nama"]);
		$alamatpemesan = htmlspecialchars($data["alamat"]);
		$notelp = htmlspecialchars($data["notelp"]);
		$jumlah = htmlspecialchars($data["jumlah"]);
		$jasakirim = htmlspecialchars($data["jasakirim"]);
		$desain = uploaddesain();
		$keterangan = htmlspecialchars($data["keterangan"]);

		$query1 = $db->prepare("SELECT harga FROM produk where id_produk='$produk'");
		$query1->execute(); 
		foreach ($query1 as $data1){
			$hargaproduk = $data1['harga'];
		}

		$query2 = $db->prepare("SELECT tarif_kirim FROM jasakirim where id_jasakirim='$jasakirim'");
		$query2->execute(); 
		foreach ($query2 as $data2){
			$ongkir = $data2['tarif_kirim'];
		}

		try{ 
			$insert = $db->prepare("INSERT INTO pemesanan (id_pemesanan, nama_pemesan, tgl_pemesanan, no_telp, alamat, id_produk, desain_produk, jumlah, id_jasakirim, jumlah_bayar, keterangan, status_pemesanan) values (:id_pemesanan, :nama_pemesan, NOW(), :no_telp, :alamat, :id_produk, :desain_produk, :jumlah, :id_jasakirim, :jumlah_bayar, :keterangan, :status_pemesanan)"); 
			$insert->bindValue(':id_pemesanan', $id); 
			$insert->bindValue(':nama_pemesan', $namapemesan);
			$insert->bindValue(':no_telp', $notelp); 
			$insert->bindValue(':alamat', $alamatpemesan);
			$insert->bindValue(':id_produk', $produk);
			$insert->bindValue(':desain_produk', $desain); 
			$insert->bindValue(':jumlah', $jumlah);
			$insert->bindValue(':id_jasakirim', $jasakirim);
			$insert->bindValue(':jumlah_bayar', $hargaproduk*$jumlah + $ongkir); 
			$insert->bindValue(':keterangan', $keterangan);
			$insert->bindValue(':status_pemesanan', 1);
			$insert->execute(); //mengeksekusi query tambah produk

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}

	if( isset($_POST["pesanproduk"]) ) {    
		if( pemesanan($_POST) > 0 ) { 
			echo "
			<script>
			alert('berhasil memesan produk!');
			document.location.href = 'invoice.php?id=".$_POST["id"]."';
			</script>
			"; 
		} else {
			echo "
			<script>
			alert('gagal memesan produk!');
			document.location.href = 'product.php';
			</script>
			";
		}
	}

	function pembayaran($data) { 
		global $db; 

		$idorder = htmlspecialchars($data["idorder"]);
		$nama = htmlspecialchars($data["nama"]);
		$jumlah = htmlspecialchars($data["jumlah"]);
		$pembayaran = uploadpembayaran();

		try{ 
			$insert = $db->prepare("INSERT INTO pembayaran (id_pembayaran, id_pemesanan, nama_pemesan, jumlah_bayar, tgl_pembayaran, bukti_bayar, status_pembayaran) values ('', :id_pemesanan, :nama_pemesan, :jumlah_bayar, NOW(), :bukti_bayar, :status_pembayaran)"); 
			$insert->bindValue(':id_pemesanan', $idorder);
			$insert->bindValue(':nama_pemesan', $nama);
			$insert->bindValue(':jumlah_bayar', $jumlah);
			$insert->bindValue(':bukti_bayar', $pembayaran); 
			$insert->bindValue(':status_pembayaran', 1);
			$insert->execute(); //mengeksekusi query tambah produk

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}

	if( isset($_POST["kirimpembayaran"]) ) {    
		if( pembayaran($_POST) > 0 ) { 
			echo "
			<script>
			alert('Pembayaran anda sedang diproses, silahkan menunggu admin mengkonfirmasi pembayaran anda, terima kasih');
			document.location.href = 'pembayaran.php';
			</script>
			"; 
		} else {
			echo "
			<script>
			alert('Pembayaran anda gagal diproses');
			document.location.href = 'pembayaran.php';
			</script>
			";
		}
	}

	function pesananbatal($id) { 
		global $db; 
	
		try{
			$update = $db->prepare("UPDATE pemesanan SET status_pemesanan = 4 WHERE id_pemesanan = :id_pemesanan");
			$update->bindValue(':id_pemesanan', $id);
			$update->execute(); //mengeksekusi query

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}

	function pesananselesai($id) { 
		global $db; 
	
		try{
			$update = $db->prepare("UPDATE pemesanan SET status_pemesanan = 3 WHERE id_pemesanan = :id_pemesanan");
			$update->bindValue(':id_pemesanan', $id);
			$update->execute(); //mengeksekusi query

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}
	
	function terimabayar($id1, $id2) { 
		global $db; 
	
		try{
			$update = $db->prepare("UPDATE pembayaran SET status_pembayaran = 2 WHERE id_pembayaran = :id_pembayaran");
			$update->bindValue(':id_pembayaran', $id1);
			$update->execute(); //mengeksekusi query

			$update2 = $db->prepare("UPDATE pemesanan SET status_pemesanan = 2 WHERE id_pemesanan = :id_pemesanan");
			$update2->bindValue(':id_pemesanan', $id2);
			$update2->execute(); //mengeksekusi query

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}

	function tolakbayar($id1, $id2) { 
		global $db; 
	
		try{
			$update = $db->prepare("UPDATE pembayaran SET status_pembayaran = 3 WHERE id_pembayaran = :id_pembayaran");
			$update->bindValue(':id_pembayaran', $id1);
			$update->execute(); //mengeksekusi query

			$update2 = $db->prepare("UPDATE pemesanan SET status_pemesanan = 4 WHERE id_pemesanan = :id_pemesanan");
			$update2->bindValue(':id_pemesanan', $id2);
			$update2->execute(); //mengeksekusi query

			return 1; //pengecekan error
		} catch(PDOException $e) {
			return 0;}
	}
?>