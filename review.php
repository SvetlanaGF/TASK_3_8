<?php
$host = 'localhost';
$dbname = 'reviews_db';
$user = 'root';
$password = '';
$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Обработка формы
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
        // Проверяем наличие всех необходимых полей
        $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : '';
        $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
        $rating = isset($_POST['rating']) ? $_POST['rating'] : '';
        $comment = isset($_POST['comment']) ? $_POST['comment'] : '';


        // Валидация данных
        if (!empty($product_id) && !empty($user_name) && !empty($rating) && !empty($comment)) {
            // Подготовка и выполнение запроса на вставку данных
            $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (:product_id, :user_name, :rating, :comment)");
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':user_name', $user_name);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comment', $comment);

            if ($stmt->execute()) {
                echo "<p>Спасибо за ваш отзыв!</p>";
            } else {
                echo "<p>Ошибка отправки отзыва.</p>";
            }
        } else {
            echo "<p>Все поля обязательны для заполнения!</p>";
        }
    }

    // Получение всех отзывов из базы данных
    $sql = "SELECT * FROM reviews";
    $stmt = $conn->query($sql);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Отображение отзывов
    if ($reviews) {
        echo "<h2>Отзывы о товаре:</h2>";
        foreach ($reviews as $review) {
            echo "<p><strong>{$review['user_name']}</strong>: {$review['comment']} (Рейтинг: {$review['rating']})</p>";
        }
    } else {
        echo "<p>Нет отзывов.</p>";
    }
} catch (PDOException $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
?>