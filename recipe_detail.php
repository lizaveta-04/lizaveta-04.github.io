<?php
require_once 'script.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Некорректный запрос');
}

$id = (int)$_GET['id'];

// Получаем рецепт из базы по id
try {
    $stmt = $pdo->prepare("SELECT * FROM recipes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $recipe = $stmt->fetch();

    if (!$recipe) {
        die('Рецепт не найден');
    }
} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($recipe['title']) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="styles_recipe_detail.css" />
</head>
<body>
<header>
  <div class="logo-title">
    <img src="logo.png" alt="Логотип" class="logo" />
    <h1>Подробности рецепта</h1>
  </div>
  <nav class="menu">
    <a href="index.html">Главная</a>
    <a href="receipes2.php">Рецепты</a>
    <a href="your_receipe.php">Ваш рецепт</a>
    <a href="contact.html">Обратная связь</a>
  </nav>
</header>

<div id="recipeDetailContainer" class="recipe-detail">
  <h2><?= htmlspecialchars($recipe['title']) ?></h2>
  <?php if (!empty($recipe['image'])): ?>
    <img src="data:image/jpeg;base64,<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="recipe-image" />
  <?php endif; ?>
  <p class="recipe-description"><strong>Описание:</strong> <?= nl2br(htmlspecialchars($recipe['description'])) ?></p>
  <div class="recipe-ingredients">
    <strong>Состав:</strong><br/>
    <?= nl2br(htmlspecialchars($recipe['ingredients'])) ?>
  </div>
  <a href="receipes2.php" class="back-button">Назад к рецептам</a>
</div>
 <!-- Подвал сайта -->
  <footer class="footer">
    <!-- Левая часть подвала с контактами -->
    <div class="left">
      <p>Контакты<br>
      тел.: 89539870092<br>
      email: lizashe2005@mail.ru</p>
    </div>
    <!-- Правая часть подвала с копирайтом -->
    <div class="right">
      <p>© 2025 Кулинарная книга.<br>Все права защищены.</p>
    </div>
  </footer>
</body>
</html>