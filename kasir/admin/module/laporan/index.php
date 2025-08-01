<?php
include '../../../config.php';
include '../../../fungsi/view/view.php';
function formatPeriode($bln, $thn)
{
    return sprintf('%04d-%02d', $thn, $bln); // hasil: "2025-08"
}
// Array bulan untuk tampilan bulan dalam format teks
$bulan_tes = array(
    '01' => "Januari",
    '02' => "Februari",
    '03' => "Maret",
    '04' => "April",
    '05' => "Mei",
    '06' => "Juni",
    '07' => "Juli",
    '08' => "Agustus",
    '09' => "September",
    '10' => "Oktober",
    '11' => "November",
    '12' => "Desember"
);
?>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12 main-chart">
                <h3>
                    <?php
                    if (!empty($_GET['cari'])) {
                        $bln = $_POST['bln'] ?? ($_GET['bln'] ?? '');
                        $thn = $_POST['thn'] ?? ($_GET['thn'] ?? '');
                        echo "Data Laporan Penjualan " . ($bulan_tes[$bln] ?? 'Unknown') . " " . htmlspecialchars($thn);
                    } elseif (!empty($_GET['hari'])) {
                        echo "Data Laporan Penjualan " . htmlspecialchars($_POST['hari']);
                    } else {
                        echo "Data Laporan Penjualan " . $bulan_tes[date('m')] . " " . date('Y');
                    }
                    ?>

                </h3>
                <br />
                <h4>Cari Laporan Per Bulan</h4>
                <form method="get" action="index.php">
                    <input type="hidden" name="page" value="laporan">
                    <input type="hidden" name="cari" value="ok">

                    <table class="table table-striped">
                        <tr>
                            <th>Pilih Bulan</th>
                            <th>Pilih Tahun</th>
                            <th>Aksi</th>
                        </tr>
                        <tr>
                            <td>
                                <select name="bln" class="form-control">
                                    <option selected="selected">Bulan</option>
                                    <?php
                                    $bulan = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
                                    $bln1 = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
                                    for ($c = 0; $c < count($bulan); $c++) {
                                        echo "<option value='$bln1[$c]'> $bulan[$c] </option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <?php
                                $now = date('Y');
                                echo "<select name='thn' class='form-control'>";
                                echo '<option selected="selected">Tahun</option>';
                                for ($a = 2017; $a <= $now; $a++) {
                                    echo "<option value='$a'>$a</option>";
                                }
                                echo "</select>";
                                ?>
                            </td>
                            <td>
                                <input type="hidden" name="periode" value="ya">
                                <button class="btn btn-primary">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <a href="index.php?page=laporan" class="btn btn-success">
                                    <i class="fa fa-refresh"></i> Refresh
                                </a>

                                <?php if (!empty($_GET['cari']) && isset($_GET['bln'], $_GET['thn'])) { ?>
                                    <a href="excel.php?cari=yes&bln=<?= htmlspecialchars($_GET['bln']); ?>&thn=<?= htmlspecialchars($_GET['thn']); ?>" class="btn btn-info">
                                        <i class="fa fa-download"></i> Excel
                                    </a>
                                <?php } else { ?>
                                    <a href="excel.php" class="btn btn-info">
                                        <i class="fa fa-download"></i> Excel
                                    </a>
                                <?php } ?>


                            </td>

                        </tr>
                    </table>
                </form>

                <form method="post" action="index.php?page=laporan&hari=cek">
                    <table class="table table-striped">
                        <tr>
                            <th>Pilih Hari</th>
                            <th>Aksi</th>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <?php
                                    // Jika sudah submit, ambil nilai day, month, year dari $_POST['hari']
                                    $dayValue = '';
                                    $monthValue = '';
                                    $yearValue = '';
                                    if (!empty($_POST['hari'])) {
                                        $parts = explode('-', $_POST['hari']); // yyyy-mm-dd
                                        if (count($parts) === 3) {
                                            $yearValue = $parts[0];
                                            $monthValue = $parts[1];
                                            $dayValue = $parts[2];
                                        }
                                    }
                                    ?>
                                    <div class="col">
                                        <input type="number" class="form-control" name="day" placeholder="DD" min="1" max="31" value="<?= $dayValue; ?>" required>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" name="month" placeholder="MM" min="1" max="12" value="<?= $monthValue; ?>" required>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" name="year" placeholder="YYYY" min="2000" max="2100" value="<?= $yearValue; ?>" required>
                                    </div>
                                </div>
                                <!-- Hidden field untuk gabungan tanggal -->
                                <input type="hidden" name="hari" id="hari">
                            </td>
                            <td>
                                <input type="hidden" name="periode" value="ya">
                                <button type="submit" class="btn btn-primary" onclick="combineDate()">
                                    <i class="fa fa-search"></i> Cari
                                </button>
                                <a href="index.php?page=laporan" class="btn btn-success">
                                    <i class="fa fa-refresh"></i> Refresh</a>
                                <?php if (!empty($_GET['hari'])) { ?>
                                    <a href="excel.php?hari=cek&tgl=<?= $_POST['hari']; ?>" class="btn btn-info">
                                        <i class="fa fa-download"></i> Excel
                                    </a>
                                <?php } else { ?>
                                    <a href="excel.php" class="btn btn-info">
                                        <i class="fa fa-download"></i> Excel
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </form>
                <div class="clearfix" style="border-top:1px solid #ccc;"></div>
                <br /><br />

                <!-- Tabel Data Barang -->
                <!-- Diagram Batang Laporan -->
                <div style="max-width:500px;margin:auto;">
                    <canvas id="laporanChart" width="400" height="180"></canvas>
                </div>
                <div class="modal-view">
                    <table class="table table-bordered" id="example1">
                        <thead>
                            <tr style="background:#DFF0D8; color:#333;">
                                <th style="width:5%;">No</th>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th style="width:10%;">Jumlah</th>
                                <th style="width:10%;">Modal</th>
                                <th style="width:10%;">Total</th>
                                <th style="width:10%">Keuntungan</th>
                                <th style="width:15%;">Tanggal Input</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $no = 1;
                            $jumlah = 0;
                            $bayar = 0;
                            $modal = 0;

                            // Mengambil data berdasarkan periode atau hari yang dipilih
                            if (!empty($_GET['cari'])) {
                                $bln = $_GET['bln'] ?? '';
                                $thn = $_GET['thn'] ?? '';


                                $periode = formatPeriode($bln, $thn); // format: "2025-08"
                                $hasil = $lihat->periode_jual($periode);
                            } elseif (!empty($_GET['hari'])) {
                                $hari = $_POST['hari'];
                                $hasil = $lihat->hari_jual($hari); // Mengambil data berdasarkan hari
                            } else {
                                $hasil = $lihat->jual(); // Mengambil semua data jika tidak ada filter periode atau hari
                            }

                            // Menampilkan data dalam tabel
                            // Siapkan data untuk chart
                            $labels = [];
                            $dataJumlah = [];
                            $dataKeuntungan = [];
                            foreach ($hasil as $isi) {
                                $bayar += $isi['total'];
                                $modal += $isi['harga_beli'] * $isi['jumlah'];
                                $jumlah += $isi['jumlah'];
                                $totalKeuntungan = $isi['total'] - ($isi['harga_beli'] * $isi['jumlah']);
                                $labels[] = $isi['nama_barang'];
                                $dataJumlah[] = (int)$isi['jumlah'];
                                $dataKeuntungan[] = (int)$totalKeuntungan;
                            ?>
                                <tr>
                                    <td><?php echo $no; ?></td>
                                    <td><?php echo $isi['id_barang']; ?></td>
                                    <td><?php echo $isi['nama_barang'] ?></td>
                                    <td><?php echo $isi['jumlah']; ?></td>
                                    <td>Rp.<?php echo number_format($isi['harga_beli'] * $isi['jumlah']); ?>,-</td>
                                    <td>Rp.<?php echo number_format($isi['total']); ?>,-</td>
                                    <!-- <td><?php echo !empty($isi['nm_member']) ? $isi['nm_member'] : (isset($_SESSION['admin']['nm_member']) ? $_SESSION['admin']['nm_member'] : 'Umum'); ?></td> -->
                                    <td><?= number_format($totalKeuntungan); ?>,-</td>
                                    <td><?php echo $isi['tanggal_input']; ?></td>
                                </tr>
                            <?php $no++;
                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3">Total Terjual</th>
                                <th><?php echo $jumlah; ?></th>
                                <th>Rp.<?php echo number_format($modal); ?>,-</th>
                                <th>Rp.<?php echo number_format($bayar); ?>,-</th>
                                <!-- <th style="background:#0bb365;color:#fff;">Keuntungan</th> -->
                                <th style="background:#0bb365;color:#fff;">
                                    Rp.<?php echo number_format($bayar - $modal); ?>,-
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="clearfix" style="padding-top:5pc;"></div>
            </div>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('laporanChart').getContext('2d');
        const laporanChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                        label: 'Jumlah Terjual',
                        data: <?= json_encode($dataJumlah) ?>,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    },
                    {
                        label: 'Keuntungan',
                        data: <?= json_encode($dataKeuntungan) ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }
                ]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</section>

<script>
    function combineDate() {
        let day = document.querySelector('input[name="day"]').value.padStart(2, '0');
        let month = document.querySelector('input[name="month"]').value.padStart(2, '0');
        let year = document.querySelector('input[name="year"]').value;

        // Gabungkan jadi format YYYY-MM-DD
        let fullDate = `${year}-${month}-${day}`;
        document.getElementById('hari').value = fullDate;
    }

    function submitBulan() {
        const bln = document.querySelector('select[name="bln"]').value;
        const thn = document.querySelector('select[name="thn"]').value;
        window.location.href = `index.php?page=laporan&cari=ok&bln=${bln}&thn=${thn}`;
    }
</script>