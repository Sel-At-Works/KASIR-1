<?php
require 'config.php'; // Pastikan file konfigurasi database di-load

// Ambil waktu sekarang
$now = new DateTime();

// Query untuk mengambil data member
$sql = "SELECT id, name, phone, diskon, point, status, Tanggal_Aktif FROM member1";
$query = $config->prepare($sql);
$query->execute();
$hasil = $query->fetchAll(PDO::FETCH_ASSOC);

// Update status jika lebih dari 30 detik
foreach ($hasil as $row) {
    // $tanggalAktif = new DateTime($row['Tanggal_Aktif']);
    // $selisih = $now->getTimestamp() - $tanggalAktif->getTimestamp();

    // if ($selisih > 60 && $row['status'] === 'active') {
    //     $updateSql = "UPDATE member1 SET status = 'non-active' WHERE id = :id";
    //     $updateQuery = $config->prepare($updateSql);
    //     $updateQuery->execute([':id' => $row['id']]);
    // }
}

// Refresh data setelah update
$query = $config->prepare($sql);
$query->execute();
$hasil = $query->fetchAll(PDO::FETCH_ASSOC);
?>


<!-- Tampilkan Data Admin -->
<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12 main-chart">
                <h3>Data Member</h3>
                <br />

                <!-- Tombol Tambah Data -->
                <a href="admin/module/member/tambah_member.php" class="btn btn-primary" style="margin-bottom: 10px;">
                    + Tambah Data
                </a>

                <!-- Notifikasi -->
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
                            <th>Id</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Diskon</th>
                            <th>Point</th>
                            <th>Status</th>
                            <th>Tanggal Aktif</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil as $isi) { ?>
                            <tr>
                                <td><?= $isi['id']; ?></td>
                                <td><?= $isi['name']; ?></td>
                                <td><?= $isi['phone']; ?></td>
                                <td><?= $isi['diskon']; ?> %</td>
                                <td><?= $isi['point']; ?></td>
                                <td><?= $isi['status']; ?></td>
                                <td><?= date('d-m-Y H:i:s', strtotime($isi['Tanggal_Aktif'])); ?></td>
                                <td>
                                    <a href="admin/module/member/editmember.php?uid=<?= $isi['id']; ?>">
                                        <button class="btn btn-warning">Edit</button>
                                    </a>

                                    <form action="admin/module/member/toggle_status.php" method="post" style="display:inline-flex; align-items: center; gap: 5px;">
                                        <input type="hidden" name="id" value="<?= $isi['id']; ?>"> <!-- Input hidden untuk ID member -->
                                        <select name="status" class="form-control form-control-sm">
                                            <option value="active" <?= $isi['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="non-active" <?= $isi['status'] === 'non-active' ? 'selected' : '' ?>>Non-Active</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-info">Simpan</button>
                                    </form>



                                    <?php if ($isi['status'] !== 'active') { ?>
                                        <a href="fungsi/hapus/hapus.php?member=hapus&id=<?= $isi['id']; ?>"
                                            onclick="return confirm('Hapus Data Member?');">
                                            <button class="btn btn-danger">Hapus</button>
                                        </a>
                                    <?php } else { ?>
                                        <button class="btn btn-danger" disabled title="Tidak bisa menghapus member aktif">Hapus</button>
                                    <?php } ?>
                                </td>

                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div class="clearfix" style="padding-bottom: -0;"></div>
            </div>
        </div>
    </section>
</section>