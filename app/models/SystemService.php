<?php
require_once dirname(__DIR__, 2) . '/config/db.php';
// require_once dirname(__DIR__, 1) . '/models/support.php';

class SystemService {
    private $conn;
    private $support;

    public function __construct($conn) {
        $this->conn = $conn;
        // $this->support = new support();
    }

    public function getInfoList() {
        // Query to fetch MaTinTuc and TieuDe
        $sql1 = "SELECT MaTinTuc, TieuDe FROM TIN_TUC";
        $query1 = $this->conn->prepare($sql1);
        $query1->execute();
        $result1 = $query1->get_result();
    
        $finalResults = [];
        while ($row = $result1->fetch_assoc()) {
            $maTinTuc = $row['MaTinTuc'];
            $tieuDe = $row['TieuDe'];
    
            // Query to fetch LinkAnh based on MaTinTuc
            $sql2 = "SELECT LinkAnh FROM ANH_MINH_HOA WHERE MaTinTuc = ?";
            $query2 = $this->conn->prepare($sql2);
            $query2->bind_param('i', $maTinTuc);
            $query2->execute();
            $result2 = $query2->get_result();
    
            $tmp = [];
            while ($row2 = $result2->fetch_assoc()) {
                $tmp[] = $row2;
            }
    
            // Add the results to the final array
            $finalResults[] = [
                'MaTinTuc' => $maTinTuc,
                'TieuDe' => $tieuDe,
                'AnhMinhHoa' => $tmp
            ];
        }
    
        return $finalResults;
    }
    

    public function getComment($id) {
        $sql = "SELECT * FROM binh_luan WHERE id = '$id'";
        $result = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($result) === 0) {
            return ['success' => false, 'message' => 'Không có bình luận'];
        }
        $comments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $comments[] = $row;
        }
        return $comments;
    }

    public function getReview($id) {
        $sql = "SELECT * FROM danh_gia WHERE id = '$id'";
        $result = mysqli_query($this->conn, $sql);
        if (mysqli_num_rows($result) === 0) {
            return ['success' => false, 'message' => 'Không có đánh giá'];
        }
        $reviews = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $reviews[] = $row;
        }
        return $reviews;
    }
}
?>