<?php
// Bao gồm file kết nối cơ sở dữ liệu với đường dẫn tuyệt đối
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Kết nối cơ sở dữ liệu
$conn = db_connect();

// Kiểm tra nếu có tham số 'id' trong URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Truy vấn lấy thông tin chi tiết bài viết từ cơ sở dữ liệu
    $sql = "SELECT * FROM tintuc WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra nếu có bài viết
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $tieu_de = $row['tieu_de'];
        $noi_dung = $row['noi_dung'];
        $anh_bia = $row['anh_bia'];
    } else {
        // Nếu không tìm thấy bài viết
        echo "Bài viết không tồn tại!";
        exit;
    }
} else {
    echo "ID bài viết không hợp lệ!";
    exit;
}

// Đóng kết nối
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tieu_de; ?> - Chi tiết bài viết</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <a href="#" class="navbar-brand text-white">
                        <img src="logo.png" alt="Logo" style="width: 150px;">
                    </a>
                </div>
                <div class="col-12 col-md-8">
                    <form class="form-inline float-right">
                        <input class="form-control mr-2" type="search" placeholder="Tìm kiếm" aria-label="Search">
                        <button class="btn btn-outline-light" type="submit">Tìm kiếm</button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <h2><?php echo $tieu_de; ?></h2>
        <div class="row">
            <div class="col-md-8">
                <img src="uploads/<?php echo $anh_bia; ?>" class="img-fluid" alt="Article Image">
                <div class="mt-4">
                    <p><?php echo nl2br($noi_dung); ?></p>
                </div>
            </div>

            <!-- Sidebar with "Back to Home" button -->
            <div class="col-md-4">
                <a href="index.php" class="btn btn-primary btn-block">Quay lại trang chủ</a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
        <div class="container">
            <p class="text-center mb-0">© 2024 Báo GENZ. Bảo vệ quyền lợi của bạn.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>