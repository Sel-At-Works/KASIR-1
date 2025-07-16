<?php 
session_start();
if(!empty($_SESSION['admin'])){
	require '../../config.php';
	if (!empty($_GET['kategori']) && $_GET['kategori'] == 'hapus') {
    $id = $_GET['id'];

    // Cek apakah kategori digunakan oleh produk
    $cek = $config->prepare("SELECT * FROM barang WHERE id_kategori = ?");
    $cek->execute([$id]);

    if ($cek->rowCount() > 0) {
        echo '<script>alert("Kategori tidak dapat dihapus karena masih digunakan oleh produk!"); window.location="../../index.php?page=kategori&error=terpakai";</script>';
    } else {
        $hapus = $config->prepare("DELETE FROM kategori WHERE id_kategori = ?");
        $hapus->execute([$id]);
        echo '<script>alert("Kategori berhasil dihapus!"); window.location="../../index.php?page=kategori&remove";</script>';
    }
}

    

	// Kode untuk hapus barang
    if(!empty($_GET['barang'])){
        $id= $_GET['id'];

        // Cek stok barang
        $cekStok = $config->prepare("SELECT stok FROM barang WHERE id_barang = ?");
        $cekStok->execute([$id]);
        $stok = $cekStok->fetchColumn();

        if($stok > 0){
            echo '<script>alert("Produk tidak bisa dihapus karena masih memiliki stok."); window.location="../../index.php?page=barang&error=stok-ada";</script>';
            exit;
        }

        // Hapus dari keranjang (session)
        if(isset($_SESSION['keranjang'])){
            foreach($_SESSION['keranjang'] as $key => $item){
                if($item['id_barang'] == $id){
                    unset($_SESSION['keranjang'][$key]);
                }
            }
            // Reset indeks supaya rapi
            $_SESSION['keranjang'] = array_values($_SESSION['keranjang']);
        }

        // Hapus dari database
        $data[] = $id;
        $sql = 'DELETE FROM barang WHERE id_barang=?';
        $row = $config -> prepare($sql);
        $row -> execute($data);
        echo '<script>window.location="../../index.php?page=barang&remove=hapus-data"</script>';
    }

    // kode lain untuk hapus kategori, penjualan, dll
}
	if(!empty($_GET['jual'])){
		
		$dataI[] = $_GET['brg'];
		$sqlI = 'select*from barang where id_barang=?';
		$rowI = $config -> prepare($sqlI);
		$rowI -> execute($dataI);
		$hasil = $rowI -> fetch();
		
		/*$jml = $_GET['jml'] + $hasil['stok'];
		
		$dataU[] = $jml;
		$dataU[] = $_GET['brg'];
		$sqlU = 'UPDATE barang SET stok =? where id_barang=?';
		$rowU = $config -> prepare($sqlU);
		$rowU -> execute($dataU);*/
		
		$id = $_GET['id'];
		$data[] = $id;
		$sql = 'DELETE FROM penjualan WHERE id_penjualan=?';
		$row = $config -> prepare($sql);
		$row -> execute($data);
		echo '<script>window.location="../../index.php?page=jual"</script>';
	}
	if(!empty($_GET['penjualan'])){
		
		$sql = 'DELETE FROM penjualan';
		$row = $config -> prepare($sql);
		$row -> execute();
		echo '<script>window.location="../../index.php?page=jual"</script>';
	}
	if(!empty($_GET['laporan'])){
		
		$sql = 'DELETE FROM nota';
		$row = $config -> prepare($sql);
		$row -> execute();
		echo '<script>window.location="../../index.php?page=laporan&remove=hapus"</script>';
	}
	if (!empty($_GET['id'])) { 
		require '../../config.php';
	
		$id = intval($_GET['id']); // Pastikan ID integer
		$sql = 'DELETE FROM member WHERE id_member=?';
	
		$row = $config->prepare($sql);
		
		if ($row->execute([$id])) {
			echo "Data dengan ID $id berhasil dihapus!";
			echo '<script>window.location="../../index.php?page=kategori&&remove=hapus-data"</script>';
		} else {
			print_r($row->errorInfo()); // Debug error query
			die();
		}
	}
	if (!empty($_GET['id'])) { 
		require '../../config.php';
	
		$id = intval($_GET['id']); // Pastikan ID integer
		$sql = 'DELETE FROM member1 WHERE id=?';
	
		$row = $config->prepare($sql);
		
		if ($row->execute([$id])) {
			echo "Data dengan ID $id berhasil dihapus!";
			echo '<script>window.location="../../index.php?page=kategori&&remove=hapus-data"</script>';
		} else {
			print_r($row->errorInfo()); // Debug error query
			die();
		}
	}
	



