<?php

if (!isset($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$cart = $_SESSION['cart'];
$message = '';

if (!empty($cart)) {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $user_id = $_SESSION['id'];

    // Создаём заказ
    $sql = "INSERT INTO orders (user_id, username, email, order_price, date_order, status) VALUES (:user_id, :username, :email, 0, CURDATE(), 'В обработке')";
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    if ($stmt->execute()) {
        $order_id = $database->lastInsertId();
        $order_price = 0;
        // Добавление товаров в заказ
        $sql = 'INSERT INTO order_items (order_id, flower_title, quantity, price_at_order) VALUES (:order_id, :flower_title, :quantity, :price_at_order)';
        $stmt = $database->prepare($sql);
        foreach ($cart as $flower_id => $flower):
            $stmt->bindParam(':order_id', $order_id);
            $stmt->bindParam(':flower_title', $flower['name']);
            $stmt->bindParam(':quantity', $flower['quantity']);
            $stmt->bindParam(':price_at_order', $flower['price']);
            $stmt->execute();
            $order_price += $flower['price'] * $flower['quantity'];
        endforeach;
        // Обновление итоговой суммы заказа
        $sql = 'UPDATE orders SET order_price = :order_price WHERE id = :order_id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':order_price', $order_price);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        $message = 'Спасибо за заказ!';
        $_SESSION['cart'] = [];
    }
} else {
    $message = 'Корзина пуста :(';
}
?>

<div class="products container">
    <a href="/?page=listOrders">Список ваших заказов</a><br><br>

    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
</div>