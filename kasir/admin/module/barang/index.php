<!-- SIDEBAR END -->

<head>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap JS -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>

<!-- MAIN CONTENT START -->
<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12 main-chart">
				<h3>Data Barang</h3>
				<br />

				<!-- Notifikasi -->
				<?php if (isset($_GET['success-stok'])): ?>
					<div class="alert alert-success">
						<p>Tambah Stok Berhasil!</p>
					</div>
				<?php endif; ?>
				<?php if (isset($_GET['success'])): ?>
					<div class="alert alert-success">
						<p>Tambah Data Berhasil!</p>
					</div>
				<?php endif; ?>
				<?php if (isset($_GET['remove'])): ?>
					<div class="alert alert-danger">
						<p>Hapus Data Berhasil!</p>
					</div>
				<?php endif; ?>

				<!-- Peringatan stok menipis -->
				<?php
				$sql = "SELECT * FROM barang WHERE stok <= 3";
				$row = $config->prepare($sql);
				$row->execute();
				$r = $row->rowCount();
				if ($r > 0):
				?>
					<div class='alert alert-warning'>
						<span class='glyphicon glyphicon-info-sign'></span>
						Ada <span style='color:red'><?= $r ?></span> barang yang stoknya kurang dari 3.
						<span class='pull-right'><a href='index.php?page=barang&stok=yes'>Cek Barang <i class='fa fa-angle-double-right'></i></a></span>
					</div>
				<?php endif; ?>

				<!-- Tombol Aksi -->
				<button type="button" class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#myModal">
					<i class="fa fa-plus"></i> Insert Data
				</button>
				<a href="index.php?page=barang&stok=yes" class="btn btn-warning btn-md pull-right" style="margin-right:0.5pc;">
					<i class="fa fa-list"></i> Sortir Stok Kurang
				</a>
				<a href="index.php?page=barang" class="btn btn-success btn-md pull-right" style="margin-right:0.5pc;">
					<i class="fa fa-refresh"></i> Refresh Data
				</a>
				<div class="clearfix"></div>
				<br />

				<!-- Tabel Barang -->
				<div class="modal-view">
					<table class="table table-bordered table-striped" id="example1">
						<thead>
							<tr style="background:#DFF0D8;color:#333;">
								<th>No.</th>
								<th>ID Barang</th>
								<th>Barcode</th>
								<th>Gambar</th>
								<th>Kategori</th>
								<th>Nama Barang</th>
								<th>Merk</th>
								<th>Stok</th>
								<th>Harga Beli</th>
								<th>Harga Jual</th>
								<th>Satuan</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$totalBeli = $totalJual = $totalStok = 0;
							$hasil = ($_GET['stok'] == 'yes') ? $lihat->barang_stok() : $lihat->barang();
							$no = 1;
							foreach ($hasil as $isi):
							?>
								<tr>
									<td><?= $no++ ?></td>
									<td><?= $isi['id_barang'] ?></td>
									<td>
										<svg id="barcode<?= $isi['id_barang'] ?>"></svg>
										<script>
											JsBarcode("#barcode<?= $isi['id_barang'] ?>", "<?= $isi['barcode'] ?>", {
												format: "CODE128",
												width: 2,
												height: 40,
												displayValue: true
											});
										</script>
									</td>
									<td><img src="admin/module/admin/gambar/<?= $isi['gambar'] ?>" width="150px" alt=""></td>
									<td><?= $isi['nama_kategori'] ?></td>
									<td><?= $isi['nama_barang'] ?></td>
									<td><?= $isi['merk'] ?></td>
									<td>
										<?php if ($isi['stok'] == 0): ?>
											<button class="btn btn-danger btn-xs">Habis</button>
										<?php else: ?>
											<?= $isi['stok'] ?>
										<?php endif; ?>
									</td>
									<td>Rp.<?= number_format($isi['harga_beli']) ?>,-</td>
									<td>Rp.<?= number_format($isi['harga_jual']) ?>,-</td>
									<td><?= $isi['satuan_barang'] ?></td>
									<td>
										<?php if ($isi['stok'] <= 3): ?>
											<form method="POST" action="fungsi/edit/edit.php?stok=edit">
												<input type="number" name="restok" class="form-control" placeholder="Jumlah">
												<input type="hidden" name="id" value="<?= $isi['id_barang'] ?>">
												<button class="btn btn-primary btn-sm">Restok</button>
												<a href="fungsi/hapus/hapus.php?barang=hapus&id=<?= $isi['id_barang'] ?>" onclick="return confirm('Hapus Data barang ?');" class="btn btn-danger btn-sm">Hapus</a>
											</form>
										<?php else: ?>
											<a href="index.php?page=barang/details&barang=<?= $isi['id_barang'] ?>" class="btn btn-primary btn-xs">Details</a>
											<a href="index.php?page=barang/edit&barang=<?= $isi['id_barang'] ?>" class="btn btn-warning btn-xs">Edit</a>
											<a href="fungsi/hapus/hapus.php?barang=hapus&id=<?= $isi['id_barang'] ?>" onclick="return confirm('Hapus Data barang ?');" class="btn btn-danger btn-xs">Hapus</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php
								$totalBeli += $isi['harga_beli'] * $isi['stok'];
								$totalJual += $isi['harga_jual'] * $isi['stok'];
								$totalStok += $isi['stok'];
							endforeach;
							?>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="5">Total</th>
								<th><?= $totalStok ?></th>
								<th>Rp.<?= number_format($totalBeli) ?>,-</th>
								<th>Rp.<?= number_format($totalJual) ?>,-</th>
								<th colspan="2" style="background:#ddd"></th>
							</tr>
						</tfoot>
					</table>
				</div>

				<!-- MODAL TAMBAH BARANG -->
				<div id="myModal" class="modal fade" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content" style="border-radius:0px;">
							<div class="modal-header" style="background:#285c64;color:#fff;">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title"><i class="fa fa-plus"></i> Tambah Barang</h4>
							</div>
							<form action="fungsi/tambah/tambah.php?barang=tambah" method="POST" enctype="multipart/form-data">
								<div class="modal-body">
									<table class="table table-striped">
										<?php $format = $lihat->barang_id(); ?>
										<tr>
											<td>ID Barang</td>
											<td><input type="text" readonly value="<?= $format ?>" class="form-control" name="id"></td>
										</tr>
										<tr>
											<td>Barcode</td>
											<td><input type="text" name="barcode" class="form-control" id="barcodeInput" readonly required></td>
										</tr>

										<tr>
											<td>Kategori</td>
											<td>
												<select name="kategori" class="form-control" required>
													<option value="#">Pilih Kategori</option>
													<?php foreach ($lihat->kategori() as $isi): ?>
														<option value="<?= $isi['id_kategori'] ?>"><?= $isi['nama_kategori'] ?></option>
													<?php endforeach; ?>
												</select>
											</td>
										</tr>
										<tr>
											<td>Nama Barang</td>
											<td><input type="text" name="nama" class="form-control" placeholder="Nama Barang" required></td>
										</tr>
										<tr>
											<td>Merk Barang</td>
											<td><input type="text" name="merk" class="form-control" placeholder="Merk Barang" required></td>
										</tr>
										<tr>
											<td>Harga Beli( modal )</td>
											<td><input type="number" name="beli" class="form-control" placeholder="Harga Beli" required></td>
										</tr>
										<tr>
											<td>Harga Jual</td>
											<td><input type="number" name="jual" class="form-control" placeholder="Harga Jual" required></td>
										</tr>
										<tr>
											<td>Satuan Barang</td>
											<td>
												<select name="satuan" class="form-control" required>
													<option value="#">Pilih Satuan</option>
													<option value="PCS">PCS</option>
												</select>
											</td>
										</tr>
										<tr>
											<td>Stok</td>
											<td><input type="number" name="stok" class="form-control" placeholder="Stok" required></td>
										</tr>
										<tr>
											<td>Tanggal Input</td>
											<td><input type="text" name="tgl" class="form-control" value="<?= date("j F Y, G:i") ?>" readonly></td>
										</tr>
										<tr>
											<td>Gambar Product</td>
											<td><input type="file" name="gambar" class="form-control" required></td>
										</tr>
										<tr>
											<td>Deskripsi</td>
											<td><textarea name="deskripsi" class="form-control" placeholder="Masukkan deskripsi barang" required></textarea></td>
										</tr>
									</table>
								</div>
								<div class="modal-footer">
									<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Insert Data</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<!-- END MODAL -->
			</div>
		</div>
	</section>
</section>
<!-- MAIN CONTENT END -->
<script>
	// Tunggu sampai seluruh halaman selesai dimuat
	$(window).on('load', function() {
		// Fungsi untuk generate barcode sesuai id_barang
		function generateBarcodeFromId() {
			var idBarang = $("input[name='id']").val();
			if (idBarang) {
				$('#barcodeInput').val(idBarang);
			}
		}

		// Saat modal ditampilkan, jalankan fungsi generateBarcodeFromId
		$('#myModal').on('shown.bs.modal', function() {
			generateBarcodeFromId();
		});
	});
</script>