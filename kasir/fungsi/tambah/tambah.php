<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!empty($_SESSION['admin'])) {
    require '../../config.php';

    // Fungsi untuk upload gambar dan validasi
    function uploadImage($fileInputName, $uploadDir = '../../admin/module/admin/gambar/', $maxSize = 2097152, $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']) {
        if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
            $fileName = $_FILES[$fileInputName]['name'];
            $fileSize = $_FILES[$fileInputName]['size'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExt, $allowedTypes) && $fileSize <= $maxSize) {
                $newFileName = uniqid() . '.' . $fileExt;
                $destPath = $uploadDir . $newFileName;
                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    return $newFileName;
                } else {
                    throw new Exception("Gagal mengupload gambar.");
                }
            } else {
                throw new Exception("Format gambar tidak didukung atau ukuran terlalu besar (max 2MB)!");
            }
        }
        return null; // Tidak ada file diupload
    }

    // ================== TAMBAH KATEGORI ==================
    if (!empty($_GET['kategori'])) {
    $nama = $_POST['kategori'] ?? '';
    $tgl = date("j F Y, G:i");

    if ($nama) {
        // Cek apakah nama kategori sudah ada
        $cek = $config->prepare("SELECT * FROM kategori WHERE nama_kategori = ?");
        $cek->execute([$nama]);

        if ($cek->rowCount() > 0) {
            header("Location: ../../index.php?page=kategori&error=duplikat");
            exit;
        } else {
            $sql = 'INSERT INTO kategori (nama_kategori, tgl_input) VALUES (?, ?)';
            $stmt = $config->prepare($sql);
            $stmt->execute([$nama, $tgl]);
            header('Location: ../../index.php?page=kategori&success=tambah-data');
            exit;
        }
    }
}


// ================== TAMBAH BARANG ==================
if (!empty($_GET['barang']) && $_GET['barang'] == 'tambah') {
    $id       = $_POST['id'];
    $barcode  = $_POST['barcode'];
    $kategori = $_POST['kategori'];
    $nama     = $_POST['nama'];
    $merk     = $_POST['merk'];
    $beli     = $_POST['beli'];
    $jual     = $_POST['jual'];
    $satuan   = $_POST['satuan'];
    $stok     = $_POST['stok'];
    $tgl      = $_POST['tgl'];
    $deskripsi= $_POST['deskripsi'];

    $gambar   = $_FILES['gambar']['name'];
    $tmp      = $_FILES['gambar']['tmp_name'];

    // Cek nama barang duplikat
    $cek = $config->prepare("SELECT * FROM barang WHERE nama_barang = ?");
    $cek->execute([$nama]);

    if ($cek->rowCount() > 0) {
        echo '<script>alert("Nama barang sudah ada!"); window.location="../../index.php?page=barang&error=duplikat";</script>';
        exit;
    } else {
        move_uploaded_file($tmp, "../../admin/module/admin/gambar/" . $gambar);
        $sql = $config->prepare("INSERT INTO barang (id_barang, barcode, id_kategori, nama_barang, merk, harga_beli, harga_jual, satuan_barang, stok, tgl_input, gambar, deskripsi) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $sql->execute([$id, $barcode, $kategori, $nama, $merk, $beli, $jual, $satuan, $stok, $tgl, $gambar, $deskripsi]);
        echo '<script>alert("Data barang berhasil ditambahkan!"); window.location="../../index.php?page=barang&success";</script>';
        exit;
    }
}


    // ================== TAMBAH PENJUALAN ==================
    if (!empty($_GET['jual'])) {
        $id = $_GET['id'] ?? '';
        if (!$id) {
            header('Location: ../../index.php?page=jual');
            exit;
        }

        // Ambil data barang berdasar id
        $sql = 'SELECT * FROM barang WHERE id_barang = ?';
        $stmt = $config->prepare($sql);
        $stmt->execute([$id]);
        $barang = $stmt->fetch();

        if ($barang) {
            if ($barang['stok'] > 0) {
                $kasir = $_GET['id_kasir'] ?? null;
                $jumlah = 1;
                $total = $barang['harga_jual'];
                $tgl = date("j F Y, G:i");

                $sqlInsert = 'INSERT INTO penjualan (id_barang, id_member, jumlah, total, tanggal_input) VALUES (?, ?, ?, ?, ?)';
                $stmtInsert = $config->prepare($sqlInsert);
                $stmtInsert->execute([$id, $kasir, $jumlah, $total, $tgl]);

                header('Location: ../../index.php?page=jual&success=tambah-data');
                exit;
            } else {
                echo "<script>alert('Stok Barang Anda Telah Habis !'); window.location='../../index.php?page=jual#keranjang'</script>";
                exit;
            }
        } else {
            header('Location: ../../index.php?page=jual');
            exit;
        }
    }
}
?>
