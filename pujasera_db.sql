-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 30, 2018 at 11:29 AM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pujasera_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `nohp` varchar(13) NOT NULL,
  `saldo` int(11) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `id_user` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `nama`, `alamat`, `nohp`, `saldo`, `email`, `foto`, `id_user`) VALUES
(1, 'customer satu', 'jalan customer satu', '0898768678', 0, 'customer_satu@test.com', '135bf8c692a3347.png', 13),
(2, 'customer dua', 'jalan customer dua', '065799789', 0, 'customer_dua@test.com', '145bf8c741daba1.png', 14),
(3, 'customer tiga', 'jalan customer tiga', '097865679', 388000, 'customer_tiga@test.com', '155bf8cd1deac9c.png', 15);

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

DROP TABLE IF EXISTS `deposit`;
CREATE TABLE IF NOT EXISTS `deposit` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_stan` int(5) NOT NULL,
  `id_customer` int(5) NOT NULL,
  `nohp` varchar(15) NOT NULL,
  `nominal` int(11) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_stan` (`id_stan`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`id`, `id_stan`, `id_customer`, `nohp`, `nominal`, `tanggal`) VALUES
(3, 6, 3, '0980899090', 8000, '2018-11-30 17:58:46'),
(4, 6, 3, '0980899090', 3000, '2018-11-30 17:59:08');

-- --------------------------------------------------------

--
-- Table structure for table `hidangan`
--

DROP TABLE IF EXISTS `hidangan`;
CREATE TABLE IF NOT EXISTS `hidangan` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  `stock` tinyint(4) NOT NULL,
  `harga` int(11) NOT NULL,
  `id_stan` int(5) NOT NULL,
  `id_kategori` int(5) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_stan` (`id_stan`),
  KEY `id_kategori` (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `hidangan`
--

INSERT INTO `hidangan` (`id`, `nama`, `stock`, `harga`, `id_stan`, `id_kategori`, `foto`) VALUES
(7, 'bakso super', 2, 9000, 6, 2, '201811230315355bf770d785050.jpg'),
(8, 'sate', 2, 9000, 3, 4, '201811230343565bf7777c79d35.jpg'),
(9, 'mie pangsit', 2, 15000, 6, 3, '201811230345385bf777e2bad93.jpg'),
(10, 'menu 1', 1, 5000, 6, 1, '201811301812235c011b17d8783.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `nama` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id`, `nama`) VALUES
(1, 'makanan ringan'),
(2, 'bakso'),
(3, 'mi'),
(4, 'rujak');

-- --------------------------------------------------------

--
-- Table structure for table `meja`
--

DROP TABLE IF EXISTS `meja`;
CREATE TABLE IF NOT EXISTS `meja` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `nomor` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meja`
--

INSERT INTO `meja` (`id`, `status`, `nomor`) VALUES
(1, 1, 1),
(2, 2, 24),
(4, 2, 21),
(6, 2, 64);

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

DROP TABLE IF EXISTS `pemesanan`;
CREATE TABLE IF NOT EXISTS `pemesanan` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `jumlah` int(5) NOT NULL,
  `total` int(11) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `id_transaksi` int(5) NOT NULL,
  `id_hidangan` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_trans` (`id_transaksi`),
  KEY `id_hidangan` (`id_hidangan`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`id`, `jumlah`, `total`, `tanggal`, `status`, `id_transaksi`, `id_hidangan`) VALUES
(4, 1, 9000, '2018-11-30 17:43:35', 4, 6, 7),
(5, 2, 30000, '2018-11-30 17:44:03', 4, 6, 9),
(8, 15, 135000, '2018-11-29 13:05:53', 2, 4, 7),
(9, 3, 45000, '2018-11-29 13:15:06', 2, 8, 9);

-- --------------------------------------------------------

--
-- Table structure for table `refund`
--

