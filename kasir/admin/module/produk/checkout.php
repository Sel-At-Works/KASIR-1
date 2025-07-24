<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require '../../../config.php';

if (!isset($_POST['checkout']) || empty($_POST['checkout'])) {
    echo "Tidak ada data yang diproses.";
    exit;
}

$keranjang = $_SESSION['keranjang'] ?? [];
$checkoutBarang = [];
$checkoutIndexes = $_POST['checkout'];

foreach ($checkoutIndexes as $index) {
    if (isset($keranjang[$index])) {
        $checkoutBarang[] = $keranjang[$index];
    }
}

$id_member = $_POST['id_member'] ?? 0;
$phone_num = $_POST['phone'] ?? 0;
$tanggal_input = date('Y-m-d H:i:s');
$periode = date('Ym');
$used_poin = $_POST['diskonPoin'] ?? 0;
$bayar = $_POST['bayar'] ?? 0;

if ($bayar <= 0) {
    echo "Jumlah bayar tidak boleh kurang dari atau sama dengan 0.";
    exit;
}

$items = [];
$totalSeluruh = 0;
$totalDiskonPersen = 0;
$totalSetelahDiskon = 0;
$nama_member = '';
$diskonPersen = 0;

try {
    $config->beginTransaction();
    // Buat satu nota utama
    $insertNotaUtama = $config->prepare("INSERT INTO nota_utama (id_member, tanggal_input, periode, total, diskon, bayar, kembalian) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $totalSeluruhTemp = 0;
    foreach ($checkoutIndexes as $index) {
        if (!isset($keranjang[$index])) continue;
        $item = $keranjang[$index];
        $jumlah = max(1, intval($item['jumlah']));
      $harga_beli = isset($item['harga_jual']) ? $item['harga_jual'] : $item['harga_beli'];
        $totalSeluruhTemp += (isset($item['harga_jual']) ? $item['harga_jual'] : $item['harga_beli']) * $jumlah;

    }
    // Hitung diskon dan total setelah diskon
    $diskonPersen = 0;
    if ($used_poin == 20) $diskonPersen = 0.2;
    else if ($used_poin == 30) $diskonPersen = 0.3;
    else if ($used_poin == 40) $diskonPersen = 0.4;
    $totalDiskonPersen = $diskonPersen * $totalSeluruhTemp;
    $totalSetelahDiskon = $totalSeluruhTemp - $totalDiskonPersen;
    if ($totalSetelahDiskon < 0) $totalSetelahDiskon = 0;
    $kembalian = $bayar - $totalSetelahDiskon;
    if ($kembalian < 0) $kembalian = 0;
    $insertNotaUtama->execute([
        $id_member,
        $tanggal_input,
        $periode,
        $totalSeluruhTemp,
        $totalDiskonPersen,
        $bayar,
        $kembalian
    ]);
    $idNota = $config->lastInsertId();
    // Simpan detail produk ke nota
    foreach ($checkoutIndexes as $index) {
        if (!isset($keranjang[$index])) continue;
        $item = $keranjang[$index];
        $id_barang = $item['id_barang'];
        $nama_barang = $item['nama_barang'];
        $jumlah = max(1, intval($item['jumlah']));
      $harga = isset($item['harga_jual']) ? $item['harga_jual'] : $item['harga_beli'];
        $total = (isset($item['harga_jual']) ? $item['harga_jual'] : $item['harga_beli']) * $jumlah;
        $cekStok = $config->prepare("SELECT stok FROM barang WHERE id_barang = ?");
        $cekStok->execute([$id_barang]);
        $stokTersedia = $cekStok->fetchColumn();
        if ($stokTersedia < $jumlah) {
            throw new Exception("Stok untuk '$nama_barang' tidak mencukupi (tersisa $stokTersedia). ");
        }
        $update = $config->prepare("UPDATE barang SET stok = stok - :jumlah WHERE id_barang = :id");
        $update->execute([
            ':jumlah' => $jumlah,
            ':id' => $id_barang
        ]);
        $item['jumlah'] = $jumlah;
        $items[] = $item;
        if ($phone_num != null) {
            $ambilIdMember = $config->prepare("SELECT id FROM member1 WHERE phone = ?");
            $ambilIdMember->execute([$phone_num]);
            $id_member = $ambilIdMember->fetchColumn();
            if (!$id_member) {
                throw new Exception("Member dengan nomor telepon '$phone_num' tidak ditemukan.");
            }
        }
        $insert = $config->prepare("INSERT INTO nota (id_nota_utama, id_barang, id_member, jumlah, total, tanggal_input, periode) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([
            $idNota,
            $id_barang,
            $id_member,
            $jumlah,
            $total,
            $tanggal_input,
            $periode
        ]);
        if ($id_member) {
            $point_baru = $totalSeluruhTemp / 1000;
            if ($point_baru > 0) {
                $updatePoint = $config->prepare("UPDATE member1 SET point = point + :jumlah WHERE id = :id");
                $updatePoint->execute([
                    ':jumlah' => $point_baru,
                    ':id' => $id_member
                ]);
            }
        }
        unset($_SESSION['keranjang'][$index]);
    }
    $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    $config->commit();
} catch (Exception $e) {
    $config->rollBack();
    echo "Terjadi kesalahan: " . $e->getMessage();
    exit;
}

