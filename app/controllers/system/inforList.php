<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/SystemService.php';

$db = new Database();
$model = new SystemService($db->conn);
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'GET') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    $response = $model->getInfoList();
    if ($response) {
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'Error', 'message' => 'Có lỗi xảy ra!']);
    }
}
else {
    $response = ['error' => 'Invalid request method'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>