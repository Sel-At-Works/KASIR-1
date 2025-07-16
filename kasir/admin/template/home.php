 <!--sidebar end-->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
 <!--main content start-->
 <section id="main-content">
 	<section class="wrapper">

 		<div class="row">
 			<div class="col-lg-9">
 				<div class="row" style="margin-left:1pc;margin-right:1pc;">
 					<h1>DASHBOARD</h1>
 					<hr>

 					<?php
						$sql = " select * from barang where stok <= 3";
						$row = $config->prepare($sql);
						$row->execute();
						$r = $row->rowCount();
						if ($r > 0) {
						?>
 					<?php
							echo "
							<div class='alert alert-warning'>
								<span class='glyphicon glyphicon-info-sign'></span> Ada <span style='color:red'>$r</span> barang yang Stok tersisa sudah kurang dari 3 items. silahkan pesan lagi !!
								<span class='pull-right'><a href='index.php?page=barang&stok=yes'>Tabel Barang <i class='fa fa-angle-double-right'></i></a></span>
							</div>
							";
						}
						?>
 					<?php $hasil_barang = $lihat->barang_row(); ?>
 					<?php $hasil_kategori = $lihat->kategori_row(); ?>
 					<?php $stok = $lihat->barang_stok_row(); ?>
 					<?php $jual = $lihat->jual_row(); ?>
 					<div class="row">
 						<!--STATUS PANELS -->
 						<div class="col-md-3">
 							<div class="panel panel-primary">
 								<div class="panel-heading">
 									<h5><i class="fa fa-desktop"></i> Nama Barang</h5>
 								</div>
 								<div class="panel-body">
 									<center>
 										<h1><?php echo number_format($hasil_barang); ?></h1>
 									</center>
 								</div>
 								<div class="panel-footer">
 									<h4 style="font-size:15px;font-weight:700;"><a href='index.php?page=barang'>Tabel Barang <i class='fa fa-angle-double-right'></i></a></h4>
 								</div>
 							</div><!--/grey-panel -->
 						</div><!-- /col-md-3-->
 						<!-- STATUS PANELS -->
 						<!-- <div class="col-md-3">
                      		<div class="panel panel-success">
                      			<div class="panel-heading">
						  			<h5><i class="fa fa-desktop"></i> Stok Barang</h5>
                      			</div>
                      			<div class="panel-body">
								  <?php foreach ($stok as $data): ?>
    <center><h1><?php echo number_format($data['stok']); ?></h1></center>
<?php endforeach; ?>
								</div>
								<div class="panel-footer">
									<h4 style="font-size:15px;font-weight:700;"><a href='index.php?page=barang'>Tabel Barang  <i class='fa fa-angle-double-right'></i></a></h4>
								</div>
	                      	</div>
                      	</div> -->
 						<!-- /col-md-3-->
 						<!-- STATUS PANELS -->
 						<div class="col-md-3">
 							<div class="panel panel-info">
 								<div class="panel-heading">
 									<h5><i class="fa fa-desktop"></i> Telah Terjual</h5>
 								</div>
 								<div class="panel-body">
 									<center>
 										<h1><?php echo number_format($jual['stok']); ?></h1>
 									</center>
 								</div>
 								<div class="panel-footer">
 									<h4 style="font-size:15px;font-weight:700;font-weight:700;"><a href='index.php?page=laporan'>Tabel laporan <i class='fa fa-angle-double-right'></i></a></h4>
 								</div>
 							</div>
 							<!--/grey-panel -->
 						</div><!-- /col-md-3-->
 						<div class="col-md-3">
 							<div class="panel panel-danger">
 								<div class="panel-heading">
 									<h5><i class="fa fa-desktop"></i> Kategori Barang</h5>
 								</div>
 								<div class="panel-body">
 									<center>
 										<h1><?php echo number_format($hasil_kategori); ?></h1>
 									</center>
 								</div>
 								<div class="panel-footer">
 									<h4 style="font-size:15px;font-weight:700;"><a href='index.php?page=kategori'>Tabel Kategori <i class='fa fa-angle-double-right'></i></a></h4>
 								</div>
 							</div><!--/grey-panel -->
 							<?php
								// Ambil data statistik dari database
								$dataStat = $config->prepare("SELECT tanggal_input, SUM(jumlah) as total_jumlah FROM nota GROUP BY tanggal_input ORDER BY tanggal_input ASC");
								$dataStat->execute();
								$dataNota = $dataStat->fetchAll();

								// Siapkan array untuk label dan data
								$labels = [];
								$jumlahs = [];

								foreach ($dataNota as $row) {
									$labels[] = $row['tanggal_input'];
									$jumlahs[] = $row['total_jumlah'];
								}
								?>
 						</div><!-- /col-md-3-->
 					</div>
 				</div>
 				<div class="col-md-12">
 					<div class="panel panel-default">
 						<div class="panel-heading">
 							<h4><i class="fa fa-line-chart"></i> Grafik Penjualan per Tanggal</h4>
 						</div>
 						<div class="panel-body">
 							<!-- Canvas untuk Grafik -->
 							<canvas id="lineChart" height="400" width="800"></canvas>
 						</div>
 					</div>
 				</div>

 				<script>
 					const ctx = document.getElementById('lineChart').getContext('2d');
 					const lineChart = new Chart(ctx, {
 						type: 'line',
 						data: {
 							labels: <?= json_encode($labels); ?>,
 							datasets: [{
 								label: 'Jumlah Penjualan',
 								data: <?= json_encode($jumlahs); ?>,
 								borderColor: 'rgba(54, 162, 235, 1)',
 								backgroundColor: 'rgba(54, 162, 235, 0.2)',
 								fill: true,
 								tension: 0.3,
 								pointRadius: 5,
 								pointBackgroundColor: 'rgba(54, 162, 235, 1)',
 							}]
 						},
 						options: {
 							responsive: true,
 							maintainAspectRatio: false, // Memungkinkan grafik untuk menyesuaikan ukuran
 							scales: {
 								y: {
 									beginAtZero: true,
 									title: {
 										display: true,
 										text: 'Jumlah'
 									}
 								},
 								x: {
 									title: {
 										display: true,
 										text: 'Tanggal'
 									}
 								}
 							}
 						}
 					});
 				</script>
 			</div><!-- /col-lg-9 END SECTION MIDDLE -->

 			<!-- **********************************************************************************************************************************************************
      RIGHT SIDEBAR CONTENT
      *********************************************************************************************************************************************************** -->

 			<div class="col-lg-3 ds">
 				<div id="calendar" class="mb">
 					<div class="panel green-panel no-margin">
 						<div class="panel-body">
 							<div id="date-popover" class="popover top" style="cursor: pointer; disadding: block; margin-left: 33%; margin-top: -50px; width: 175px;">
 								<div class="arrow"></div>
 								<h3 class="popover-title" style="disadding: none;"></h3>
 								<div id="date-popover-content" class="popover-content"></div>
 							</div>
 							<div id="my-calendar"></div>
 						</div>
 					</div>
 				</div><!-- / calendar -->
 			</div><!-- /col-lg-3 -->
 		</div>
 		<! --/row -->
 			<div class="clearfix" style="padding-top:18%;"></div>
 	</section>
 </section>


 <!-- **********************************************************************************************************************************************************
      statistik
      *********************************************************************************************************************************************************** -->