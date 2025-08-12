<?php
namespace App\Controller;

class AuthController {
    public function login($method) {
        $error = '';
        if ($method === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = \App\Repo\UserRepo::findByUsername($username);
            // ...existing code...
            if ($user && password_verify($password, $user['password_hash'])) {
                echo 'matches';
                echo $password;
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['role']
                ];
                echo 'Made it';
                header('Location: /attendance');
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        }
        include __DIR__ . '/../../views/login.php';
    }
    public function logout() {
        unset($_SESSION['user']);
        session_destroy();
        header('Location: /login');
        exit;
    }
}
