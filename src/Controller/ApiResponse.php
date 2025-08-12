<?php
namespace App\Controller;

class ApiResponse {
    public static function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
