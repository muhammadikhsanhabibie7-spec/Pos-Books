-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Sep 2026 pada 14.44
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
-- Database: `db_pos_system_buku`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `id_kategori` int(11) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `judul_buku` varchar(200) NOT NULL,
  `harga_beli` decimal(12,2) NOT NULL,
  `harga_jual` decimal(12,2) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `gambar` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `buku`
--

INSERT INTO `buku` (`id_buku`, `isbn`, `id_kategori`, `id_jenis`, `id_supplier`, `judul_buku`, `harga_beli`, `harga_jual`, `stok`, `gambar`, `deskripsi`) VALUES
(1, '849-158-415', 1, 2, 2, 'Bulan Juli', 10.00, 500.00, 999, '1789909249_202.jpg', 'Bulan yang penuh kebahagian\r\n'),
(2, '999-555-888', 3, 3, 1, 'Belajar Komputer Kita Bisa', 10.00, 50.00, 199, '1789909344_180.jpg', 'Belajar Mengoprasikan Komputer lengkap '),
(3, '995-461-475', 1, 3, 2, 'Dongeng Lengkap Sang Kancil', 20000.00, 40000.00, 100, '1789992653_199.jpg', 'Dongeng sang kancil yang cerdas '),
(4, '649-458-158', 1, 1, 2, 'Laskar Pelangi', 100.00, 1000.00, 2500, '1789909459_161.jpg', 'Laskar Pelangi'),
(5, '415-451-453', 1, 2, 2, 'wujud tanpa suara', 900.00, 5000.00, 3000, '1789971751_947.jpg', 'wujud tanpa suara'),
(6, '116-498-116', 2, 1, 1, 'Kehidupan sebuah  ransel', 500.00, 10000.00, 44, '1789971882_569.jpg', 'Kehidupan sang ransel'),
(7, '213-456-746', 2, 2, 2, 'Tujuh', 22.00, 20000.00, 9, '1789971962_587.jpg', 'tujuh'),
(9, '418-454-155', 2, 2, 1, 'Kiri Sampai Sini', 100000.00, 250000.00, 260, '1789992543_999.jpg', 'kiri sampai sini\r\n'),
(10, '497-546-212', 1, 2, 1, 'Kecil nya dunia ku', 5000.00, 100000.00, 499, '1789992837_865.jpg', 'Kecil nya dunia ku'),
(11, '516-564-164', 3, 2, 2, 'buku computer vision Chapter1', 250000.00, 500000.00, 3, '1789992889_985.jpg', 'buku computer vision Chapter 1'),
(12, '621-044-761', 3, 2, 1, 'Kecerdasan Buatan dengan Deep Computer Vision ', 400000.00, 600000.00, 2, '1789993059_230.jpg', 'Kecerdasan Buatan dengan Deep Computer Vision Dr. Eng. Said Mirza Pahlevi');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE `detail_transaksi` (
  `id_detail` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_buku`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 10, 1, 100000.00, 100000.00),
(2, 2, 9, 1, 6000.00, 6000.00),
(3, 3, 2, 1, 50.00, 50.00),
(4, 4, 12, 1, 600000.00, 600000.00),
(5, 4, 11, 1, 500000.00, 500000.00),
(6, 5, 7, 1, 20000.00, 20000.00),
(7, 5, 6, 1, 10000.00, 10000.00),
(8, 6, 1, 1, 500.00, 500.00),
(9, 6, 11, 1, 500000.00, 500000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `jenis_buku`
--

CREATE TABLE `jenis_buku` (
  `id_jenis` int(11) NOT NULL,
  `nama_jenis` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jenis_buku`
--

INSERT INTO `jenis_buku` (`id_jenis`, `nama_jenis`) VALUES
(1, 'Novel'),
(2, 'Buku Teks'),
(3, 'Komik');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Fiksi'),
(2, 'Non-Fiksi'),
(3, 'Teknologi & Komputer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stok_opname`
--

CREATE TABLE `stok_opname` (
  `id_opname` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `stok_sistem` int(11) NOT NULL,
  `stok_fisik` int(11) NOT NULL,
  `selisih` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tgl_opname` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(100) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama_supplier`, `telepon`, `alamat`) VALUES
(1, 'PT Penerbit Nusantara', '0215551234', 'Jl. Merdeka No. 10, Jakarta'),
(2, 'CV Gramedia Media', '0215555678', 'Jl. Gajah Mada No. 45, Bandung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `tgl_transaksi` datetime DEFAULT current_timestamp(),
  `total_bayar` decimal(12,2) NOT NULL,
  `metode_pembayaran` enum('COD','QRIS') NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `status` enum('Pending','Approved','Dikirim','Selesai','Ditolak') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_customer`, `tgl_transaksi`, `total_bayar`, `metode_pembayaran`, `alamat_pengiriman`, `status`) VALUES
(1, 3, '2026-09-20 23:50:58', 100000.00, 'QRIS', 'Jl. Legend', 'Selesai'),
(2, 3, '2026-09-20 23:56:04', 6000.00, 'QRIS', 'Jl. Legend', 'Selesai'),
(3, 4, '2026-09-21 00:00:44', 50.00, 'COD', 'Jl. Lempuyangan', 'Selesai'),
(4, 5, '2026-09-21 05:31:44', 1100000.00, 'QRIS', 'Jl.Perumnas', 'Dikirim'),
(5, 2, '2026-09-21 05:38:54', 30000.00, 'COD', 'Jl. Supramen', 'Dikirim'),
(6, 8, '2026-09-21 05:42:44', 500500.00, 'QRIS', 'Jl. Keduri', 'Selesai');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id_user`, `nama`, `email`, `password`, `telepon`, `alamat`, `role`, `created_at`) VALUES
(2, 'naswa', 'awa@gmail.com', '$2y$10$GE6p9xwFEsIE5.MSNJZAse9GLMxpGW03RMhnkW7cpWoiPFBccd./e', '0987456', 'Jl. Supramen', 'customer', '2026-09-21 12:37:00'),
(3, 'ujang', 'jagung@gmail.com', '$2y$10$e5rci5IuyDib1dRbp8uuR.mshOLusiEhRkSyCaxg4py5IUfqj94ru', '011111', 'Jl. Legend', 'customer', '2026-09-21 06:40:38'),
(4, 'selen', 'selen@gmail.com', '$2y$10$PrKIxL6.fVRsHqBPF644Du75MAe7wRLZomqqQAApOxTa6f7KNdyBW', '0321456', 'Jl. Lempuyangan', 'customer', '2026-09-21 06:57:12'),
(5, 'akmal', 'mal@gmail.com', '$2y$10$UpY.dvLnpXekFixGTIqujOoRz3viVnQx80m.22rys72rtnP5E/93u', '0123456', 'Jl.Perumnas', 'customer', '2026-09-21 12:30:49'),
(6, 'admin', 'admin@gmail.com', '$2y$10$a3A2Tje9WCB.2Yz9.F6phuNI25Ht8FiMql4atpqzheoRxL2eAo3lS', '08123456', 'Jl.bsd Tangerang Selatan', 'admin', '2026-09-21 12:35:06'),
(8, 'Muhammad Ikhsan Habibie', 'muhammadikhsanhabibi@gmail.com', '$2y$10$zHjLA.WUFJB4cTdUIJYWbuv4.dYoYxRePf96G.t8MtILlszZXMJMO', '0456789', 'Jl. Keduri', 'customer', '2026-09-21 12:42:14');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_jenis` (`id_jenis`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indeks untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_transaksi` (`id_transaksi`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indeks untuk tabel `jenis_buku`
--
ALTER TABLE `jenis_buku`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `stok_opname`
--
ALTER TABLE `stok_opname`
  ADD PRIMARY KEY (`id_opname`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indeks untuk tabel `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `jenis_buku`
--
ALTER TABLE `jenis_buku`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `stok_opname`
--
ALTER TABLE `stok_opname`
  MODIFY `id_opname` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `buku`
--
ALTER TABLE `buku`
  ADD CONSTRAINT `buku_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_ibfk_2` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_buku` (`id_jenis`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_ibfk_3` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id_supplier`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stok_opname`
--
ALTER TABLE `stok_opname`
  ADD CONSTRAINT `stok_opname_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
