<?php
function db_connect()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tlunews"; // Tên database của bạn

    // Kết nối
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Kiểm tra kết nối
    if ($conn->connect_error) {
        die("Kết nối thất bại: " . $conn->connect_error);
    }

    return $conn;
}
