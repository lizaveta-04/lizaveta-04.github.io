<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$db_host = "localhost";
$db_port = "5432";
$db_name = "recipes_db";
$db_user = "postgres";
$db_pass = "12345";

try {
    $db = new PDO("pgsql:host=$db_host;port=$db_port;dbname=$db_name", $db_user, $db_pass);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed: " . $e->getMessage()]);
    exit;
}

// Получение рецептов
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $db->query("SELECT * FROM recipes ORDER BY created_at DESC");
        $recipes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($recipes);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Добавление рецепта
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipe'])) {
    $data = json_decode($_POST['recipe'], true);
    
    try {
        $stmt = $db->prepare("
            INSERT INTO recipes (title, description, ingredients, image_base64) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['title'],
            $data['description'],
            $data['ingredients'],
            $data['imageBase64']
        ]);
        echo json_encode(["success" => true, "id" => $db->lastInsertId()]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}

// Обратная связь
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
    $data = json_decode($_POST['feedback'], true);
    
    try {
        $stmt = $db->prepare("
            INSERT INTO feedback (name, email, phone, message) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['message']
        ]);
        echo json_encode(["success" => true]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>