// Pastikan $totalSeluruh sudah diisi sebelum digunakan
$totalSeluruh = $totalSeluruhTemp;
if ($phone_num) {
    $stmt = $config->prepare("SELECT id, point, name, diskon FROM member1 WHERE phone = ?");
    $stmt->execute([$phone_num]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($member) {
        $id_member = $member['id'];
        $nama_member = $member['name'];
        $diskonPersen = 0;
        if ($used_poin == 20) {
            $diskonPersen = 0.2;
        } else if ($used_poin == 30) {
            $diskonPersen = 0.3;
        } else if ($used_poin == 40) {
            $diskonPersen = 0.4;
        }
        $totalDiskonPersen = $diskonPersen * $totalSeluruh;
        $totalSetelahDiskon = $totalSeluruh - $totalDiskonPersen;
        if ($totalSetelahDiskon < 0) $totalSetelahDiskon = 0;
        if ($used_poin > 0) {
            $updatePoin = $config->prepare("UPDATE member1 SET point = point - ? WHERE id = ?");
            $updatePoin->execute([$used_poin, $id_member]);
        }
    } else {
        $totalSetelahDiskon = $totalSeluruh;
        $totalDiskonPersen = 0;
        $diskonPersen = 0;
    }
} else {
    $totalSetelahDiskon = $totalSeluruh;
    $totalDiskonPersen = 0;
    $diskonPersen = 0;
}
// Ambil total setelah diskon dari POST jika ada (dari form transaksi)
if (isset($_POST['totalSetelahDiskonInput']) && is_numeric($_POST['totalSetelahDiskonInput'])) {
    $totalSetelahDiskon = (int)$_POST['totalSetelahDiskonInput'];
    // Update juga totalDiskonPersen jika inputan ada
    $totalDiskonPersen = $totalSeluruh - $totalSetelahDiskon;
}
$bayar = intval($bayar);
$kembalian = $bayar - $totalSetelahDiskon;
if ($kembalian < 0) $kembalian = 0;
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f8f9fa;
            font-family: monospace;
        }

        .struk {
            background: #fff;
            border: 1px dashed #ccc;
            padding: 20px;
            max-width: 400px;
            margin: 50px auto;
        }

        .struk h4 {
            text-align: center;
            font-weight: bold;
        }

        .struk .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .item {
            display: flex;
            justify-content: space-between;
        }

        .total {
            font-weight: bold;
        }

        .print-btn {
            display: block;
            margin: 20px auto;
        }

        @media print {
          body * {
            visibility: hidden;
          }
          .struk, .struk * {
            visibility: visible;
          }
          .struk {
            position: fixed;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 400px;
            margin: 0;
            box-shadow: none;
            border: none;
          }
          .print-btn, .btn, a.btn {
            display: none !important;
          }
        }
    </style>
</head>
<body>

<div class="struk">
    <h4>Struk Pembelian</h4>
    <?php if ($nama_member) { ?>
        <p>Member: <?= htmlspecialchars($nama_member) ?> (<?= htmlspecialchars($phone_num) ?>)</p>
    <?php } ?>
    <p>Tanggal: <?= date('d-m-Y H:i:s') ?> WIB</p>
    <div class="line"></div>
  <?php foreach ($items as $item): ?>
    <?php
    $harga_dipakai = isset($item['harga_jual']) ? $item['harga_jual'] : $item['harga_beli'];
    $total_item = $harga_dipakai * $item['jumlah'];
    ?>
    <div class="item">
        <span><?= $item['nama_barang'] ?> x <?= $item['jumlah'] ?></span>
        <span>Rp <?= number_format($total_item, 0, ',', '.') ?></span>
    </div>
<?php endforeach; ?>
<!-- <div class="item">
    <span><?= $item['nama_barang'] ?> x <?= $item['jumlah'] ?></span>
    <span>Rp <?= number_format($total_item, 0, ',', '.') ?></span>
</div> -->
    <div class="line"></div>
    <div class="item total">
        <span>Total</span>
        <span>Rp <?= number_format($totalSeluruh, 0, ',', '.') ?></span>
    </div>
    <div class="item">
        <span>Diskon Member (<?= ($diskonPersen * 100) ?>%)</span>
        <span>- Rp <?= number_format($totalDiskonPersen, 0, ',', '.') ?></span>
    </div>
    <div class="item total">
        <span>Total Setelah Diskon</span>
        <span>Rp <?= number_format($totalSetelahDiskon, 0, ',', '.') ?></span>
    </div>
    <div class="item total">
        <span>Kembalian</span>
        <span>Rp <?= number_format($kembalian, 0, ',', '.') ?></span>
    </div>
    <div class="line"></div>
    <p class="text-center">Terima kasih telah berbelanja!</p>
</div>

<button onclick="window.print()" class="btn btn-primary print-btn">Cetak Struk</button>
<?php if ($member): ?>
    <a href="kirimwa.php?kirim_wa=true&id_nota=<?= $idNota ?>&bayar=<?= $bayar ?>&diskon=<?= $totalDiskonPersen ?>">
        <button class="btn btn-success print-btn">Kirim WA</button>
    </a>
<?php endif; ?>

</body>
</html>
