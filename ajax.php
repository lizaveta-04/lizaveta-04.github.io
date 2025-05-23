<?php
header('Content-Type: application/json; charset=utf-8');

// Конфигурация подключения к PostgreSQL
$db_host = "localhost";
$db_port = "5432";
$db_name = "dbrecipes";
$db_user = "postgres";
$db_pass = "12345";

try {
    $pdo = new PDO(
        "pgsql:host=$db_host;port=$db_port;dbname=$db_name",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Ошибка подключения к базе: " . $e->getMessage()]);
    exit;
}


// POST: Добавление обратной связи
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'feedback') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $phone === '' || $message === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Заполните все поля']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO feedback (name, email, phone, message, created_at) VALUES (:name, :email, :phone, :message, NOW())");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':message' => $message,
        ]);
        echo json_encode(['success' => true, 'message' => 'Спасибо за обратную связь!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ошибка БД']);
    }
    exit;
}

// GET: Получить список обратной связи
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($_GET['action'] ?? '') === 'get_feedback') {
    try {
        $stmt = $pdo->query("SELECT * FROM feedback ORDER BY created_at DESC LIMIT 20");
        $feedback = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $feedback]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ошибка БД']);
    }
    exit;
}
?>