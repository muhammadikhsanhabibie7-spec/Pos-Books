<?php
session_start();
require_once "config/koneksi.php";

$id_buku = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = mysqli_query($conn, "SELECT buku.*, kategori.nama_kategori, jenis_buku.nama_jenis, supplier.nama_supplier 
                              FROM buku 
                              JOIN kategori ON buku.id_kategori = kategori.id_kategori 
                              JOIN jenis_buku ON buku.id_jenis = jenis_buku.id_jenis 
                              JOIN supplier ON buku.id_supplier = supplier.id_supplier 
                              WHERE buku.id_buku = $id_buku");

$buku = mysqli_fetch_assoc($query);

if (!$buku) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($buku['judul_buku']) ?> - Detail Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Katalog</a>
  </div>
</nav>

<div class="container my-4">
    <div class="card shadow border-0 p-4">
        <div class="row g-4">
            <div class="col-md-4 text-center">
                <img src="uploads/<?= !empty($buku['gambar']) ? $buku['gambar'] : 'no-image.jpg' ?>" class="img-fluid rounded shadow" style="max-height: 400px; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <span class="badge bg-info text-dark mb-2"><?= htmlspecialchars($buku['nama_kategori']) ?></span>
                <span class="badge bg-secondary mb-2"><?= htmlspecialchars($buku['nama_jenis']) ?></span>
                <h2 class="fw-bold mb-3"><?= htmlspecialchars($buku['judul_buku']) ?></h2>
                <h3 class="text-primary fw-bold mb-3">Rp <?= number_format($buku['harga_jual'], 0, ',', '.') ?></h3>
                
                <p><strong>Stok Tersedia:</strong> <?= $buku['stok'] ?> Pcs</p>
                <p><strong>Penerbit / Supplier:</strong> <?= htmlspecialchars($buku['nama_supplier']) ?></p>
                <hr>
                <h5 class="fw-bold">Deskripsi Buku:</h5>
                <p class="text-secondary"><?= nl2br(htmlspecialchars($buku['deskripsi'])) ?></p>
                
                <div class="mt-4">
                    <?php if ($buku['stok'] > 0): ?>
                        <a href="cart.php?action=add&id=<?= $buku['id_buku'] ?>" class="btn btn-success btn-lg"><i class="fa-solid fa-cart-plus me-2"></i> Tambah ke Keranjang</a>
                    <?php else: ?>
                        <button class="btn btn-danger btn-lg" disabled>Stok Habis</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>