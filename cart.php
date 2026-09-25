<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($action == 'add' && $id > 0) {
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += 1;
    } else {
        $_SESSION['cart'][$id] = 1;
    }
    header("Location: cart.php");
    exit();
}

if ($action == 'remove' && $id > 0) {
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

if ($action == 'update' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['qty']) && is_array($_POST['qty'])) {
        foreach ($_POST['qty'] as $buku_id => $qty) {
            $buku_id = intval($buku_id);
            $qty = intval($qty);
            if ($qty <= 0) {
                unset($_SESSION['cart'][$buku_id]);
            } else {
                $_SESSION['cart'][$buku_id] = $qty;
            }
        }
    }
    header("Location: cart.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Inventory Warehouse</title>

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
            --accent-emerald: #059669;
            --border-color: #e2e8f0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-body);
            color: #334155;
            margin: 0;
            padding: 0;
        }

        /* Top Announcement Utility Bar */
        .top-announcement-bar {
            background-color: var(--primary-dark);
            color: #94a3b8;
            font-size: 0.775rem;
            padding: 6px 0;
        }

        /* Navbar Profesional */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.03);
        }

        .navbar-brand {
            color: var(--primary-dark) !important;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .navbar-nav .nav-link {
            color: #475569 !important;
            font-weight: 500;
            font-size: 0.925rem;
            padding: 0.5rem 0.85rem !important;
            transition: color 0.15s ease-in-out;
        }

        .navbar-nav .nav-link:hover, .navbar-nav .nav-link.active {
            color: var(--accent-blue) !important;
        }

        /* Card Container Styling */
        .card-custom {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        }

        /* Tabel Kustom */
        .table-custom thead th {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            border-bottom: 2px solid var(--border-color);
            padding: 12px 16px;
        }

        .table-custom tbody td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        /* Footer Korporat */
        footer {
            background-color: #ffffff;
            border-top: 1px solid var(--border-color);
            color: #64748b;
            padding: 3rem 0;
            margin-top: 5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>

<!-- Top Announcement Utility Bar -->
<div class="top-announcement-bar d-none d-md-block">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <i class="fa-solid fa-shield-halved text-success me-1"></i> Sistem Manajemen Gudang & Jaminan Produk Original 100%
        </div>
        <div class="d-flex align-items-center gap-3">
            <span><i class="fa-solid fa-headset me-1 text-primary"></i> Layanan Bantuan: support@inventorywarehouse.test</span>
        </div>
    </div>
</div>

<!-- Main Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom sticky-top py-3">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center fs-5" href="index.php">
        <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 36px; height: 36px;">
            <i class="fa-solid fa-boxes-stacked fs-6"></i>
        </div>
        <span>Inventory Warehouse</span>
    </a>
    <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto ms-lg-3">
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fa-solid fa-store me-1"></i> Katalog Buku</a></li>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'customer'): ?>
            <li class="nav-item"><a class="nav-link" href="status_pesanan.php"><i class="fa-solid fa-receipt me-1"></i> Status Pesanan</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
        <a href="cart.php" class="btn btn-dark position-relative btn-sm px-3 py-2 fw-medium">
            <i class="fa-solid fa-cart-shopping me-1"></i> Keranjang
            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white">
                    <?= array_sum($_SESSION['cart']) ?>
                </span>
            <?php endif; ?>
        </a>
        <?php if (isset($_SESSION['user'])): ?>
            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle btn-sm px-3 py-2 fw-semibold d-flex align-items-center" type="button" data-bs-toggle="dropdown">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px; font-size: 0.75rem;">
                        <?= strtoupper(substr($_SESSION['user']['nama'], 0, 1)) ?>
                    </div>
                    <span><?= htmlspecialchars($_SESSION['user']['nama']); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2 rounded-3">
                    <?php if ($_SESSION['user']['role'] == 'admin'): ?>
                        <li><a class="dropdown-item rounded-2 fw-bold text-primary py-2 mb-1" href="admin/index.php"><i class="fa-solid fa-gauge me-2"></i>Dashboard Admin</a></li>
                        <li><hr class="dropdown-divider my-1"></li>
                    <?php endif; ?>
                    <li><a class="dropdown-item rounded-2 text-danger py-2 fw-medium" href="auth/logout.php"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</a></li>
                </ul>
            </div>
        <?php else: ?>
            <a href="auth/login.php" class="btn btn-primary btn-sm px-4 py-2 fw-bold shadow-sm">Login / Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<!-- Main Container -->
<div class="container my-5" style="min-height: 55vh;">
    
    <!-- Header Section -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1"><i class="fa-solid fa-cart-shopping me-2 text-primary"></i>Keranjang Belanja</h3>
            <p class="text-muted small mb-0">Kelola kuantitas buku pilihan Anda sebelum melanjutkan ke proses checkout.</p>
        </div>
        <div>
            <a href="index.php" class="btn btn-outline-secondary btn-sm px-3 py-2 fw-medium"><i class="fa-solid fa-arrow-left me-1"></i> Lanjut Belanja</a>
        </div>
    </div>
    
    <?php if (!empty($_SESSION['cart'])): ?>
        <form action="cart.php?action=update" method="POST">
            <div class="card card-custom p-4 mb-4">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Produk Buku</th>
                                <th>Harga Satuan</th>
                                <th style="width: 140px;">Jumlah Unit</th>
                                <th>Subtotal</th>
                                <th class="text-center" style="width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            $ids = implode(',', array_map('intval', array_keys($_SESSION['cart'])));
                            $result = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku IN ($ids)");
                            
                            if ($result && mysqli_num_rows($result) > 0):
                                while ($row = mysqli_fetch_assoc($result)):
                                    $id_buku = $row['id_buku'];
                                    $qty = isset($_SESSION['cart'][$id_buku]) ? $_SESSION['cart'][$id_buku] : 1;
                                    $subtotal = $row['harga_jual'] * $qty;
                                    $total += $subtotal;
                                    
                                    $nama_gambar = $row['gambar'] ?? '';
                                    if (!empty($nama_gambar) && file_exists("uploads/" . $nama_gambar)) {
                                        $src_gambar = "uploads/" . $nama_gambar;
                                    } else {
                                        $src_gambar = "https://placehold.co/100x130/e2e8f0/1e293b?text=No+Img";
                                    }
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $src_gambar ?>" 
                                             onerror="this.onerror=null; this.src='https://placehold.co/100x130/e2e8f0/1e293b?text=No+Img';" 
                                             width="50" height="65" class="me-3 rounded object-fit-cover shadow-sm border" alt="Cover">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($row['judul_buku']) ?></h6>
                                            <span class="text-muted small">Stok Gudang: <?= $row['stok'] ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="fw-medium">Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></td>
                                <td>
                                    <input type="number" name="qty[<?= $id_buku ?>]" value="<?= $qty ?>" class="form-control form-control-sm text-center fw-bold" min="1" max="<?= $row['stok'] ?>">
                                </td>
                                <td class="fw-bold text-success">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                <td class="text-center">
                                    <a href="cart.php?action=remove&id=<?= $id_buku ?>" class="btn btn-sm btn-outline-danger border-0 py-1 px-2" title="Hapus Item"><i class="fa-solid fa-trash-can"></i></a>
                                </td>
                            </tr>
                            <?php 
                                endwhile; 
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Footer Tabel / Tombol Aksi & Total -->
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-4 pt-3 border-top">
                    <button type="submit" class="btn btn-outline-dark btn-sm px-4 py-2 fw-medium">
                        <i class="fa-solid fa-arrows-rotate me-1"></i> Perbarui Keranjang
                    </button>
                    <div class="text-md-end">
                        <span class="text-muted small d-block">Total Pembayaran Keseluruhan:</span>
                        <h3 class="fw-bold text-success mb-0">Rp <?= number_format($total, 0, ',', '.') ?></h3>
                    </div>
                </div>
            </div>
        </form>

        <!-- Tombol Lanjut Checkout -->
        <div class="d-flex justify-content-end">
            <a href="checkout.php" class="btn btn-primary btn-lg px-5 fw-bold shadow-sm py-3">
                <i class="fa-solid fa-cash-register me-2"></i> Lanjut ke Checkout Pesanan
            </a>
        </div>

    <?php else: ?>
        <div class="card card-custom p-5 text-center border-0">
            <div class="py-4">
                <div class="text-muted mb-3">
                    <i class="fa-solid fa-cart-shopping fa-3x opacity-50"></i>
                </div>
                <h4 class="fw-bold text-dark">Keranjang Belanja Anda Kosong</h4>
                <p class="text-muted small mb-4">Belum ada item buku yang ditambahkan ke keranjang belanja Anda saat ini.</p>
                <a href="index.php" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-store me-2"></i> Jelajahi Katalog Buku
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<!-- Footer Korporat -->
<footer>
    <div class="container text-center">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6 text-md-start">
                <span class="fw-bold text-dark"><i class="fa-solid fa-boxes-stacked text-primary me-2"></i>Inventory Warehouse Management System</span>
                <p class="small text-muted mb-0 mt-1">Platform terpadu pengelolaan inventaris gudang dan transaksi e-commerce buku.</p>
            </div>
            <div class="col-md-6 text-md-end text-muted small">
                <p class="mb-0">&copy; <?= date('Y') ?> Inventory Warehouse. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>