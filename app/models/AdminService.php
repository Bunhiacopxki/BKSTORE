<?php
require_once dirname(__DIR__, 1) . '/models/support.php';
require_once dirname(__DIR__, 1) . '/models/UserService.php';
require_once dirname(__DIR__, 1) . '/models/ProductService.php';


class AdminService {
    private $conn;
    private $support;
    private $user;
    private $product;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->support = new support();
        $this->user = new UserService($conn);
        $this->product = new ProductService($conn);
    }

    public function getUsers() {
        $sql = "SELECT `UID` FROM khach_hang";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $user = $this->user->getUserInfo($row["UID"]);
            if ($user['success'] === false) {
                return ['success' => false, 'message' => $user['message']];
            }
            
            $login = $this->user->getLoginInfo($row["UID"]);
            if ($login['success'] === false) {
                return ['success' => false, 'message' => $login['message']];
            }

            if (!$user || !$login) {
                return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
            }

            $users[] = [
                'uid' => $row['UID'],
                'name' => $login['name'],
                'role' => $login['role'],
                'email'=> $login['email'],
                'password' => $login['password'],
                'phone' => $user['phone'],
                'sex' => $user['sex'],
                'address' => $user['address'],
                'avatar' => $login['avatar'],
                'status' => $user['status']
            ];
        }
        return ['success' => true, 'user-list' => $users];
    }

    public function updateUser($UID, $name, $email, $phone, $sex, $addr, $status) {
        $sql1 = "UPDATE LOGIN SET Ten = ?, Email = ? WHERE UID = ?";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->bind_param("ssi", $name, $email, $UID);
        $stmt1->execute();
        
        if ($stmt1->affected_rows === -1) {
            return ["success" => false, "message" => "Error updating LOGIN table"];
        }
        
        $stmt1->close();

        // Second update statement
        $sql2 = "
        UPDATE KHACH_HANG SET
            GioiTinh = ?, SDT = ?, DiaChi = ?, TrangThai = ?
        WHERE UID = ?";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->bind_param("ssssi", $sex, $phone, $addr, $status, $UID);
        $stmt2->execute();
        if ($stmt2->affected_rows === -1) {
            return ["success" => false, "message" => "Error updating KHACH_HANG table"];
        }
        return ["success" => true, "message" => "Sửa thông tin thành công"];
    }

    public function product() {
        return ['success' => true, 'product_list' => $this->productInfo(), 'product_category' => $this->product->getType()];
    }

    public function addProductInfo($TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB) {
        $sql = "SELECT ID_SP FROM san_pham WHERE TenSp = ? AND NXB = ? AND NamXB = ? AND TacGia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssis", $TenSp, $NXB, $NamXB, $TacGia);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return ["success" => false, "message" => "Sản phẩm đã có trong kho"];
        }
        $sql = "INSERT INTO san_pham (TenSp, MoTa, Gia, TyLeGiamGia, SoLuongKho, NXB, KichThuoc, SoTrang, PhanLoai, TuKhoa, HinhThuc, TacGia, NgonNgu, NamXB) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssidississsssi",$TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB);
        $stmt->execute();
        $sql = "SELECT ID_SP FROM san_pham WHERE TenSp = ? AND NXB = ? AND NamXB = ? AND TacGia = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssis", $TenSp, $NXB, $NamXB, $TacGia);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        return ["success" => true, "product_id" => $product['ID_SP']];
    }

    public function addProductImage ($Anh, $ID_SP) {
        $relativeAvatarPath = "public/image/product/$ID_SP/" . basename($Anh);
        $sql = "INSERT INTO hinh_anh (Anh, ID_SP) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $relativeAvatarPath, $ID_SP);
        $stmt->execute();
        return ['success' => true, 'message' => 'Thêm sản phẩm thành công!'];
    }

    public function propose() {
        $propose_list = $this->proposeInfo();
        foreach ($propose_list as &$propose) {
            $user = $this->user->getUserInfo($propose["UID"]);
            if ($user['success'] === false) {
                return ['success' => false, 'message' => $user['message']];
            }
            
            $login = $this->user->getLoginInfo($propose["UID"]);
            if ($login['success'] === false) {
                return ['success' => false, 'message' => $login['message']];
            }

            if (!$user || !$login) {
                return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
            }            
            $propose['ten'] = $login['name'];
            $propose['SDT'] = $user['phone'];
            $propose['email'] = $login['email'];
            $propose['gioi_tinh'] = $user['sex'];
        }
        return ['success'=> true, 'message'=> $propose_list];
    }

    public function updatePropose($status, $content, $id) {
        $sql = 'SELECT * FROM san_pham_de_xuat WHERE MaDeXuat = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success"=> false,"message"=> "Không tìm thấy đề xuất"];
        }
        if ($status != 'Đang chờ duyệt' && $status != 'Đã duyệt' && $status != 'Đã từ chối') 
            return ['success'=> false, 'message' => 'Cập nhật thất bại'];
        $sql = "UPDATE san_pham_de_xuat SET TrangThai = ?, GhiChu = ? WHERE MaDeXuat = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi",$status, $content, $id);
        $stmt->execute();
        return ['success' => true, 'message' => 'Cập nhật thành công'];
    }

    public function statusComment($id, $trang_thai) {
        if (!is_numeric($id)) return ['success' => false, 'message' => 'Không tìm thấy bình luận'];
        $sql = 'SELECT * FROM binh_luan WHERE MaBinhLuan = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ['success'=> false, 'message'=> 'Không tìm thấy bình luận'];
        $sql = 'UPDATE binh_luan SET TrangThai = ? WHERE MaBinhLuan = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $trang_thai, $id);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Cập nhật trạng thái thành công'];
    }

    public function repComment($id, $content) {
        if (!is_numeric($id)) return ['success' => false, 'message' => 'Không tìm thấy bình luận'];
        $sql = 'UPDATE binh_luan SET PhanHoi = ? WHERE MaBinhLuan = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $content, $id);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Phản hồi thành công'];
    }

    public function statusReview($id, $trang_thai) {
        if (!is_numeric($id)) return ['success' => false, 'message' => 'Không tìm thấy đánh giá'];
        $sql = 'SELECT * FROM danh_gia WHERE MaDanhGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ['success'=> false, 'message'=> 'Không tìm thấy đánh giá'];
        $sql = 'UPDATE danh_gia SET TrangThai = ? WHERE MaDanhGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $trang_thai, $id);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Cập nhật trạng thái thành công'];
    }

    public function repReview($id, $content) {
        if (!is_numeric($id)) return ['success' => false, 'message' => 'Không tìm thấy đánh giá'];
        $sql = 'UPDATE danh_gia SET PhanHoi = ? WHERE MaDanhGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $content, $id);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Phản hồi thành công'];
    }

    public function sale() {
        $sql = 'SELECT * FROM ma_giam_gia WHERE TrangThai != ?';
        $stmt = $this->conn->prepare($sql);
        $rac = 'Đã xóa';
        $stmt->bind_param('s', $rac);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }

    public function conditionSale() {
        $sql = 'SELECT DieuKien FROM ma_giam_gia WHERE TrangThai != ?';
        $stmt = $this->conn->prepare($sql);
        $rac = 'Đã xóa';
        $stmt->bind_param('s', $rac);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row['DieuKien'];
        }
        return $result;
    }

    public function updateSale($ID_GiamGia, $Ma, $TienGiam, $DieuKien, $SoLuong, $TrangThai) {
        $rac = $this->getSaleById($ID_GiamGia);
        // Tiền xử lý dữ liệu
        $Ma = $Ma == '' ? $rac['Ma'] : $Ma;
        $TienGiam = $TienGiam < 0 ? $rac['TienGiam'] : $TienGiam;
        $DieuKien = $DieuKien == '' ? $rac['DieuKien'] : $DieuKien;
        $SoLuong = $SoLuong < 0 ? $rac['SoLuong'] : $SoLuong;
        $TrangThai = $TrangThai == '' ? $rac['TrangThai'] : $TrangThai; 
        // // Cập nhật database
        $sql = 'UPDATE ma_giam_gia SET Ma = ?, TienGiam = ?, DieuKien = ?, SoLuong = ?, TrangThai = ? WHERE ID_GiamGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssiss', $Ma, $TienGiam, $DieuKien, $SoLuong, $TrangThai, $ID_GiamGia);
        $stmt->execute();
        return ['success'=> true,'message'=> 'Cập nhật mã giảm giá thành thành công'];
    }

    public function setSale($Ma, $TienGiam, $DieuKien, $SoLuong) {
        if (!(is_numeric($TienGiam) && is_numeric($SoLuong) && $this->checkConditionSale($DieuKien))) return ['success'=> false,'message'=> 'Đầu vào không hợp lệ'];
        $sql = "INSERT INTO ma_giam_gia (Ma, TienGiam, DieuKien, SoLuong) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisi", $Ma, $TienGiam, $DieuKien, $SoLuong);
        $stmt->execute();
        return ["success"=> true,"message"=> "Thêm khuyến mãi thành công"];
    }

    private function checkConditionSale($DieuKien) {
        switch ($DieuKien) {
            case 'Tất cả':
                return true;
            case 'Male':
                return true;
            case 'Female':
                return true;
            case 'Chẵn':   
                return true;
            case 'Lẻ':  
                return true;
            case 'COD':      
                return true;
            default:
                return false;
        }
    }

    private function getSaleById($id) {
        $sql = 'SELECT * FROM ma_giam_gia WHERE ID_GiamGia = ?';
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt = $stmt->get_result();
        return $stmt->fetch_assoc();
    }

    private function proposeInfo() {
        $sql = "SELECT * FROM san_pham_de_xuat";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();   
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }

    private function productInfo() {
        $product_list = $this->product->get();
        $product_info = [];
        foreach ($product_list as $product) {
            $product_info[] = $this->product->getProductInfo($product['ID_SP']);
        }
        return $this->support->sort($product_info);
    }
    public function updateProductInfo($ID_SP, $TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB) {
        $sql = "
        UPDATE SAN_PHAM SET
        TenSP = ?,
        MoTa = ?,
        Gia = ?,
        TyLeGiamGia = ?,
        SoLuongKho = ?,
        NXB = ?,
        KichThuoc = ?,
        SoTrang = ?,
        PhanLoai = ?,
        TuKhoa = ?,
        HinhThuc = ?,
        TacGia = ?,
        NgonNgu = ?,
        NamXB = ?
        WHERE ID_SP = ?";

    $stmt = $this->conn->prepare($sql);

    if (!$stmt) {
        // Check for SQL error in prepare statement
        return ["success" => false, "message" => "Database error: " . $this->conn->error];
    }

    $stmt->bind_param("ssidississsssii", $TenSp, $MoTa, $Gia, $TyLeGiamGia, $SoLuongKho, $NXB, $KichThuoc, $SoTrang, $PhanLoai, $TuKhoa, $HinhThuc, $TacGia, $NgonNgu, $NamXB, $ID_SP);
    $stmt->execute();

    $sqlDelete = "DELETE FROM hinh_anh WHERE ID_SP = ?";
    $stmtDelete = $this->conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $ID_SP);
    $stmtDelete->execute();
    return ["success" => true, "message" => "Cập nhật thông tin sản phẩm thành công"];

    }

    public function updateProductImage ($Anh, $ID_SP) {
        $relativeAvatarPath = "public/image/product/$ID_SP/" . basename($Anh);
        // $sqlDelete = "DELETE FROM hinh_anh WHERE ID_SP = ?";
        // $stmtDelete = $this->conn->prepare($sqlDelete);
        // $stmtDelete->bind_param("i", $ID_SP);
        // $stmtDelete->execute();

        $sqlInsert = "INSERT INTO hinh_anh (Anh, ID_SP) VALUES (?, ?)";
        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bind_param("si", $relativeAvatarPath, $ID_SP);
        $stmtInsert->execute();
        return ["success" => true, "message" => "Cập nhật thành công"];
    }

    public function deleteProduct($ID_SP) {
        $sql = "DELETE FROM SAN_PHAM WHERE ID_SP = ?";
        $stmt = $this->conn->prepare($sql);
    
        if ($stmt === false) {
            error_log("Prepare failed: (" . $this->conn->errno . ") " . $this->conn->error);
            return ["success" => false, "message" => "Prepare failed"];
        }
    
        $stmt->bind_param("i", $ID_SP);
        $stmt->execute();
    
        if ($stmt->affected_rows === 0) {
            $stmt->close();
            return ["success" => false, "message" => "Xóa sản phẩm không thành công"];
        }
    
        $stmt->close();
        return ["success" => true, "message" => "Xóa sản phẩm thành công"];
    }
    
}
?>
