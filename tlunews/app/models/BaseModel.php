<?php
class BaseModel
{
    protected $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    // Các phương thức chung cho mọi model, như truy vấn cơ sở dữ liệu
}
