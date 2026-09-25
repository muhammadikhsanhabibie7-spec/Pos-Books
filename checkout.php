<?php
session_start();
require_once "config/koneksi.php";

// Wajib Login Alur Beli
if (!isset($_SESSION['user'])) {
    header("Location: auth/login.php?redirect=checkout.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<div class="container my-5">
    <h2 class="mb-4 fw-bold"><i class="fa-solid fa-credit-card me-2"></i>Form Checkout Pembayaran</h2>
    
    <form action="proses_checkout.php" method="POST">
        <div class="row g-4">
            <!-- Form Alamat & Payment -->
            <div class="col-md-7">
                <div class="card border-0 shadow-sm p-4 mb-3">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-location-dot me-2"></i>Lokasi / Alamat Pengiriman</h5>
                    <div class="mb-3">
                        <label class="form-label">Nama Penerima</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['nama']) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Pengiriman Lengkap</label>
                        <textarea name="alamat_pengiriman" class="form-control" rows="3" required><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3"><i class="fa-solid fa-wallet me-2"></i>Pilih Metode Pembayaran</h5>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="cod" value="COD" checked onclick="toggleQris(false)">
                        <label class="form-check-label fw-bold" for="cod">
                            COD (Bayar di Tempat)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="metode_pembayaran" id="qris" value="QRIS" onclick="toggleQris(true)">
                        <label class="form-check-label fw-bold" for="qris">
                            Payment QRIS (Dummy)
                        </label>
                    </div>

                    <!-- Dummy QRIS Modal/Box -->
                    <div id="qrisBox" class="mt-3 text-center d-none border p-3 rounded bg-white">
                        <p class="small text-muted mb-2">Scan QRIS Dummy Di Bawah Ini untuk Konfirmasi Instant:</p>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=DUMMY_PAYMENT_1APOS" class="img-fluid border p-2">
                        <p class="text-success small mt-2 mb-0"><i class="fa-solid fa-circle-check"></i> Sistem Otomatis Berhasil saat Checkout Klik Submit!</p>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Order -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4">
                    <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>
                    <ul class="list-group list-group-flush mb-3">
                        <?php 
                        $total = 0;
                        $ids = implode(',', array_keys($_SESSION['cart']));
                        $result = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku IN ($ids)");
                        while ($row = mysqli_fetch_assoc($result)):
                            $qty = $_SESSION['cart'][$row['id_buku']];
                            $subtotal = $row['harga_jual'] * $qty;
                            $total += $subtotal;
                        ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <h6 class="my-0"><?= htmlspecialchars($row['judul_buku']) ?></h6>
                                <small class="text-muted"><?= $qty ?> x Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></small>
                            </div>
                            <span class="text-muted">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                        </li>
                        <?php endwhile; ?>
                        <li class="list-group-item d-flex justify-content-between px-0 fw-bold fs-5">
                            <span>Total Pembayaran</span>
                            <span class="text-primary">Rp <?= number_format($total, 0, ',', '.') ?></span>
                        </li>
                    </ul>
                    <input type="hidden" name="total_bayar" value="<?= $total ?>">
                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold"><i class="fa-solid fa-check-circle me-2"></i> Buat Pesanan</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function toggleQris(show) {
    const qrisBox = document.getElementById('qrisBox');
    if (show) {
        qrisBox.classList.remove('d-none');
    } else {
        qrisBox.classList.add('d-none');
    }
}
</script>
</body>
</html>