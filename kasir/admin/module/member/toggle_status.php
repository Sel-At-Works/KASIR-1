<?php
require '../../../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $statusBaru = $_POST['status'];

    $sql = "UPDATE member1 SET status = :status WHERE id = :id";
    $query = $config->prepare($sql);
    $query->execute([
        ':status' => $statusBaru,
        ':id' => $id
    ]);

    header("Location: ../../../index.php?page=member&success-edit.php");
    exit();
} else {
    echo "Akses tidak valid.";
}
