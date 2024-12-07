<?php
// Bao gồm file kết nối cơ sở dữ liệu
include($_SERVER['DOCUMENT_ROOT'] . '/tlunews/config/db_connect.php');

// Bắt đầu session
session_start();

// Kết nối cơ sở dữ liệu
$conn = db_connect();

// Kiểm tra xem có id danh mục từ URL không
$id_category = isset($_GET['id']) ? $_GET['id'] : null;

// Truy vấn lấy bài viết từ cơ sở dữ liệu
if ($id_category) {
    // Nếu có id danh mục, lọc bài viết theo danh mục
    $sql = "SELECT * FROM tintuc WHERE danhmuc_id = ? ORDER BY ngay_tao DESC LIMIT 6"; // Lấy 6 bài viết mới nhất trong danh mục
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_category); // Liên kết tham số
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Nếu không có id danh mục, lấy 6 bài viết mới nhất
    $sql = "SELECT * FROM tintuc ORDER BY ngay_tao DESC LIMIT 6";
    $result = $conn->query($sql);
}

// Truy vấn lấy các bài viết nổi bật
$sql_featured = "SELECT * FROM tintuc WHERE is_featured = 1 ORDER BY ngay_tao DESC LIMIT 3"; // Lấy 3 bài viết nổi bật
$result_featured = $conn->query($sql_featured);

// Truy vấn lấy danh mục từ cơ sở dữ liệu
$sql_categories = "SELECT * FROM danhmuc ORDER BY ten_danhmuc ASC"; // Lấy các danh mục
$result_categories = $conn->query($sql_categories);

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ - TLUNEWS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom style for the text logo */
        .navbar-brand h1 {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            margin: 0;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="bg-dark text-white py-3">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <a href="#" class="navbar-brand text-white">
                        <h1>TLUNEWS</h1>
                    </a>
                </div>
                <div class="col-12 col-md-8 text-right">
                    <!-- Kiểm tra đăng nhập -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span>Chào, <?= htmlspecialchars($_SESSION['username']); ?>!</span>
                        <a href="/tlunews/app/views/user/logout.php" class="btn btn-outline-light ml-2">Đăng xuất</a>
                    <?php else: ?>
                        <a href="/tlunews/app/views/user/login.php" class="btn btn-outline-light">Đăng nhập</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <div class="row">
            <!-- Left Content (Main Articles) -->
            <div class="col-lg-8">
                <h2>Bài viết mới nhất</h2>
                <div class="row">
                    <?php
                    // Kiểm tra nếu có bài viết
                    if ($result->num_rows > 0) {
                        // Lặp qua các bài viết và hiển thị
                        while ($row = $result->fetch_assoc()) {
                            $tieu_de = $row['tieu_de'];
                            $anh_bia = $row['anh_bia'];
                            $noi_dung = substr($row['noi_dung'], 0, 150); // Lấy đoạn tóm tắt 150 ký tự của bài viết
                            $id = $row['id'];
                    ?>
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <img src="uploads/<?php echo $anh_bia; ?>" class="card-img-top" alt="Article Image">
                                    <div class="card-body">
                                        <h5 class="card-title"><?php echo $tieu_de; ?></h5>
                                        <p class="card-text"><?php echo $noi_dung; ?>...</p>
                                        <a href="article.php?id=<?php echo $id; ?>" class="btn btn-primary">Đọc thêm</a>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo "Chưa có bài viết!";
                    }
                    ?>
                </div>
            </div>

            <!-- Right Sidebar (Featured Articles) -->
            <div class="col-lg-4">
                <h3>Bài viết nổi bật</h3>
                <ul class="list-group mb-4">
                    <?php
                    // Kiểm tra nếu có bài viết nổi bật
                    if ($result_featured->num_rows > 0) {
                        while ($row = $result_featured->fetch_assoc()) {
                            $tieu_de_featured = $row['tieu_de'];
                            $id_featured = $row['id'];
                    ?>
                            <li class="list-group-item">
                                <a href="article.php?id=<?php echo $id_featured; ?>"><?php echo $tieu_de_featured; ?></a>
                            </li>
                    <?php
                        }
                    } else {
                        echo "<li class='list-group-item'>Chưa có bài viết nổi bật!</li>";
                    }
                    ?>
                </ul>

                <h3>Danh mục</h3>
                <ul class="list-group">
                    <?php
                    // Kiểm tra nếu có danh mục
                    if ($result_categories->num_rows > 0) {
                        while ($row = $result_categories->fetch_assoc()) {
                            $ten_danh_muc = $row['ten_danhmuc']; // Chỉnh sửa tên cột ở đây
                            $id_category = $row['id']; // Dùng id từ bảng danhmuc
                    ?>
                            <li class="list-group-item">
                                <a href="index.php?id=<?php echo $id_category; ?>"><?php echo $ten_danh_muc; ?></a>
                            </li>
                    <?php
                        }
                    } else {
                        echo "<li class='list-group-item'>Chưa có danh mục!</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-3">
        <div class="container">
            <p class="text-center mb-0">© 2024 TLUNEWS. Bảo vệ quyền lợi của bạn.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
</body>

</html>