DROP TABLE IF EXISTS `refund`;
CREATE TABLE IF NOT EXISTS `refund` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_stan` int(5) NOT NULL,
  `id_customer` int(5) NOT NULL,
  `nohp` varchar(13) NOT NULL,
  `alasan` text,
  `nominal` int(11) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_stan` (`id_stan`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `refund`
--

INSERT INTO `refund` (`id`, `id_stan`, `id_customer`, `nohp`, `alasan`, `nominal`, `tanggal`) VALUES
(1, 6, 3, '097865679', NULL, 75000, '2018-10-29 17:07:05'),
(2, 6, 3, '097865679', NULL, 27000, '2018-11-29 14:07:05');

-- --------------------------------------------------------

--
-- Table structure for table `stan`
--

DROP TABLE IF EXISTS `stan`;
CREATE TABLE IF NOT EXISTS `stan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `saldo` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `id_user` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stan`
--

INSERT INTO `stan` (`id`, `nama`, `saldo`, `foto`, `id_user`) VALUES
(2, 'stan1', 2000, '3.2.png', 3),
(3, 'stan 2coy', 3000011, '152393489179766448.jpg-4', 4),
(4, 'stan3', 30000, '3.2.png-5', 5),
(6, 'aneka masakan', 139000, 'icon.png-7', 7),
(10, 'burger king', 900000000, '115bf653b00f8cf.jpg', 11);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `id_meja` int(5) DEFAULT NULL,
  `id_customer` int(5) NOT NULL,
  `tanggal` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id_meja` (`id_meja`),
  KEY `id_customer` (`id_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_meja`, `id_customer`, `tanggal`, `status`, `total`) VALUES
(4, 2, 3, '2018-10-29 13:05:53', 2, 39000),
(6, 2, 3, '2018-11-30 17:44:03', 4, 39000),
(7, NULL, 3, '2018-11-29 13:12:27', 1, 0),
(8, 2, 3, '2018-11-29 13:15:06', 2, 45000);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `api_token` varchar(255) NOT NULL,
  `role` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `remember_token`, `api_token`, `role`) VALUES
(1, 'admin', '$2y$10$Pbd3oYgFr6IN0uWDQBBx7Ow7mpOTWeYlicKoMXvlco3Y2fMZXLj/K', 'pLozg2XqG1izcYBTMuMtZNmLW7xLzOcB2v1v9LC3ukTamQgwdclA9Xm3cIlx', '$2y$10$vUIpY.dVVorPynoSukJYhuwx1kzoQP1XPZjDWmJdmPFKIKZw4yYB6', 1),
(3, 'stan1', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$f2A4hGoor3IX0vZtU.jwwuHKoGucB2G8C8ERYoOlqBBTSNo8l0.tW', 2),
(4, 'stan2 das', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$Jqcjc8EJTG8qxOoAqX/KY.SUw9IRLsXRrdJUTuHNS0vgRnPA.cihy', 2),
(5, 'stan3', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$5sJuyAlw7KEJyM4vzrOK2.OpnAsjdkGje1lPWD6wpE9BoDhPgga3y', 2),
(7, 'aneka', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$bDYgcpVXUMlza7WOfFWH8OfqL.hbfmG9rL7AOsY20Mdm0IvXFe4IS', 2),
(11, 'burgerking', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$c7F9MyoIvJoR6IoDOvF.PuKm6tUuJpKPj94FNdFxOKBNdcGkSTuju', 2),
(13, 'customer_satu', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$BSayx2.tOlufnvncXIBg2.U5qMYWvsyz3SQ9Lq32vC1Y7u1TSocFK', 3),
(14, 'customer_dua', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$mjdGfBOvMd6kfK.07Oi7fOJmA1eSMg2tYrvAdaAPlTc2gh9jlKlO2', 3),
(15, 'customer_tiga', '$2y$10$9hr2./e5Q.VL6cxgjxLVVeuPqW31HmOjsc6j3kXasubSXNHh5d5vi', NULL, '$2y$10$v/Sc.eI3vDLqYZRI81d77uhOgXM6klxtDaJjezR3kC.tU6f1Tm01a', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `deposit`
--
ALTER TABLE `deposit`
  ADD CONSTRAINT `deposit_ibfk_1` FOREIGN KEY (`id_stan`) REFERENCES `stan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deposit_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hidangan`
--
ALTER TABLE `hidangan`
  ADD CONSTRAINT `hidangan_ibfk_1` FOREIGN KEY (`id_stan`) REFERENCES `stan` (`id`),
  ADD CONSTRAINT `hidangan_ibfk_2` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_2` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pemesanan_ibfk_3` FOREIGN KEY (`id_hidangan`) REFERENCES `hidangan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `refund`
--
ALTER TABLE `refund`
  ADD CONSTRAINT `refund_ibfk_1` FOREIGN KEY (`id_stan`) REFERENCES `stan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `refund_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stan`
--
ALTER TABLE `stan`
  ADD CONSTRAINT `stan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_meja`) REFERENCES `meja` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
