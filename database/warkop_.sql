-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for warkop
CREATE DATABASE IF NOT EXISTS `warkop` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `warkop`;

-- Dumping structure for table warkop.detail_transaksi
CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `id_transaksi` (`id_transaksi`),
  KEY `id_menu` (`id_menu`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id_menu`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.detail_transaksi: ~0 rows (approximately)

-- Dumping structure for table warkop.menu
CREATE TABLE IF NOT EXISTS `menu` (
  `id_menu` int NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(50) NOT NULL,
  `harga` int NOT NULL,
  `kategori` varchar(30) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.menu: ~0 rows (approximately)

-- Dumping structure for table warkop.pelanggan
CREATE TABLE IF NOT EXISTS `pelanggan` (
  `id_pelanggan` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `no_wa` varchar(15) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.pelanggan: ~0 rows (approximately)

-- Dumping structure for table warkop.pesanan
CREATE TABLE IF NOT EXISTS `pesanan` (
  `id_pesanan` int NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(20) NOT NULL,
  `harga` int NOT NULL DEFAULT '0',
  `qty` int NOT NULL DEFAULT '0',
  `total` int NOT NULL DEFAULT '0',
  `tanggal` date NOT NULL,
  `id_menu` int NOT NULL,
  `jumlah` int NOT NULL DEFAULT '0',
  `status_pesanan` enum('selesai','proses') NOT NULL,
  PRIMARY KEY (`id_pesanan`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.pesanan: ~5 rows (approximately)
INSERT INTO `pesanan` (`id_pesanan`, `nama_menu`, `harga`, `qty`, `total`, `tanggal`, `id_menu`, `jumlah`, `status_pesanan`) VALUES
	(24, 'Kopi', 5000, 2, 10000, '2025-08-15', 0, 0, 'selesai'),
	(25, 'Rokok', 25000, 7, 175000, '2025-08-15', 0, 0, 'selesai'),
	(26, 'Indomie', 12000, 1, 12000, '2025-08-15', 0, 0, 'selesai'),
	(27, 'Es Teh', 3000, 1, 3000, '2025-08-15', 0, 0, 'selesai'),
	(28, 'Indomie', 12000, 2, 24000, '2025-08-20', 0, 0, 'selesai');

-- Dumping structure for table warkop.profil
CREATE TABLE IF NOT EXISTS `profil` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text,
  `bio` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table warkop.profil: ~1 rows (approximately)
INSERT INTO `profil` (`id`, `nama`, `email`, `telepon`, `alamat`, `bio`) VALUES
	(1, 'Erlangga Al Fahrizki', 'erla@mail.com', '08123456789', 'Jl. Kenangan No. 123', 'Saya suka ngoding dan ngopi.');

-- Dumping structure for table warkop.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_pelanggan` int DEFAULT NULL,
  `total` int NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_transaksi`),
  KEY `id_pelanggan` (`id_pelanggan`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.transaksi: ~0 rows (approximately)

-- Dumping structure for table warkop.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `image` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int NOT NULL,
  `is_active` int NOT NULL,
  `date_created` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.user: ~3 rows (approximately)
INSERT INTO `user` (`id`, `name`, `email`, `image`, `password`, `role_id`, `is_active`, `date_created`) VALUES
	(8, 'ikii', 'omiki@gmail.com', 'default.jpg', '$2y$10$gzKjPsQySerHQA0MHKEEDe0QNERCkqOcLSS1LE3DExTCq9F82WSQG', 2, 1, 1755746831),
	(9, 'Muhamad Surya Al Ghifari', 'lily@rifkiidr.id', 'default.jpg', '$2y$10$I33ba9m8T1rjY0zGKzOckOmQlja/r4.siDPQpeXGBX2r13wKLr7ZC', 2, 1, 2025),
	(10, 'UyaSky', 'm.suryaalghifari@gmail.com', 'default.jpg', '$2y$10$VtJiXQybgrDQGQkZBhMK1uf42NgOnJAmBZTcYgeLfHhYIoKDMTICK', 2, 1, 2025);

-- Dumping structure for table warkop.user_otp_codes
CREATE TABLE IF NOT EXISTS `user_otp_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `code_hash` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `max_attempts` int NOT NULL DEFAULT '5',
  `ip` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.user_otp_codes: ~6 rows (approximately)
INSERT INTO `user_otp_codes` (`id`, `user_id`, `code_hash`, `expires_at`, `used_at`, `attempts`, `max_attempts`, `ip`, `user_agent`, `created_at`) VALUES
	(1, 10, '$2y$10$JNv9i/mqnfURJ.HtY0EdRec6if/w7zR2GHXXaQl/IU2RSTJ15MxBi', '2025-08-22 06:54:33', NULL, 0, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-22 13:49:33'),
	(2, 10, '$2y$10$l7hHoMWjv85NxWgzg/RzMeaKQRjQH5ZgS9ZCvdToAEohoDN/jhjUq', '2025-08-22 06:56:29', NULL, 0, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-22 13:51:29'),
	(3, 10, '$2y$10$gC0fhQXjl9mGVfZc9JzDG.hJe2n3Vc9in2BvhiN6TMo/RsyuAMyZ6', '2025-08-22 07:00:38', NULL, 0, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-22 13:55:38'),
	(4, 10, '$2y$10$1C8BCFEjT4t9HSeS3fBak.Dkt.sc5XdgV6CqyObINEcsU8nVlTlZe', '2025-08-22 07:03:25', NULL, 0, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-22 13:58:25'),
	(5, 10, '$2y$10$3/Iiac9kwiQRD./7/odDg.dvi2Cc..15.gpNxIEAnHBuQ.i0kumhG', '2025-08-22 07:05:56', '2025-08-22 07:01:19', 0, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-22 14:00:56'),
	(6, 9, '$2y$10$EdR9IvHmDb6F0I0q0LaE7.8uE0y.U.u1.w/5Vhq8fWFfyebKZJtQa', '2025-08-22 07:11:03', NULL, 0, 5, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/139.0.0.0 Safari/537.36', '2025-08-22 14:06:03');

-- Dumping structure for table warkop.user_role
CREATE TABLE IF NOT EXISTS `user_role` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` varchar(128) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table warkop.user_role: ~2 rows (approximately)
INSERT INTO `user_role` (`id`, `role`) VALUES
	(1, 'administrator'),
	(2, 'member');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
