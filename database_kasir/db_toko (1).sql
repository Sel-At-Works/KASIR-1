-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Jul 2025 pada 11.30
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_toko`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `email`, `username`, `password`, `image`) VALUES
(8, 'marsel herlino@gmail.com', 'marsel', '123', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE `barang` (
  `id` int(11) NOT NULL,
  `id_barang` varchar(255) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `gambar_barcode` varchar(255) DEFAULT NULL,
  `id_kategori` int(11) NOT NULL,
  `nama_barang` text NOT NULL,
  `merk` varchar(255) NOT NULL,
  `harga_beli` varchar(255) NOT NULL,
  `harga_jual` varchar(255) NOT NULL,
  `satuan_barang` varchar(255) NOT NULL,
  `stok` text NOT NULL,
  `tgl_input` varchar(255) NOT NULL,
  `tgl_update` varchar(255) DEFAULT NULL,
  `gambar` varchar(250) NOT NULL,
  `deskripsi` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`id`, `id_barang`, `barcode`, `gambar_barcode`, `id_kategori`, `nama_barang`, `merk`, `harga_beli`, `harga_jual`, `satuan_barang`, `stok`, `tgl_input`, `tgl_update`, `gambar`, `deskripsi`) VALUES
(27, 'BR002', 'BRG1747750148810', NULL, 1, 'jdjjkdjd', 'dnnmdd', '120000', '10000', 'PCS', '948', '20 May 2025, 21:08', NULL, 'Product (1).png', 'sshshhjsjs'),
(28, 'BR003', 'BRG1747750822374', NULL, 1, 'ggs', 'louis', '12000', '12000', 'PCS', '0', '20 May 2025, 21:20', NULL, 'Product (1).png', '135553'),
(29, 'BR004', 'BRG1747750869648', NULL, 1, 'jsjnssj', 'snjnsm', '12000', '12000', 'PCS', '0', '20 May 2025, 21:21', NULL, 'Product2.png', 'ahajaajha'),
(30, 'BR005', 'BRG1747750910656', NULL, 1, 'sjksjss', 'slkls', '10000', '10000', 'PCS', '0', '20 May 2025, 21:21', NULL, 'Desain tanpa judul.png', 'sjjs'),
(31, 'BR006', 'BRG1747750952102', NULL, 1, 'zmmzmz', 'zmmz', '1000', '1000', 'PCS', '19999', '20 May 2025, 21:22', '2 June 2025, 8:43', 'Product3.png', 'skks'),
(32, 'BR007', 'BRG1747750989523', NULL, 1, 'ksklsks', 'sjkjs', '10000', '1000', 'PCS', '1995', '20 May 2025, 21:23', '21 May 2025, 8:59', 'Product3.png', 'Jjaja');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `tgl_input` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `tgl_input`) VALUES
(1, 'gaga', '7 May 2017, 10:23'),
(15, 'pulpen', '20 May 2025, 20:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `login`
--

CREATE TABLE `login` (
  `id_login` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `pass` char(32) NOT NULL,
  `id_member` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `login`
--

INSERT INTO `login` (`id_login`, `user`, `pass`, `id_member`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `member`
--

CREATE TABLE `member` (
  `id_member` int(11) NOT NULL,
  `nm_member` varchar(255) NOT NULL,
  `pw` varchar(255) NOT NULL,
  `alamat_member` text NOT NULL,
  `telepon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gambar` text NOT NULL DEFAULT 'unnamed.jpg',
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `Nik` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `member`
--

INSERT INTO `member` (`id_member`, `nm_member`, `pw`, `alamat_member`, `telepon`, `email`, `gambar`, `status`, `Nik`) VALUES
(43, 'mario12', '202cb962ac59075b964b07152d234b70', 'kp.pisangan', '4904904994', '123@gmail.com', '1747665266_Product (1).png', 'Tidak Aktif', '89884844'),
(46, 'haha', '202cb962ac59075b964b07152d234b70', 'kp.pisangan', '80580537738738', 'haha@gmail.com', '1748085348_Product2.png', 'Tidak Aktif', ''),
(47, 'hilmi', '202cb962ac59075b964b07152d234b70', 'kp.pisangan', '98765676789', 'hilmi@gmail.com', '1748085522_Product2.png', 'Tidak Aktif', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `member1`
--

CREATE TABLE `member1` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `diskon` int(11) NOT NULL,
  `point` int(11) NOT NULL,
  `Tanggal_Aktif` datetime DEFAULT current_timestamp(),
  `status` enum('active','non-active') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `member1`
--

INSERT INTO `member1` (`id`, `name`, `phone`, `diskon`, `point`, `Tanggal_Aktif`, `status`) VALUES
(42, 'marsel', '90009990999', 5, 10, '2025-05-22 11:18:46', 'active'),
(43, 'rizal', '0000000000000', 100, 30, '2025-05-24 17:54:17', 'non-active'),
(45, 'venom', '045678902876543', 10, 30, '2025-05-24 17:52:30', 'non-active'),
(46, 'hilmi', '085718514933', 30, 8774, '2025-05-27 19:06:54', 'active'),
(47, 'marsel', '0811018970', 0, 370, '2025-05-29 08:34:43', 'active'),
(48, 'indah', '081284421151', 10, 31, '2025-06-02 07:56:40', 'active'),
(49, 'fahri alatas', '089502793671', 10, 240, '2025-06-07 22:27:09', 'active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nota`
--

