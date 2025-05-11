<?php require_once 'script.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Рецепты</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles_receipes2.css">
</head>
<body>

<header>
  <div class="logo-title">
    <img src="logo.png" alt="Логотип" class="logo">
    <h1>Рецепты</h1>
  </div>
  <input type="checkbox" id="menu-toggle">
  <label class="menu-icon" for="menu-toggle"><span></span><span></span><span></span></label>
  <nav class="menu">
    <a href="index.html">Главная</a>
    <a href="your_receipe.html">Ваш рецепт</a>
    <a href="contact.php">Обратная связь</a>
  </nav>
</header>

<!-- Поиск -->
<div class="search-container">
  <form method="get" class="search-form">
    <input type="text" name="search" placeholder="Поиск рецептов..." class="search-input" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
    <button type="submit">Найти</button>
  </form>
</div>

<!-- Вывод рецептов -->
<div class="recipes-container">
<?php
$search = $_GET['search'] ?? '';

if ($search) {
  $query = "SELECT * FROM recipes WHERE title ILIKE :term OR description ILIKE :term ORDER BY id DESC";
  $stmt = $pdo->prepare($query);
  $stmt->execute([':term' => "%$search%"]);
} else {
  $stmt = $pdo->query("SELECT * FROM recipes ORDER BY id DESC");
}

$recipes = $stmt->fetchAll();

if (count($recipes) === 0) {
  echo '<p class="no-results">Рецепты не найдены</p>';
}

foreach ($recipes as $recipe): ?>
  <div class="recipe-card">
    <img src="<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
    <div class="recipe-content">
      <h2><?= htmlspecialchars($recipe['title']) ?></h2>
      <p><?= htmlspecialchars($recipe['description']) ?></p>
      <a href="recipe_detail.php?id=<?= $recipe['id'] ?>">Подробнее</a>
    </div>
  </div>
<?php endforeach; ?>
</div>

<footer class="footer">
  <div class="left">
    <p>Контакты<br>тел.: 89539870092<br>email: lizashe2005@mail.ru</p>
  </div>
  <div class="right">
    <p>© 2025 Кулинарная книга. Все права защищены.</p>
  </div>
</footer>

</body>
</html>
