-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jul 2024 pada 11.18
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sikompen`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_05_06_062719_create_tbl_user', 1),
(7, '2024_05_06_062824_create_tbl_pekerjaan', 1),
(8, '2024_05_09_064722_add_limit_pekerja_to_tbl_pekerjaan_table', 2),
(9, '2024_05_09_072530_add_batas_pekerja_to_tbl_pekerjaan_table', 3),
(28, '2024_05_28_075722_create_tbl_mahasiswa', 8),
(49, '2024_06_05_044101_add_semester_to_tbl_mahasiswa_table', 11),
(52, '2024_05_29_062744_create_tbl_pengajuan', 12),
(53, '2024_05_29_081132_create_tbl_pengajuan_detail', 12),
(54, '2024_06_12_104625_add_penanggung_jawab_to_tbl_pekerjaan', 13),
(56, '2024_06_12_124944_add_id_penanggung_jawab_to_tbl_pekerjaan', 14),
(57, '2024_06_12_125443_add_id_penanggung_jawab_to_tbl_pengajuan', 15),
(58, '2024_06_12_135705_add_prodi_to_tbl_user', 16),
(61, '2024_06_21_075850_create_tbl_setup_bertugas', 18),
(62, '2024_06_21_112636_add_collumn_to_tbl_pengajuan', 19),
(63, '2024_05_15_052259_create_tbl_kelas_table', 20),
(66, '2024_06_22_053412_create_tbl_form_bebas_kompen', 21),
(67, '2024_06_28_101848_add_collumn_to_tbl_form_bebas_kompen', 22),
(68, '2024_07_02_081408_add_collumn_edit_password_to_tbl_user', 23),
(69, '2024_07_02_092316_add_collumn_edit_password_to_tbl_mahasiswa', 24),
(70, '2024_07_02_105336_add_collumn_token_to_tbl_mahasiswa', 25),
(71, '2024_07_02_105401_add_collumn_token_to_tbl_user', 25),
(72, '2024_05_15_051755_create_tbl_prodi_table', 26),
(73, '2024_07_17_062119_add_collumn_to_tbl_pengajuan', 27),
(74, '2024_07_17_062450_add_collum_perkiraan_to_tbl_pengajuan', 28);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_form_bebas_kompen`
--

