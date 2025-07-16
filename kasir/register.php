<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Halaman Register</title>
    <!-- BOOTSTRAP STYLES -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <style>
        .register-container {
            max-width: 500px;
            margin: auto;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 8px;
            height: 45px;
        }
        .btn-register {
            width: 100%;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="register-container">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Daftar</h3>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" id="form_register" role="form" method="POST">
                        <div class="form-group">
                            <label for="nm_member">Nama Member</label>
                            <input type="text" name="nm_member" id="nm_member" placeholder="Masukkan Nama Member" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="pw">Password</label>
                            <input type="password" name="pw" id="pw" placeholder="Masukkan Password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat_member">Alamat Member</label>
                            <input type="text" name="alamat_member" id="alamat_member" placeholder="Masukkan Alamat" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="telepon">Telepon</label>
                            <input type="text" name="telepon" id="telepon" placeholder="Masukkan Nomor Telepon" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Masukkan Email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" name="nik" id="nik" placeholder="Masukkan NIK" class="form-control" required>
                        </div>
                        <button type="submit" name="btnReg" class="btn btn-primary btn-register">Daftar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    if (isset($_POST['btnReg'])) {
        require 'config.php';
    
        $nm_member = strip_tags($_POST['nm_member']);
        $pw = strip_tags($_POST['pw']);
        $alamat_member = strip_tags($_POST['alamat_member']);
        $telepon = strip_tags($_POST['telepon']);
        $email = strip_tags($_POST['email']);
        $nik = strip_tags($_POST['nik']);
    
        // Cek apakah email sudah ada
        $sql = "SELECT * FROM member WHERE email = ?";
        $stmt = $config->prepare($sql);
        $stmt->execute([$email]);
        $cek = $stmt->rowCount();
    
        if ($cek > 0) {
            echo '<script>alert("Email telah digunakan"); window.location="register.php";</script>';
        } else {
            if (empty($nm_member) || empty($pw) || empty($alamat_member) || empty($telepon) || empty($email) || empty($nik)) {
                echo '<script>alert("Inputan tidak boleh kosong"); window.location="register.php";</script>';
            } else {
                // Simpan data ke database
                $query = "INSERT INTO member (nm_member, pw, alamat_member, telepon, email, nik) VALUES (?, MD5(?), ?, ?, ?, ?)";
                $stmt = $config->prepare($query);
                $result = $stmt->execute([$nm_member, $pw, $alamat_member, $telepon, $email, $nik]);
    
                if ($result) {
                    echo '<script>alert("Anda berhasil mendaftar"); window.location="login.php";</script>';
                } else {
                    echo '<script>alert("SQL error, coba lagi"); window.location="register.php";</script>';
                }
            }
        }
    }
?>