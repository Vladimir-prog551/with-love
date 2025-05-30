<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Админ') {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$message = '';
$order = '';
$statuses = '';
$flower_title = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_order'];
    $status_id = $_POST['status_id'];
    $sql = 'UPDATE orders SET status_id = :status_id WHERE id = :id';
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':status_id', $status_id);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        $message = 'Статус заказа отредактирован';
    } else {
        $message = 'Ошибка редактирования статуса заказа';
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql_order = 'SELECT
        o.id AS order_id,
        o.date_order AS order_date,
        o.username AS customer_name,
        o.email AS customer_email,
        o.status_id AS current_status_id,
        SUM(oi.price_at_order * oi.quantity) AS order_total
    FROM orders AS o
    JOIN order_items AS oi ON o.id = oi.order_id
    WHERE o.id = :order_id
    GROUP BY o.id'; // Группируем по id заказа, чтобы получить одну строку для сводки заказа
    $stmt_order = $database->prepare($sql_order);
    $stmt_order->bindParam(':order_id', $id);
    $stmt_order->execute();
    $order_details = $stmt_order->fetch();

    if (!$order_details) {
        $message = 'Нет заказа с указанным id';
    } else {
        // Теперь получаем отдельные товары заказа
        $sql_items = 'SELECT
            f.id AS flower_id,
            f.title AS flower_name,
            f.image AS flower_image,
            oi.price_at_order AS unit_price,
            oi.quantity AS quantity,
            (oi.price_at_order * oi.quantity) AS item_total
        FROM order_items AS oi
        JOIN flowers AS f ON oi.flower_id = f.id
        WHERE oi.order_id = :order_id';
        $stmt_items = $database->prepare($sql_items);
        $stmt_items->bindParam(':order_id', $id);
        $stmt_items->execute();
        $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

        // Получаем статусы
        $sql_statuses = 'SELECT * FROM statuses';
        $stmt_statuses = $database->prepare($sql_statuses);
        $stmt_statuses->execute();
        $statuses = $stmt_statuses->fetchAll();
    }
} else {
    $message = 'Не указан id заказа';
}

?>

<div class="container">
    <a href="/?page=adminPanel&action=orders">
        <a href="/?page=adminPanel&action=orders">
            <button class="btn_in_admin">
                Вернуться назад
            </button>
        </a>
    </a>
</div>
<?php if (!empty($order_details)) { ?>
    <div class="basket">
        <p>Заказ №<?php echo htmlspecialchars($order_details['order_id']); ?></p>
        <p>Дата заказа: <?php echo htmlspecialchars($order_details['order_date']); ?> </p>
        <p>Заказчик: <?php echo htmlspecialchars($order_details['customer_name']); ?> </p>
        <p>Email: <?php echo htmlspecialchars($order_details['customer_email']); ?> </p>
        <p>Общая цена заказа:
            <?php echo htmlspecialchars(number_format((int) ($order_details['order_total']), 0, '', ' ')); ?> ₽
        </p>
        <p>Список товаров в заказе:</p>

        <?php foreach ($order_items as $item): ?>
            <div class="cart_product">
                <img src="<?= htmlspecialchars($item['flower_image']) ?>" width="80">
                <h4><?= htmlspecialchars($item['flower_name']) ?></h4>
                <p>Цена: <?= number_format($item['unit_price'], 0, '', ' ') ?> ₽</p>
                <p>Количество: <?= $item['quantity'] ?></p>
                <p>Сумма: <?= number_format($item['item_total'], 0, '', ' ') ?> ₽</p>
            </div>
        <?php endforeach; ?>


        <form action="" method="post">
            <input type="hidden" name="id_order" value="<?php echo $order_details['order_id']; ?>">

            <?php foreach ($statuses as $status): ?>
                <div class="input_category">
                    <input type="radio" name="status_id" value="<?php echo $status['id'] ?>" <?php
                       if ($status['id'] === $order_details['current_status_id']) { // Исправлено на использование current_status_id
                           echo 'checked';
                       }
                       ?>>
                    <label for=""><?php echo $status['name'] ?></label> <br>
                </div>
            <?php endforeach; ?>

            <div class="create-btn">
                <input type="submit" value="Редактировать">
            </div>
        </form>
    </div>

<?php } ?>
<div class="container">
    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
</div>
<style>
    .btn_in_admin {
        text-decoration: underline;
        border: none;
        background: none;
    }

    p {
        margin-bottom: 10px;
    }

    .basket {
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px 25px;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 40px 25px;
    }

    /* Карточка товара */
    .cart_product {
        display: flex;
        align-items: center;
        gap: 30px;
        background: #fff;
        padding: 25px;
        margin-bottom: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(255, 182, 193, 0.1);
        border: 1px solid #ffe6ea;
        transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
    }

    .cart_product:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 25px rgba(255, 182, 193, 0.15);
    }

    .cart_product img {
        height: 180px;
        width: 180px;
        object-fit: cover;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
    }
</style>