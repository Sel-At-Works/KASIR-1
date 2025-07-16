<?php
require 'config.php'; // Menggunakan koneksi dari config.php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_GET['token'])) {
    die("<p class='error'>❌ Token tidak ditemukan!</p>");
}

$token = trim($_GET['token']); // Bersihkan token dari spasi

try {
    // Cek token di database reset_tokens
    $query = "SELECT email, expired FROM reset_tokens WHERE token = :token";
    $stmt = $config->prepare($query);
    $stmt->bindParam(":token", $token);
    $stmt->execute();
    
    if ($stmt->rowCount() == 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $email = $row['email'];
        $reset_expiry = strtotime($row['expired']); // Ubah ke format timestamp
        $current_time = time(); // Ambil waktu saat ini dalam format timestamp

        // Validasi apakah token sudah kedaluwarsa
        if ($reset_expiry < $current_time) {
            die("<p class='error'>❌ Token sudah kedaluwarsa!</p>");
        }
    } else {
        die("<p class='error'>❌ Token tidak valid!</p>");
    }
} catch (PDOException $e) {
    die("<p class='error'>❌ Kesalahan Database: " . $e->getMessage() . "</p>");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <link rel="stylesheet" href="style.css">
        <style>
            /* Reset default margin dan padding */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: Arial, sans-serif;
            }

            /* Styling halaman */
            body {
                background-color: #f0f5ff;
                /* Warna latar belakang lembut */
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            /* Kontainer utama */
            .container {
                background: #ffffff;
                padding: 25px;
                border-radius: 10px;
                box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
                width: 400px;
                text-align: center;
            }

            /* Judul */
            h2 {
                color: #0044cc;
                margin-bottom: 10px;
            }

            /* Paragraf */
            p {
                color: #555;
                margin-bottom: 15px;
            }

            /* Input fields */
            input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 8px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 16px;
            }

            /* Tombol reset */
            button {
                width: 100%;
                background-color: #0044cc;
                color: white;
                padding: 12px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                margin-top: 10px;
            }

            button:hover {
                background-color: #003399;
            }

            /* Pesan error & sukses */
            .error {
                color: red;
                font-size: 14px;
                margin-top: 10px;
            }

            .success {
                color: green;
                font-size: 14px;
                margin-top: 10px;
            }
        </style>
    </head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Masukkan password baru untuk akun: <strong><?php echo htmlspecialchars($email); ?></strong></p>

        <form method="POST">
            <input type="password" name="password" placeholder="Password Baru" required>
            <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
            <button type="submit" name="reset">Reset Password</button>
        </form>

        <?php
        // Proses reset password jika form dikirimkan
        if (isset($_POST['reset'])) {
            $password = $_POST['password'];
            $konfirmasi = $_POST['konfirmasi'];

            if ($password !== $konfirmasi) {
                echo "<p class='error'>❌ Password tidak cocok!</p>";
            } else {
                try {
                    $hash = md5($password); 

                    // Update password di tabel member
                    $update = "UPDATE member SET pw = :password WHERE email = :email";
                    $stmt = $config->prepare($update);
                    $stmt->bindParam(":password", $hash);
                    $stmt->bindParam(":email", $email);
                    $stmt->execute();

                    // Hapus token setelah digunakan
                    $delete = "DELETE FROM reset_tokens WHERE email = :email";
                    $stmt = $config->prepare($delete);
                    $stmt->bindParam(":email", $email);
                    $stmt->execute();

                    echo "<p class='success'>✅ Password berhasil direset! Silakan <a href='login.php'>login</a>.</p>";
                } catch (PDOException $e) {
                    echo "<p class='error'>❌ Gagal mereset password: " . $e->getMessage() . "</p>";
                }
            }
        }
        ?>
    </div>
</body>
</html>
