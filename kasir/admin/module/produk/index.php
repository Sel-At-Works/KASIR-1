<?php
session_start();
require 'config.php';

// Ambil data barang dari database
$sql = "SELECT id_barang, nama_barang, harga_beli, harga_jual, gambar, stok FROM barang";
$query = $config->prepare($sql);
$query->execute();
$hasil = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Produk</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #eef1f5;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2 {
      font-weight: 700;
      color: #2c3e50;
    }

    .card {
      border: none;
      border-radius: 20px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      min-height: 100%; /* Agar tinggi seragam */
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 20px rgba(0, 0, 0, 0.12);
    }

    .card-img-top {
      height: 200px;
      object-fit: cover;
      border-top-left-radius: 20px;
      border-top-right-radius: 20px;
    }

    .card-title {
      font-size: 1.2rem;
      font-weight: 600;
      color: #34495e;
    }

    .card-text {
      color: #7f8c8d;
    }

    .btn-custom {
      border-radius: 25px;
      padding: 8px 18px;
      font-size: 0.9rem;
    }

    .btn-primary {
      background-color: #2980b9;
      border: none;
    }

    .btn-primary:hover {
      background-color: #1c6690;
    }

    .btn-secondary {
      background-color: #95a5a6;
      border: none;
    }

    .btn-secondary:hover {
      background-color: #7f8c8d;
    }

    .top-button {
      margin-bottom: 30px;
      text-align: right;
    }

    .product-card {
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .card-body {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    @media (max-width: 576px) {
      .top-button {
        text-align: center;
      }
    }

    .btn-scan-big {
  font-size: 1.5rem;       /* Ukuran teks lebih besar */
  padding: 16px 32px;      /* Padding atas-bawah dan kiri-kanan lebih besar */
  min-width: 220px;        /* Lebar minimum tombol */
}
  </style>
</head>
<body>

<div class="container mt-9 mx-auto">
  <div class="top-button">
    <a href="admin/module/produk/view_cart.php" class="btn btn-secondary btn-custom">🛒 Lihat Keranjang</a>
    <a href="admin/module/produk/scan.php" class="btn btn-success btn-custom ml-2">📷 Scan Produk</a>
  </div>

  <a href="admin/module/produk/scan.php" class="btn btn-success btn-custom btn-lg btn-scan-big ml-2">📷 Scan Produk</a>
  <h2 class="text-center mb-4">Daftar Produk</h2>

  <div class="row">
    <?php foreach ($hasil as $row): ?>
    <div class="col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
      <div class="card product-card w-100">
        <img src="admin/module/admin/gambar/<?= $row['gambar'] ?>" class="card-img-top" alt="<?= $row['nama_barang'] ?>">

        <div class="card-body text-center">
          <div>
            <h5 class="card-title"><?= $row['nama_barang'] ?></h5>
            <p class="card-text">Harga: Rp <?= number_format($row['harga_jual'], 0, ',', '.') ?></p>
          </div>

       <p class="card-text">Stok: <?= $row['stok'] ?></p>

<?php if ($row['stok'] > 0): ?>
  <form method="post" action="admin/module/produk/add_to_cart.php">
    <input type="hidden" name="id_barang" value="<?= $row['id_barang'] ?>">
    <input type="hidden" name="nama_barang" value="<?= $row['nama_barang'] ?>">
    <input type="hidden" name="harga_beli" value="<?= $row['harga_jual'] ?>">
    <input type="hidden" name="gambar" value="<?= $row['gambar'] ?>">

    <button type="submit" class="btn btn-primary btn-custom btn-block mt-3 mb-4">Tambah ke Keranjang</button>
  </form>
<?php else: ?>
  <button class="btn btn-secondary btn-custom btn-block mt-3 mb-4" disabled>Stok Habis</button>
<?php endif; ?>

        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>  
</div>

</body>
</html>
