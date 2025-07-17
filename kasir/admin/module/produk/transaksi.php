<?php
session_start();
require '../../../config.php';

// INITIALIZE VARIABLES
$inputPhone = $_POST['phone'] ?? '';
$diskon_poin = 0;
$total_bayar = 0;
$diskon_tetap = 0;
$potongan_diskon_tetap = 0;
$kembalian = 0;
$diskon_pakai = 0;

// FETCH MEMBER POINT IF PHONE IS PROVIDED
if (!empty($inputPhone)) {
    $stmt = $config->prepare("SELECT point FROM member1 WHERE phone = ?");
    $stmt->execute([$inputPhone]);
    $poin_user = $stmt->fetchColumn();
    $diskon_poin = floor($poin_user / 10) * 1000;
}

// CHECK ADMIN SESSION
if (!isset($_SESSION['admin']['id_member'])) {
    header('Location: login.php');
    exit;
}

$id = $_SESSION['admin']['id_member'];

// GET PHONE NUMBER FROM ID MEMBER
$stmt = $config->prepare("SELECT phone FROM member1 WHERE id = ?");
$stmt->execute([$id]);
$phone = $stmt->fetchColumn() ?: '';

$keranjang = $_SESSION['keranjang'] ?? [];

// UPDATE QUANTITY IN CART
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-keranjang'])) {
    foreach ($_POST['jumlah'] as $index => $new_quantity) {
        if (isset($_SESSION['keranjang'][$index])) {
            $_SESSION['keranjang'][$index]['jumlah'] = $new_quantity;
        }
    }
}

// Tambahkan validasi server-side: jika member non aktif, tolak transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['phone']) && trim($_POST['phone']) !== '') {
    $phoneInput = trim($_POST['phone']);
    $stmt = $config->prepare("SELECT status FROM member1 WHERE phone = ?");
    $stmt->execute([$phoneInput]);
    $statusMember = $stmt->fetchColumn();
    if ($statusMember && strtolower($statusMember) !== 'aktif') {
        echo "<div style='text-align:center;color:red;font-weight:bold;margin-top:30px;'>Member dengan nomor telepon ini statusnya tidak aktif. Transaksi tidak dapat dilanjutkan!<br><a href='javascript:history.back()' class='btn btn-danger mt-3'>Kembali</a></div>";
        exit;
    }
}
?>

<!-- Continue with your HTML and PHP -->

<head>
    <style>
        /* General styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fa;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h3 {
            color: #34495e;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .panel {
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #e1e8f0;
            background-color: #fff;
        }

        .panel-heading {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            border-radius: 5px 5px 0 0;
        }

        .panel-body {
            padding: 20px;
        }

        .table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            background-color: #fff;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #e1e8f0;
        }

        .table th {
            background-color: #f1f3f5;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }

        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        /* Input fields */
        input.form-control {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            font-size: 14px;
        }

        input.form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-success {
            background-color: #28a745;
            color: #fff;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #fff;
            border: none;
        }

        .btn-default {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }

        .btn:hover {
            opacity: 0.8;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }

        .pull-right {
            float: right;
        }
    </style>
</head>

