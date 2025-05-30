<?php

if (!isset($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$user_id = $_SESSION['id'];

// История заказов
// 1. Поиск заказов нашего пользователя
$sql = 'SELECT * FROM orders WHERE user_id = :user_id';
$stmt = $database->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$orders = $stmt->fetchAll();
// 2. Получение товаров наших заказов
foreach ($orders as &$order):
    $sql = 'SELECT * FROM order_items WHERE order_id = :order:id';
    $stmt = $database->prepare($sql);
    $stmt->bindParam(':order_id', $order['id']);
    $stmt->execute();
    $order['flowers'] = $stmt->fetchAll();
endforeach;
unset($order);

?>

<div class="products container">
    <h3 style="margin:2rem 0 1rem">История заказов</h3>

    <?php
    foreach ($orders as $order):
        $total = 0;
        ?>
        <div class="order-block">
            <div class="order-header">
                <div>Заказ №<?php echo $order['id']; ?> от <?php echo $order['date']; ?></div>
                <div><span style="font-weight: 300">Статус: </span><?php echo $order['status']; ?></div>
            </div>
            <table class="order-products">
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['flowers'] as $flower): ?>
                        <tr>
                            <td><?php echo $flower['flower_title']; ?></td>
                            <td><?php echo number_format((int) ($flower['price_at_order']), 0, '', ' '); ?> ₽</td>
                            <td><?php echo $flower['quantity']; ?></td>
                            <td><?php echo number_format((int) ($flower['flower_title'] * $flower['price_at_order']), 0, '', ' '); ?>
                                ₽</td>
                        </tr>
                        <?php $total += $flower['flower_title'] * $flower['price_at_order']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p><strong>Итого по заказу: <?php echo number_format((int) ($total), 0, '', ' '); ?> ₽</strong></p>
        </div>
    <?php endforeach; ?>

    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
</div>

<style>
    :root {
        --primary-color: #F6D0DD;
        --secondary-color: #2d2d2d;
        --text-color: #333;
        --border-color: #e0e0e0;
        --card-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: #f9f9f9;
        margin: 0;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0
    }

    th,
    td {
        padding: .75rem;
        border: 1px solid #ddd;
        text-align: left
    }

    th {
        background: #f1f1f1
    }

    .order-block {
        background: #fff;
        border: 1px solid #ddd;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border-radius: 6px;
    }

    .order-header {
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .order-products {
        width: 100%;
        border-collapse: collapse;
        margin-top: .5rem;
    }

    .order-products th,
    .order-products td {
        border: 1px solid #ddd;
        padding: .75rem;
        text-align: left;
    }

    .order-products th {
        background-color: #f8f8f8;
    }
</style>