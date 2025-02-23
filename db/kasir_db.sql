-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Feb 2025 pada 05.20
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
-- Database: `kasir_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `proid` bigint(20) NOT NULL,
  `pronama` varchar(200) DEFAULT NULL,
  `projumlah` bigint(20) DEFAULT NULL,
  `proharga` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`proid`, `pronama`, `projumlah`, `proharga`) VALUES
(2, 'keyboard', 6, 50000),
(3, 'Mouse', 13, 23000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `trafaktur` varchar(200) NOT NULL,
  `tratanggal` date DEFAULT NULL,
  `trapelanggan` varchar(200) DEFAULT NULL,
  `tratotal` float DEFAULT NULL,
  `uangpembeli` int(11) NOT NULL,
  `tradiskon` float NOT NULL,
  `userid` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`trafaktur`, `tratanggal`, `trapelanggan`, `tratotal`, `uangpembeli`, `tradiskon`, `userid`) VALUES
('TRA0001', '2025-02-22', 'adit', 50000, 0, 0, 1),
('TRA0002', '2025-02-22', 'satria', 44000, 50000, 6000, 1),
('TRA0003', '2025-02-22', 'adit', 50000, 50000, 0, 1),
('TRA0004', '2025-02-22', 'tama', 36800, 40000, 9200, 1),
('TRA0005', '2025-02-22', 'tamatama', 23000, 30000, 0, 1),
('TRA0006', '2025-02-22', 'pratama', 73000, 80000, 0, 1),
('TRA0007', '2025-02-22', 'bintang', 44000, 50000, 6000, 1),
('TRA0008', '2025-02-22', 'tamatama', 23000, 30000, 0, 1),
('TRA0009', '2025-02-22', 'satria', 23000, 25000, 0, 1),
('TRA0010', '2025-02-22', 'satria', 23000, 24000, 0, 1),
('TRA0011', '2025-02-22', 'bintang', 50000, 50000, 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `tdid` bigint(20) NOT NULL,
  `trafaktur` varchar(200) DEFAULT NULL,
  `proid` bigint(20) DEFAULT NULL,
  `tdjumlah` bigint(20) DEFAULT NULL,
  `tdharga` float DEFAULT NULL,
  `tddiskon` float NOT NULL,
  `tdsubtotal` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`tdid`, `trafaktur`, `proid`, `tdjumlah`, `tdharga`, `tddiskon`, `tdsubtotal`) VALUES
(7, 'TRA0001', 2, 1, 50000, 0, 50000),
(10, 'TRA0002', 2, 1, 50000, 12, 44000),
(12, 'TRA0003', 2, 1, 50000, 0, 50000),
(13, 'TRA0004', 3, 2, 23000, 20, 36800),
(14, 'TRA0005', 3, 1, 23000, 0, 23000),
(15, 'TRA0006', 2, 1, 50000, 0, 50000),
(16, 'TRA0006', 3, 1, 23000, 0, 23000),
(17, 'TRA0007', 2, 1, 50000, 12, 44000),
(18, 'TRA0008', 3, 1, 23000, 0, 23000),
(19, 'TRA0009', 3, 1, 23000, 0, 23000),
(20, 'TRA0010', 3, 1, 23000, 0, 23000),
(21, 'TRA0011', 2, 1, 50000, 0, 50000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `userid` bigint(20) NOT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `level` enum('admin','petugas') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`userid`, `nama`, `username`, `password`, `level`) VALUES
(1, 'admin1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin'),
(2, 'satria adinata', 'petugas1', 'b53fe7751b37e40ff34d012c7774d65f', 'petugas'),
(3, 'bintang', 'bintang', 'c84258e9c39059a89ab77d846ddab909', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`proid`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`tdid`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `proid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `tdid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `userid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
