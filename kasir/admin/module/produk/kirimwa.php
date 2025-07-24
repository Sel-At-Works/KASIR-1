<?php

require '../../../config.php';
require_once 'FonnteWhatsapp.php';

if (isset($_GET['kirim_wa']) && isset($_GET['id_nota'])) {
    $token = 'T6uDv9JAjkPqfv8ogS9r';
    $wa = new FonnteWhatsapp($token);
    $id_nota_utama = intval($_GET['id_nota']);
    $bayar = isset($_GET['bayar']) ? intval($_GET['bayar']) : 0;
    $diskon = isset($_GET['diskon']) ? intval($_GET['diskon']) : 0;

    // Ambil semua data nota + member untuk satu transaksi
    $sql = "SELECT n.*, b.nama_barang, b.harga_jual, m.name AS nama_member, m.phone, m.status AS status_member, nu.tanggal_input
            FROM nota n
            LEFT JOIN barang b ON n.id_barang = b.id_barang
            LEFT JOIN member1 m ON n.id_member = m.id
            LEFT JOIN nota_utama nu ON n.id_nota_utama = nu.id_nota_utama
            WHERE n.id_nota_utama = ?";
    $stmt = $config->prepare($sql);
    $stmt->execute([$id_nota_utama]);
    $transaksis = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$transaksis) {
        echo "<script>alert('Transaksi tidak ditemukan!');</script>";
        exit;
    }

    // Ambil nomor WA (dari member atau fallback)
    $phone = $transaksis[0]['phone'] ?? '';
    $target = preg_replace('/[^0-9]/', '', $phone);
    if (!$target) {
        echo "<script>alert('Nomor WhatsApp tidak ditemukan!');</script>";
        exit;
    }
    if (substr($target, 0, 1) === '0') {
        $target = '62' . substr($target, 1);
    }

    // Ambil data untuk pesan
    $nama = $transaksis[0]['nama_member'] ?: 'Customer';
    $status = $transaksis[0]['status_member'] ?: 'Non-member';
    $tanggal = $transaksis[0]['tanggal_input'];

    $totalSemua = array_sum(array_column($transaksis, 'total'));
    $totalSetelahDiskon = $totalSemua - $diskon;
    $kembalian = $bayar - $totalSetelahDiskon;

    // Format pesan WhatsApp
    $pesan  = "🛒 *Struk Pembelian*\n";
    $pesan .= "Halo *{$nama}*, berikut adalah detail transaksi Anda:\n\n";
    $pesan .= "*Status Member:* {$status}\n";
    $pesan .= "*ID Transaksi:* {$id_nota_utama}\n";
    $pesan .= "*Tanggal:* {$tanggal}\n\n";
    $pesan .= "*Detail Produk:*\n";
    foreach ($transaksis as $trx) {
        $subtotal = $trx['harga_jual'] * intval($trx['jumlah']);
        $pesan .= "- {$trx['nama_barang']} x{$trx['jumlah']} @Rp" . number_format($trx['harga_jual'], 0, ',', '.') . " = Rp" . number_format($subtotal, 0, ',', '.') . "\n";
    }
    $pesan .= "\n*Total:* Rp. " . number_format($totalSemua, 0, ',', '.');
    if ($diskon > 0) {
        $pesan .= "\n*Diskon:* Rp. " . number_format($diskon, 0, ',', '.');
        $pesan .= "\n*Total Setelah Diskon:* Rp. " . number_format($totalSetelahDiskon, 0, ',', '.');
    }
    $pesan .= "\n*Bayar:* Rp. " . number_format($bayar, 0, ',', '.');
    $pesan .= "\n*Kembalian:* Rp. " . number_format($kembalian, 0, ',', '.');
    $pesan .= "\n\n🙏 Terima kasih telah berbelanja di toko kami 😊";

    // Kirim WA
    $result = $wa->sendMessage($target, $pesan);
    if ($result['status']) {
        echo "<script>alert('Pesan berhasil dikirim ke WhatsApp!'); window.location.href='http://localhost/KASIR%201/kasir/index.php?page=produk'</script>";
    } else {
        echo "<script>alert('Gagal mengirim pesan: " . $result['error'] . "');</script>";
    }
}
