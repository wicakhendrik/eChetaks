<?php
    include "koneksi.php"; //mengkoneksikan dengan database
    $id_pemesanan = $_GET["id"];
    $query = $db->prepare("SELECT * FROM pemesanan p, jasakirim j, produk where p.id_pemesanan='$id_pemesanan' and p.id_produk=produk.id_produk and p.id_jasakirim = j.id_jasakirim");
    $query->execute();
    foreach ($query as $data){
?>
<!doctype html>
<html>
    <head>
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <!------ Include the above in your HEAD tag ---------->
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="invoice-title">
                        <h2>Invoice</h2><h3 class="pull-right">Order # <?php echo $data['id_pemesanan']; ?></h3>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                            <strong>Chetak's Product</strong><br>
                                Whatsapp : +6283850187425<br>
                                Instagram : Instagram
                            </address>
                        </div>
                        <div class="col-xs-6 text-right">
                            <address>
                            <strong>Pemesan :</strong><br>
                                <?php echo $data['nama_pemesan']; ?><br>
                                <?php echo $data['alamat']; ?>Alamat<br>
                                <?php echo $data['no_telp']; ?><br>
                                <?php echo $data['nama_jaskir']; ?>
                            </address>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6">
                            <address>
                                <strong>Silahkan Melakukan Pembayaran Melalui Rekening Kami :</strong><br>
                                Bank : BCA<br>
                                No Rek. : 123456789<br>
                                A/N : Iqbal Okthapian
                            </address>
                        </div>
                        <div class="col-xs-6 text-right">
                            <address>
                                <strong>Tanggal Pemesanan :</strong><br>
                                <?php echo $data['tgl_pemesanan']; ?><br><br>
                            </address>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Order summary</strong></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td><strong>Nama Produk</strong></td>
                                            <td class="text-center"><strong>Harga Satuan</strong></td>
                                            <td class="text-center"><strong>Jumlah yang dipesan</strong></td>
                                            <td class="text-center"><strong>Keterangan</strong></td>
                                            <td class="text-right"><strong>Total Harga</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                        <tr>
                                            <td><?php echo $data['nama_produk']; ?></td>
                                            <td class="text-center"><?php echo "Rp.", $data['harga']; ?></td>
                                            <td class="text-center"><?php echo $data['jumlah']; ?></td>
                                            <td class="text-center"><?php echo $data['keterangan']; ?></td>
                                            <td class="text-right"><?php echo "Rp.", $data['harga']*$data['jumlah']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="thick-line text-center"><strong>Subtotal</strong></td>
                                            <td class="thick-line text-right"><?php echo "Rp.", $data['harga']*$data['jumlah']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="no-line text-center"><strong>Ongkos Kirim</strong></td>
                                            <td class="no-line text-right"><?php echo "Rp.", $data['tarif_kirim']; ?></td>
                                        </tr>
                                        <tr>
                                            <td class="no-line"></td>
                                            <td class="no-line"></td>
                                            <td class="thick-line"></td>
                                            <td class="no-line text-center"><strong>Total</strong></td>
                                            <td class="no-line text-right"><?php echo "Rp.", $data['jumlah_bayar']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php    
}
?>