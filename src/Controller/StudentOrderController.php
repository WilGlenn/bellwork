<?php
namespace App\Controller;

use App\Db;

class StudentOrderController {
    public function reorder() {
        $data = json_decode(file_get_contents('php://input'), true);
        $classId = isset($data['classId']) ? (int)$data['classId'] : 0;
        $order = isset($data['order']) && is_array($data['order']) ? $data['order'] : [];
        if (!$classId || empty($order)) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Invalid input']);
            return;
        }
        $pdo = Db::get();
        foreach ($order as $i => $studentId) {
            $stmt = $pdo->prepare('UPDATE students SET sort_order=? WHERE id=? AND class_id=?');
            $stmt->execute([$i, $studentId, $classId]);
        }
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
}
