<?php
// Bắt đầu session
session_start();

// Kiểm tra nếu chưa đăng nhập hoặc không phải admin, thì chuyển hướng đến trang login
if (!isset($_SESSION['admin_id'])) {
    header('Location: /tlunews/app/views/admin/login.php');
    exit;
}

// Nếu đã đăng nhập, tiếp tục hiển thị dashboard
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tlunews | Dashboard</title>

    <!-- External CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/overlayScrollbars/1.13.1/css/OverlayScrollbars.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="index.php" class="nav-link">Home</a>
                </li>
            </ul>
            <!-- SEARCH FORM -->
            <form class="form-inline ml-3" method="GET" action="search.php">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" name="query" placeholder="Search" aria-label="Search" required>
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index.php" class="brand-link">
                <img src="path/to/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">tlunews</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="path/to/admin.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= htmlspecialchars($_SESSION['admin_name']); ?></a>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="ds_danhmuc.php" class="nav-link">
                                <i class="nav-icon fas fa-copy"></i>
                                <p>Danh mục</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="ds_tintuc.php" class="nav-link">
                                <i class="nav-icon fas fa-newspaper"></i>
                                <p>Danh sách Tin tức</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="add_post.php" class="nav-link">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Thêm Tin tức</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Bảng Điều Khiển</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Card: Danh sách Tin tức -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>Tin Tức</h3>
                                    <p>Danh sách Tin tức</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <a href="ds_tintuc.php" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card: Thêm Tin Tức -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>Thêm</h3>
                                    <p>Thêm Tin Tức</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </div>
                                <a href="add_post.php" class="small-box-footer">
                                    Thêm ngay <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card: Danh mục -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>Danh mục</h3>
                                    <p>Quản lý Danh mục</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-copy"></i>
                                </div>
                                <a href="ds_danhmuc.php" class="small-box-footer">
                                    Xem chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Card: Đăng xuất -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>Đăng xuất</h3>
                                    <p>Thoát hệ thống</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <a href="logout.php" class="small-box-footer">
                                    Đăng xuất <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- External JS Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/overlayScrollbars/1.13.1/js/OverlayScrollbars.min.js"></script>
</body>

</html>