<section id="main-content">
    <section class="wrapper">
        <div class="row">
            <div class="col-lg-12 main-chart">
                <!-- Success and Error Alerts -->
                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success">
                        <p>Edit Data Berhasil !</p>
                    </div>
                <?php } ?>
                <?php if (isset($_GET['remove'])) { ?>
                    <div class="alert alert-danger">
                        <p>Hapus Data Berhasil !</p>
                    </div>
                <?php } ?>

                <!-- Cash Register -->
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h2><i class="fa fa-shopping-cart"></i> KASIR
                            </h2>
                        </div>
                        <div class="panel-body">
                            <div id="keranjang">
                                <!-- Date Table -->
                                <table class="table table-bordered">
                                    <tr>
                                        <td><b>Tanggal</b></td>
                                        <td><input type="text" readonly="readonly" class="form-control" value="<?php echo date("j F Y, G:i"); ?>" name="tgl"></td>
                                    </tr>
                                </table>

                                <!-- Cart Table -->
                                <table class="table table-bordered" id="example1">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Nama Barang</td>
                                            <td style="width:10%;">Jumlah</td>
                                            <td style="width:20%;">Total Per Item</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_bayar = 0;
                                        $no = 1;
                                        $hasil_penjualan = $keranjang;
                                        ?>
                                        <?php foreach ($hasil_penjualan as $index => $isi) { ?>
                                            <tr>
                                                <form method="POST" action="transaksi.php">
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $isi['nama_barang']; ?></td>
                                                    <td>
                                                        <!-- Form to update item quantity -->
                                                        <input type="number" name="jumlah[<?php echo $index; ?>]" value="<?php echo $isi['jumlah']; ?>" class="form-control" min="1">
                                                    </td>
                                                    <td>Rp.<?= number_format($isi['harga_beli'] * $isi['jumlah']); ?>,-</td>

                                                </form>
                                            </tr>
                                        <?php
                                            $no++;
                                            $total_bayar += $isi['harga_beli'] * $isi['jumlah'];
                                        } ?>
                                    </tbody>
                                </table>
                                <br />

                                <!-- Total Calculation and Payment Form -->
                                <?php $hasil = [] ?>
                                <div id="kasirnya">
                                    <table class="table table-stripped">
                                        <form id="checkout" method="POST" action="checkout.php">
                                            <?php foreach ($hasil_penjualan as $index => $isi) { ?>
                                                <input type="hidden" name="checkout[]" value="<?= $index ?>">
                                            <?php } ?>

                                            <!-- Diskon dari Poin -->
                                            <!-- <tr>
                                                <td>Diskon dari Poin</td>
                                                <td>Diskon dari Poin</td>
                                                <td colspan="3">
                                                    Rp. <?= number_format($diskon_poin) ?>,-
                                                </td>
                                            </tr> -->

                                            <!-- Total Setelah Diskon -->
                                            <tr>
                                                <td>Total Harga</td>
                                                <td>
                                                    <input type="text" class="form-control" id="total" name="total" value="<?= $total_bayar ?>" readonly>
                                                </td>
                                                <td>Bayar</td>
                                                <td>
                                                    <input type="number" class="form-control" id="bayar" name="bayar" oninput="hitungKembalian()" required>
                                                </td>
                                                <td>
                                                    <button type="button" onclick="bayarSekarang()" class="btn btn-success"><i class="fa fa-shopping-cart"></i> Bayar</button>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>Nomor Telepon</td>
                                                <td colspan="4">
                                                    <input type="text" class="form-control" name="phone" id="phone" value="<?= htmlspecialchars($phone) ?>" placeholder="Masukkan nomor telepon">
                                                    <button type="button" class="btn btn-warning" onclick="cekDiskon()">Cek Diskon</button>
                                                </td>
                                            </tr>
                                            <!-- <tr>
                                                <td>Diskon Member</td>
                                                <td colspan="4">
                                                    <input type="text" class="form-control" id="diskon_member" name="diskon" readonly>
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td>Diskon Member (<span id="diskonMember">0%</span>)</td>
                                                <td colspan="3"><span id="totalDiskonMember">Rp. 0</span></td>
                                            </tr>
                                            <tr>
                                                <td>Total Setelah Diskon</td>
                                                <td colspan="3"><span id="totalSetelahDiskon">Rp. 0</span></td>
                                            </tr>
                                            <input type="hidden" id="diskonPoin" name="diskonPoin" value="0">
                                            <input type="hidden" id="totalSetelahDiskonInput" name="totalSetelahDiskonInput" value="0">
                                        </form>

                                        </tfoot>
                                    </table>

                                    <tr>
                                        <td>Kembali</td>
                                        <td><input type="text" class="form-control" id="kembalian" name="kembalian" readonly></td>
                                        <td></td>
                                        <td>
                                            <?php
                                            // Di checkout.php setelah berhasil
                                            $_SESSION['print_data'] = [
                                                'nm_member' => $_SESSION['admin']['nm_member'],
                                                'bayar' => $bayar,
                                                'diskon' => $potongan_diskon_tetap + $diskon_pakai,
                                                'kembali' => $kembalian
                                            ];
                                            header("Location: kasir.php?success=1&print=1");
                                            ?>

                                            </a>
                                        </td>
                                    </tr>
                                    </table>
                                    <!-- <?php if (isset($_GET['print']) && isset($_SESSION['print_data'])): ?>
                                        <a href="print.php?nm_member=<?= $_SESSION['print_data']['nm_member'] ?>&bayar=<?= $_SESSION['print_data']['bayar'] ?>&kembali=<?= $_SESSION['print_data']['kembali'] ?>" target="_blank">
                                            <button class="btn btn-default"><i class="fa fa-print"></i> Print Untuk Bukti Pembayaran</button>
                                        </a>
                                        <br><br>
                                        <?php unset($_SESSION['print_data']); ?>
                                    <?php endif; ?> -->

                                    <br />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>

