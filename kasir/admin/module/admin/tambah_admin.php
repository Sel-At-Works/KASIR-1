<?php
require '../../../config.php'; // Pastikan file konfigurasi database di-load

if (isset($_POST['submit'])) {
    $username = strip_tags($_POST['nm_member']);
    $password = strip_tags($_POST['pw']); // Gunakan password_hash untuk keamanan
    $alamat = strip_tags($_POST['alamat_member']);
    $telepon = strip_tags($_POST['telepon']);
    $email = strip_tags($_POST['email']);
    $status= strip_tags($_POST['status']);

    // Cek apakah email sudah terdaftar
    $cek_email = $config->prepare("SELECT COUNT(*) FROM member WHERE email = ?");
    $cek_email->execute([$email]);
    if ($cek_email->fetchColumn() > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location='../../../index.php?page=admin';</script>";
        exit();
    }

    // Proses upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp = $_FILES['gambar']['tmp_name'];
    $gambar_size = $_FILES['gambar']['size'];
    $gambar_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    // Tentukan folder tujuan
    // $upload_dir = "gambar/";
    $upload_dir = "../../../assets/img/user/";

    // Pastikan folder gambar tersedia, jika tidak buat foldernya
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Hanya izinkan format JPG, JPEG, PNG, dan GIF
    $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

    // Buat nama file unik agar tidak menimpa file lain
    $new_filename = time() . "_" . basename($gambar);
    $target = $upload_dir . $new_filename;

    if (!in_array($gambar_ext, $allowed_ext)) {
        echo "<script>alert('Format gambar tidak diizinkan! Hanya JPG, JPEG, PNG, dan GIF.');</script>";
    } elseif ($gambar_size > 2097152) { // Maksimal 2MB
        echo "<script>alert('Ukuran gambar terlalu besar! Maksimal 2MB.');</script>";
    } elseif (!is_uploaded_file($gambar_tmp)) {
        echo "<script>alert('File gambar tidak ditemukan!');</script>";
    } else {
        if (move_uploaded_file($gambar_tmp, $target)) {
            // Query untuk menyimpan admin baru ke database
            $sql = "INSERT INTO member (nm_member, pw, alamat_member, telepon, email, gambar, status) VALUES (?, MD5(?), ?, ?, ?, ?, ?)";
            $stmt = $config->prepare($sql);
            $execute = $stmt->execute([$username, $password, $alamat, $telepon, $email, $new_filename,$status]);

            if ($execute) {
                echo "<script>alert('Data berhasil ditambah!'); window.location='../../../index.php?page=admin&success';</script>";
                exit();
            } else {
                echo "<script>alert('Gagal menambahkan admin ke database.');</script>";
            }
        } else {
            echo "<script>alert('Gagal mengupload gambar. Pastikan folder memiliki izin yang cukup.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin</title>
    <link rel="stylesheet" href="../../../assets/css/bootstrap.css">
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
        <h2 class="mb-4">Tambah Admin</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Username:</label>
                <input type="text" name="nm_member" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="pw" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Alamat:</label>
                <input type="text" name="alamat_member" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Telepon:</label>
                <input type="text" name="telepon" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Upload Gambar (JPG, PNG, GIF, max 2MB):</label>
                <input type="file" name="gambar" class="form-control" required>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Tambah Admin</button>
            <a href="index.php?page=admin" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>