CREATE TABLE `tbl_form_bebas_kompen` (
  `id_bebas_kompen` bigint(20) UNSIGNED NOT NULL,
  `id_pengajuan` bigint(20) DEFAULT NULL,
  `kode_user` varchar(50) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `jumlah_terlambat` varchar(50) DEFAULT NULL,
  `jumlah_alfa` varchar(50) DEFAULT NULL,
  `total` varchar(50) DEFAULT NULL,
  `sisa` varchar(50) DEFAULT NULL,
  `form_bebas_kompen` varchar(255) DEFAULT NULL,
  `status_approval1` varchar(50) DEFAULT NULL,
  `approval1_by` varchar(50) DEFAULT NULL,
  `status_approval2` varchar(50) DEFAULT NULL,
  `approval2_by` varchar(50) DEFAULT NULL,
  `status_approval3` varchar(50) DEFAULT NULL,
  `approval3_by` varchar(50) DEFAULT NULL,
  `uid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_form_bebas_kompen`
--

INSERT INTO `tbl_form_bebas_kompen` (`id_bebas_kompen`, `id_pengajuan`, `kode_user`, `nama_user`, `kelas`, `prodi`, `semester`, `jumlah_terlambat`, `jumlah_alfa`, `total`, `sisa`, `form_bebas_kompen`, `status_approval1`, `approval1_by`, `status_approval2`, `approval2_by`, `status_approval3`, `approval3_by`, `uid`, `created_at`, `updated_at`) VALUES
(7, 61, '111', 'ggg', 'TMD 1A', 'TMD', '1', NULL, NULL, NULL, NULL, 'sfwJ2OyCIjvzCltItzNWRKBdlXY12bOIdXHMjaap.png', 'Disetujui', 'Kalab', 'Disetujui', 'pengawas', 'Disetujui', 'gg', NULL, '2024-06-26 04:20:58', '2024-06-26 04:20:58'),
(8, 62, '222', 'AAAA', 'TMD 1A', 'TMD', '1', NULL, NULL, NULL, NULL, 'UQcAXsIknGNCNAxe1cbZOfbUhGJ8xuiiDDQ7YPOp.png', 'Disetujui', 'Kalab', 'Disetujui', 'pengawas', 'Disetujui', 'gg', NULL, '2024-06-26 04:21:39', '2024-06-26 04:21:39'),
(9, 64, '5232', 'GG', 'TI 1A', 'TI', '3', NULL, NULL, NULL, NULL, 'AiXpd9pnOUqCKk9QCnXk2F0QEb5bT5F06AtMQSVw.png', 'Disetujui', 'Kalab', 'Disetujui', 'pengawas', 'Disetujui', 'gg', NULL, '2024-06-26 04:23:41', '2024-06-26 04:23:41'),
(15, 95, '2307431028', 'MICHAEL FARREL FEDORA', 'TMD 2A', 'TMD', '2', '0', '0', '0', '0', '9zbcQAq534iNgfea2JjrhvVEnVTUGtVTTWorjcU7.png', 'Disetujui', 'pengawas', 'Disetujui', 'Kalab', 'Disetujui', 'gg', NULL, '2024-06-28 04:01:54', '2024-06-28 04:01:54'),
(19, 185, NULL, NULL, 'TMD 3A', 'TMD', '3', '1555', '55', '1610', '0', 'zNbaHPBXymknaEXlsCRTV47HQB9gNikqmtj02YXQ.png', 'Disetujui', NULL, 'Disetujui', 'gg', 'Disetujui', 'asdasdas', NULL, '2024-07-08 05:00:17', '2024-07-16 03:07:22'),
(29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-16 23:42:26', '2024-07-16 23:42:26'),
(32, 184, '555', 'gg', 'TMD 3A', 'TMD', '3', '1200', '60', '1260', '0', 'KgdCXybijYu02J6bfrA3mDKxzNob9riaZFtFX5RF.png', 'Disetujui', 'pengawas', 'Disetujui', 'testPLP', 'Disetujui', 'Kalab', NULL, '2024-07-16 23:54:50', '2024-07-16 23:54:50'),
(33, 211, '212', 'asdas', 'TMD 3A', 'TMD', '3', '1555', '55', '1610', '0', 'z17m3V8IwErjxrfFwUQOAt61cWKpSHyIJqHOWehA.png', 'Disetujui', 'pengawas', 'Disetujui', 'testPLP', 'Disetujui', 'KALAB', NULL, '2024-07-23 00:47:50', '2024-07-23 00:47:50'),
(34, 212, '212', 'asdas', 'TMD 3A', 'TMD', '3', '1555', '55', '1610', '0', 'OOrTk98mgs4t3qMu6SjzXZNH5QciGjlwImF5vzP7.png', 'Disetujui', 'pengawas', 'Disetujui', 'testPLP', 'Disetujui', 'KALAB', NULL, '2024-07-23 05:05:25', '2024-07-23 05:05:25'),
(35, 220, '2134', 'hans', 'TI5A', 'TI', '5', '0', '0', '0', '0', 'Yn5nfnk3u7uBU1TTPSCis5xcxDpF5WU8gbP4FPCb.png', 'Disetujui', 'pengawas', 'Disetujui', 'testPLP', 'Disetujui', 'Kalab', NULL, '2024-07-24 00:50:30', '2024-07-24 00:50:30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `id_kelas` bigint(20) UNSIGNED NOT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `dosen_pembimbing_akademik` varchar(50) DEFAULT NULL,
  `uid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`id_kelas`, `kelas`, `dosen_pembimbing_akademik`, `uid`, `created_at`, `updated_at`) VALUES
(2, 'TMD 1A', NULL, '28ec541d-309c-4524-8280-f8deaeade529', '2024-06-26 04:17:41', '2024-06-28 02:43:02'),
(3, 'TMD 2A', NULL, '5f224013-c38c-4ec6-b5a2-e0149aaff3e0', '2024-06-28 02:42:52', '2024-06-28 02:42:52'),
(4, 'TMD 3A', NULL, 'c889b24d-2031-4ccf-99df-f06bc584463d', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(5, 'TMD 7A', NULL, '3e4857c6-1f04-4bc4-b70a-45e77ca81bde', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(6, 'TI 1A', NULL, '8852cc6f-a1fc-4218-a7ba-e81655b9c9b2', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(7, 'TI 3A', NULL, 'efb7b5e5-2e58-4f56-a41c-d2bd13d04477', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(8, 'TI 5A', NULL, 'cade9c36-d2a5-4b77-a576-9653608b4962', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(9, 'TI-CCIT 1', NULL, 'e16b1cb8-b866-40c3-b733-f48d5f9f0088', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(10, 'TI-CCIT 3', NULL, 'dade275e-5ae9-48f6-a744-e2097bf92971', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(11, 'TI-CCIT 5', NULL, 'c7ff78f8-82d4-4b8a-8937-e18904a67255', '2024-07-08 05:33:08', '2024-07-08 05:33:08'),
(12, 'TI-CCIT 7', NULL, '8944d481-d845-4dea-a92f-8a004cd88f02', '2024-07-08 05:33:08', '2024-07-08 05:33:08');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_mahasiswa`
--

CREATE TABLE `tbl_mahasiswa` (
  `id_mahasiswa` bigint(20) UNSIGNED NOT NULL,
  `kode_user` varchar(50) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `notelp` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `edit_password` varchar(30) DEFAULT '0',
  `token` varchar(50) DEFAULT NULL,
  `status_token` int(11) NOT NULL DEFAULT 0,
  `role` varchar(50) NOT NULL DEFAULT 'Mahasiswa',
  `jumlah_terlambat` varchar(50) DEFAULT NULL,
  `jumlah_alfa` varchar(50) DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `user_create` varchar(255) DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `uid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_mahasiswa`
--

INSERT INTO `tbl_mahasiswa` (`id_mahasiswa`, `kode_user`, `nama_user`, `email`, `prodi`, `kelas`, `semester`, `notelp`, `password`, `edit_password`, `token`, `status_token`, `role`, `jumlah_terlambat`, `jumlah_alfa`, `total`, `user_create`, `user_update`, `uid`, `created_at`, `updated_at`) VALUES
(178, '2307431028', 'MICHAEL FARREL FEDORA', NULL, 'TMD', 'TMD 2A', '2', NULL, '$2y$12$VRsgwYqinXDMYrDU074sxuvH2Y9UrwDNRgW6Ma7Aw8YW.1zAmj3Fu', '0', NULL, 0, 'Mahasiswa', '4', '8', '12', NULL, NULL, NULL, '2024-07-23 00:37:26', '2024-07-23 00:37:26'),
(179, '2307431022', 'MUHAMMAD DZIKRI AL FARROS', NULL, 'TMD', 'TMD 2A', '2', NULL, '$2y$12$y1W3FV74EUWE/rs7K/ZkDOryW49c0ckCK9GSr4PABQUjIIkqWtypO', '0', NULL, 0, 'Mahasiswa', '57', '960', '1017', NULL, NULL, NULL, '2024-07-23 00:37:26', '2024-07-23 00:37:26'),
(180, '2307431009', 'MUHAMMAD KHALFANI ABRAR FATHIR', NULL, 'TMD', 'TMD 2A', '2', NULL, '$2y$12$Znsj/.cCkYKjFZhZ1z9Dtum5QFbTru53IpIhFn4INjtcMlSShu8q2', '0', NULL, 0, 'Mahasiswa', '0', '720', '720', NULL, NULL, NULL, '2024-07-23 00:37:27', '2024-07-23 00:37:27'),
(181, '54321', 'asdsaadsa', NULL, 'TMD', 'TMD 2A', '2', NULL, '$2y$12$cCzg.83ziSahIxLi1sjC4.llnLvSFqRf177diSRaorXLesPhB1edq', '0', NULL, 0, 'Mahasiswa', '1531', '10', '1541', NULL, NULL, NULL, '2024-07-23 00:37:27', '2024-07-23 00:37:27'),
(182, '555', 'gg', NULL, 'TMD', 'TMD 3A', '3', NULL, '$2y$12$NkYbub6iU/FQMB2Vr2902uF3YL0tlHUKRqgLNE8.R0Rfx.MRC0K.G', '0', NULL, 0, 'Mahasiswa', '1200', '60', '1260', NULL, NULL, NULL, '2024-07-23 00:37:28', '2024-07-23 00:37:28'),
(183, '212', 'asdas', 'kevinrisqir21@gmail.com', 'TMD', 'TMD 3A', '3', NULL, '$2y$12$cKjYWrtF12SQZ4CdrMqfc.phCDNIibVsfr/ZG69bgr2WSTRc5IiZ2', '0', 'iupe2bEwKYNo', 1, 'Mahasiswa', '1555', '55', '1610', NULL, NULL, NULL, '2024-07-23 00:37:29', '2024-07-23 04:53:06'),
(184, '111', 'ggg', NULL, 'TMD', 'TMD 3A', '2', NULL, '$2y$12$Dl4baXagv05c1MXlgKWJi.gcIzx7vZ/ujZxnnFDC0w.S/3D1gOFYi', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:29', '2024-07-23 00:37:29'),
(185, '222', 'AAAA', NULL, 'TMD', 'TMD 3A', '2', NULL, '$2y$12$owZjHjZrNId8pl6b4Xv2CehUOapfNqtw7dNpF9FHq8wW1QncQGX5u', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:30', '2024-07-23 00:37:30'),
(186, '333', 'BBB', NULL, 'TMD', 'TMD 2A', '2', NULL, '$2y$12$TvSii6BCBHfVYfOt2Yj32eDHVZ2ypvPSfh6OHSyU6OE4LKhcLV80i', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:30', '2024-07-23 00:37:30'),
(187, '5232', 'GG', NULL, 'TI', 'TI 2A', '4', NULL, '$2y$12$rvlAaOXK9BA.F27f/JIDJ.skMdCDsrNB2hMJ5OLsosOEqT0HylPBi', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:31', '2024-07-23 00:37:31'),
(188, '293', 'TESTTT', NULL, 'TMJ', 'TMJ 2A', '4', NULL, '$2y$12$b4VTP8TOl/joELIZ2s6xH.D2HzwL5L4qVhuHsKRf9kDcLxl4oMaTO', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:32', '2024-07-23 00:37:32'),
(189, '412321', 'GGGGGG', NULL, 'TI', 'TI 4A', '4', NULL, '$2y$12$ljfUrqn6MscAYgVpnmx9I.MJAaCP0xjZvYpLIwwUj4wds6vPH9aoa', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:32', '2024-07-23 00:37:32'),
(190, '422', 'gas', NULL, 'TI', 'TI 5A', '5', NULL, '$2y$12$RcZNWPKCT..nBBNoxhFT9Oe9HwJe1FMPdyXDmDkwpimZ2aTSru/va', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-23 00:37:33', '2024-07-23 00:37:33'),
(192, '585', 'Arief', NULL, 'TI', 'TI 5A', '6', NULL, '$2y$12$8Ox74VavMH.mM61gi64qA.WGAFy/uovZAjFxFsAWZ20LMfpb3VpFS', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-24 00:24:41', '2024-07-24 00:24:41'),
(193, '92090', 'GALIH', NULL, 'TI', 'TI 5A', '5', NULL, '$2y$12$f1VS0pXSdfFX40cyntHeluJOfvBzhvC1gwmsPs0g21noz2/sTtADS', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-24 00:26:55', '2024-07-24 00:26:55'),
(194, '4321', 'aluka', NULL, 'TI', 'TI5A', '5', NULL, '$2y$12$rrARYSsbqRtygVFlg8Cfo.b94EeNVDrJABrD.TvNAU1aEGZ4CRY7.', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-24 00:29:38', '2024-07-24 00:29:38'),
(195, '2134', 'hans', NULL, 'TI', 'TI5A', '5', NULL, '$2y$12$wx9pgSCZCiIbqMfesC67TuE/0iZV6Ox4bc2U3LdoAet9AI1pNSPGq', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-24 00:32:43', '2024-07-24 01:13:16'),
(197, '6123', 'malik', NULL, 'TI', 'TI 5A', '5', NULL, '$2y$12$LEOdBDHHGxtVfVWZrPZ6R.GAVL7COXKsRnjmex6.toCQC92fM3DXa', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-24 00:52:52', '2024-07-24 00:52:52'),
(198, '8733', 'adam', NULL, 'TI', 'ti 5A', '5', NULL, '$2y$12$4UHz0Y4fbUjifEs.oVnzieg2G3V7hrgHKYTDmtzZVy3hpqml/4YBC', '0', NULL, 0, 'Mahasiswa', '0', '0', '0', NULL, NULL, NULL, '2024-07-24 01:13:16', '2024-07-24 01:13:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pekerjaan`
--

CREATE TABLE `tbl_pekerjaan` (
  `id_pekerjaan` bigint(20) UNSIGNED NOT NULL,
  `kode_pekerjaan` varchar(50) DEFAULT NULL,
  `nama_pekerjaan` varchar(1000) DEFAULT NULL,
  `jam_pekerjaan` varchar(50) DEFAULT NULL,
  `batas_pekerja` int(11) DEFAULT NULL,
  `id_penanggung_jawab` varchar(50) DEFAULT NULL,
  `penanggung_jawab` varchar(50) DEFAULT NULL,
  `user_create` varchar(255) DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `uid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_pekerjaan`
--

INSERT INTO `tbl_pekerjaan` (`id_pekerjaan`, `kode_pekerjaan`, `nama_pekerjaan`, `jam_pekerjaan`, `batas_pekerja`, `id_penanggung_jawab`, `penanggung_jawab`, `user_create`, `user_update`, `uid`, `created_at`, `updated_at`) VALUES
(1, 'K01', 'Kerja Bakti', '300', 15, '17', 'pengawas', NULL, NULL, '82ac8192-1611-4c5d-82f2-b4841ab6d2c6', '2024-07-23 00:30:55', '2024-07-23 05:26:24'),
(2, 'K02', 'Bersihin Kelas', '1000', 20, '17', 'pengawas', NULL, NULL, 'b09790d5-7ad1-4ca2-8ff1-cd030eb7da86', '2024-07-23 00:30:55', '2024-07-25 00:12:37'),
(3, 'K03', 'Cabut Rumput', '500', 50, '17', 'pengawas', NULL, NULL, 'aa9ef1f0-f5cc-4b6f-83bf-5217c1497c28', '2024-07-23 00:30:55', '2024-07-24 23:46:19'),
(4, 'K04', 'Laundry', '2000', 29, '17', 'pengawas', NULL, NULL, 'abd3277a-5d06-46d2-a261-c38edfebebdc', '2024-07-23 00:30:55', '2024-07-25 00:59:52'),
(5, 'GGGASDADAS2', 'GGGGGGG', '222222', 111, '17', 'pengawas', NULL, NULL, 'a1d0b548-971d-490b-8a66-1fe7d9da4b17', '2024-07-23 00:30:55', '2024-07-25 00:01:11'),
(6, 'AA1', 'Merapikan ruang dosen:\n1.	Memindahkan lemari cabinet.\n2.	Menata dan merapikan, membersihkan kaca ruang dosen. \n3.	Pasang Kordeng.', '300', 120, '32', 'lutfi', NULL, NULL, '28762186-b8c5-4704-95c6-39eb6c2fe611', '2024-07-23 00:31:22', '2024-07-23 00:31:22'),
(12, 'AA03', 'Merapikan  ruang rapat 201:\n1.	Merapikan berkas berkas dan ditata dilemari sesuai nama nama nya. \n2.	Merapikan kursi, meja rapat, membersihkan kaca\n3.	Merapihkan etalase dan alat-alat di dalamnya\n4.	Vacum dan sapu ruangan', '1000', 15, '17', 'pengawas', NULL, NULL, '2d2976cd-e0c5-4f12-9066-9f31fa071b29', '2024-07-24 23:00:07', '2024-07-25 00:08:07'),
(13, 'AA04', 'Merapikan embedded 001\n1.	Menata buku buku TA\n2.	Convert data skripsi berupa CD dari tahun 2015 – 2020 sebagai arsip jurusan tik \n3.	Melengkapi kebutuhan alat alat podcase sesuai dengan kompensasi nya \n4.	Menata ruang baca embedded 001', '1000', 15, '17', 'pengawas', NULL, NULL, 'f8a76c8c-0d9f-4232-b1aa-6731bfb5cc36', '2024-07-24 23:00:07', '2024-07-25 00:08:07'),
(14, 'AA05', 'Merapikan embedded 002:\n1.	Merapikan dan menata ruang ngoprek komputer \n2.	Menata alat alat kompenen ruang ngoprek komputer agar di tempatkan yang sesuai\n3.	Membuat   jalur intalansi kabel internet supaya rapi', '500', 10, '17', 'pengawas', NULL, NULL, '52895116-899c-408e-87cc-0d3ae41896e6', '2024-07-24 23:00:07', '2024-07-24 23:00:07'),
(15, 'AA06', 'Lab AA 301\n1.	Membersihkan kaca\n2.	Memperbaiki bangku yang rusak\n3.	Menata komputer dengan rapih\n4.	memperbaiki komputer yang rusak (cek kerusakan/install komputer)\n5.	Membersihkan dan vacum karpet\n6.	Mengecek aplikasi yang tidak digunakan\n7.	Cek/update/install software di lab', '500', 10, '17', 'pengawas', NULL, NULL, '8fb70aec-9163-4e42-9ddd-7e387e0671c5', '2024-07-24 23:00:07', '2024-07-24 23:00:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengajuan`
--

CREATE TABLE `tbl_pengajuan` (
  `id_pengajuan` bigint(20) UNSIGNED NOT NULL,
  `kode_kegiatan` varchar(50) DEFAULT NULL,
  `kode_user` varchar(50) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `kelas` varchar(50) DEFAULT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `semester` varchar(50) DEFAULT NULL,
  `jumlah_terlambat` varchar(50) DEFAULT NULL,
  `jumlah_alfa` varchar(50) DEFAULT NULL,
  `total` varchar(50) DEFAULT NULL,
  `sisa` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `id_penanggung_jawab` varchar(50) DEFAULT NULL,
  `penanggung_jawab` varchar(50) DEFAULT NULL,
  `tanggal_pengajuan` date DEFAULT NULL,
  `status_approval1` varchar(50) DEFAULT 'Belum Disetujui',
  `keterangan_approval1` varchar(255) DEFAULT NULL,
  `bukti_tambahan` varchar(50) DEFAULT NULL,
  `approval1_by` varchar(50) DEFAULT NULL,
  `status_approval2` varchar(50) DEFAULT 'Belum Disetujui',
  `keterangan_approval2` varchar(255) DEFAULT NULL,
  `approval2_by` varchar(50) DEFAULT NULL,
  `status_approval3` varchar(50) DEFAULT 'Belum Disetujui',
  `keterangan_approval3` varchar(255) DEFAULT NULL,
  `approval3_by` varchar(50) DEFAULT NULL,
  `ttd1` varchar(255) DEFAULT NULL,
  `ttd2` varchar(255) DEFAULT NULL,
  `ttd3` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Belum Selesai',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_create` varchar(255) DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `uid` char(36) DEFAULT NULL,
  `perkiraan_sisa_jam` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_pengajuan`
--

INSERT INTO `tbl_pengajuan` (`id_pengajuan`, `kode_kegiatan`, `kode_user`, `nama_user`, `kelas`, `prodi`, `semester`, `jumlah_terlambat`, `jumlah_alfa`, `total`, `sisa`, `keterangan`, `id_penanggung_jawab`, `penanggung_jawab`, `tanggal_pengajuan`, `status_approval1`, `keterangan_approval1`, `bukti_tambahan`, `approval1_by`, `status_approval2`, `keterangan_approval2`, `approval2_by`, `status_approval3`, `keterangan_approval3`, `approval3_by`, `ttd1`, `ttd2`, `ttd3`, `status`, `created_at`, `updated_at`, `user_create`, `user_update`, `uid`, `perkiraan_sisa_jam`) VALUES
(203, '1', '111', 'ggg', 'TMD 3A', 'TMD', '2', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:29', '2024-07-23 00:37:29', NULL, NULL, NULL, NULL),
(204, '2', '222', 'AAAA', 'TMD 3A', 'TMD', '2', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:30', '2024-07-23 00:37:30', NULL, NULL, NULL, NULL),
(205, '3', '333', 'BBB', 'TMD 2A', 'TMD', '2', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:30', '2024-07-23 00:37:30', NULL, NULL, NULL, NULL),
(206, '4', '5232', 'GG', 'TI 2A', 'TI', '4', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:31', '2024-07-23 00:37:31', NULL, NULL, NULL, NULL),
(207, '5', '293', 'TESTTT', 'TMJ 2A', 'TMJ', '4', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:32', '2024-07-23 00:37:32', NULL, NULL, NULL, NULL),
(208, '6', '412321', 'GGGGGG', 'TI 4A', 'TI', '4', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:32', '2024-07-23 00:37:32', NULL, NULL, NULL, NULL),
(209, '7', '422', 'gas', 'TI 5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:33', '2024-07-23 00:37:33', NULL, NULL, NULL, NULL),
(210, '8', '585', 'Arief', 'TI 5A', 'TI', '6', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'gg', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-23 00:37:34', '2024-07-23 00:37:34', NULL, NULL, NULL, NULL),
(218, '10', '92090', 'GALIH', 'TI 5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Sudah Upload', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'testPLP', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-24 00:26:55', '2024-07-25 00:10:38', NULL, NULL, NULL, NULL),
(219, '10', '4321', 'aluka', 'TI5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'testPLP', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-24 00:29:38', '2024-07-24 00:29:38', NULL, NULL, NULL, NULL),
(220, '10', '2134', 'hans', 'TI5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'testPLP', 'Disetujui', NULL, 'Kalab', '', NULL, NULL, 'Belum Selesai', '2024-07-24 00:32:43', '2024-07-24 00:32:43', NULL, NULL, NULL, NULL),
(221, '10', '6123', 'malik', 'TI 5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'testPLP', 'Disetujui', NULL, 'Kalab', NULL, NULL, NULL, 'Belum Selesai', '2024-07-24 00:52:52', '2024-07-24 00:52:52', NULL, NULL, NULL, NULL),
(222, '10', '2134', 'adam', 'ti 5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'testPLP', 'Disetujui', NULL, 'Kalab', NULL, NULL, NULL, 'Belum Selesai', '2024-07-24 01:12:56', '2024-07-24 01:12:56', NULL, NULL, NULL, NULL),
(223, '10', '8733', 'adam', 'ti 5A', 'TI', '5', '0', '0', '0', '0', NULL, NULL, NULL, NULL, 'Disetujui', NULL, NULL, 'pengawas', 'Disetujui', NULL, 'testPLP', 'Disetujui', NULL, 'Kalab', NULL, NULL, NULL, 'Belum Selesai', '2024-07-24 01:13:16', '2024-07-24 01:13:16', NULL, NULL, NULL, NULL),
(234, '9', '212', 'asdas', 'TMD 3A', 'TMD', '3', '1555', '55', '1610', NULL, NULL, '17', 'pengawas', NULL, 'Belum Upload', NULL, NULL, NULL, 'Belum Disetujui', NULL, NULL, 'Belum Disetujui', NULL, NULL, NULL, NULL, NULL, 'Belum Selesai', '2024-07-25 00:59:52', '2024-07-25 00:59:52', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pengajuan_detail`
--

CREATE TABLE `tbl_pengajuan_detail` (
  `id_pengajuan_detail` bigint(20) UNSIGNED NOT NULL,
  `kode_kegiatan` varchar(50) DEFAULT NULL,
  `kode_pekerjaan` varchar(50) DEFAULT NULL,
  `nama_pekerjaan` varchar(1000) DEFAULT NULL,
  `jam_pekerjaan` varchar(50) DEFAULT NULL,
  `batas_pekerja` varchar(50) DEFAULT NULL,
  `before_pekerjaan` varchar(50) DEFAULT NULL,
  `after_pekerjaan` varchar(50) DEFAULT NULL,
  `bukti_tambahan` varchar(50) DEFAULT NULL,
  `user_create` varchar(255) DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `uid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_pengajuan_detail`
--

INSERT INTO `tbl_pengajuan_detail` (`id_pengajuan_detail`, `kode_kegiatan`, `kode_pekerjaan`, `nama_pekerjaan`, `jam_pekerjaan`, `batas_pekerja`, `before_pekerjaan`, `after_pekerjaan`, `bukti_tambahan`, `user_create`, `user_update`, `uid`, `created_at`, `updated_at`) VALUES
(260, '9', 'K04', 'Laundry', '2000', '30', NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-25 00:59:52', '2024-07-25 00:59:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_prodi`
--

CREATE TABLE `tbl_prodi` (
  `id_prodi` bigint(20) UNSIGNED NOT NULL,
  `prodi` varchar(50) DEFAULT NULL,
  `uid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_prodi`
--

INSERT INTO `tbl_prodi` (`id_prodi`, `prodi`, `uid`, `created_at`, `updated_at`) VALUES
(8, 'TI', '287a0acf-6e11-46b2-8ed6-74c02d9401f2', '2024-07-08 05:51:00', '2024-07-08 05:51:00'),
(9, 'TMD', 'acfc7187-9661-4183-a009-a89304f53f30', '2024-07-08 05:51:00', '2024-07-08 05:51:00'),
(10, 'TMJ', '3527d6d9-25f3-4c7f-8ff7-2494b6aa07bb', '2024-07-08 05:51:00', '2024-07-08 05:51:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_setup_bertugas`
--

CREATE TABLE `tbl_setup_bertugas` (
  `id_setup_bertugas` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) DEFAULT NULL,
  `kode_user` varchar(50) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `tgl_bertugas` datetime DEFAULT NULL,
  `uid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_setup_bertugas`
--

INSERT INTO `tbl_setup_bertugas` (`id_setup_bertugas`, `id_user`, `kode_user`, `nama_user`, `role`, `tgl_bertugas`, `uid`, `created_at`, `updated_at`) VALUES
(11, 17, '007', 'pengawas', 'Pengawas', '2024-07-24 00:00:00', '768b6b15-678c-4a15-ada5-17383a323b00', '2024-07-24 00:52:01', '2024-07-24 00:52:01'),
(12, 4, '2222', 'testPLP', 'PLP', '2024-07-24 00:00:00', 'c641f5a4-5977-484b-8247-a477a19f65e5', '2024-07-24 00:52:10', '2024-07-24 00:52:10'),
(13, 18, '333', 'Kalab', 'Kepala Lab', '2024-07-24 00:00:00', '163420ee-8a8f-4fb8-81e1-5a6d721b92ab', '2024-07-24 00:52:19', '2024-07-24 00:52:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `kode_user` varchar(50) DEFAULT NULL,
  `nama_user` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `edit_password` varchar(30) DEFAULT '0',
  `token` varchar(50) DEFAULT NULL,
  `status_token` int(11) NOT NULL DEFAULT 0,
  `role` varchar(50) NOT NULL DEFAULT 'MAHASISWA',
  `ttd` varchar(255) DEFAULT NULL,
  `user_create` varchar(255) DEFAULT NULL,
  `user_update` varchar(255) DEFAULT NULL,
  `uid` char(36) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `kode_user`, `nama_user`, `email`, `password`, `edit_password`, `token`, `status_token`, `role`, `ttd`, `user_create`, `user_update`, `uid`, `created_at`, `updated_at`) VALUES
(4, '2222', 'testPLP', 'kevinrisqir21@gmail.com', '$2y$12$QhUxql1wnkDYvAiwzjs6Iulz1pMOl8NVKK0z8c/u7B78U680fqJGG', '1', 'EAkkJX8SztRa', 0, 'PLP', 'JNxqFk3AYO2UF11j6negExBmU4oOYTzKTobPCDNe.jpg', NULL, NULL, 'bbe08507-30e7-4926-9fb8-0f20e95f465f', '2024-05-06 01:36:47', '2024-07-24 00:55:03'),
(5, '2131232', 'test', 'asdas@mail.com', '$2y$12$/AMStPyQixHxIGlSu5cDluEdr8.RqWs5iYcD.az9gxh09SmO49qOK', '1', NULL, 0, 'PLP', 'nJU2Rhbi1hee16TnGBxyz3ike1JpuXih1PszMwvl.jpg', NULL, NULL, '57f3089b-de5f-4938-a063-5f5f411325ee', '2024-05-06 02:48:04', '2024-07-03 03:42:49'),
(6, '21322', 'asdassad', 'kevinrisqir21@gmail.com', '$2y$12$SwV/3pIMinG3EqF48fyqx.p6yEl9JJ21SwtsCc7XNpH0EFCR1olQa', '1', 'N50PuQl24TdF', 0, 'Kajur', 'PdwYV7zvkXnjRAqosYsi6dKIuldCZvRR5kVTDX0e.jpg', NULL, NULL, '5de282ef-3d87-4862-8819-ef9135c6350a', '2024-05-06 03:19:46', '2024-07-15 04:58:06'),
(9, '2174', 'Vin', 'kevinrisqir21@gmail.com', '$2y$12$zJcAAXJIogbvObYdTnHXE.3xS68PX9cmWximiNkvHKoyu72JppvFW', '0', 'cCUZwwUn0LCO', 1, 'Admin Prodi', NULL, NULL, NULL, '00eb4576-4742-4f89-a7d4-3160554365f9', '2024-05-09 02:46:34', '2024-07-15 02:31:22'),
(10, '123', 'gggg', '1@gmail.com', '$2y$12$8aTAAtC9dkZqgFJBZAe6qufowzVgsrXWW73.rwq0VmxEh58kxs2H6', '1', NULL, 0, 'Admin Prodi', NULL, NULL, NULL, '29cddfb3-cbf3-490f-93c5-fe98c1d73679', '2024-05-09 03:54:09', '2024-07-02 02:06:25'),
(11, '21312312123', 'asdasdas', 'a@gmail.com', '$2y$12$PRHJE4gp8f98crtcT28JAO3XlwI95d4UFwVL5pMmSCYhgkIiVdSRO', '1', NULL, 0, 'Kepala Lab', NULL, NULL, NULL, '00a93ef1-2a81-4247-bbf4-42d31906d914', '2024-05-09 23:52:49', '2024-07-02 06:47:16'),
(13, '122', 'testggg', 'add@gmail.com', '$2y$12$GkTY51FL0h2i.Q7Blgj9uuYu9/HM8ZwuDLeccZ8F4/zEsb6wO1Akq', '1', NULL, 0, 'Kajur', NULL, NULL, NULL, '999a6338-a01e-4f88-9874-2ad0880ce858', '2024-05-10 00:01:31', '2024-07-13 06:46:32'),
(16, '1232132121', 'test', 'asda@gmail.com', '$2y$12$NoDwIUX/YiEfpx36mvcLwe9Sbh4CoQg8OO30qOez0.PedzIR44FPu', '0', NULL, 0, 'KPS', 'Vutxg8PEdpWqiulRa18xfkhTqXeZRtApRPlYShB4.jpg', NULL, NULL, '8b4c3471-bde8-4383-bc7f-b1ff26a07ca5', '2024-06-03 23:00:15', '2024-06-03 23:00:15'),
(17, '007', 'pengawas', 'tes231@gmail.com', '$2y$12$nc7fH3rGz/qjoDfKXeGkP.hsJZZZCLt3SANYVCGsbQ1sEJBzA3LDG', '1', NULL, 0, 'Pengawas', 'D0UhSdNnCOL4btr2einJ0C2gt9KigE40AVHnvXuT.jpg', NULL, NULL, 'b8f4fbd6-2a9e-448b-8637-f6d1078d2351', '2024-06-04 01:22:43', '2024-07-24 00:54:42'),
(18, '333', 'Kalab', 'asdokaso@gmail.com', '$2y$12$sw0FrxfCq7nFmBhuPCj0nuf7GiY7mQWFz/Q2tAW57E7NKRPRj13a2', '1', NULL, 0, 'Kepala Lab', 'pyFxXU9j1ACZWTmHeeopBZAaJ7JnIEvV1zWgvQ0s.jpg', NULL, NULL, 'aa51fd3a-7000-45ab-8f8b-cf527fd79370', '2024-06-06 01:35:21', '2024-07-24 00:55:26'),
(19, '323', 'PLP TEST', 'asda@gmail.com', '$2y$12$xcllzktRXNGTypbUZlZdweQb1DIhc5OGCy1G1ugbnqTCCdjACHHbO', '1', NULL, 0, 'PLP', 'lCwrUr5QDxSVszpPb0qFnmyUehnPVRJVMTrJNtwf.jpg', NULL, NULL, 'e755ce23-6a52-4261-86c8-7bd8f932913c', '2024-06-06 05:09:49', '2024-07-07 21:26:39'),
(20, '777', 'KALAB', 'kalab88@gmail.com', '$2y$12$gL0lU4L70vps1Rit6j6Zp.UErL.z7pKKB.Cza6O8toVty9e.YtOvG', '1', NULL, 0, 'Kepala Lab', 'B1mNTXyQEN4B27g82viLWcm88WJpviCbJQlu20Yq.jpg', NULL, NULL, 'bf992460-7249-4232-b9ce-1336d9a7d838', '2024-06-06 05:11:05', '2024-07-23 00:45:47'),
(27, '512', 'Alter', 'pengawasalter@gmail.com', '$2y$12$2wb2lylPdtB3qL1eaeo9IucxB13crrW5yPt3D3TVi0yYgFLjic7lG', '0', NULL, 0, 'Pengawas', 'a2l57jJPdO6csXP9ZbVsRHFBWYB366lBI6ofQvbV.jpg', NULL, NULL, 'fca02cdf-fa3b-4464-ae2c-14fec518ecbc', '2024-06-21 02:49:49', '2024-06-21 02:49:49'),
(28, '222', '222', '222@gmail.com', '$2y$12$ZSw0T9dQbMIYlnHT.hL6WuZvLbley/zxUl5ndGu.5P45n85OY7Kvm', '1', NULL, 0, 'Dosen Pembimbing Akademik', NULL, NULL, NULL, 'de8b2369-5c21-40b5-bd11-c9cd1ef239c6', '2024-06-26 02:30:46', '2024-07-07 21:31:45'),
(29, '214', 'hh', 'test@gmail.com', '$2y$12$W/4gXkdxIeBLhIZaeeNsi.tSOeEbOKIDRdVImbKdK4Umg2oXBvDVq', '0', NULL, 0, 'KPS', NULL, NULL, NULL, '7d46e682-9117-4a22-87d5-7badd0eefa11', '2024-06-28 22:21:41', '2024-06-28 22:21:41'),
(30, '888', 'Pak IIk', 'kevinrisqir21@gmail.com', '$2y$12$IbVejU65HGGr2zyk26DtGuNGXNuUuch/uhN.jGjeWD81l2gNs1kUS', '1', NULL, 0, 'Kepala Lab', '8umO1DqvFlfBxGv4Vr6nOvQzD0sHmDGCPfiN4qsX.png', NULL, NULL, '31f23ff8-032f-4bd6-964e-e893ea4c97d7', '2024-07-07 23:34:58', '2024-07-07 23:43:02'),
(31, '123456', 'kps', 'tes@gmail.com', '$2y$12$PNVdmE6m30XyaltSSokzrOg2dnU5x54hkzjwIm2S.53kyGlOcIZaC', '1', NULL, 0, 'KPS', NULL, NULL, NULL, 'd296943c-feed-4e88-b296-02d755510269', '2024-07-12 00:41:48', '2024-07-12 00:43:01'),
(32, '1234', 'lutfi', 'lutfi@pnj.ac.id', '$2y$12$osf.nuP67sXHIfMV23RoYu4Mf8F7fLv9.P1Fds.n5d/vfJ19igsH6', '0', NULL, 0, 'Pengawas', '7SrsbY25buUSzHPwLPkWKvNnL3JCCyhIkxinQ11F.png', NULL, NULL, '8e6e1fc5-d07f-45ae-8396-0bc528a34268', '2024-07-23 00:30:29', '2024-07-23 00:30:29'),
(33, '2020202', 'Testpengawas2', 'kevinrisqir21@gmail.com', '$2y$12$acdDZnTGGV9YVY4RU3j2w.tmi.VjSmAKRQzCyRSHJBrndXhESryy.', '0', NULL, 0, 'Pengawas', 'He76YQgdnc3TlsFhey1ThXUiClKZSQetS8nBEXKp.png', NULL, NULL, '29177ce5-7e15-42a4-9684-7c3058f887df', '2024-07-23 00:34:03', '2024-07-23 00:34:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `tbl_form_bebas_kompen`
--
ALTER TABLE `tbl_form_bebas_kompen`
  ADD PRIMARY KEY (`id_bebas_kompen`);

--
-- Indeks untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD UNIQUE KEY `tbl_kelas_kelas_unique` (`kelas`);

--
-- Indeks untuk tabel `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  ADD PRIMARY KEY (`id_mahasiswa`),
  ADD UNIQUE KEY `tbl_mahasiswa_kode_user_unique` (`kode_user`);

--
-- Indeks untuk tabel `tbl_pekerjaan`
--
ALTER TABLE `tbl_pekerjaan`
  ADD PRIMARY KEY (`id_pekerjaan`),
  ADD UNIQUE KEY `tbl_pekerjaan_id_pekerjaan_kode_pekerjaan_unique` (`id_pekerjaan`,`kode_pekerjaan`);

--
-- Indeks untuk tabel `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  ADD PRIMARY KEY (`id_pengajuan`),
  ADD KEY `tbl_pengajuan_kode_kegiatan_index` (`kode_kegiatan`);

--
-- Indeks untuk tabel `tbl_pengajuan_detail`
--
ALTER TABLE `tbl_pengajuan_detail`
  ADD PRIMARY KEY (`id_pengajuan_detail`),
  ADD KEY `tbl_pengajuan_detail_kode_kegiatan_foreign` (`kode_kegiatan`);

--
-- Indeks untuk tabel `tbl_prodi`
--
ALTER TABLE `tbl_prodi`
  ADD PRIMARY KEY (`id_prodi`),
  ADD UNIQUE KEY `tbl_prodi_prodi_unique` (`prodi`);

--
-- Indeks untuk tabel `tbl_setup_bertugas`
--
ALTER TABLE `tbl_setup_bertugas`
  ADD PRIMARY KEY (`id_setup_bertugas`);

--
-- Indeks untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `tbl_user_kode_user_unique` (`kode_user`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tbl_form_bebas_kompen`
--
ALTER TABLE `tbl_form_bebas_kompen`
  MODIFY `id_bebas_kompen` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT untuk tabel `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `id_kelas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `tbl_mahasiswa`
--
ALTER TABLE `tbl_mahasiswa`
  MODIFY `id_mahasiswa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT untuk tabel `tbl_pekerjaan`
--
ALTER TABLE `tbl_pekerjaan`
  MODIFY `id_pekerjaan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengajuan`
--
ALTER TABLE `tbl_pengajuan`
  MODIFY `id_pengajuan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=235;

--
-- AUTO_INCREMENT untuk tabel `tbl_pengajuan_detail`
--
ALTER TABLE `tbl_pengajuan_detail`
  MODIFY `id_pengajuan_detail` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT untuk tabel `tbl_prodi`
--
ALTER TABLE `tbl_prodi`
  MODIFY `id_prodi` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tbl_setup_bertugas`
--
ALTER TABLE `tbl_setup_bertugas`
  MODIFY `id_setup_bertugas` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tbl_pengajuan_detail`
--
ALTER TABLE `tbl_pengajuan_detail`
  ADD CONSTRAINT `tbl_pengajuan_detail_kode_kegiatan_foreign` FOREIGN KEY (`kode_kegiatan`) REFERENCES `tbl_pengajuan` (`kode_kegiatan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
