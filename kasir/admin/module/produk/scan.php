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
                'harga_jual'  => $produk['harga_jual'],
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
        .form-scan {
            max-width: 400px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<h2>📦 Scan Produk (Kamera atau Alat Scanner)</h2>

<!-- ✅ Input untuk alat scanner fisik -->
<div class="form-scan">
    <form id="manualScanForm" method="POST" action="scan.php">
        <label for="barcodeInput">Scan Barcode (Manual):</label>
        <input type="text" name="barcode" id="barcodeInput" class="form-control" autocomplete="off" autofocus required>
    </form>
</div>

<!-- ✅ Scanner Kamera -->
<div id="reader"></div>
<div class="loading" id="loadingText">Menunggu scan kamera...</div>

<!-- ✅ Form untuk kamera scanner -->
<form id="addToCartForm" method="POST" action="scan.php" style="display: none;">
    <input type="hidden" name="barcode" id="barcodeHiddenInput">
</form>

<script>
    // 🔁 Fokus terus di input manual (alat scanner)
    setInterval(() => {
        document.getElementById('barcodeInput').focus();
    }, 2000);

    // 🧠 Jika alat scanner tekan Enter, langsung submit
    document.getElementById('barcodeInput').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            document.getElementById('manualScanForm').submit();
        }
    });

    // 🔍 Kamera Scanner (html5-qrcode)
    let alreadyScanned = false;
    const html5QrCode = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });

    function onScanSuccess(decodedText) {
        if (alreadyScanned) return;
        alreadyScanned = true;

        document.getElementById('loadingText').innerText = "Barcode berhasil: " + decodedText;
        document.getElementById('barcodeHiddenInput').value = decodedText;

        html5QrCode.clear().then(() => {
            console.log("Kamera scanner dimatikan.");
        }).catch(console.error);

        setTimeout(() => {
            document.getElementById('addToCartForm').submit();
        }, 300);
    }

    function onScanFailure(error) {
        // Tidak tampilkan error ke user
    }

    html5QrCode.render(onScanSuccess, onScanFailure);
</script>

</body>
</html>
