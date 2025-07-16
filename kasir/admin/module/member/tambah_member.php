<?php
require '../../../config.php'; // Pastikan path ini sesuai dengan struktur folder Anda

// Set zona waktu ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

// Proses tambah data member saat form disubmit
if (isset($_POST['submit'])) {
    $name   = $_POST['name'];
    $phone  = $_POST['phone'];
    $diskon = $_POST['diskon'];
    $status = $_POST['status'];

    // Periksa apakah nomor telepon sudah ada
    $check = $config->prepare("SELECT COUNT(*) FROM member1 WHERE phone = ?");
    $check->execute([$phone]);
    $exists = $check->fetchColumn();

    if ($exists > 0) {
        // Tampilkan alert dan kembali ke halaman sebelumnya
        echo "<script>
                alert('Nomor telepon sudah digunakan oleh member lain!');
                window.location.href='../../../index.php?page=member';
              </script>";
        exit;
    } else {
        // Lanjutkan proses penyimpanan
        $sql = "INSERT INTO member1 (name, phone, diskon, point,  status, Tanggal_Aktif) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $config->prepare($sql);
        $point = 0; // Nilai point default
        $tanggal_aktif = date('Y-m-d H:i:s'); // Tanggal dan jam aktif otomatis

        if ($stmt->execute([$name, $phone, $diskon, $point, $status, $tanggal_aktif])) {
            header("Location: ../../../index.php?page=member&success");
            exit;
        } else {
            echo "Gagal menambahkan data.<br>";
            print_r($stmt->errorInfo());
            exit;
        }
    }
}
?>


<!-- Style form -->
<style>
    .form-container {
        background-color: #fff;
        padding: 30px;
        max-width: 500px;
        margin: 40px auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }

    h3 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    input[type="text"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        font-size: 14px;
    }

    .btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        margin-left: 10px;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>

<!-- Form Tambah Member -->
<div class="form-container">
    <h3>Tambah Data Member</h3>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="name" required>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Diskon:</label>
        <input type="text" name="diskon" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="active">Aktif</option>
            <!-- <option value="non-active">Nonaktif</option> -->
        </select>

        <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
        <a href="../../../index.php?page=member" class="btn btn-secondary">Batal</a>
    </form>
</div>
