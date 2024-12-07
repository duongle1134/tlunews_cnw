<?php
// Bắt đầu session
session_start();

// Hủy tất cả session
session_unset();
session_destroy();

// Chuyển hướng về trang login
header('Location: /tlunews/app/views/user/login.php');
exit;
