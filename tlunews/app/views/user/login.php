<?php
// Bao gồm file kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Bắt đầu session
session_start();

$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn cơ sở dữ liệu để kiểm tra tài khoản người dùng
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Lấy thông tin người dùng
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Đăng nhập thành công, lưu thông tin vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['fullname'] = $user['fullname'];

            // Chuyển hướng đến trang chủ hoặc dashboard user
            header('Location: /tlunews/app/views/home/index.php');
            exit;
        } else {
            // Nếu mật khẩu không chính xác
            $error_message = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    } else {
        // Nếu không tìm thấy người dùng
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - TLUNEWS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <h3 class="text-center">Đăng Nhập</h3>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger">
                        <?= $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                </form>
                <div class="text-center mt-3">
                    <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>