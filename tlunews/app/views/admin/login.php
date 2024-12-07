<?php
// Bao gồm file kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Bắt đầu session
session_start();

$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Kiểm tra tài khoản trong cơ sở dữ liệu
    $sql = "SELECT * FROM admin WHERE username = ? AND password = MD5(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Đăng nhập thành công, lưu thông tin vào session
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['fullname'];

        // Chuyển hướng đến trang dashboard
        header('Location: /tlunews/app/views/admin/dashboard.php');
        exit;
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-4">
                <h3 class="text-center">Admin Login</h3>
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
            </div>
        </div>
    </div>
</body>

</html>
