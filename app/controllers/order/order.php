<?php
require_once dirname(__DIR__, 3) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/models/OrderService.php';

$db = new Database();
$model = new OrderService($db->conn);
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
if ($method === 'POST') {
    if (!isset($_SESSION["uid"])) {
        echo json_encode(['success' => false, 'message' => 'Người dùng chưa đăng nhập']);
        return;
    }
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    if (isset($data['PhuongThucThanhToan'])) $PhuongThucThanhToan = $data['PhuongThucThanhToan'];
    else {
        echo json_encode(['success'=> false,'message'=> 'Chưa điền đủ thông tin']);
        return;
    }
    if (isset($data['MaGiamGia'])) $MaGiamGia = $data['MaGiamGia'];
    else $MaGiamGia = '';
    if (isset($data['SDT'])) $SDT = $data['SDT'];
    else {
        echo json_encode(['success'=> false,'message'=> 'Chưa điền đủ thông tin']);
        return;
    }
    if (isset($data['DiaChi'])) $DiaChi = $data['DiaChi'];
    else {
        echo json_encode(['success'=> false,'message'=> 'Chưa điền đủ thông tin']);
        return;
    }
    if (isset($data['TenNguoiNhan'])) $TenNguoiNhan = $data['TenNguoiNhan'];
    else {
        echo json_encode(['success'=> false,'message'=> 'Chưa điền đủ thông tin']);
        return;
    }
    if (isset($data['product_list'])) $product_list = explode(',', trim($data['product_list']));
    else {
        echo json_encode(['success'=> false,'message'=> 'Chưa điền đủ thông tin']);
        return;
    }
    echo json_encode($model->order($_SESSION["uid"], $PhuongThucThanhToan, $MaGiamGia, $SDT, $DiaChi, $TenNguoiNhan, $product_list));
}
else {
    $response = ['error' => 'Sai phương thức yêu cầu'];
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>