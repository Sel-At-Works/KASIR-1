<?php
session_start();
require 'config.php';

if (isset($_SESSION['admin'])) {
    $id = $_SESSION['admin']['id_member'];

    // Set status ke Tidak Aktif saat logout
    $update = $config->prepare("UPDATE member SET status = 'Tidak Aktif' WHERE id_member = ?");
    $update->execute([$id]);
}

// Hancurkan session
session_destroy();

// Redirect ke halaman login
echo '<script>alert("Anda Telah Logout");window.location="login.php";</script>';
?>
