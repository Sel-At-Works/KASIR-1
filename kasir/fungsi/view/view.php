<?php
	/*
	 * PROSES TAMPIL  
	 */ 
	 class view {
		protected $db;
		function __construct($db){
			$this->db = $db;
		}
			
			function member(){
				$sql = "select member.*, login.*
						from member inner join login on member.id_member = login.id_member";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetchAll();
				return $hasil;
			}

			function member_edit($id){
				$sql = "SELECT * FROM member WHERE id_member = ?";
				$row = $this->db->prepare($sql);
				$row->execute([$id]);
				$hasil = $row->fetch();
				return $hasil;
			}
			
			function toko(){
				$sql = "select*from toko where id_toko='1'";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetch();
				return $hasil;
			}

			function kategori(){
				$sql = "select*from kategori";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetchAll();
				return $hasil;
			}

			function barang(){
				$sql = "select barang.*, kategori.id_kategori, kategori.nama_kategori
						from barang inner join kategori on barang.id_kategori = kategori.id_kategori 
						ORDER BY id DESC";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetchAll();
				return $hasil;
			}
			
			function barang_stok(){
				$sql = "select barang.*, kategori.id_kategori, kategori.nama_kategori
						from barang inner join kategori on barang.id_kategori = kategori.id_kategori 
						where stok <= 3 
						ORDER BY id DESC";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetchAll();
				return $hasil;
			}

			function barang_edit($id){
				$sql = "select barang.*, kategori.id_kategori, kategori.nama_kategori
						from barang inner join kategori on barang.id_kategori = kategori.id_kategori
						where id_barang=?";
				$row = $this-> db -> prepare($sql);
				$row -> execute(array($id));
				$hasil = $row -> fetch();
				return $hasil;
			}

			function barang_cari($cari){
				$sql = "select barang.*, kategori.id_kategori, kategori.nama_kategori
						from barang inner join kategori on barang.id_kategori = kategori.id_kategori
						where id_barang like '%$cari%' or nama_barang like '%$cari%' or merk like '%$cari%'";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetchAll();
				return $hasil;
			}

			function barang_id(){
				$sql = 'SELECT * FROM barang ORDER BY id DESC';
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetch();
				
				$urut = substr($hasil['id_barang'], 2, 3);
				$tambah = (int) $urut + 1;
				if(strlen($tambah) == 1){
					 $format = 'BR00'.$tambah.'';
				}else if(strlen($tambah) == 2){
					 $format = 'BR0'.$tambah.'';
				}else{
					$ex = explode('BR',$hasil['id_barang']);
					$no = (int) $ex[1] + 1;
					$format = 'BR'.$no.'';
				}
				return $format;
			}

			function kategori_edit($id){
				$sql = "select*from kategori where id_kategori=?";
				$row = $this-> db -> prepare($sql);
				$row -> execute(array($id));
				$hasil = $row -> fetch();
				return $hasil;
			}

			function kategori_row(){
				$sql = "select*from kategori";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> rowCount();
				return $hasil;
			}

			function barang_row(){
				$sql = "select*from barang";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> rowCount();
				return $hasil;
			}

			// function barang_stok_row(){
			// 	// $sql ="SELECT SUM(stok) as stok FROM barang";
			// 	$sql ="SELECT * FROM barang where stok";
			// 	$row = $this-> db -> prepare($sql);
			// 	$row -> execute();
			// 	$hasil = $row -> fetch();
			// 	return $hasil;
			// }

			function barang_stok_row(){
				$sql = "SELECT * FROM barang WHERE stok > 0";
				$row = $this->db->prepare($sql);
				$row->execute();
				$hasil = $row->fetchAll(); // karena banyak baris
				return $hasil;
			}			

			function barang_beli_row(){
				$sql ="SELECT SUM(harga_beli) as beli FROM barang";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetch();
				return $hasil;
			}

			// function jual_row(){
			// 	$sql ="SELECT SUM(jumlah) as stok FROM nota";
			// 	$row = $this-> db -> prepare($sql);
			// 	$row -> execute();
			// 	$hasil = $row -> fetch();
			// 	return $hasil;
			// }

			function jual_row(){
				$sql ="SELECT SUM(jumlah) as stok FROM nota";
				$row = $this->db->prepare($sql);
				$row -> execute();
				$hasil = $row->fetch();
				return $hasil;
			}
			
			function jual(){
				$sql = "SELECT nota.* , barang.id_barang, barang.nama_barang, barang.harga_beli, member.id_member,
						member.nm_member 
						FROM nota 
						LEFT JOIN barang ON barang.id_barang = nota.id_barang 
						LEFT JOIN member ON member.id_member = nota.id_member 
						ORDER BY id_nota DESC";
				$row = $this->db->prepare($sql);
				$row->execute();
				$hasil = $row->fetchAll();
				return $hasil;
			}
function periode_jual($periode){
	$sql = "SELECT nota.*, barang.id_barang, barang.nama_barang, barang.harga_beli, member.id_member,
			member.nm_member 
			FROM nota 
			LEFT JOIN barang ON barang.id_barang = nota.id_barang 
			LEFT JOIN member ON member.id_member = nota.id_member 
			WHERE DATE_FORMAT(nota.tanggal_input, '%Y-%m') = ? 
			ORDER BY id_nota ASC";
	$row = $this->db->prepare($sql);
	$row->execute(array($periode));
	$hasil = $row->fetchAll();
	return $hasil;
}


			function hari_jual($hari){
				$sql = "SELECT nota.* , barang.id_barang, barang.nama_barang,  barang.harga_beli, member.id_member,
						member.nm_member from nota 
					   left join barang on barang.id_barang=nota.id_barang 
					 left join member on member.id_member=nota.id_member 
					   WHERE DATE(nota.tanggal_input) = ? 
					   ORDER BY id_nota ASC";
				$row = $this->db->prepare($sql);
				$row->execute(array($hari));
				$hasil = $row->fetchAll();
				return $hasil;
			}

			function penjualan(){
				$sql ="SELECT penjualan.* , barang.id_barang, barang.nama_barang, member.id_member,
						member.nm_member from penjualan 
					   left join barang on barang.id_barang=penjualan.id_barang 
					   left join member on member.id_member=penjualan.id_member
					   ORDER BY id_penjualan";
				$row = $this-> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetchAll();
				return $hasil;
			}

			function jumlah(){
				$sql ="SELECT SUM(total) as bayar FROM penjualan";
				$row = $this -> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetch();
				return $hasil;
			}

			function jumlah_nota(){
				$sql ="SELECT SUM(total) as bayar FROM nota";
				$row = $this -> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetch();
				return $hasil;
			}

			function jml(){
		$sql = "SELECT SUM(harga_beli * stok) as byr FROM barang";
				$row = $this -> db -> prepare($sql);
				$row -> execute();
				$hasil = $row -> fetch();
				return $hasil;
			}
	 }
