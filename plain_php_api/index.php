<?php


require_once __DIR__ . '/TaskController.php';

// CORS (для удобного тестирования из браузера)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Preflight-запросы
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$controller = new TaskController();

// /tasks
if ($uri === '/tasks' && $method === 'GET') {
    $controller->index();

} elseif ($uri === '/tasks' && $method === 'POST') {
    $controller->create();

// /tasks/{id}
} elseif (preg_match('#^/tasks/(\d+)$#', $uri, $matches)) {
    $id = (int)$matches[1];

    if ($method === 'GET') {
        $controller->show($id);
    } elseif ($method === 'PUT') {
        $controller->update($id);
    } elseif ($method === 'DELETE') {
        $controller->delete($id);
    } else {
        http_response_code(405);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
    }

} else {
    http_response_code(404);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => 'Not found'], JSON_UNESCAPED_UNICODE);
}
