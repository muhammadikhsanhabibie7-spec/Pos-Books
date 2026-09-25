<?php
session_start();
require_once "config/koneksi.php";

// Filter Search & Kategori
$search         = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$kategori_filter = isset($_GET['kategori']) ? $_GET['kategori'] : '';

$query_sql = "SELECT buku.*, kategori.nama_kategori, jenis_buku.nama_jenis 
              FROM buku 
              JOIN kategori ON buku.id_kategori = kategori.id_kategori 
              JOIN jenis_buku ON buku.id_jenis = jenis_buku.id_jenis WHERE 1=1";

if (!empty($search)) {
    $query_sql .= " AND buku.judul_buku LIKE '%$search%'";
}
if (!empty($kategori_filter)) {
    $query_sql .= " AND buku.id_kategori = '$kategori_filter'";
}

$query_sql .= " ORDER BY buku.id_buku DESC";
$result_buku   = mysqli_query($conn, $query_sql);
$kategori_res  = mysqli_query($conn, "SELECT * FROM kategori");

// Hitung total produk ditemukan
$total_produk  = mysqli_num_rows($result_buku);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku & E-Commerce - Inventory Warehouse</title>

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

        /* Top Bar Korporat */
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

        /* Hero Banner Premium */
        .hero-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 4.5rem 0 3.5rem 0;
            margin-bottom: 2.5rem;
            position: relative;
            overflow: hidden;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #2563eb, #38bdf8, #059669);
        }

        /* Search & Filter Form Card */
        .search-card {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* Filter Chips / Kategori Pills */
        .category-chips-container {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
            scrollbar-width: thin;
        }

        .chip-filter {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            color: #475569;
            font-size: 0.825rem;
            font-weight: 500;
            padding: 6px 16px;
            border-radius: 50rem;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }

        .chip-filter:hover, .chip-filter.active {
            background-color: var(--accent-blue);
            border-color: var(--accent-blue);
            color: #ffffff;
        }

        /* Product Card Enterprise */
        .card-product {
            border: 1px solid var(--border-color);
            border-radius: 14px;
            background-color: #ffffff;
            transition: all 0.25s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            overflow: hidden;
        }

        .card-product:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.07), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #cbd5e1;
        }

        .card-img-wrapper {
            position: relative;
            background-color: #f1f5f9;
            height: 260px;
            overflow: hidden;
        }

        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card-product:hover .card-img-top {
            transform: scale(1.05);
        }

        .badge-category-float {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(6px);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        .price-tag {
            color: var(--accent-emerald);
            font-weight: 800;
            font-size: 1.15rem;
            letter-spacing: -0.3px;
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
        <li class="nav-item"><a class="nav-link active" href="index.php"><i class="fa-solid fa-store me-1 text-primary"></i> Katalog Buku</a></li>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'customer'): ?>
            <li class="nav-item"><a class="nav-link" href="status_pesanan.php"><i class="fa-solid fa-receipt me-1"></i> Status Pesanan</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
        <a href="cart.php" class="btn btn-outline-dark position-relative btn-sm px-3 py-2 fw-medium">
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

<!-- Hero Section & Instant Search -->
<div class="hero-section">
    <div class="container px-4">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <span class="badge bg-primary bg-opacity-50 text-info mb-3 px-3 py-2 rounded-pill fw-semibold border border-primary border-opacity-25">
                    <i class="fa-solid fa-bullhorn me-1"></i> Pusat Referensi & Katalog Gudang Terlengkap
                </span>
                <h1 class="fw-bold display-6 mb-3 tracking-tight">Temukan Buku & Literasi Pilihan Anda</h1>
                <p class="text-white-50 mb-4 fs-6 mx-auto" style="max-width: 600px;">
                    Jelajahi ribuan inventaris buku berkualitas tinggi dengan sistem pemesanan terintegrasi langsung dari pusat gudang.
                </p>
                
                <!-- Search Box Card -->
                <div class="search-card">
                    <form action="index.php" method="GET" class="row g-2 align-items-center">
                        <div class="col-lg-6 col-md-5">
                            <div class="input-group input-group-lg bg-white rounded-3 overflow-hidden border-0">
                                <span class="input-group-text bg-white border-0 text-muted ps-3"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" name="search" class="form-control border-0 shadow-none ps-0" placeholder="Cari judul buku atau penulis..." value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4">
                            <select name="kategori" class="form-select form-select-lg bg-white border-0 shadow-none rounded-3 text-secondary" style="font-size: 0.95rem;">
                                <option value="">Semua Kategori Buku</option>
                                <?php 
                                mysqli_data_seek($kategori_res, 0);
                                while ($kat = mysqli_fetch_assoc($kategori_res)): 
                                ?>
                                    <option value="<?= $kat['id_kategori'] ?>" <?= $kategori_filter == $kat['id_kategori'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($kat['nama_kategori']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-3 py-2">
                                <i class="fa-solid fa-filter me-1"></i> Cari
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Main Catalog Container -->
<div class="container mb-5">
    
    <!-- Quick Category Filter Chips Bar -->
    <div class="mb-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <span class="text-uppercase text-muted fw-bold" style="font-size: 0.725rem; letter-spacing: 0.8px;">Filter Kategori Cepat</span>
            <?php if(!empty($kategori_filter) || !empty($search)): ?>
                <a href="index.php" class="text-danger text-decoration-none small fw-semibold"><i class="fa-solid fa-rotate-left me-1"></i> Reset Filter</a>
            <?php endif; ?>
        </div>
        <div class="category-chips-container">
            <a href="index.php" class="chip-filter <?= empty($kategori_filter) ? 'active' : '' ?>">Semua Produk</a>
            <?php 
            mysqli_data_seek($kategori_res, 0);
            while ($kat_chip = mysqli_fetch_assoc($kategori_res)): 
            ?>
                <a href="index.php?kategori=<?= $kat_chip['id_kategori'] ?><?= !empty($search) ? '&search='.urlencode($search) : '' ?>" 
                   class="chip-filter <?= $kategori_filter == $kat_chip['id_kategori'] ? 'active' : '' ?>">
                    <?= htmlspecialchars($kat_chip['nama_kategori']) ?>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Header Grid Info -->
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold text-dark m-0"><i class="fa-solid fa-book-bookmark text-primary me-2"></i>Katalog Buku Tersedia</h4>
            <span class="text-muted small">Menampilkan <strong><?= $total_produk ?></strong> item buku aktif di gudang</span>
        </div>
    </div>

    <!-- Grid Produk -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <?php if ($total_produk > 0): ?>
            <?php while ($buku = mysqli_fetch_assoc($result_buku)): ?>
                <?php 
                    $nama_gambar = $buku['gambar'] ?? '';
                    if (!empty($nama_gambar) && file_exists("uploads/" . $nama_gambar)) {
                        $src_gambar = "uploads/" . $nama_gambar;
                    } else {
                        $src_gambar = "https://placehold.co/300x400/e2e8f0/1e293b?text=No+Cover";
                    }
                ?>
                <div class="col">
                    <div class="card card-product h-100 d-flex flex-column">
                        <div class="card-img-wrapper">
                            <span class="badge-category-float shadow-sm"><?= htmlspecialchars($buku['nama_kategori']) ?></span>
                            <img src="<?= $src_gambar ?>" 
                                 onerror="this.onerror=null; this.src='https://placehold.co/300x400/e2e8f0/1e293b?text=No+Cover';" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($buku['judul_buku']) ?>">
                        </div>
                        <div class="card-body d-flex flex-column p-3">
                            <div class="text-muted small fw-medium mb-1 text-truncate"><?= htmlspecialchars($buku['nama_jenis']) ?></div>
                            <h5 class="card-title text-truncate fw-bold text-dark mb-2" title="<?= htmlspecialchars($buku['judul_buku']) ?>">
                                <?= htmlspecialchars($buku['judul_buku']) ?>
                            </h5>
                            <div class="mb-3">
                                <span class="price-tag">Rp <?= number_format($buku['harga_jual'], 0, ',', '.') ?></span>
                            </div>
                            <div class="mt-auto d-grid gap-2">
                                <a href="detail_buku.php?id=<?= $buku['id_buku'] ?>" class="btn btn-outline-dark btn-sm fw-medium py-1.5">
                                    <i class="fa-solid fa-eye me-1"></i> Detail Buku
                                </a>
                                <a href="cart.php?action=add&id=<?= $buku['id_buku'] ?>" class="btn btn-primary btn-sm fw-bold shadow-sm py-1.5">
                                    <i class="fa-solid fa-cart-plus me-1"></i> Beli Barang
                                </a>
                            </div>
                        </div>
                        <div class="card-footer bg-light border-top border-0 py-2 px-3 d-flex justify-content-between align-items-center">
                            <span class="text-muted" style="font-size: 0.75rem;">Stok Gudang:</span>
                            <span class="badge <?= $buku['stok'] > 0 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' ?> fw-semibold px-2 py-1">
                                <?= $buku['stok'] > 0 ? $buku['stok'] . ' Unit' : 'Habis' ?>
                            </span>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <div class="card card-product p-5 border-0 text-center shadow-none bg-white mx-auto" style="max-width: 500px;">
                    <div class="text-muted mb-3">
                        <i class="fa-solid fa-book-open-reader fa-3x opacity-50"></i>
                    </div>
                    <h5 class="fw-bold text-dark">Buku Tidak Ditemukan</h5>
                    <p class="text-muted small mb-4">Maaf, kata kunci pencarian atau filter kategori yang Anda pilih tidak menghasilkan data buku.</p>
                    <div>
                        <a href="index.php" class="btn btn-primary btn-sm px-4 fw-bold shadow-sm">Reset Filter Katalog</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
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