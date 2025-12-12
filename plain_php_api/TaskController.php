<?php

require_once __DIR__ . '/db.php';

class TaskController
{
    /** @var PDO */
    private $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }


    private function getJsonInput()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }


    public function create()
    {
        $data = $this->getJsonInput();

        if (empty($data['title'])) {
            $this->jsonResponse(['error' => 'Title is required'], 422);
        }

        $title = $data['title'];
        $description = isset($data['description']) ? $data['description'] : '';
        $status = isset($data['status']) ? $data['status'] : 'pending';

        $stmt = $this->pdo->prepare("
            INSERT INTO tasks (title, description, status)
            VALUES (:title, :description, :status)
        ");
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':status'      => $status,
        ]);

        $id   = (int)$this->pdo->lastInsertId();
        $task = $this->findById($id);

        $this->jsonResponse($task, 201);
    }

    public function index()
    {
        $stmt  = $this->pdo->query("SELECT * FROM tasks ORDER BY id DESC");
        $tasks = $stmt->fetchAll();
        $this->jsonResponse($tasks);
    }

    public function show($id)
    {
        $task = $this->findById($id);
        if (!$task) {
            $this->jsonResponse(['error' => 'Task not found'], 404);
        }
        $this->jsonResponse($task);
    }

    public function update($id)
    {
        $task = $this->findById($id);
        if (!$task) {
            $this->jsonResponse(['error' => 'Task not found'], 404);
        }

        $data = $this->getJsonInput();

        if (array_key_exists('title', $data) && trim($data['title']) === '') {
            $this->jsonResponse(['error' => 'Title cannot be empty'], 422);
        }

        $title       = isset($data['title']) ? $data['title'] : $task['title'];
        $description = isset($data['description']) ? $data['description'] : $task['description'];
        $status      = isset($data['status']) ? $data['status'] : $task['status'];

        $stmt = $this->pdo->prepare("
            UPDATE tasks
            SET title = :title,
                description = :description,
                status = :status
            WHERE id = :id
        ");
        $stmt->execute([
            ':title'       => $title,
            ':description' => $description,
            ':status'      => $status,
            ':id'          => $id,
        ]);

        $updatedTask = $this->findById($id);
        $this->jsonResponse($updatedTask);
    }

    public function delete($id)
    {
        $task = $this->findById($id);
        if (!$task) {
            $this->jsonResponse(['error' => 'Task not found'], 404);
        }

        $stmt = $this->pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $this->jsonResponse(['message' => 'Task deleted']);
    }

    private function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM tasks WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $task = $stmt->fetch();
        return $task ? $task : null;
    }
}
