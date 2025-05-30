<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Админ') {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$flowers = '';
$users = '';
$orders = '';
$categories = '';
$action = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_product'])) {
        $id = $_POST['id_product'];
        $old_image = $_POST['old_image'];
        unlink($old_image);

        $sql = 'DELETE FROM flowers WHERE id = :id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('Location: /?page=adminPanel');
        exit();
    } elseif (isset($_POST['id_order'])) {
        $id = $_POST['id_order'];
        $sql = 'DELETE FROM orders WHERE id = :id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $sql = 'DELETE FROM order_items WHERE order_id = :id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('Location: /?page=adminPanel&action=orders');
        exit();
    } elseif (isset($_POST['id_user'])) {
        $id = $_POST['id_user'];
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        header('Location: /?page=adminPanel&action=users');
        exit();
    } elseif (isset($_POST['status'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $sql = 'UPDATE orders SET status = :status WHERE id = :id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $id);
        if ($status === 'В обработке') {
            $status = 'Завершён';
        } else {
            $status = 'В обработке';
        }
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        header('Location: /?page=adminPanel&action=orders');
        exit();
    }
}


if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action === 'products' || empty($action)) {
    $sql = 'SELECT * FROM flowers';
    $stmt = $database->prepare($sql);
    $stmt->execute();
    $flowers = $stmt->fetchAll();

    $sql = 'SELECT * FROM categories';
    $stmt = $database->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll();
} elseif ($action === 'orders') {
    // Получаем все заказы
    $sql = 'SELECT * FROM orders';
    $stmt = $database->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll();
} elseif ($action === 'users') {
    $sql = 'SELECT * FROM users';
    $stmt = $database->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll();
}

?>

