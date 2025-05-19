<?php
require_once 'script.php'; // подключаем PDO и базовые функции

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Проверяем обязательные поля
        if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['ingredients'])) {
            throw new Exception('Пожалуйста, заполните все обязательные поля.');
        }

        // Проверяем файл картинки
        if (empty($_FILES['photo']['tmp_name'])) {
            throw new Exception('Пожалуйста, загрузите фото рецепта.');
        }

        // Читаем содержимое изображения и кодируем в base64
        $imageData = base64_encode(file_get_contents($_FILES['photo']['tmp_name']));

        // Подготавливаем и выполняем запрос на добавление
        $stmt = $pdo->prepare("
            INSERT INTO recipes (title, description, ingredients, image)
            VALUES (:title, :description, :ingredients, :image)
        ");

        $stmt->execute([
            ':title' => $_POST['title'],
            ':description' => $_POST['description'],
            ':ingredients' => $_POST['ingredients'],
            ':image' => $imageData,
        ]);

        $successMessage = "Рецепт успешно добавлен!";
        header("Location: receipes2.php");
        exit;
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <!-- Базовые настройки страницы -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ваш рецепт</title>
  <!-- Подключение шрифта Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <!-- Подключение CSS-файла -->
  <link rel="stylesheet" href="styles_your_receipe.css">
</head>
<body>
  <!-- Шапка сайта с логотипом и навигацией -->
  <header>
    <div class="logo-title">
      <!-- Логотип сайта -->
      <img src="logo.png" alt="Логотип" class="logo">
      <!-- Заголовок страницы -->
      <h1>Ваш рецепт</h1>
    </div>
    <!-- Чекбокс для мобильного меню (гамбургер) -->
    <input type="checkbox" id="menu-toggle">
    <!-- Иконка мобильного меню (гамбургер) -->
    <label class="menu-icon" for="menu-toggle">
      <span></span>
      <span></span>
      <span></span>
    </label>

    <!-- Основное меню навигации -->
    <div class="menu">
      <a href="index.html">Главная</a>
      <a href="receipes2.php">Рецепты</a>
      <a href="contact.html">Обратная связь</a>
    </div>
  </header>

  <!-- Основной контейнер с формой добавления рецепта -->
  <div class="container">
    <!-- Форма для добавления нового рецепта -->
    <form id="recipeForm" method="POST" enctype="multipart/form-data">
      <!-- Поле для названия рецепта -->
      <div>
        <label for="title">Название рецепта</label>
        <input type="text" id="title" name="title" placeholder="Введите название" required>
      </div>
      <!-- Поле для краткого описания -->
      <div>
        <label for="description">Краткое описание</label>
        <input type="text" id="description" name="description" placeholder="Введите описание" required>
      </div>
      <!-- Поле для списка ингредиентов -->
      <div>
        <label for="ingredients">Состав</label>
        <textarea id="ingredients" name="ingredients" placeholder="Введите ингредиенты" required></textarea>
      </div>
      <!-- Блок для загрузки изображения -->
      <div class="upload-block">
        <label for="photo" class="upload-button">Загрузите фото</label>
        <input type="file" id="photo" name="photo" accept="image/*">
        <!-- Превью загруженного изображения -->
        <img id="preview" class="preview-image" alt="Предпросмотр изображения">
      </div>
      <!-- Кнопка отправки формы -->
      <button type="submit" class="button">Добавить</button>
    </form>
  </div>

  <!-- Подвал сайта -->
  <div class="footer">
    <!-- Левая часть подвала с контактами -->
    <div class="left">
      <p>Контакты<br>тел.: 89539870092<br>email: lizashe2005@mail.ru</p>
    </div>
    <!-- Правая часть подвала с копирайтом -->
    <div>
      <p>© 2025 Кулинарная книга.<br>Все права защищены.</p>
    </div>
  </div>

<?php if(!empty($successMessage)): ?>
  <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php elseif(!empty($errorMessage)): ?>
  <p style="color: red;"><?= htmlspecialchars($errorMessage) ?></p>
<?php endif; ?>

  <script>
    // Обработчик для предпросмотра загружаемого изображения
    document.getElementById('photo').addEventListener('change', function(e) {
      const preview = document.getElementById('preview');
      const file = e.target.files[0];

      if (file) {
        const reader = new FileReader();

        // Отображаем превью после загрузки файла
        reader.onload = function(e) {
          preview.src = e.target.result;
          preview.style.display = 'block';
        }

        reader.readAsDataURL(file);
      }
    });
  </script>
  <script src="script.js"></script>

</body>
</html>