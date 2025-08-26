<?php
namespace App;

class Router {
    public function dispatch() {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Remove /public if present (IIS rewrite)
        $uri = preg_replace('#^/public#', '', $uri);
        
        // Auth: allow /login and /logout, require login for all else
        session_start();
        if ($uri === '/login') {
            (new \App\Controller\AuthController())->login($method);
            return;
        }
        if ($uri === '/logout') {
            (new \App\Controller\AuthController())->logout();
            return;
        }
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Routing
        if ($uri === '/' || $uri === '') {
            header('Location: /attendance');
            exit;
        }
        if (strpos($uri, '/attendance') === 0) {
            (new \App\Controller\AttendanceController())->handle($method, $uri);
            return;
        }
        if (strpos($uri, '/api/attendance/toggle') === 0) {
            (new \App\Controller\AttendanceController())->toggle();
            return;
        }
        if (strpos($uri, '/api/attendance/reset') === 0) {
            (new \App\Controller\AttendanceController())->reset();
            return;
        }
        if (strpos($uri, '/setup/csv') === 0) {
            (new \App\Controller\CsvController())->handle($method, $uri);
            return;
        }
        if (strpos($uri, '/setup/users') === 0) {
            (new \App\Controller\UserController())->index($method);
            return;
        }
        if (strpos($uri, '/setup/teachers') === 0) {
            (new \App\Controller\SetupController())->teachers($method, $uri);
            return;
        }
        if (strpos($uri, '/setup/classes') === 0) {
            (new \App\Controller\SetupController())->classes($method, $uri);
            return;
        }
        if (strpos($uri, '/setup/students') === 0) {
            (new \App\Controller\SetupController())->students($method, $uri);
            return;
        }
        if (strpos($uri, '/setup') === 0) {
            (new \App\Controller\SetupController())->index();
            return;
        }
		if ($uri === '/api/students/reorder') {
            (new \App\Controller\StudentOrderController())->reorder();
            return;
        }
        // 404
        http_response_code(404);
        echo 'Not Found';
    }
}