CREATE TABLE `nota` (
  `id_nota` int(11) NOT NULL,
  `id_nota_utama` int(11) DEFAULT NULL,
  `id_barang` varchar(255) NOT NULL,
  `id_member` int(11) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `tanggal_input` varchar(255) NOT NULL,
  `periode` varchar(255) DEFAULT NULL,
  `status` enum('pending','dibayar') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `nota`
--

INSERT INTO `nota` (`id_nota`, `id_nota_utama`, `id_barang`, `id_member`, `jumlah`, `total`, `tanggal_input`, `periode`, `status`) VALUES
(369, NULL, 'BR005', 42, '1', '10000', '2025-05-26 18:40:23', '202505', 'pending'),
(370, NULL, 'BR007', 0, '1', '10000', '2025-05-26 18:41:46', '202505', 'pending'),
(371, NULL, 'BR007', 42, '1', '10000', '2025-05-26 18:42:12', '202505', 'pending'),
(372, NULL, 'BR005', 42, '1', '10000', '2025-05-26 18:43:54', '202505', 'pending'),
(373, NULL, 'BR005', 42, '1', '10000', '2025-05-27 18:41:52', '202505', 'pending'),
(374, NULL, 'BR005', 42, '1', '10000', '2025-05-27 18:53:41', '202505', 'pending'),
(375, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:07:30', '202505', 'pending'),
(376, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:11:07', '202505', 'pending'),
(377, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:11:31', '202505', 'pending'),
(378, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:12:50', '202505', 'pending'),
(379, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:50:18', '202505', 'pending'),
(380, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:51:15', '202505', 'pending'),
(381, NULL, 'BR005', 46, '1', '10000', '2025-05-27 19:58:28', '202505', 'pending'),
(382, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:02:48', '202505', 'pending'),
(383, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:04:05', '202505', 'pending'),
(384, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:06:18', '202505', 'pending'),
(385, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:07:10', '202505', 'pending'),
(386, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:10:12', '202505', 'pending'),
(387, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:12:22', '202505', 'pending'),
(388, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:13:03', '202505', 'pending'),
(389, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:17:12', '202505', 'pending'),
(390, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:18:24', '202505', 'pending'),
(391, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:19:55', '202505', 'pending'),
(392, NULL, 'BR005', 46, '1', '10000', '2025-05-27 20:20:35', '202505', 'pending'),
(393, NULL, 'BR005', 41, '1', '10000', '2025-05-29 08:32:16', '202505', 'pending'),
(394, NULL, 'BR005', 47, '1', '10000', '2025-05-29 08:35:29', '202505', 'pending'),
(395, NULL, 'BR005', 47, '1', '10000', '2025-05-29 08:36:25', '202505', 'pending'),
(396, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:19:20', '202505', 'pending'),
(397, NULL, 'BR005', 47, '1', '10000', '2025-05-30 13:20:42', '202505', 'pending'),
(398, NULL, 'BR005', 47, '1', '10000', '2025-05-30 13:30:30', '202505', 'pending'),
(399, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:31:37', '202505', 'pending'),
(400, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:32:14', '202505', 'pending'),
(401, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:54:06', '202505', 'pending'),
(402, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:54:35', '202505', 'pending'),
(403, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:57:04', '202505', 'pending'),
(404, NULL, 'BR005', 42, '1', '10000', '2025-05-30 13:59:07', '202505', 'pending'),
(405, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:02:23', '202505', 'pending'),
(406, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:03:04', '202505', 'pending'),
(407, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:07:42', '202505', 'pending'),
(408, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:09:34', '202505', 'pending'),
(409, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:12:43', '202505', 'pending'),
(410, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:13:44', '202505', 'pending'),
(411, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:16:21', '202505', 'pending'),
(412, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:19:08', '202505', 'pending'),
(413, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:19:45', '202505', 'pending'),
(414, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:20:24', '202505', 'pending'),
(415, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:21:44', '202505', 'pending'),
(416, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:24:09', '202505', 'pending'),
(417, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:25:17', '202505', 'pending'),
(418, NULL, 'BR005', 47, '1', '10000', '2025-05-30 14:26:01', '202505', 'pending'),
(419, NULL, 'BR002', 47, '1', '120000', '2025-05-30 14:34:18', '202505', 'pending'),
(420, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:35:15', '202505', 'pending'),
(421, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:39:55', '202505', 'pending'),
(422, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:42:47', '202505', 'pending'),
(423, NULL, 'BR005', 42, '1', '10000', '2025-05-30 14:43:37', '202505', 'pending'),
(424, NULL, 'BR005', 0, '1', '10000', '2025-05-30 14:46:23', '202505', 'pending'),
(425, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:47:50', '202505', 'pending'),
(426, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:49:53', '202505', 'pending'),
(427, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:50:17', '202505', 'pending'),
(428, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:50:52', '202505', 'pending'),
(429, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:51:18', '202505', 'pending'),
(430, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:52:26', '202505', 'pending'),
(431, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:53:02', '202505', 'pending'),
(432, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:54:18', '202505', 'pending'),
(433, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:57:08', '202505', 'pending'),
(434, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:57:40', '202505', 'pending'),
(435, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:58:01', '202505', 'pending'),
(436, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:59:03', '202505', 'pending'),
(437, NULL, 'BR005', 46, '1', '10000', '2025-05-30 14:59:28', '202505', 'pending'),
(438, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:00:20', '202505', 'pending'),
(439, NULL, 'BR002', 0, '1', '120000', '2025-05-30 15:01:24', '202505', 'pending'),
(440, NULL, 'BR002', 46, '1', '120000', '2025-05-30 15:02:11', '202505', 'pending'),
(441, NULL, 'BR002', 46, '1', '120000', '2025-05-30 15:02:37', '202505', 'pending'),
(442, NULL, 'BR002', 0, '1', '120000', '2025-05-30 15:03:13', '202505', 'pending'),
(443, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:07:27', '202505', 'pending'),
(444, NULL, 'BR005', 46, '1', '10000', '2025-05-30 15:10:34', '202505', 'pending'),
(445, NULL, 'BR005', 46, '1', '10000', '2025-05-30 15:19:38', '202505', 'pending'),
(446, NULL, 'BR005', 46, '1', '10000', '2025-05-30 15:20:27', '202505', 'pending'),
(447, NULL, 'BR005', 46, '1', '10000', '2025-05-30 15:20:43', '202505', 'pending'),
(448, NULL, 'BR005', 46, '1', '10000', '2025-05-30 15:21:07', '202505', 'pending'),
(449, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:32:19', '202505', 'pending'),
(450, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:35:57', '202505', 'pending'),
(451, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:39:07', '202505', 'pending'),
(452, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:42:14', '202505', 'pending'),
(453, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:42:35', '202505', 'pending'),
(454, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:47:02', '202505', 'pending'),
(455, NULL, 'BR005', 0, '1', '10000', '2025-05-30 15:52:12', '202505', 'pending'),
(456, NULL, 'BR005', 0, '1', '10000', '2025-05-30 16:00:46', '202505', 'pending'),
(457, NULL, 'BR005', 0, '1', '10000', '2025-05-30 16:02:46', '202505', 'pending'),
(458, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:08:08', '202505', 'pending'),
(459, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:08:23', '202505', 'pending'),
(460, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:12:33', '202505', 'pending'),
(461, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:12:45', '202505', 'pending'),
(462, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:13:36', '202505', 'pending'),
(463, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:14:02', '202505', 'pending'),
(464, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:14:24', '202505', 'pending'),
(465, NULL, 'BR005', 46, '2', '20000', '2025-05-30 16:15:01', '202505', 'pending'),
(466, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:15:59', '202505', 'pending'),
(467, NULL, 'BR005', 46, '2', '20000', '2025-05-30 16:16:50', '202505', 'pending'),
(468, NULL, 'BR005', 46, '1', '10000', '2025-05-30 16:17:27', '202505', 'pending'),
(469, NULL, 'BR005', 48, '1', '10000', '2025-06-02 07:57:51', '202506', 'pending'),
(470, NULL, 'BR005', 48, '1', '10000', '2025-06-02 07:58:23', '202506', 'pending'),
(471, NULL, 'BR007', 48, '1', '10000', '2025-06-02 07:59:33', '202506', 'pending'),
(472, NULL, 'BR007', 48, '1', '10000', '2025-06-02 08:02:43', '202506', 'pending'),
(473, NULL, 'BR006', 48, '1', '1000', '2025-06-02 08:02:43', '202506', 'pending'),
(474, NULL, 'BR003', 46, '1', '12000', '2025-06-02 08:24:21', '202506', 'pending'),
(475, NULL, 'BR007', 46, '1', '10000', '2025-06-02 08:24:21', '202506', 'pending'),
(476, NULL, 'BR003', 46, '1', '12000', '2025-06-02 08:25:08', '202506', 'pending'),
(477, NULL, 'BR007', 46, '1', '10000', '2025-06-02 08:25:08', '202506', 'pending'),
(478, NULL, 'BR002', 46, '1', '120000', '2025-06-02 08:28:54', '202506', 'pending'),
(479, NULL, 'BR007', 46, '1', '10000', '2025-06-02 08:28:54', '202506', 'pending'),
(480, NULL, 'BR007', 46, '1', '10000', '2025-06-02 08:32:08', '202506', 'pending'),
(481, NULL, 'BR002', 46, '1', '120000', '2025-06-02 08:32:08', '202506', 'pending'),
(482, NULL, 'BR002', 46, '1', '120000', '2025-06-02 08:38:42', '202506', 'pending'),
(483, NULL, 'BR007', 46, '1', '10000', '2025-06-02 08:38:42', '202506', 'pending'),
(484, NULL, 'BR007', 46, '1', '10000', '2025-06-02 08:41:27', '202506', 'pending'),
(485, NULL, 'BR006', 46, '1', '1000', '2025-06-02 08:41:27', '202506', 'pending'),
(486, NULL, 'BR002', 49, '1', '120000', '2025-06-07 22:27:44', '202506', 'pending'),
(487, NULL, 'BR002', 49, '1', '120000', '2025-06-07 22:28:50', '202506', 'pending'),
(488, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:29:45', '202507', 'pending'),
(489, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:32:00', '202507', 'pending'),
(490, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:33:01', '202507', 'pending'),
(491, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:43:06', '202507', 'pending'),
(492, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:43:55', '202507', 'pending'),
(493, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:45:45', '202507', 'pending'),
(494, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:47:53', '202507', 'pending'),
(495, NULL, 'BR006', 46, '1', '1000', '2025-07-15 19:47:53', '202507', 'pending'),
(496, NULL, 'BR007', 46, '1', '10000', '2025-07-15 19:50:48', '202507', 'pending'),
(497, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:50:48', '202507', 'pending'),
(498, NULL, 'BR002', 46, '1', '120000', '2025-07-15 19:52:59', '202507', 'pending'),
(499, NULL, 'BR007', 46, '1', '10000', '2025-07-15 19:52:59', '202507', 'pending'),
(500, NULL, 'BR006', 46, '1', '1000', '2025-07-15 19:53:54', '202507', 'pending'),
(501, NULL, 'BR007', 46, '1', '10000', '2025-07-15 19:53:54', '202507', 'pending'),
(502, NULL, 'BR002', 46, '1', '120000', '2025-07-15 20:04:55', '202507', 'pending'),
(503, NULL, 'BR007', 46, '1', '10000', '2025-07-15 20:04:55', '202507', 'pending'),
(504, NULL, 'BR002', 46, '1', '120000', '2025-07-15 20:18:21', '202507', 'pending'),
(505, NULL, 'BR007', 46, '1', '10000', '2025-07-15 20:18:21', '202507', 'pending'),
(506, NULL, 'BR002', 46, '1', '120000', '2025-07-15 20:25:13', '202507', 'pending'),
(507, NULL, 'BR002', 46, '1', '120000', '2025-07-15 20:37:20', '202507', 'pending'),
(508, NULL, 'BR007', 46, '1', '10000', '2025-07-15 20:37:20', '202507', 'pending'),
(509, NULL, 'BR007', 46, '1', '10000', '2025-07-15 20:39:02', '202507', 'pending'),
(510, NULL, 'BR002', 46, '1', '120000', '2025-07-15 20:39:02', '202507', 'pending'),
(511, NULL, 'BR002', 46, '1', '120000', '2025-07-15 20:40:34', '202507', 'pending'),
(512, NULL, 'BR007', 46, '1', '10000', '2025-07-15 20:40:34', '202507', 'pending'),
(513, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:05:13', '202507', 'pending'),
(514, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:05:13', '202507', 'pending'),
(515, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:08:02', '202507', 'pending'),
(516, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:08:02', '202507', 'pending'),
(517, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:10:47', '202507', 'pending'),
(518, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:10:47', '202507', 'pending'),
(519, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:12:55', '202507', 'pending'),
(520, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:12:55', '202507', 'pending'),
(521, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:16:05', '202507', 'pending'),
(522, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:16:05', '202507', 'pending'),
(523, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:17:18', '202507', 'pending'),
(524, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:17:18', '202507', 'pending'),
(525, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:17:54', '202507', 'pending'),
(526, NULL, 'BR002', 46, '2', '240000', '2025-07-15 21:18:46', '202507', 'pending'),
(527, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:18:46', '202507', 'pending'),
(528, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:21:09', '202507', 'pending'),
(529, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:24:10', '202507', 'pending'),
(530, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:24:10', '202507', 'pending'),
(531, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:24:52', '202507', 'pending'),
(532, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:25:26', '202507', 'pending'),
(533, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:26:46', '202507', 'pending'),
(534, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:26:46', '202507', 'pending'),
(535, NULL, 'BR002', 46, '1', '120000', '2025-07-15 21:32:47', '202507', 'pending'),
(536, NULL, 'BR007', 46, '1', '10000', '2025-07-15 21:32:47', '202507', 'pending'),
(537, NULL, 'BR002', 46, '3', '360000', '2025-07-15 22:30:26', '202507', 'pending'),
(538, NULL, 'BR002', 46, '1', '120000', '2025-07-15 22:32:36', '202507', 'pending'),
(539, NULL, 'BR007', 46, '1', '10000', '2025-07-15 22:32:36', '202507', 'pending'),
(540, NULL, 'BR002', 47, '1', '120000', '2025-07-15 22:33:43', '202507', 'pending'),
(541, NULL, 'BR007', 47, '1', '10000', '2025-07-15 22:33:43', '202507', 'pending'),
(542, NULL, 'BR002', 46, '1', '120000', '2025-07-15 22:39:19', '202507', 'pending'),
(543, NULL, 'BR007', 46, '1', '10000', '2025-07-15 22:39:19', '202507', 'pending'),
(544, NULL, 'BR002', 46, '1', '120000', '2025-07-15 22:41:06', '202507', 'pending'),
(545, NULL, 'BR007', 46, '1', '10000', '2025-07-15 22:41:06', '202507', 'pending'),
(546, NULL, 'BR002', 46, '1', '120000', '2025-07-16 16:04:11', '202507', 'pending'),
(547, NULL, 'BR007', 46, '1', '10000', '2025-07-16 16:04:11', '202507', 'pending'),
(548, NULL, 'BR002', 46, '1', '120000', '2025-07-16 16:05:34', '202507', 'pending'),
(549, NULL, 'BR007', 46, '1', '10000', '2025-07-16 16:05:34', '202507', 'pending'),
(550, NULL, 'BR002', 46, '1', '120000', '2025-07-16 16:06:33', '202507', 'pending'),
(551, NULL, 'BR007', 46, '1', '10000', '2025-07-16 16:06:33', '202507', 'pending'),
(552, NULL, 'BR002', 46, '1', '120000', '2025-07-16 16:07:51', '202507', 'pending'),
(553, 3, 'BR002', 46, '1', '120000', '2025-07-16 16:23:26', '202507', 'pending'),
(554, 3, 'BR007', 46, '1', '10000', '2025-07-16 16:23:26', '202507', 'pending'),
(555, 4, 'BR006', 46, '1', '1000', '2025-07-16 16:25:32', '202507', 'pending'),
(556, 4, 'BR007', 46, '1', '10000', '2025-07-16 16:25:32', '202507', 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `nota_utama`
--

CREATE TABLE `nota_utama` (
  `id_nota_utama` int(11) NOT NULL,
  `id_member` int(11) DEFAULT NULL,
  `tanggal_input` datetime DEFAULT NULL,
  `periode` varchar(10) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `diskon` int(11) DEFAULT NULL,
  `bayar` int(11) DEFAULT NULL,
  `kembalian` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `nota_utama`
--

INSERT INTO `nota_utama` (`id_nota_utama`, `id_member`, `tanggal_input`, `periode`, `total`, `diskon`, `bayar`, `kembalian`) VALUES
(3, 0, '2025-07-16 16:23:26', '202507', 130000, 0, 1200000, 1070000),
(4, 0, '2025-07-16 16:25:32', '202507', 11000, 0, 1200000, 1189000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `penjualan`
--

CREATE TABLE `penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `id_barang` varchar(255) NOT NULL,
  `id_member` int(11) NOT NULL,
  `jumlah` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `tanggal_input` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `penjualan`
--

INSERT INTO `penjualan` (`id_penjualan`, `id_barang`, `id_member`, `jumlah`, `total`, `tanggal_input`) VALUES
(45, 'BR008', 21, '1', '12000', '14 May 2025, 19:15');

-- --------------------------------------------------------

--
-- Struktur dari tabel `reset_tokens`
--

CREATE TABLE `reset_tokens` (
  `id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `expired` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `toko`
--

CREATE TABLE `toko` (
  `id_toko` int(11) NOT NULL,
  `nama_toko` varchar(255) NOT NULL,
  `alamat_toko` text NOT NULL,
  `tlp` varchar(255) NOT NULL,
  `nama_pemilik` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `toko`
--

INSERT INTO `toko` (`id_toko`, `nama_toko`, `alamat_toko`, `tlp`, `nama_pemilik`) VALUES
(1, 'Tokokulah', 'kp.Pisangan Rt 05/011', '089618173609', 'marsel herlino');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id_login`);

--
-- Indeks untuk tabel `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`id_member`);

--
-- Indeks untuk tabel `member1`
--
ALTER TABLE `member1`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`id_nota`);

--
-- Indeks untuk tabel `nota_utama`
--
ALTER TABLE `nota_utama`
  ADD PRIMARY KEY (`id_nota_utama`);

--
-- Indeks untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indeks untuk tabel `reset_tokens`
--
ALTER TABLE `reset_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id_toko`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT untuk tabel `barang`
--
ALTER TABLE `barang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `login`
--
ALTER TABLE `login`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `member`
--
ALTER TABLE `member`
  MODIFY `id_member` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `member1`
--
ALTER TABLE `member1`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `nota`
--
ALTER TABLE `nota`
  MODIFY `id_nota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=557;

--
-- AUTO_INCREMENT untuk tabel `nota_utama`
--
ALTER TABLE `nota_utama`
  MODIFY `id_nota_utama` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT untuk tabel `reset_tokens`
--
ALTER TABLE `reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `toko`
--
ALTER TABLE `toko`
  MODIFY `id_toko` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