<!-- panel start -->
<div class="panel container">
    <div class="left_menu">
        <a href="/?page=adminPanel&action=products">Список товаров</a>
        <a href="/?page=adminPanel&action=orders">Список заказов</a>
        <a href="/?page=adminPanel&action=users">Список пользователей</a>
        <a href="/?page=create">Добавление товара</a>
    </div>
    <div class="right_content">
        <?php if (!empty($flowers)) { ?>
            <div class="flowers_block">
                <table>
                    <thead>
                        <tr>
                            <th>Название</th>
                            <th>Изображение</th>
                            <th>Описание</th>
                            <th>Стоимость</th>
                            <th>Категория</th>
                            <th>Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($flowers as $flower): ?>
                            <tr class="card_flower">
                                <td>
                                    <p><?php echo htmlspecialchars($flower['title']); ?></p>
                                </td>
                                <td>
                                    <img src="<?php echo $flower['image']; ?>"
                                        alt="<?php echo htmlspecialchars($flower['title']); ?>">
                                </td>
                                <td>
                                    <p><?php echo htmlspecialchars($flower['description']); ?></p>
                                </td>
                                <td style="text-align: center;">
                                    <p><?php echo htmlspecialchars(number_format((int) ($flower['price']), 0, '', ' ')); ?> ₽
                                    </p>
                                </td>
                                <td>
                                    <p>
                                        <?php foreach ($categories as $category):
                                            if ($flower['category_id'] === $category['id']) { ?>
                                            <p><?php echo htmlspecialchars($category['name']); ?></p>
                                            <?php break;
                                            }
                                        endforeach; ?>
                                    </p>
                                </td>
                                <td>
                                    <div class="btns_action">
                                        <div class="edit_btn">
                                            <a href="/?page=edit&id=<?php echo $flower['id']; ?>"><img
                                                    src="assets/media/icon/edit.svg" alt="edit"></a>
                                        </div> <br>
                                        <form action="" method="post">
                                            <button class="btn btn-danger delete_btn"
                                                onclick="return confirm('Вы действительно хотите удалить товар?');">
                                                <img src="assets/media/icon/delete.svg" alt="delete">
                                            </button>
                                            <input type="hidden" name="old_image" value="<?php echo $flower['image'] ?>">
                                            <input type="hidden" name="id_product" value="<?php echo $flower['id'] ?>">
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php } elseif (!empty($orders)) { ?>
            <div class="orders_container">
                <?php foreach ($orders as $order): ?>
                    <div class="order-item">
                        <p>Заказ №<?php echo htmlspecialchars($order['id']); ?></p>
                        <p>Заказчик: <?php echo htmlspecialchars($order['username']); ?></p>
                        <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
                        <p>Дата заказа: <?php echo htmlspecialchars($order['date_order']); ?></p>
                        <p>Общая цена заказа:
                            <?php echo htmlspecialchars(number_format((int) ($order['order_price']), 0, '', ' ')); ?> ₽
                        </p>


                        <p>Статус заказа: <?php echo htmlspecialchars($order['status']); ?></p>



                        <!-- Выводим товары в заказе -->
                        <div class="order-items" style="margin-top: 15px;">
                            <p><strong>Товары в заказе:</strong></p>

                            <?php
                            $sql = 'SELECT * FROM order_items WHERE order_id = :order_id';
                            $stmt = $database->prepare($sql);
                            $stmt->bindParam(':order_id', $order['id']);
                            $stmt->execute();
                            $flowers = $stmt->fetchAll();
                            ?>
                            <ul style="list-style-type: none; padding-left: 0;">
                                <?php foreach ($flowers as $flower): ?>
                                    <li style="margin-bottom: 10px; border-bottom: 1px dashed #eee; padding-bottom: 5px;">
                                        <?php echo htmlspecialchars($flower['flower_title']); ?> ×
                                        <?php echo htmlspecialchars($flower['quantity']); ?> шт. −
                                        <?php echo htmlspecialchars(number_format($flower['price_at_order'] * $flower['quantity'], 0, '', ' ')); ?>
                                        ₽
                                        <br>
                                        <small>(Цена за шт.:
                                            <?php echo htmlspecialchars(number_format($flower['price_at_order'], 0, '', ' ')); ?>
                                            ₽)</small>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="btns_action">
                            <form action="" method="post">
                                <input type="hidden" name="id" value="<?php echo $order['id']; ?>">
                                <input type="hidden" name="status" value="<?php echo $order['status']; ?>">
                                <button class="btn btn-danger"
                                    onclick="return confirm('Вы действительно хотите изменить статус заказа?');">
                                    <img src="assets/media/icon/edit.svg" alt="edit">
                                </button>
                            </form>
                            <br>
                            <form action="" method="post">
                                <button class="btn btn-danger"
                                    onclick="return confirm('Вы действительно хотите удалить заказ?');">
                                    <img src="assets/media/icon/delete.svg" alt="delete">
                                </button>
                                <input type="hidden" name="id_order" value="<?php echo $order['id']; ?>">
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php } elseif (!empty($users)) { ?>
            <div class="orders_container">
                <?php foreach ($users as $user): ?>
                    <div class="user-item">
                        <p>Имя: <?php echo htmlspecialchars($user['username']); ?> </p>
                        <p>Почта: <?php echo htmlspecialchars($user['email']); ?> </p>
                        <p>Роль: <?php echo htmlspecialchars($user['role']); ?> </p>
                        <form action="" method="post">
                            <div class="btns_action">
                                <button class="btn btn-danger"
                                    onclick="return confirm('Вы действительно хотите удалить пользователя?');">
                                    <img src="assets/media/icon/delete.svg" alt="">
                                </button>
                            </div>
                            <input type="hidden" name="id_user" value="<?php echo $user['id'] ?>">
                        </form>
                    </div>
                <?php endforeach;
        } ?>
        </div>
    </div>
