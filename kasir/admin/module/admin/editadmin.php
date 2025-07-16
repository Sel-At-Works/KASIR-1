<?php
require '../../../config.php';

// Ambil data member berdasarkan uid dari GET
if (isset($_GET['uid'])) {
    $id = $_GET['uid'];

    $sql = "SELECT * FROM member WHERE id_member = ?";
    $query = $config->prepare($sql);
    $query->execute([$id]);
    $member = $query->fetch(PDO::FETCH_ASSOC);

    if (!$member) {
        echo "<script>alert('Admin tidak ditemukan!'); window.location='index.php?page=admin';</script>";
        exit;
    }
}

// Ambil status member awal
$status_member = $member['status'] ?? 'Tidak Aktif';

// **Proses update data kalau ada submit form POST**
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['nm_member']);

    // validasi email
    $sql = "SELECT * FROM member WHERE email =?";
    $query = $config->prepare($sql);
    $query->execute([$_POST['email']]);
    $email_check = $query->fetch(PDO::FETCH_ASSOC);
    if ($email_check && $email_check['id_member'] != $id) {
        echo "<script>alert('Email sudah digunakan!');</script>";
        exit;
    }

    // Ambil status terbaru dari form POST
    $status = $_POST['status'] ?? $status_member;

    if ($status == 'Aktif') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $new_password = $_POST['pw'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Format email tidak valid!');</script>";
            exit;
        }

        if (!empty($_FILES['gambar']['name'])) {
            $image = $_FILES['gambar']['name'];
            $target = "../../../assets/img/user/" . basename($image);
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
            $file_extension = strtolower(pathinfo($target, PATHINFO_EXTENSION));

            if (!in_array($file_extension, $allowed_extensions)) {
                echo "<script>alert('Format gambar tidak didukung!');</script>";
                exit;
            }
            $cek_email = $config->prepare("SELECT id_member FROM member WHERE email = ? AND id_member != ?");
            $cek_email->execute([$email, $id]);
            if ($cek_email->rowCount() > 0) {
                echo "<script>alert('Email sudah digunakan oleh member lain!');</script>";
                exit;
         }

            move_uploaded_file($_FILES['gambar']['tmp_name'], $target);
        } else {
            $image = $member['gambar'];
        }

        if (!empty($new_password)) {
            $sql = "UPDATE member SET email=?, nm_member=?, pw=MD5(?), gambar=? , status=? WHERE id_member=?";
            $params = [$email, $username, $new_password, $image, $status, $id];
        } else {
            $sql = "UPDATE member SET email=?, nm_member=?, gambar=? , status=? WHERE id_member=?";
            $params = [$email, $username, $image, $status, $id];
        }
    } else {
        $sql = "UPDATE member SET nm_member=? WHERE id_member=?";
        $params = [$username, $id];
    }

    $query = $config->prepare($sql);
    $update = $query->execute($params);

    if ($update) {
        echo "<script>alert('Data berhasil diperbarui!'); window.location='../../../index.php?page=admin&success-edit';</script>";
        exit; // setelah redirect, jangan lanjutkan eksekusi
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 600px;
            background: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ced4da;
        }

        .btn-success {
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }

        .btn-secondary {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }

        .mb-3 label {
            font-weight: bold;
            color: #555;
        }

        input[type="file"] {
            padding: 5px;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h2>Edit Admin</h2>
       <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" 
               value="<?php echo htmlspecialchars($member['email']); ?>" 
               class="form-control" 
               required
               <?php echo ($status_member == 'Tidak Aktif') ? 'readonly' : ''; ?>>
    </div>
    <div class="mb-3">
        <label>Username:</label>
        <input type="text" name="nm_member" 
               value="<?php echo htmlspecialchars($member['nm_member']); ?>" 
               class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password (kosongkan jika tidak ingin mengubah):</label>
        <input type="password" name="pw" class="form-control" 
               <?php echo ($status_member == 'Tidak Aktif') ? 'readonly placeholder="Tidak bisa diubah"' : ''; ?>>
    </div>
    <div class="mb-3">
        <label>Gambar Saat Ini:</label><br>
        <img src="images/<?php echo htmlspecialchars($member['gambar']); ?>" width="100" alt="Profile"><br>
        <label>Upload Gambar Baru:</label>
        <input type="file" name="gambar" class="form-control" 
               <?php echo ($status_member == 'Tidak Aktif') ? 'disabled' : ''; ?>>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required 
                <?php echo ($status_member == 'Tidak Aktif') ? 'disabled' : ''; ?>>
            <option value="Aktif" <?php echo ($status_member == 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
            <option value="Tidak Aktif" <?php echo ($status_member == 'Tidak Aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    <a href="index.php?page=admin" class="btn btn-secondary">Batal</a>
</form>
    </div>
</body>

</html>

<style>
    /* Gaya Umum */
    body {
        background-color: #f4f7fc;
        font-family: 'Poppins', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    /* Kontainer */
    .container {
        max-width: 450px;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Judul */
    h2 {
        text-align: center;
        color: #007bff;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    /* Styling Form */
    .form-label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        display: block;
    }

    .form-control {
        width: 100%;
        border: 1px solid #ccc;
        padding: 12px;
        border-radius: 8px;
        font-size: 14px;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.2);
    }

    /* Tombol */
    .btn {
        width: 100%;
        font-size: 16px;
        font-weight: bold;
        padding: 12px;
        border-radius: 8px;
        transition: 0.3s;
        cursor: pointer;
        border: none;
        margin-top: 10px;
    }

    .btn-success {
        background-color: #007bff;
        color: white;
    }

    .btn-success:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Gaya Gambar Profil */
    img {
        display: block;
        margin: 10px auto;
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #007bff;
    }

    /* Responsif */
    @media (max-width: 768px) {
        .container {
            max-width: 90%;
            padding: 20px;
        }
    }
</style>