<script>
    // Function to calculate change (kembalian) on user input
    function hitungKembalian() {
        var total = parseInt(document.getElementById('total').value);
        var bayar = parseInt(document.getElementById('bayar').value);
        var kembalian = bayar - total;
        document.getElementById('kembalian').value = kembalian;
    }


    // AJAX call for autocomplete 
    $(document).ready(function() {
        $("#cari").change(function() {
            $.ajax({
                type: "POST",
                url: "fungsi/edit/edit.php?cari_barang=yes",
                data: 'keyword=' + $(this).val(),
                beforeSend: function() {
                    $("#hasil_cari").hide();
                    $("#tunggu").html('<p style="color:green"><blink>tunggu sebentar</blink></p>');
                },
                success: function(html) {
                    $("#tunggu").html('');
                    $("#hasil_cari").show();
                    $("#hasil_cari").html(html);
                }
            });
        });
    });


    function bayarSekarang() {
        // Pastikan totalSetelahDiskonInput selalu diupdate sebelum submit
        var totalSetelahDiskon = document.getElementById('totalSetelahDiskon');
        var totalSetelahDiskonInput = document.getElementById('totalSetelahDiskonInput');
        if (totalSetelahDiskon && totalSetelahDiskonInput) {
            var val = totalSetelahDiskon.innerText.replace(/[^\d,]/g, '');
            var num = parseInt(val.split(',')[0].replace(/\./g, '')) || 0;
            if (num === 0) {
                var totalInput = document.getElementById('total');
                totalSetelahDiskonInput.value = parseInt(totalInput.value);
            } else {
                totalSetelahDiskonInput.value = num;
            }
        }
        var bayar = document.getElementById('bayar').value;
        var totalText = document.getElementById('totalSetelahDiskon').innerText.replace(/[^\d,]/g, '');
        var totalParts = totalText.split(',');
        var total = parseInt(totalParts[0].replace(/\./g, '')) || 0;
        if (bayar === '' || isNaN(bayar) || parseInt(bayar) <= 0) {
            alert("Masukkan jumlah pembayaran yang valid!");
            document.getElementById('bayar').focus();
            return;
        }
        if (parseInt(bayar) < total) {
            if (phone === "") {
                alert("Transaksi tanpa member: Masukkan nominal yang sesuai!");
            } else {
                alert("Masukkan nominal yang sesuai!");
            }
            document.getElementById('bayar').focus();
            return;
        }
        // Jika pas atau lebih, proses transaksi
        hitungKembalian();
        document.getElementById('checkout').submit();
    }

    function hitungKembalian() {
        // Ambil nilai total akhir dari input hidden, jika 0 ambil dari input total
        var totalSetelahDiskonInput = document.getElementById('totalSetelahDiskonInput');
        var totalInput = document.getElementById('total');
        var total = parseInt(totalSetelahDiskonInput.value) || 0;
        if (total === 0 && totalInput) {
            total = parseInt(totalInput.value) || 0;
        }
        var bayar = parseInt(document.getElementById('bayar').value);
        var kembalian = bayar - total;
        document.getElementById('kembalian').value = kembalian;
    }

    function cekDiskon() {
        const phone = document.getElementById("phone").value;

        if (!phone) {
            alert("Masukkan nomor telepon terlebih dahulu!");
            return;
        }

        fetch("cek_diskon.php?phone=" + encodeURIComponent(phone))
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    alert(
                        `Nama: ${data.nama}\n` +
                        `Status: ${data.status_member}`
                    );

                    const total = document.getElementById("total").value;
                    const point = data.point || 0;
                    let diskonPoin = 0;
                    let usedPoin = 0;
                    if (point >= 20 && point < 30) {
                        diskonPoin = 0.2;
                        usedPoin = 20;
                    } else if (point >= 30 && point < 40) {
                        diskonPoin = 0.3;
                        usedPoin = 30;
                    } else if (point >= 40 && point < 50) {
                        diskonPoin = 0.4;
                        usedPoin = 40;
                    } else {
                        diskonPoin = 0;
                        usedPoin = 0;
                    }

                    const totalHarga = parseInt(total);
                    const diskon = (diskonPoin * totalHarga);
                    document.getElementById("diskonPoin").value = usedPoin;
                    document.getElementById("diskonMember").innerText = (diskonPoin * 100) + "%";
                    document.getElementById("totalDiskonMember").innerText = diskon.toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    });
                    document.getElementById("totalSetelahDiskon").innerText = (totalHarga - diskon).toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    });
                    document.getElementById("totalSetelahDiskonInput").value = totalHarga - diskon;
                } else if (data.status === "non-active") {
                    alert("Member dengan nomor telepon ini tidak aktif.");
                } else {
                    alert("Terjadi kesalahan.");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Gagal mengambil data diskon.");
            });
    }

    window.onload = function() {
        var totalSetelahDiskon = document.getElementById('totalSetelahDiskon');
        var totalInput = document.getElementById('total');
        var totalSetelahDiskonInput = document.getElementById('totalSetelahDiskonInput');
        if (totalSetelahDiskon && totalInput && totalSetelahDiskonInput) {
            var val = totalSetelahDiskon.innerText.replace(/[^\d,]/g, '');
            var num = parseInt(val.split(',')[0].replace(/\./g, '')) || 0;
            if (num === 0) {
                totalSetelahDiskon.innerText = parseInt(totalInput.value).toLocaleString('id-ID', {style:'currency',currency:'IDR'});
                totalSetelahDiskonInput.value = parseInt(totalInput.value);
            } else {
                totalSetelahDiskonInput.value = num;
            }
        }
        hitungKembalian();
    }
    // Update totalSetelahDiskonInput setiap kali field bayar atau phone diubah
    ['bayar','phone'].forEach(function(id){
        var el = document.getElementById(id);
        if(el){
            el.addEventListener('input', function(){
                var totalSetelahDiskon = document.getElementById('totalSetelahDiskon');
                var totalSetelahDiskonInput = document.getElementById('totalSetelahDiskonInput');
                var totalInput = document.getElementById('total');
                if (totalSetelahDiskon && totalSetelahDiskonInput) {
                    var val = totalSetelahDiskon.innerText.replace(/[^\d,]/g, '');
                    var num = parseInt(val.split(',')[0].replace(/\./g, '')) || 0;
                    if (num === 0 && totalInput) {
                        totalSetelahDiskonInput.value = parseInt(totalInput.value) || 0;
                    } else {
                        totalSetelahDiskonInput.value = num;
                    }
                }
                hitungKembalian();
            });
        }
    });
</script>