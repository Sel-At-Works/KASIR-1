<?php
session_start();
require '../../../config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['id_barang'], $_POST['nama_barang'], $_POST['harga_beli'], $_POST['gambar'])) {
    $id_barang = $_POST['id_barang'];
    $nama_barang = $_POST['nama_barang'];
    $harga_beli = $_POST['harga_beli'];
    $gambar = $_POST['gambar'];

    // Ambil stok dari database
    $stmt = $config->prepare("SELECT stok FROM barang WHERE id_barang = ?");
    $stmt->execute([$id_barang]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stok = $row ? $row['stok'] : 0;

    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Cek apakah produk sudah ada di keranjang
    $found = false;
    foreach ($_SESSION['keranjang'] as $key => $item) {
        if ($item['id_barang'] == $id_barang) {
            // Jika jumlah dalam keranjang >= stok, tolak
            if ($_SESSION['keranjang'][$key]['jumlah'] >= $stok) {
                echo '<script>alert("Jumlah dalam keranjang sudah sesuai dengan stok yang tersedia."); window.location="../../../index.php";</script>';
                exit;
            }

            // Tambahkan jumlah
            $_SESSION['keranjang'][$key]['jumlah'] += 1;
            $_SESSION['keranjang'][$key]['waktu'] = time();
            $found = true;
            break;
        }
    }

    // Kalau produk belum ada
    if (!$found) {
        // Batas maksimal 5 produk berbeda
        if (count($_SESSION['keranjang']) >= 5) {
            echo '<script>alert("Maksimal 5 produk berbeda di keranjang!"); window.location="../../../index.php";</script>';
            exit;
        }

        // Tambahkan produk baru
        $_SESSION['keranjang'][] = [
            'id_barang' => $id_barang,
            'nama_barang' => $nama_barang,
            'harga_beli' => $harga_beli,
            'gambar' => $gambar,
            'jumlah' => 1,
            'waktu' => time()
        ];
    }

    // Redirect ke keranjang
    header('Location: /KASIR 1/kasir/admin/module/produk/view_cart.php');
    exit;

} else {
    echo '<script>alert("Data produk tidak lengkap! Pastikan semua informasi sudah diisi.");</script>';
    echo '<script>window.location.href = "admin/module/produk/index.php";</script>';
    exit;
}
?>
