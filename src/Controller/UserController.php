<?php
namespace App\Controller;

class UserController {
    public function index($method) {
        if ($_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
        if ($method === 'POST') {
            if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
                $username = trim($_POST['username']);
                $password = $_POST['password'];
                $role = $_POST['role'] === 'admin' ? 'admin' : 'user';
                if ($username && $password) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    \App\Repo\UserRepo::add($username, $hash, $role);
                    $_SESSION['flash'] = 'User added.';
                    header('Location: /setup/users'); exit;
                }
            }
            if (isset($_POST['delete_id'])) {
                $id = (int)$_POST['delete_id'];
                if ($id !== $_SESSION['user']['id']) {
                    \App\Repo\UserRepo::delete($id);
                    $_SESSION['flash'] = 'User deleted.';
                    header('Location: /setup/users'); exit;
                }
            }
        }
        $users = \App\Repo\UserRepo::all();
        include __DIR__ . '/../../views/users.php';
    }
}
