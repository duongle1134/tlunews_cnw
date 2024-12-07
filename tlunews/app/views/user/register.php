<?php
// Kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Nhận thông tin người dùng từ form đăng ký
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Mã hóa mật khẩu
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Kiểm tra nếu username hoặc email đã tồn tại
    $sql_check = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "Tên người dùng hoặc email đã tồn tại!";
    } else {
        // Câu lệnh SQL để thêm người dùng vào cơ sở dữ liệu
        $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password_hashed, $email);

        if ($stmt->execute()) {
            // Chuyển hướng về trang đăng nhập sau khi đăng ký thành công
            header("Location: login.php");
            exit();
        } else {
            echo "Lỗi khi đăng ký: " . $stmt->error;
        }

        $stmt->close();
    }

    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - TLUNEWS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Form Đăng Ký -->
    <div class="container mt-5">
        <h2>Đăng Ký Tài Khoản</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng Ký</button>
        </form>
        <p class="mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>