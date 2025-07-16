<?php
require '../../../config.php'; // Sesuaikan path config

// Tangkap ID dari URL
$id = $_GET['uid'];

// Ambil data member berdasarkan ID
$sql = "SELECT * FROM member1 WHERE id = ?";
$query = $config->prepare($sql);
$query->execute([$id]);
$data = $query->fetch(PDO::FETCH_ASSOC);

// Proses update data saat form disubmit
if (isset($_POST['submit'])) {
    $name   = $_POST['name'];
    $phone  = $_POST['phone'];
    $diskon  = $_POST['diskon'];
    $status = $_POST['status'];
    $tanggal_aktif = date('Y-m-d H:i:s');  // waktu sekarang

    // Cek nomor telepon sudah digunakan atau tidak
    $check = $config->prepare("SELECT COUNT(*) FROM member1 WHERE phone = ? AND id != ?");
    $check->execute([$phone, $id]);
    $exists = $check->fetchColumn();

    if ($exists > 0) {
        echo "<script>
            alert('Nomor telepon sudah digunakan oleh member lain!');
            window.location.href = '../../../index.php?page=member';
        </script>";
        exit;
    } else {
        // Update termasuk tanggal aktif
        $update = "UPDATE member1 SET name = ?, phone = ?, diskon = ?, status = ?, Tanggal_Aktif = ? WHERE id = ?";
        $stmt = $config->prepare($update);
        $stmt->execute([$name, $phone, $diskon, $status, $tanggal_aktif, $id]);

        header("Location: ../../../index.php?page=member&success-edit");
        exit;
    }
}
?>

<!-- Form Edit -->
<div class="form-container">
    <h3>Edit Data Member</h3>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($data['name']); ?>" required>

        <label>Phone:</label>
        <input type="text" name="phone" value="<?php echo htmlspecialchars($data['phone']); ?>" required>

        <label>Diskon:</label>
        <input type="text" name="diskon" value="<?php echo htmlspecialchars($data['diskon']); ?>" required>

        <label>Status:</label>
        <select name="status" required>
            <option value="active" <?php if ($data['status'] == 'active') echo 'selected'; ?>>Aktif</option>
            <!-- <option value="non-active" <?php if ($data['status'] == 'non-active') echo 'selected'; ?>>Nonaktif</option> -->
        </select>

        <button type="submit" name="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="../../../index.php?page=member" class="btn btn-secondary">Batal</a>
    </form>
</div>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f7f7f7;
        margin: 0;
        padding: 20px;
    }

    .form-container {
        background-color: #fff;
        padding: 30px;
        max-width: 500px;
        margin: auto;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
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

    .btn-success {
        background-color: #28a745;
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
