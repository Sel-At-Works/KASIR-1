<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Dashboard">
  <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

  <title>Sistem Penjualan Barang Berbasis Web </title>

  <!-- Bootstrap core CSS -->
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <!--external css-->
  <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
  <link rel="stylesheet" type="text/css" href="assets/css/zabuto_calendar.css">
  <link rel="stylesheet" type="text/css" href="assets/datatables/dataTables.bootstrap.css" />
  <link rel="stylesheet" type="text/css" href="assets/js/gritter/css/jquery.gritter.css" />
  <link rel="stylesheet" type="text/css" href="assets/lineicons/style.css">

  <!-- Custom styles for this template -->
  <link href="assets/css/style.css" rel="stylesheet">
  <link href="assets/css/style-responsive.css" rel="stylesheet">

  <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="assets/datatables/jquery.dataTables.js"></script>
  <script type="text/javascript" src="assets/datatables/dataTables.bootstrap.js"></script>
  <script type="text/javascript" src="assets/js/jquery-2.2.3.min.js"></script>
  <style>
    .header {
      background: #328f6b;
      color: #fff;
    }

    #main-content {
      background: #fff;
    }

    #hidden {
      display: none
    }

    .top-menu .cart-icon {
      font-size: 18px;
      color: #fff;
      padding: 8px 12px;
      display: inline-block;
      text-decoration: none;
    }

    .top-menu .cart-icon:hover {
      color: #fcb322;
    }
  </style>
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
  <section id="container">
    <!-- **********************************************************************************************************************************************************
      TOP BAR CONTENT & NOTIFICATIONS
      *********************************************************************************************************************************************************** -->
    <!--header start-->
    <header class="header black-bg">
      <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
      </div>
      <!--logo start-->
      <a href="index.php" class="logo"><b><?php echo $toko['nama_toko']; ?></b> <small style="padding-left:5px;font-size:13px;"><?php echo $toko['alamat_toko']; ?></small></a>
      <!--logo end-->

      <!-- notification start  -->
      <div class="top-menu">
        <ul class="nav pull top-menu">
        </ul>
      </div>
      <!-- notification end -->

      <div class="top-menu d-flex align-items-center justify-content-end">
        <ul class="nav top-menu d-flex align-items-center">

          <!-- Icon Keranjang -->
          <div class="top-menu me-5 ">
            <ul class="nav pull-right top-menu mt-2">
              <!-- Icon Keranjang -->
              <li>
                <a href="/KASIR 1/kasir/admin/module/produk/view_cart.php" class="cart-icon" title="Keranjang Belanja">
                  <i class="fa fa-shopping-cart"></i>
                </a>
              </li>

              <!-- Logout -->
              <li>
                <a class="logout" onclick="javascript: return confirm('Ingin Logout ?');" href="logout.php">Logout</a>
              </li>
            </ul>
          </div>

        </ul>
      </div>


    </header>
    <!--header end-->