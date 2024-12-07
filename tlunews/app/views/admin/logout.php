<?php
// Bắt đầu session
session_start();

// Hủy tất cả các session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập
header('Location: /tlunews/app/views/admin/login.php');
exit;
