<?php
// Bắt đầu phiên làm việc
session_start();

// Tự động tải các file cần thiết
require_once __DIR__ . '/config/config.php';

// Hàm tự động tải controller và model
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/app/controllers/' . $class . '.php',
        __DIR__ . '/app/models/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Lấy route từ URL và kiểm tra tính hợp lệ
$route = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_STRING) ?: 'home/index';

// Phân tách route thành controller và action
$routeParts = explode('/', $route);
$controllerName = ucfirst($routeParts[0]) . 'Controller'; // Tên controller
$actionName = isset($routeParts[1]) ? $routeParts[1] : 'index'; // Tên action

// Kiểm tra và gọi controller
$controllerPath = __DIR__ . '/app/controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    $controller = new $controllerName();

    // Kiểm tra method trong controller
    if (method_exists($controller, $actionName)) {
        call_user_func([$controller, $actionName]);
    } else {
        // Gọi controller lỗi hoặc page 404
        handleError("Action '$actionName' không tồn tại trong controller '$controllerName'!");
    }
} else {
    // Gọi controller lỗi hoặc page 404
    handleError("Controller '$controllerName' không tồn tại!");
}

// Hàm xử lý lỗi và hiển thị trang lỗi
function handleError($message)
{
    echo "<h1>Lỗi!</h1><p>$message</p>";
    // Có thể chuyển hướng hoặc hiển thị một trang 404 nếu cần
    // header("HTTP/1.1 404 Not Found");
    // include(__DIR__ . '/app/views/404.php');
    exit;
}
