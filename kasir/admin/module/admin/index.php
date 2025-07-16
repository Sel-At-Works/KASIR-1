<?php
@ob_start();
session_start();

if (isset($_POST['proses'])) {
    require 'config.php';

    $user = strip_tags($_POST['user']);
    $pass = strip_tags($_POST['pass']);

    $sql = 'SELECT * FROM member WHERE nm_member = ? AND pw = MD5(?)';
    $row = $config->prepare($sql);
    $row->execute([$user, $pass]);
    $jum = $row->rowCount();

    if ($jum > 0) {
        $hasil = $row->fetch();
        $_SESSION['admin'] = $hasil;

        $update = $config->prepare("UPDATE member SET status = 'Aktif' WHERE id_member = ?");
        $update->execute([$hasil['id_member']]);

        echo '<script>alert("Login Sukses");window.location="index.php"</script>';
    } else {
        echo '<script>alert("Login Gagal");history.go(-1);</script>';
    }
}

// ✅ Ambil semua data admin di sini
require 'config.php';
$sql = "SELECT * FROM member";
$query = $config->prepare($sql);
$query->execute();
$data_admin = $query->fetchAll(PDO::FETCH_ASSOC);
?>




<!-- Tampilkan Data Admin -->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12 main-chart">
                <h3>Data Admin</h3>
                <br />

                <!-- Tombol Tambah Data -->
                <a href="admin/module/admin/tambah_admin.php" class="btn btn-primary" style="margin-bottom: 10px;">
                    + Tambah Data
                </a>

                <!-- Notifikasi Sukses atau Gagal -->
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success">
                        <p>Tambah Data Berhasil!</p>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['success-edit'])) { ?>
                    <div class="alert alert-success">
                        <p>Update Data Berhasil!</p>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['remove'])) { ?>
                    <div class="alert alert-danger">
                        <p>Hapus Data Berhasil!</p>
                    </div>
                <?php } ?>

                <br />
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr style="background:#DFF0D8;color:#333;">
                            <th>ID</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Email</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data_admin as $isi) { ?>
                            <tr>
                                <td><?php echo $isi['id_member']; ?></td>
                                <td><?php echo $isi['nm_member']; ?></td>
                                <td><?php echo $isi['pw']; ?></td>
                                <td><?php echo $isi['alamat_member']; ?></td>
                                <td><?php echo $isi['telepon']; ?></td>
                                <td><?php echo $isi['email']; ?></td>
                                <td>
                                    <img src="assets/img/user/<?php echo $isi['gambar']; ?>" width="50" height="50" alt="Profile">
                                </td>
                                <td>
                                    <?php
                                    if ($isi['status'] === 'Aktif') {
                                        echo '<span class="status-aktif">Aktif</span>';
                                    } else {
                                        echo '<span class="status-nonaktif">Tidak Aktif</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="admin/module/admin/editadmin.php?uid=<?php echo $isi['id_member']; ?>">
                                        <button class="btn btn-warning">Edit</button>
                                    </a>
                                    <?php if ($isi['status'] === 'Tidak Aktif') { ?>
                                        <a href="fungsi/hapus/hapus.php?admin=hapus&id=<?php echo $isi['id_member']; ?>"
                                            onclick="return confirm('Hapus Data Admin?');">
                                            <button class="btn btn-danger">Hapus</button>
                                        </a>
                                    <?php } else { ?>
                                        <button class="btn btn-secondary" disabled>Tidak Bisa Dihapus</button>
                                    <?php } ?>

                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>

                </table>

                <!-- Spacer agar konten tidak terlalu mepet ke bawah -->
                <div class="clearfix" style="padding-bottom: -0;"></div>
            </div>
        </div>
    </section>
</section>