<?php
require '../../../config.php'; // atau koneksi lain

if (isset($_GET['phone'])) {
    $phone = $_GET['phone'];
    
    $stmt = $config->prepare("SELECT name, diskon, point, status FROM member1 WHERE phone = ?");
    $stmt->execute([$phone]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        if (strtolower($data['status']) === "nonaktif" || strtolower($data['status']) === "non-active") {
            echo json_encode(['status' => 'non-active']);
        } else {
            echo json_encode([
                'status' => 'success',
                'nama' => $data['name'],
                'diskon' => $data['diskon'],
                'point' => $data['point'],
                'status_member' => $data['status']
            ]);
        }
    } else {
        echo json_encode(['status' => 'not_found']);
    }
} else {
    echo json_encode(['status' => 'error']);
}
