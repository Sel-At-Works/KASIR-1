<?php
@ob_start();
session_start();
if (isset($_POST['proses'])) {
    require 'config.php';

    $user = strip_tags($_POST['user']);
    $pass = strip_tags($_POST['pass']);

    $sql = 'SELECT * FROM member WHERE nm_member = ? AND pw = MD5(?)';
    $row = $config->prepare($sql);
    $row->execute([$user, $pass]);
    $jum = $row->rowCount();

    if ($jum > 0) {
        $hasil = $row->fetch();  // Ambil data user dari hasil query
        $_SESSION['admin'] = $hasil;  // Simpan data user ke session supaya bisa dipakai di halaman lain

        // ✅ Update status menjadi Aktif saat berhasil login
        $update = $config->prepare("UPDATE member SET status = 'Aktif' WHERE id_member = ?");
        $update->execute([$hasil['id_member']]);

        echo '<script>alert("Login Sukses");window.location="index.php"</script>';
    } else {
        echo '<script>alert("Login Gagal");history.go(-1);</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword">

    <title>Login To Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <!--external css-->
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/style-responsive.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body style="background:#004643;color:#fff;">

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page" style="padding-top:3pc;">
	  	<div class="container">
		      <form class="form-login" method="POST">
		        <h2 class="form-login-heading">Kasir Toko</h2>
		        <div class="login-wrap">
		            <input type="text" class="form-control" name="user" placeholder="Username" autofocus>
		            <br>
		            <input type="password" class="form-control" name="pass" placeholder="Password">
		            <br>
		            <button class="btn btn-primary btn-block" name="proses" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
		        </div>
				<!-- <button type="button" name="btnReg" class="btn btn-info" onclick="window.location.href='register.php'">
                 Sign Up
               </button> -->
			   <button type="button" name="btnReg" class="btn btn-info" onclick="window.location.href='forgetpw.php'">
             Forget Password
            </button>


		      </form>	

	  	</div>
	  </div>
    <!-- js placed at the end of the document so the pages load faster -->
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
  </body>
</html>

