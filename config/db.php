<?php
session_start();
class Database {
    public $conn;
    private $servername = "localhost";
    private $username = "root";
    private $db_name = "bkstore";
    private $db_password = "";

    public function __construct() {
        // Tạo kết nối
        $this->conn = new mysqli($this->servername, $this->username, $this->db_password, $this->db_name);

        // Kiểm tra kết nối
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
?>
