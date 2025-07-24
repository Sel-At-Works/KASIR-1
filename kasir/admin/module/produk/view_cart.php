<?php
session_start();

// // Bersihkan barang lebih dari 1 jam
// foreach ($_SESSION['keranjang'] as $key => $item) {
//     if (isset($item['waktu']) && (time() - $item['waktu'] > 3600)) {
//         unset($_SESSION['keranjang'][$key]);
//     }
// }
// $_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // re-index

// $keranjang = $_SESSION['keranjang'] ?? [];
// jika ada error=stok_limit, tampilkan pesan
if (isset($_GET['error']) && $_GET['error'] === 'stok_limit') {
    echo "<div class='alert alert-danger text-center'>Jumlah di keranjang sudah sesuai dengan stok yang tersedia.</div>";
} 


// Bersihkan barang lebih dari 30 detik
foreach ($_SESSION['keranjang'] as $key => $item) {
    if (isset($item['waktu']) && (time() - $item['waktu'] > 30)) {
        unset($_SESSION['keranjang'][$key]);
    }
}
$_SESSION['keranjang'] = array_values($_SESSION['keranjang']); // re-index


// Handle item removal
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_index'])) {
    $index = $_POST['hapus_index'];
    if (isset($_SESSION['keranjang'][$index])) {
        unset($_SESSION['keranjang'][$index]);
        $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
    }
}

$keranjang = $_SESSION['keranjang'] ?? [];

if (empty($keranjang)) {
    echo "<div style='text-align:center; margin-top: 50px;'>Keranjang belanja kosong.</div>";
    exit;
}
?>

<!-- SISA PHP DI ATAS TIDAK DIUBAH -->

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Keranjang Belanja</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <style>
    body {
      background-color: #f1f3f5;
      font-family: 'Segoe UI', sans-serif;
    }

    h2 {
      font-weight: bold;
      color: #343a40;
    }

    .card {
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
      position: relative;
      transition: transform 0.2s ease;
      background-color: white;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card-img-top {
      border-top-left-radius: 15px;
      border-top-right-radius: 15px;
      height: 180px;
      object-fit: cover;
    }

    .card-body {
      padding: 20px;
      text-align: center;
    }

    .card-body h5 {
      font-size: 1.1rem;
      font-weight: 600;
      color: #343a40;
    }

    .card-body p {
      font-size: 0.95rem;
      color: #495057;
    }

    .hapus-btn {
      position: absolute;
      top: 10px;
      right: 15px;
      background: #e63946;
      color: white;
      border: none;
      border-radius: 50%;
      width: 28px;
      height: 28px;
      font-weight: bold;
      line-height: 1;
    }

    .qty-box input {
      width: 70px;
      text-align: center;
      margin: 0 auto;
      border: 1px solid #ced4da;
      border-radius: 5px;
    }

    .form-check-label {
      font-size: 0.9rem;
      color: #212529;
    }

    .btn-checkout {
      display: block;
      margin: 30px auto 0;
      padding: 12px 35px;
      font-size: 1.1rem;
      border-radius: 30px;
      background-color: #007bff;
      border: none;
      color: white;
      transition: background 0.3s ease;
    }

    .btn-checkout:hover {
      background-color: #0056b3;
    }

    #total-harga {
      font-weight: bold;
      font-size: 1.3rem;
      color: #28a745;
    }
  </style>
</head>
<body>
  
  <div class="container mt-5">
    <h2 class="text-center mb-4">Keranjang Belanja</h2>
    <form method="POST" action="transaksi.php">
    <div class="row">
      <?php foreach ($keranjang as $index => $item): ?>
        <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
          <div class="card h-100">
            <button type="button" class="hapus-btn" onclick="hapusProduk(<?= $index ?>)">&times;</button>
            <img src="../../../admin/module/admin/gambar/<?= $item['gambar'] ?>" class="card-img-top" alt="<?= $item['nama_barang'] ?>">
            <div class="card-body">
              <h5><?= $item['nama_barang'] ?></h5>
              
            <p>Harga: Rp <?= isset($item['harga_jual']) ? number_format($item['harga_jual'], 0, ',', '.') : number_format($item['harga_beli'], 0, ',', '.') ?></p>
              <div class="qty-box mb-2">
                <input type="number" name="jumlah[<?= $index ?>]" value="<?= $item['jumlah'] ?>" min="1" class="form-control" disabled>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="checkout[]" value="<?= $index ?>" id="check-<?= $index ?>">
                <label class="form-check-label" for="check-<?= $index ?>">Pilih untuk Checkout</label>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- TOTAL HARGA -->
    <div class="container text-center mt-4">
      <h4 id="total-harga" style="display:none;">Total: Rp <span id="total-value">0</span></h4>
    </div>

  <div class="text-center">
    <button type="submit" name="" class="btn btn-success btn-checkout mt-3">Checkout Barang Terpilih</button>
  </div>
  </div>
      </form>

  <!-- FORM HAPUS TIDAK DIUBAH -->
  <form id="form-hapus" method="POST" style="display: none;">
    <input type="hidden" name="hapus_index" id="hapus_index">
  </form>

  <!-- SCRIPT TIDAK DIUBAH -->
  <script>
    function hapusProduk(index) {
      if (confirm("Yakin ingin menghapus produk ini dari keranjang?")) {
        document.getElementById('hapus_index').value = index;
        document.getElementById('form-hapus').submit();
      }
    }


    // Reload halaman setiap 10 detik supaya session keranjang yang sudah timeout langsung bersih
    setTimeout(() => {
      window.location.reload();
    }, 10000); // 10.000 ms = 10 detik

    function formatRupiah(angka) {
      return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="checkout[]"]');
    const totalText = document.getElementById('total-harga');
    const totalValue = document.getElementById('total-value');

    function updateTotal() {
      let total = 0;
      checkboxes.forEach((cb, idx) => {
        if (cb.checked) {
          const harga = parseInt(document.querySelectorAll('.card-body p')[idx].textContent.replace(/\D/g, ''));
          total += harga;
        }
      });

      if (total > 0) {
        totalText.style.display = 'block';
        totalValue.textContent = formatRupiah(total);
      } else {
        totalText.style.display = 'none';
      }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
  </script>

</body>
</html>