</div>
<!-- panel end -->

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
        color: var(--text-color);
    }

    .panel {
        width: 100%;
        max-width: 1300px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 0 20px rgba(246, 208, 221, 0.6);
        display: flex;
        overflow: hidden;
        flex-direction: row;
    }

    .left_menu {
        padding: 30px;
        border-right: 2px solid var(--border-color);
        width: 380px;
        background-color: #fff;
        box-sizing: border-box;
    }

    .left_menu a {
        display: block;
        padding: 12px 15px;
        margin-bottom: 10px;
        font-size: 20px;
        color: var(--secondary-color);
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s;
    }

    .left_menu a:hover {
        background-color: var(--primary-color);
        color: var(--secondary-color);
    }

    .right_content {
        flex: 1;
        padding: 30px;
        background-color: #ffffff;
        box-sizing: border-box;
        overflow-x: auto;
    }

    .flowers_block table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        box-shadow: var(--card-shadow);
    }

    .flowers_block th {
        background-color: var(--primary-color);
        color: var(--secondary-color);
        padding: 15px;
        text-align: left;
        font-size: 16px;
        font-weight: 500;
    }

    .flowers_block td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .flowers_block img {
        max-width: 100px;
        max-height: 100px;
        display: block;
        margin: 0 auto;
        border-radius: 4px;
        object-fit: cover;
    }

    .flowers_block a {
        color: var(--secondary-color);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s;
    }

    .flowers_block a:hover {
        color: rgb(210, 96, 153);
    }

    .order-item,
    .user-item {
        background: #fff;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        box-shadow: var(--card-shadow);
    }

    .order-item p,
    .user-item p {
        margin-bottom: 5px;
        font-size: 18px;
    }

    .card_flower p {
        font-weight: normal;
        font-size: 17px;
    }

    .btns_action {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btns_action img {
        height: 30px;
        transition: transform 0.3s;
    }

    .btns_action img:hover {
        transform: scale(1.1);
    }

    .btns_action button {
        border: none;
        background: none;
        cursor: pointer;
    }

    .orders_container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 20px;
    }

    .user-item span {
        font-size: 10px;
    }

    /* Адаптация для 1400px */
    @media (max-width: 1400px) {
        .panel {
            max-width: 1200px;
        }

        .left_menu {
            width: 300px;
            padding: 20px;
        }

        .right_content {
            padding: 20px;
        }

        .flowers_block th,
        .flowers_block td {
            padding: 12px;
            font-size: 15px;
        }

        .orders_container {
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        }
    }

    /* Адаптация для 1000px */
    @media (max-width: 1000px) {
        .panel {
            flex-direction: column;
        }

        .left_menu {
            width: 100%;
            border-right: none;
            border-bottom: 2px solid var(--border-color);
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 15px;
        }

        .left_menu a {
            margin-bottom: 0;
            font-size: 18px;
            padding: 10px 12px;
        }

        .right_content {
            padding: 15px;
        }

        .flowers_block table {
            font-size: 14px;
        }

        .flowers_block th,
        .flowers_block td {
            padding: 10px;
        }

        .orders_container {
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        }

        .order-item,
        .user-item {
            padding: 15px;
        }
    }

    /* Адаптация для 393px (мобильные) */
    @media (max-width: 393px) {
        body {
            padding: 10px;
        }

        .panel {
            border-radius: 8px;
        }

        .left_menu {
            flex-direction: column;
            gap: 5px;
            padding: 10px;
        }

        .left_menu a {
            font-size: 16px;
            padding: 8px 10px;
        }

        .right_content {
            padding: 10px;
        }

        .flowers_block {
            overflow-x: auto;
        }

        .flowers_block table {
            min-width: 600px;
        }

        .flowers_block img {
            max-width: 80px;
            max-height: 80px;
        }

        .orders_container {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .order-item,
        .user-item {
            width: auto;
            margin-bottom: 10px;
        }

        .order-item p,
        .user-item p {
            font-size: 16px;
        }

        .btns_action img {
            height: 25px;
        }
    }
</style>