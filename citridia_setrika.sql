-- --------------------------------------------------------
-- Host:                         dev.citridia.com
-- Server version:               10.0.34-MariaDB-0ubuntu0.16.04.1 - Ubuntu 16.04
-- Server OS:                    debian-linux-gnu
-- HeidiSQL Version:             9.5.0.5196
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for citridia_setrika
DROP DATABASE IF EXISTS `citridia_setrika`;
CREATE DATABASE IF NOT EXISTS `citridia_setrika` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `citridia_setrika`;

-- Dumping structure for table citridia_setrika.afiliasi
DROP TABLE IF EXISTS `afiliasi`;
CREATE TABLE IF NOT EXISTS `afiliasi` (
  `kode_afiliasi` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fcm` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT NULL,
  `file_ktp` varchar(100) DEFAULT NULL,
  `file_kk` varchar(100) DEFAULT NULL,
  `saldo` double(12,2) DEFAULT NULL,
  `kodepos` char(5) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_afiliasi`),
  KEY `afiliasi_FKIndex1` (`kode_pshcabang`),
  CONSTRAINT `afiliasi_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.afiliasi: ~2 rows (approximately)
/*!40000 ALTER TABLE `afiliasi` DISABLE KEYS */;
INSERT INTO `afiliasi` (`kode_afiliasi`, `kode_pshcabang`, `nama`, `alamat`, `notelp`, `tanggal_lahir`, `email`, `fcm`, `token`, `tanggal_daftar`, `file_ktp`, `file_kk`, `saldo`, `kodepos`, `foto`, `hapus`) VALUES
	('AFL/MLG/20161016120916028194', 'MLG/CAB/20161013101442616542', 'Afiliasi 1', 'malang', '0341', '2016-10-16', 'Afiliasi@1', 'Afiliasifcm', NULL, '2016-10-16 12:09:16', 'http://urlktp.com', 'http://urlkk.com', NULL, '65141', 'http://urlfoto.com', '0'),
	('AFL/MLG/20161101075409279229', 'MLG/CAB/20161013101442616542', 'Iqbal Afiliasihihi', 'malang', '0341341341', '2016-11-01', 'firmanslash@gmail.com', 'ZDTRSQwlD6WFd7itaKyKbntXAIb2', NULL, '2016-11-01 07:54:09', 'http://urlktp.com', 'http://urlkk.com', NULL, '65141', 'http://dev.citridia.com/ws.jastrik/manifest/avatarafiliasi/ZDTRSQwlD6WFd7itaKyKbntXAIb2.jpg', '0');
/*!40000 ALTER TABLE `afiliasi` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.afiliasi_bank
DROP TABLE IF EXISTS `afiliasi_bank`;
CREATE TABLE IF NOT EXISTS `afiliasi_bank` (
  `kode_afiliasi_bank` bigint(20) NOT NULL AUTO_INCREMENT,
  `afiliasi_kode_afiliasi` varchar(50) NOT NULL,
  `kode_jenis_bank` smallint(5) unsigned NOT NULL,
  `norek_afiliasi_bank` varchar(20) NOT NULL,
  `atas_nama_afiliasi_bank` varchar(50) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_afiliasi_bank`),
  KEY `afiliasi_bank_FKIndex1` (`afiliasi_kode_afiliasi`),
  KEY `afiliasi_bank_ibfk_2` (`kode_jenis_bank`),
  CONSTRAINT `afiliasi_bank_ibfk_1` FOREIGN KEY (`afiliasi_kode_afiliasi`) REFERENCES `afiliasi` (`kode_afiliasi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `afiliasi_bank_ibfk_2` FOREIGN KEY (`kode_jenis_bank`) REFERENCES `jenis_bank` (`kode_jenis_bank`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.afiliasi_bank: ~1 rows (approximately)
/*!40000 ALTER TABLE `afiliasi_bank` DISABLE KEYS */;
INSERT INTO `afiliasi_bank` (`kode_afiliasi_bank`, `afiliasi_kode_afiliasi`, `kode_jenis_bank`, `norek_afiliasi_bank`, `atas_nama_afiliasi_bank`, `hapus`) VALUES
	(2, 'AFL/MLG/20161101075409279229', 2, '333666', 'Iqbal Afiliasihi', '0');
/*!40000 ALTER TABLE `afiliasi_bank` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.afiliasi_pencairan_saldo
DROP TABLE IF EXISTS `afiliasi_pencairan_saldo`;
CREATE TABLE IF NOT EXISTS `afiliasi_pencairan_saldo` (
  `kode_afiliasi_pencairan_saldo` varchar(50) NOT NULL,
  `kode_afiliasi` varchar(50) NOT NULL,
  `kode_afiliasi_bank` bigint(20) NOT NULL,
  `kode_bank_pusat` smallint(5) unsigned DEFAULT NULL,
  `kode_pegawai_pshpusat` varchar(50) DEFAULT NULL,
  `tanggal_request` datetime DEFAULT NULL,
  `tanggal_terima` datetime DEFAULT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `status_afiliasi_pencairan_saldo` char(1) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_afiliasi_pencairan_saldo`),
  KEY `afiliasi_pencairan_saldo_FKIndex1` (`kode_afiliasi`),
  KEY `afiliasi_pencairan_saldo_FKIndex2` (`kode_afiliasi_bank`),
  KEY `afiliasi_pencairan_saldo_FKIndex3` (`kode_bank_pusat`),
  KEY `afiliasi_pencairan_saldo_FKIndex4` (`kode_pegawai_pshpusat`),
  CONSTRAINT `afiliasi_pencairan_saldo_ibfk_1` FOREIGN KEY (`kode_afiliasi`) REFERENCES `afiliasi` (`kode_afiliasi`),
  CONSTRAINT `afiliasi_pencairan_saldo_ibfk_2` FOREIGN KEY (`kode_afiliasi_bank`) REFERENCES `afiliasi_bank` (`kode_afiliasi_bank`),
  CONSTRAINT `afiliasi_pencairan_saldo_ibfk_3` FOREIGN KEY (`kode_bank_pusat`) REFERENCES `bank_pusat` (`kode_bank_pusat`),
  CONSTRAINT `afiliasi_pencairan_saldo_ibfk_4` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.afiliasi_pencairan_saldo: ~2 rows (approximately)
/*!40000 ALTER TABLE `afiliasi_pencairan_saldo` DISABLE KEYS */;
INSERT INTO `afiliasi_pencairan_saldo` (`kode_afiliasi_pencairan_saldo`, `kode_afiliasi`, `kode_afiliasi_bank`, `kode_bank_pusat`, `kode_pegawai_pshpusat`, `tanggal_request`, `tanggal_terima`, `nominal`, `status_afiliasi_pencairan_saldo`, `hapus`) VALUES
	('RPF/170404140142268190', 'AFL/MLG/20161101075409279229', 2, NULL, NULL, '2017-04-04 14:01:42', NULL, 2000.00, '3', '0'),
	('RPF/170404152048998496', 'AFL/MLG/20161101075409279229', 2, NULL, NULL, '2017-04-04 15:20:48', NULL, 6000.00, '0', '0');
/*!40000 ALTER TABLE `afiliasi_pencairan_saldo` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen
DROP TABLE IF EXISTS `agen`;
CREATE TABLE IF NOT EXISTS `agen` (
  `kode_agen` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) DEFAULT NULL,
  `kode_checker` varchar(50) DEFAULT NULL,
  `kode_afiliasi` varchar(50) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fcm` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT NULL,
  `file_ktp` varchar(100) DEFAULT NULL,
  `file_kk` varchar(100) DEFAULT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `slogan` varchar(100) DEFAULT NULL,
  `dana_agen` double(12,2) DEFAULT '0.00',
  `fee_agen` double(12,2) DEFAULT NULL,
  `status_agen` char(1) DEFAULT '0' COMMENT '0: belum aktif, 1: aktif, 2: libur, 3:ditolak',
  `rating_rapi` float(2,1) DEFAULT NULL,
  `rating_cepat` float(2,1) DEFAULT NULL,
  `tipe_agen` char(1) DEFAULT NULL COMMENT '0: agen, 1: vendor',
  `keterangan` text,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen`),
  KEY `agen_FKIndex1` (`kode_afiliasi`),
  KEY `agen_FKIndex2` (`kode_checker`),
  KEY `agen_FKIndex3` (`kode_pshcabang`),
  CONSTRAINT `agen_ibfk_1` FOREIGN KEY (`kode_afiliasi`) REFERENCES `afiliasi` (`kode_afiliasi`),
  CONSTRAINT `agen_ibfk_2` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`),
  CONSTRAINT `agen_ibfk_3` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen: ~20 rows (approximately)
/*!40000 ALTER TABLE `agen` DISABLE KEYS */;
INSERT INTO `agen` (`kode_agen`, `kode_pshcabang`, `kode_checker`, `kode_afiliasi`, `nama`, `notelp`, `email`, `fcm`, `token`, `tanggal_daftar`, `file_ktp`, `file_kk`, `logo`, `slogan`, `dana_agen`, `fee_agen`, `status_agen`, `rating_rapi`, `rating_cepat`, `tipe_agen`, `keterangan`, `hapus`) VALUES
	('AGN/BDG/161208114539704602', 'BDG/CAB/6543', NULL, NULL, 'Markonah', '021423030', 'goldenways@mail.com', NULL, NULL, '2016-12-08 11:45:39', NULL, NULL, NULL, 'Markonah is the best', 0.00, 0.00, '1', 4.0, 4.0, '0', NULL, '0'),
	('AGN/KPN/161208131039173921', 'BDG/CAB/6543', 'CHK/MLG/20161018150202269169', 'AFL/MLG/20161101075409279229', 'Iqbal Al-qoholz', '085855855008', 'ontanyasar@gmail.com', 'ngJIvO8nl0Xv6SR5iVQaur7gRan2', 'dAJpWMFeX8A:APA91bFmdy1FdszywOT3tYxxgGI1xG32e5rUD_Ai16Z41aMZ04aF3IFHrIbb7QEvBtUEok7ujdOUZDQMy3tD9OLnj-ym69EbDTOOekTn3MZKwDHwp_WgOYgu2AAAbyeBsps8h8yUMggg', '2016-12-08 13:10:39', NULL, NULL, 'http://dev.citridia.com/ws.jastrik/manifest/avataragen/ngJIvO8nl0Xv6SR5iVQaur7gRan2.jpg', 'yo yo, disini ada setrikaan yoo', 0.00, 0.00, '1', 5.0, 5.0, '0', NULL, '0'),
	('AGN/KPN/161208164447540229', 'BDG/CAB/6543', NULL, 'AFL/MLG/20161101075409279229', 'agen 007', '080989999', 'jamesbond@gmail.com', NULL, NULL, '2016-12-08 16:44:47', NULL, NULL, NULL, NULL, 0.00, 0.00, '0', NULL, NULL, '0', NULL, '0'),
	('AGN/KPN/170410090632673431', NULL, NULL, 'AFL/MLG/20161101075409279229', 'komarudin', '123', 'abc@mail.xxx', NULL, NULL, '2017-04-10 09:06:32', 'http://dev.citridia.com/ws.jastrik/manifest/ktpagen/AGNKPN170410090632673431.jpg', 'http://dev.citridia.com/ws.jastrik/manifest/kkagen/AGNKPN170410090632673431.jpg', NULL, NULL, 0.00, NULL, '0', NULL, NULL, NULL, NULL, '0'),
	('AGN/MLG/01010101', 'MLG/CAB/20161013101442616542', 'CHK/MLG/161221123757', NULL, 'Agen Test', '+62341423030', 'agen@mail.com', 'fcmnyanih', NULL, NULL, 'http://localhost/setrika_cabang/cabang/server/php/files/17012112115901.png', 'http://localhost/setrika_cabang/cabang/server/php/files/1701211212033.jpg', NULL, 'Onta jawa', 0.00, 0.00, '0', 4.6, 4.3, '0', NULL, '0'),
	('AGN/MLG/02020202', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 2', '+62384824832423', 'agen2@email.com', 'fcmnyanih2', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '0', 0.0, 0.0, '0', NULL, '0'),
	('AGN/MLG/03030303', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 3', NULL, 'agen3@mail.com', 'fcmnyanih3', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', 0.0, 0.0, '0', NULL, '0'),
	('AGN/MLG/04040404', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 4', NULL, 'agen4@mail.com', 'fcmnyanih4', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', 0.0, 0.0, '0', NULL, '0'),
	('AGN/MLG/05050505', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 5', NULL, 'agen5@mail.com', 'fcmnyanih5', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/06060606', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 6', NULL, 'agen6@mail.com', 'fcmnyanih6', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/07070707', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 7', NULL, 'agen7@mail.com', 'fcmnyanih7', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/08080808', 'MLG/CAB/20161013101442616542', '', NULL, 'Agen Test 8', NULL, 'agen8@mail.com', 'fcmnyanih8', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/09090909', 'MLG/CAB/20161013101442616542', 'CHK/MLG/20161018150202269169', NULL, 'Agen Test 9', NULL, 'agen9@mail.com', 'fcmnyanih9', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/11111111', 'MLG/CAB/20161013101442616542', 'CHK/MLG/20161018150202269169', NULL, 'Agen Test 10', NULL, 'agen10@mail.com', 'fcmnyanih10', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/161129152011027450', 'MLG/CAB/20161013101442616542', 'CHK/MLG/161221123757', 'AFL/MLG/20161101075409279229', 'Ali Mahmud', '+62341423030', 'ali@mail.com', NULL, NULL, '2016-11-29 15:20:11', NULL, NULL, NULL, NULL, 0.00, 0.00, '1', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/161208112343655566', 'MLG/CAB/20161013101442616542', NULL, 'AFL/MLG/20161101075409279229', 'Reza Oktovian', '021423030', 'arap@mail.com', NULL, NULL, '2016-12-08 11:23:43', 'http://dev.citridia.com/ws.jastrik/manifest/ktpagen/AGNMLG161208112343655566.png', 'http://dev.citridia.com/ws.jastrik/manifest/kkagen/AGNMLG161208112343655566.png', NULL, NULL, 0.00, 0.00, '0', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/161208112728618072', 'MLG/CAB/20161013101442616542', NULL, 'AFL/MLG/20161101075409279229', 'Kemal Palevi', '0341423030', 'kemal@mail.com', NULL, NULL, '2016-12-08 11:27:28', NULL, NULL, NULL, NULL, 0.00, 0.00, '0', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/161208112905459184', 'MLG/CAB/20161013101442616542', NULL, 'AFL/MLG/20161101075409279229', 'Andi Noya', '0341423030', 'andinoya@mail.com', NULL, NULL, '2016-12-08 11:29:05', NULL, NULL, NULL, NULL, 0.00, 0.00, '0', NULL, NULL, '0', NULL, '0'),
	('AGN/MLG/17012444144', 'MLG/CAB/20161013101442616542', 'CHK/MLG/20161018150202269169', 'AFL/MLG/20161101075409279229', 'Agen Hery', '+6234343', 'speed.rcm99@gmail.com', 'xZVjTfXhq5dkh4jOOPMAxnaY5wG3', 'caggUvY6UGg:APA91bF5IqoF4sf8MK1UbvfVM-RCcEYwX4Vr_hxeYvgUe59RYHFev0dJh2Ng7n4N9ieCo9ca1OF4ESo99iBKFdZWdYXs27pmqKThG6zSj5iac13bcPMmIkWsi4yflnkpiJSAcNNFhU-M', '2016-12-08 11:29:05', 'http://localhost/setrika_cabang/cabang/server/php/files/17012410413801.png', 'http://localhost/setrika_cabang/cabang/server/php/files/170124104140161208123501def.png', NULL, 'lamun peryogi mangga talatah lamun henteu mangga wangsul', 0.00, 0.00, '1', 5.0, 5.0, '0', 'agen ieu henteu peryogi artos,agen ieu henteu terpercaya,urang saleresna oge embung ngaladenan anjeun. \r\nkumakarep anjeun hoyong atawa henteu. margi layanan urang awon pisan,nanging urang sok satia.', '0'),
	('AGN/MLG/170404104340709857', NULL, NULL, NULL, 'kardun', '14045', 'aku@luwe.com', NULL, NULL, '2017-04-04 10:43:40', NULL, NULL, NULL, NULL, 0.00, NULL, '0', NULL, NULL, NULL, NULL, '0');
/*!40000 ALTER TABLE `agen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_absen
DROP TABLE IF EXISTS `agen_absen`;
CREATE TABLE IF NOT EXISTS `agen_absen` (
  `kode_agen_absen` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_checker` varchar(50) NOT NULL,
  `tanggal_agen_absen` datetime DEFAULT NULL,
  `transaksi_masuk` bigint(20) DEFAULT NULL,
  `omzet_masuk` double(12,2) DEFAULT NULL,
  `transaksi_dikerjakan` bigint(20) DEFAULT NULL,
  `transaksi_selesai` bigint(20) DEFAULT NULL,
  `pengaduan_tuntas` bigint(20) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_absen`),
  KEY `agen_absen_FKIndex1` (`kode_agen`),
  KEY `agen_absen_FKIndex2` (`kode_checker`),
  CONSTRAINT `agen_absen_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_absen_ibfk_2` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_absen: ~3 rows (approximately)
/*!40000 ALTER TABLE `agen_absen` DISABLE KEYS */;
INSERT INTO `agen_absen` (`kode_agen_absen`, `kode_agen`, `kode_checker`, `tanggal_agen_absen`, `transaksi_masuk`, `omzet_masuk`, `transaksi_dikerjakan`, `transaksi_selesai`, `pengaduan_tuntas`, `hapus`) VALUES
	('aaa', 'AGN/KPN/161208131039173921', 'CHK/MLG/20161018150202269169', '2017-04-26 12:59:11', 5, 90000.00, 2, 0, 0, '0'),
	('VIS/170425151056862182', 'AGN/BDG/161208114539704602', 'CHK/MLG/20161018150202269169', '2017-04-29 19:33:00', 2, 3000.00, 2, 2, 3, '0'),
	('VIS/170425151056862188', 'AGN/KPN/161208131039173921', 'CHK/MLG/20161018150202269169', '2017-04-25 15:10:56', 0, NULL, 1, 4, 0, '0');
/*!40000 ALTER TABLE `agen_absen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_alamat
DROP TABLE IF EXISTS `agen_alamat`;
CREATE TABLE IF NOT EXISTS `agen_alamat` (
  `kode_agen` varchar(50) NOT NULL,
  `kode_kota` char(4) DEFAULT NULL,
  `kode_kecamatan` char(10) DEFAULT NULL,
  `kode_kelurahan` char(15) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `kodepos` char(5) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `agen_alamat_FKIndex1` (`kode_agen`),
  KEY `agen_alamat_ibfk_2` (`kode_kota`),
  KEY `agen_alamat_ibfk_3` (`kode_kecamatan`),
  KEY `agen_alamat_ibfk_4` (`kode_kelurahan`),
  CONSTRAINT `agen_alamat_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`) ON DELETE CASCADE,
  CONSTRAINT `agen_alamat_ibfk_2` FOREIGN KEY (`kode_kota`) REFERENCES `kota` (`kode_kota`),
  CONSTRAINT `agen_alamat_ibfk_3` FOREIGN KEY (`kode_kecamatan`) REFERENCES `kecamatan` (`kode_kecamatan`),
  CONSTRAINT `agen_alamat_ibfk_4` FOREIGN KEY (`kode_kelurahan`) REFERENCES `kelurahan` (`kode_kelurahan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_alamat: ~20 rows (approximately)
/*!40000 ALTER TABLE `agen_alamat` DISABLE KEYS */;
INSERT INTO `agen_alamat` (`kode_agen`, `kode_kota`, `kode_kecamatan`, `kode_kelurahan`, `alamat`, `kodepos`, `latitude`, `longitude`, `hapus`) VALUES
	('AGN/MLG/01010101', 'MLG', NULL, NULL, 'Malang', '65141', '-7.9424412', '112.5781896', '0'),
	('AGN/MLG/02020202', 'MLG', NULL, NULL, 'Malang', '65141', '-7.944014', '112.5815803', '0'),
	('AGN/MLG/03030303', NULL, NULL, NULL, 'Malang', '65141', '-7.944145', '112.5851965', '0'),
	('AGN/MLG/04040404', NULL, NULL, NULL, 'Malang', '65141', '-7.94369', '112.5889413', '0'),
	('AGN/MLG/05050505', NULL, NULL, NULL, 'Malang', '65141', '-7.928888', '112.5797033', '0'),
	('AGN/MLG/06060606', NULL, NULL, NULL, 'Malang', '65141', '-7.9452601', '112.5995129', '0'),
	('AGN/MLG/07070707', NULL, NULL, NULL, 'Malang', '65141', '-7.941387', '112.6059253', '0'),
	('AGN/MLG/08080808', NULL, NULL, NULL, 'Malang', '65141', '-7.9345387', '112.6121893', '0'),
	('AGN/MLG/09090909', NULL, NULL, NULL, 'Malang', '65141', '-7.9428662', '112.5902918', '0'),
	('AGN/MLG/11111111', NULL, NULL, NULL, 'Lowokwaru Kav 90B', '65141', '-7.9442898', '112.6106009', '0'),
	('AGN/MLG/161129152011027450', 'MLG', '3573010', '3573010001', 'ARJOWINANGUN', '65132', '-7.9606108', '112.6026616', '0'),
	('AGN/MLG/161208112343655566', 'MLG', '3573010', '3573010001', 'ARJOWINANGUN', '65132', NULL, NULL, '0'),
	('AGN/MLG/161208112728618072', 'MLG', '3573010', '3573010001', 'ARJOWINANGUN', '65132', NULL, NULL, '0'),
	('AGN/MLG/161208112905459184', 'MLG', '3573010', '3573010001', 'ARJOWINANGUN', '65132', NULL, NULL, '0'),
	('AGN/BDG/161208114539704602', 'BDG', '3273010', '3273010006', 'KOTA BANDUNG', '40211', '-8.783195', '34.508523', '0'),
	('AGN/KPN/161208131039173921', 'KPN', '3507280', '3507280010', 'jl. tumapel gg.4 no.10', '65153', '-7.894147', '112.663764', '0'),
	('AGN/KPN/161208164447540229', 'KPN', '3507280', '3507280015', 'ndek ndi hayoo', '65153', NULL, NULL, '0'),
	('AGN/MLG/17012444144', 'MLG', '3573010', '3573010001', NULL, NULL, NULL, NULL, '0'),
	('AGN/MLG/170404104340709857', 'MLG', '3573050', '3573050006', 'lowokwaru', '65141', NULL, NULL, '0'),
	('AGN/KPN/170410090632673431', 'KPN', '3507280', '3507280010', 'tumafeel', '65153', NULL, NULL, '0');
/*!40000 ALTER TABLE `agen_alamat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_bank
DROP TABLE IF EXISTS `agen_bank`;
CREATE TABLE IF NOT EXISTS `agen_bank` (
  `kode_agen_bank` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_agen` varchar(50) NOT NULL,
  `kode_jenis_bank` smallint(5) unsigned NOT NULL,
  `norek` varchar(20) DEFAULT NULL,
  `atas_nama` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_bank`),
  KEY `agen_bank_FKIndex1` (`kode_agen`),
  KEY `agen_bank_FKIndex2` (`kode_jenis_bank`),
  CONSTRAINT `agen_bank_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_bank_ibfk_2` FOREIGN KEY (`kode_jenis_bank`) REFERENCES `jenis_bank` (`kode_jenis_bank`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_bank: ~0 rows (approximately)
/*!40000 ALTER TABLE `agen_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `agen_bank` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_diskusi
DROP TABLE IF EXISTS `agen_diskusi`;
CREATE TABLE IF NOT EXISTS `agen_diskusi` (
  `kode_agen_diskusi` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_konsumen` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `isi_agen_diskusi` text,
  `tanggal_agen_diskusi` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_diskusi`),
  KEY `agen_diskusi_FKIndex1` (`kode_agen`),
  KEY `agen_diskusi_FKIndex2` (`kode_konsumen`),
  CONSTRAINT `agen_diskusi_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_diskusi_ibfk_2` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_diskusi: ~16 rows (approximately)
/*!40000 ALTER TABLE `agen_diskusi` DISABLE KEYS */;
INSERT INTO `agen_diskusi` (`kode_agen_diskusi`, `kode_konsumen`, `kode_agen`, `isi_agen_diskusi`, `tanggal_agen_diskusi`, `hapus`) VALUES
	(2, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Agen ini laki2 / perempuan ?', '2016-12-04 12:50:30', '0'),
	(3, 'KNS/MLG/20161018111310949492', 'AGN/KPN/161208131039173921', 'Mas ini bisa dianter langsung ke rumah ga?', '2016-12-08 14:37:09', '0'),
	(18, 'KNS/MLG/20161018111310949492', 'AGN/KPN/161208131039173921', 'Mas, boleh kenalan?', '2016-12-10 12:55:01', '0'),
	(23, 'KNS/JKT/20161016075806869469', 'AGN/MLG/11111111', 'new', '2016-12-10 13:25:30', '0'),
	(25, 'KNS/JKT/20161016075806869469', 'AGN/MLG/02020202', 'diskusi kuy', '2016-12-10 16:10:58', '0'),
	(26, 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', 'wew', '2017-01-24 16:49:13', '0'),
	(27, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Anda Habib?', '2017-01-31 17:48:15', '0'),
	(28, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Anda Habib?', '2017-01-31 17:49:08', '0'),
	(29, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Anda Habib?', '2017-01-31 17:50:21', '0'),
	(30, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Anda Habib?', '2017-01-31 17:50:55', '0'),
	(31, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Anda Habib?', '2017-01-31 17:56:39', '0'),
	(32, 'KNS/JKT/20161016075806869469', 'AGN/MLG/01010101', 'Anda Habib?', '2017-01-31 17:57:16', '0'),
	(33, 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', 'agkll', '2017-01-31 20:24:08', '0'),
	(34, 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', 'y', '2017-02-01 08:44:21', '0'),
	(35, 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', 'tes tis', '2017-02-01 17:31:07', '0'),
	(36, 'KNS/MLG/170303193545637965', 'AGN/KPN/161208131039173921', 'Dugong bakar rasa stroberi', '2017-03-03 19:40:22', '0');
/*!40000 ALTER TABLE `agen_diskusi` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_diskusi_komentar
DROP TABLE IF EXISTS `agen_diskusi_komentar`;
CREATE TABLE IF NOT EXISTS `agen_diskusi_komentar` (
  `kode_agen_diskusi_komentar` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_konsumen` varchar(50) DEFAULT NULL,
  `kode_agen` varchar(50) DEFAULT NULL,
  `kode_agen_diskusi` bigint(20) NOT NULL,
  `isi_agen_diskusi_komentar` text,
  `tanggal_agen_diskusi_komentar` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_diskusi_komentar`),
  KEY `agen_diskusi_komentar_FKIndex1` (`kode_agen_diskusi`),
  KEY `agen_diskusi_komentar_FKIndex2` (`kode_agen`),
  KEY `agen_diskusi_komentar_FKIndex3` (`kode_konsumen`),
  CONSTRAINT `agen_diskusi_komentar_ibfk_1` FOREIGN KEY (`kode_agen_diskusi`) REFERENCES `agen_diskusi` (`kode_agen_diskusi`),
  CONSTRAINT `agen_diskusi_komentar_ibfk_2` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_diskusi_komentar_ibfk_3` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_diskusi_komentar: ~44 rows (approximately)
/*!40000 ALTER TABLE `agen_diskusi_komentar` DISABLE KEYS */;
INSERT INTO `agen_diskusi_komentar` (`kode_agen_diskusi_komentar`, `kode_konsumen`, `kode_agen`, `kode_agen_diskusi`, `isi_agen_diskusi_komentar`, `tanggal_agen_diskusi_komentar`, `hapus`) VALUES
	(2, NULL, 'AGN/MLG/01010101', 2, 'Perempuan', '2016-12-04 12:54:34', '0'),
	(3, 'KNS/JKT/20161016075806869469', NULL, 2, 'Sejam berapa ?', '2016-12-04 12:56:12', '0'),
	(4, NULL, 'AGN/MLG/01010101', 2, 'Emang gue cewek apaan ?', '2016-12-04 12:56:35', '0'),
	(5, 'KNS/JKT/20161016075806869469', NULL, 2, 'Sejam 60 menit goblok !', '2016-12-04 12:56:57', '0'),
	(6, NULL, 'AGN/MLG/01010101', 2, 'Mas, berapaan?', '2016-12-08 14:16:08', '0'),
	(7, 'KNS/JKT/20161016075806869469', NULL, 2, 'Murah mbak', '2016-12-08 14:16:28', '0'),
	(8, 'KNS/JKT/20161016075806869469', NULL, 2, 'kalo mbaknya cakep + semok gratis lah', '2016-12-08 17:51:11', '0'),
	(9, 'KNS/JKT/20161016075806869469', NULL, 2, 'gimana ?', '2016-12-08 17:51:41', '0'),
	(10, 'KNS/JKT/20161018123856604725', NULL, 2, 'bangsat iki malah kate mesum ?', '2016-12-08 17:54:56', '0'),
	(11, 'KNS/JKT/20161016075806869469', NULL, 2, 'pingin ya ?', '2016-12-08 17:56:20', '0'),
	(12, NULL, 'AGN/MLG/01010101', 2, 'Mau dong', '2016-12-08 18:07:44', '0'),
	(13, NULL, 'AGN/MLG/01010101', 2, 'Mau dong', '2016-12-08 18:10:58', '0'),
	(14, 'KNS/JKT/20161016075806869469', NULL, 2, 'ayo ketemuan mbak', '2016-12-08 18:19:40', '0'),
	(15, 'KNS/JKT/20161018123856604725', NULL, 2, 'loh kan gak ajak-ajak', '2016-12-08 18:40:12', '0'),
	(16, 'KNS/JKT/20161016075806869469', NULL, 3, 'tes', '2016-12-10 13:11:27', '1'),
	(17, 'KNS/JKT/20161016075806869469', NULL, 3, 'tes', '2016-12-10 13:11:36', '1'),
	(18, 'KNS/JKT/20161016075806869469', NULL, 3, 'tes', '2016-12-10 13:11:39', '1'),
	(19, 'KNS/JKT/20161016075806869469', NULL, 3, 'tes', '2016-12-10 13:11:45', '1'),
	(20, 'KNS/JKT/20161016075806869469', NULL, 3, 'tes', '2016-12-10 13:12:01', '0'),
	(21, 'KNS/JKT/20161016075806869469', NULL, 3, 'dlflf', '2016-12-10 13:12:08', '1'),
	(22, 'KNS/JKT/20161016075806869469', NULL, 3, 'hjdkflfl', '2016-12-10 13:12:13', '0'),
	(23, 'KNS/JKT/20161016075806869469', NULL, 18, 'jdkflfl', '2016-12-10 13:12:31', '0'),
	(24, 'KNS/JKT/20161016075806869469', NULL, 18, 'f', '2016-12-10 13:13:29', '0'),
	(25, 'KNS/JKT/20161016075806869469', NULL, 18, 'm', '2016-12-10 13:15:21', '0'),
	(26, 'KNS/JKT/20161016075806869469', NULL, 18, 'z', '2016-12-10 13:18:08', '0'),
	(27, 'KNS/JKT/20161016075806869469', NULL, 18, 'q', '2016-12-10 13:21:27', '0'),
	(28, 'KNS/JKT/20161016075806869469', NULL, 18, 'a', '2016-12-10 13:21:33', '0'),
	(29, 'KNS/JKT/20161016075806869469', NULL, 3, 'a', '2016-12-10 13:21:39', '0'),
	(30, 'KNS/JKT/20161016075806869469', NULL, 18, 'a', '2016-12-10 13:24:53', '0'),
	(31, 'KNS/JKT/20161016075806869469', NULL, 18, 'a', '2016-12-10 13:25:16', '0'),
	(32, 'KNS/JKT/20161016075806869469', NULL, 23, 'm', '2016-12-10 13:25:38', '0'),
	(33, 'KNS/JKT/20161016075806869469', NULL, 2, 'babah', '2016-12-10 13:39:55', '0'),
	(34, NULL, 'AGN/MLG/01010101', 2, 'Halo ini saya', '2016-12-20 11:01:03', '0'),
	(35, NULL, 'AGN/MLG/01010101', 2, 'Halo ini saya', '2016-12-20 11:05:01', '0'),
	(36, NULL, 'AGN/MLG/01010101', 2, 'Halo ini saya', '2016-12-20 11:05:19', '0'),
	(37, 'KNS/JKT/20161018123856604725', NULL, 2, 'aku pingin salto', '2016-12-20 14:55:58', '1'),
	(38, 'KNS/JKT/20161016075806869469', NULL, 26, 'tes', '2017-01-24 16:49:39', '1'),
	(39, 'KNS/KPN/170222165428191495', NULL, 3, 'hih', '2017-02-26 20:41:48', '0'),
	(40, 'KNS/KPN/161217184934374199', NULL, 35, 'biji zakar', '2017-03-03 16:17:59', '0'),
	(41, 'KNS/JKT/20161016075806869469', NULL, 3, 'well', '2017-03-04 00:07:08', '1'),
	(42, 'KNS/JKT/20161016075806869469', NULL, 3, 'wells', '2017-03-04 00:07:35', '0'),
	(43, 'KNS/JKT/20161016075806869469', NULL, 34, 'wew', '2017-03-04 00:11:27', '1'),
	(44, 'KNS/MLG/170303193545637965', NULL, 3, 'faq', '2017-03-06 15:03:07', '0'),
	(45, NULL, 'AGN/KPN/161208131039173921', 3, 'bangke rame ae', '2017-03-29 07:58:26', '0');
/*!40000 ALTER TABLE `agen_diskusi_komentar` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_history_inventory
DROP TABLE IF EXISTS `agen_history_inventory`;
CREATE TABLE IF NOT EXISTS `agen_history_inventory` (
  `kode_history_inventory` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_inventory` varchar(100) NOT NULL,
  `kode_checker` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshcabang` varchar(50) DEFAULT NULL,
  `stok_masuk` double(12,3) DEFAULT NULL,
  `stok_keluar` double(12,3) DEFAULT NULL,
  `tanggal_history_inventory` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_history_inventory`),
  KEY `ahi_to_agen_fk1` (`kode_agen`),
  KEY `ahi_to_inventory_fk2` (`kode_inventory`),
  KEY `ahi_to_kode_checker_fk3` (`kode_checker`),
  KEY `ahi_to_pegawai_pshcabang_fk4` (`kode_pegawai_pshcabang`),
  CONSTRAINT `ahi_to_agen_fk1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `ahi_to_inventory_fk2` FOREIGN KEY (`kode_inventory`) REFERENCES `inventory` (`kode_inventory`),
  CONSTRAINT `ahi_to_kode_checker_fk3` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`),
  CONSTRAINT `ahi_to_pegawai_pshcabang_fk4` FOREIGN KEY (`kode_pegawai_pshcabang`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_history_inventory: ~26 rows (approximately)
/*!40000 ALTER TABLE `agen_history_inventory` DISABLE KEYS */;
INSERT INTO `agen_history_inventory` (`kode_history_inventory`, `kode_agen`, `kode_inventory`, `kode_checker`, `kode_pegawai_pshcabang`, `stok_masuk`, `stok_keluar`, `tanggal_history_inventory`, `hapus`) VALUES
	('AHI/170418140155171568', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 3.000, '2017-04-18 14:01:55', '0'),
	('AHI/170418140155177994', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 5.000, '2017-04-18 14:01:55', '0'),
	('AHI/170418140256675107', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 3.000, '2017-04-18 14:02:56', '0'),
	('AHI/170418140256680609', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 5.000, '2017-04-18 14:02:56', '0'),
	('AHI/170419180746668817', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 5.000, '2017-04-19 18:07:46', '0'),
	('AHI/170419180746677876', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 50.000, '2017-04-19 18:07:46', '0'),
	('AHI/170419181408673593', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:14:08', '0'),
	('AHI/170419181408693502', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:14:08', '0'),
	('AHI/170419181546163713', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:15:46', '0'),
	('AHI/170419181546169621', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:15:46', '0'),
	('AHI/170419182130831210', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:21:30', '0'),
	('AHI/170419182130848471', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:21:30', '0'),
	('AHI/170419182425548758', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:24:25', '0'),
	('AHI/170419182425570803', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:24:25', '0'),
	('AHI/170419182540830797', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:25:40', '0'),
	('AHI/170419182540837089', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:25:40', '0'),
	('AHI/170419184021172017', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:40:21', '0'),
	('AHI/170419184021179416', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:40:21', '0'),
	('AHI/170419184257981394', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:42:57', '0'),
	('AHI/170419184257987414', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:42:57', '0'),
	('AHI/170419184423979884', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:44:23', '0'),
	('AHI/170419184423984574', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:44:23', '0'),
	('AHI/170419184920341551', 'AGN/KPN/161208131039173921', '123213123', 'CHK/MLG/20161018150202269169', NULL, NULL, 1.000, '2017-04-19 18:49:20', '0'),
	('AHI/170419184920345544', 'AGN/KPN/161208131039173921', 'PRF0001', 'CHK/MLG/20161018150202269169', NULL, NULL, 0.000, '2017-04-19 18:49:20', '0'),
	('KHI/20170418125712343', 'AGN/KPN/161208131039173921', '123213123', NULL, 'KRY/CBG/0001', 50.000, NULL, '2017-04-18 12:58:18', '0'),
	('KHI/20170418125989637', 'AGN/KPN/161208131039173921', 'PRF0001', NULL, 'KRY/CBG/0001', 50.500, NULL, '2017-04-18 12:59:20', '0');
/*!40000 ALTER TABLE `agen_history_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_inventory
DROP TABLE IF EXISTS `agen_inventory`;
CREATE TABLE IF NOT EXISTS `agen_inventory` (
  `kode_agen` varchar(50) NOT NULL,
  `kode_inventory` varchar(100) NOT NULL,
  `jml_stok` double(12,3) NOT NULL,
  `hapus` char(1) DEFAULT '0',
  PRIMARY KEY (`kode_agen`,`kode_inventory`),
  KEY `agen_inventory_to_inventory_fk2` (`kode_inventory`),
  CONSTRAINT `agen_inventory_to_agen_fk1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_inventory_to_inventory_fk2` FOREIGN KEY (`kode_inventory`) REFERENCES `inventory` (`kode_inventory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_inventory: ~2 rows (approximately)
/*!40000 ALTER TABLE `agen_inventory` DISABLE KEYS */;
INSERT INTO `agen_inventory` (`kode_agen`, `kode_inventory`, `jml_stok`, `hapus`) VALUES
	('AGN/KPN/161208131039173921', '123213123', 30.000, '0'),
	('AGN/KPN/161208131039173921', 'PRF0001', -9.500, '0');
/*!40000 ALTER TABLE `agen_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_layanan_harga
DROP TABLE IF EXISTS `agen_layanan_harga`;
CREATE TABLE IF NOT EXISTS `agen_layanan_harga` (
  `kode_agen` varchar(50) NOT NULL,
  `kode_harga_layanan` int(10) unsigned NOT NULL,
  `status_aktif` smallint(5) unsigned DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen`,`kode_harga_layanan`),
  KEY `layanan_harga_has_agen_FKIndex1` (`kode_harga_layanan`),
  KEY `layanan_harga_has_agen_FKIndex2` (`kode_agen`),
  CONSTRAINT `fk_kode_agen_agen_layanan_harga` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_kode_harga_layanan_harga_layanan` FOREIGN KEY (`kode_harga_layanan`) REFERENCES `layanan_harga` (`kode_harga_layanan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_layanan_harga: ~45 rows (approximately)
/*!40000 ALTER TABLE `agen_layanan_harga` DISABLE KEYS */;
INSERT INTO `agen_layanan_harga` (`kode_agen`, `kode_harga_layanan`, `status_aktif`, `hapus`) VALUES
	('AGN/KPN/161208131039173921', 7, 1, '0'),
	('AGN/KPN/161208131039173921', 12, 1, '0'),
	('AGN/KPN/161208131039173921', 14, 1, '0'),
	('AGN/KPN/161208131039173921', 15, 1, '0'),
	('AGN/KPN/161208131039173921', 23, 1, '0'),
	('AGN/MLG/01010101', 8, 1, '0'),
	('AGN/MLG/01010101', 9, 1, '0'),
	('AGN/MLG/01010101', 14, 1, '0'),
	('AGN/MLG/09090909', 8, 1, '0'),
	('AGN/MLG/09090909', 9, 1, '0'),
	('AGN/MLG/09090909', 11, 1, '0'),
	('AGN/MLG/09090909', 12, 1, '0'),
	('AGN/MLG/09090909', 13, 1, '0'),
	('AGN/MLG/09090909', 14, 1, '0'),
	('AGN/MLG/09090909', 15, 1, '0'),
	('AGN/MLG/09090909', 16, 1, '0'),
	('AGN/MLG/09090909', 17, 1, '0'),
	('AGN/MLG/09090909', 18, 1, '0'),
	('AGN/MLG/09090909', 19, 1, '0'),
	('AGN/MLG/09090909', 20, 1, '0'),
	('AGN/MLG/09090909', 21, 1, '0'),
	('AGN/MLG/09090909', 22, 1, '0'),
	('AGN/MLG/09090909', 23, 1, '0'),
	('AGN/MLG/11111111', 13, 1, '0'),
	('AGN/MLG/11111111', 14, 1, '0'),
	('AGN/MLG/161129152011027450', 8, 1, '0'),
	('AGN/MLG/161129152011027450', 9, 1, '0'),
	('AGN/MLG/161129152011027450', 11, 1, '0'),
	('AGN/MLG/161129152011027450', 12, 1, '0'),
	('AGN/MLG/161129152011027450', 14, 0, '0'),
	('AGN/MLG/161129152011027450', 15, 1, '0'),
	('AGN/MLG/161129152011027450', 16, 1, '0'),
	('AGN/MLG/161129152011027450', 18, 1, '0'),
	('AGN/MLG/161129152011027450', 19, 1, '0'),
	('AGN/MLG/161129152011027450', 20, 1, '0'),
	('AGN/MLG/161129152011027450', 22, 1, '0'),
	('AGN/MLG/161129152011027450', 23, 1, '0'),
	('AGN/MLG/17012444144', 8, 1, '0'),
	('AGN/MLG/17012444144', 9, 1, '0'),
	('AGN/MLG/17012444144', 13, 1, '0'),
	('AGN/MLG/17012444144', 16, 1, '0'),
	('AGN/MLG/17012444144', 17, 1, '0'),
	('AGN/MLG/17012444144', 20, 1, '0'),
	('AGN/MLG/17012444144', 21, 1, '0'),
	('AGN/MLG/17012444144', 22, 1, '0');
/*!40000 ALTER TABLE `agen_layanan_harga` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_pencairan_fee
DROP TABLE IF EXISTS `agen_pencairan_fee`;
CREATE TABLE IF NOT EXISTS `agen_pencairan_fee` (
  `kode_agen_pencairan_fee` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_agen_bank` bigint(20) NOT NULL,
  `kode_bank_pusat` smallint(5) unsigned NOT NULL,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `tanggal_request` datetime DEFAULT NULL,
  `tanggal_terima` datetime DEFAULT NULL,
  `status_agen_pencairan_fee` char(1) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_pencairan_fee`),
  KEY `agen_pencairan_fee_FKIndex1` (`kode_agen`),
  KEY `agen_pencairan_fee_FKIndex2` (`kode_agen_bank`),
  KEY `agen_pencairan_fee_FKIndex3` (`kode_bank_pusat`),
  KEY `agen_pencairan_fee_FKIndex4` (`kode_pegawai_pshpusat`),
  CONSTRAINT `agen_pencairan_fee_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_pencairan_fee_ibfk_2` FOREIGN KEY (`kode_agen_bank`) REFERENCES `agen_bank` (`kode_agen_bank`),
  CONSTRAINT `agen_pencairan_fee_ibfk_3` FOREIGN KEY (`kode_bank_pusat`) REFERENCES `bank_pusat` (`kode_bank_pusat`),
  CONSTRAINT `agen_pencairan_fee_ibfk_4` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_pencairan_fee: ~0 rows (approximately)
/*!40000 ALTER TABLE `agen_pencairan_fee` DISABLE KEYS */;
/*!40000 ALTER TABLE `agen_pencairan_fee` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_setoran_dana
DROP TABLE IF EXISTS `agen_setoran_dana`;
CREATE TABLE IF NOT EXISTS `agen_setoran_dana` (
  `kode_agen_setoran_dana` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_checker` varchar(50) NOT NULL,
  `nominal_agen_setoran_dana` double(12,2) DEFAULT NULL,
  `tanggal_agen_setoran_dana` datetime DEFAULT NULL,
  `status_agen_setoran_dana` char(1) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_setoran_dana`),
  KEY `agen_setoran_dana_FKIndex1` (`kode_agen`),
  KEY `agen_setoran_dana_FKIndex2` (`kode_checker`),
  CONSTRAINT `agen_setoran_dana_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_setoran_dana_ibfk_2` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_setoran_dana: ~1 rows (approximately)
/*!40000 ALTER TABLE `agen_setoran_dana` DISABLE KEYS */;
INSERT INTO `agen_setoran_dana` (`kode_agen_setoran_dana`, `kode_agen`, `kode_checker`, `nominal_agen_setoran_dana`, `tanggal_agen_setoran_dana`, `status_agen_setoran_dana`, `hapus`) VALUES
	('RSDA/170410074358418944', 'AGN/KPN/161208131039173921', 'CHK/MLG/20161018150202269169', 472000.00, '2017-04-10 07:43:58', '1', '0');
/*!40000 ALTER TABLE `agen_setoran_dana` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.agen_temp_kode_absen
DROP TABLE IF EXISTS `agen_temp_kode_absen`;
CREATE TABLE IF NOT EXISTS `agen_temp_kode_absen` (
  `kode_agen_temp_kode_absen` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_checker` varchar(50) NOT NULL,
  `time_generate` time NOT NULL,
  `time_expired` time NOT NULL,
  `enkripsi` varchar(255) NOT NULL,
  `scan` char(1) NOT NULL DEFAULT '0',
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen_temp_kode_absen`),
  KEY `agen_temp_kode_absen_FKIndex1` (`kode_agen`),
  KEY `agen_temp_kode_absen_ibfk_2` (`kode_checker`),
  CONSTRAINT `agen_temp_kode_absen_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `agen_temp_kode_absen_ibfk_2` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.agen_temp_kode_absen: ~3 rows (approximately)
/*!40000 ALTER TABLE `agen_temp_kode_absen` DISABLE KEYS */;
INSERT INTO `agen_temp_kode_absen` (`kode_agen_temp_kode_absen`, `kode_agen`, `kode_checker`, `time_generate`, `time_expired`, `enkripsi`, `scan`, `hapus`) VALUES
	('170425120315', 'AGN/KPN/161208131039173921', 'CHK/MLG/20161018150202269169', '12:03:15', '12:04:15', 'd7b90ee3a504967db6aa59fe27ad683d', '1', '0'),
	('170427111644', 'AGN/KPN/161208131039173921', 'CHK/MLG/20161018150202269169', '11:16:44', '11:17:44', 'd7b90ee3a504967db6aa59fe27ad683d', '0', '0'),
	('170427112132', 'AGN/KPN/161208131039173921', 'CHK/MLG/20161018150202269169', '11:21:32', '11:22:32', 'd7b90ee3a504967db6aa59fe27ad683d', '0', '0');
/*!40000 ALTER TABLE `agen_temp_kode_absen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.akun_keuangan_cabang
DROP TABLE IF EXISTS `akun_keuangan_cabang`;
CREATE TABLE IF NOT EXISTS `akun_keuangan_cabang` (
  `kode_akun_keuangan_cabang` varchar(50) NOT NULL,
  `kode_kategori_akun_cabang` varchar(50) NOT NULL,
  `nama_akun_keuangan_cabang` varchar(50) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_akun_keuangan_cabang`,`kode_pshcabang`),
  KEY `akun_keuangan_cabang_FKIndex1` (`kode_kategori_akun_cabang`),
  CONSTRAINT `fk_kategori_akun_cabang` FOREIGN KEY (`kode_kategori_akun_cabang`) REFERENCES `kategori_akun_cabang` (`kode_kategori_akun_cabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.akun_keuangan_cabang: ~11 rows (approximately)
/*!40000 ALTER TABLE `akun_keuangan_cabang` DISABLE KEYS */;
INSERT INTO `akun_keuangan_cabang` (`kode_akun_keuangan_cabang`, `kode_kategori_akun_cabang`, `nama_akun_keuangan_cabang`, `keterangan`, `kode_pshcabang`, `hapus`) VALUES
	('1.01.001', '1.01.000', 'kas kasir Cabang Bandung', NULL, 'BDG/CAB/6543', '0'),
	('1.01.001', '1.01.000', 'Kas Cabang Malang', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('1.02.001', '1.02.000', 'BCA 120339920 A/n PT. Cabang Banding', NULL, 'BDG/CAB/6543', '0'),
	('1.02.001', '1.02.000', 'Bank BCA Malang 1209238', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('1.02.003', '1.02.000', 'Mandiri Mlaang 23912312030213 A/n swoana', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('1.03.001', '1.03.000', 'Piutang Usaha', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('1.04.001', '1.04.000', 'Persediaan Bahan Baku', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('2.01.001', '2.01.000', 'Hutang ke Pak Andi', NULL, 'BDG/CAB/6543', '0'),
	('2.01.001', '2.01.000', 'Hutang Usaha', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('3.01.001', '3.01.000', 'Modal Usaha', NULL, 'MLG/CAB/20161013101442616542', '0'),
	('5.01.001', '5.01.000', 'Biaya Bahan Baku', NULL, 'MLG/CAB/20161013101442616542', '0');
/*!40000 ALTER TABLE `akun_keuangan_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.akun_keuangan_pusat
DROP TABLE IF EXISTS `akun_keuangan_pusat`;
CREATE TABLE IF NOT EXISTS `akun_keuangan_pusat` (
  `kode_akun_keuangan_pusat` varchar(50) NOT NULL,
  `kode_kategori_akun_pusat` varchar(50) NOT NULL,
  `nama_akun_keuangan_pusat` varchar(50) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_akun_keuangan_pusat`),
  KEY `akun_keuangan_pusat_FKIndex1` (`kode_kategori_akun_pusat`),
  CONSTRAINT `fk_kategori_keuangan_pusat` FOREIGN KEY (`kode_kategori_akun_pusat`) REFERENCES `kategori_akun_pusat` (`kode_kategori_akun_pusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.akun_keuangan_pusat: ~4 rows (approximately)
/*!40000 ALTER TABLE `akun_keuangan_pusat` DISABLE KEYS */;
INSERT INTO `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`, `kode_kategori_akun_pusat`, `nama_akun_keuangan_pusat`, `keterangan`, `hapus`) VALUES
	('1.01.001', '1.01.000', 'Kas Di Kasir', NULL, '0'),
	('1.02.001', '1.02.000', 'Mandiri 140794  Desvita aliagara', NULL, '0'),
	('1.02.003', '1.02.000', 'BCA 92881000 A.n Hery Kuswandi', NULL, '0'),
	('AKK/01', 'KAA/01', 'Akun Keuangan Percobaan Beli Dompet', 'Percobaan Pertama', '0');
/*!40000 ALTER TABLE `akun_keuangan_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.bank_cabang
DROP TABLE IF EXISTS `bank_cabang`;
CREATE TABLE IF NOT EXISTS `bank_cabang` (
  `kode_bank_cabang` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_akun_keuangan_cabang` varchar(30) NOT NULL,
  `kode_jenis_bank` smallint(5) unsigned NOT NULL,
  `norek` varchar(20) DEFAULT NULL,
  `atas_nama` varchar(50) DEFAULT NULL,
  `kode_pshcabang` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_bank_cabang`),
  KEY `bank_cabang_FKIndex1` (`kode_jenis_bank`),
  KEY `bank_cabang_FKIndex2` (`kode_akun_keuangan_cabang`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.bank_cabang: ~3 rows (approximately)
/*!40000 ALTER TABLE `bank_cabang` DISABLE KEYS */;
INSERT INTO `bank_cabang` (`kode_bank_cabang`, `kode_akun_keuangan_cabang`, `kode_jenis_bank`, `norek`, `atas_nama`, `kode_pshcabang`, `hapus`) VALUES
	(1, '1.02.001', 1, '102009993', ' PT CABANG BANDUNG', 'BDG/CAB/6543', '0'),
	(2, '1.02.001', 1, '1407993', 'Desvinta Cabang Malang', 'MLG/CAB/20161013101442616542', '0'),
	(3, '1.02.003', 2, '1201293230 ', 'Diandra Larasati', 'MLG/CAB/20161013101442616542', '0');
/*!40000 ALTER TABLE `bank_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.bank_pusat
DROP TABLE IF EXISTS `bank_pusat`;
CREATE TABLE IF NOT EXISTS `bank_pusat` (
  `kode_bank_pusat` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_akun_keuangan_pusat` varchar(50) NOT NULL,
  `kode_jenis_bank` smallint(5) unsigned NOT NULL,
  `norek` varchar(20) DEFAULT NULL,
  `atas_nama` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_bank_pusat`),
  KEY `bank_pusat_FKIndex1` (`kode_jenis_bank`),
  KEY `bank_pusat_FKIndex2` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `bank_pusat_ibfk_1` FOREIGN KEY (`kode_jenis_bank`) REFERENCES `jenis_bank` (`kode_jenis_bank`),
  CONSTRAINT `bank_pusat_ibfk_2` FOREIGN KEY (`kode_akun_keuangan_pusat`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.bank_pusat: ~2 rows (approximately)
/*!40000 ALTER TABLE `bank_pusat` DISABLE KEYS */;
INSERT INTO `bank_pusat` (`kode_bank_pusat`, `kode_akun_keuangan_pusat`, `kode_jenis_bank`, `norek`, `atas_nama`, `hapus`) VALUES
	(1, 'AKK/01', 1, '696969', 'Jasa Setrika Pusat 01', '0'),
	(2, '1.02.001', 2, '140794', 'Hery Kuswandi 2', '0');
/*!40000 ALTER TABLE `bank_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.bayar_fee_pegawai_cabang
DROP TABLE IF EXISTS `bayar_fee_pegawai_cabang`;
CREATE TABLE IF NOT EXISTS `bayar_fee_pegawai_cabang` (
  `kode_bayar_fee_pegawai_cabang` varchar(50) NOT NULL,
  `kode_pegawai_pshcabang` varchar(50) NOT NULL,
  `total_gaji` double(12,2) DEFAULT NULL,
  `tanggal_bayar` datetime DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_bayar_fee_pegawai_cabang`),
  KEY `bayar_fee_pegawai_cabang_FKIndex1` (`kode_pegawai_pshcabang`),
  CONSTRAINT `bayar_fee_pegawai_cabang_ibfk_1` FOREIGN KEY (`kode_pegawai_pshcabang`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.bayar_fee_pegawai_cabang: ~0 rows (approximately)
/*!40000 ALTER TABLE `bayar_fee_pegawai_cabang` DISABLE KEYS */;
/*!40000 ALTER TABLE `bayar_fee_pegawai_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.bayar_fee_pegawai_pusat
DROP TABLE IF EXISTS `bayar_fee_pegawai_pusat`;
CREATE TABLE IF NOT EXISTS `bayar_fee_pegawai_pusat` (
  `kode_bayar_fee_pegawai_pusat` varchar(50) NOT NULL,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `total_gaji` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_bayar_fee_pegawai_pusat`),
  KEY `bayar_fee_pegawai_pusat_FKIndex1` (`kode_pegawai_pshpusat`),
  CONSTRAINT `bayar_fee_pegawai_pusat_ibfk_1` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.bayar_fee_pegawai_pusat: ~0 rows (approximately)
/*!40000 ALTER TABLE `bayar_fee_pegawai_pusat` DISABLE KEYS */;
/*!40000 ALTER TABLE `bayar_fee_pegawai_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.beli_inventory
DROP TABLE IF EXISTS `beli_inventory`;
CREATE TABLE IF NOT EXISTS `beli_inventory` (
  `kode_beli_inventory` varchar(50) NOT NULL,
  `kode_pegawai_pshcabang` varchar(50) NOT NULL,
  `kode_akun_keuangan_cabang` varchar(50) NOT NULL,
  `tanggal_beli_inventory` datetime DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  `status_beli_inventory` char(2) DEFAULT '0' COMMENT '0=menunggu,1= konfirmasi spv ,2=konfirmasi keuangan, 3=terbeli',
  `total` double(12,2) DEFAULT NULL,
  `bukti_beli` text,
  `kode_surat_beli_inventory` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`kode_beli_inventory`),
  KEY `beli_inventory_FKIndex1` (`kode_pegawai_pshcabang`),
  KEY `beli_inventory_FKIndex2` (`kode_akun_keuangan_cabang`),
  CONSTRAINT `beli_inventory_ibfk_1` FOREIGN KEY (`kode_pegawai_pshcabang`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`),
  CONSTRAINT `beli_inventory_ibfk_2` FOREIGN KEY (`kode_akun_keuangan_cabang`) REFERENCES `akun_keuangan_cabang` (`kode_akun_keuangan_cabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.beli_inventory: ~1 rows (approximately)
/*!40000 ALTER TABLE `beli_inventory` DISABLE KEYS */;
INSERT INTO `beli_inventory` (`kode_beli_inventory`, `kode_pegawai_pshcabang`, `kode_akun_keuangan_cabang`, `tanggal_beli_inventory`, `keterangan`, `hapus`, `status_beli_inventory`, `total`, `bukti_beli`, `kode_surat_beli_inventory`) VALUES
	('BIV17041004313997', 'KRY/CBG/0004', '1.01.001', '2017-04-10 16:31:39', 'deterjen dan parfum', '0', '0', 72500.00, '["http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/170410113100green.jpg","http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/170410113100db33e29f6ac22ede16589c362d070a48.jpg","http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/170410113108minatex.jpg"]', 'SPO17040512274362');
/*!40000 ALTER TABLE `beli_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.biaya_keuangan_cabang
DROP TABLE IF EXISTS `biaya_keuangan_cabang`;
CREATE TABLE IF NOT EXISTS `biaya_keuangan_cabang` (
  `kode_biaya_keuangan_cabang` varchar(50) NOT NULL,
  `kode_pegawai_pshcabang` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_cabang_biaya` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_cabang_asset` varchar(50) DEFAULT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) DEFAULT '0',
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kode_biaya_keuangan_cabang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.biaya_keuangan_cabang: 1 rows
/*!40000 ALTER TABLE `biaya_keuangan_cabang` DISABLE KEYS */;
INSERT INTO `biaya_keuangan_cabang` (`kode_biaya_keuangan_cabang`, `kode_pegawai_pshcabang`, `kode_akun_keuangan_cabang_biaya`, `kode_akun_keuangan_cabang_asset`, `nominal`, `tanggal`, `keterangan`, `hapus`, `foto`) VALUES
	('ETK201704280410370041', 'KRY/CBG/0002', '5.01.001', '1.01.001', 20000.00, '2017-04-28 16:10:37', NULL, '0', '["http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/17042841033ktp1.jpg","http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/17042841033Screenshot_22.png"]');
/*!40000 ALTER TABLE `biaya_keuangan_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.biaya_operasional_pusat
DROP TABLE IF EXISTS `biaya_operasional_pusat`;
CREATE TABLE IF NOT EXISTS `biaya_operasional_pusat` (
  `kode_biaya_operasional_pusat` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `kode_akun_keuangan_pusat_debet` varchar(50) NOT NULL,
  `kode_akun_keuangan_pusat_kredit` varchar(50) NOT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_biaya_operasional_pusat`),
  KEY `biaya_operasional_pusat_FKIndex1` (`kode_akun_keuangan_pusat_debet`),
  KEY `biaya_operasional_pusat_FKIndex2` (`kode_akun_keuangan_pusat_kredit`),
  KEY `biaya_operasional_pusat_FKIndex3` (`kode_pegawai_pshpusat`),
  CONSTRAINT `biaya_operasional_pusat_ibfk_1` FOREIGN KEY (`kode_akun_keuangan_pusat_debet`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `biaya_operasional_pusat_ibfk_2` FOREIGN KEY (`kode_akun_keuangan_pusat_kredit`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `biaya_operasional_pusat_ibfk_3` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.biaya_operasional_pusat: ~0 rows (approximately)
/*!40000 ALTER TABLE `biaya_operasional_pusat` DISABLE KEYS */;
/*!40000 ALTER TABLE `biaya_operasional_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.checker
DROP TABLE IF EXISTS `checker`;
CREATE TABLE IF NOT EXISTS `checker` (
  `kode_checker` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fcm` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT NULL,
  `file_ktp` varchar(100) DEFAULT NULL,
  `file_kk` varchar(100) DEFAULT NULL,
  `kodepos` char(5) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_checker`),
  KEY `checker_FKIndex1` (`kode_pshcabang`),
  CONSTRAINT `checker_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.checker: ~2 rows (approximately)
/*!40000 ALTER TABLE `checker` DISABLE KEYS */;
INSERT INTO `checker` (`kode_checker`, `kode_pshcabang`, `nama`, `alamat`, `notelp`, `tanggal_lahir`, `email`, `fcm`, `token`, `tanggal_daftar`, `file_ktp`, `file_kk`, `kodepos`, `foto`, `keterangan`, `hapus`) VALUES
	('CHK/MLG/161221123757', 'MLG/CAB/20161013101442616542', 'Checker Cut Mutia', 'Jl. Salak Wagir', '+6285755598020', '1993-07-14', 'speed.rcm99@gmail.com', NULL, NULL, '2016-12-21 12:37:57', 'http://localhost/setrika_cabang/cabang/server/php/files/1612216373701.png', 'http://localhost/setrika_cabang/cabang/server/php/files/16122163754received_225048051271530.jpeg', '65158', 'http://localhost/setrika_cabang/cabang/server/php/files/16122163727received_225048051271530.jpeg', NULL, '0'),
	('CHK/MLG/20161018150202269169', 'MLG/CAB/20161013101442616542', 'kak emma', 'Malang Singosari', '14022', '2016-10-18', 'firmanslash@gmail.com', 'ZDTRSQwlD6WFd7itaKyKbntXAIb2', NULL, '2016-10-18 15:02:02', 'http://urlktp', 'http://urlkk', '65141', 'http://dev.citridia.com/ws.jastrik/manifest/avatarchecker/ZDTRSQwlD6WFd7itaKyKbntXAIb2.jpg', NULL, '0');
/*!40000 ALTER TABLE `checker` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.checker_order_inventory
DROP TABLE IF EXISTS `checker_order_inventory`;
CREATE TABLE IF NOT EXISTS `checker_order_inventory` (
  `kode_checker_order_inventory` varchar(50) NOT NULL,
  `kode_checker` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `tanggal_checker_order_inventory` datetime DEFAULT NULL,
  `kode_pegawai_pshcabang_spv` varchar(50) DEFAULT NULL,
  `tanggal_setuju_spv` datetime DEFAULT NULL,
  `kode_pegawai_pshcabang_logistik` varchar(50) DEFAULT NULL,
  `tanggal_kirim_logistik` datetime DEFAULT NULL,
  `status_checker_order_inventory` char(2) DEFAULT '0' COMMENT '0=belum diproses,1=disetujui spv,2=sedang dikirim,3=terkirim, 11 = ditolak spv,12=ditolak logistik,13= gagal di pengiriman',
  `hapus` char(1) NOT NULL DEFAULT '0',
  `total` double(12,2) DEFAULT NULL,
  `kode_pegawai_pshcabang_logistik_terkirim` varchar(50) DEFAULT NULL,
  `tanggal_terkirim_logistik` date DEFAULT NULL,
  PRIMARY KEY (`kode_checker_order_inventory`),
  KEY `checker_order_inventory_FKIndex1` (`kode_checker`),
  KEY `checker_order_inventory_FKIndex2` (`kode_agen`),
  KEY `checker_order_inventory_FKIndex3` (`kode_pegawai_pshcabang_logistik`),
  KEY `checker_order_inventory_FKIndex4` (`kode_pegawai_pshcabang_spv`),
  CONSTRAINT `checker_order_inventory_ibfk_1` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`),
  CONSTRAINT `checker_order_inventory_ibfk_2` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `checker_order_inventory_ibfk_3` FOREIGN KEY (`kode_pegawai_pshcabang_logistik`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`),
  CONSTRAINT `checker_order_inventory_ibfk_4` FOREIGN KEY (`kode_pegawai_pshcabang_spv`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.checker_order_inventory: ~12 rows (approximately)
/*!40000 ALTER TABLE `checker_order_inventory` DISABLE KEYS */;
INSERT INTO `checker_order_inventory` (`kode_checker_order_inventory`, `kode_checker`, `kode_agen`, `tanggal_checker_order_inventory`, `kode_pegawai_pshcabang_spv`, `tanggal_setuju_spv`, `kode_pegawai_pshcabang_logistik`, `tanggal_kirim_logistik`, `status_checker_order_inventory`, `hapus`, `total`, `kode_pegawai_pshcabang_logistik_terkirim`, `tanggal_terkirim_logistik`) VALUES
	('KOI/170411213231416801', 'CHK/MLG/161221123757', 'AGN/MLG/17012444144', '2017-04-11 21:32:31', 'KRY/CBG/0002', '2017-04-17 03:28:33', NULL, NULL, '1', '0', 22500.00, NULL, NULL),
	('KOI/170418211349955112', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 21:13:49', NULL, NULL, NULL, NULL, '0', '0', 0.00, NULL, NULL),
	('KOI/170418211505772777', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 21:15:05', NULL, NULL, NULL, NULL, '0', '0', 0.00, NULL, NULL),
	('KOI/170418214921400401', 'CHK/MLG/161221123757', 'AGN/MLG/17012444144', '2017-04-18 21:49:21', NULL, NULL, NULL, NULL, '0', '0', 22500.00, NULL, NULL),
	('KOI/170418215409797585', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 21:54:09', NULL, NULL, NULL, NULL, '0', '0', 7500.00, NULL, NULL),
	('KOI/170418215853790620', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 21:58:53', NULL, NULL, NULL, NULL, '0', '0', 7500.00, NULL, NULL),
	('KOI/170418220113131781', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 22:01:13', NULL, NULL, NULL, NULL, '0', '0', 0.00, NULL, NULL),
	('KOI/170418220425434982', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 22:04:25', NULL, NULL, NULL, NULL, '0', '0', 0.00, NULL, NULL),
	('KOI/170418221230211746', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 22:12:30', NULL, NULL, NULL, NULL, '0', '0', 0.00, NULL, NULL),
	('KOI/170418221354844868', 'CHK/MLG/20161018150202269169', 'AGN/KPN/161208131039173921', '2017-04-18 22:13:54', 'KRY/CBG/0002', '2017-04-27 16:56:41', NULL, NULL, '1', '0', 1500000.00, NULL, NULL),
	('KOI1704020001', 'CHK/MLG/161221123757', 'AGN/MLG/17012444144', '2017-04-02 12:32:46', 'KRY/CBG/0002', '2017-04-03 13:23:28', 'KRY/CBG/0004', '2017-04-03 16:00:15', '3', '0', 100000.00, 'KRY/CBG/0004', '2017-04-03'),
	('KOI1704020002', 'CHK/MLG/161221123757', 'AGN/MLG/161208112905459184', '2017-04-02 12:40:39', 'KRY/CBG/0002', '2017-04-17 03:28:23', 'KRY/CBG/0004', NULL, '1', '0', 10000.00, NULL, NULL);
/*!40000 ALTER TABLE `checker_order_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.checker_setoran_checker
DROP TABLE IF EXISTS `checker_setoran_checker`;
CREATE TABLE IF NOT EXISTS `checker_setoran_checker` (
  `kode_checker_setoran_checker` varchar(50) NOT NULL,
  `kode_checker` varchar(50) NOT NULL,
  `kode_pegawai_pshcabang` varchar(50) DEFAULT NULL,
  `nominal` double(12,2) NOT NULL,
  `tanggal_checker_setoran_checker` datetime NOT NULL,
  `status_checker_setoran_checker` char(1) NOT NULL DEFAULT '0' COMMENT '0=menunggu disetujui kasir,1 = disetujui kasir,2=ditolak kasir',
  `keterangan` varchar(100) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  `tanggal_terima` datetime DEFAULT NULL,
  PRIMARY KEY (`kode_checker_setoran_checker`),
  KEY `checker_setoran_checker_FKIndex1` (`kode_checker`),
  KEY `checker_setoran_checker_FKIndex2` (`kode_pegawai_pshcabang`),
  CONSTRAINT `checker_setoran_checker_ibfk_1` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`),
  CONSTRAINT `checker_setoran_checker_ibfk_2` FOREIGN KEY (`kode_pegawai_pshcabang`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.checker_setoran_checker: ~2 rows (approximately)
/*!40000 ALTER TABLE `checker_setoran_checker` DISABLE KEYS */;
INSERT INTO `checker_setoran_checker` (`kode_checker_setoran_checker`, `kode_checker`, `kode_pegawai_pshcabang`, `nominal`, `tanggal_checker_setoran_checker`, `status_checker_setoran_checker`, `keterangan`, `hapus`, `tanggal_terima`) VALUES
	('SCK1704031209012', 'CHK/MLG/161221123757', 'KRY/CBG/0003', 30000.00, '2017-04-27 00:00:00', '1', '', '0', '2017-05-03 22:33:41'),
	('SCK1704031209013', 'CHK/MLG/20161018150202269169', 'KRY/CBG/0004', 20000.00, '2017-04-28 00:00:00', '0', '', '0', NULL);
/*!40000 ALTER TABLE `checker_setoran_checker` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.dana_checker
DROP TABLE IF EXISTS `dana_checker`;
CREATE TABLE IF NOT EXISTS `dana_checker` (
  `kode_checker` varchar(50) NOT NULL,
  `tanggal_dana_checker` datetime NOT NULL,
  `nominal_dana_checker` double(12,2) NOT NULL,
  `status_dana_checker` char(1) NOT NULL,
  `kode_checker_setoran_checker` varchar(20) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `checker_dana_FKIndex1` (`kode_checker`),
  CONSTRAINT `dana_checker_ibfk_1` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.dana_checker: ~0 rows (approximately)
/*!40000 ALTER TABLE `dana_checker` DISABLE KEYS */;
/*!40000 ALTER TABLE `dana_checker` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.detail_beli_inventory
DROP TABLE IF EXISTS `detail_beli_inventory`;
CREATE TABLE IF NOT EXISTS `detail_beli_inventory` (
  `kode_inventory_harga` int(10) unsigned NOT NULL,
  `kode_beli_inventory` varchar(50) NOT NULL,
  `harga` double(12,2) DEFAULT NULL,
  `jumlah` double(12,3) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `detil_beli_inventory_FKIndex1` (`kode_beli_inventory`),
  KEY `detil_beli_inventory_FKIndex2` (`kode_inventory_harga`),
  CONSTRAINT `detail_beli_inventory_ibfk_1` FOREIGN KEY (`kode_beli_inventory`) REFERENCES `beli_inventory` (`kode_beli_inventory`),
  CONSTRAINT `detail_beli_inventory_ibfk_2` FOREIGN KEY (`kode_inventory_harga`) REFERENCES `inventory_harga` (`kode_inventory_harga`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.detail_beli_inventory: ~2 rows (approximately)
/*!40000 ALTER TABLE `detail_beli_inventory` DISABLE KEYS */;
INSERT INTO `detail_beli_inventory` (`kode_inventory_harga`, `kode_beli_inventory`, `harga`, `jumlah`, `hapus`) VALUES
	(1, 'BIV17041004313997', 5000.00, 10.500, '0'),
	(2, 'BIV17041004313997', 10000.00, 2.000, '0');
/*!40000 ALTER TABLE `detail_beli_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.detail_checker_order_inventory
DROP TABLE IF EXISTS `detail_checker_order_inventory`;
CREATE TABLE IF NOT EXISTS `detail_checker_order_inventory` (
  `kode_checker_order_inventory` varchar(50) NOT NULL,
  `kode_inventory_harga` int(10) unsigned NOT NULL,
  `harga` double(12,2) DEFAULT NULL,
  `jumlah` double(12,3) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `detail_checker_order_inventory_FKIndex1` (`kode_inventory_harga`),
  KEY `detail_checker_order_inventory_FKIndex2` (`kode_checker_order_inventory`),
  CONSTRAINT `detail_checker_order_inventory_ibfk_1` FOREIGN KEY (`kode_inventory_harga`) REFERENCES `inventory_harga` (`kode_inventory_harga`),
  CONSTRAINT `detail_checker_order_inventory_ibfk_2` FOREIGN KEY (`kode_checker_order_inventory`) REFERENCES `checker_order_inventory` (`kode_checker_order_inventory`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.detail_checker_order_inventory: ~10 rows (approximately)
/*!40000 ALTER TABLE `detail_checker_order_inventory` DISABLE KEYS */;
INSERT INTO `detail_checker_order_inventory` (`kode_checker_order_inventory`, `kode_inventory_harga`, `harga`, `jumlah`, `hapus`) VALUES
	('KOI1704020001', 2, 10000.00, 5.000, '0'),
	('KOI1704020001', 1, 5000.00, 10.000, '0'),
	('KOI1704020002', 2, 10000.00, 7.000, '0'),
	('KOI/170411213231416801', 1, 5000.00, 1.500, '0'),
	('KOI/170411213231416801', 2, 10000.00, 1.500, '0'),
	('KOI/170418214921400401', 1, 5000.00, 1.500, '0'),
	('KOI/170418214921400401', 2, 10000.00, 1.500, '0'),
	('KOI/170418215409797585', 1, 5000.00, 1.500, '0'),
	('KOI/170418215853790620', 1, 5000.00, 1.500, '0'),
	('KOI/170418221354844868', 2, 10000.00, 150.000, '0');
/*!40000 ALTER TABLE `detail_checker_order_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.detail_surat_beli_inventory
DROP TABLE IF EXISTS `detail_surat_beli_inventory`;
CREATE TABLE IF NOT EXISTS `detail_surat_beli_inventory` (
  `kode_surat_beli_inventory` varchar(50) DEFAULT NULL,
  `kode_inventory_harga` int(10) DEFAULT NULL,
  `harga` double(12,2) DEFAULT NULL,
  `jumlah` double(12,3) DEFAULT NULL,
  `hapus` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.detail_surat_beli_inventory: 6 rows
/*!40000 ALTER TABLE `detail_surat_beli_inventory` DISABLE KEYS */;
INSERT INTO `detail_surat_beli_inventory` (`kode_surat_beli_inventory`, `kode_inventory_harga`, `harga`, `jumlah`, `hapus`) VALUES
	('SPO17040512274362', 1, 5000.00, 10.500, NULL),
	('SPO1704051207505', 2, 10000.00, 5.000, NULL),
	('SPO1704051207505', 1, 5000.00, 3.000, NULL),
	('SPO17040512230793', 2, 10000.00, 10.000, NULL),
	('SPO17040502083581', 1, 5000.00, 10.500, NULL),
	('SPO17040502083581', 1, 5000.00, 5.000, NULL);
/*!40000 ALTER TABLE `detail_surat_beli_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.entry_keuangan_cabang
DROP TABLE IF EXISTS `entry_keuangan_cabang`;
CREATE TABLE IF NOT EXISTS `entry_keuangan_cabang` (
  `kode_entry_keuangan_cabang` varchar(50) NOT NULL,
  `kode_pegawai_pshcabang` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_cabang_debit` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_cabang_kredit` varchar(50) DEFAULT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) DEFAULT '0',
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`kode_entry_keuangan_cabang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.entry_keuangan_cabang: 1 rows
/*!40000 ALTER TABLE `entry_keuangan_cabang` DISABLE KEYS */;
INSERT INTO `entry_keuangan_cabang` (`kode_entry_keuangan_cabang`, `kode_pegawai_pshcabang`, `kode_akun_keuangan_cabang_debit`, `kode_akun_keuangan_cabang_kredit`, `nominal`, `tanggal`, `keterangan`, `hapus`, `foto`) VALUES
	('ETK201704280409040018', 'KRY/CBG/0002', '1.01.001', '3.01.001', 1000000.00, '2017-04-28 16:09:04', 'setoran ', '0', '["http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/17042840851ktp.jpg","http:\\/\\/localhost\\/setrika_cabang\\/cabang\\/server\\/php\\/files\\/17042840851109450_1473154857.jpg"]');
/*!40000 ALTER TABLE `entry_keuangan_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.fee_afiliasi_sekarang _pindah_ke_layanan_harga
DROP TABLE IF EXISTS `fee_afiliasi_sekarang _pindah_ke_layanan_harga`;
CREATE TABLE IF NOT EXISTS `fee_afiliasi_sekarang _pindah_ke_layanan_harga` (
  `kode_pshcabang` varchar(50) NOT NULL,
  `kode_layanan` smallint(5) unsigned NOT NULL,
  `persentase_fee_afiliasi` double(3,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `fee_afiliasi_FKIndex1` (`kode_pshcabang`),
  KEY `fee_afiliasi_FKIndex2` (`kode_layanan`),
  CONSTRAINT `fee_afiliasi_sekarang _pindah_ke_layanan_harga_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`),
  CONSTRAINT `fee_afiliasi_sekarang _pindah_ke_layanan_harga_ibfk_2` FOREIGN KEY (`kode_layanan`) REFERENCES `layanan` (`kode_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.fee_afiliasi_sekarang _pindah_ke_layanan_harga: ~0 rows (approximately)
/*!40000 ALTER TABLE `fee_afiliasi_sekarang _pindah_ke_layanan_harga` DISABLE KEYS */;
/*!40000 ALTER TABLE `fee_afiliasi_sekarang _pindah_ke_layanan_harga` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.fee_agen_sekarang _pindah_ke_layanan_harga
DROP TABLE IF EXISTS `fee_agen_sekarang _pindah_ke_layanan_harga`;
CREATE TABLE IF NOT EXISTS `fee_agen_sekarang _pindah_ke_layanan_harga` (
  `kode_pshcabang` varchar(50) NOT NULL,
  `kode_layanan` smallint(5) unsigned NOT NULL,
  `persentase_fee_agen` double(3,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `fee_agen_FKIndex1` (`kode_pshcabang`),
  KEY `fee_agen_FKIndex2` (`kode_layanan`),
  CONSTRAINT `fee_agen_sekarang _pindah_ke_layanan_harga_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`),
  CONSTRAINT `fee_agen_sekarang _pindah_ke_layanan_harga_ibfk_2` FOREIGN KEY (`kode_layanan`) REFERENCES `layanan` (`kode_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.fee_agen_sekarang _pindah_ke_layanan_harga: ~0 rows (approximately)
/*!40000 ALTER TABLE `fee_agen_sekarang _pindah_ke_layanan_harga` DISABLE KEYS */;
/*!40000 ALTER TABLE `fee_agen_sekarang _pindah_ke_layanan_harga` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.fee_kurir
DROP TABLE IF EXISTS `fee_kurir`;
CREATE TABLE IF NOT EXISTS `fee_kurir` (
  `kode_pshcabang` varchar(50) NOT NULL,
  `persentase_fee_kurir` double(3,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `fee_kurir_FKIndex1` (`kode_pshcabang`),
  CONSTRAINT `fee_kurir_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.fee_kurir: ~0 rows (approximately)
/*!40000 ALTER TABLE `fee_kurir` DISABLE KEYS */;
/*!40000 ALTER TABLE `fee_kurir` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.fee_pegawai_cabang
DROP TABLE IF EXISTS `fee_pegawai_cabang`;
CREATE TABLE IF NOT EXISTS `fee_pegawai_cabang` (
  `kode_fee_pegawai_cabang` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_pegawai_pshcabang` varchar(50) NOT NULL,
  `gaji_pokok` double(12,2) DEFAULT NULL,
  `gaji_tunjangan` double(12,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_fee_pegawai_cabang`),
  KEY `fee_pegawai_cabang_FKIndex1` (`kode_pegawai_pshcabang`),
  CONSTRAINT `fee_pegawai_cabang_ibfk_1` FOREIGN KEY (`kode_pegawai_pshcabang`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.fee_pegawai_cabang: ~0 rows (approximately)
/*!40000 ALTER TABLE `fee_pegawai_cabang` DISABLE KEYS */;
/*!40000 ALTER TABLE `fee_pegawai_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.fee_pegawai_pusat
DROP TABLE IF EXISTS `fee_pegawai_pusat`;
CREATE TABLE IF NOT EXISTS `fee_pegawai_pusat` (
  `kode_fee_pegawai_pusat` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `gaji_pokok` double(12,2) DEFAULT NULL,
  `gaji_tunjangan` double(12,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_fee_pegawai_pusat`),
  KEY `fee_pegawai_pusat_FKIndex1` (`kode_pegawai_pshpusat`),
  CONSTRAINT `fee_pegawai_pusat_ibfk_1` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.fee_pegawai_pusat: ~0 rows (approximately)
/*!40000 ALTER TABLE `fee_pegawai_pusat` DISABLE KEYS */;
/*!40000 ALTER TABLE `fee_pegawai_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.inventory
DROP TABLE IF EXISTS `inventory`;
CREATE TABLE IF NOT EXISTS `inventory` (
  `kode_inventory` varchar(100) NOT NULL,
  `kode_satuan_layanan` smallint(5) unsigned NOT NULL,
  `nama_inventory` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_inventory`),
  KEY `inventory_FKIndex1` (`kode_satuan_layanan`),
  CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`kode_satuan_layanan`) REFERENCES `satuan_layanan` (`kode_satuan_layanan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.inventory: ~2 rows (approximately)
/*!40000 ALTER TABLE `inventory` DISABLE KEYS */;
INSERT INTO `inventory` (`kode_inventory`, `kode_satuan_layanan`, `nama_inventory`, `hapus`) VALUES
	('123213123', 1, 'Deterjen', '0'),
	('PRF0001', 4, 'Parfum', '0');
/*!40000 ALTER TABLE `inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.inventory_harga
DROP TABLE IF EXISTS `inventory_harga`;
CREATE TABLE IF NOT EXISTS `inventory_harga` (
  `kode_inventory_harga` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_inventory` varchar(100) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `harga_inventory` double(12,2) DEFAULT NULL,
  `total_stok` double(12,3) DEFAULT NULL,
  `minimal_stok` double(12,3) DEFAULT NULL,
  `gudang_stok` double(12,3) DEFAULT NULL,
  `jalan_stok` double(12,3) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_inventory_harga`),
  KEY `inventory_harga_FKIndex1` (`kode_pshcabang`),
  KEY `inventory_harga_FKIndex2` (`kode_inventory`),
  CONSTRAINT `inventory_harga_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`),
  CONSTRAINT `inventory_harga_ibfk_2` FOREIGN KEY (`kode_inventory`) REFERENCES `inventory` (`kode_inventory`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.inventory_harga: ~2 rows (approximately)
/*!40000 ALTER TABLE `inventory_harga` DISABLE KEYS */;
INSERT INTO `inventory_harga` (`kode_inventory_harga`, `kode_inventory`, `kode_pshcabang`, `harga_inventory`, `total_stok`, `minimal_stok`, `gudang_stok`, `jalan_stok`, `hapus`) VALUES
	(1, '123213123', 'MLG/CAB/20161013101442616542', 5000.00, NULL, 4.000, NULL, NULL, '0'),
	(2, 'PRF0001', 'MLG/CAB/20161013101442616542', 10000.00, NULL, 4.000, NULL, NULL, '0');
/*!40000 ALTER TABLE `inventory_harga` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.item
DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `kode_item` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_item` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_item`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.item: ~9 rows (approximately)
/*!40000 ALTER TABLE `item` DISABLE KEYS */;
INSERT INTO `item` (`kode_item`, `nama_item`, `hapus`) VALUES
	(1, 'Kaos Tangan', '0'),
	(2, 'Dasi', '0'),
	(3, 'Sarung Bantal', '0'),
	(4, 'Kaos Kaki', '0'),
	(5, 'Sarung Guling', '0'),
	(6, 'Celana Dalam', '0'),
	(7, 'Be Ha', '0'),
	(8, 'Bikini', '0'),
	(9, 'Kingslte', '0');
/*!40000 ALTER TABLE `item` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.jabatan
DROP TABLE IF EXISTS `jabatan`;
CREATE TABLE IF NOT EXISTS `jabatan` (
  `kode_jabatan` varchar(50) NOT NULL,
  `nama_jabatan` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.jabatan: ~6 rows (approximately)
/*!40000 ALTER TABLE `jabatan` DISABLE KEYS */;
INSERT INTO `jabatan` (`kode_jabatan`, `nama_jabatan`, `hapus`) VALUES
	('1', 'Admin Pusat', '0'),
	('11', 'Admin Cabang', '0'),
	('12', 'Keuangan Cabang', '0'),
	('13', 'Kasir Cabang', '0'),
	('14', 'Logistik Cabang', '0'),
	('2', 'Keuangan Pusat', '0');
/*!40000 ALTER TABLE `jabatan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.jenis_akun
DROP TABLE IF EXISTS `jenis_akun`;
CREATE TABLE IF NOT EXISTS `jenis_akun` (
  `kode_jenis_akun` varchar(50) NOT NULL,
  `nama_jenis_akun` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_jenis_akun`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.jenis_akun: ~6 rows (approximately)
/*!40000 ALTER TABLE `jenis_akun` DISABLE KEYS */;
INSERT INTO `jenis_akun` (`kode_jenis_akun`, `nama_jenis_akun`, `hapus`) VALUES
	('1.00.000', 'ASET', '0'),
	('2.00.000', 'KEWAJIBAN', '0'),
	('3.00.000', 'EKUITAS/MODAL', '0'),
	('4.00.000', 'PENDAPATAN', '0'),
	('5.00.000', 'BIAYA', '0'),
	('JEA/01', 'Akun Pusat Percobaan Beli Dompet', '1');
/*!40000 ALTER TABLE `jenis_akun` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.jenis_bank
DROP TABLE IF EXISTS `jenis_bank`;
CREATE TABLE IF NOT EXISTS `jenis_bank` (
  `kode_jenis_bank` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis_bank` varchar(100) DEFAULT NULL,
  `logo_jenis_bank` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_jenis_bank`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.jenis_bank: ~2 rows (approximately)
/*!40000 ALTER TABLE `jenis_bank` DISABLE KEYS */;
INSERT INTO `jenis_bank` (`kode_jenis_bank`, `nama_jenis_bank`, `logo_jenis_bank`, `hapus`) VALUES
	(1, 'BANK CENTRAL ASIA (BCA)', '16110153258def.png', '0'),
	(2, 'MANDIRI', '16110153340excel.jpg', '0');
/*!40000 ALTER TABLE `jenis_bank` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.jenis_layanan
DROP TABLE IF EXISTS `jenis_layanan`;
CREATE TABLE IF NOT EXISTS `jenis_layanan` (
  `kode_jenis_layanan` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_jenis_layanan` varchar(20) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_jenis_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.jenis_layanan: ~3 rows (approximately)
/*!40000 ALTER TABLE `jenis_layanan` DISABLE KEYS */;
INSERT INTO `jenis_layanan` (`kode_jenis_layanan`, `nama_jenis_layanan`, `hapus`) VALUES
	(1, 'Satuan', '0'),
	(2, 'Kiloan', '0'),
	(3, 'Luas', '0');
/*!40000 ALTER TABLE `jenis_layanan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.jurnal_keuangan_cabang
DROP TABLE IF EXISTS `jurnal_keuangan_cabang`;
CREATE TABLE IF NOT EXISTS `jurnal_keuangan_cabang` (
  `kode_jurnal_keuangan_cabang` varchar(50) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `kode_akun_keuangan_cabang` varchar(20) DEFAULT NULL,
  `debit` double(12,2) DEFAULT '0.00',
  `kredit` double(12,2) DEFAULT '0.00',
  `keterangan` varchar(100) DEFAULT NULL,
  `ref` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  `kode_pshcabang` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshcabang` varchar(50) DEFAULT NULL,
  `saldo_akhir` double(12,2) DEFAULT '0.00',
  PRIMARY KEY (`kode_jurnal_keuangan_cabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.jurnal_keuangan_cabang: ~4 rows (approximately)
/*!40000 ALTER TABLE `jurnal_keuangan_cabang` DISABLE KEYS */;
INSERT INTO `jurnal_keuangan_cabang` (`kode_jurnal_keuangan_cabang`, `tanggal`, `kode_akun_keuangan_cabang`, `debit`, `kredit`, `keterangan`, `ref`, `hapus`, `kode_pshcabang`, `kode_pegawai_pshcabang`, `saldo_akhir`) VALUES
	('201704280409040001', '2017-04-28 16:09:04', '1.01.001', 1000000.00, 0.00, 'setoran ', 'ETK201704280409040018', '0', 'MLG/CAB/20161013101442616542', 'KRY/CBG/0002', 1000000.00),
	('201704280409040094', '2017-04-28 16:09:04', '3.01.001', 0.00, 1000000.00, 'setoran ', 'ETK201704280409040018', '0', 'MLG/CAB/20161013101442616542', 'KRY/CBG/0002', -1000000.00),
	('201704280410370054', '2017-04-28 16:10:37', '1.01.001', 0.00, 20000.00, NULL, 'ETK201704280410370041', '0', 'MLG/CAB/20161013101442616542', 'KRY/CBG/0002', 980000.00),
	('201704280410370098', '2017-04-28 16:10:37', '5.01.001', 20000.00, 0.00, NULL, 'ETK201704280410370041', '0', 'MLG/CAB/20161013101442616542', 'KRY/CBG/0002', 20000.00);
/*!40000 ALTER TABLE `jurnal_keuangan_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.jurnal_keuangan_pusat
DROP TABLE IF EXISTS `jurnal_keuangan_pusat`;
CREATE TABLE IF NOT EXISTS `jurnal_keuangan_pusat` (
  `kode_jurnal_keuangan_pusat` varchar(50) NOT NULL,
  `tanggal` datetime DEFAULT NULL,
  `kode_akun_pusat` varchar(20) DEFAULT NULL,
  `debit` double(12,2) DEFAULT NULL,
  `kredit` double(12,2) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `ref` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_jurnal_keuangan_pusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.jurnal_keuangan_pusat: ~0 rows (approximately)
/*!40000 ALTER TABLE `jurnal_keuangan_pusat` DISABLE KEYS */;
/*!40000 ALTER TABLE `jurnal_keuangan_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kategori_akun_cabang
DROP TABLE IF EXISTS `kategori_akun_cabang`;
CREATE TABLE IF NOT EXISTS `kategori_akun_cabang` (
  `kode_kategori_akun_cabang` varchar(50) NOT NULL,
  `kode_jenis_akun` varchar(50) NOT NULL,
  `nama_kategori_akun` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kategori_akun_cabang`,`kode_pshcabang`),
  KEY `kategori_akun_FKIndex1` (`kode_jenis_akun`),
  CONSTRAINT `kategori_akun_ibfk_1` FOREIGN KEY (`kode_jenis_akun`) REFERENCES `jenis_akun` (`kode_jenis_akun`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kategori_akun_cabang: ~11 rows (approximately)
/*!40000 ALTER TABLE `kategori_akun_cabang` DISABLE KEYS */;
INSERT INTO `kategori_akun_cabang` (`kode_kategori_akun_cabang`, `kode_jenis_akun`, `nama_kategori_akun`, `kode_pshcabang`, `hapus`) VALUES
	('1.01.000', '1.00.000', 'Kas ', 'BDG/CAB/6543', '0'),
	('1.01.000', '1.00.000', 'Kas', 'MLG/CAB/20161013101442616542', '0'),
	('1.02.000', '1.00.000', 'Bank', 'BDG/CAB/6543', '0'),
	('1.02.000', '1.00.000', 'Bank', 'MLG/CAB/20161013101442616542', '0'),
	('1.03.000', '1.00.000', 'Piutang', 'MLG/CAB/20161013101442616542', '0'),
	('1.04.000', '1.00.000', 'Persediaan', 'MLG/CAB/20161013101442616542', '0'),
	('2.01.000', '2.00.000', 'Hutang Ke Konsumen', 'BDG/CAB/6543', '0'),
	('2.01.000', '2.00.000', 'Hutang', 'MLG/CAB/20161013101442616542', '0'),
	('3.01.000', '3.00.000', 'Modal', 'MLG/CAB/20161013101442616542', '0'),
	('4.01.000', '4.00.000', 'Pendapatan Transaksi', 'MLG/CAB/20161013101442616542', '0'),
	('5.01.000', '5.00.000', 'Biaya Operasional', 'MLG/CAB/20161013101442616542', '0');
/*!40000 ALTER TABLE `kategori_akun_cabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kategori_akun_pusat
DROP TABLE IF EXISTS `kategori_akun_pusat`;
CREATE TABLE IF NOT EXISTS `kategori_akun_pusat` (
  `kode_kategori_akun_pusat` varchar(50) NOT NULL,
  `kode_jenis_akun` varchar(50) NOT NULL,
  `nama_kategori_akun` varchar(50) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kategori_akun_pusat`),
  KEY `kategori_akun_FKIndex1` (`kode_jenis_akun`),
  CONSTRAINT `kategori_akun_pusat_ibfk_1` FOREIGN KEY (`kode_jenis_akun`) REFERENCES `jenis_akun` (`kode_jenis_akun`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kategori_akun_pusat: ~6 rows (approximately)
/*!40000 ALTER TABLE `kategori_akun_pusat` DISABLE KEYS */;
INSERT INTO `kategori_akun_pusat` (`kode_kategori_akun_pusat`, `kode_jenis_akun`, `nama_kategori_akun`, `hapus`) VALUES
	('1.01.000', '1.00.000', 'Kas', '0'),
	('1.02.000', '1.00.000', 'Bank', '0'),
	('2.01.000', '2.00.000', 'Hutang', '0'),
	('4.01.000', '4.00.000', 'Pendapat Setrika', '0'),
	('4.02.000', '4.00.000', 'Pendapatan Sponsor', '0'),
	('KAA/01', 'JEA/01', 'Kategori Akun Percobaan Beli Dompet', '0');
/*!40000 ALTER TABLE `kategori_akun_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kecamatan
DROP TABLE IF EXISTS `kecamatan`;
CREATE TABLE IF NOT EXISTS `kecamatan` (
  `kode_kecamatan` char(10) NOT NULL,
  `kode_kota` char(4) NOT NULL,
  `nama_kecamatan` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kecamatan`),
  KEY `kecamatan_FKIndex1` (`kode_kota`),
  CONSTRAINT `kecamatan_ibfk_1` FOREIGN KEY (`kode_kota`) REFERENCES `kota` (`kode_kota`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kecamatan: ~115 rows (approximately)
/*!40000 ALTER TABLE `kecamatan` DISABLE KEYS */;
INSERT INTO `kecamatan` (`kode_kecamatan`, `kode_kota`, `nama_kecamatan`, `hapus`) VALUES
	('3204010', 'SOR', 'CIWIDEY', '0'),
	('3204011', 'SOR', 'RANCABALI', '0'),
	('3204020', 'SOR', 'PASIRJAMBU', '0'),
	('3204030', 'SOR', 'CIMAUNG', '0'),
	('3204040', 'SOR', 'PANGALENGAN', '0'),
	('3204050', 'SOR', 'KERTASARI', '0'),
	('3204060', 'SOR', 'PACET', '0'),
	('3204070', 'SOR', 'IBUN', '0'),
	('3204080', 'SOR', 'PASEH', '0'),
	('3204090', 'SOR', 'CIKANCUNG', '0'),
	('3204100', 'SOR', 'CICALENGKA', '0'),
	('3204101', 'SOR', 'NAGREG', '0'),
	('3204110', 'SOR', 'RANCAEKEK', '0'),
	('3204120', 'SOR', 'MAJALAYA', '0'),
	('3204121', 'SOR', 'SOLOKAN JERUK', '0'),
	('3204130', 'SOR', 'CIPARAY', '0'),
	('3204140', 'SOR', 'BALEENDAH', '0'),
	('3204150', 'SOR', 'ARJASARI', '0'),
	('3204160', 'SOR', 'BANJARAN', '0'),
	('3204161', 'SOR', 'CANGKUANG', '0'),
	('3204170', 'SOR', 'PAMEUNGPEUK', '0'),
	('3204180', 'SOR', 'KATAPANG', '0'),
	('3204190', 'SOR', 'SOREANG', '0'),
	('3204191', 'SOR', 'KUTAWARINGIN', '0'),
	('3204250', 'SOR', 'MARGAASIH', '0'),
	('3204260', 'SOR', 'MARGAHAYU', '0'),
	('3204270', 'SOR', 'DAYEUHKOLOT', '0'),
	('3204280', 'SOR', 'BOJONGSOANG', '0'),
	('3204290', 'SOR', 'CILEUNYI', '0'),
	('3204300', 'SOR', 'CILENGKRANG', '0'),
	('3204310', 'SOR', 'CIMENYAN', '0'),
	('3217010', 'NPH', 'RONGGA', '0'),
	('3217020', 'NPH', 'GUNUNGHALU', '0'),
	('3217030', 'NPH', 'SINDANGKERTA', '0'),
	('3217040', 'NPH', 'CILILIN', '0'),
	('3217050', 'NPH', 'CIHAMPELAS', '0'),
	('3217060', 'NPH', 'CIPONGKOR', '0'),
	('3217070', 'NPH', 'BATUJAJAR', '0'),
	('3217071', 'NPH', 'SAGULING', '0'),
	('3217080', 'NPH', 'CIPATAT', '0'),
	('3217090', 'NPH', 'PADALARANG', '0'),
	('3217100', 'NPH', 'NGAMPRAH', '0'),
	('3217110', 'NPH', 'PARONGPONG', '0'),
	('3217120', 'NPH', 'LEMBANG', '0'),
	('3217130', 'NPH', 'CISARUA', '0'),
	('3217140', 'NPH', 'CIKALONG WETAN', '0'),
	('3217150', 'NPH', 'CIPEUNDEUY', '0'),
	('3273010', 'BDG', 'BANDUNG KULON', '0'),
	('3273020', 'BDG', 'BABAKAN CIPARAY', '0'),
	('3273030', 'BDG', 'BOJONGLOA KALER', '0'),
	('3273040', 'BDG', 'BOJONGLOA KIDUL', '0'),
	('3273050', 'BDG', 'ASTANAANYAR', '0'),
	('3273060', 'BDG', 'REGOL', '0'),
	('3273070', 'BDG', 'LENGKONG', '0'),
	('3273080', 'BDG', 'BANDUNG KIDUL', '0'),
	('3273090', 'BDG', 'BUAHBATU', '0'),
	('3273100', 'BDG', 'RANCASARI', '0'),
	('3273101', 'BDG', 'GEDEBAGE', '0'),
	('3273110', 'BDG', 'CIBIRU', '0'),
	('3273111', 'BDG', 'PANYILEUKAN', '0'),
	('3273120', 'BDG', 'UJUNG BERUNG', '0'),
	('3273121', 'BDG', 'CINAMBO', '0'),
	('3273130', 'BDG', 'ARCAMANIK', '0'),
	('3273141', 'BDG', 'ANTAPANI', '0'),
	('3273142', 'BDG', 'MANDALAJATI', '0'),
	('3273150', 'BDG', 'KIARACONDONG', '0'),
	('3273160', 'BDG', 'BATUNUNGGAL', '0'),
	('3273170', 'BDG', 'SUMUR BANDUNG', '0'),
	('3273180', 'BDG', 'ANDIR', '0'),
	('3273190', 'BDG', 'CICENDO', '0'),
	('3273200', 'BDG', 'BANDUNG WETAN', '0'),
	('3273210', 'BDG', 'CIBEUNYING KIDUL', '0'),
	('3273220', 'BDG', 'CIBEUNYING KALER', '0'),
	('3273230', 'BDG', 'COBLONG', '0'),
	('3273240', 'BDG', 'SUKAJADI', '0'),
	('3273250', 'BDG', 'SUKASARI', '0'),
	('3273260', 'BDG', 'CIDADAP', '0'),
	('3507010', 'KPN', 'DONOMULYO', '0'),
	('3507020', 'KPN', 'KALIPARE', '0'),
	('3507030', 'KPN', 'PAGAK', '0'),
	('3507040', 'KPN', 'BANTUR', '0'),
	('3507050', 'KPN', 'GEDANGAN', '0'),
	('3507060', 'KPN', 'SUMBERMANJING', '0'),
	('3507070', 'KPN', 'DAMPIT', '0'),
	('3507080', 'KPN', 'TIRTO YUDO', '0'),
	('3507090', 'KPN', 'AMPELGADING', '0'),
	('3507100', 'KPN', 'PONCOKUSUMO', '0'),
	('3507110', 'KPN', 'WAJAK', '0'),
	('3507120', 'KPN', 'TUREN', '0'),
	('3507130', 'KPN', 'BULULAWANG', '0'),
	('3507140', 'KPN', 'GONDANGLEGI', '0'),
	('3507150', 'KPN', 'PAGELARAN', '0'),
	('3507160', 'KPN', 'KEPANJEN', '0'),
	('3507170', 'KPN', 'SUMBER PUCUNG', '0'),
	('3507180', 'KPN', 'KROMENGAN', '0'),
	('3507190', 'KPN', 'NGAJUM', '0'),
	('3507200', 'KPN', 'WONOSARI', '0'),
	('3507210', 'KPN', 'WAGIR', '0'),
	('3507220', 'KPN', 'PAKISAJI', '0'),
	('3507230', 'KPN', 'TAJINAN', '0'),
	('3507240', 'KPN', 'TUMPANG', '0'),
	('3507250', 'KPN', 'PAKIS', '0'),
	('3507260', 'KPN', 'JABUNG', '0'),
	('3507270', 'KPN', 'LAWANG', '0'),
	('3507280', 'KPN', 'SINGOSARI', '0'),
	('3507290', 'KPN', 'KARANGPLOSO', '0'),
	('3507300', 'KPN', 'DAU', '0'),
	('3507310', 'KPN', 'PUJON', '0'),
	('3507320', 'KPN', 'NGANTANG', '0'),
	('3507330', 'KPN', 'KASEMBON', '0'),
	('3573010', 'MLG', 'KEDUNGKANDANG', '0'),
	('3573020', 'MLG', 'SUKUN', '0'),
	('3573030', 'MLG', 'KLOJEN', '0'),
	('3573040', 'MLG', 'BLIMBING', '0'),
	('3573050', 'MLG', 'LOWOKWARU', '0');
/*!40000 ALTER TABLE `kecamatan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kelurahan
DROP TABLE IF EXISTS `kelurahan`;
CREATE TABLE IF NOT EXISTS `kelurahan` (
  `kode_kelurahan` char(15) NOT NULL,
  `kode_kecamatan` char(10) NOT NULL,
  `nama_kelurahan` varchar(50) DEFAULT NULL,
  `kode_pos` varchar(20) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kelurahan`),
  KEY `kelurahan_FKIndex1` (`kode_kecamatan`),
  CONSTRAINT `kelurahan_ibfk_1` FOREIGN KEY (`kode_kecamatan`) REFERENCES `kecamatan` (`kode_kecamatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kelurahan: ~1,043 rows (approximately)
/*!40000 ALTER TABLE `kelurahan` DISABLE KEYS */;
INSERT INTO `kelurahan` (`kode_kelurahan`, `kode_kecamatan`, `nama_kelurahan`, `kode_pos`, `hapus`) VALUES
	('3204010001', '3204010', 'PANUNDAAN', NULL, '0'),
	('3204010002', '3204010', 'CIWIDEY', NULL, '0'),
	('3204010003', '3204010', 'PANYOCOKAN', NULL, '0'),
	('3204010004', '3204010', 'LEBAKMUNCANG', NULL, '0'),
	('3204010005', '3204010', 'RAWABOGO', NULL, '0'),
	('3204010006', '3204010', 'NENGKELAN', NULL, '0'),
	('3204010007', '3204010', 'SUKAWENING', NULL, '0'),
	('3204011001', '3204011', 'CIPELAH', NULL, '0'),
	('3204011002', '3204011', 'SUKARESMI', NULL, '0'),
	('3204011003', '3204011', 'INDRAGIRI', NULL, '0'),
	('3204011004', '3204011', 'PATENGAN', NULL, '0'),
	('3204011005', '3204011', 'ALAMENDAH', NULL, '0'),
	('3204020001', '3204020', 'SUGIHMUKTI', NULL, '0'),
	('3204020002', '3204020', 'MARGAMULYA', NULL, '0'),
	('3204020003', '3204020', 'TENJOLAYA', NULL, '0'),
	('3204020004', '3204020', 'CISONDARI', NULL, '0'),
	('3204020005', '3204020', 'MEKARSARI', NULL, '0'),
	('3204020006', '3204020', 'CIBODAS', NULL, '0'),
	('3204020007', '3204020', 'CUKANGGENTENG', NULL, '0'),
	('3204020008', '3204020', 'PASIRJAMBU', NULL, '0'),
	('3204020009', '3204020', 'MEKARMAJU', NULL, '0'),
	('3204020010', '3204020', 'CIKONENG', NULL, '0'),
	('3204030001', '3204030', 'CIKALONG', NULL, '0'),
	('3204030002', '3204030', 'MEKARSARI', NULL, '0'),
	('3204030003', '3204030', 'CIPINANG', NULL, '0'),
	('3204030004', '3204030', 'CIMAUNG', NULL, '0'),
	('3204030005', '3204030', 'CAMPAKAMULYA', NULL, '0'),
	('3204030006', '3204030', 'PASIRHUNI', NULL, '0'),
	('3204030007', '3204030', 'JAGABAYA', NULL, '0'),
	('3204030008', '3204030', 'MALASARI', NULL, '0'),
	('3204030009', '3204030', 'SUKAMAJU', NULL, '0'),
	('3204030010', '3204030', 'WARJABAKTI', NULL, '0'),
	('3204040001', '3204040', 'WANASUKA', NULL, '0'),
	('3204040002', '3204040', 'BANJARSARI', NULL, '0'),
	('3204040003', '3204040', 'MARGALUYU', NULL, '0'),
	('3204040004', '3204040', 'SUKALUYU', NULL, '0'),
	('3204040005', '3204040', 'WARNASARI', NULL, '0'),
	('3204040006', '3204040', 'PULOSARI', NULL, '0'),
	('3204040007', '3204040', 'MARGAMEKAR', NULL, '0'),
	('3204040008', '3204040', 'SUKAMANAH', NULL, '0'),
	('3204040009', '3204040', 'MARGAMUKTI', NULL, '0'),
	('3204040010', '3204040', 'PANGALENGAN', NULL, '0'),
	('3204040011', '3204040', 'MARGAMULYA', NULL, '0'),
	('3204040012', '3204040', 'TRIBAKTIMULYA', NULL, '0'),
	('3204040013', '3204040', 'LAMAJANG', NULL, '0'),
	('3204050001', '3204050', 'NEGLAWANGI', NULL, '0'),
	('3204050002', '3204050', 'SANTOSA', NULL, '0'),
	('3204050003', '3204050', 'TARUMAJAYA', NULL, '0'),
	('3204050004', '3204050', 'CIKEMBANG', NULL, '0'),
	('3204050005', '3204050', 'CIBEUREUM', NULL, '0'),
	('3204050006', '3204050', 'CIHAWUK', NULL, '0'),
	('3204050007', '3204050', 'SUKAPURA', NULL, '0'),
	('3204050008', '3204050', 'RESMI TINGGAL', NULL, '0'),
	('3204060001', '3204060', 'CIKITU', NULL, '0'),
	('3204060002', '3204060', 'GIRIMULYA', NULL, '0'),
	('3204060003', '3204060', 'SUKARAME', NULL, '0'),
	('3204060004', '3204060', 'CIKAWAO', NULL, '0'),
	('3204060005', '3204060', 'NAGRAK', NULL, '0'),
	('3204060006', '3204060', 'MANDALAHAJI', NULL, '0'),
	('3204060007', '3204060', 'MARUYUNG', NULL, '0'),
	('3204060008', '3204060', 'PANGAUBAN', NULL, '0'),
	('3204060009', '3204060', 'CINANGGELA', NULL, '0'),
	('3204060010', '3204060', 'MEKARJAYA', NULL, '0'),
	('3204060011', '3204060', 'MEKARSARI', NULL, '0'),
	('3204060012', '3204060', 'CIPEUJEUH', NULL, '0'),
	('3204060013', '3204060', 'TANJUNGWANGI', NULL, '0'),
	('3204070001', '3204070', 'NEGLASARI', NULL, '0'),
	('3204070002', '3204070', 'DUKUH', NULL, '0'),
	('3204070003', '3204070', 'IBUN', NULL, '0'),
	('3204070004', '3204070', 'LAKSANA', NULL, '0'),
	('3204070005', '3204070', 'MEKARWANGI', NULL, '0'),
	('3204070006', '3204070', 'SUDI', NULL, '0'),
	('3204070007', '3204070', 'CIBEET', NULL, '0'),
	('3204070008', '3204070', 'PANGGUH', NULL, '0'),
	('3204070009', '3204070', 'KARYALAKSANA', NULL, '0'),
	('3204070010', '3204070', 'LAMPEGAN', NULL, '0'),
	('3204070011', '3204070', 'TALUN', NULL, '0'),
	('3204070012', '3204070', 'TANGGULUN', NULL, '0'),
	('3204080001', '3204080', 'LOA', NULL, '0'),
	('3204080002', '3204080', 'DRAWATI', NULL, '0'),
	('3204080003', '3204080', 'CIPAKU', NULL, '0'),
	('3204080004', '3204080', 'SINDANGSARI', NULL, '0'),
	('3204080005', '3204080', 'SUKAMANTRI', NULL, '0'),
	('3204080006', '3204080', 'SUKAMANAH', NULL, '0'),
	('3204080007', '3204080', 'MEKARPAWITAN', NULL, '0'),
	('3204080008', '3204080', 'CIJAGRA', NULL, '0'),
	('3204080009', '3204080', 'TANGSIMEKAR', NULL, '0'),
	('3204080010', '3204080', 'CIPEDES', NULL, '0'),
	('3204080011', '3204080', 'KARANGTUNGGAL', NULL, '0'),
	('3204080012', '3204080', 'CIGENTUR', NULL, '0'),
	('3204090001', '3204090', 'SRIRAHAYU', NULL, '0'),
	('3204090002', '3204090', 'CILULUK', NULL, '0'),
	('3204090003', '3204090', 'MEKARLAKSANA', NULL, '0'),
	('3204090004', '3204090', 'CIHANYIR', NULL, '0'),
	('3204090005', '3204090', 'CIKANCUNG', NULL, '0'),
	('3204090006', '3204090', 'MANDALASARI', NULL, '0'),
	('3204090007', '3204090', 'HEGARMANAH', NULL, '0'),
	('3204090008', '3204090', 'CIKASUNGKA', NULL, '0'),
	('3204090009', '3204090', 'TANJUNGLAYA', NULL, '0'),
	('3204100001', '3204100', 'NAGROG', NULL, '0'),
	('3204100002', '3204100', 'NARAWITA', NULL, '0'),
	('3204100003', '3204100', 'MARGAASIH', NULL, '0'),
	('3204100004', '3204100', 'CICALENGKA WETAN', NULL, '0'),
	('3204100005', '3204100', 'CIKUYA', NULL, '0'),
	('3204100006', '3204100', 'WALUYA', NULL, '0'),
	('3204100007', '3204100', 'PANENJOAN', NULL, '0'),
	('3204100008', '3204100', 'TENJOLAYA', NULL, '0'),
	('3204100009', '3204100', 'CICALENGKA KULON', NULL, '0'),
	('3204100010', '3204100', 'BABAKANPEUTEUY', NULL, '0'),
	('3204100011', '3204100', 'DAMPIT', NULL, '0'),
	('3204100012', '3204100', 'TANJUNGWANGI', NULL, '0'),
	('3204101001', '3204101', 'MANDALAWANGI', NULL, '0'),
	('3204101002', '3204101', 'BOJONG', NULL, '0'),
	('3204101003', '3204101', 'CIHERANG', NULL, '0'),
	('3204101004', '3204101', 'CIARO', NULL, '0'),
	('3204101005', '3204101', 'NAGREG', NULL, '0'),
	('3204101006', '3204101', 'CITAMAN', NULL, '0'),
	('3204101007', '3204101', 'NAGREG KENDAN', NULL, '0'),
	('3204101008', '3204101', 'GANJAR SABAR', NULL, '0'),
	('3204110001', '3204110', 'SUKAMANAH', NULL, '0'),
	('3204110002', '3204110', 'TEGALSUMEDANG', NULL, '0'),
	('3204110003', '3204110', 'RANCAEKEK KULON', NULL, '0'),
	('3204110004', '3204110', 'RANCAEKEK WETAN', NULL, '0'),
	('3204110005', '3204110', 'BOJONGLOA', NULL, '0'),
	('3204110006', '3204110', 'JELEGONG', NULL, '0'),
	('3204110007', '3204110', 'LINGGAR', NULL, '0'),
	('3204110008', '3204110', 'SUKAMULYA', NULL, '0'),
	('3204110009', '3204110', 'HAURPUGUR', NULL, '0'),
	('3204110010', '3204110', 'SANGIANG', NULL, '0'),
	('3204110011', '3204110', 'BOJONGSALAM', NULL, '0'),
	('3204110012', '3204110', 'CANGKUANG', NULL, '0'),
	('3204110013', '3204110', 'NANJUNGMEKAR', NULL, '0'),
	('3204110014', '3204110', 'RANCAEKEK KENCANA', NULL, '0'),
	('3204120001', '3204120', 'NEGLASARI', NULL, '0'),
	('3204120002', '3204120', 'WANGISAGARA', NULL, '0'),
	('3204120003', '3204120', 'PADAMULYA', NULL, '0'),
	('3204120004', '3204120', 'SUKAMUKTI', NULL, '0'),
	('3204120005', '3204120', 'PADAULUN', NULL, '0'),
	('3204120006', '3204120', 'BIRU', NULL, '0'),
	('3204120007', '3204120', 'SUKAMAJU', NULL, '0'),
	('3204120008', '3204120', 'MAJASETRA', NULL, '0'),
	('3204120009', '3204120', 'MAJALAYA', NULL, '0'),
	('3204120010', '3204120', 'MAJAKERTA', NULL, '0'),
	('3204120011', '3204120', 'BOJONG', NULL, '0'),
	('3204121001', '3204121', 'PANYADAP', NULL, '0'),
	('3204121002', '3204121', 'PADAMUKTI', NULL, '0'),
	('3204121003', '3204121', 'CIBODAS', NULL, '0'),
	('3204121004', '3204121', 'LANGENSARI', NULL, '0'),
	('3204121005', '3204121', 'SOLOKANJERUK', NULL, '0'),
	('3204121006', '3204121', 'RANCAKASUMBA', NULL, '0'),
	('3204121007', '3204121', 'BOJONGEMAS', NULL, '0'),
	('3204130001', '3204130', 'BABAKAN', NULL, '0'),
	('3204130002', '3204130', 'CIKONENG', NULL, '0'),
	('3204130003', '3204130', 'SIGARACIPTA', NULL, '0'),
	('3204130004', '3204130', 'PAKUTANDANG', NULL, '0'),
	('3204130005', '3204130', 'MANGGUNGHARJA', NULL, '0'),
	('3204130006', '3204130', 'MEKARSARI', NULL, '0'),
	('3204130007', '3204130', 'CIPARAY', NULL, '0'),
	('3204130008', '3204130', 'SUMBERSARI', NULL, '0'),
	('3204130009', '3204130', 'SARIMAHI', NULL, '0'),
	('3204130010', '3204130', 'SERANGMEKAR', NULL, '0'),
	('3204130011', '3204130', 'GUNUNGLEUTIK', NULL, '0'),
	('3204130012', '3204130', 'CIHEULANG', NULL, '0'),
	('3204130013', '3204130', 'MEKARLAKSANA', NULL, '0'),
	('3204130014', '3204130', 'BUMIWANGI', NULL, '0'),
	('3204140001', '3204140', 'JELEKONG', NULL, '0'),
	('3204140002', '3204140', 'MANGGAHANG', NULL, '0'),
	('3204140003', '3204140', 'BALEENDAH', NULL, '0'),
	('3204140004', '3204140', 'ANDIR', NULL, '0'),
	('3204140005', '3204140', 'MALAKASARI', NULL, '0'),
	('3204140006', '3204140', 'BOJONGMALAKA', NULL, '0'),
	('3204140007', '3204140', 'RANCAMANYAR', NULL, '0'),
	('3204140008', '3204140', 'WARGAMEKAR', NULL, '0'),
	('3204150001', '3204150', 'BATUKARUT', NULL, '0'),
	('3204150002', '3204150', 'MANGUNJAYA', NULL, '0'),
	('3204150003', '3204150', 'MEKARJAYA', NULL, '0'),
	('3204150004', '3204150', 'BAROS', NULL, '0'),
	('3204150005', '3204150', 'LEBAKWANGI', NULL, '0'),
	('3204150006', '3204150', 'WARGALUYU', NULL, '0'),
	('3204150007', '3204150', 'ARJASARI', NULL, '0'),
	('3204150008', '3204150', 'PINGGIRSARI', NULL, '0'),
	('3204150009', '3204150', 'PATROLSARI', NULL, '0'),
	('3204150010', '3204150', 'RANCAKOLE', NULL, '0'),
	('3204150011', '3204150', 'ANCOLMEKAR', NULL, '0'),
	('3204160001', '3204160', 'MEKARJAYA', NULL, '0'),
	('3204160002', '3204160', 'BANJARAN WETAN', NULL, '0'),
	('3204160003', '3204160', 'CIAPUS', NULL, '0'),
	('3204160004', '3204160', 'SINDANGPANON', NULL, '0'),
	('3204160005', '3204160', 'NEGLASARI', NULL, '0'),
	('3204160006', '3204160', 'MARGAHURIP', NULL, '0'),
	('3204160007', '3204160', 'KIANGROKE', NULL, '0'),
	('3204160008', '3204160', 'KAMASAN', NULL, '0'),
	('3204160009', '3204160', 'BANJARAN', NULL, '0'),
	('3204160010', '3204160', 'TARAJUSARI', NULL, '0'),
	('3204160011', '3204160', 'PASIRMULYA', NULL, '0'),
	('3204161001', '3204161', 'JATISARI', NULL, '0'),
	('3204161002', '3204161', 'NAGRAK', NULL, '0'),
	('3204161003', '3204161', 'BANDASARI', NULL, '0'),
	('3204161004', '3204161', 'PANANJUNG', NULL, '0'),
	('3204161005', '3204161', 'CILUNCAT', NULL, '0'),
	('3204161006', '3204161', 'CANGKUANG', NULL, '0'),
	('3204161007', '3204161', 'TANJUNGSARI', NULL, '0'),
	('3204170001', '3204170', 'BOJONGMANGGU', NULL, '0'),
	('3204170002', '3204170', 'LANGONSARI', NULL, '0'),
	('3204170003', '3204170', 'SUKASARI', NULL, '0'),
	('3204170004', '3204170', 'RANCAMULYA', NULL, '0'),
	('3204170005', '3204170', 'RANCATUNGKU', NULL, '0'),
	('3204170006', '3204170', 'BOJONGKUNCI', NULL, '0'),
	('3204180004', '3204180', 'GANDASARI', NULL, '0'),
	('3204180005', '3204180', 'KATAPANG', NULL, '0'),
	('3204180006', '3204180', 'CILAMPENI', NULL, '0'),
	('3204180007', '3204180', 'PANGAUBAN', NULL, '0'),
	('3204180008', '3204180', 'BANYUSARI', NULL, '0'),
	('3204180009', '3204180', 'SANGKANHURIP', NULL, '0'),
	('3204180010', '3204180', 'SUKAMUKTI', NULL, '0'),
	('3204190002', '3204190', 'SADU', NULL, '0'),
	('3204190003', '3204190', 'SUKAJADI', NULL, '0'),
	('3204190004', '3204190', 'SUKANAGARA', NULL, '0'),
	('3204190005', '3204190', 'PANYIRAPAN', NULL, '0'),
	('3204190006', '3204190', 'KARAMATMULYA', NULL, '0'),
	('3204190007', '3204190', 'SOREANG', NULL, '0'),
	('3204190008', '3204190', 'PAMEKARAN', NULL, '0'),
	('3204190019', '3204190', 'PARUNGSERAB', NULL, '0'),
	('3204190020', '3204190', 'SEKARWANGI', NULL, '0'),
	('3204190021', '3204190', 'CINGCIN', NULL, '0'),
	('3204191001', '3204191', 'CILAME', NULL, '0'),
	('3204191002', '3204191', 'BUNINAGARA', NULL, '0'),
	('3204191003', '3204191', 'PADASUKA', NULL, '0'),
	('3204191004', '3204191', 'SUKAMULYA', NULL, '0'),
	('3204191005', '3204191', 'KUTAWARINGIN', NULL, '0'),
	('3204191006', '3204191', 'KOPO', NULL, '0'),
	('3204191007', '3204191', 'CIBODAS', NULL, '0'),
	('3204191008', '3204191', 'JATISARI', NULL, '0'),
	('3204191009', '3204191', 'JELEGONG', NULL, '0'),
	('3204191010', '3204191', 'GAJAHMEKAR', NULL, '0'),
	('3204191011', '3204191', 'PAMEUNTASAN', NULL, '0'),
	('3204250001', '3204250', 'NANJUNG', NULL, '0'),
	('3204250002', '3204250', 'MEKAR RAHAYU', NULL, '0'),
	('3204250003', '3204250', 'RAHAYU', NULL, '0'),
	('3204250004', '3204250', 'CIGONDEWAH HILIR', NULL, '0'),
	('3204250005', '3204250', 'MARGAASIH', NULL, '0'),
	('3204250006', '3204250', 'LAGADAR', NULL, '0'),
	('3204260001', '3204260', 'SULAEMAN', NULL, '0'),
	('3204260002', '3204260', 'SUKAMENAK', NULL, '0'),
	('3204260003', '3204260', 'SAYATI', NULL, '0'),
	('3204260004', '3204260', 'MARGAHAYU SELATAN', NULL, '0'),
	('3204260005', '3204260', 'MARGAHAYU TENGAH', NULL, '0'),
	('3204270001', '3204270', 'CANGKUANG KULON', NULL, '0'),
	('3204270002', '3204270', 'CANGKUANG WETAN', NULL, '0'),
	('3204270003', '3204270', 'PASAWAHAN', NULL, '0'),
	('3204270004', '3204270', 'DAYEUHKOLOT', NULL, '0'),
	('3204270005', '3204270', 'CITEUREUP', NULL, '0'),
	('3204270006', '3204270', 'SUKAPURA', NULL, '0'),
	('3204280001', '3204280', 'BOJONGSARI', NULL, '0'),
	('3204280002', '3204280', 'BOJONGSOANG', NULL, '0'),
	('3204280003', '3204280', 'LENGKONG', NULL, '0'),
	('3204280004', '3204280', 'CIPAGALO', NULL, '0'),
	('3204280005', '3204280', 'BUAHBATU', NULL, '0'),
	('3204280006', '3204280', 'TEGALLUAR', NULL, '0'),
	('3204290001', '3204290', 'CIBIRU HILIR', NULL, '0'),
	('3204290002', '3204290', 'CINUNUK', NULL, '0'),
	('3204290003', '3204290', 'CIMEKAR', NULL, '0'),
	('3204290004', '3204290', 'CILEUNYI KULON', NULL, '0'),
	('3204290005', '3204290', 'CILEUNYI WETAN', NULL, '0'),
	('3204290006', '3204290', 'CIBIRU WETAN', NULL, '0'),
	('3204300001', '3204300', 'GIRIMEKAR', NULL, '0'),
	('3204300002', '3204300', 'JATIENDAH', NULL, '0'),
	('3204300003', '3204300', 'MELATIWANGI', NULL, '0'),
	('3204300004', '3204300', 'CIPANJALU', NULL, '0'),
	('3204300005', '3204300', 'CIPOREAT', NULL, '0'),
	('3204300006', '3204300', 'CILENGKRANG', NULL, '0'),
	('3204310001', '3204310', 'CIBEUNYING', NULL, '0'),
	('3204310002', '3204310', 'PADASUKA', NULL, '0'),
	('3204310003', '3204310', 'MANDALAMEKAR', NULL, '0'),
	('3204310004', '3204310', 'CIKADUT', NULL, '0'),
	('3204310005', '3204310', 'SINDANGLAYA', NULL, '0'),
	('3204310006', '3204310', 'MEKARMANIK', NULL, '0'),
	('3204310007', '3204310', 'CIMENYAN', NULL, '0'),
	('3204310008', '3204310', 'MEKARSALUYU', NULL, '0'),
	('3204310009', '3204310', 'CIBURIAL', NULL, '0'),
	('3217010001', '3217010', 'CICADAS', NULL, '0'),
	('3217010002', '3217010', 'CIBEDUG', NULL, '0'),
	('3217010003', '3217010', 'SUKAMANAH', NULL, '0'),
	('3217010004', '3217010', 'BOJONG', NULL, '0'),
	('3217010005', '3217010', 'BOJONGSALAM', NULL, '0'),
	('3217010006', '3217010', 'CINENGAH', NULL, '0'),
	('3217010007', '3217010', 'SUKARESMI', NULL, '0'),
	('3217010008', '3217010', 'CIBITUNG', NULL, '0'),
	('3217020001', '3217020', 'CILANGARI', NULL, '0'),
	('3217020002', '3217020', 'SINDANGJAYA', NULL, '0'),
	('3217020003', '3217020', 'BUNIJAYA', NULL, '0'),
	('3217020004', '3217020', 'SIRNAJAYA', NULL, '0'),
	('3217020005', '3217020', 'GUNUNGHALU', NULL, '0'),
	('3217020006', '3217020', 'CELAK', NULL, '0'),
	('3217020007', '3217020', 'WARGASALUYU', NULL, '0'),
	('3217020008', '3217020', 'SUKASARI', NULL, '0'),
	('3217020009', '3217020', 'TAMANJAYA', NULL, '0'),
	('3217030001', '3217030', 'MEKARWANGI', NULL, '0'),
	('3217030002', '3217030', 'WENINGGALIH', NULL, '0'),
	('3217030003', '3217030', 'WANGUNSARI', NULL, '0'),
	('3217030004', '3217030', 'BUNINAGARA', NULL, '0'),
	('3217030005', '3217030', 'CIKADU', NULL, '0'),
	('3217030006', '3217030', 'RANCA SENGGANG', NULL, '0'),
	('3217030007', '3217030', 'CINTAKARYA', NULL, '0'),
	('3217030008', '3217030', 'CICANGKANG GIRANG', NULL, '0'),
	('3217030009', '3217030', 'PUNCAKSARI', NULL, '0'),
	('3217030010', '3217030', 'PASIRPOGOR', NULL, '0'),
	('3217030011', '3217030', 'SINDANGKERTA', NULL, '0'),
	('3217040001', '3217040', 'KARYAMUKTI', NULL, '0'),
	('3217040002', '3217040', 'NANGGERANG', NULL, '0'),
	('3217040003', '3217040', 'MUKAPAYUNG', NULL, '0'),
	('3217040004', '3217040', 'RANCAPANGGUNG', NULL, '0'),
	('3217040005', '3217040', 'BONGAS', NULL, '0'),
	('3217040006', '3217040', 'BATULAYANG', NULL, '0'),
	('3217040007', '3217040', 'CILILIN', NULL, '0'),
	('3217040008', '3217040', 'KARANGTANJUNG', NULL, '0'),
	('3217040010', '3217040', 'KIDANGPANANJUNG', NULL, '0'),
	('3217040018', '3217040', 'BUDIHARJA', NULL, '0'),
	('3217040019', '3217040', 'KARANGANYAR', NULL, '0'),
	('3217050009', '3217050', 'SINGAJAYA', NULL, '0'),
	('3217050011', '3217050', 'TANJUNGWANGI', NULL, '0'),
	('3217050012', '3217050', 'SITUWANGI', NULL, '0'),
	('3217050013', '3217050', 'PATARUMAN', NULL, '0'),
	('3217050014', '3217050', 'CIPATIK', NULL, '0'),
	('3217050015', '3217050', 'CITAPEN', NULL, '0'),
	('3217050016', '3217050', 'CIHAMPELAS', NULL, '0'),
	('3217050017', '3217050', 'MEKARMUKTI', NULL, '0'),
	('3217050020', '3217050', 'TANJUNGJAYA', NULL, '0'),
	('3217050021', '3217050', 'MEKARJAYA', NULL, '0'),
	('3217060001', '3217060', 'CINTAASIH', NULL, '0'),
	('3217060002', '3217060', 'KARANGSARI', NULL, '0'),
	('3217060003', '3217060', 'NEGLASARI', NULL, '0'),
	('3217060004', '3217060', 'GIRIMUKTI', NULL, '0'),
	('3217060005', '3217060', 'CIJENUK', NULL, '0'),
	('3217060006', '3217060', 'CICANGKANG HILIR', NULL, '0'),
	('3217060007', '3217060', 'SUKAMULYA', NULL, '0'),
	('3217060008', '3217060', 'CITALEM', NULL, '0'),
	('3217060009', '3217060', 'MEKARSARI', NULL, '0'),
	('3217060010', '3217060', 'SARINAGEN', NULL, '0'),
	('3217060011', '3217060', 'CIBENDA', NULL, '0'),
	('3217060012', '3217060', 'CIJAMBU', NULL, '0'),
	('3217060013', '3217060', 'SIRNAGALIH', NULL, '0'),
	('3217060014', '3217060', 'BARANANGSIANG', NULL, '0'),
	('3217070001', '3217070', 'SELACAU', NULL, '0'),
	('3217070002', '3217070', 'BATUJAJAR BARAT', NULL, '0'),
	('3217070003', '3217070', 'BATUJAJAR TIMUR', NULL, '0'),
	('3217070004', '3217070', 'GIRIASIH', NULL, '0'),
	('3217070005', '3217070', 'GALANGGANG', NULL, '0'),
	('3217070006', '3217070', 'PANGAUBAN', NULL, '0'),
	('3217070007', '3217070', 'CANGKORAH', NULL, '0'),
	('3217071001', '3217071', 'BOJONGHALEUANG', NULL, '0'),
	('3217071002', '3217071', 'CIKANDE', NULL, '0'),
	('3217071003', '3217071', 'GIRIMUKTI', NULL, '0'),
	('3217071004', '3217071', 'CIPANGERAN', NULL, '0'),
	('3217071005', '3217071', 'JATI', NULL, '0'),
	('3217071006', '3217071', 'SAGULING', NULL, '0'),
	('3217080001', '3217080', 'RAJAMANDALA KULON', NULL, '0'),
	('3217080002', '3217080', 'CIPTAHARJA', NULL, '0'),
	('3217080003', '3217080', 'CIPATAT', NULL, '0'),
	('3217080004', '3217080', 'CITATAH', NULL, '0'),
	('3217080005', '3217080', 'GUNUNGMASIGIT', NULL, '0'),
	('3217080006', '3217080', 'CIRAWAMEKAR', NULL, '0'),
	('3217080007', '3217080', 'NYALINDUNG', NULL, '0'),
	('3217080008', '3217080', 'SUMURBANDUNG', NULL, '0'),
	('3217080009', '3217080', 'KERTAMUKTI', NULL, '0'),
	('3217080010', '3217080', 'SARIMUKTI', NULL, '0'),
	('3217080011', '3217080', 'MANDALASARI', NULL, '0'),
	('3217080012', '3217080', 'MANDALAWANGI', NULL, '0'),
	('3217090001', '3217090', 'LAKSANAMEKAR', NULL, '0'),
	('3217090002', '3217090', 'CIMERANG', NULL, '0'),
	('3217090003', '3217090', 'CIPEUNDEUY', NULL, '0'),
	('3217090004', '3217090', 'KERTAJAYA', NULL, '0'),
	('3217090005', '3217090', 'JAYAMEKAR', NULL, '0'),
	('3217090006', '3217090', 'PADALARANG', NULL, '0'),
	('3217090007', '3217090', 'KERTAMULYA', NULL, '0'),
	('3217090008', '3217090', 'CIBURUY', NULL, '0'),
	('3217090009', '3217090', 'TAGOGAPU', NULL, '0'),
	('3217090010', '3217090', 'CEMPAKAMEKAR', NULL, '0'),
	('3217100001', '3217100', 'CIMAREME', NULL, '0'),
	('3217100002', '3217100', 'GADOBANGKONG', NULL, '0'),
	('3217100003', '3217100', 'TANIMULYA', NULL, '0'),
	('3217100004', '3217100', 'PAKUHAJI', NULL, '0'),
	('3217100005', '3217100', 'CILAME', NULL, '0'),
	('3217100006', '3217100', 'MARGAJAYA', NULL, '0'),
	('3217100007', '3217100', 'MEKARSARI', NULL, '0'),
	('3217100008', '3217100', 'NGAMPRAH', NULL, '0'),
	('3217100009', '3217100', 'SUKATANI', NULL, '0'),
	('3217100010', '3217100', 'CIMANGGU', NULL, '0'),
	('3217100011', '3217100', 'BOJONGKONENG', NULL, '0'),
	('3217110001', '3217110', 'CIWARUGA', NULL, '0'),
	('3217110002', '3217110', 'CIHIDEUNG', NULL, '0'),
	('3217110003', '3217110', 'CIGUGUR GIRANG', NULL, '0'),
	('3217110004', '3217110', 'SARIWANGI', NULL, '0'),
	('3217110005', '3217110', 'CIHANJUANG', NULL, '0'),
	('3217110006', '3217110', 'CIHANJUANG RAHAYU', NULL, '0'),
	('3217110007', '3217110', 'KARYAWANGI', NULL, '0'),
	('3217120001', '3217120', 'GUDANGKAHURIPAN', NULL, '0'),
	('3217120002', '3217120', 'WANGUNSARI', NULL, '0'),
	('3217120003', '3217120', 'PAGERWANGI', NULL, '0'),
	('3217120004', '3217120', 'MEKARWANGI', NULL, '0'),
	('3217120005', '3217120', 'LANGENSARI', NULL, '0'),
	('3217120006', '3217120', 'KAYUAMBON', NULL, '0'),
	('3217120007', '3217120', 'LEMBANG', NULL, '0'),
	('3217120008', '3217120', 'CIKAHURIPAN', NULL, '0'),
	('3217120009', '3217120', 'SUKAJAYA', NULL, '0'),
	('3217120010', '3217120', 'JAYAGIRI', NULL, '0'),
	('3217120011', '3217120', 'CIBOGO', NULL, '0'),
	('3217120012', '3217120', 'CIKOLE', NULL, '0'),
	('3217120013', '3217120', 'CIKIDANG', NULL, '0'),
	('3217120014', '3217120', 'WANGUNHARJA', NULL, '0'),
	('3217120015', '3217120', 'CIBODAS', NULL, '0'),
	('3217120016', '3217120', 'SUNTENJAYA', NULL, '0'),
	('3217130001', '3217130', 'PASIRHALANG', NULL, '0'),
	('3217130002', '3217130', 'JAMBUDIPA', NULL, '0'),
	('3217130003', '3217130', 'PADAASIH', NULL, '0'),
	('3217130004', '3217130', 'KERTAWANGI', NULL, '0'),
	('3217130005', '3217130', 'TUGUMUKTI', NULL, '0'),
	('3217130006', '3217130', 'PASIRLANGU', NULL, '0'),
	('3217130007', '3217130', 'CIPADA', NULL, '0'),
	('3217130008', '3217130', 'SADANGMEKAR', NULL, '0'),
	('3217140001', '3217140', 'KANANGASARI', NULL, '0'),
	('3217140002', '3217140', 'MANDALASARI', NULL, '0'),
	('3217140003', '3217140', 'MEKARJAYA', NULL, '0'),
	('3217140004', '3217140', 'CIPADA', NULL, '0'),
	('3217140005', '3217140', 'GANJARSARI', NULL, '0'),
	('3217140006', '3217140', 'MANDALAMUKTI', NULL, '0'),
	('3217140007', '3217140', 'CIPTAGUMATI', NULL, '0'),
	('3217140008', '3217140', 'CIKALONG', NULL, '0'),
	('3217140009', '3217140', 'RENDE', NULL, '0'),
	('3217140010', '3217140', 'PUTERAN', NULL, '0'),
	('3217140011', '3217140', 'TENJOLAUT', NULL, '0'),
	('3217140012', '3217140', 'CISOMANG BARAT', NULL, '0'),
	('3217140013', '3217140', 'WANGUNJAYA', NULL, '0'),
	('3217150001', '3217150', 'MARGALUYU', NULL, '0'),
	('3217150002', '3217150', 'NANGGELENG', NULL, '0'),
	('3217150003', '3217150', 'SIRNARAJA', NULL, '0'),
	('3217150004', '3217150', 'JATIMEKAR', NULL, '0'),
	('3217150005', '3217150', 'BOJONGMEKAR', NULL, '0'),
	('3217150006', '3217150', 'NYENANG', NULL, '0'),
	('3217150007', '3217150', 'CIPEUNDEUY', NULL, '0'),
	('3217150008', '3217150', 'MARGALAKSANA', NULL, '0'),
	('3217150009', '3217150', 'SUKAHAJI', NULL, '0'),
	('3217150010', '3217150', 'CIHARASHAS', NULL, '0'),
	('3217150011', '3217150', 'SIRNAGALIH', NULL, '0'),
	('3217150012', '3217150', 'CIROYOM', NULL, '0'),
	('3273010001', '3273010', 'GEMPOL SARI', '40215', '0'),
	('3273010002', '3273010', 'CIGONDEWAH KALER', '40214', '0'),
	('3273010003', '3273010', 'CIGONDEWAH KIDUL', '40214', '0'),
	('3273010004', '3273010', 'CIGONDEWAH RAHAYU', '40215', '0'),
	('3273010005', '3273010', 'CARINGIN', '40212', '0'),
	('3273010006', '3273010', 'WARUNG MUNCANG', '40211', '0'),
	('3273010007', '3273010', 'CIBUNTU', '40212', '0'),
	('3273010008', '3273010', 'CIJERAH', '40213', '0'),
	('3273020001', '3273020', 'MARGASUKA', '40225', '0'),
	('3273020002', '3273020', 'CIRANGRANG', '40227', '0'),
	('3273020003', '3273020', 'MARGAHAYU UTARA', '40224', '0'),
	('3273020004', '3273020', 'BABAKAN CIPARAY', '40223', '0'),
	('3273020005', '3273020', 'BABAKAN', '40222', '0'),
	('3273020006', '3273020', 'SUKAHAJI', '40221', '0'),
	('3273030001', '3273030', 'KOPO', '40233', '0'),
	('3273030002', '3273030', 'SUKA ASIH', '40231', '0'),
	('3273030003', '3273030', 'BABAKAN ASIH', '40232', '0'),
	('3273030004', '3273030', 'BABAKAN TAROGONG', '40232', '0'),
	('3273030005', '3273030', 'JAMIKA', '40231', '0'),
	('3273040001', '3273040', 'CIBADUYUT KIDUL', '40239', '0'),
	('3273040002', '3273040', 'CIBADUYUT WETAN', '40238', '0'),
	('3273040003', '3273040', 'MEKAR WANGI', '40237', '0'),
	('3273040004', '3273040', 'CIBADUYUT', '40236', '0'),
	('3273040005', '3273040', 'KEBON LEGA', '40235', '0'),
	('3273040006', '3273040', 'SITUSAEUR', '40234', '0'),
	('3273050001', '3273050', 'KARASAK', '40243', '0'),
	('3273050002', '3273050', 'PELINDUNG HEWAN', '40243', '0'),
	('3273050003', '3273050', 'NYENGSERET', '40242', '0'),
	('3273050004', '3273050', 'PANJUNAN', '40242', '0'),
	('3273050005', '3273050', 'CIBADAK', '40241', '0'),
	('3273050006', '3273050', 'KARANG ANYAR', '40241', '0'),
	('3273060001', '3273060', 'CISEUREUH', '40255', '0'),
	('3273060002', '3273060', 'PASIRLUYU', '40254', '0'),
	('3273060003', '3273060', 'ANCOL', '40254', '0'),
	('3273060004', '3273060', 'CIGERELENG', '40253', '0'),
	('3273060005', '3273060', 'CIATEUL', '40252', '0'),
	('3273060006', '3273060', 'PUNGKUR', '40252', '0'),
	('3273060007', '3273060', 'BALONG GEDE', '40251', '0'),
	('3273070001', '3273070', 'CIJAGRA', '40265', '0'),
	('3273070002', '3273070', 'TURANGGA', '40264', '0'),
	('3273070003', '3273070', 'LINGKAR SELATAN', '40263', '0'),
	('3273070004', '3273070', 'MALABAR', '40262', '0'),
	('3273070005', '3273070', 'BURANGRANG', '40262', '0'),
	('3273070006', '3273070', 'CIKAWAO', '40261', '0'),
	('3273070007', '3273070', 'PALEDANG', '40261', '0'),
	('3273080001', '3273080', 'WATES', '40256', '0'),
	('3273080002', '3273080', 'MENGGER', '40267', '0'),
	('3273080003', '3273080', 'BATUNUNGGAL', '40266', '0'),
	('3273080004', '3273080', 'KUJANGSARI', '40287', '0'),
	('3273090001', '3273090', 'CIJAURA', '40287', '0'),
	('3273090002', '3273090', 'MARGASARI', '40286', '0'),
	('3273090003', '3273090', 'SEKEJATI', '40286', '0'),
	('3273090004', '3273090', 'JATI SARI', '40286', '0'),
	('3273100001', '3273100', 'DERWATI', '40296', '0'),
	('3273100002', '3273100', 'CIPAMOKOLAN', '40292', '0'),
	('3273100005', '3273100', 'MANJAHLEGA', '40295', '0'),
	('3273100006', '3273100', 'MEKARJAYA', '40292', '0'),
	('3273101001', '3273101', 'RANCABOLANG', '40294', '0'),
	('3273101002', '3273101', 'RANCANUMPANG', '40294', '0'),
	('3273101003', '3273101', 'CISARANTEN KIDUL', '40294', '0'),
	('3273101004', '3273101', 'CIMINCRANG', '40294', '0'),
	('3273110003', '3273110', 'PASIR BIRU', '40615', '0'),
	('3273110004', '3273110', 'CIPADUNG', '40614', '0'),
	('3273110005', '3273110', 'PALASARI', '40615', '0'),
	('3273110006', '3273110', 'CISURUPAN', '40614', '0'),
	('3273111001', '3273111', 'MEKAR MULYA', '40614', '0'),
	('3273111002', '3273111', 'CIPADUNG KIDUL', '40614', '0'),
	('3273111003', '3273111', 'CIPADUNG WETAN', '40614', '0'),
	('3273111004', '3273111', 'CIPADUNG KULON', '40614', '0'),
	('3273120003', '3273120', 'PASANGGRAHAN', '40617', '0'),
	('3273120004', '3273120', 'PASIRJATI', '40616', '0'),
	('3273120005', '3273120', 'PASIR WANGI', '40618', '0'),
	('3273120006', '3273120', 'CIGENDING', '40611', '0'),
	('3273120007', '3273120', 'PASIR ENDAH', '40619', '0'),
	('3273121001', '3273121', 'CISARANTEN WETAN', '40294', '0'),
	('3273121002', '3273121', 'BABAKAN PENGHULU', '40294', '0'),
	('3273121003', '3273121', 'PAKEMITAN', '40294', '0'),
	('3273121004', '3273121', 'SUKAMULYA', '40294', '0'),
	('3273130001', '3273130', 'CISARANTEN KULON', '40293', '0'),
	('3273130002', '3273130', 'CISARANTEN BINA HARAPAN', '40294', '0'),
	('3273130003', '3273130', 'SUKAMISKIN', '40293', '0'),
	('3273130005', '3273130', 'CISARANTEN ENDAH', '40293', '0'),
	('3273141001', '3273141', 'ANTAPANI KIDUL', '40291', '0'),
	('3273141002', '3273141', 'ANTAPANI TENGAH', '40291', '0'),
	('3273141003', '3273141', 'ANTAPANI WETAN', '40291', '0'),
	('3273141004', '3273141', 'ANTAPANI KULON', '40291', '0'),
	('3273142001', '3273142', 'JATIHANDAP', '40195', '0'),
	('3273142002', '3273142', 'KARANG PAMULANG', '40195', '0'),
	('3273142003', '3273142', 'SINDANG JAYA', '40195', '0'),
	('3273142004', '3273142', 'PASIR IMPUN', '40195', '0'),
	('3273150001', '3273150', 'KEBON KANGKUNG', '40284', '0'),
	('3273150002', '3273150', 'SUKAPURA', '40285', '0'),
	('3273150003', '3273150', 'KEBUN JAYANTI', '40281', '0'),
	('3273150004', '3273150', 'BABAKAN SARI', '40283', '0'),
	('3273150005', '3273150', 'BABAKAN SURABAYA', '40281', '0'),
	('3273150006', '3273150', 'CICAHEUM', '40282', '0'),
	('3273160001', '3273160', 'GUMURUH', '40275', '0'),
	('3273160002', '3273160', 'BINONG', '40275', '0'),
	('3273160003', '3273160', 'KEBON GEDANG', '40274', '0'),
	('3273160004', '3273160', 'MALEER', '40274', '0'),
	('3273160005', '3273160', 'CIBANGKONG', '40273', '0'),
	('3273160006', '3273160', 'SAMOJA', '40273', '0'),
	('3273160007', '3273160', 'KACAPIRING', '40271', '0'),
	('3273160008', '3273160', 'KEBON WARU', '40272', '0'),
	('3273170001', '3273170', 'BRAGA', '40111', '0'),
	('3273170002', '3273170', 'KEBON PISANG', '40112', '0'),
	('3273170003', '3273170', 'MERDEKA', '40113', '0'),
	('3273170004', '3273170', 'BABAKAN CIAMIS', '40117', '0'),
	('3273180001', '3273180', 'CAMPAKA', '40184', '0'),
	('3273180002', '3273180', 'MALEBER', '40184', '0'),
	('3273180003', '3273180', 'GARUDA', '40184', '0'),
	('3273180004', '3273180', 'DUNGUS CARIANG', '40183', '0'),
	('3273180005', '3273180', 'CIROYOM', '40182', '0'),
	('3273180006', '3273180', 'KEBON JERUK', '40181', '0'),
	('3273190001', '3273190', 'ARJUNA', '40172', '0'),
	('3273190002', '3273190', 'PASIRKALIKI', '40171', '0'),
	('3273190003', '3273190', 'PAMOYANAN', '40173', '0'),
	('3273190004', '3273190', 'PAJAJARAN', '40173', '0'),
	('3273190005', '3273190', 'HUSEN SASTRANEGARA', '40174', '0'),
	('3273190006', '3273190', 'SUKARAJA', '40175', '0'),
	('3273200001', '3273200', 'TAMAN SARI', '40116', '0'),
	('3273200002', '3273200', 'CITARUM', '40115', '0'),
	('3273200003', '3273200', 'CIHAPIT', '40114', '0'),
	('3273210001', '3273210', 'SUKAMAJU', '40121', '0'),
	('3273210002', '3273210', 'CICADAS', '40121', '0'),
	('3273210003', '3273210', 'CIKUTRA', '40124', '0'),
	('3273210004', '3273210', 'PADASUKA', '40125', '0'),
	('3273210005', '3273210', 'PASIRLAYUNG', '40192', '0'),
	('3273210006', '3273210', 'SUKAPADA', '40125', '0'),
	('3273220001', '3273220', 'CIHAURGEULIS', '40122', '0'),
	('3273220002', '3273220', 'SUKALUYU', '40123', '0'),
	('3273220003', '3273220', 'NEGLASARI', '40124', '0'),
	('3273220004', '3273220', 'CIGADUNG', '40191', '0'),
	('3273230001', '3273230', 'CIPAGANTI', '40131', '0'),
	('3273230002', '3273230', 'LEBAK SILIWANGI', '40132', '0'),
	('3273230003', '3273230', 'LEBAK GEDE', '40132', '0'),
	('3273230004', '3273230', 'SADANG SERANG', '40133', '0'),
	('3273230005', '3273230', 'SEKELOA', '40134', '0'),
	('3273230006', '3273230', 'DAGO', '40135', '0'),
	('3273240001', '3273240', 'SUKAWARNA', '40164', '0'),
	('3273240002', '3273240', 'SUKAGALIH', '40163', '0'),
	('3273240003', '3273240', 'SUKABUNGAH', '40162', '0'),
	('3273240004', '3273240', 'CIPEDES', '40162', '0'),
	('3273240005', '3273240', 'PASTEUR', '40161', '0'),
	('3273250001', '3273250', 'SARIJADI', '40151', '0'),
	('3273250002', '3273250', 'SUKARASA', '40152', '0'),
	('3273250003', '3273250', 'GEGERKALONG', '40153', '0'),
	('3273250004', '3273250', 'ISOLA', '40154', '0'),
	('3273260001', '3273260', 'HEGARMANAH', '40141', '0'),
	('3273260002', '3273260', 'CIUMBULEUIT', '40142', '0'),
	('3273260003', '3273260', 'LEDENG', '40143', '0'),
	('3507010001', '3507010', 'SUMBEROTO', NULL, '0'),
	('3507010002', '3507010', 'PURWOREJO', NULL, '0'),
	('3507010003', '3507010', 'MENTARAMAN', NULL, '0'),
	('3507010004', '3507010', 'DONOMULYO', NULL, '0'),
	('3507010005', '3507010', 'TEMPURSARI', NULL, '0'),
	('3507010006', '3507010', 'TLOGOSARI', NULL, '0'),
	('3507010007', '3507010', 'KEDUNGSALAM', NULL, '0'),
	('3507010008', '3507010', 'BANJARJO', NULL, '0'),
	('3507010009', '3507010', 'TULUNGREJO', NULL, '0'),
	('3507010010', '3507010', 'PURWODADI', NULL, '0'),
	('3507020001', '3507020', 'ARJOSARI', NULL, '0'),
	('3507020002', '3507020', 'TUMPAKREJO', NULL, '0'),
	('3507020003', '3507020', 'KALIASRI', NULL, '0'),
	('3507020004', '3507020', 'PUTUKREJO', NULL, '0'),
	('3507020005', '3507020', 'SUMBERPETUNG', NULL, '0'),
	('3507020006', '3507020', 'KALIPARE', NULL, '0'),
	('3507020007', '3507020', 'SUKOWILANGUN', NULL, '0'),
	('3507020008', '3507020', 'ARJOWILANGUN', NULL, '0'),
	('3507020009', '3507020', 'KALIREJO', NULL, '0'),
	('3507030001', '3507030', 'SUMBERMANJING KULON', NULL, '0'),
	('3507030002', '3507030', 'PANDANREJO', NULL, '0'),
	('3507030003', '3507030', 'SUMBERKERTO', NULL, '0'),
	('3507030004', '3507030', 'SEMPOL', NULL, '0'),
	('3507030005', '3507030', 'PAGAK', NULL, '0'),
	('3507030006', '3507030', 'SUMBERREJO', NULL, '0'),
	('3507030007', '3507030', 'GAMPINGAN', NULL, '0'),
	('3507030008', '3507030', 'TLOGOREJO', NULL, '0'),
	('3507040001', '3507040', 'BANDUNGREJO', NULL, '0'),
	('3507040002', '3507040', 'SUMBERBENING', NULL, '0'),
	('3507040003', '3507040', 'SRIGONCO', NULL, '0'),
	('3507040004', '3507040', 'WONOREJO', NULL, '0'),
	('3507040005', '3507040', 'BANTUR', NULL, '0'),
	('3507040006', '3507040', 'PRINGGODANI', NULL, '0'),
	('3507040007', '3507040', 'REJOSARI', NULL, '0'),
	('3507040008', '3507040', 'WONOKERTO', NULL, '0'),
	('3507040009', '3507040', 'REJOYOSO', NULL, '0'),
	('3507040010', '3507040', 'KARANGSARI', NULL, '0'),
	('3507050001', '3507050', 'TUMPAKREJO', NULL, '0'),
	('3507050002', '3507050', 'SINDUREJO', NULL, '0'),
	('3507050003', '3507050', 'GAJAHREJO', NULL, '0'),
	('3507050004', '3507050', 'SIDODADI', NULL, '0'),
	('3507050005', '3507050', 'GEDANGAN', NULL, '0'),
	('3507050006', '3507050', 'SEGARAN', NULL, '0'),
	('3507050007', '3507050', 'SUMBEREJO', NULL, '0'),
	('3507050008', '3507050', 'GIRIMULYO', NULL, '0'),
	('3507060001', '3507060', 'SITIARJO', NULL, '0'),
	('3507060002', '3507060', 'TAMBAKREJO', NULL, '0'),
	('3507060003', '3507060', 'KEDUNGBANTENG', NULL, '0'),
	('3507060004', '3507060', 'TAMBAKASRI', NULL, '0'),
	('3507060005', '3507060', 'TEGALREJO', NULL, '0'),
	('3507060006', '3507060', 'RINGINKEMBAR', NULL, '0'),
	('3507060007', '3507060', 'SUMBERAGUNG', NULL, '0'),
	('3507060008', '3507060', 'HARJOKUNCARAN', NULL, '0'),
	('3507060009', '3507060', 'ARGOTIRTO', NULL, '0'),
	('3507060010', '3507060', 'RINGINSARI', NULL, '0'),
	('3507060011', '3507060', 'DRUJU', NULL, '0'),
	('3507060012', '3507060', 'SUMBERMANJING WETAN', NULL, '0'),
	('3507060013', '3507060', 'KLEPU', NULL, '0'),
	('3507060014', '3507060', 'SEKARBANYU', NULL, '0'),
	('3507060015', '3507060', 'SIDOASRI', NULL, '0'),
	('3507070001', '3507070', 'SUKODONO', NULL, '0'),
	('3507070002', '3507070', 'SRIMULYO', NULL, '0'),
	('3507070003', '3507070', 'BATURETNO', NULL, '0'),
	('3507070004', '3507070', 'BUMIREJO', NULL, '0'),
	('3507070005', '3507070', 'SUMBERSUKO', NULL, '0'),
	('3507070006', '3507070', 'AMADANOM', NULL, '0'),
	('3507070007', '3507070', 'DAMPIT', NULL, '0'),
	('3507070008', '3507070', 'PAMOTAN', NULL, '0'),
	('3507070009', '3507070', 'MAJANGTENGAH', NULL, '0'),
	('3507070010', '3507070', 'REMBUN', NULL, '0'),
	('3507070011', '3507070', 'POJOK', NULL, '0'),
	('3507070012', '3507070', 'JAMBANGAN', NULL, '0'),
	('3507080001', '3507080', 'PURWODADI', NULL, '0'),
	('3507080002', '3507080', 'PUJIHARJO', NULL, '0'),
	('3507080003', '3507080', 'SUMBERTANGKIL', NULL, '0'),
	('3507080004', '3507080', 'KEPATIHAN', NULL, '0'),
	('3507080005', '3507080', 'JOGOMULYAN', NULL, '0'),
	('3507080006', '3507080', 'TIRTOYUDO', NULL, '0'),
	('3507080007', '3507080', 'GADUNGSARI', NULL, '0'),
	('3507080008', '3507080', 'TLOGOSARI', NULL, '0'),
	('3507080009', '3507080', 'SUKOREJO', NULL, '0'),
	('3507080010', '3507080', 'AMPELGADING', NULL, '0'),
	('3507080011', '3507080', 'TAMANKUNCARAN', NULL, '0'),
	('3507080012', '3507080', 'WONOAGUNG', NULL, '0'),
	('3507080013', '3507080', 'TAMANSATRIYAN', NULL, '0'),
	('3507090001', '3507090', 'LEBAKHARJO', NULL, '0'),
	('3507090002', '3507090', 'WIROTAMAN', NULL, '0'),
	('3507090003', '3507090', 'TAMANASRI', NULL, '0'),
	('3507090004', '3507090', 'SONOWANGI', NULL, '0'),
	('3507090005', '3507090', 'TIRTOMARTO', NULL, '0'),
	('3507090006', '3507090', 'PURWOHARJO', NULL, '0'),
	('3507090007', '3507090', 'SIDORENGGO', NULL, '0'),
	('3507090008', '3507090', 'TIRTOMOYO', NULL, '0'),
	('3507090009', '3507090', 'TAWANGAGUNG', NULL, '0'),
	('3507090010', '3507090', 'SIMOJAYAN', NULL, '0'),
	('3507090011', '3507090', 'ARGOYUWONO', NULL, '0'),
	('3507090012', '3507090', 'MULYOASRI', NULL, '0'),
	('3507090013', '3507090', 'TAMANSARI', NULL, '0'),
	('3507100001', '3507100', 'DAWUHAN', NULL, '0'),
	('3507100002', '3507100', 'SUMBEREJO', NULL, '0'),
	('3507100003', '3507100', 'PANDANSARI', NULL, '0'),
	('3507100004', '3507100', 'NGADIRESO', NULL, '0'),
	('3507100005', '3507100', 'KARANGANYAR', NULL, '0'),
	('3507100006', '3507100', 'JAMBESARI', NULL, '0'),
	('3507100007', '3507100', 'PAJARAN', NULL, '0'),
	('3507100008', '3507100', 'ARGOSUKO', NULL, '0'),
	('3507100009', '3507100', 'NGEBRUK', NULL, '0'),
	('3507100010', '3507100', 'KARANGNONGKO', NULL, '0'),
	('3507100011', '3507100', 'WONOMULYO', NULL, '0'),
	('3507100012', '3507100', 'BELUNG', NULL, '0'),
	('3507100013', '3507100', 'WONOREJO', NULL, '0'),
	('3507100014', '3507100', 'PONCOKUSUMO', NULL, '0'),
	('3507100015', '3507100', 'WRINGINANOM', NULL, '0'),
	('3507100016', '3507100', 'GUBUKKLAKAH', NULL, '0'),
	('3507100017', '3507100', 'NGADAS', NULL, '0'),
	('3507110001', '3507110', 'SUMBERPUTIH', NULL, '0'),
	('3507110002', '3507110', 'WONOAYU', NULL, '0'),
	('3507110003', '3507110', 'BAMBANG', NULL, '0'),
	('3507110004', '3507110', 'BRINGIN', NULL, '0'),
	('3507110005', '3507110', 'DADAPAN', NULL, '0'),
	('3507110006', '3507110', 'PATOKPICIS', NULL, '0'),
	('3507110007', '3507110', 'BLAYU', NULL, '0'),
	('3507110008', '3507110', 'CODO', NULL, '0'),
	('3507110009', '3507110', 'SUKOLILO', NULL, '0'),
	('3507110010', '3507110', 'KIDANGBANG', NULL, '0'),
	('3507110011', '3507110', 'SUKOANYAR', NULL, '0'),
	('3507110012', '3507110', 'WAJAK', NULL, '0'),
	('3507110013', '3507110', 'NGEMBAL', NULL, '0'),
	('3507120001', '3507120', 'KEMULAN', NULL, '0'),
	('3507120002', '3507120', 'TAWANGREJENI', NULL, '0'),
	('3507120003', '3507120', 'SAWAHAN', NULL, '0'),
	('3507120004', '3507120', 'UNDAAN', NULL, '0'),
	('3507120005', '3507120', 'GEDOG KULON', NULL, '0'),
	('3507120006', '3507120', 'GEDOG WETAN', NULL, '0'),
	('3507120007', '3507120', 'TALOK', NULL, '0'),
	('3507120008', '3507120', 'SEDAYU', NULL, '0'),
	('3507120009', '3507120', 'TANGGUNG', NULL, '0'),
	('3507120010', '3507120', 'JERU', NULL, '0'),
	('3507120011', '3507120', 'TUREN', NULL, '0'),
	('3507120012', '3507120', 'PAGEDANGAN', NULL, '0'),
	('3507120013', '3507120', 'SANANKERTO', NULL, '0'),
	('3507120014', '3507120', 'SANANREJO', NULL, '0'),
	('3507120015', '3507120', 'KEDOK', NULL, '0'),
	('3507120016', '3507120', 'TALANGSUKO', NULL, '0'),
	('3507120017', '3507120', 'TUMPUKRENTENG', NULL, '0'),
	('3507130001', '3507130', 'SUKONOLO', NULL, '0'),
	('3507130002', '3507130', 'GADING', NULL, '0'),
	('3507130003', '3507130', 'KREBET', NULL, '0'),
	('3507130004', '3507130', 'BAKALAN', NULL, '0'),
	('3507130005', '3507130', 'SUDIMORO', NULL, '0'),
	('3507130006', '3507130', 'KASRI', NULL, '0'),
	('3507130007', '3507130', 'PRINGU', NULL, '0'),
	('3507130008', '3507130', 'KASEMBON', NULL, '0'),
	('3507130009', '3507130', 'KUWOLU', NULL, '0'),
	('3507130010', '3507130', 'KREBET SENGGRONG', NULL, '0'),
	('3507130011', '3507130', 'LUMBANGSARI', NULL, '0'),
	('3507130012', '3507130', 'WANDANPURO', NULL, '0'),
	('3507130013', '3507130', 'BULULAWANG', NULL, '0'),
	('3507130014', '3507130', 'SEMPALWADAK', NULL, '0'),
	('3507140001', '3507140', 'SUKOREJO', NULL, '0'),
	('3507140002', '3507140', 'BULUPITU', NULL, '0'),
	('3507140003', '3507140', 'SUKOSARI', NULL, '0'),
	('3507140004', '3507140', 'PANGGUNGREJO', NULL, '0'),
	('3507140005', '3507140', 'GONDANGLEGI KULON', NULL, '0'),
	('3507140006', '3507140', 'GONDANGLEGI WETAN', NULL, '0'),
	('3507140007', '3507140', 'SEPANJANG', NULL, '0'),
	('3507140008', '3507140', 'PUTAT KIDUL', NULL, '0'),
	('3507140009', '3507140', 'PUTAT LOR', NULL, '0'),
	('3507140010', '3507140', 'UREK UREK', NULL, '0'),
	('3507140011', '3507140', 'KETAWANG', NULL, '0'),
	('3507140012', '3507140', 'GANJARAN', NULL, '0'),
	('3507140013', '3507140', 'PUTUKREJO', NULL, '0'),
	('3507140014', '3507140', 'SUMBERJAYA', NULL, '0'),
	('3507150001', '3507150', 'KANIGORO', NULL, '0'),
	('3507150002', '3507150', 'BALEARJO', NULL, '0'),
	('3507150003', '3507150', 'KADEMANGAN', NULL, '0'),
	('3507150004', '3507150', 'SUWARU', NULL, '0'),
	('3507150005', '3507150', 'CLUMPRIT', NULL, '0'),
	('3507150006', '3507150', 'SIDOREJO', NULL, '0'),
	('3507150007', '3507150', 'PAGELARAN', NULL, '0'),
	('3507150008', '3507150', 'BANJAREJO', NULL, '0'),
	('3507150009', '3507150', 'BRONGKAL', NULL, '0'),
	('3507150010', '3507150', 'KARANGSUKO', NULL, '0'),
	('3507160001', '3507160', 'JENGGOLO', NULL, '0'),
	('3507160002', '3507160', 'SENGGURUH', NULL, '0'),
	('3507160003', '3507160', 'KEMIRI', NULL, '0'),
	('3507160004', '3507160', 'TEGALSARI', NULL, '0'),
	('3507160005', '3507160', 'MANGUNREJO', NULL, '0'),
	('3507160006', '3507160', 'PANGGUNGREJO', NULL, '0'),
	('3507160007', '3507160', 'KEDUNGPEDARINGAN', NULL, '0'),
	('3507160008', '3507160', 'PENARUKAN', NULL, '0'),
	('3507160009', '3507160', 'CEPOKOMULYO', NULL, '0'),
	('3507160010', '3507160', 'KEPANJEN', NULL, '0'),
	('3507160011', '3507160', 'TALANGAGUNG', NULL, '0'),
	('3507160012', '3507160', 'DILEM', NULL, '0'),
	('3507160013', '3507160', 'ARDIREJO', NULL, '0'),
	('3507160014', '3507160', 'SUKORAHARJO', NULL, '0'),
	('3507160015', '3507160', 'CURUNG REJO', NULL, '0'),
	('3507160016', '3507160', 'JATIREJOYOSO', NULL, '0'),
	('3507160017', '3507160', 'NGADILANGKUNG', NULL, '0'),
	('3507160018', '3507160', 'MOJOSARI', NULL, '0'),
	('3507170001', '3507170', 'KARANGKATES', NULL, '0'),
	('3507170002', '3507170', 'SUMBERPUCUNG', NULL, '0'),
	('3507170003', '3507170', 'JATIGUWI', NULL, '0'),
	('3507170004', '3507170', 'SAMBIGEDE', NULL, '0'),
	('3507170005', '3507170', 'SENGGRENG', NULL, '0'),
	('3507170006', '3507170', 'TERNYANG', NULL, '0'),
	('3507170007', '3507170', 'NGEBRUK', NULL, '0'),
	('3507180001', '3507180', 'SLOROK', NULL, '0'),
	('3507180002', '3507180', 'JATIKERTO', NULL, '0'),
	('3507180003', '3507180', 'NGADIREJO', NULL, '0'),
	('3507180004', '3507180', 'KARANGREJO', NULL, '0'),
	('3507180005', '3507180', 'KROMENGAN', NULL, '0'),
	('3507180006', '3507180', 'PENIWEN', NULL, '0'),
	('3507180007', '3507180', 'JAMBUWER', NULL, '0'),
	('3507190001', '3507190', 'NGAJUM', NULL, '0'),
	('3507190002', '3507190', 'PALAAN', NULL, '0'),
	('3507190003', '3507190', 'NGASEM', NULL, '0'),
	('3507190004', '3507190', 'BANJARSARI', NULL, '0'),
	('3507190005', '3507190', 'KRANGGAN', NULL, '0'),
	('3507190006', '3507190', 'KESAMBEN', NULL, '0'),
	('3507190007', '3507190', 'BABADAN', NULL, '0'),
	('3507190008', '3507190', 'BALESARI', NULL, '0'),
	('3507190009', '3507190', 'MAGUAN', NULL, '0'),
	('3507200001', '3507200', 'KLUWUT', NULL, '0'),
	('3507200002', '3507200', 'PLANDI', NULL, '0'),
	('3507200003', '3507200', 'PLAOSAN', NULL, '0'),
	('3507200004', '3507200', 'KEBOBANG', NULL, '0'),
	('3507200005', '3507200', 'BANGELAN', NULL, '0'),
	('3507200006', '3507200', 'SUMBERDEM', NULL, '0'),
	('3507200007', '3507200', 'SUMBERTEMPUR', NULL, '0'),
	('3507200008', '3507200', 'WONOSARI', NULL, '0'),
	('3507210001', '3507210', 'SUMBERSUKO', NULL, '0'),
	('3507210002', '3507210', 'MENDALANWANGI', NULL, '0'),
	('3507210003', '3507210', 'SITIREJO', NULL, '0'),
	('3507210004', '3507210', 'PARANGARGO', NULL, '0'),
	('3507210005', '3507210', 'GONDOWANGI', NULL, '0'),
	('3507210006', '3507210', 'PANDANREJO', NULL, '0'),
	('3507210007', '3507210', 'PETUNGSEWU', NULL, '0'),
	('3507210008', '3507210', 'SUKODADI', NULL, '0'),
	('3507210009', '3507210', 'SIDORAHAYU', NULL, '0'),
	('3507210010', '3507210', 'JEDONG', NULL, '0'),
	('3507210011', '3507210', 'DALISODO', NULL, '0'),
	('3507210012', '3507210', 'PANDANLANDUNG', NULL, '0'),
	('3507220001', '3507220', 'PERMANU', NULL, '0'),
	('3507220002', '3507220', 'KARANGPANDAN', NULL, '0'),
	('3507220003', '3507220', 'GLANGGANG', NULL, '0'),
	('3507220004', '3507220', 'SUTOJAYAN', NULL, '0'),
	('3507220005', '3507220', 'WONOKERSO', NULL, '0'),
	('3507220006', '3507220', 'KARANGDUREN', NULL, '0'),
	('3507220007', '3507220', 'PAKISAJI', NULL, '0'),
	('3507220008', '3507220', 'JATISARI', NULL, '0'),
	('3507220009', '3507220', 'WADUNG', NULL, '0'),
	('3507220010', '3507220', 'GENENGAN', NULL, '0'),
	('3507220011', '3507220', 'KEBONAGUNG', NULL, '0'),
	('3507220012', '3507220', 'KENDALPAYAK', NULL, '0'),
	('3507230001', '3507230', 'TAMBAKASRI', NULL, '0'),
	('3507230002', '3507230', 'TANGKILSARI', NULL, '0'),
	('3507230003', '3507230', 'JAMBEARJO', NULL, '0'),
	('3507230004', '3507230', 'JATISARI', NULL, '0'),
	('3507230005', '3507230', 'PANDANMULYO', NULL, '0'),
	('3507230006', '3507230', 'NGAWONGGO', NULL, '0'),
	('3507230007', '3507230', 'PURWOSEKAR', NULL, '0'),
	('3507230008', '3507230', 'GUNUNGRONGGO', NULL, '0'),
	('3507230009', '3507230', 'GUNUNGSARI', NULL, '0'),
	('3507230010', '3507230', 'TAJINAN', NULL, '0'),
	('3507230011', '3507230', 'RANDUGADING', NULL, '0'),
	('3507230012', '3507230', 'SUMBERSUKO', NULL, '0'),
	('3507240001', '3507240', 'NGINGIT', NULL, '0'),
	('3507240002', '3507240', 'KIDAL', NULL, '0'),
	('3507240003', '3507240', 'KAMBINGAN', NULL, '0'),
	('3507240004', '3507240', 'PANDANAJENG', NULL, '0'),
	('3507240005', '3507240', 'PULUNGDOWO', NULL, '0'),
	('3507240006', '3507240', 'BOKOR', NULL, '0'),
	('3507240007', '3507240', 'SLAMET', NULL, '0'),
	('3507240008', '3507240', 'WRINGINSONGO', NULL, '0'),
	('3507240009', '3507240', 'JERU', NULL, '0'),
	('3507240010', '3507240', 'MALANGSUKO', NULL, '0'),
	('3507240011', '3507240', 'TUMPANG', NULL, '0'),
	('3507240012', '3507240', 'TULUSBESAR', NULL, '0'),
	('3507240013', '3507240', 'BENJOR', NULL, '0'),
	('3507240014', '3507240', 'DUWET', NULL, '0'),
	('3507240015', '3507240', 'DUWET KRAJAN', NULL, '0'),
	('3507250001', '3507250', 'SEKARPURO', NULL, '0'),
	('3507250002', '3507250', 'AMPELDENTO', NULL, '0'),
	('3507250003', '3507250', 'SUMBERKRADENAN', NULL, '0'),
	('3507250004', '3507250', 'KEDUNGREJO', NULL, '0'),
	('3507250005', '3507250', 'BANJARREJO', NULL, '0'),
	('3507250006', '3507250', 'PUCANG SONGO', NULL, '0'),
	('3507250007', '3507250', 'SUKOANYAR', NULL, '0'),
	('3507250008', '3507250', 'SUMBERPASIR', NULL, '0'),
	('3507250009', '3507250', 'PAKISKEMBAR', NULL, '0'),
	('3507250010', '3507250', 'PAKISJAJAR', NULL, '0'),
	('3507250011', '3507250', 'BUNUTWETAN', NULL, '0'),
	('3507250012', '3507250', 'ASRIKATON', NULL, '0'),
	('3507250013', '3507250', 'SAPTORENGGO', NULL, '0'),
	('3507250014', '3507250', 'MANGLIAWAN', NULL, '0'),
	('3507250015', '3507250', 'TIRTOMOYO', NULL, '0'),
	('3507260001', '3507260', 'KENONGO', NULL, '0'),
	('3507260002', '3507260', 'NGADIREJO', NULL, '0'),
	('3507260003', '3507260', 'TAJI', NULL, '0'),
	('3507260004', '3507260', 'PANDANSARI LOR', NULL, '0'),
	('3507260005', '3507260', 'SUKOPURO', NULL, '0'),
	('3507260006', '3507260', 'SIDOREJO', NULL, '0'),
	('3507260007', '3507260', 'SUKOLILO', NULL, '0'),
	('3507260008', '3507260', 'SIDOMULYO', NULL, '0'),
	('3507260009', '3507260', 'GADING KEMBAR', NULL, '0'),
	('3507260010', '3507260', 'KEMANTREN', NULL, '0'),
	('3507260011', '3507260', 'ARGOSARI', NULL, '0'),
	('3507260012', '3507260', 'SLAMPAREJO', NULL, '0'),
	('3507260013', '3507260', 'KEMIRI', NULL, '0'),
	('3507260014', '3507260', 'JABUNG', NULL, '0'),
	('3507260015', '3507260', 'GUNUNG JATI', NULL, '0'),
	('3507270001', '3507270', 'SIDOLUHUR', NULL, '0'),
	('3507270002', '3507270', 'SRIGADING', NULL, '0'),
	('3507270003', '3507270', 'SIDODADI', NULL, '0'),
	('3507270004', '3507270', 'BEDALI', NULL, '0'),
	('3507270005', '3507270', 'KALIREJO', NULL, '0'),
	('3507270006', '3507270', 'MULYOARJO', NULL, '0'),
	('3507270007', '3507270', 'SUMBER NGEPOH', NULL, '0'),
	('3507270008', '3507270', 'SUMBER PORONG', NULL, '0'),
	('3507270009', '3507270', 'TURIREJO', NULL, '0'),
	('3507270010', '3507270', 'LAWANG', NULL, '0'),
	('3507270011', '3507270', 'KETINDAN', NULL, '0'),
	('3507270012', '3507270', 'WONOREJO', NULL, '0'),
	('3507280001', '3507280', 'LANGLANG', '65153', '0'),
	('3507280002', '3507280', 'TUNJUNGTIRTO', '65153', '0'),
	('3507280003', '3507280', 'BANJARARUM', '65153', '0'),
	('3507280004', '3507280', 'WATUGEDE', '65153', '0'),
	('3507280005', '3507280', 'DENGKOL', '65153', '0'),
	('3507280006', '3507280', 'WONOREJO', '65153', '0'),
	('3507280007', '3507280', 'BATURETNO', '65153', '0'),
	('3507280008', '3507280', 'TAMANHARJO', '65153', '0'),
	('3507280009', '3507280', 'LOSARI', '65153', '0'),
	('3507280010', '3507280', 'PAGENTAN', '65153', '0'),
	('3507280011', '3507280', 'PURWOASRI', '65153', '0'),
	('3507280012', '3507280', 'KLAMPOK', '65153', '0'),
	('3507280013', '3507280', 'GUNUNGREJO', '65153', '0'),
	('3507280014', '3507280', 'CANDIRENGGO', '65153', '0'),
	('3507280015', '3507280', 'ARDIMULYO', '65153', '0'),
	('3507280016', '3507280', 'RANDUAGUNG', '65153', '0'),
	('3507280017', '3507280', 'TOYOMARTO', '65153', '0'),
	('3507290001', '3507290', 'TEGALGONDO', NULL, '0'),
	('3507290002', '3507290', 'KEPUHARJO', NULL, '0'),
	('3507290003', '3507290', 'NGENEP', NULL, '0'),
	('3507290004', '3507290', 'NGIJO', NULL, '0'),
	('3507290005', '3507290', 'AMPELDENTO', NULL, '0'),
	('3507290006', '3507290', 'GIRIMOYO', NULL, '0'),
	('3507290007', '3507290', 'BOCEK', NULL, '0'),
	('3507290008', '3507290', 'DONOWARIH', NULL, '0'),
	('3507290009', '3507290', 'TAWANGARGO', NULL, '0'),
	('3507300001', '3507300', 'KUCUR', NULL, '0'),
	('3507300002', '3507300', 'KALISONGO', NULL, '0'),
	('3507300003', '3507300', 'KARANGWIDORO', NULL, '0'),
	('3507300004', '3507300', 'PETUNG SEWU', NULL, '0'),
	('3507300005', '3507300', 'SELOREJO', NULL, '0'),
	('3507300006', '3507300', 'TEGALWERU', NULL, '0'),
	('3507300007', '3507300', 'LANDUNGSARI', NULL, '0'),
	('3507300008', '3507300', 'GADINGKULON', NULL, '0'),
	('3507300009', '3507300', 'MULYOAGUNG', NULL, '0'),
	('3507300010', '3507300', 'SUMBERSEKAR', NULL, '0'),
	('3507310001', '3507310', 'BENDOSARI', NULL, '0'),
	('3507310002', '3507310', 'SUKOMULYO', NULL, '0'),
	('3507310003', '3507310', 'PUJON KIDUL', NULL, '0'),
	('3507310004', '3507310', 'PANDESARI', NULL, '0'),
	('3507310005', '3507310', 'PUJON LOR', NULL, '0'),
	('3507310006', '3507310', 'NGROTO', NULL, '0'),
	('3507310007', '3507310', 'NGABAB', NULL, '0'),
	('3507310008', '3507310', 'TAWANGSARI', NULL, '0'),
	('3507310009', '3507310', 'MADIREDO', NULL, '0'),
	('3507310010', '3507310', 'WIYUREJO', NULL, '0'),
	('3507320001', '3507320', 'PAGERSARI', NULL, '0'),
	('3507320002', '3507320', 'SIDODADI', NULL, '0'),
	('3507320003', '3507320', 'BANJAREJO', NULL, '0'),
	('3507320004', '3507320', 'PURWOREJO', NULL, '0'),
	('3507320005', '3507320', 'NGANTRU', NULL, '0'),
	('3507320006', '3507320', 'BANTUREJO', NULL, '0'),
	('3507320007', '3507320', 'PANDANSARI', NULL, '0'),
	('3507320008', '3507320', 'MULYOREJO', NULL, '0'),
	('3507320009', '3507320', 'SUMBERAGUNG', NULL, '0'),
	('3507320010', '3507320', 'KAUMREJO', NULL, '0'),
	('3507320011', '3507320', 'TULUNGREJO', NULL, '0'),
	('3507320012', '3507320', 'WATUREJO', NULL, '0'),
	('3507320013', '3507320', 'JOMBOK', NULL, '0'),
	('3507330001', '3507330', 'PONDOK AGUNG', NULL, '0'),
	('3507330002', '3507330', 'BAYEM', NULL, '0'),
	('3507330003', '3507330', 'PAIT', NULL, '0'),
	('3507330004', '3507330', 'WONOAGUNG', NULL, '0'),
	('3507330005', '3507330', 'KASEMBON', NULL, '0'),
	('3507330006', '3507330', 'SUKOSARI', NULL, '0'),
	('3573010001', '3573010', 'ARJOWINANGUN', '65132', '0'),
	('3573010002', '3573010', 'TLOGOWARU', '65133', '0'),
	('3573010003', '3573010', 'WONOKOYO', '65135', '0'),
	('3573010004', '3573010', 'BUMIAYU', '65135', '0'),
	('3573010005', '3573010', 'BURING', '65136', '0'),
	('3573010006', '3573010', 'MERGOSONO', '65134', '0'),
	('3573010007', '3573010', 'KOTALAMA', '65136', '0'),
	('3573010008', '3573010', 'KEDUNGKANDANG', '65137', '0'),
	('3573010009', '3573010', 'SAWOJAJAR', '65139', '0'),
	('3573010010', '3573010', 'MADYOPURO', '65139', '0'),
	('3573010011', '3573010', 'LESANPURO', '65138', '0'),
	('3573010012', '3573010', 'CEMOROKANDANG', '65138', '0'),
	('3573020001', '3573020', 'KEBONSARI', '65149', '0'),
	('3573020002', '3573020', 'GADANG', '65149', '0'),
	('3573020003', '3573020', 'CIPTOMULYO', '65148', '0'),
	('3573020004', '3573020', 'SUKUN', '65147', '0'),
	('3573020005', '3573020', 'BANDUNGREJOSARI', '65148', '0'),
	('3573020006', '3573020', 'BAKALAN KRAJAN', '65148', '0'),
	('3573020007', '3573020', 'MULYOREJO', '65147', '0'),
	('3573020008', '3573020', 'BANDULAN', '65146', '0'),
	('3573020009', '3573020', 'TANJUNGREJO', '65147', '0'),
	('3573020010', '3573020', 'PISANG CANDI', '65146', '0'),
	('3573020011', '3573020', 'KARANG BESUKI', '65149', '0'),
	('3573030001', '3573030', 'KASIN', '65117', '0'),
	('3573030002', '3573030', 'SUKOHARJO', '65118', '0'),
	('3573030003', '3573030', 'KIDUL DALEM', '65119', '0'),
	('3573030004', '3573030', 'KAUMAN', '65119', '0'),
	('3573030005', '3573030', 'BARENG', '65116', '0'),
	('3573030006', '3573030', 'GADINGKASRI', '65115', '0'),
	('3573030007', '3573030', 'ORO ORO DOWO', '65119', '0'),
	('3573030008', '3573030', 'KLOJEN', '65111', '0'),
	('3573030009', '3573030', 'RAMPAL CELAKET', '65111', '0'),
	('3573030010', '3573030', 'SAMAAN', '65112', '0'),
	('3573030011', '3573030', 'PENANGGUNGAN', '65113', '0'),
	('3573040001', '3573040', 'JODIPAN', '65137', '0'),
	('3573040002', '3573040', 'POLEHAN', '65121', '0'),
	('3573040003', '3573040', 'KESATRIAN', '65121', '0'),
	('3573040004', '3573040', 'BUNULREJO', '65123', '0'),
	('3573040005', '3573040', 'PURWANTORO', '65122', '0'),
	('3573040006', '3573040', 'PANDANWANGI', '65124', '0'),
	('3573040007', '3573040', 'BLIMBING', '65125', '0'),
	('3573040008', '3573040', 'PURWODADI', '65125', '0'),
	('3573040009', '3573040', 'POLOWIJEN', '65126', '0'),
	('3573040010', '3573040', 'ARJOSARI', '65126', '0'),
	('3573040011', '3573040', 'BALEARJOSARI', '65126', '0'),
	('3573050001', '3573050', 'MERJOSARI', '65144', '0'),
	('3573050002', '3573050', 'DINOYO', '65144', '0'),
	('3573050003', '3573050', 'SUMBERSARI', '65145', '0'),
	('3573050004', '3573050', 'KETAWANGGEDE', '65145', '0'),
	('3573050005', '3573050', 'JATIMULYO', '65141', '0'),
	('3573050006', '3573050', 'LOWOKWARU', '65141', '0'),
	('3573050007', '3573050', 'TULUSREJO', '65141', '0'),
	('3573050008', '3573050', 'MOJOLANGU', '65142', '0'),
	('3573050009', '3573050', 'TUNJUNGSEKAR', '65142', '0'),
	('3573050010', '3573050', 'TASIKMADU', '65143', '0'),
	('3573050011', '3573050', 'TUNGGULWULUNG', '65143', '0'),
	('3573050012', '3573050', 'TLOGOMAS', '65144', '0');
/*!40000 ALTER TABLE `kelurahan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.konsumen
DROP TABLE IF EXISTS `konsumen`;
CREATE TABLE IF NOT EXISTS `konsumen` (
  `kode_konsumen` varchar(50) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `jk` char(1) DEFAULT NULL,
  `fcm` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `saldo_dompet` double(9,2) DEFAULT '0.00',
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_konsumen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.konsumen: ~17 rows (approximately)
/*!40000 ALTER TABLE `konsumen` DISABLE KEYS */;
INSERT INTO `konsumen` (`kode_konsumen`, `nama`, `notelp`, `jk`, `fcm`, `token`, `email`, `tanggal_lahir`, `tanggal_daftar`, `foto`, `saldo_dompet`, `hapus`) VALUES
	('KNS/3172/170303193506168718', 'ardiansyah oblong', '085746123456', 'L', 'efBHM1FqcVVsgg3Atd4XBFiHWUP2', 'e_qZQP_0sP8:APA91bGzA9rGdYVoXv3c49xWwTXYtUX_EiN6kDsZyzCe3uJEmcapSK2A7tGRtvLLhGIPvlpfL-jL36aV_QO3-z24f-VZgCUY5eP_DcUCqUzofoQ2vTCHgxMG0w3-gt-jnmu28RjL5PtI', 'xmpx.oblong@gmail.com', '2017-03-14', '2017-03-03 19:35:06', NULL, 0.00, '0'),
	('KNS/3517/170701040141869995', 'Prima Yudha', '082131111414', 'P', 'aTKEpsqPqxeSaMATx2TYuNlntid2', 'ddvgZRMTgr0:APA91bEYB48_sHnLUe1mqXTHFTKG2Iy5EClSgOtub9Fz3I39hDVSPZd5bmePBiMNn8V0GWEyqSEBgczZwOz269T-8GVp2ZtmhOm8khZtZjNwWEorIzLuqpf6b5ry98I2vH6ndtXx_Gav', 'kolakpisang.id@gmail.com', '1988-07-24', '2017-07-01 04:01:41', NULL, 0.00, '0'),
	('KNS/3517/170701040507789024', 'Yudha', '082131111414', 'P', 'xkueu75TK5QW52HgViBTTtSpEUQ2', 'dK0h8lzqKwg:APA91bFymX-o90cCusJgYvZ9l5yXK15aoZsSr6p7T-QKKE3nSFB-VBblTgltkbV2LO9pdtMqKkzhBMFByusMtPj6Y4bXZbcOUtTieHDhevqKYbqx6amK6htln9_FwFJ00NiIJJuPskzD', 'case.iphone.unik@gmail.com', '1989-07-24', '2017-07-01 04:05:07', NULL, 0.00, '0'),
	('KNS/BDG/170430213254694464', 'Andi Goan', '081910459605', 'L', 'JrAnMYHmO5aRO5D4IRDrFbku0SZ2', 'dsznlHmZSSY:APA91bE_WlOimnbOC12AI_V3nvvtxMpZxMPIVs867D49hFTn623CYqtJ039CM-p_w-R1TVeUz9KECkPZX-MoILad95oxJxG4k1zkn8w8ZSbNQZPjIaE8l1vr8f3Tb9iYXMNmT0IRSHsW', 'andi.goan@gmail.com', '1983-05-27', '2017-04-30 21:32:54', NULL, 0.00, '0'),
	('KNS/JKT/20161016075806869469', 'Jokur', '08098999', 'L', 'ZDTRSQwlD6WFd7itaKyKbntXAIb2', 'e55XlUbMcsI:APA91bGsYB3Ni9BGzTxQDYphJ1_TxmqdicjaHi_tUNGxAL56mBftdggVHWs7swpF8cP4mGRzuuO3y8n02TaIKpQl7AmImsZznb9cLG0oC0SNOoAKAp8jXnGYQj_aDlZ97iNBpsVvkgIq', 'firmanslash@gmail.com', '2016-10-16', '2016-10-16 07:58:06', 'http://dev.citridia.com/ws.jastrik/manifest/avatarkonsumen/ZDTRSQwlD6WFd7itaKyKbntXAIb2.jpg', 667000.00, '0'),
	('KNS/JKT/20161018123856604725', 'fathir izzuddin', '085790697655', 'L', 'xeCVa3CpkHVueu8sIX34RFEOnEj2', 'dP2Q5QuLzto:APA91bHycro8nMEEMmE1mu5pQqBvvQDlie-vYFrlDoFbfCeK9iFmlCYld4saV343qbzR_IP6VvBRreAw-YzjUnHGyCkJ7v5hN-MN3zHfrvzNSHnhrvuOskcuJYhJNxlTePSL7M981dnx', '3runrunrun@gmail.com', '2016-10-18', '2016-10-18 12:38:56', 'http://dev.citridia.com/ws.jastrik/manifest/avatarkonsumen/xeCVa3CpkHVueu8sIX34RFEOnEj2.jpg', 0.00, '0'),
	('KNS/KPN/161217184934374199', 'onta jawa', '8000', 'L', 'ngJIvO8nl0Xv6SR5iVQaur7gRan2', 'cQfXgTvszJ0:APA91bFNvqi5BNvY6ndmhQfIhoddp6b87ClsXmtD2Inbn2wMGwkdb1cPcKEI2KkDsz8HnWMR7xmQUSUuxNPTTGfFX4vKXJLzx3qHHuEwKfVDWteLyG66Txn1rKlL4wTMlpmNt9fOKOFw', 'ontanyasar@gmail.com', '2016-12-17', '2016-12-17 18:49:34', 'http://dev.citridia.com/ws.jastrik/manifest/avatarkonsumen/ngJIvO8nl0Xv6SR5iVQaur7gRan2.jpg', 0.00, '0'),
	('KNS/KPN/170222165428191495', 'Febi Anto', '212', 'L', 'Qo7hn49I8KdzpkzJ1vbIy0eeMro1', 'dve1xbEQWAo:APA91bFO0J32PYwIKRbk2cyx09sfdsCv5_OmDGlTC6gIyutj_g0wdqMr10-ZWbU5zw0qECO33W_bhBA5P4u4f7DAq4gyiIR3lwiiB5bAwXdGHddKt9ntFgb89oOh_zCKoT47jUwKsPqY', 'phebianto@gmail.com', '1980-02-22', '2017-02-22 16:54:28', 'http://dev.citridia.com/ws.jastrik/manifest/avatarkonsumen/Qo7hn49I8KdzpkzJ1vbIy0eeMro1.jpg', 50000.00, '0'),
	('KNS/MLG/161217182431047299', 'Matthew Koma', '0341423030', 'L', 'd7a5g9f879a8s899dg6sd9sadf9sad9gs6adg0', NULL, 'koma@mail.com', '1992-12-21', '2016-12-17 18:24:31', NULL, 0.00, '0'),
	('KNS/MLG/170207120129491454', 'Yreh Idnawsuky', '08575598020', 'L', '89Gk0RaeLAQb3KVxIDssFHOUYzD2', 'czxmCCZr8-g:APA91bHGHclULJ_ZngS5NlBpIX0qavIIJcMnpM0I0A6N2G-kA0UMfbkUjxpddA3Hd6eV47xZKRc-b0iGYCBG--4TsvBw4ON6Cokl2px1EfBD-P082CuKVNvYkYEdIFCrCLLYYaHeyuzw', 'id.herykuswandi@gmail.com', '2017-02-07', '2017-02-07 12:01:29', NULL, 0.00, '0'),
	('KNS/MLG/170214153117061872', 'Devany Larasati', '085755598020', 'P', 'c1P4GBlAUOY7WBq0EdJrQrsSsvI3', 'dQRkRpCCJyE:APA91bHeuW-pRMOM9oTcUnxN-yplvrg2b1MIxtmVW63wpdMBr9C6UfEoxiBUH33b38OI-ANYJ9AphEcY-xqvMnNQqUD6Y1fgR7rGBKVbBVWLzGcWoOCAjX4KUWg-oY951bIl0WGDo6_V', 'larasatidevany@gmail.com', '2017-02-14', '2017-02-14 15:31:17', NULL, 0.00, '0'),
	('KNS/MLG/170214163317377102', 'Intan Prameswari', '085755598020', 'P', 'Z0NqCZk0K6gQonxhpeYtldqB13f1', 'duV1t_Gsz_A:APA91bE5kwcC3OyNB06psfEabK90-U1RyHw1auzwy4_roE2iQVnuPRNZZ2YbdO3FfzdBNGO37fdptISFeIQ0H-GceNwP-LCmBpe_yArWfi5Zl2sdsyZcZNN-lEZeKNot1E627ptSR_Dc', 'intanprameswari93@gmail.com', '2017-02-14', '2017-02-14 16:33:17', NULL, 0.00, '0'),
	('KNS/MLG/170214163707717620', 'Hery Kuswandi', '085775599', 'L', 'HoiTxvNAxshnJwSRl4FKHXw88V73', 'dDg7xWh13dE:APA91bHyw-e_JSoZV_RF3waIN8B9ZxBK989KkeMaE75JxI_bygfYnWQrnb0YizGI17m7Q0tUK5iAIL6QCYQf6tYJbOv4iagWBSsTPbiDUSNo-4RoY4iHTdKL-rQHa-jcD4TXL2n8BFWy', 'my.herykuswandi@gmail.com', '2017-02-14', '2017-02-14 16:37:07', NULL, 0.00, '0'),
	('KNS/MLG/170303193545637965', 'Dugong', '012345678900', 'L', 'BwKaZ8wx5MbWC4j32KSuw11cxca2', 'cPrIWqre-JI:APA91bG972DRgxm0oBnu1Fby5Lw_VPjoy2bO81mhS2mPK21PUb1n1mB7uCXDKrVQ2-A0gp-uUNjq3a30NuoyGACG1XuvDL2YrTWz6cp4kO_ifOGE9mBmaqpQg0vcWXQ4zPgOteG_dtO-', 'hudolf.mosterhulk@gmail.com', '2017-03-03', '2017-03-03 19:35:45', 'http://dev.citridia.com/ws.jastrik/manifest/avatarkonsumen/BwKaZ8wx5MbWC4j32KSuw11cxca2.jpg', 0.00, '0'),
	('KNS/MLG/20161014214907333930', 'nama', '0341', 'l', 'fcmfathir', NULL, 'fathir@mail.com', '2016-10-14', '2016-10-14 21:49:07', 'fcmfathir.jpg', 0.00, '0'),
	('KNS/MLG/20161018111310949492', 'diantebes aindra', '081945197768', 'l', 'swYrMYR5Ztatq0CwhxkHNzO8x293', 'cSbNbdqCTFY:APA91bH8H642I18bTzq80a0tFK3-b0nFOX-TeORLz-cgJGJxJ0FV7KP96Nz-OQd1bvO1RIBKXy0Br4AxsWgkdKesvkEcr2K_nX6tzTXqrIQGKJ8nT0ORGBuAfJpJSHMpNJKH2CbmhbL4', 'diantebes.aindra@gmail.com', '1994-04-09', '2016-10-18 11:13:10', NULL, 0.00, '0'),
	('KNS/MLG/20170430205851258751', 'Prima Yudha', '082131111414', 'l', '', '', 'primayudha_24@icloud.com', '1990-04-09', '2017-04-30 20:58:10', NULL, 0.00, '0');
/*!40000 ALTER TABLE `konsumen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.konsumen_alamat
DROP TABLE IF EXISTS `konsumen_alamat`;
CREATE TABLE IF NOT EXISTS `konsumen_alamat` (
  `kode_konsumen_alamat` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_konsumen` varchar(50) NOT NULL,
  `kode_kota` char(4) NOT NULL,
  `nama_penerima` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kodepos` char(5) DEFAULT NULL,
  `def` char(1) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_konsumen_alamat`),
  KEY `konsumen_alamat_FKIndex1` (`kode_konsumen`),
  KEY `konsumen_alamat_FKIndex2` (`kode_kota`),
  CONSTRAINT `konsumen_alamat_ibfk_1` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`),
  CONSTRAINT `konsumen_alamat_ibfk_2` FOREIGN KEY (`kode_kota`) REFERENCES `kota` (`kode_kota`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.konsumen_alamat: ~13 rows (approximately)
/*!40000 ALTER TABLE `konsumen_alamat` DISABLE KEYS */;
INSERT INTO `konsumen_alamat` (`kode_konsumen_alamat`, `kode_konsumen`, `kode_kota`, `nama_penerima`, `alamat`, `kelurahan`, `kecamatan`, `kodepos`, `def`, `hapus`) VALUES
	(27, 'KNS/JKT/20161016075806869469', 'KPN', NULL, 'JL. TUMAPEL GANG 4 NO 10', 'PAGENTAN', 'SINGOSARI', '65153', '1', '0'),
	(28, 'KNS/JKT/20161018123856604725', 'MLG', NULL, 'JALAN SOEKARNO HATTA', 'LOWOKWARU', 'LOWOKWARU', '65141', '1', '0'),
	(29, 'KNS/JKT/20161018123856604725', 'MLG', NULL, 'VBT', 'MERJOSARI', 'LOWOKWARU', '65144', '0', '0'),
	(30, 'KNS/JKT/20161016075806869469', 'MLG', NULL, 'VILLA BUKIT TIDAR', 'TLOGOMAS', 'LOWOKWARU', '65144', '0', '0'),
	(31, 'KNS/JKT/20161016075806869469', 'MLG', NULL, 'GYU', 'MERJOSARI', 'LOWOKWARU', '65144', '0', '0'),
	(32, 'KNS/MLG/170214153117061872', 'MLG', NULL, 'JAPAN', 'MERJOSARI', 'LOWOKWARU', '65144', '1', '0'),
	(33, 'KNS/MLG/170214153117061872', 'MLG', NULL, 'VILLA BUKIT TINGGI', 'MERJOSARI', 'LOWOKWARU', '65144', '0', '0'),
	(34, 'KNS/MLG/170207120129491454', 'KPN', NULL, 'BANJARARUM', 'BANJARARUM', 'SINGOSARI', '65153', '0', '0'),
	(35, 'KNS/MLG/170207120129491454', 'MLG', NULL, 'VILLA BUKIT Tidar', 'MERJOSARI', 'LOWOKWARU', '65144', '1', '0'),
	(36, 'KNS/MLG/170214163317377102', 'MLG', NULL, 'LALALALA', 'MERJOSARI', 'LOWOKWARU', '65144', '1', '0'),
	(37, 'KNS/MLG/170214163707717620', 'MLG', NULL, 'JAJJA', 'MERJOSARI', 'LOWOKWARU', '65144', '1', '0'),
	(39, 'KNS/KPN/170222165428191495', 'KPN', NULL, 'TUMAPEL 4 10', 'PAGENTAN', 'SINGOSARI', '65153', '1', '0'),
	(40, 'KNS/MLG/20161018111310949492', 'MLG', NULL, 'JL AMGGREK GARUDA 11A', 'JATIMULYO', 'LOWOKWARU', '65141', '1', '0');
/*!40000 ALTER TABLE `konsumen_alamat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.konsumen_bank
DROP TABLE IF EXISTS `konsumen_bank`;
CREATE TABLE IF NOT EXISTS `konsumen_bank` (
  `kode_konsumen_bank` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_jenis_bank` smallint(5) unsigned NOT NULL,
  `kode_konsumen` varchar(50) NOT NULL,
  `norek` varchar(20) DEFAULT NULL,
  `atas_nama` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_konsumen_bank`),
  KEY `konsumen_bank_FKIndex1` (`kode_konsumen`),
  KEY `konsumen_bank_FKIndex2` (`kode_jenis_bank`),
  CONSTRAINT `konsumen_bank_ibfk_1` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`),
  CONSTRAINT `konsumen_bank_ibfk_2` FOREIGN KEY (`kode_jenis_bank`) REFERENCES `jenis_bank` (`kode_jenis_bank`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.konsumen_bank: ~3 rows (approximately)
/*!40000 ALTER TABLE `konsumen_bank` DISABLE KEYS */;
INSERT INTO `konsumen_bank` (`kode_konsumen_bank`, `kode_jenis_bank`, `kode_konsumen`, `norek`, `atas_nama`, `hapus`) VALUES
	(4, 2, 'KNS/JKT/20161016075806869469', '774873724763274362', 'Joker', '0'),
	(5, 1, 'KNS/MLG/170214153117061872', '55', 'ddd', '0'),
	(6, 2, 'KNS/KPN/170222165428191495', '212', 'wiro sableng', '0');
/*!40000 ALTER TABLE `konsumen_bank` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.konsumen_bayar_beli_dompet
DROP TABLE IF EXISTS `konsumen_bayar_beli_dompet`;
CREATE TABLE IF NOT EXISTS `konsumen_bayar_beli_dompet` (
  `kode_konsumen_bayar_beli_dompet` varchar(50) NOT NULL,
  `kode_konsumen_beli_dompet` varchar(50) NOT NULL,
  `kode_konsumen_bank` bigint(20) NOT NULL,
  `kode_bank_pusat` smallint(5) unsigned NOT NULL,
  `tanggal_konfirmasi` datetime DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  `tanggal_transfer` datetime NOT NULL,
  PRIMARY KEY (`kode_konsumen_bayar_beli_dompet`),
  KEY `konsumen_bayar_beli_dompe_FKIndex1` (`kode_konsumen_beli_dompet`),
  KEY `konsumen_bayar_beli_dompe_FKIndex2` (`kode_konsumen_bank`),
  KEY `konsumen_bayar_beli_dompe_FKIndex3` (`kode_bank_pusat`),
  CONSTRAINT `konsumen_bayar_beli_dompet_ibfk_1` FOREIGN KEY (`kode_konsumen_beli_dompet`) REFERENCES `konsumen_beli_dompet` (`kode_konsumen_beli_dompet`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `konsumen_bayar_beli_dompet_ibfk_2` FOREIGN KEY (`kode_konsumen_bank`) REFERENCES `konsumen_bank` (`kode_konsumen_bank`),
  CONSTRAINT `konsumen_bayar_beli_dompet_ibfk_3` FOREIGN KEY (`kode_bank_pusat`) REFERENCES `bank_pusat` (`kode_bank_pusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.konsumen_bayar_beli_dompet: ~7 rows (approximately)
/*!40000 ALTER TABLE `konsumen_bayar_beli_dompet` DISABLE KEYS */;
INSERT INTO `konsumen_bayar_beli_dompet` (`kode_konsumen_bayar_beli_dompet`, `kode_konsumen_beli_dompet`, `kode_konsumen_bank`, `kode_bank_pusat`, `tanggal_konfirmasi`, `foto`, `keterangan`, `hapus`, `tanggal_transfer`) VALUES
	('BBD/170125214757839839', 'BLD/170125214737201102', 4, 2, '2017-01-25 21:47:57', 'http://dev.citridia.com/ws.jastrik/manifest/konfirmasibld/BLD170125214737201102.jpg', 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-01-25 21:47:57'),
	('BBD/170130003050434936', 'BLD/170130003030502905', 4, 2, '2017-01-30 00:30:50', 'http://dev.citridia.com/ws.jastrik/manifest/konfirmasibld/BLD170130003030502905.jpg', 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-01-30 00:30:50'),
	('BBD/170131120547690130', 'BLD/170131120532225372', 4, 2, '2017-01-31 12:05:47', NULL, 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-01-31 12:05:47'),
	('BBD/170131120651382796', 'BLD/170131120630941192', 4, 2, '2017-01-31 12:06:51', NULL, 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-01-31 12:06:51'),
	('BBD/170131122947029821', 'BLD/170131122928544326', 4, 2, '2017-01-31 12:29:47', NULL, 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-01-31 12:29:47'),
	('BBD/170228155401114331', 'BLD/170228155324290578', 4, 2, '2017-02-28 15:54:01', 'http://dev.citridia.com/ws.jastrik/manifest/konfirmasibld/BLD170228155324290578.jpg', 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-02-28 15:54:01'),
	('BBD/170228215006419269', 'BLD/170228214609254066', 6, 2, '2017-02-28 21:50:06', NULL, 'WAITING FOR CONFIRMATION FROM JASTRIK CONFIRMATION', '0', '2017-02-28 21:50:06');
/*!40000 ALTER TABLE `konsumen_bayar_beli_dompet` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.konsumen_beli_dompet
DROP TABLE IF EXISTS `konsumen_beli_dompet`;
CREATE TABLE IF NOT EXISTS `konsumen_beli_dompet` (
  `kode_konsumen_beli_dompet` varchar(50) NOT NULL,
  `kode_konsumen` varchar(50) NOT NULL,
  `kode_paket_dompet` smallint(6) NOT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `harga` double(12,2) DEFAULT NULL,
  `harga_transfer` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `tanggal_diterima` datetime DEFAULT NULL,
  `status_konsumen_beli_dompet` char(1) DEFAULT NULL COMMENT '0: belum di konfirmasi, 1: terkonfirmasi oleh konsumen, 2: terkonfirmasi oleh jastrik, 3: dibatalkan',
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_konsumen_beli_dompet`),
  KEY `konsumen_beli_dompet_FKIndex1` (`kode_konsumen`),
  KEY `fk_konsumen_beli_dompet_paket_dompet1_idx` (`kode_paket_dompet`),
  CONSTRAINT `fk_konsumen_beli_dompet_paket_dompet1` FOREIGN KEY (`kode_paket_dompet`) REFERENCES `paket_dompet` (`kode_paket_dompet`),
  CONSTRAINT `konsumen_beli_dompet_ibfk_1` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.konsumen_beli_dompet: ~8 rows (approximately)
/*!40000 ALTER TABLE `konsumen_beli_dompet` DISABLE KEYS */;
INSERT INTO `konsumen_beli_dompet` (`kode_konsumen_beli_dompet`, `kode_konsumen`, `kode_paket_dompet`, `nominal`, `harga`, `harga_transfer`, `tanggal`, `tanggal_diterima`, `status_konsumen_beli_dompet`, `hapus`) VALUES
	('BLD/170125214737201102', 'KNS/JKT/20161016075806869469', 3, 50000.00, 51000.00, 51642.00, '2017-01-25 21:47:37', NULL, '2', '0'),
	('BLD/170130003030502905', 'KNS/JKT/20161016075806869469', 3, 50000.00, 51000.00, 51002.00, '2017-01-30 00:30:30', NULL, '2', '0'),
	('BLD/170131120532225372', 'KNS/JKT/20161016075806869469', 3, 50000.00, 51000.00, 51868.00, '2017-01-31 12:05:32', NULL, '2', '0'),
	('BLD/170131120630941192', 'KNS/JKT/20161016075806869469', 3, 50000.00, 51000.00, 51753.00, '2017-01-31 12:06:30', NULL, '2', '0'),
	('BLD/170131122928544326', 'KNS/JKT/20161016075806869469', 3, 50000.00, 51000.00, 51707.00, '2017-01-31 12:29:28', NULL, '2', '0'),
	('BLD/170228155324290578', 'KNS/JKT/20161016075806869469', 3, 50000.00, 51000.00, 51906.00, '2017-02-28 15:53:24', NULL, '2', '0'),
	('BLD/170228214609254066', 'KNS/KPN/170222165428191495', 3, 50000.00, 51000.00, 51405.00, '2017-02-28 21:46:09', NULL, '2', '0'),
	('BLD/170429220134063628', 'KNS/JKT/20161018123856604725', 1, 10000.00, 12000.00, 12157.00, '2017-04-29 22:01:34', NULL, '0', '0');
/*!40000 ALTER TABLE `konsumen_beli_dompet` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.konsumen_history_dompet
DROP TABLE IF EXISTS `konsumen_history_dompet`;
CREATE TABLE IF NOT EXISTS `konsumen_history_dompet` (
  `kode_konsumen_history_dompet` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_konsumen` varchar(50) NOT NULL,
  `nominal_masuk` double(12,2) DEFAULT NULL,
  `nominal_keluar` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `ref_konsumen_history_dompet` varchar(20) DEFAULT NULL COMMENT 'referensi ke transaksi atau pembelian dompet',
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_konsumen_history_dompet`),
  KEY `konsumen_history_dompet_FKIndex1` (`kode_konsumen`),
  CONSTRAINT `konsumen_history_dompet_ibfk_1` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.konsumen_history_dompet: ~25 rows (approximately)
/*!40000 ALTER TABLE `konsumen_history_dompet` DISABLE KEYS */;
INSERT INTO `konsumen_history_dompet` (`kode_konsumen_history_dompet`, `kode_konsumen`, `nominal_masuk`, `nominal_keluar`, `tanggal`, `ref_konsumen_history_dompet`, `hapus`) VALUES
	(2, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-01-28 08:54:39', 'BLD/1701252147372011', '0'),
	(4, 'KNS/JKT/20161016075806869469', 0.00, 12000.00, '2017-01-29 23:19:54', 'INV/MLG/170129231954', '0'),
	(5, 'KNS/JKT/20161016075806869469', 0.00, 12000.00, '2017-01-30 00:16:17', 'INV/MLG/170130001617', '0'),
	(6, 'KNS/JKT/20161016075806869469', 0.00, 12000.00, '2017-01-30 00:22:05', 'INV/MLG/170130002205', '0'),
	(7, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-01-30 12:32:06', 'BLD/1701300030305029', '0'),
	(8, 'KNS/JKT/20161016075806869469', 0.00, 75050.00, '2017-01-30 11:36:47', 'INV/MLG/170130113647', '0'),
	(10, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-01-31 12:06:04', 'BLD/1701311205322253', '0'),
	(11, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-01-31 12:07:08', 'BLD/1701311206309411', '0'),
	(12, 'KNS/JKT/20161016075806869469', 0.00, 107950.00, '2017-01-31 12:25:56', 'INV/MLG/170131122556', '0'),
	(13, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-01-31 12:30:04', 'BLD/1701311229285443', '0'),
	(14, 'KNS/JKT/20161016075806869469', 107950.00, 0.00, '2017-01-31 12:30:52', 'INV/MLG/170131122556', '0'),
	(15, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:14:30', 'BLD/1701311205322253', '0'),
	(16, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:16:00', 'BLD/1701311206309411', '0'),
	(17, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:19:25', 'BLD/1701311206309411', '0'),
	(18, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:19:28', 'BLD/1701311206309411', '0'),
	(19, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:19:33', 'BLD/1701311206309411', '0'),
	(20, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:19:37', 'BLD/1701311206309411', '0'),
	(21, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:19:41', 'BLD/1701311206309411', '0'),
	(22, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:20:15', 'BLD/1701311206309411', '0'),
	(23, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-04 05:23:55', 'BLD/1701311206309411', '0'),
	(24, 'KNS/JKT/20161016075806869469', 0.00, 107950.00, '2017-02-21 16:38:35', 'INV/KPN/170221163835', '0'),
	(25, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-28 01:12:59', 'BLD/1701311206309411', '0'),
	(26, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-28 01:13:04', 'BLD/1701311229285443', '0'),
	(27, 'KNS/JKT/20161016075806869469', 50000.00, NULL, '2017-02-28 03:54:58', 'BLD/1702281553242905', '0'),
	(28, 'KNS/KPN/170222165428191495', 50000.00, NULL, '2017-03-12 08:59:00', 'BLD/1702282146092540', '0');
/*!40000 ALTER TABLE `konsumen_history_dompet` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kota
DROP TABLE IF EXISTS `kota`;
CREATE TABLE IF NOT EXISTS `kota` (
  `kode_kota` char(4) NOT NULL,
  `kode_provinsi` char(2) NOT NULL,
  `nama_kota` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kota`),
  KEY `kota_FKIndex1` (`kode_provinsi`),
  CONSTRAINT `kota_ibfk_1` FOREIGN KEY (`kode_provinsi`) REFERENCES `provinsi` (`kode_provinsi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kota: ~514 rows (approximately)
/*!40000 ALTER TABLE `kota` DISABLE KEYS */;
INSERT INTO `kota` (`kode_kota`, `kode_provinsi`, `nama_kota`, `hapus`) VALUES
	('1101', '11', 'KABUPATEN SIMEULUE', '0'),
	('1102', '11', 'KABUPATEN ACEH SINGKIL', '0'),
	('1103', '11', 'KABUPATEN ACEH SELATAN', '0'),
	('1104', '11', 'KABUPATEN ACEH TENGGARA', '0'),
	('1105', '11', 'KABUPATEN ACEH TIMUR', '0'),
	('1106', '11', 'KABUPATEN ACEH TENGAH', '0'),
	('1107', '11', 'KABUPATEN ACEH BARAT', '0'),
	('1108', '11', 'KABUPATEN ACEH BESAR', '0'),
	('1109', '11', 'KABUPATEN PIDIE', '0'),
	('1110', '11', 'KABUPATEN BIREUEN', '0'),
	('1111', '11', 'KABUPATEN ACEH UTARA', '0'),
	('1112', '11', 'KABUPATEN ACEH BARAT DAYA', '0'),
	('1113', '11', 'KABUPATEN GAYO LUES', '0'),
	('1114', '11', 'KABUPATEN ACEH TAMIANG', '0'),
	('1115', '11', 'KABUPATEN NAGAN RAYA', '0'),
	('1116', '11', 'KABUPATEN ACEH JAYA', '0'),
	('1117', '11', 'KABUPATEN BENER MERIAH', '0'),
	('1118', '11', 'KABUPATEN PIDIE JAYA', '0'),
	('1171', '11', 'KOTA BANDA ACEH', '0'),
	('1172', '11', 'KOTA SABANG', '0'),
	('1173', '11', 'KOTA LANGSA', '0'),
	('1174', '11', 'KOTA LHOKSEUMAWE', '0'),
	('1175', '11', 'KOTA SUBULUSSALAM', '0'),
	('1201', '12', 'KABUPATEN NIAS', '0'),
	('1202', '12', 'KABUPATEN MANDAILING NATAL', '0'),
	('1203', '12', 'KABUPATEN TAPANULI SELATAN', '0'),
	('1204', '12', 'KABUPATEN TAPANULI TENGAH', '0'),
	('1205', '12', 'KABUPATEN TAPANULI UTARA', '0'),
	('1206', '12', 'KABUPATEN TOBA SAMOSIR', '0'),
	('1207', '12', 'KABUPATEN LABUHAN BATU', '0'),
	('1208', '12', 'KABUPATEN ASAHAN', '0'),
	('1209', '12', 'KABUPATEN SIMALUNGUN', '0'),
	('1210', '12', 'KABUPATEN DAIRI', '0'),
	('1211', '12', 'KABUPATEN KARO', '0'),
	('1212', '12', 'KABUPATEN DELI SERDANG', '0'),
	('1213', '12', 'KABUPATEN LANGKAT', '0'),
	('1214', '12', 'KABUPATEN NIAS SELATAN', '0'),
	('1215', '12', 'KABUPATEN HUMBANG HASUNDUTAN', '0'),
	('1216', '12', 'KABUPATEN PAKPAK BHARAT', '0'),
	('1217', '12', 'KABUPATEN SAMOSIR', '0'),
	('1218', '12', 'KABUPATEN SERDANG BEDAGAI', '0'),
	('1219', '12', 'KABUPATEN BATU BARA', '0'),
	('1220', '12', 'KABUPATEN PADANG LAWAS UTARA', '0'),
	('1221', '12', 'KABUPATEN PADANG LAWAS', '0'),
	('1222', '12', 'KABUPATEN LABUHAN BATU SELATAN', '0'),
	('1223', '12', 'KABUPATEN LABUHAN BATU UTARA', '0'),
	('1224', '12', 'KABUPATEN NIAS UTARA', '0'),
	('1225', '12', 'KABUPATEN NIAS BARAT', '0'),
	('1271', '12', 'KOTA SIBOLGA', '0'),
	('1272', '12', 'KOTA TANJUNG BALAI', '0'),
	('1273', '12', 'KOTA PEMATANG SIANTAR', '0'),
	('1274', '12', 'KOTA TEBING TINGGI', '0'),
	('1275', '12', 'KOTA MEDAN', '0'),
	('1276', '12', 'KOTA BINJAI', '0'),
	('1277', '12', 'KOTA PADANGSIDIMPUAN', '0'),
	('1278', '12', 'KOTA GUNUNGSITOLI', '0'),
	('1301', '13', 'KABUPATEN KEPULAUAN MENTAWAI', '0'),
	('1302', '13', 'KABUPATEN PESISIR SELATAN', '0'),
	('1303', '13', 'KABUPATEN SOLOK', '0'),
	('1304', '13', 'KABUPATEN SIJUNJUNG', '0'),
	('1305', '13', 'KABUPATEN TANAH DATAR', '0'),
	('1306', '13', 'KABUPATEN PADANG PARIAMAN', '0'),
	('1307', '13', 'KABUPATEN AGAM', '0'),
	('1308', '13', 'KABUPATEN LIMA PULUH KOTA', '0'),
	('1309', '13', 'KABUPATEN PASAMAN', '0'),
	('1310', '13', 'KABUPATEN SOLOK SELATAN', '0'),
	('1311', '13', 'KABUPATEN DHARMASRAYA', '0'),
	('1312', '13', 'KABUPATEN PASAMAN BARAT', '0'),
	('1371', '13', 'KOTA PADANG', '0'),
	('1372', '13', 'KOTA SOLOK', '0'),
	('1373', '13', 'KOTA SAWAH LUNTO', '0'),
	('1374', '13', 'KOTA PADANG PANJANG', '0'),
	('1375', '13', 'KOTA BUKITTINGGI', '0'),
	('1376', '13', 'KOTA PAYAKUMBUH', '0'),
	('1377', '13', 'KOTA PARIAMAN', '0'),
	('1401', '14', 'KABUPATEN KUANTAN SINGINGI', '0'),
	('1402', '14', 'KABUPATEN INDRAGIRI HULU', '0'),
	('1403', '14', 'KABUPATEN INDRAGIRI HILIR', '0'),
	('1404', '14', 'KABUPATEN PELALAWAN', '0'),
	('1405', '14', 'KABUPATEN S I A K', '0'),
	('1406', '14', 'KABUPATEN KAMPAR', '0'),
	('1407', '14', 'KABUPATEN ROKAN HULU', '0'),
	('1408', '14', 'KABUPATEN BENGKALIS', '0'),
	('1409', '14', 'KABUPATEN ROKAN HILIR', '0'),
	('1410', '14', 'KABUPATEN KEPULAUAN MERANTI', '0'),
	('1471', '14', 'KOTA PEKANBARU', '0'),
	('1473', '14', 'KOTA D U M A I', '0'),
	('1501', '15', 'KABUPATEN KERINCI', '0'),
	('1502', '15', 'KABUPATEN MERANGIN', '0'),
	('1503', '15', 'KABUPATEN SAROLANGUN', '0'),
	('1504', '15', 'KABUPATEN BATANG HARI', '0'),
	('1505', '15', 'KABUPATEN MUARO JAMBI', '0'),
	('1506', '15', 'KABUPATEN TANJUNG JABUNG TIMUR', '0'),
	('1507', '15', 'KABUPATEN TANJUNG JABUNG BARAT', '0'),
	('1508', '15', 'KABUPATEN TEBO', '0'),
	('1509', '15', 'KABUPATEN BUNGO', '0'),
	('1571', '15', 'KOTA JAMBI', '0'),
	('1572', '15', 'KOTA SUNGAI PENUH', '0'),
	('1601', '16', 'KABUPATEN OGAN KOMERING ULU', '0'),
	('1602', '16', 'KABUPATEN OGAN KOMERING ILIR', '0'),
	('1603', '16', 'KABUPATEN MUARA ENIM', '0'),
	('1604', '16', 'KABUPATEN LAHAT', '0'),
	('1605', '16', 'KABUPATEN MUSI RAWAS', '0'),
	('1606', '16', 'KABUPATEN MUSI BANYUASIN', '0'),
	('1607', '16', 'KABUPATEN BANYU ASIN', '0'),
	('1608', '16', 'KABUPATEN OGAN KOMERING ULU SELATAN', '0'),
	('1609', '16', 'KABUPATEN OGAN KOMERING ULU TIMUR', '0'),
	('1610', '16', 'KABUPATEN OGAN ILIR', '0'),
	('1611', '16', 'KABUPATEN EMPAT LAWANG', '0'),
	('1612', '16', 'KABUPATEN PENUKAL ABAB LEMATANG ILIR', '0'),
	('1613', '16', 'KABUPATEN MUSI RAWAS UTARA', '0'),
	('1671', '16', 'KOTA PALEMBANG', '0'),
	('1672', '16', 'KOTA PRABUMULIH', '0'),
	('1673', '16', 'KOTA PAGAR ALAM', '0'),
	('1674', '16', 'KOTA LUBUKLINGGAU', '0'),
	('1701', '17', 'KABUPATEN BENGKULU SELATAN', '0'),
	('1702', '17', 'KABUPATEN REJANG LEBONG', '0'),
	('1703', '17', 'KABUPATEN BENGKULU UTARA', '0'),
	('1704', '17', 'KABUPATEN KAUR', '0'),
	('1705', '17', 'KABUPATEN SELUMA', '0'),
	('1706', '17', 'KABUPATEN MUKOMUKO', '0'),
	('1707', '17', 'KABUPATEN LEBONG', '0'),
	('1708', '17', 'KABUPATEN KEPAHIANG', '0'),
	('1709', '17', 'KABUPATEN BENGKULU TENGAH', '0'),
	('1771', '17', 'KOTA BENGKULU', '0'),
	('1801', '18', 'KABUPATEN LAMPUNG BARAT', '0'),
	('1802', '18', 'KABUPATEN TANGGAMUS', '0'),
	('1803', '18', 'KABUPATEN LAMPUNG SELATAN', '0'),
	('1804', '18', 'KABUPATEN LAMPUNG TIMUR', '0'),
	('1805', '18', 'KABUPATEN LAMPUNG TENGAH', '0'),
	('1806', '18', 'KABUPATEN LAMPUNG UTARA', '0'),
	('1807', '18', 'KABUPATEN WAY KANAN', '0'),
	('1808', '18', 'KABUPATEN TULANGBAWANG', '0'),
	('1809', '18', 'KABUPATEN PESAWARAN', '0'),
	('1810', '18', 'KABUPATEN PRINGSEWU', '0'),
	('1811', '18', 'KABUPATEN MESUJI', '0'),
	('1812', '18', 'KABUPATEN TULANG BAWANG BARAT', '0'),
	('1813', '18', 'KABUPATEN PESISIR BARAT', '0'),
	('1871', '18', 'KOTA BANDAR LAMPUNG', '0'),
	('1872', '18', 'KOTA METRO', '0'),
	('1901', '19', 'KABUPATEN BANGKA', '0'),
	('1902', '19', 'KABUPATEN BELITUNG', '0'),
	('1903', '19', 'KABUPATEN BANGKA BARAT', '0'),
	('1904', '19', 'KABUPATEN BANGKA TENGAH', '0'),
	('1905', '19', 'KABUPATEN BANGKA SELATAN', '0'),
	('1906', '19', 'KABUPATEN BELITUNG TIMUR', '0'),
	('1971', '19', 'KOTA PANGKAL PINANG', '0'),
	('2101', '21', 'KABUPATEN KARIMUN', '0'),
	('2102', '21', 'KABUPATEN BINTAN', '0'),
	('2103', '21', 'KABUPATEN NATUNA', '0'),
	('2104', '21', 'KABUPATEN LINGGA', '0'),
	('2105', '21', 'KABUPATEN KEPULAUAN ANAMBAS', '0'),
	('2171', '21', 'KOTA B A T A M', '0'),
	('2172', '21', 'KOTA TANJUNG PINANG', '0'),
	('3101', '31', 'KABUPATEN KEPULAUAN SERIBU', '0'),
	('3171', '31', 'KOTA JAKARTA SELATAN', '0'),
	('3172', '31', 'KOTA JAKARTA TIMUR', '0'),
	('3173', '31', 'KOTA JAKARTA PUSAT', '0'),
	('3174', '31', 'KOTA JAKARTA BARAT', '0'),
	('3175', '31', 'KOTA JAKARTA UTARA', '0'),
	('3201', '32', 'KABUPATEN BOGOR', '0'),
	('3202', '32', 'KABUPATEN SUKABUMI', '0'),
	('3203', '32', 'KABUPATEN CIANJUR', '0'),
	('3205', '32', 'KABUPATEN GARUT', '0'),
	('3206', '32', 'KABUPATEN TASIKMALAYA', '0'),
	('3207', '32', 'KABUPATEN CIAMIS', '0'),
	('3208', '32', 'KABUPATEN KUNINGAN', '0'),
	('3209', '32', 'KABUPATEN CIREBON', '0'),
	('3210', '32', 'KABUPATEN MAJALENGKA', '0'),
	('3211', '32', 'KABUPATEN SUMEDANG', '0'),
	('3212', '32', 'KABUPATEN INDRAMAYU', '0'),
	('3213', '32', 'KABUPATEN SUBANG', '0'),
	('3214', '32', 'KABUPATEN PURWAKARTA', '0'),
	('3215', '32', 'KABUPATEN KARAWANG', '0'),
	('3216', '32', 'KABUPATEN BEKASI', '0'),
	('3218', '32', 'KABUPATEN PANGANDARAN', '0'),
	('3271', '32', 'KOTA BOGOR', '0'),
	('3272', '32', 'KOTA SUKABUMI', '0'),
	('3274', '32', 'KOTA CIREBON', '0'),
	('3275', '32', 'KOTA BEKASI', '0'),
	('3276', '32', 'KOTA DEPOK', '0'),
	('3277', '32', 'KOTA CIMAHI', '0'),
	('3278', '32', 'KOTA TASIKMALAYA', '0'),
	('3279', '32', 'KOTA BANJAR', '0'),
	('3301', '33', 'KABUPATEN CILACAP', '0'),
	('3302', '33', 'KABUPATEN BANYUMAS', '0'),
	('3303', '33', 'KABUPATEN PURBALINGGA', '0'),
	('3304', '33', 'KABUPATEN BANJARNEGARA', '0'),
	('3305', '33', 'KABUPATEN KEBUMEN', '0'),
	('3306', '33', 'KABUPATEN PURWOREJO', '0'),
	('3307', '33', 'KABUPATEN WONOSOBO', '0'),
	('3308', '33', 'KABUPATEN MAGELANG', '0'),
	('3309', '33', 'KABUPATEN BOYOLALI', '0'),
	('3310', '33', 'KABUPATEN KLATEN', '0'),
	('3311', '33', 'KABUPATEN SUKOHARJO', '0'),
	('3312', '33', 'KABUPATEN WONOGIRI', '0'),
	('3313', '33', 'KABUPATEN KARANGANYAR', '0'),
	('3314', '33', 'KABUPATEN SRAGEN', '0'),
	('3315', '33', 'KABUPATEN GROBOGAN', '0'),
	('3316', '33', 'KABUPATEN BLORA', '0'),
	('3317', '33', 'KABUPATEN REMBANG', '0'),
	('3318', '33', 'KABUPATEN PATI', '0'),
	('3319', '33', 'KABUPATEN KUDUS', '0'),
	('3320', '33', 'KABUPATEN JEPARA', '0'),
	('3321', '33', 'KABUPATEN DEMAK', '0'),
	('3322', '33', 'KABUPATEN SEMARANG', '0'),
	('3323', '33', 'KABUPATEN TEMANGGUNG', '0'),
	('3324', '33', 'KABUPATEN KENDAL', '0'),
	('3325', '33', 'KABUPATEN BATANG', '0'),
	('3326', '33', 'KABUPATEN PEKALONGAN', '0'),
	('3327', '33', 'KABUPATEN PEMALANG', '0'),
	('3328', '33', 'KABUPATEN TEGAL', '0'),
	('3329', '33', 'KABUPATEN BREBES', '0'),
	('3371', '33', 'KOTA MAGELANG', '0'),
	('3372', '33', 'KOTA SURAKARTA', '0'),
	('3373', '33', 'KOTA SALATIGA', '0'),
	('3374', '33', 'KOTA SEMARANG', '0'),
	('3375', '33', 'KOTA PEKALONGAN', '0'),
	('3376', '33', 'KOTA TEGAL', '0'),
	('3401', '34', 'KABUPATEN KULON PROGO', '0'),
	('3402', '34', 'KABUPATEN BANTUL', '0'),
	('3403', '34', 'KABUPATEN GUNUNG KIDUL', '0'),
	('3404', '34', 'KABUPATEN SLEMAN', '0'),
	('3471', '34', 'KOTA YOGYAKARTA', '0'),
	('3501', '35', 'KABUPATEN PACITAN', '0'),
	('3502', '35', 'KABUPATEN PONOROGO', '0'),
	('3503', '35', 'KABUPATEN TRENGGALEK', '0'),
	('3504', '35', 'KABUPATEN TULUNGAGUNG', '0'),
	('3505', '35', 'KABUPATEN BLITAR', '0'),
	('3506', '35', 'KABUPATEN KEDIRI', '0'),
	('3508', '35', 'KABUPATEN LUMAJANG', '0'),
	('3509', '35', 'KABUPATEN JEMBER', '0'),
	('3510', '35', 'KABUPATEN BANYUWANGI', '0'),
	('3511', '35', 'KABUPATEN BONDOWOSO', '0'),
	('3512', '35', 'KABUPATEN SITUBONDO', '0'),
	('3513', '35', 'KABUPATEN PROBOLINGGO', '0'),
	('3514', '35', 'KABUPATEN PASURUAN', '0'),
	('3515', '35', 'KABUPATEN SIDOARJO', '0'),
	('3516', '35', 'KABUPATEN MOJOKERTO', '0'),
	('3517', '35', 'KABUPATEN JOMBANG', '0'),
	('3518', '35', 'KABUPATEN NGANJUK', '0'),
	('3519', '35', 'KABUPATEN MADIUN', '0'),
	('3520', '35', 'KABUPATEN MAGETAN', '0'),
	('3521', '35', 'KABUPATEN NGAWI', '0'),
	('3522', '35', 'KABUPATEN BOJONEGORO', '0'),
	('3523', '35', 'KABUPATEN TUBAN', '0'),
	('3524', '35', 'KABUPATEN LAMONGAN', '0'),
	('3525', '35', 'KABUPATEN GRESIK', '0'),
	('3526', '35', 'KABUPATEN BANGKALAN', '0'),
	('3527', '35', 'KABUPATEN SAMPANG', '0'),
	('3528', '35', 'KABUPATEN PAMEKASAN', '0'),
	('3529', '35', 'KABUPATEN SUMENEP', '0'),
	('3571', '35', 'KOTA KEDIRI', '0'),
	('3572', '35', 'KOTA BLITAR', '0'),
	('3574', '35', 'KOTA PROBOLINGGO', '0'),
	('3575', '35', 'KOTA PASURUAN', '0'),
	('3576', '35', 'KOTA MOJOKERTO', '0'),
	('3577', '35', 'KOTA MADIUN', '0'),
	('3578', '35', 'KOTA SURABAYA', '0'),
	('3579', '35', 'KOTA BATU', '0'),
	('3601', '36', 'KABUPATEN PANDEGLANG', '0'),
	('3602', '36', 'KABUPATEN LEBAK', '0'),
	('3603', '36', 'KABUPATEN TANGERANG', '0'),
	('3604', '36', 'KABUPATEN SERANG', '0'),
	('3671', '36', 'KOTA TANGERANG', '0'),
	('3672', '36', 'KOTA CILEGON', '0'),
	('3673', '36', 'KOTA SERANG', '0'),
	('3674', '36', 'KOTA TANGERANG SELATAN', '0'),
	('5101', '51', 'KABUPATEN JEMBRANA', '0'),
	('5102', '51', 'KABUPATEN TABANAN', '0'),
	('5103', '51', 'KABUPATEN BADUNG', '0'),
	('5104', '51', 'KABUPATEN GIANYAR', '0'),
	('5105', '51', 'KABUPATEN KLUNGKUNG', '0'),
	('5106', '51', 'KABUPATEN BANGLI', '0'),
	('5107', '51', 'KABUPATEN KARANG ASEM', '0'),
	('5108', '51', 'KABUPATEN BULELENG', '0'),
	('5171', '51', 'KOTA DENPASAR', '0'),
	('5201', '52', 'KABUPATEN LOMBOK BARAT', '0'),
	('5202', '52', 'KABUPATEN LOMBOK TENGAH', '0'),
	('5203', '52', 'KABUPATEN LOMBOK TIMUR', '0'),
	('5204', '52', 'KABUPATEN SUMBAWA', '0'),
	('5205', '52', 'KABUPATEN DOMPU', '0'),
	('5206', '52', 'KABUPATEN BIMA', '0'),
	('5207', '52', 'KABUPATEN SUMBAWA BARAT', '0'),
	('5208', '52', 'KABUPATEN LOMBOK UTARA', '0'),
	('5271', '52', 'KOTA MATARAM', '0'),
	('5272', '52', 'KOTA BIMA', '0'),
	('5301', '53', 'KABUPATEN SUMBA BARAT', '0'),
	('5302', '53', 'KABUPATEN SUMBA TIMUR', '0'),
	('5303', '53', 'KABUPATEN KUPANG', '0'),
	('5304', '53', 'KABUPATEN TIMOR TENGAH SELATAN', '0'),
	('5305', '53', 'KABUPATEN TIMOR TENGAH UTARA', '0'),
	('5306', '53', 'KABUPATEN BELU', '0'),
	('5307', '53', 'KABUPATEN ALOR', '0'),
	('5308', '53', 'KABUPATEN LEMBATA', '0'),
	('5309', '53', 'KABUPATEN FLORES TIMUR', '0'),
	('5310', '53', 'KABUPATEN SIKKA', '0'),
	('5311', '53', 'KABUPATEN ENDE', '0'),
	('5312', '53', 'KABUPATEN NGADA', '0'),
	('5313', '53', 'KABUPATEN MANGGARAI', '0'),
	('5314', '53', 'KABUPATEN ROTE NDAO', '0'),
	('5315', '53', 'KABUPATEN MANGGARAI BARAT', '0'),
	('5316', '53', 'KABUPATEN SUMBA TENGAH', '0'),
	('5317', '53', 'KABUPATEN SUMBA BARAT DAYA', '0'),
	('5318', '53', 'KABUPATEN NAGEKEO', '0'),
	('5319', '53', 'KABUPATEN MANGGARAI TIMUR', '0'),
	('5320', '53', 'KABUPATEN SABU RAIJUA', '0'),
	('5321', '53', 'KABUPATEN MALAKA', '0'),
	('5371', '53', 'KOTA KUPANG', '0'),
	('6101', '61', 'KABUPATEN SAMBAS', '0'),
	('6102', '61', 'KABUPATEN BENGKAYANG', '0'),
	('6103', '61', 'KABUPATEN LANDAK', '0'),
	('6104', '61', 'KABUPATEN MEMPAWAH', '0'),
	('6105', '61', 'KABUPATEN SANGGAU', '0'),
	('6106', '61', 'KABUPATEN KETAPANG', '0'),
	('6107', '61', 'KABUPATEN SINTANG', '0'),
	('6108', '61', 'KABUPATEN KAPUAS HULU', '0'),
	('6109', '61', 'KABUPATEN SEKADAU', '0'),
	('6110', '61', 'KABUPATEN MELAWI', '0'),
	('6111', '61', 'KABUPATEN KAYONG UTARA', '0'),
	('6112', '61', 'KABUPATEN KUBU RAYA', '0'),
	('6171', '61', 'KOTA PONTIANAK', '0'),
	('6172', '61', 'KOTA SINGKAWANG', '0'),
	('6201', '62', 'KABUPATEN KOTAWARINGIN BARAT', '0'),
	('6202', '62', 'KABUPATEN KOTAWARINGIN TIMUR', '0'),
	('6203', '62', 'KABUPATEN KAPUAS', '0'),
	('6204', '62', 'KABUPATEN BARITO SELATAN', '0'),
	('6205', '62', 'KABUPATEN BARITO UTARA', '0'),
	('6206', '62', 'KABUPATEN SUKAMARA', '0'),
	('6207', '62', 'KABUPATEN LAMANDAU', '0'),
	('6208', '62', 'KABUPATEN SERUYAN', '0'),
	('6209', '62', 'KABUPATEN KATINGAN', '0'),
	('6210', '62', 'KABUPATEN PULANG PISAU', '0'),
	('6211', '62', 'KABUPATEN GUNUNG MAS', '0'),
	('6212', '62', 'KABUPATEN BARITO TIMUR', '0'),
	('6213', '62', 'KABUPATEN MURUNG RAYA', '0'),
	('6271', '62', 'KOTA PALANGKA RAYA', '0'),
	('6301', '63', 'KABUPATEN TANAH LAUT', '0'),
	('6302', '63', 'KABUPATEN KOTA BARU', '0'),
	('6303', '63', 'KABUPATEN BANJAR', '0'),
	('6304', '63', 'KABUPATEN BARITO KUALA', '0'),
	('6305', '63', 'KABUPATEN TAPIN', '0'),
	('6306', '63', 'KABUPATEN HULU SUNGAI SELATAN', '0'),
	('6307', '63', 'KABUPATEN HULU SUNGAI TENGAH', '0'),
	('6308', '63', 'KABUPATEN HULU SUNGAI UTARA', '0'),
	('6309', '63', 'KABUPATEN TABALONG', '0'),
	('6310', '63', 'KABUPATEN TANAH BUMBU', '0'),
	('6311', '63', 'KABUPATEN BALANGAN', '0'),
	('6371', '63', 'KOTA BANJARMASIN', '0'),
	('6372', '63', 'KOTA BANJAR BARU', '0'),
	('6401', '64', 'KABUPATEN PASER', '0'),
	('6402', '64', 'KABUPATEN KUTAI BARAT', '0'),
	('6403', '64', 'KABUPATEN KUTAI KARTANEGARA', '0'),
	('6404', '64', 'KABUPATEN KUTAI TIMUR', '0'),
	('6405', '64', 'KABUPATEN BERAU', '0'),
	('6409', '64', 'KABUPATEN PENAJAM PASER UTARA', '0'),
	('6411', '64', 'KABUPATEN MAHAKAM HULU', '0'),
	('6471', '64', 'KOTA BALIKPAPAN', '0'),
	('6472', '64', 'KOTA SAMARINDA', '0'),
	('6474', '64', 'KOTA BONTANG', '0'),
	('6501', '65', 'KABUPATEN MALINAU', '0'),
	('6502', '65', 'KABUPATEN BULUNGAN', '0'),
	('6503', '65', 'KABUPATEN TANA TIDUNG', '0'),
	('6504', '65', 'KABUPATEN NUNUKAN', '0'),
	('6571', '65', 'KOTA TARAKAN', '0'),
	('7101', '71', 'KABUPATEN BOLAANG MONGONDOW', '0'),
	('7102', '71', 'KABUPATEN MINAHASA', '0'),
	('7103', '71', 'KABUPATEN KEPULAUAN SANGIHE', '0'),
	('7104', '71', 'KABUPATEN KEPULAUAN TALAUD', '0'),
	('7105', '71', 'KABUPATEN MINAHASA SELATAN', '0'),
	('7106', '71', 'KABUPATEN MINAHASA UTARA', '0'),
	('7107', '71', 'KABUPATEN BOLAANG MONGONDOW UTARA', '0'),
	('7108', '71', 'KABUPATEN SIAU TAGULANDANG BIARO', '0'),
	('7109', '71', 'KABUPATEN MINAHASA TENGGARA', '0'),
	('7110', '71', 'KABUPATEN BOLAANG MONGONDOW SELATAN', '0'),
	('7111', '71', 'KABUPATEN BOLAANG MONGONDOW TIMUR', '0'),
	('7171', '71', 'KOTA MANADO', '0'),
	('7172', '71', 'KOTA BITUNG', '0'),
	('7173', '71', 'KOTA TOMOHON', '0'),
	('7174', '71', 'KOTA KOTAMOBAGU', '0'),
	('7201', '72', 'KABUPATEN BANGGAI KEPULAUAN', '0'),
	('7202', '72', 'KABUPATEN BANGGAI', '0'),
	('7203', '72', 'KABUPATEN MOROWALI', '0'),
	('7204', '72', 'KABUPATEN POSO', '0'),
	('7205', '72', 'KABUPATEN DONGGALA', '0'),
	('7206', '72', 'KABUPATEN TOLI-TOLI', '0'),
	('7207', '72', 'KABUPATEN BUOL', '0'),
	('7208', '72', 'KABUPATEN PARIGI MOUTONG', '0'),
	('7209', '72', 'KABUPATEN TOJO UNA-UNA', '0'),
	('7210', '72', 'KABUPATEN SIGI', '0'),
	('7211', '72', 'KABUPATEN BANGGAI LAUT', '0'),
	('7212', '72', 'KABUPATEN MOROWALI UTARA', '0'),
	('7271', '72', 'KOTA PALU', '0'),
	('7301', '73', 'KABUPATEN KEPULAUAN SELAYAR', '0'),
	('7302', '73', 'KABUPATEN BULUKUMBA', '0'),
	('7303', '73', 'KABUPATEN BANTAENG', '0'),
	('7304', '73', 'KABUPATEN JENEPONTO', '0'),
	('7305', '73', 'KABUPATEN TAKALAR', '0'),
	('7306', '73', 'KABUPATEN GOWA', '0'),
	('7307', '73', 'KABUPATEN SINJAI', '0'),
	('7308', '73', 'KABUPATEN MAROS', '0'),
	('7309', '73', 'KABUPATEN PANGKAJENE DAN KEPULAUAN', '0'),
	('7310', '73', 'KABUPATEN BARRU', '0'),
	('7311', '73', 'KABUPATEN BONE', '0'),
	('7312', '73', 'KABUPATEN SOPPENG', '0'),
	('7313', '73', 'KABUPATEN WAJO', '0'),
	('7314', '73', 'KABUPATEN SIDENRENG RAPPANG', '0'),
	('7315', '73', 'KABUPATEN PINRANG', '0'),
	('7316', '73', 'KABUPATEN ENREKANG', '0'),
	('7317', '73', 'KABUPATEN LUWU', '0'),
	('7318', '73', 'KABUPATEN TANA TORAJA', '0'),
	('7322', '73', 'KABUPATEN LUWU UTARA', '0'),
	('7325', '73', 'KABUPATEN LUWU TIMUR', '0'),
	('7326', '73', 'KABUPATEN TORAJA UTARA', '0'),
	('7371', '73', 'KOTA MAKASSAR', '0'),
	('7372', '73', 'KOTA PAREPARE', '0'),
	('7373', '73', 'KOTA PALOPO', '0'),
	('7401', '74', 'KABUPATEN BUTON', '0'),
	('7402', '74', 'KABUPATEN MUNA', '0'),
	('7403', '74', 'KABUPATEN KONAWE', '0'),
	('7404', '74', 'KABUPATEN KOLAKA', '0'),
	('7405', '74', 'KABUPATEN KONAWE SELATAN', '0'),
	('7406', '74', 'KABUPATEN BOMBANA', '0'),
	('7407', '74', 'KABUPATEN WAKATOBI', '0'),
	('7408', '74', 'KABUPATEN KOLAKA UTARA', '0'),
	('7409', '74', 'KABUPATEN BUTON UTARA', '0'),
	('7410', '74', 'KABUPATEN KONAWE UTARA', '0'),
	('7411', '74', 'KABUPATEN KOLAKA TIMUR', '0'),
	('7412', '74', 'KABUPATEN KONAWE KEPULAUAN', '0'),
	('7413', '74', 'KABUPATEN MUNA BARAT', '0'),
	('7414', '74', 'KABUPATEN BUTON TENGAH', '0'),
	('7415', '74', 'KABUPATEN BUTON SELATAN', '0'),
	('7471', '74', 'KOTA KENDARI', '0'),
	('7472', '74', 'KOTA BAUBAU', '0'),
	('7501', '75', 'KABUPATEN BOALEMO', '0'),
	('7502', '75', 'KABUPATEN GORONTALO', '0'),
	('7503', '75', 'KABUPATEN POHUWATO', '0'),
	('7504', '75', 'KABUPATEN BONE BOLANGO', '0'),
	('7505', '75', 'KABUPATEN GORONTALO UTARA', '0'),
	('7571', '75', 'KOTA GORONTALO', '0'),
	('7601', '76', 'KABUPATEN MAJENE', '0'),
	('7602', '76', 'KABUPATEN POLEWALI MANDAR', '0'),
	('7603', '76', 'KABUPATEN MAMASA', '0'),
	('7604', '76', 'KABUPATEN MAMUJU', '0'),
	('7605', '76', 'KABUPATEN MAMUJU UTARA', '0'),
	('7606', '76', 'KABUPATEN MAMUJU TENGAH', '0'),
	('8101', '81', 'KABUPATEN MALUKU TENGGARA BARAT', '0'),
	('8102', '81', 'KABUPATEN MALUKU TENGGARA', '0'),
	('8103', '81', 'KABUPATEN MALUKU TENGAH', '0'),
	('8104', '81', 'KABUPATEN BURU', '0'),
	('8105', '81', 'KABUPATEN KEPULAUAN ARU', '0'),
	('8106', '81', 'KABUPATEN SERAM BAGIAN BARAT', '0'),
	('8107', '81', 'KABUPATEN SERAM BAGIAN TIMUR', '0'),
	('8108', '81', 'KABUPATEN MALUKU BARAT DAYA', '0'),
	('8109', '81', 'KABUPATEN BURU SELATAN', '0'),
	('8171', '81', 'KOTA AMBON', '0'),
	('8172', '81', 'KOTA TUAL', '0'),
	('8201', '82', 'KABUPATEN HALMAHERA BARAT', '0'),
	('8202', '82', 'KABUPATEN HALMAHERA TENGAH', '0'),
	('8203', '82', 'KABUPATEN KEPULAUAN SULA', '0'),
	('8204', '82', 'KABUPATEN HALMAHERA SELATAN', '0'),
	('8205', '82', 'KABUPATEN HALMAHERA UTARA', '0'),
	('8206', '82', 'KABUPATEN HALMAHERA TIMUR', '0'),
	('8207', '82', 'KABUPATEN PULAU MOROTAI', '0'),
	('8208', '82', 'KABUPATEN PULAU TALIABU', '0'),
	('8271', '82', 'KOTA TERNATE', '0'),
	('8272', '82', 'KOTA TIDORE KEPULAUAN', '0'),
	('9101', '91', 'KABUPATEN FAKFAK', '0'),
	('9102', '91', 'KABUPATEN KAIMANA', '0'),
	('9103', '91', 'KABUPATEN TELUK WONDAMA', '0'),
	('9104', '91', 'KABUPATEN TELUK BINTUNI', '0'),
	('9105', '91', 'KABUPATEN MANOKWARI', '0'),
	('9106', '91', 'KABUPATEN SORONG SELATAN', '0'),
	('9107', '91', 'KABUPATEN SORONG', '0'),
	('9108', '91', 'KABUPATEN RAJA AMPAT', '0'),
	('9109', '91', 'KABUPATEN TAMBRAUW', '0'),
	('9110', '91', 'KABUPATEN MAYBRAT', '0'),
	('9111', '91', 'KABUPATEN MANOKWARI SELATAN', '0'),
	('9112', '91', 'KABUPATEN PEGUNUNGAN ARFAK', '0'),
	('9171', '91', 'KOTA SORONG', '0'),
	('9401', '94', 'KABUPATEN MERAUKE', '0'),
	('9402', '94', 'KABUPATEN JAYAWIJAYA', '0'),
	('9403', '94', 'KABUPATEN JAYAPURA', '0'),
	('9404', '94', 'KABUPATEN NABIRE', '0'),
	('9408', '94', 'KABUPATEN KEPULAUAN YAPEN', '0'),
	('9409', '94', 'KABUPATEN BIAK NUMFOR', '0'),
	('9410', '94', 'KABUPATEN PANIAI', '0'),
	('9411', '94', 'KABUPATEN PUNCAK JAYA', '0'),
	('9412', '94', 'KABUPATEN MIMIKA', '0'),
	('9413', '94', 'KABUPATEN BOVEN DIGOEL', '0'),
	('9414', '94', 'KABUPATEN MAPPI', '0'),
	('9415', '94', 'KABUPATEN ASMAT', '0'),
	('9416', '94', 'KABUPATEN YAHUKIMO', '0'),
	('9417', '94', 'KABUPATEN PEGUNUNGAN BINTANG', '0'),
	('9418', '94', 'KABUPATEN TOLIKARA', '0'),
	('9419', '94', 'KABUPATEN SARMI', '0'),
	('9420', '94', 'KABUPATEN KEEROM', '0'),
	('9426', '94', 'KABUPATEN WAROPEN', '0'),
	('9427', '94', 'KABUPATEN SUPIORI', '0'),
	('9428', '94', 'KABUPATEN MAMBERAMO RAYA', '0'),
	('9429', '94', 'KABUPATEN NDUGA', '0'),
	('9430', '94', 'KABUPATEN LANNY JAYA', '0'),
	('9431', '94', 'KABUPATEN MAMBERAMO TENGAH', '0'),
	('9432', '94', 'KABUPATEN YALIMO', '0'),
	('9433', '94', 'KABUPATEN PUNCAK', '0'),
	('9434', '94', 'KABUPATEN DOGIYAI', '0'),
	('9435', '94', 'KABUPATEN INTAN JAYA', '0'),
	('9436', '94', 'KABUPATEN DEIYAI', '0'),
	('9471', '94', 'KOTA JAYAPURA', '0'),
	('BDG', '32', 'KOTA BANDUNG', '0'),
	('KPN', '35', 'KABUPATEN MALANG', '0'),
	('MLG', '35', 'KOTA MALANG', '0'),
	('NPH', '32', 'KABUPATEN BANDUNG BARAT', '0'),
	('SOR', '32', 'KABUPATEN BANDUNG', '0');
/*!40000 ALTER TABLE `kota` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kuota_layanan
DROP TABLE IF EXISTS `kuota_layanan`;
CREATE TABLE IF NOT EXISTS `kuota_layanan` (
  `kode_kuota_layanan` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_jenis_layanan` smallint(5) unsigned NOT NULL,
  `nama_kuota_layanan` varchar(50) DEFAULT NULL,
  `kuota` int(10) unsigned DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kuota_layanan`),
  KEY `kuota_layanan_FKIndex1` (`kode_jenis_layanan`),
  CONSTRAINT `kuota_layanan_ibfk_1` FOREIGN KEY (`kode_jenis_layanan`) REFERENCES `jenis_layanan` (`kode_jenis_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kuota_layanan: ~3 rows (approximately)
/*!40000 ALTER TABLE `kuota_layanan` DISABLE KEYS */;
INSERT INTO `kuota_layanan` (`kode_kuota_layanan`, `kode_jenis_layanan`, `nama_kuota_layanan`, `kuota`, `hapus`) VALUES
	(1, 1, 'KUOTA SATUAN BASIC', 25, '0'),
	(2, 2, 'KUOTA KILOAN BASIC', 50, '0'),
	(3, 3, 'KUOTA METERAN BASIC', 20, '0');
/*!40000 ALTER TABLE `kuota_layanan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kuota_layanan_agen
DROP TABLE IF EXISTS `kuota_layanan_agen`;
CREATE TABLE IF NOT EXISTS `kuota_layanan_agen` (
  `kode_agen` varchar(50) NOT NULL,
  `kode_kuota_layanan` smallint(5) unsigned NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen`,`kode_kuota_layanan`),
  KEY `kuota_layanan_has_agen_FKIndex1` (`kode_kuota_layanan`),
  KEY `kuota_layanan_has_agen_FKIndex2` (`kode_agen`),
  CONSTRAINT `fk_kode_kuota_layanan` FOREIGN KEY (`kode_kuota_layanan`) REFERENCES `kuota_layanan` (`kode_kuota_layanan`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_kodeagen` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kuota_layanan_agen: ~3 rows (approximately)
/*!40000 ALTER TABLE `kuota_layanan_agen` DISABLE KEYS */;
INSERT INTO `kuota_layanan_agen` (`kode_agen`, `kode_kuota_layanan`, `hapus`) VALUES
	('AGN/KPN/161208131039173921', 1, '0'),
	('AGN/KPN/161208131039173921', 2, '0'),
	('AGN/KPN/161208131039173921', 3, '1');
/*!40000 ALTER TABLE `kuota_layanan_agen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kurir
DROP TABLE IF EXISTS `kurir`;
CREATE TABLE IF NOT EXISTS `kurir` (
  `kode_kurir` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `jk` char(1) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `fcm` varchar(50) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `tanggal_daftar` datetime DEFAULT NULL,
  `file_ktp` varchar(200) DEFAULT NULL,
  `file_kk` varchar(200) DEFAULT NULL,
  `kodepos` char(5) DEFAULT NULL,
  `jenis_kurir` char(1) DEFAULT NULL COMMENT '1=umum (semua agen bisa pakai),2=khusus saja (Agen Tertentu),3=umum dan khusus',
  `latitude` varchar(25) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `rating` float(2,1) DEFAULT NULL,
  `foto` varchar(200) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kurir`),
  KEY `kurir_FKIndex1` (`kode_pshcabang`),
  CONSTRAINT `kurir_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kurir: ~6 rows (approximately)
/*!40000 ALTER TABLE `kurir` DISABLE KEYS */;
INSERT INTO `kurir` (`kode_kurir`, `kode_pshcabang`, `nama`, `alamat`, `notelp`, `jk`, `email`, `fcm`, `token`, `tanggal_lahir`, `tanggal_daftar`, `file_ktp`, `file_kk`, `kodepos`, `jenis_kurir`, `latitude`, `longitude`, `rating`, `foto`, `hapus`) VALUES
	('KUR/KPN/20171234813848138443', 'BDG/CAB/6543', 'Kurir Kepanjen', 'Jl Ade Irma Suryani', '+6298837412312', 'l', 'kurir2@mail.com', 'fcmkurir2', NULL, '2016-10-21', '2016-10-21 15:53:16', 'http://localhost/setrika_cabang/cabang/server/php/files/17011284557Screenshot_1.png', 'http://localhost/setrika_cabang/cabang/server/php/files/17011284602Screenshot_3.png', '65141', '1', '-7.984422', '112.625591', NULL, 'http://localhost/setrika_cabang/cabang/server/php/files/17011285803Screenshot_2.png', '0'),
	('KUR/KPN/20179252765245264855', 'BDG/CAB/6543', 'kurir fitsa hats', 'Jl Ade Irma Suryani', '7038', 'l', 'ontanyasar@gmail.com', 'ngJIvO8nl0Xv6SR5iVQaur7gRan2', 'fwh7AZmzdKg:APA91bF6JtOjOPF6w1EOF7H6NwhEAbMrp8EDs_KokG4itM0KZdYrabPzRxPVbucRDq5n7-NULrgk3G94PXLPpl38EJDqYej1heZ4uguUachA9cOUe2O0M9H_ROhX4h7lU1YofJ76s-lX', '2016-10-21', '2016-10-21 15:53:16', 'http://localhost/setrika_cabang/cabang/server/php/files/17011284557Screenshot_1.png', 'http://localhost/setrika_cabang/cabang/server/php/files/17011284602Screenshot_3.png', '65141', '1', '-7.979532', '112.625659', NULL, 'http://dev.citridia.com/ws.jastrik/manifest/avatarkurir/ngJIvO8nl0Xv6SR5iVQaur7gRan2.jpg', '0'),
	('KUR/MLG/17011231214', 'MLG/CAB/20161013101442616542', 'Novitha Anggraini Khusus', 'Jl. Salak Wagir', '+6285755598020', NULL, 'kurur2@gmail.com', NULL, NULL, '1993-07-14', '2017-01-12 03:12:14', 'http://localhost/setrika_cabang/cabang/server/php/files/17011543426Screenshot_2016-01-15-15-43-10.jpg', 'http://localhost/setrika_cabang/cabang/server/php/files/17011543432Screenshot_2016-01-15-15-43-10.jpg', '65158', '2', '-7.9442898', '112.6106009', NULL, 'http://localhost/setrika_cabang/cabang/server/php/files/17011543418transfer.PNG', '0'),
	('KUR/MLG/170115113137', 'MLG/CAB/20161013101442616542', 'Kurir', 'Jl. Salak Wagir', '+6285755598020', NULL, 'speed.rcm199@gmail.com', NULL, NULL, '1993-07-14', '2017-01-15 11:31:37', NULL, NULL, '65158', '3', '-7.9345387', '112.6121893', NULL, NULL, '0'),
	('KUR/MLG/17012444544', 'MLG/CAB/20161013101442616542', 'Indha', 'Jl. Salak Wagir', '+6285755598020', NULL, 'speed.rcm99@gmail.com', NULL, NULL, '1993-07-14', '2017-01-24 04:45:44', 'http://localhost/setrika_cabang/cabang/server/php/files/170124104535161208123501def.png', 'http://localhost/setrika_cabang/cabang/server/php/files/170124104541received_225048051271530.jpeg', '65158', '2', '-7.9452601', '112.5995129', NULL, 'http://localhost/setrika_cabang/cabang/server/php/files/1701241045323.jpg', '0'),
	('KUR/MLG/20161021155316246351', 'MLG/CAB/20161013101442616542', 'SiCepat Umum', 'Jl malang kadak', '+629840323423432', 'l', 'kurir1@mail.com', 'fcmkurir1', NULL, '2016-10-21', '2016-10-21 15:53:16', 'http://localhost/setrika_cabang/cabang/server/php/files/17011284557Screenshot_1.png', 'http://localhost/setrika_cabang/cabang/server/php/files/17011284602Screenshot_3.png', '65141', '1', '-7.94369', '112.5889413', NULL, 'http://localhost/setrika_cabang/cabang/server/php/files/17011285803Screenshot_2.png', '0');
/*!40000 ALTER TABLE `kurir` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kurir_agen
DROP TABLE IF EXISTS `kurir_agen`;
CREATE TABLE IF NOT EXISTS `kurir_agen` (
  `kode_agen` varchar(50) NOT NULL,
  `kode_kurir` varchar(50) NOT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_agen`,`kode_kurir`),
  KEY `agen_has_kurir_FKIndex1` (`kode_agen`),
  KEY `agen_has_kurir_FKIndex2` (`kode_kurir`),
  CONSTRAINT `kurir_agen_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `kurir_agen_ibfk_2` FOREIGN KEY (`kode_kurir`) REFERENCES `kurir` (`kode_kurir`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kurir_agen: ~7 rows (approximately)
/*!40000 ALTER TABLE `kurir_agen` DISABLE KEYS */;
INSERT INTO `kurir_agen` (`kode_agen`, `kode_kurir`, `hapus`) VALUES
	('AGN/KPN/161208131039173921', 'KUR/KPN/20179252765245264855', '0'),
	('AGN/MLG/01010101', 'KUR/MLG/17011231214', '0'),
	('AGN/MLG/03030303', 'KUR/MLG/17012444544', '0'),
	('AGN/MLG/04040404', 'KUR/MLG/17012444544', '0'),
	('AGN/MLG/161129152011027450', 'KUR/MLG/17011231214', '0'),
	('AGN/MLG/161129152011027450', 'KUR/MLG/170115113137', '0'),
	('AGN/MLG/17012444144', 'KUR/MLG/17012444544', '0');
/*!40000 ALTER TABLE `kurir_agen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kurir_bank
DROP TABLE IF EXISTS `kurir_bank`;
CREATE TABLE IF NOT EXISTS `kurir_bank` (
  `kode_kurir_bank` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_kurir` varchar(50) NOT NULL,
  `kode_jenis_bank` smallint(5) unsigned NOT NULL,
  `norek` varchar(20) DEFAULT NULL,
  `atas_nama` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kurir_bank`),
  KEY `kurir_bank_FKIndex1` (`kode_kurir`),
  KEY `kurir_bank_FKIndex2` (`kode_jenis_bank`),
  CONSTRAINT `kurir_bank_ibfk_1` FOREIGN KEY (`kode_kurir`) REFERENCES `kurir` (`kode_kurir`),
  CONSTRAINT `kurir_bank_ibfk_2` FOREIGN KEY (`kode_jenis_bank`) REFERENCES `jenis_bank` (`kode_jenis_bank`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kurir_bank: ~0 rows (approximately)
/*!40000 ALTER TABLE `kurir_bank` DISABLE KEYS */;
/*!40000 ALTER TABLE `kurir_bank` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.kurir_pencairan_fee
DROP TABLE IF EXISTS `kurir_pencairan_fee`;
CREATE TABLE IF NOT EXISTS `kurir_pencairan_fee` (
  `kode_kurir_pencairan_fee` varchar(50) NOT NULL,
  `kode_bank_pusat` smallint(5) unsigned NOT NULL,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `kode_kurir_bank` bigint(20) NOT NULL,
  `kode_kurir` varchar(50) NOT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `status_kurir_pencairan_fee` char(1) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_kurir_pencairan_fee`),
  KEY `kurir_pencairan_fee_FKIndex1` (`kode_kurir`),
  KEY `kurir_pencairan_fee_FKIndex2` (`kode_kurir_bank`),
  KEY `kurir_pencairan_fee_FKIndex3` (`kode_bank_pusat`),
  KEY `kurir_pencairan_fee_FKIndex4` (`kode_pegawai_pshpusat`),
  CONSTRAINT `kurir_pencairan_fee_ibfk_1` FOREIGN KEY (`kode_kurir`) REFERENCES `kurir` (`kode_kurir`),
  CONSTRAINT `kurir_pencairan_fee_ibfk_2` FOREIGN KEY (`kode_kurir_bank`) REFERENCES `kurir_bank` (`kode_kurir_bank`),
  CONSTRAINT `kurir_pencairan_fee_ibfk_3` FOREIGN KEY (`kode_bank_pusat`) REFERENCES `bank_pusat` (`kode_bank_pusat`),
  CONSTRAINT `kurir_pencairan_fee_ibfk_4` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.kurir_pencairan_fee: ~0 rows (approximately)
/*!40000 ALTER TABLE `kurir_pencairan_fee` DISABLE KEYS */;
/*!40000 ALTER TABLE `kurir_pencairan_fee` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.layanan
DROP TABLE IF EXISTS `layanan`;
CREATE TABLE IF NOT EXISTS `layanan` (
  `kode_layanan` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_jenis_layanan` smallint(5) unsigned NOT NULL,
  `kode_satuan_layanan` smallint(5) unsigned NOT NULL,
  `kode_layanan_grup` smallint(6) DEFAULT NULL,
  `nama_layanan` varchar(100) DEFAULT NULL,
  `durasi_layanan` smallint(5) unsigned DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_layanan`),
  KEY `layanan_FKIndex1` (`kode_jenis_layanan`),
  KEY `layanan_FKIndex2` (`kode_satuan_layanan`),
  KEY `fk_layanan_layanan_grup1_idx` (`kode_layanan_grup`),
  CONSTRAINT `fk_layanan_layanan_grup1` FOREIGN KEY (`kode_layanan_grup`) REFERENCES `layanan_grup` (`kode_layanan_grup`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `layanan_ibfk_1` FOREIGN KEY (`kode_jenis_layanan`) REFERENCES `jenis_layanan` (`kode_jenis_layanan`),
  CONSTRAINT `layanan_ibfk_2` FOREIGN KEY (`kode_satuan_layanan`) REFERENCES `satuan_layanan` (`kode_satuan_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.layanan: ~15 rows (approximately)
/*!40000 ALTER TABLE `layanan` DISABLE KEYS */;
INSERT INTO `layanan` (`kode_layanan`, `kode_jenis_layanan`, `kode_satuan_layanan`, `kode_layanan_grup`, `nama_layanan`, `durasi_layanan`, `hapus`) VALUES
	(1, 1, 2, 2, 'Setrika Kemeja', 5, '0'),
	(2, 1, 2, 2, 'Setrika Kaos', 3, '0'),
	(3, 1, 2, 1, 'Cuci Jas Dry Clean', 5, '0'),
	(4, 1, 2, 4, 'Cuci Blazzer', 3, '0'),
	(5, 2, 1, NULL, 'Setrika Reguler 2 Hari', 2, '0'),
	(6, 2, 1, NULL, 'Setrika Reguler 3 Hari', 3, '0'),
	(7, 2, 1, NULL, 'Cuci Basah', 2, '0'),
	(8, 2, 1, NULL, 'Cuci Kering 3 Hari', 3, '0'),
	(9, 3, 3, NULL, 'Setrika Gorden', 2, '0'),
	(10, 3, 3, NULL, 'Setrika Seprei', 3, '0'),
	(11, 1, 4, 4, 'Dry Clean Jacket', 3, '0'),
	(12, 3, 3, NULL, 'Cuci Karpet ', 2, '0'),
	(13, 3, 3, NULL, 'Cuci BedCover 1 Set', 5, '0'),
	(16, 1, 2, 2, 'Setrika Baju Koko', 3, '0'),
	(17, 2, 1, 1, 'Setrika Kiloan 1 hari', 1, '0');
/*!40000 ALTER TABLE `layanan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.layanan_grup
DROP TABLE IF EXISTS `layanan_grup`;
CREATE TABLE IF NOT EXISTS `layanan_grup` (
  `kode_layanan_grup` smallint(6) NOT NULL AUTO_INCREMENT,
  `nama_grup` varchar(30) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_layanan_grup`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.layanan_grup: ~5 rows (approximately)
/*!40000 ALTER TABLE `layanan_grup` DISABLE KEYS */;
INSERT INTO `layanan_grup` (`kode_layanan_grup`, `nama_grup`, `hapus`) VALUES
	(1, 'Bawahan', '0'),
	(2, 'Atasan', '0'),
	(3, 'Jas', '0'),
	(4, 'Blazer', '0'),
	(5, 'Tas', '0');
/*!40000 ALTER TABLE `layanan_grup` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.layanan_harga
DROP TABLE IF EXISTS `layanan_harga`;
CREATE TABLE IF NOT EXISTS `layanan_harga` (
  `kode_harga_layanan` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_layanan` smallint(5) unsigned NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `harga_layanan` double(12,2) DEFAULT NULL,
  `persentase_fee_agen` double(6,2) DEFAULT NULL,
  `persentase_fee_afiliasi` double(6,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_harga_layanan`),
  KEY `layanan_harga_FKIndex1` (`kode_pshcabang`),
  KEY `layanan_harga_FKIndex2` (`kode_layanan`),
  CONSTRAINT `layanan_harga_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`),
  CONSTRAINT `layanan_harga_ibfk_2` FOREIGN KEY (`kode_layanan`) REFERENCES `layanan` (`kode_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.layanan_harga: ~20 rows (approximately)
/*!40000 ALTER TABLE `layanan_harga` DISABLE KEYS */;
INSERT INTO `layanan_harga` (`kode_harga_layanan`, `kode_layanan`, `kode_pshcabang`, `harga_layanan`, `persentase_fee_agen`, `persentase_fee_afiliasi`, `hapus`) VALUES
	(1, 1, 'BDG/CAB/6543', 15000.00, NULL, 0.25, '0'),
	(3, 2, 'BDG/CAB/6543', 4000.00, NULL, 0.25, '0'),
	(4, 3, 'BDG/CAB/6543', 10000.00, NULL, 0.25, '0'),
	(6, 4, 'BDG/CAB/6543', 9000.00, NULL, 0.25, '0'),
	(7, 5, 'BDG/CAB/6543', 12000.00, NULL, 0.25, '0'),
	(8, 1, 'MLG/CAB/20161013101442616542', 1400000.00, 0.04, 0.25, '0'),
	(9, 2, 'MLG/CAB/20161013101442616542', 300000.00, 0.30, 0.25, '0'),
	(11, 3, 'MLG/CAB/20161013101442616542', 900000.00, 0.10, 0.25, '0'),
	(12, 4, 'MLG/CAB/20161013101442616542', 800000.00, 0.40, 0.25, '0'),
	(13, 5, 'MLG/CAB/20161013101442616542', 1100000.00, 0.50, 0.25, '0'),
	(14, 12, 'MLG/CAB/20161013101442616542', 7500000.00, 0.40, 0.25, '0'),
	(15, 11, 'MLG/CAB/20161013101442616542', 200000.00, 0.60, 0.25, '0'),
	(16, 16, 'MLG/CAB/20161013101442616542', 900000.00, 0.60, 0.25, '0'),
	(17, 6, 'MLG/CAB/20161013101442616542', 300000.00, 0.40, 0.25, '0'),
	(18, 7, 'MLG/CAB/20161013101442616542', 400000.00, 0.30, 0.25, '0'),
	(19, 8, 'MLG/CAB/20161013101442616542', 1000000.00, 0.40, 0.25, '0'),
	(20, 17, 'MLG/CAB/20161013101442616542', 500000.00, 0.50, 0.25, '0'),
	(21, 9, 'MLG/CAB/20161013101442616542', 6000000.00, 0.40, 0.25, '0'),
	(22, 10, 'MLG/CAB/20161013101442616542', 2800000.00, 0.50, 0.25, '0'),
	(23, 13, 'MLG/CAB/20161013101442616542', 700000.00, 0.30, 0.25, '0');
/*!40000 ALTER TABLE `layanan_harga` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.master_dealer
DROP TABLE IF EXISTS `master_dealer`;
CREATE TABLE IF NOT EXISTS `master_dealer` (
  `kode_master_dealer` varchar(100) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `jk` char(1) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password_2` varchar(50) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `fee_master_dealer` double(12,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_master_dealer`),
  KEY `master_dealer_FKIndex1` (`kode_pshcabang`),
  CONSTRAINT `master_dealer_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.master_dealer: ~0 rows (approximately)
/*!40000 ALTER TABLE `master_dealer` DISABLE KEYS */;
/*!40000 ALTER TABLE `master_dealer` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.paket_dompet
DROP TABLE IF EXISTS `paket_dompet`;
CREATE TABLE IF NOT EXISTS `paket_dompet` (
  `kode_paket_dompet` smallint(6) NOT NULL AUTO_INCREMENT,
  `nama_paket_dompet` varchar(30) DEFAULT NULL,
  `nominal_paket_dompet` double(12,2) DEFAULT NULL,
  `harga_paket_dompet` double(12,2) DEFAULT NULL,
  `hapus` int(3) NOT NULL,
  PRIMARY KEY (`kode_paket_dompet`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.paket_dompet: ~3 rows (approximately)
/*!40000 ALTER TABLE `paket_dompet` DISABLE KEYS */;
INSERT INTO `paket_dompet` (`kode_paket_dompet`, `nama_paket_dompet`, `nominal_paket_dompet`, `harga_paket_dompet`, `hapus`) VALUES
	(1, 'JASWALLET10', 10000.00, 12000.00, 0),
	(2, 'JASWALLET25', 25000.00, 27000.00, 0),
	(3, 'JASWALLET50', 50000.00, 51000.00, 0);
/*!40000 ALTER TABLE `paket_dompet` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.parfum
DROP TABLE IF EXISTS `parfum`;
CREATE TABLE IF NOT EXISTS `parfum` (
  `kode_parfum` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_parfum` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_parfum`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.parfum: ~5 rows (approximately)
/*!40000 ALTER TABLE `parfum` DISABLE KEYS */;
INSERT INTO `parfum` (`kode_parfum`, `nama_parfum`, `hapus`) VALUES
	(1, 'molto', '0'),
	(2, 'Rosepry Berry', '0'),
	(3, 'Lavender Aqua', '0'),
	(4, 'LimeFresh', '0'),
	(5, 'Calingan', '0');
/*!40000 ALTER TABLE `parfum` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.pegawai_pshcabang
DROP TABLE IF EXISTS `pegawai_pshcabang`;
CREATE TABLE IF NOT EXISTS `pegawai_pshcabang` (
  `kode_pegawai_pshcabang` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) NOT NULL,
  `kode_jabatan` varchar(50) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `jk` char(1) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_pegawai_pshcabang`),
  KEY `pegawai_pshcabang_FKIndex1` (`kode_pshcabang`),
  KEY `pegawai_pshcabang_FKIndex2` (`kode_jabatan`),
  CONSTRAINT `pegawai_pshcabang_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`),
  CONSTRAINT `pegawai_pshcabang_ibfk_2` FOREIGN KEY (`kode_jabatan`) REFERENCES `jabatan` (`kode_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.pegawai_pshcabang: ~4 rows (approximately)
/*!40000 ALTER TABLE `pegawai_pshcabang` DISABLE KEYS */;
INSERT INTO `pegawai_pshcabang` (`kode_pegawai_pshcabang`, `kode_pshcabang`, `kode_jabatan`, `nama`, `alamat`, `notelp`, `jk`, `tanggal_lahir`, `email`, `password`, `foto`, `hapus`) VALUES
	('KRY/CBG/0001', 'MLG/CAB/20161013101442616542', '12', 'Cahya Intan Prameswari', 'Perum Sawojajar 2. Malang', '+62873002123213', 'p', '1993-07-14', 'keuangan@gmail.com', '*5B3204503FA21D2A4C6E0AE232B5B901E77C5D37', '16110265934def.jpg', '0'),
	('KRY/CBG/0002', 'MLG/CAB/20161013101442616542', '11', 'Rifalis Anita Subandrio', 'Perum Sawojajar 1 Malang', '+6248394394392423', 'p', '1993-07-14', 'admin@gmail.com', '*5B3204503FA21D2A4C6E0AE232B5B901E77C5D37', 'http://localhost/setrika_cabang/cabang/server/php/files/16112191954export%20%2814%29.png', '0'),
	('KRY/CBG/0003', 'MLG/CAB/20161013101442616542', '13', 'Meivi Kartika sari', 'Jl', '+62892323232', 'p', '1993-07-14', 'kasir@gmail.com', '*5B3204503FA21D2A4C6E0AE232B5B901E77C5D37', NULL, '0'),
	('KRY/CBG/0004', 'MLG/CAB/20161013101442616542', '14', 'Logistik', 'Jl. Salak Wagir', '+6285755598020', 'l', '1993-07-14', 'logistik@gmail.com', '*5B3204503FA21D2A4C6E0AE232B5B901E77C5D37', 'http://localhost/setrika_cabang/cabang/server/php/files/17032983056minatex.jpg', '0');
/*!40000 ALTER TABLE `pegawai_pshcabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.pegawai_pshpusat
DROP TABLE IF EXISTS `pegawai_pshpusat`;
CREATE TABLE IF NOT EXISTS `pegawai_pshpusat` (
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `kode_jabatan` varchar(50) NOT NULL,
  `kode_pshpusat` varchar(50) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `jk` char(1) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_pegawai_pshpusat`),
  KEY `pegawai_pshpusat_FKIndex1` (`kode_pshpusat`),
  KEY `pegawai_pshpusat_FKIndex2` (`kode_jabatan`),
  CONSTRAINT `pegawai_pshpusat_ibfk_1` FOREIGN KEY (`kode_pshpusat`) REFERENCES `pshpusat` (`kode_pshpusat`),
  CONSTRAINT `pegawai_pshpusat_ibfk_2` FOREIGN KEY (`kode_jabatan`) REFERENCES `jabatan` (`kode_jabatan`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.pegawai_pshpusat: ~3 rows (approximately)
/*!40000 ALTER TABLE `pegawai_pshpusat` DISABLE KEYS */;
INSERT INTO `pegawai_pshpusat` (`kode_pegawai_pshpusat`, `kode_jabatan`, `kode_pshpusat`, `nama`, `alamat`, `notelp`, `jk`, `tanggal_lahir`, `email`, `password`, `foto`, `hapus`) VALUES
	('KRY/PST/0001', '1', '1', 'Hery Thobias Kuswandi', 'Sengon Rejo , Wagir City', '+6285755598020', 'l', '2016-10-11', 'speed.rcm99@gmail.com', '*4EBEA199ACBFB9F734049C83B7CD73D6102F1734', 'http://localhost/setrika/server/php/files/16112394139ice-cream-5.jpg', '0'),
	('KRY/PST/0002', '2', '1', 'Puteri Diana Gutchi', 'Jl. Buduran , Pandaan. N091- 2122', '+622147483647', 'p', '1993-07-14', 'puteri_gutchi@gmail.com', '*4EBEA199ACBFB9F734049C83B7CD73D6102F1734', '16110261952IMG-20121223-00040.jpg', '0'),
	('KRY/PST/0003', '1', '1', 'Iqbal', NULL, '+62547654', 'l', '1993-07-14', 'firmanslash@gmail.com', '*5B3204503FA21D2A4C6E0AE232B5B901E77C5D37', NULL, '0');
/*!40000 ALTER TABLE `pegawai_pshpusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.pendaftaran_agen
DROP TABLE IF EXISTS `pendaftaran_agen`;
CREATE TABLE IF NOT EXISTS `pendaftaran_agen` (
  `kode_pendaftaran_agen` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_pshcabang` varchar(50) DEFAULT NULL,
  `kode_afiliasi` varchar(50) DEFAULT NULL,
  `total_tagihan` double(12,2) DEFAULT NULL,
  `bayar` char(1) DEFAULT '0',
  `tanggal_daftar` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_pendaftaran_agen`),
  KEY `pendaftaran_agen_FKIndex1` (`kode_pshcabang`),
  KEY `pendaftaran_agen_FKIndex2` (`kode_agen`),
  KEY `pendaftaran_agen_FKIndex3` (`kode_afiliasi`),
  CONSTRAINT `pendaftaran_agen_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`),
  CONSTRAINT `pendaftaran_agen_ibfk_2` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `pendaftaran_agen_ibfk_3` FOREIGN KEY (`kode_afiliasi`) REFERENCES `afiliasi` (`kode_afiliasi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.pendaftaran_agen: ~1 rows (approximately)
/*!40000 ALTER TABLE `pendaftaran_agen` DISABLE KEYS */;
INSERT INTO `pendaftaran_agen` (`kode_pendaftaran_agen`, `kode_agen`, `kode_pshcabang`, `kode_afiliasi`, `total_tagihan`, `bayar`, `tanggal_daftar`, `hapus`) VALUES
	('REGA/170410090632673446', 'AGN/KPN/170410090632673431', NULL, 'AFL/MLG/20161101075409279229', 100000.00, '0', '2017-04-10 09:06:32', '0');
/*!40000 ALTER TABLE `pendaftaran_agen` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.provinsi
DROP TABLE IF EXISTS `provinsi`;
CREATE TABLE IF NOT EXISTS `provinsi` (
  `kode_provinsi` char(2) NOT NULL,
  `nama_provinsi` varchar(50) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_provinsi`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.provinsi: ~34 rows (approximately)
/*!40000 ALTER TABLE `provinsi` DISABLE KEYS */;
INSERT INTO `provinsi` (`kode_provinsi`, `nama_provinsi`, `hapus`) VALUES
	('11', 'ACEH', '0'),
	('12', 'SUMATERA UTARA', '0'),
	('13', 'SUMATERA BARAT', '0'),
	('14', 'RIAU', '0'),
	('15', 'JAMBI', '0'),
	('16', 'SUMATERA SELATAN', '0'),
	('17', 'BENGKULU', '0'),
	('18', 'LAMPUNG', '0'),
	('19', 'KEPULAUAN BANGKA BELITUNG', '0'),
	('21', 'KEPULAUAN RIAU', '0'),
	('31', 'DKI JAKARTA', '0'),
	('32', 'JAWA BARAT', '0'),
	('33', 'JAWA TENGAH', '0'),
	('34', 'DI YOGYAKARTA', '0'),
	('35', 'JAWA TIMUR', '0'),
	('36', 'BANTEN', '0'),
	('51', 'BALI', '0'),
	('52', 'NUSA TENGGARA BARAT', '0'),
	('53', 'NUSA TENGGARA TIMUR', '0'),
	('61', 'KALIMANTAN BARAT', '0'),
	('62', 'KALIMANTAN TENGAH', '0'),
	('63', 'KALIMANTAN SELATAN', '0'),
	('64', 'KALIMANTAN TIMUR', '0'),
	('65', 'KALIMANTAN UTARA', '0'),
	('71', 'SULAWESI UTARA', '0'),
	('72', 'SULAWESI TENGAH', '0'),
	('73', 'SULAWESI SELATAN', '0'),
	('74', 'SULAWESI TENGGARA', '0'),
	('75', 'GORONTALO', '0'),
	('76', 'SULAWESI BARAT', '0'),
	('81', 'MALUKU', '0'),
	('82', 'MALUKU UTARA', '0'),
	('91', 'PAPUA', '0'),
	('92', 'PAPUA BARAT', '0');
/*!40000 ALTER TABLE `provinsi` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.pshcabang
DROP TABLE IF EXISTS `pshcabang`;
CREATE TABLE IF NOT EXISTS `pshcabang` (
  `kode_pshcabang` varchar(50) NOT NULL,
  `kode_kota` char(4) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_pshcabang`),
  KEY `pshcabang_FKIndex1` (`kode_kota`),
  CONSTRAINT `pshcabang_ibfk_1` FOREIGN KEY (`kode_kota`) REFERENCES `kota` (`kode_kota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.pshcabang: ~2 rows (approximately)
/*!40000 ALTER TABLE `pshcabang` DISABLE KEYS */;
INSERT INTO `pshcabang` (`kode_pshcabang`, `kode_kota`, `nama`, `alamat`, `notelp`, `longitude`, `latitude`, `hapus`) VALUES
	('BDG/CAB/6543', 'BDG', 'Cabang Bandung', 'Jl. Kali Anget . Buah Bandur, NO. 336', '085755598020', '-232112312110', '02399211223213', '1'),
	('MLG/CAB/20161013101442616542', 'MLG', 'Cabang Malang', 'Malang', '0341', 'longitude', 'latitude', '0');
/*!40000 ALTER TABLE `pshcabang` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.pshpusat
DROP TABLE IF EXISTS `pshpusat`;
CREATE TABLE IF NOT EXISTS `pshpusat` (
  `kode_pshpusat` varchar(50) NOT NULL,
  `kode_kota` char(4) NOT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_pshpusat`),
  KEY `pshpusat_FKIndex1` (`kode_kota`),
  CONSTRAINT `pshpusat_ibfk_1` FOREIGN KEY (`kode_kota`) REFERENCES `kota` (`kode_kota`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.pshpusat: ~1 rows (approximately)
/*!40000 ALTER TABLE `pshpusat` DISABLE KEYS */;
INSERT INTO `pshpusat` (`kode_pshpusat`, `kode_kota`, `nama`, `alamat`, `notelp`, `longitude`, `latitude`, `hapus`) VALUES
	('1', 'BDG', 'PT Golden River', 'Jl. Buah Batu', '085755598020', '1232032013', '31312311', '0');
/*!40000 ALTER TABLE `pshpusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.qr_transaksi
DROP TABLE IF EXISTS `qr_transaksi`;
CREATE TABLE IF NOT EXISTS `qr_transaksi` (
  `kode_qr_transaksi` varchar(50) NOT NULL,
  `kode_agen` varchar(50) NOT NULL,
  `kode_konsumen` varchar(50) NOT NULL,
  `tanggal_pembuatan` datetime DEFAULT NULL,
  `enkripsi` varchar(255) NOT NULL,
  `scan` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_qr_transaksi`),
  KEY `fk_qr_transaksi_agen1_idx` (`kode_agen`),
  KEY `fk_qr_transaksi_konsumen1_idx` (`kode_konsumen`),
  CONSTRAINT `fk_qr_transaksi_agen1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_transaksi_konsumen1` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.qr_transaksi: ~3 rows (approximately)
/*!40000 ALTER TABLE `qr_transaksi` DISABLE KEYS */;
INSERT INTO `qr_transaksi` (`kode_qr_transaksi`, `kode_agen`, `kode_konsumen`, `tanggal_pembuatan`, `enkripsi`, `scan`) VALUES
	('170129065555', 'AGN/KPN/161208131039173921', 'KNS/JKT/20161016075806869469', '2017-01-29 18:55:55', 'eba23e416cf56948a66559752bda99f7', '1'),
	('170129065637', 'AGN/KPN/161208131039173921', 'KNS/JKT/20161016075806869469', '2017-01-29 18:56:37', 'eba23e416cf56948a66559752bda99f7', '1'),
	('170129065722', 'AGN/KPN/161208131039173921', 'KNS/JKT/20161016075806869469', '2017-01-29 18:57:22', 'eba23e416cf56948a66559752bda99f7', '1');
/*!40000 ALTER TABLE `qr_transaksi` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.qr_transaksi_kurir
DROP TABLE IF EXISTS `qr_transaksi_kurir`;
CREATE TABLE IF NOT EXISTS `qr_transaksi_kurir` (
  `kode_qr_transaksi_kurir` varchar(50) NOT NULL,
  `kode_kurir` varchar(50) NOT NULL,
  `kode_konsumen` varchar(50) NOT NULL,
  `tanggal_pembuatan` datetime DEFAULT NULL,
  `enkripsi` varchar(255) DEFAULT NULL,
  `scan` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_qr_transaksi_kurir`),
  KEY `fk_qr_transaksi_kurir11` (`kode_kurir`),
  KEY `fk_qr_transaksi_konsumen12` (`kode_konsumen`),
  CONSTRAINT `fk_qr_transaksi_konsumen12` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_qr_transaksi_kurir11` FOREIGN KEY (`kode_kurir`) REFERENCES `kurir` (`kode_kurir`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.qr_transaksi_kurir: ~14 rows (approximately)
/*!40000 ALTER TABLE `qr_transaksi_kurir` DISABLE KEYS */;
INSERT INTO `qr_transaksi_kurir` (`kode_qr_transaksi_kurir`, `kode_kurir`, `kode_konsumen`, `tanggal_pembuatan`, `enkripsi`, `scan`) VALUES
	('170212113614', 'KUR/MLG/17011231214', 'KNS/JKT/20161016075806869469', '2017-02-12 11:36:14', '4092a719515c51116a34aeded005371d', '0'),
	('170212113632', 'KUR/MLG/17011231214', 'KNS/JKT/20161016075806869469', '2017-02-12 11:36:32', '4092a719515c51116a34aeded005371d', '0'),
	('170212113642', 'KUR/MLG/17011231214', 'KNS/JKT/20161016075806869469', '2017-02-12 11:36:42', '4092a719515c51116a34aeded005371d', '0'),
	('170224073546', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-24 07:35:46', '69c14c70684368a65b107fbd1184437a', '0'),
	('170224073716', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-24 07:37:16', '69c14c70684368a65b107fbd1184437a', '0'),
	('170224073744', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-24 07:37:44', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225020340', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 14:03:40', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225020504', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 14:05:04', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225020652', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 14:06:52', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225021205', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 14:12:05', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225021210', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 14:12:10', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225095503', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 09:55:03', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225103821', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 10:38:21', '69c14c70684368a65b107fbd1184437a', '0'),
	('170225105806', 'KUR/KPN/20179252765245264855', 'KNS/JKT/20161016075806869469', '2017-02-25 10:58:06', '69c14c70684368a65b107fbd1184437a', '0');
/*!40000 ALTER TABLE `qr_transaksi_kurir` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.satuan_layanan
DROP TABLE IF EXISTS `satuan_layanan`;
CREATE TABLE IF NOT EXISTS `satuan_layanan` (
  `kode_satuan_layanan` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `nama_satuan_layanan` varchar(20) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_satuan_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.satuan_layanan: ~6 rows (approximately)
/*!40000 ALTER TABLE `satuan_layanan` DISABLE KEYS */;
INSERT INTO `satuan_layanan` (`kode_satuan_layanan`, `nama_satuan_layanan`, `hapus`) VALUES
	(1, 'KG', '0'),
	(2, 'Setel', '0'),
	(3, 'M2', '0'),
	(4, 'PCS', '0'),
	(5, 'Helai', '0'),
	(6, 'Pasang', '0');
/*!40000 ALTER TABLE `satuan_layanan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.setoran_modal_pusat
DROP TABLE IF EXISTS `setoran_modal_pusat`;
CREATE TABLE IF NOT EXISTS `setoran_modal_pusat` (
  `kode_setoran_modal_pusat` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_master_dealer` varchar(100) NOT NULL,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `kode_akun_keuangan_pusat_kredit` varchar(50) NOT NULL,
  `kode_akun_keuangan_pusat_debet` varchar(50) NOT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_setoran_modal_pusat`),
  KEY `setoran_modal_pusat_FKIndex1` (`kode_master_dealer`),
  KEY `setoran_modal_pusat_FKIndex2` (`kode_akun_keuangan_pusat_debet`),
  KEY `setoran_modal_pusat_FKIndex3` (`kode_akun_keuangan_pusat_kredit`),
  KEY `setoran_modal_pusat_FKIndex4` (`kode_pegawai_pshpusat`),
  CONSTRAINT `setoran_modal_pusat_ibfk_1` FOREIGN KEY (`kode_master_dealer`) REFERENCES `master_dealer` (`kode_master_dealer`),
  CONSTRAINT `setoran_modal_pusat_ibfk_2` FOREIGN KEY (`kode_akun_keuangan_pusat_debet`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `setoran_modal_pusat_ibfk_3` FOREIGN KEY (`kode_akun_keuangan_pusat_kredit`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `setoran_modal_pusat_ibfk_4` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.setoran_modal_pusat: ~0 rows (approximately)
/*!40000 ALTER TABLE `setoran_modal_pusat` DISABLE KEYS */;
/*!40000 ALTER TABLE `setoran_modal_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.stok_opname
DROP TABLE IF EXISTS `stok_opname`;
CREATE TABLE IF NOT EXISTS `stok_opname` (
  `kode_inventory_harga` int(10) unsigned NOT NULL,
  `kode_checker_order_inventory` varchar(50) NOT NULL,
  `kode_beli_inventory` varchar(50) DEFAULT NULL,
  `keluar_stok` double(12,3) DEFAULT NULL,
  `masuk_stok` double(12,3) DEFAULT NULL,
  `sisa_stok` double(12,3) DEFAULT NULL,
  `tanggal_stok_opname` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `stok_opname_FKIndex1` (`kode_inventory_harga`),
  KEY `stok_opname_FKIndex2` (`kode_checker_order_inventory`),
  KEY `stok_opname_FKIndex3` (`kode_beli_inventory`),
  CONSTRAINT `stok_opname_ibfk_1` FOREIGN KEY (`kode_inventory_harga`) REFERENCES `inventory_harga` (`kode_inventory_harga`),
  CONSTRAINT `stok_opname_ibfk_2` FOREIGN KEY (`kode_checker_order_inventory`) REFERENCES `checker_order_inventory` (`kode_checker_order_inventory`),
  CONSTRAINT `stok_opname_ibfk_3` FOREIGN KEY (`kode_beli_inventory`) REFERENCES `beli_inventory` (`kode_beli_inventory`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.stok_opname: ~1 rows (approximately)
/*!40000 ALTER TABLE `stok_opname` DISABLE KEYS */;
INSERT INTO `stok_opname` (`kode_inventory_harga`, `kode_checker_order_inventory`, `kode_beli_inventory`, `keluar_stok`, `masuk_stok`, `sisa_stok`, `tanggal_stok_opname`, `hapus`) VALUES
	(1, 'KOI1704020001', NULL, NULL, NULL, NULL, NULL, '0');
/*!40000 ALTER TABLE `stok_opname` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.surat_beli_inventory
DROP TABLE IF EXISTS `surat_beli_inventory`;
CREATE TABLE IF NOT EXISTS `surat_beli_inventory` (
  `kode_surat_beli_inventory` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshcabang` varchar(50) DEFAULT NULL,
  `tanggal_surat_beli_inventory` datetime DEFAULT NULL,
  `kode_pegawai_pshcabang_spv` varchar(50) DEFAULT NULL,
  `tanggal_konfirmasi_spv` datetime DEFAULT NULL,
  `kode_pegawai_pshcabang_keuangan` varchar(50) DEFAULT NULL,
  `tanggal_konfirmasi_keuangan` datetime DEFAULT NULL,
  `total` double(12,2) DEFAULT NULL,
  `keterangan` text,
  `status_surat_beli_inventory` char(1) DEFAULT NULL COMMENT '0=menunggu,1= konfirmasi spv ,2=konfirmasi keuangan, 3=terbeli',
  `hapus` char(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.surat_beli_inventory: 4 rows
/*!40000 ALTER TABLE `surat_beli_inventory` DISABLE KEYS */;
INSERT INTO `surat_beli_inventory` (`kode_surat_beli_inventory`, `kode_pegawai_pshcabang`, `tanggal_surat_beli_inventory`, `kode_pegawai_pshcabang_spv`, `tanggal_konfirmasi_spv`, `kode_pegawai_pshcabang_keuangan`, `tanggal_konfirmasi_keuangan`, `total`, `keterangan`, `status_surat_beli_inventory`, `hapus`) VALUES
	('SPO1704051207505', 'KRY/CBG/0004', '2017-04-05 00:00:00', 'KRY/CBG/0002', '2017-04-05 13:10:24', NULL, NULL, 65000.00, 'barang sudah habis harus segera beli', '2', NULL),
	('SPO17040512230793', 'KRY/CBG/0004', '2017-04-05 12:23:07', NULL, NULL, NULL, NULL, 103000.00, NULL, '0', NULL),
	('SPO17040512274362', 'KRY/CBG/0004', '2017-04-05 12:27:43', 'KRY/CBG/0002', '2017-04-05 13:12:19', 'KRY/CBG/0003', '2017-04-05 13:27:45', 52500.00, NULL, '3', NULL),
	('SPO17040502083581', 'KRY/CBG/0004', '2017-04-05 14:08:35', NULL, NULL, NULL, NULL, 77500.00, NULL, '0', NULL);
/*!40000 ALTER TABLE `surat_beli_inventory` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.tarif_antar_jemput
DROP TABLE IF EXISTS `tarif_antar_jemput`;
CREATE TABLE IF NOT EXISTS `tarif_antar_jemput` (
  `kode_tarif_antar_jemput` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pshcabang` varchar(50) NOT NULL,
  `tarif_minimal` double(12,2) DEFAULT NULL,
  `jarak_minimal` int(11) DEFAULT NULL,
  `tarif_per_km` double(12,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_tarif_antar_jemput`),
  KEY `tarif_antar_jemput_FKIndex1` (`kode_pshcabang`),
  CONSTRAINT `tarif_antar_jemput_ibfk_1` FOREIGN KEY (`kode_pshcabang`) REFERENCES `pshcabang` (`kode_pshcabang`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.tarif_antar_jemput: ~2 rows (approximately)
/*!40000 ALTER TABLE `tarif_antar_jemput` DISABLE KEYS */;
INSERT INTO `tarif_antar_jemput` (`kode_tarif_antar_jemput`, `kode_pshcabang`, `tarif_minimal`, `jarak_minimal`, `tarif_per_km`, `hapus`) VALUES
	(1, 'MLG/CAB/20161013101442616542', 10000.00, 4, 4000.00, '0'),
	(2, 'BDG/CAB/6543', 13000.00, 4, 5500.00, '0');
/*!40000 ALTER TABLE `tarif_antar_jemput` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE IF NOT EXISTS `transaksi` (
  `kode_transaksi` varchar(50) NOT NULL,
  `kode_konsumen` varchar(50) NOT NULL,
  `kode_agen` varchar(50) DEFAULT NULL,
  `kode_parfum` smallint(5) unsigned DEFAULT NULL,
  `tanggal_terima` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `jenis_bayar` char(1) DEFAULT NULL COMMENT '0: cash; 1: transfer; 2: dompet',
  `subtotal` double(12,2) DEFAULT NULL,
  `diskon` double(12,2) DEFAULT NULL,
  `biaya_antar` double(12,2) DEFAULT NULL,
  `pajak` double(12,2) DEFAULT NULL,
  `total` double(12,2) DEFAULT NULL,
  `bayar` double(12,2) DEFAULT NULL,
  `kembalian` double(12,2) DEFAULT NULL,
  `status_bayar` char(1) DEFAULT NULL COMMENT '0: belum lunas, 1: sudah lunas',
  `jenis_antar` char(1) DEFAULT '0',
  `jenis_jemput` char(1) DEFAULT '0',
  `status_transaksi` char(2) DEFAULT NULL,
  `catatan` varchar(100) DEFAULT NULL,
  `checked` char(1) DEFAULT '0',
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_transaksi`),
  KEY `transaksi_FKIndex1` (`kode_agen`),
  KEY `transaksi_FKIndex2` (`kode_konsumen`),
  KEY `transaksi_FKIndex3` (`kode_parfum`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`),
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`),
  CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`kode_parfum`) REFERENCES `parfum` (`kode_parfum`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi: ~15 rows (approximately)
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` (`kode_transaksi`, `kode_konsumen`, `kode_agen`, `kode_parfum`, `tanggal_terima`, `tanggal_selesai`, `jenis_bayar`, `subtotal`, `diskon`, `biaya_antar`, `pajak`, `total`, `bayar`, `kembalian`, `status_bayar`, `jenis_antar`, `jenis_jemput`, `status_transaksi`, `catatan`, `checked`, `hapus`) VALUES
	('cobatrans3', 'KNS/JKT/20161016075806869469', 'AGN/BDG/161208114539704602', 1, '2016-12-10 12:06:45', '2016-12-10 12:06:46', '0', NULL, NULL, NULL, NULL, 43000.00, NULL, NULL, '0', '1', '1', '5', NULL, '0', '0'),
	('cobatrans4', 'KNS/JKT/20161016075806869469', 'AGN/BDG/161208114539704602', 3, '2016-12-10 15:26:34', '2016-12-10 15:26:35', '0', NULL, 0.00, NULL, NULL, 50000.00, NULL, NULL, '0', '1', NULL, '11', NULL, '0', '0'),
	('INV/KPN/170225171811167154', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-02-25 17:18:11', '2017-02-28 17:18:11', '1', 200000.00, 0.00, 13000.00, 0.00, 213000.00, NULL, NULL, '0', '0', '1', '20', NULL, '0', '0'),
	('INV/KPN/170225173215662979', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-02-25 17:32:15', '2017-02-28 17:32:15', '1', 200000.00, 0.00, 13000.00, 0.00, 213000.00, 250000.00, 37000.00, '1', '0', '1', '3', 'LUNAS', '0', '0'),
	('INV/KPN/170304160906018185', 'KNS/3172/170303193506168718', 'AGN/KPN/161208131039173921', NULL, '2017-03-04 16:09:06', '2017-03-06 16:09:06', '0', 24000.00, 0.00, 0.00, 0.00, 24000.00, 30000.00, 6000.00, '0', '0', '0', '7', NULL, '0', '0'),
	('INV/KPN/170304181719980590', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-03-04 18:17:19', '2017-03-06 18:17:19', '0', 24000.00, 0.00, 0.00, 0.00, 24000.00, 25000.00, 1000.00, '0', '0', '0', '11', NULL, '0', '0'),
	('INV/KPN/170305064055079096', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-03-05 06:40:55', '2017-03-08 06:40:55', '1', 200000.00, 0.00, 13000.00, 0.00, 213000.00, NULL, NULL, '0', '0', '1', '20', NULL, '0', '0'),
	('INV/KPN/170305073034135873', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-03-05 07:30:34', '2017-03-08 07:30:34', '1', 800000.00, 0.00, 73500.00, 0.00, 873500.00, NULL, NULL, '0', '0', '1', '20', NULL, '0', '0'),
	('INV/KPN/170305073942153488', 'KNS/JKT/20161018123856604725', 'AGN/KPN/161208131039173921', NULL, '2017-03-05 07:39:42', '2017-03-08 07:39:42', '0', 200000.00, 0.00, 0.00, 0.00, 200000.00, 200000.00, 0.00, '0', '0', '0', '6', NULL, '0', '0'),
	('INV/KPN/170305074152821999', 'KNS/MLG/170214163317377102', 'AGN/KPN/161208131039173921', NULL, '2017-03-05 07:41:52', '2017-03-07 07:41:52', '0', 24000.00, 0.00, 0.00, 0.00, 24000.00, 25000.00, 1000.00, '0', '0', '0', '5', NULL, '0', '0'),
	('INV/KPN/170306150525839356', 'KNS/MLG/170303193545637965', 'AGN/KPN/161208131039173921', NULL, '2017-03-06 15:05:25', '2017-03-09 15:05:25', '0', 200000.00, 0.00, 0.00, 0.00, 200000.00, 200000.00, 0.00, '0', '0', '0', '5', NULL, '0', '0'),
	('INV/KPN/170314100326587840', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-03-14 10:03:26', '2017-03-16 10:03:26', '2', 12000.00, 0.00, 0.00, 0.00, 12000.00, 12000.00, 0.00, '1', '0', '1', '7', 'LUNAS', '0', '0'),
	('INV/KPN/170404160928935319', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-04-04 16:09:28', '2017-04-06 16:09:28', '0', 12000.00, 0.00, 83950.00, 0.00, 95950.00, NULL, NULL, '0', '0', '1', '1', NULL, '0', '0'),
	('INV/KPN/170406175529232059', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-04-06 17:55:29', '2017-04-08 17:55:29', '1', 75000000.00, 0.00, 83950.00, 0.00, 75083950.00, NULL, NULL, '0', '1', '1', '10', NULL, '0', '0'),
	('INV/KPN/170411160534644235', 'KNS/JKT/20161016075806869469', 'AGN/KPN/161208131039173921', NULL, '2017-04-11 16:05:34', '2017-04-13 16:05:34', '1', 75000.00, 0.00, 83950.00, 0.00, 158950.00, 7600000.00, 7441050.00, '1', '1', '1', '4', 'LUNAS', '0', '0');
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_antar
DROP TABLE IF EXISTS `transaksi_antar`;
CREATE TABLE IF NOT EXISTS `transaksi_antar` (
  `kode_kurir` varchar(50) DEFAULT NULL,
  `kode_konsumen_alamat` bigint(20) DEFAULT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `catatan` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `tanggal_transaksi_antar` datetime DEFAULT NULL,
  `status_transaksi_antar` char(1) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `auto_antar` char(1) DEFAULT '1' COMMENT '0: manual; 1: auto-generated',
  `hapus` char(1) DEFAULT '0',
  KEY `transaksi_antar_FKIndex1` (`kode_transaksi`),
  KEY `transaksi_antar_FKIndex2` (`kode_konsumen_alamat`),
  KEY `transaksi_antar_FKIndex3` (`kode_kurir`),
  CONSTRAINT `transaksi_antar_ibfk_1` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_antar_ibfk_2` FOREIGN KEY (`kode_konsumen_alamat`) REFERENCES `konsumen_alamat` (`kode_konsumen_alamat`),
  CONSTRAINT `transaksi_antar_ibfk_3` FOREIGN KEY (`kode_kurir`) REFERENCES `kurir` (`kode_kurir`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_antar: ~8 rows (approximately)
/*!40000 ALTER TABLE `transaksi_antar` DISABLE KEYS */;
INSERT INTO `transaksi_antar` (`kode_kurir`, `kode_konsumen_alamat`, `kode_transaksi`, `catatan`, `notelp`, `tanggal_transaksi_antar`, `status_transaksi_antar`, `latitude`, `longitude`, `auto_antar`, `hapus`) VALUES
	('KUR/MLG/17011231214', 27, 'cobatrans3', 'Anterin Ya', NULL, '2016-12-20 14:03:17', '0', NULL, NULL, '0', '0'),
	(NULL, 30, 'cobatrans4', 'Tolong antar, saya buru-buru', '08098999', '2016-12-10 15:26:35', '0', '-7.945122', '112.582053', '0', '0'),
	(NULL, 27, 'INV/KPN/170305064055079096', NULL, '08098999', NULL, '0', NULL, NULL, '0', '0'),
	(NULL, 27, 'INV/KPN/170305073034135873', NULL, '08098999', NULL, '0', NULL, NULL, '0', '0'),
	(NULL, NULL, 'INV/KPN/170305074152821999', NULL, NULL, NULL, '0', NULL, NULL, '0', '0'),
	(NULL, NULL, 'INV/KPN/170404160928935319', NULL, '08098999', NULL, '0', NULL, NULL, '1', '0'),
	('KUR/MLG/17011231214', 30, 'INV/KPN/170406175529232059', 'Tolong antar, saya buru-buru', '085790697366', '2017-02-13 16:50:00', '0', '-7.945122', '112.582053', '0', '0'),
	('KUR/KPN/20179252765245264855', NULL, 'INV/KPN/170411160534644235', NULL, '085790697366', NULL, '0', NULL, NULL, '1', '0');
/*!40000 ALTER TABLE `transaksi_antar` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_bayar_transfer
DROP TABLE IF EXISTS `transaksi_bayar_transfer`;
CREATE TABLE IF NOT EXISTS `transaksi_bayar_transfer` (
  `kode_transaksi` varchar(50) NOT NULL,
  `kode_konsumen_bank` bigint(20) NOT NULL,
  `kode_bank_pusat` smallint(5) unsigned NOT NULL,
  `kode_pegawai_pshpusat` varchar(50) DEFAULT NULL,
  `tanggal_transaksi_bayar_transfer` datetime DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `status_transaksi_bayar_transfer` char(1) DEFAULT NULL COMMENT '0: belum di konfirmasi, 1: terkonfirmasi oleh jastrik, 2: dibatalkan',
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `transaksi_bayar_transfer_FKIndex1` (`kode_transaksi`),
  KEY `transaksi_bayar_transfer_FKIndex2` (`kode_konsumen_bank`),
  KEY `transaksi_bayar_transfer_FKIndex3` (`kode_bank_pusat`),
  KEY `transaksi_bayar_transfer_FKIndex4` (`kode_pegawai_pshpusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_bayar_transfer: ~1 rows (approximately)
/*!40000 ALTER TABLE `transaksi_bayar_transfer` DISABLE KEYS */;
INSERT INTO `transaksi_bayar_transfer` (`kode_transaksi`, `kode_konsumen_bank`, `kode_bank_pusat`, `kode_pegawai_pshpusat`, `tanggal_transaksi_bayar_transfer`, `foto`, `status_transaksi_bayar_transfer`, `keterangan`, `hapus`) VALUES
	('INV/KPN/170225173215662979', 4, 2, NULL, '2017-03-21 15:07:16', 'http://dev.citridia.com/ws.jastrik/manifest/konfirmasibtt/INVKPN170225173215662979.jpg', '2', 'Waiting for Confirmation from Jastrik', '0');
/*!40000 ALTER TABLE `transaksi_bayar_transfer` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_ditolak
DROP TABLE IF EXISTS `transaksi_ditolak`;
CREATE TABLE IF NOT EXISTS `transaksi_ditolak` (
  `kode_transaksi` varchar(50) NOT NULL,
  `tanggal_transaksi_ditolak` datetime DEFAULT NULL,
  `alasan` varchar(100) DEFAULT NULL,
  `jenis_transaksi_ditolak` char(1) DEFAULT NULL,
  `ref` varchar(20) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_transaksi`),
  KEY `transaksi_ditolak_FKIndex1` (`kode_transaksi`),
  CONSTRAINT `transaksi_ditolak_ibfk_1` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_ditolak: ~3 rows (approximately)
/*!40000 ALTER TABLE `transaksi_ditolak` DISABLE KEYS */;
INSERT INTO `transaksi_ditolak` (`kode_transaksi`, `tanggal_transaksi_ditolak`, `alasan`, `jenis_transaksi_ditolak`, `ref`, `hapus`) VALUES
	('INV/KPN/170225171811167154', '2017-02-25 17:23:15', 'AGEN TIDAK MERESPON', '0', 'ref', '0'),
	('INV/KPN/170305064055079096', '2017-03-05 06:42:25', 'DITOLAK AGEN PERTAMA', '0', 'ref', '0'),
	('INV/KPN/170305073034135873', '2017-03-05 07:35:36', 'AGEN TIDAK MERESPON', '0', 'ref', '0');
/*!40000 ALTER TABLE `transaksi_ditolak` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_item
DROP TABLE IF EXISTS `transaksi_item`;
CREATE TABLE IF NOT EXISTS `transaksi_item` (
  `kode_transaksi_item` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi_layanan` bigint(20) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `kode_item` smallint(5) unsigned NOT NULL,
  `jumlah` smallint(6) DEFAULT '0',
  `hapus` char(1) DEFAULT '0',
  PRIMARY KEY (`kode_transaksi_item`),
  KEY `transaksi_item_FKIndex1` (`kode_item`),
  KEY `transaksi_item_FKIndex2` (`kode_transaksi`),
  KEY `transaksi_item_FKIndex3` (`kode_transaksi_layanan`),
  CONSTRAINT `transaksi_item_ibfk_1` FOREIGN KEY (`kode_item`) REFERENCES `item` (`kode_item`),
  CONSTRAINT `transaksi_item_ibfk_2` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_item_ibfk_3` FOREIGN KEY (`kode_transaksi_layanan`) REFERENCES `transaksi_layanan` (`kode_transaksi_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_item: ~17 rows (approximately)
/*!40000 ALTER TABLE `transaksi_item` DISABLE KEYS */;
INSERT INTO `transaksi_item` (`kode_transaksi_item`, `kode_transaksi_layanan`, `kode_transaksi`, `kode_item`, `jumlah`, `hapus`) VALUES
	(1, 1, 'cobatrans3', 1, 0, '0'),
	(2, 1, 'cobatrans3', 2, 0, '0'),
	(3, 2, 'cobatrans3', 2, 0, '0'),
	(69, 275, 'INV/KPN/170304160906018185', 6, 10, '0'),
	(70, 276, 'INV/KPN/170304181719980590', 6, 10, '0'),
	(71, 280, 'INV/KPN/170305074152821999', 6, 5, '0'),
	(72, 282, 'INV/KPN/170314100326587840', 3, 5, '0'),
	(73, 282, 'INV/KPN/170314100326587840', 2, 1, '0'),
	(74, 282, 'INV/KPN/170314100326587840', 5, 1, '0'),
	(75, 282, 'INV/KPN/170314100326587840', 7, 1, '0'),
	(76, 282, 'INV/KPN/170314100326587840', 8, 1, '0'),
	(77, 282, 'INV/KPN/170314100326587840', 9, 1, '0'),
	(78, 282, 'INV/KPN/170314100326587840', 2, 1, '0'),
	(79, 282, 'INV/KPN/170314100326587840', 5, 1, '0'),
	(80, 282, 'INV/KPN/170314100326587840', 7, 1, '0'),
	(81, 282, 'INV/KPN/170314100326587840', 8, 1, '0'),
	(82, 282, 'INV/KPN/170314100326587840', 9, 1, '0');
/*!40000 ALTER TABLE `transaksi_item` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_jemput
DROP TABLE IF EXISTS `transaksi_jemput`;
CREATE TABLE IF NOT EXISTS `transaksi_jemput` (
  `kode_kurir` varchar(50) DEFAULT NULL,
  `kode_konsumen_alamat` bigint(20) NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `catatan` varchar(100) DEFAULT NULL,
  `notelp` varchar(20) DEFAULT NULL,
  `tanggal_transaksi_jemput` datetime DEFAULT NULL,
  `status_transaksi_jemput` char(1) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `transaksi_jemput_FKIndex1` (`kode_transaksi`),
  KEY `transaksi_jemput_FKIndex2` (`kode_konsumen_alamat`),
  KEY `transaksi_jemput_FKIndex3` (`kode_kurir`),
  CONSTRAINT `transaksi_jemput_ibfk_1` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_jemput_ibfk_2` FOREIGN KEY (`kode_konsumen_alamat`) REFERENCES `konsumen_alamat` (`kode_konsumen_alamat`),
  CONSTRAINT `transaksi_jemput_ibfk_3` FOREIGN KEY (`kode_kurir`) REFERENCES `kurir` (`kode_kurir`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_jemput: ~9 rows (approximately)
/*!40000 ALTER TABLE `transaksi_jemput` DISABLE KEYS */;
INSERT INTO `transaksi_jemput` (`kode_kurir`, `kode_konsumen_alamat`, `kode_transaksi`, `catatan`, `notelp`, `tanggal_transaksi_jemput`, `status_transaksi_jemput`, `latitude`, `longitude`, `hapus`) VALUES
	(NULL, 27, 'cobatrans3', 'Jemput Ya', NULL, '2016-12-20 14:03:43', '0', NULL, NULL, '0'),
	('KUR/KPN/20179252765245264855', 27, 'INV/KPN/170225171811167154', '', '08098999', '2017-02-25 18:12:00', '0', '-7.89412334998', '112.663407549', '1'),
	('KUR/KPN/20179252765245264855', 27, 'INV/KPN/170225173215662979', '', '08098999', '2017-02-25 18:31:00', '0', '-7.8942053784', '112.663334459', '0'),
	(NULL, 27, 'INV/KPN/170305064055079096', '', '08098999', '2017-03-05 07:39:00', '0', '-7.89409611787', '112.663397156', '0'),
	(NULL, 27, 'INV/KPN/170305073034135873', '', '08098999', '2017-03-05 08:29:00', '0', '-7.89401741041', '112.663812898', '1'),
	('KUR/KPN/20179252765245264855', 30, 'INV/KPN/170314100326587840', '', '08098999', '2017-03-14 12:00:00', '0', '-7.94551278439', '112.582561746', '0'),
	('KUR/KPN/20179252765245264855', 30, 'INV/KPN/170404160928935319', '', '08098999', '2017-04-04 19:08:00', '0', '-7.945389259', '112.582789063', '0'),
	('KUR/KPN/20179252765245264855', 30, 'INV/KPN/170406175529232059', 'jemput ya', '085790697366', '2017-02-09 16:50:00', '0', '-7.94532351159', '112.582620084', '0'),
	('KUR/MLG/17011231214', 30, 'INV/KPN/170411160534644235', 'jemput ya', '085790697366', '2017-04-11 16:50:00', '0', '-7.94532351159', '112.582620084', '0');
/*!40000 ALTER TABLE `transaksi_jemput` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_layanan
DROP TABLE IF EXISTS `transaksi_layanan`;
CREATE TABLE IF NOT EXISTS `transaksi_layanan` (
  `kode_transaksi_layanan` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_harga_layanan` int(10) unsigned NOT NULL,
  `kode_transaksi` varchar(50) NOT NULL,
  `jumlah` double(12,3) DEFAULT '0.000',
  `jumlah_helai` smallint(6) DEFAULT '0',
  `panjang` double(12,3) DEFAULT '0.000',
  `lebar` double(12,3) DEFAULT '0.000',
  `harga` double(12,2) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_transaksi_layanan`),
  KEY `transaksi_layanan_FKIndex1` (`kode_transaksi`),
  KEY `transaksi_layanan_FKIndex2` (`kode_harga_layanan`),
  CONSTRAINT `transaksi_layanan_ibfk_1` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_layanan_ibfk_2` FOREIGN KEY (`kode_harga_layanan`) REFERENCES `layanan_harga` (`kode_harga_layanan`)
) ENGINE=InnoDB AUTO_INCREMENT=291 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_layanan: ~15 rows (approximately)
/*!40000 ALTER TABLE `transaksi_layanan` DISABLE KEYS */;
INSERT INTO `transaksi_layanan` (`kode_transaksi_layanan`, `kode_harga_layanan`, `kode_transaksi`, `jumlah`, `jumlah_helai`, `panjang`, `lebar`, `harga`, `hapus`) VALUES
	(1, 8, 'cobatrans3', 1.500, 0, 0.000, 0.000, 14000.00, '0'),
	(2, 11, 'cobatrans3', 2.000, 0, 0.000, 0.000, 14000.00, '0'),
	(271, 15, 'INV/KPN/170225171811167154', 0.000, 1, 0.000, 0.000, 200000.00, '1'),
	(274, 15, 'INV/KPN/170225173215662979', 0.000, 1, 0.000, 0.000, 200000.00, '0'),
	(275, 7, 'INV/KPN/170304160906018185', 2.000, 10, 0.000, 0.000, 24000.00, '0'),
	(276, 7, 'INV/KPN/170304181719980590', 2.000, 10, 0.000, 0.000, 24000.00, '0'),
	(277, 15, 'INV/KPN/170305064055079096', 0.000, 1, 0.000, 0.000, 200000.00, '0'),
	(278, 12, 'INV/KPN/170305073034135873', 0.000, 1, 0.000, 0.000, 800000.00, '1'),
	(279, 15, 'INV/KPN/170305073942153488', 0.000, 1, 0.000, 0.000, 200000.00, '0'),
	(280, 7, 'INV/KPN/170305074152821999', 2.000, 5, 0.000, 0.000, 24000.00, '0'),
	(281, 15, 'INV/KPN/170306150525839356', 0.000, 1, 0.000, 0.000, 200000.00, '0'),
	(282, 7, 'INV/KPN/170314100326587840', 1.000, 5, 0.000, 0.000, 12000.00, '0'),
	(285, 7, 'INV/KPN/170404160928935319', 1.000, 5, 0.000, 0.000, 12000.00, '0'),
	(288, 14, 'INV/KPN/170406175529232059', 0.000, 0, 20.000, 50.000, 75000000.00, '0'),
	(290, 14, 'INV/KPN/170411160534644235', 0.000, 0, 1.000, 1.000, 75000.00, '0');
/*!40000 ALTER TABLE `transaksi_layanan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_pengaduan
DROP TABLE IF EXISTS `transaksi_pengaduan`;
CREATE TABLE IF NOT EXISTS `transaksi_pengaduan` (
  `kode_transaksi_pengaduan` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) NOT NULL,
  `isi_transaksi_pengaduan` varchar(100) DEFAULT NULL,
  `tanggal_transaksi_pengaduan` datetime DEFAULT NULL,
  `status_transaksi_pengaduan` char(1) DEFAULT '0',
  `hapus` char(1) DEFAULT '0',
  PRIMARY KEY (`kode_transaksi_pengaduan`),
  KEY `fk_transaksi_pengaduan_to_transaksi1` (`kode_transaksi`),
  CONSTRAINT `fk_transaksi_pengaduan_to_transaksi1` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_pengaduan: ~2 rows (approximately)
/*!40000 ALTER TABLE `transaksi_pengaduan` DISABLE KEYS */;
INSERT INTO `transaksi_pengaduan` (`kode_transaksi_pengaduan`, `kode_transaksi`, `isi_transaksi_pengaduan`, `tanggal_transaksi_pengaduan`, `status_transaksi_pengaduan`, `hapus`) VALUES
	(3, 'cobatrans4', 'sampah', '2017-03-05 22:43:18', '0', '0'),
	(4, 'INV/KPN/170304181719980590', 'Ini Pengaduannya Wenak', '2017-03-26 08:21:29', '1', '0');
/*!40000 ALTER TABLE `transaksi_pengaduan` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_pengaduan_balas
DROP TABLE IF EXISTS `transaksi_pengaduan_balas`;
CREATE TABLE IF NOT EXISTS `transaksi_pengaduan_balas` (
  `kode_transaksi_pengaduan_balas` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_agen` varchar(50) DEFAULT NULL,
  `kode_konsumen` varchar(50) DEFAULT NULL,
  `kode_checker` varchar(50) DEFAULT NULL,
  `kode_transaksi_pengaduan` bigint(20) unsigned DEFAULT NULL,
  `isi_transaksi_pengaduan_balas` varchar(100) DEFAULT NULL,
  `waktu_transaksi_pengaduan_balas` datetime DEFAULT NULL,
  `hapus` char(1) DEFAULT '0',
  PRIMARY KEY (`kode_transaksi_pengaduan_balas`),
  KEY `fk_transaksi_pengaduan_balas_to_agen1` (`kode_agen`),
  KEY `fk_transaksi_pengaduan_balas_to_konsumen1` (`kode_konsumen`),
  KEY `fk_transaksi_pengaduan_balas_to_transaksi_pengaduan` (`kode_transaksi_pengaduan`),
  KEY `fk_transaksi_pengaduan_balas_to_checker1` (`kode_checker`),
  CONSTRAINT `fk_transaksi_pengaduan_balas_to_agen1` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transaksi_pengaduan_balas_to_checker1` FOREIGN KEY (`kode_checker`) REFERENCES `checker` (`kode_checker`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transaksi_pengaduan_balas_to_konsumen1` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_transaksi_pengaduan_balas_to_transaksi_pengaduan` FOREIGN KEY (`kode_transaksi_pengaduan`) REFERENCES `transaksi_pengaduan` (`kode_transaksi_pengaduan`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_pengaduan_balas: ~12 rows (approximately)
/*!40000 ALTER TABLE `transaksi_pengaduan_balas` DISABLE KEYS */;
INSERT INTO `transaksi_pengaduan_balas` (`kode_transaksi_pengaduan_balas`, `kode_agen`, `kode_konsumen`, `kode_checker`, `kode_transaksi_pengaduan`, `isi_transaksi_pengaduan_balas`, `waktu_transaksi_pengaduan_balas`, `hapus`) VALUES
	(1, 'AGN/KPN/161208131039173921', NULL, NULL, 4, 'Balasan pengaduan wenak', '2017-03-26 08:27:42', '1'),
	(2, NULL, 'KNS/JKT/20161016075806869469', NULL, 4, 'Dibalas lagi sama konsumen', '2017-03-26 08:28:11', '0'),
	(3, NULL, NULL, 'CHK/MLG/20161018150202269169', 4, 'Yooo yeezy gua siap', '2017-03-28 12:38:12', '0'),
	(4, NULL, NULL, 'CHK/MLG/20161018150202269169', 4, 'Yooo yeezy gua siap', '2017-03-28 12:40:31', '1'),
	(5, NULL, NULL, 'CHK/MLG/20161018150202269169', 4, 'Yooo yeezy gua siap', '2017-03-28 12:44:50', '1'),
	(6, 'AGN/KPN/161208131039173921', NULL, NULL, 4, 'ampun saya ga salah apa apa', '2017-03-28 19:26:34', '1'),
	(7, 'AGN/KPN/161208131039173921', NULL, NULL, 4, 'tes', '2017-03-28 19:41:32', '1'),
	(8, 'AGN/KPN/161208131039173921', NULL, NULL, 4, 'hehe', '2017-03-28 20:34:34', '0'),
	(9, NULL, 'KNS/JKT/20161016075806869469', NULL, 4, 'ih', '2017-03-28 20:36:40', '0'),
	(10, 'AGN/KPN/161208131039173921', NULL, NULL, 4, 'sek tak salto', '2017-03-30 11:29:34', '0'),
	(11, NULL, NULL, 'CHK/MLG/20161018150202269169', 4, 'aku kebelet ngising e', '2017-03-30 14:14:10', '0'),
	(12, NULL, 'KNS/JKT/20161016075806869469', NULL, 3, 'kintil', '2017-04-09 22:39:56', '0');
/*!40000 ALTER TABLE `transaksi_pengaduan_balas` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_review
DROP TABLE IF EXISTS `transaksi_review`;
CREATE TABLE IF NOT EXISTS `transaksi_review` (
  `kode_transaksi_review` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(50) NOT NULL,
  `rating_rapi` smallint(5) unsigned DEFAULT NULL,
  `rating_cepat` smallint(5) unsigned DEFAULT NULL,
  `isi_transaksi_review` varchar(100) DEFAULT NULL,
  `tanggal_transaksi_review` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_transaksi_review`),
  KEY `transaksi_review_FKIndex1` (`kode_transaksi`),
  CONSTRAINT `transaksi_review_ibfk_1` FOREIGN KEY (`kode_transaksi`) REFERENCES `transaksi` (`kode_transaksi`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_review: ~6 rows (approximately)
/*!40000 ALTER TABLE `transaksi_review` DISABLE KEYS */;
INSERT INTO `transaksi_review` (`kode_transaksi_review`, `kode_transaksi`, `rating_rapi`, `rating_cepat`, `isi_transaksi_review`, `tanggal_transaksi_review`, `hapus`) VALUES
	(10, 'cobatrans3', 3, 3, 'no sardines for today', '2016-12-10 16:09:33', '0'),
	(11, 'cobatrans4', 4, 3, 'cihuy', '2017-01-24 16:50:54', '0'),
	(12, 'cobatrans3', 4, 4, 'Yeaaaaah', '2017-01-31 16:56:39', '0'),
	(13, 'cobatrans3', 4, 4, 'Yeaaaaah', '2017-01-31 16:57:28', '0'),
	(14, 'cobatrans3', 4, 4, 'Yeaaaaah', '2017-01-31 17:25:54', '0'),
	(15, 'INV/KPN/170304181719980590', 5, 5, 'salto salto', '2017-03-29 08:12:15', '0');
/*!40000 ALTER TABLE `transaksi_review` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transaksi_review_balas
DROP TABLE IF EXISTS `transaksi_review_balas`;
CREATE TABLE IF NOT EXISTS `transaksi_review_balas` (
  `kode_agen` varchar(50) DEFAULT NULL,
  `kode_konsumen` varchar(50) DEFAULT NULL,
  `kode_transaksi_review` bigint(20) NOT NULL,
  `isi_transaksi_review_balas` varchar(100) DEFAULT NULL,
  `waktu_transaksi_review_balas` datetime DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  KEY `transaksi_review_balas_FKIndex1` (`kode_transaksi_review`),
  KEY `transaksi_review_balas_FKIndex2` (`kode_konsumen`),
  KEY `transaksi_review_balas_FKIndex3` (`kode_agen`),
  CONSTRAINT `transaksi_review_balas_ibfk_1` FOREIGN KEY (`kode_transaksi_review`) REFERENCES `transaksi_review` (`kode_transaksi_review`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `transaksi_review_balas_ibfk_2` FOREIGN KEY (`kode_konsumen`) REFERENCES `konsumen` (`kode_konsumen`),
  CONSTRAINT `transaksi_review_balas_ibfk_3` FOREIGN KEY (`kode_agen`) REFERENCES `agen` (`kode_agen`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transaksi_review_balas: ~4 rows (approximately)
/*!40000 ALTER TABLE `transaksi_review_balas` DISABLE KEYS */;
INSERT INTO `transaksi_review_balas` (`kode_agen`, `kode_konsumen`, `kode_transaksi_review`, `isi_transaksi_review_balas`, `waktu_transaksi_review_balas`, `hapus`) VALUES
	(NULL, 'KNS/JKT/20161016075806869469', 11, 'sd', '2017-01-24 22:14:47', '0'),
	(NULL, 'KNS/JKT/20161016075806869469', 11, 'sd', '2017-01-24 22:14:49', '1'),
	(NULL, 'KNS/JKT/20161016075806869469', 10, 'uh', '2017-03-04 00:02:30', '1'),
	(NULL, 'KNS/JKT/20161016075806869469', 10, 'eh', '2017-03-04 00:08:43', '1');
/*!40000 ALTER TABLE `transaksi_review_balas` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transfer_keuangan_pusat
DROP TABLE IF EXISTS `transfer_keuangan_pusat`;
CREATE TABLE IF NOT EXISTS `transfer_keuangan_pusat` (
  `kode_transfer_keuangan_pusat` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kode_pegawai_pshpusat` varchar(50) NOT NULL,
  `kode_akun_keuangan_pusat_debet` varchar(50) NOT NULL,
  `kode_akun_keuangan_pusat_kredit` varchar(50) NOT NULL,
  `tanggal_transfer` datetime DEFAULT NULL,
  `tanggal_terima` datetime DEFAULT NULL,
  `status_transfer_keuangan_pusat` char(1) DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `keterangan` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_transfer_keuangan_pusat`),
  KEY `transfer_keuangan_pusat_FKIndex1` (`kode_pegawai_pshpusat`),
  KEY `transfer_keuangan_pusat_FKIndex2` (`kode_akun_keuangan_pusat_debet`),
  KEY `transfer_keuangan_pusat_FKIndex3` (`kode_akun_keuangan_pusat_kredit`),
  CONSTRAINT `transfer_keuangan_pusat_ibfk_1` FOREIGN KEY (`kode_pegawai_pshpusat`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`),
  CONSTRAINT `transfer_keuangan_pusat_ibfk_2` FOREIGN KEY (`kode_akun_keuangan_pusat_debet`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `transfer_keuangan_pusat_ibfk_3` FOREIGN KEY (`kode_akun_keuangan_pusat_kredit`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transfer_keuangan_pusat: ~0 rows (approximately)
/*!40000 ALTER TABLE `transfer_keuangan_pusat` DISABLE KEYS */;
/*!40000 ALTER TABLE `transfer_keuangan_pusat` ENABLE KEYS */;

-- Dumping structure for table citridia_setrika.transfer_keuangan_puscab
DROP TABLE IF EXISTS `transfer_keuangan_puscab`;
CREATE TABLE IF NOT EXISTS `transfer_keuangan_puscab` (
  `kode_transfer_keuangan_puscab` bigint(20) NOT NULL AUTO_INCREMENT,
  `kode_akun_keuangan_pusat_debit` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_pusat_kredit` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshpusat_pengirim` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshpusat_penerima` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_cabang_kredit` varchar(50) DEFAULT NULL,
  `kode_akun_keuangan_cabang_debit` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshcabang_penerima` varchar(50) DEFAULT NULL,
  `kode_pegawai_pshcabang_pengirim` varchar(50) DEFAULT NULL,
  `nominal` double(12,2) DEFAULT NULL,
  `jenis_transfer` char(1) DEFAULT NULL,
  `tanggal_kirim` datetime DEFAULT NULL,
  `tanggal_terima` datetime DEFAULT NULL,
  `foto` varchar(100) DEFAULT NULL,
  `status_transfer_keuangan_puscab` char(1) DEFAULT NULL,
  `keterangan_kirim` varchar(100) DEFAULT NULL,
  `keterangan_terima` varchar(100) DEFAULT NULL,
  `hapus` char(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`kode_transfer_keuangan_puscab`),
  KEY `transfer_keuangan_puscab_FKIndex1` (`kode_pegawai_pshcabang_pengirim`),
  KEY `transfer_keuangan_puscab_FKIndex2` (`kode_pegawai_pshcabang_penerima`),
  KEY `transfer_keuangan_puscab_FKIndex3` (`kode_pegawai_pshpusat_penerima`),
  KEY `transfer_keuangan_puscab_FKIndex4` (`kode_pegawai_pshpusat_pengirim`),
  KEY `transfer_keuangan_puscab_FKIndex5` (`kode_akun_keuangan_cabang_debit`),
  KEY `transfer_keuangan_puscab_FKIndex6` (`kode_akun_keuangan_cabang_kredit`),
  KEY `transfer_keuangan_puscab_FKIndex7` (`kode_akun_keuangan_pusat_kredit`),
  KEY `transfer_keuangan_puscab_FKIndex8` (`kode_akun_keuangan_pusat_debit`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_1` FOREIGN KEY (`kode_pegawai_pshcabang_pengirim`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_2` FOREIGN KEY (`kode_pegawai_pshcabang_penerima`) REFERENCES `pegawai_pshcabang` (`kode_pegawai_pshcabang`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_3` FOREIGN KEY (`kode_pegawai_pshpusat_penerima`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_4` FOREIGN KEY (`kode_pegawai_pshpusat_pengirim`) REFERENCES `pegawai_pshpusat` (`kode_pegawai_pshpusat`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_5` FOREIGN KEY (`kode_akun_keuangan_cabang_debit`) REFERENCES `akun_keuangan_cabang` (`kode_akun_keuangan_cabang`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_6` FOREIGN KEY (`kode_akun_keuangan_cabang_kredit`) REFERENCES `akun_keuangan_cabang` (`kode_akun_keuangan_cabang`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_7` FOREIGN KEY (`kode_akun_keuangan_pusat_kredit`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`),
  CONSTRAINT `transfer_keuangan_puscab_ibfk_8` FOREIGN KEY (`kode_akun_keuangan_pusat_debit`) REFERENCES `akun_keuangan_pusat` (`kode_akun_keuangan_pusat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table citridia_setrika.transfer_keuangan_puscab: ~0 rows (approximately)
/*!40000 ALTER TABLE `transfer_keuangan_puscab` DISABLE KEYS */;
/*!40000 ALTER TABLE `transfer_keuangan_puscab` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
