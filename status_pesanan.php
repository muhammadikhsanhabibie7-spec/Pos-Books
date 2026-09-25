<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php");
    exit();
}

$id_customer = $_SESSION['user']['id_user'];
$query = mysqli_query($conn, "SELECT * FROM transaksi WHERE id_customer = '$id_customer' ORDER BY id_transaksi DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pemrosesan Barang - Inventory Warehouse</title>

    <!-- Google Fonts (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --font-family: 'Inter', system-ui, -apple-system, sans-serif;
            --bg-body: #f8fafc;
            --primary-dark: #0f172a;
            --accent-blue: #2563eb;
            --border-color: #e2e8f0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: #334155;
            min-height: 100vh;
        }

        .custom-card {
            border: 1px solid var(--border-color);
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }

        .table-custom th {
            background-color: #0f172a !important;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            padding: 1rem;
            border: none;
        }

        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.925rem;
            border-bottom: 1px solid var(--border-color);
        }

        .table-custom tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-outline-custom {
            border: 1px solid var(--border-color);
            color: #334155;
            background: #ffffff;
            border-radius: 10px;
            font-weight: 500;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        .btn-outline-custom:hover {
            background: #f1f5f9;
            color: var(--accent-blue);
            border-color: var(--accent-blue);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header Bagian Atas -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-truck-fast text-primary me-2"></i>Status Pemrosesan Barang</h3>
            <p class="text-muted small mb-0">Pantau riwayat transaksi dan status pengiriman pesanan Anda secara real-time.</p>
        </div>
        <a href="index.php" class="btn btn-outline-custom shadow-sm">
            <i class="fa-solid fa-house me-2"></i>Ke Katalog Produk
        </a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm py-3 mb-4">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-4 me-3"></i>
                <div>
                    <strong>Transaksi Berhasil!</strong> Pesanan Anda telah tercatat dan sedang diproses oleh tim Warehouse Admin.
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Konten Tabel Riwayat -->
    <div class="custom-card p-4">
        <?php if (mysqli_num_rows($query) > 0): ?>
            <div class="table-responsive">
                <table class="table table-custom align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="rounded-start-3">ID Transaksi</th>
                            <th>Tanggal</th>
                            <th>Pembayaran</th>
                            <th>Total Bayar</th>
                            <th>Status Pemrosesan</th>
                            <th class="rounded-end-3">Rincian Barang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <tr>
                            <td class="fw-bold text-dark">#TRX-<?= $row['id_transaksi'] ?></td>
                            <td class="text-secondary small"><?= date('d M Y, H:i', strtotime($row['tgl_transaksi'])) ?></td>
                            <td><span class="badge bg-light text-dark border px-2 py-1"><?= $row['metode_pembayaran'] ?></span></td>
                            <td class="fw-bold text-primary">Rp <?= number_format($row['total_bayar'], 0, ',', '.') ?></td>
                            <td>
                                <?php 
                                $status = $row['status'];
                                $badge = 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                                if ($status == 'Approved') $badge = 'bg-info-subtle text-info-emphasis border border-info-subtle';
                                if ($status == 'Dikirim') $badge = 'bg-primary-subtle text-primary-emphasis border border-primary-subtle';
                                if ($status == 'Selesai') $badge = 'bg-success-subtle text-success-emphasis border border-success-subtle';
                                if ($status == 'Ditolak') $badge = 'bg-danger-subtle text-danger-emphasis border border-danger-subtle';
                                ?>
                                <span class="badge <?= $badge ?> px-3 py-2 rounded-pill fw-semibold"><?= $status ?></span>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 text-secondary small">
                                    <?php 
                                    $id_tx = $row['id_transaksi'];
                                    $details = mysqli_query($conn, "SELECT detail_transaksi.*, buku.judul_buku 
                                                                    FROM detail_transaksi 
                                                                    JOIN buku ON detail_transaksi.id_buku = buku.id_buku 
                                                                    WHERE id_transaksi = '$id_tx'");
                                    while ($dt = mysqli_fetch_assoc($details)):
                                    ?>
                                        <li class="mb-1"><i class="fa-solid fa-book text-muted me-1 small"></i> <?= htmlspecialchars($dt['judul_buku']) ?> <span class="fw-semibold text-dark">(x<?= $dt['jumlah'] ?>)</span></li>
                                    <?php endwhile; ?>
                                </ul>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="fa-solid fa-box-open fa-3x mb-3 text-secondary opacity-50"></i>
                <p class="fw-semibold mb-1">Belum ada riwayat pemrosesan barang.</p>
                <p class="small text-muted mb-3">Pesanan yang Anda buat akan muncul di halaman ini.</p>
                <a href="index.php" class="btn btn-primary btn-sm px-4 py-2 rounded-3">Mulai Belanja Buku</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>