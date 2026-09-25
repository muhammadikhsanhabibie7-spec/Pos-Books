<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['user']) || empty($_SESSION['cart']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.php");
    exit();
}

$id_customer = $_SESSION['user']['id_user'];
$alamat = mysqli_real_escape_string($conn, $_POST['alamat_pengiriman']);
$metode = $_POST['metode_pembayaran'];
$total = floatval($_POST['total_bayar']);

// Start Transaction Database
mysqli_begin_transaction($conn);

try {
    // 1. Insert Header Transaksi
    $query_tx = "INSERT INTO transaksi (id_customer, total_bayar, metode_pembayaran, alamat_pengiriman, status) 
                 VALUES ('$id_customer', '$total', '$metode', '$alamat', 'Pending')";
    mysqli_query($conn, $query_tx);
    $id_transaksi = mysqli_insert_id($conn);

    // 2. Insert Detail & Potong Stok Buku
    foreach ($_SESSION['cart'] as $id_buku => $qty) {
        $buku_res = mysqli_query($conn, "SELECT harga_jual, stok FROM buku WHERE id_buku = '$id_buku'");
        $buku = mysqli_fetch_assoc($buku_res);
        
        $harga = $buku['harga_jual'];
        $subtotal = $harga * $qty;

        // Insert Detail
        mysqli_query($conn, "INSERT INTO detail_transaksi (id_transaksi, id_buku, jumlah, harga_satuan, subtotal) 
                             VALUES ('$id_transaksi', '$id_buku', '$qty', '$harga', '$subtotal')");

        // Kurangi Stok Buku
        mysqli_query($conn, "UPDATE buku SET stok = stok - $qty WHERE id_buku = '$id_buku'");
    }

    // Commit Transaction
    mysqli_commit($conn);

    // Clear Cart
    unset($_SESSION['cart']);

    header("Location: status_pesanan.php?success=1");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Gagal memproses transaksi: " . $e->getMessage();
}
?>