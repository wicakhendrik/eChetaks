-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 06, 2020 at 04:27 PM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chetaks`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id_admin` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_bin NOT NULL,
  `passwd` varchar(70) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id_admin`, `username`, `passwd`) VALUES
(1, 'admin', '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9');

-- --------------------------------------------------------

--
-- Table structure for table `jasakirim`
--

CREATE TABLE `jasakirim` (
  `id_jasakirim` int(11) NOT NULL,
  `nama_jaskir` varchar(25) COLLATE utf8_bin NOT NULL,
  `tarif_kirim` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `jasakirim`
--

INSERT INTO `jasakirim` (`id_jasakirim`, `nama_jaskir`, `tarif_kirim`) VALUES
(1, 'J&T', 30000),
(2, 'JNE', 25000),
(3, 'TIKI', 35000),
(4, 'Sicepat', 25000);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `nama_pemesan` varchar(40) COLLATE utf8_bin NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `tgl_pembayaran` date NOT NULL,
  `bukti_bayar` varchar(50) COLLATE utf8_bin NOT NULL,
  `status_pembayaran` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `id_pemesanan`, `nama_pemesan`, `jumlah_bayar`, `tgl_pembayaran`, `bukti_bayar`, `status_pembayaran`) VALUES
(1, 21221864, 'Muhamad Hendrik Wicaksono', 130000, '2020-05-18', 'bukti1.jpg', 2),
(2, 52930188, 'Muhamad Hendrik Wicaksono', 55000, '2020-05-19', 'bukti2.jpg', 2),
(3, 11268491, 'Nur Latif Hilaluddin', 105000, '2020-05-19', 'bukti3.jpg', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `nama_pemesan` varchar(40) COLLATE utf8_bin NOT NULL,
  `tgl_pemesanan` date NOT NULL,
  `no_telp` varchar(40) COLLATE utf8_bin NOT NULL,
  `alamat` varchar(100) COLLATE utf8_bin NOT NULL,
  `id_produk` int(11) NOT NULL,
  `desain_produk` varchar(40) COLLATE utf8_bin NOT NULL,
  `jumlah` int(11) NOT NULL,
  `id_jasakirim` int(11) NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `keterangan` varchar(65) COLLATE utf8_bin NOT NULL,
  `status_pemesanan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id_pemesanan`, `nama_pemesan`, `tgl_pemesanan`, `no_telp`, `alamat`, `id_produk`, `desain_produk`, `jumlah`, `id_jasakirim`, `jumlah_bayar`, `keterangan`, `status_pemesanan`) VALUES
(11268491, 'Nur Latif Hilaluddin', '2020-05-19', '0812323442', 'Jl. mana saja', 3, '794246_21221597_3668864_b503cea5_image.p', 2, 2, 105000, 'beli 2', 4),
(21221864, 'Muhamad Hendrik Wicaksono', '2020-05-18', '02839434022323', 'Jl. Blablabla', 1, 'logo3.png', 1, 1, 130000, 'tes', 3),
(52930188, 'Muhamad Hendrik Wicaksono', '2020-05-18', '02839434022323', 'Jl. Blablabla', 2, 'logoSakeraSecurity.jpg', 1, 2, 55000, 'tes', 2),
(77158817, 'Rohman Fathoni', '2020-05-19', '0812233203', 'Jl. apasajalah', 2, '2019-nasa-artemis-lunar-program-logo-des', 3, 4, 115000, 'beli 3', 4);

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id_produk` int(11) NOT NULL,
  `nama_produk` varchar(40) COLLATE utf8_bin NOT NULL,
  `harga` int(11) NOT NULL,
  `gambar_produk` varchar(30) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id_produk`, `nama_produk`, `harga`, `gambar_produk`) VALUES
(1, 'Sablon Jaket Custom', 100000, 'jaket.png'),
(2, 'Topi Desain Suka -suka', 30000, 'topi.png'),
(3, 'Mug', 40000, 'mug.png');

-- --------------------------------------------------------

--
-- Table structure for table `status_pembayaran`
--

CREATE TABLE `status_pembayaran` (
  `id_status_pembayaran` int(11) NOT NULL,
  `ket` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `status_pembayaran`
--

INSERT INTO `status_pembayaran` (`id_status_pembayaran`, `ket`) VALUES
(1, 'Belum Dikonfirmasi'),
(2, 'Pembayaran Diterima'),
(3, 'Pembayaran Ditolak');

-- --------------------------------------------------------

--
-- Table structure for table `status_pemesanan`
--

CREATE TABLE `status_pemesanan` (
  `id_status_pemesanan` int(11) NOT NULL,
  `ket` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `status_pemesanan`
--

INSERT INTO `status_pemesanan` (`id_status_pemesanan`, `ket`) VALUES
(1, 'Belum Diproses'),
(2, 'Sedang Diproses'),
(3, 'Selesai Diproses'),
(4, 'Dibatalkan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `jasakirim`
--
ALTER TABLE `jasakirim`
  ADD PRIMARY KEY (`id_jasakirim`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `id_pemesanan` (`id_pemesanan`),
  ADD KEY `status_pembayaran` (`status_pembayaran`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `id_produk` (`id_produk`),
  ADD KEY `id_jasakirim` (`id_jasakirim`),
  ADD KEY `status_pemesanan` (`status_pemesanan`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_produk`);

--
-- Indexes for table `status_pembayaran`
--
ALTER TABLE `status_pembayaran`
  ADD PRIMARY KEY (`id_status_pembayaran`);

--
-- Indexes for table `status_pemesanan`
--
ALTER TABLE `status_pemesanan`
  ADD PRIMARY KEY (`id_status_pemesanan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jasakirim`
--
ALTER TABLE `jasakirim`
  MODIFY `id_jasakirim` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id_produk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `status_pembayaran`
--
ALTER TABLE `status_pembayaran`
  MODIFY `id_status_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `status_pemesanan`
--
ALTER TABLE `status_pemesanan`
  MODIFY `id_status_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`status_pembayaran`) REFERENCES `status_pembayaran` (`id_status_pembayaran`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`),
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_jasakirim`) REFERENCES `jasakirim` (`id_jasakirim`),
  ADD CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`status_pemesanan`) REFERENCES `status_pemesanan` (`id_status_pemesanan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
