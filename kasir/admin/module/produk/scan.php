<?php
session_start();
require '../../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    // Ambil data produk berdasarkan barcode
    $stmt = $config->prepare("SELECT * FROM barang WHERE id_barang = ?");
    $stmt->execute([$barcode]);
    $produk = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($produk && $produk['stok'] > 0) {
        $id_member = $_SESSION['admin']['id_member'] ?? 'guest';

        // Tambahkan ke session keranjang
        if (!isset($_SESSION['keranjang'])) $_SESSION['keranjang'] = [];

        $ditemukan = false;
        foreach ($_SESSION['keranjang'] as &$item) {
            if ($item['id_barang'] == $produk['id_barang']) {
                // CEK JUMLAH DI KERANJANG VS STOK
                if ($item['jumlah'] >= $produk['stok']) {
                    // Jika jumlah di keranjang sudah sama atau lebih dari stok, tolak
                    header("Location: view_cart.php?error=stok_limit");
                    exit;
                }
                $item['jumlah'] += 1;
                $item['total'] += $produk['harga_beli'];
                $ditemukan = true;
                break;
            }
        }

        if (!$ditemukan) {
            // Jika produk baru, cek juga stok
            if (1 > $produk['stok']) {
                header("Location: view_cart.php?error=stok_limit");
                exit;
            }
            $_SESSION['keranjang'][] = [
                'id_barang'   => $produk['id_barang'],
                'nama_barang' => $produk['nama_barang'],
                'harga_beli'  => $produk['harga_beli'],
                'gambar'      => $produk['gambar'],
                'jumlah'      => 1,
                'total'       => $produk['harga_beli'],
                'id_member'   => $id_member
            ];
        }

        // Tidak ada pengurangan stok & tidak ada penyimpanan ke tabel nota

        header("Location: view_cart.php?success=1");
    } else {
        $error = !$produk ? 'notfound' : 'stok_kosong';
        header("Location: view_cart.php?error=$error");
    }

    exit;
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
        }
        #reader {
            width: 100%;
            max-width: 500px;
            margin: auto;
        }
        .loading {
            text-align: center;
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>📷 Scan Produk untuk Tambah ke Keranjang</h2>

<div id="reader"></div>
<div class="loading" id="loadingText">Menunggu scan barcode...</div>

<form id="addToCartForm" method="POST" action="scan.php" style="display: none;">
    <input type="hidden" name="barcode" id="barcodeInput">
</form>

<script>
    let alreadyScanned = false;
    let html5QrCode = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
    let lastScan = ""; // Ini deklarasi variabel lastScan

    function onScanSuccess(decodedText) {
        // Cegah pemindaian duplikat
        if (alreadyScanned) return;
        alreadyScanned = true;

        document.getElementById('loadingText').innerText = "Barcode berhasil: " + decodedText;
        document.getElementById('barcodeInput').value = decodedText;

        html5QrCode.clear().then(() => {
            console.log("Scanner stopped.");
        }).catch(console.error);

        setTimeout(() => {
            document.getElementById('addToCartForm').submit();
        }, 300);
    }

    function onScanFailure(error) {
        // Tidak perlu menampilkan error terus-menerus
    }

    // Inisialisasi scanner barcode
    html5QrCode.render(onScanSuccess, onScanFailure);

    // Event listener untuk memastikan hanya scan yang unik yang diproses
    document.querySelector("#barcode_input").addEventListener("change", function () {
        if (this.value === lastScan) return;
        lastScan = this.value;

        // Kirim form atau fetch (misalnya menggunakan form submit atau AJAX)
        document.getElementById('addToCartForm').submit();
    });
</script>


</body>
</html>
