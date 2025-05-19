<?php
require_once 'script.php';

$search = $_GET['search'] ?? '';
$recipes = Recipes($pdo, $search);

if (isset($_GET['ajax'])) {
    if (empty($recipes)) {
        echo '<p id="noResultsMessage" class="no-results" style="display: block;">Рецепты не найдены</p>';
    } else {
        foreach ($recipes as $recipe) {
            echo '
                <div class="recipe-card">
                    <img src="data:image/jpeg;base64,' . htmlspecialchars($recipe['image']) . '" alt="' . htmlspecialchars($recipe['title']) . '" class="recipe-image">
                    <div class="recipe-content">
                        <h2>' . highlightText(htmlspecialchars($recipe['title']), $search) . '</h2>
                        <p>' . highlightText(htmlspecialchars($recipe['description']), $search) . '</p>
                        <a href="recipe_detail.php?id=' . $recipe['id'] . '">Подробнее</a>
                    </div>
                </div>
            ';
        }
    }
    exit;
}

function highlightText($text, $search) {
    if (!empty($search)) {
        $search = preg_quote($search, '/');
        $text = preg_replace('/(' . $search . ')/iu', '<span class="highlight">$1</span>', $text);
    }
    return $text;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Рецепты</title>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles_receipes2.css">
  <style>
    .highlight {
      background-color: yellow;
    }
  </style>
</head>
<body>
  <header>
    <div class="logo-title">
      <img src="logo.png" alt="Логотип" class="logo">
      <h1>Рецепты</h1>
    </div>
    <input type="checkbox" id="menu-toggle">
    <label class="menu-icon" for="menu-toggle">
      <span></span>
      <span></span>
      <span></span>
    </label>
    <div class="menu">
      <a href="index.html">Главная</a>
      <a href="your_receipe.php">Ваш рецепт</a>
      <a href="contact.html">Обратная связь</a>
    </div>
  </header>

  <div class="search-container">
    <input type="text" id="searchInput" placeholder="Поиск рецептов..." class="search-input" value="<?= htmlspecialchars($search) ?>">
    <p id="noResultsMessage" class="no-results" style="display: none;">Рецепты не найдены</p>
  </div>

  <div class="recipes-container" id="recipesContainer">
  <?php if (empty($recipes)): ?>
    <p id="noResultsMessage" class="no-results" style="display: block;">Рецепты не найдены</p>
  <?php else: ?>
    <p id="noResultsMessage" class="no-results" style="display: none;">Рецепты не найдены</p>
    <?php foreach ($recipes as $recipe): ?>
      <div class="recipe-card">
        <img src="data:image/jpeg;base64,<?= htmlspecialchars($recipe['image']) ?>" alt="<?= htmlspecialchars($recipe['title']) ?>" class="recipe-image">
        <div class="recipe-content">
          <h2><?= highlightText(htmlspecialchars($recipe['title']), $search) ?></h2>
          <p><?= highlightText(htmlspecialchars($recipe['description']), $search) ?></p>
          <a href="recipe_detail.php?id=<?= $recipe['id'] ?>">Подробнее</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</div>

  <footer class="footer">
    <div class="left">
      <p>Контакты<br>
      тел.: 89539870092<br>
      email: lizashe2005@mail.ru</p>
    </div>
    <div class="right">
      <p>© 2025 Кулинарная книга.<br>Все права защищены.</p>
    </div>
  </footer>

  <script>
  document.getElementById('searchInput').addEventListener('input', function () {
    const searchQuery = this.value;
    const noResultsMessage = document.getElementById('noResultsMessage');
    const recipesContainer = document.getElementById('recipesContainer');

    fetch(`receipes2.php?search=${encodeURIComponent(searchQuery)}&ajax=true`)
      .then(response => response.text())
      .then(data => {
        recipesContainer.innerHTML = data.trim();

        const hasResults = recipesContainer.querySelector('.recipe-card') !== null;
        noResultsMessage.style.display = hasResults ? 'none' : 'block';
      })
      .catch(error => {
        console.error('Ошибка при поиске рецептов:', error);
      });
  });
</script>


</body>
</html>