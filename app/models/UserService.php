<?php
require_once dirname(__DIR__, 1) . '/models/support.php';
require_once dirname(__DIR__, 1) . '/models/ProductService.php';

class UserService {
    private $conn;
    private $support;
    private $product;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->support = new support();
        $this->product = new ProductService($conn);
    }

    public function info($id) {
        $user = $this->getUserInfo($id);
        if ($user['success'] === false) {
            return ['success' => false, 'message' => $user['message']];
        }
        
        $login = $this->getLoginInfo($id);
        if ($login['success'] === false) {
            return ['success' => false, 'message' => $login['message']];
        }

        if (!$user || !$login) {
            return ['success' => false, 'message' => 'Không tìm thấy người dùng'];
        }

        return [
            'name' => $login['name'],
            'role' => $login['role'],
            'phone' => $user['phone'],
            'sex' => $user['sex'],
            'address' => $user['address'],
            'avatar' => $login['avatar'],
        ];
    }

    public function diary($id) {
        $sql = "SELECT * FROM lich_su_dang_nhap WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $diary = [];
        while ($row = $result->fetch_assoc()) {
            $diary[] = [
                'ThoiGian' => $row['ThoiGian'],
                'NoiDung' => $row['NoiDung']
            ];
        }
        return $diary;
    }

    public function getBanner() {
        $sql = "SELECT * FROM banner";
        $result = $this->conn->query($sql);

        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy banner'];
        }
        
        $banner = [];
        while ($row = $result->fetch_assoc()) {
            $banner[] = $row;
        }
        return $banner;
    }

    public function getProduct() {
        $allProducts = $this->product->get();
        return $this->product->getList(array_slice($allProducts, -10));
    }

    public function getProductList() {
        $types = $this->product->getType();
        return $this->product->getProductList($types);
    }

    public function setAvatar($id, $avatarFileName) {
        $sql = "SELECT * FROM `login` WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Tài khoản không tồn tại'];
        }

        // Đường dẫn đầy đủ đến tệp ảnh
        $relativeAvatarPath = "public/image/user/$id/" . basename($avatarFileName);
    
        // Lưu đường dẫn và tệp ảnh vào cơ sở dữ liệu
        $avatar_sql = "UPDATE `login` SET `Avatar` = ? WHERE `UID` = ?";
        $stmt_avatar = $this->conn->prepare($avatar_sql);
        $stmt_avatar->bind_param("si", $relativeAvatarPath, $id);
        $stmt_avatar->execute();
    
        return ['success' => true, 'message' => 'Thay đổi ảnh đại diện thành công'];
    }    

    public function setInfo($uid, $sex, $phone, $addr, $name) {
        $user = $this->setUser($uid, $sex, $phone, $addr);
        $login = $this->setLogin($uid, $name);
        if ($user['success'] == false) return ['success'=> false,'message'=> $user['message']];
        if ($login['success'] == false) return ['success'=> false,'message'=> $login['message']];
        return ['success'=> true, 'message'=> 'Thay đổi thông tin thành công'];
    }

    public function setReview($uid, $ID_SP, $SoSao, $NoiDung) {
        $rac = $this->buyed($uid, $ID_SP);
        if ($rac['success'] == false) return $rac;
        if ($this->product->checkProduct($ID_SP) == false) return ['success'=> false,'message'=> 'Không tìm thấy sản phẩm'];
        $date = $this->support->getDateNow();
        $sql_check = "SELECT * FROM danh_gia WHERE UID = ? AND ID_SP = ?";
        $stmt_check = $this->conn->prepare($sql_check);
        $stmt_check->bind_param("ii", $uid, $ID_SP);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $sql_update = "UPDATE danh_gia SET SoSao = ?, NoiDung = ?, NgayDanhGia = ? WHERE UID = ? AND ID_SP = ?";
            $stmt_update = $this->conn->prepare($sql_update);
            $stmt_update->bind_param("issii", $SoSao, $NoiDung, $date, $uid, $ID_SP);
            $stmt_update->execute();
        } 
        else {
            $sql_insert = "INSERT INTO danh_gia (UID, ID_SP, SoSao, NoiDung, NgayDanhGia) VALUES (?, ?, ?, ?, ?)";
            $stmt_insert = $this->conn->prepare($sql_insert);
            $stmt_insert->bind_param("iiiss", $uid, $ID_SP, $SoSao, $NoiDung, $date);
            $stmt_insert->execute();
        }
        return ['success'=> true, 'message'=> 'Đánh giá thành công'];
    }

    public function setComment($uid, $ID_SP, $NoiDung) {
        if ($this->product->checkProduct($ID_SP) == false) return ['success'=> false,'message'=> 'Không tìm thấy sản phẩm'];
        $date = $this->support->getDateNow();

        $sql_insert = "INSERT INTO binh_luan (`UID`, ID_SP, NoiDung, NgayBinhLuan) VALUES (?, ?, ?, ?)";
        $stmt_insert = $this->conn->prepare($sql_insert);
        $stmt_insert->bind_param("iiss", $uid, $ID_SP, $NoiDung, $date);
        $stmt_insert->execute();
        return ['success'=> true, 'message'=> 'Bình luận thành công'];
    }

    public function like($uid, $ID_SP) {
        if ($this->product->checkProduct($ID_SP) == false) return ['success'=> false, 'message'=> 'Không tìm thấy sản phẩm'];
        if ($this->product->thich($ID_SP) == false) {
            $sql = "INSERT INTO thich (`UID`, ID_SP) VALUES (?, ?)";
            $stmt_like = $this->conn->prepare($sql);
            $stmt_like->bind_param("ii", $uid, $ID_SP);
            $stmt_like->execute();
        }
        return ["success"=> true, "message"=> "Đã thích sản phẩm"];
    }

    public function unlike($uid, $ID_SP) {
        if ($this->product->thich($ID_SP) == false) return ['success'=> false, 'message'=> 'Chưa thích sản phẩm này!'];
        $sql = "DELETE FROM thich WHERE `UID` = ? AND `ID_SP` = ?";
        $stmt_like = $this->conn->prepare($sql);
        $stmt_like->bind_param("ii", $uid, $ID_SP);
        $stmt_like->execute();
        return ["success"=> true, "message"=> "Bỏ thích thành công"];
    }

    public function getNotice($uid) {
        $sql = "SELECT NoiDung FROM thong_bao WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $stmt = $stmt->get_result();
        $result = [];
        while ($row = $stmt->fetch_assoc()) {
            $result[] = $row['NoiDung'];
        }
        $this->updateStatusNotice($uid);
        return ['sucess' => true, 'notice_list' => $result];
    }

    private function updateStatusNotice($uid) {
        $sql = 'UPDATE thong_bao SET TrangThai = ? WHERE `UID` = ? AND TrangThai = ?';
        $stmt = $this->conn->prepare($sql);
        $rac1 = 'Read';
        $rac2 = 'Unread';
        $stmt->bind_param('sis', $rac1, $uid, $rac2);
        $stmt->execute();
    }

    private function buyed ($uid, $ID_SP) {
        $sql = "SELECT TrangThai 
                FROM don_hang D 
                JOIN gom G ON G.ID_DonHang = D.ID_DonHang 
                WHERE G.ID_SP = ? AND D.UID = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $uid, $ID_SP);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) return ["success"=> false,"message"=> "Người dùng chưa mua sản phẩm"];
        $result = $result->fetch_assoc();
        if ($result['TrangThai'] != 'Đã giao hàng') return ['success'=> false,'message'=> 'Người dùng chưa mua sản phẩm'];
        return ['success'=> true, 'message' => $result['TrangThai']];
    }

    private function setUser($uid, $sex, $phone, $addr) {
        if ($sex !== '' && $sex !== 'Male' && $sex !== 'Female' && $phone !== '' && !preg_match('/^[0-9]+$/', $phone)) {
            return ['success'=> false, 'message'=> 'Dữ liệu không hợp lệ'];
        }
        $user = $this->getUserInfo($uid);
        if ($user['success'] === false) {
            return ["success" => false, "message" => $user['message']];
        }
        if ($sex == '') $sex = $user['sex'];
        if ($phone == '') $phone = $user['phone'];
        if ($addr == '') $addr = $user['address'];
        $sql = "UPDATE khach_hang SET GioiTinh = ?, SDT = ?, DiaChi = ? WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sssi", $sex, $phone, $addr, $uid);
        $stmt->execute();
        return ["success" => true];
    }

    private function setLogin($uid, $name) {
        $login = $this->getLoginInfo($uid);
        if ($login['success'] === false) {
            return ["success" => false, "message" => $login['message']];
        }
        if ($name == '') $name = $login['name'];
        $sql = "UPDATE `login` SET Ten = ? WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $uid);
        $stmt->execute();
        return ["success" => true];
    }

    public function getUserInfo($uid) {
        $sql = "SELECT * FROM khach_hang WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success" => false,"message" => "Không tìm thấy thông tin người dùng"];
        }
        $user = $result->fetch_assoc();
        return ["success" => true, 'phone' => $user['SDT'], 'sex' => $user['GioiTinh'], 'address' => $user['DiaChi'], 'status' => $user['TrangThai']];
    }

    public function getLoginInfo($uid) {
        $sql = "SELECT * FROM `login` WHERE `UID` = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            return ["success" => false,"message" => "Không tìm thấy thông tin đăng nhập"];
        }
        $user = $result->fetch_assoc();
        return ["success" => true, 'name' => $user['Ten'], 'role' => $user['Role'], 'avatar' => $user['Avatar'], 'password' => $user['Password'], 'email' => $user['Email']];
    }
}
?>