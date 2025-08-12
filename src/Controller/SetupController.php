<?php
namespace App\Controller;

class SetupController {
    public function index() {
        include __DIR__ . '/../../views/setup.php';
    }

    public function teachers($method, $uri) {
        $editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
        if ($method === 'POST') {
            if (isset($_POST['name'])) {
                \App\Repo\TeacherRepo::upsert(trim($_POST['name']));
                $_SESSION['flash'] = 'Teacher added.';
                header('Location: /setup/teachers'); exit;
            }
            if (isset($_POST['edit_id'], $_POST['edit_name'])) {
                \App\Repo\TeacherRepo::update((int)$_POST['edit_id'], trim($_POST['edit_name']));
                $_SESSION['flash'] = 'Teacher updated.';
                header('Location: /setup/teachers'); exit;
            }
            if (isset($_POST['delete_id'])) {
                \App\Repo\TeacherRepo::delete((int)$_POST['delete_id']);
                $_SESSION['flash'] = 'Teacher deleted.';
                header('Location: /setup/teachers'); exit;
            }
        }
        $teachers = \App\Repo\TeacherRepo::all();
        include __DIR__ . '/../../views/teachers.php';
    }

    public function classes($method, $uri) {
        $teachers = \App\Repo\TeacherRepo::all();
        $selectedTeacher = isset($_GET['teacher']) ? (int)$_GET['teacher'] : 0;
        $editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
        $classes = $selectedTeacher ? \App\Repo\ClassRepo::allByTeacher($selectedTeacher) : [];
        if ($method === 'POST' && $selectedTeacher) {
            if (isset($_POST['name'])) {
                \App\Repo\ClassRepo::upsert($selectedTeacher, trim($_POST['name']));
                $_SESSION['flash'] = 'Class added.';
                header('Location: /setup/classes?teacher=' . $selectedTeacher); exit;
            }
            if (isset($_POST['edit_id'], $_POST['edit_name'])) {
                \App\Repo\ClassRepo::update((int)$_POST['edit_id'], trim($_POST['edit_name']));
                $_SESSION['flash'] = 'Class updated.';
                header('Location: /setup/classes?teacher=' . $selectedTeacher); exit;
            }
            if (isset($_POST['delete_id'])) {
                \App\Repo\ClassRepo::delete((int)$_POST['delete_id']);
                $_SESSION['flash'] = 'Class deleted.';
                header('Location: /setup/classes?teacher=' . $selectedTeacher); exit;
            }
        }
        include __DIR__ . '/../../views/classes.php';
    }

    public function students($method, $uri) {
        $teachers = \App\Repo\TeacherRepo::all();
        $selectedTeacher = isset($_GET['teacher']) ? (int)$_GET['teacher'] : 0;
        $classes = $selectedTeacher ? \App\Repo\ClassRepo::allByTeacher($selectedTeacher) : [];
        $selectedClass = isset($_GET['class']) ? (int)$_GET['class'] : 0;
        $editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
        $students = $selectedClass ? \App\Repo\StudentRepo::allByClass($selectedClass) : [];
        if ($method === 'POST' && $selectedClass) {
            if (isset($_POST['name'])) {
                \App\Repo\StudentRepo::upsert($selectedClass, trim($_POST['name']));
                $_SESSION['flash'] = 'Student added.';
                header('Location: /setup/students?teacher=' . $selectedTeacher . '&class=' . $selectedClass); exit;
            }
            if (isset($_POST['edit_id'], $_POST['edit_name'])) {
                \App\Repo\StudentRepo::update((int)$_POST['edit_id'], trim($_POST['edit_name']));
                $_SESSION['flash'] = 'Student updated.';
                header('Location: /setup/students?teacher=' . $selectedTeacher . '&class=' . $selectedClass); exit;
            }
            if (isset($_POST['delete_id'])) {
                \App\Repo\StudentRepo::delete((int)$_POST['delete_id']);
                $_SESSION['flash'] = 'Student deleted.';
                header('Location: /setup/students?teacher=' . $selectedTeacher . '&class=' . $selectedClass); exit;
            }
        }
        include __DIR__ . '/../../views/students.php';
    }
}
