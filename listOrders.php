<?php

if (empty($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$email = $_SESSION['email'];

$sql = 'SELECT * FROM orders WHERE email = :email';
$stmt = $database->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$orders = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="panel container">
        <div class="left_menu">
            <p>Список ваших заказов</p>
        </div>
        <div class="right_content">
            <?php if (!empty($orders)) { ?>
                <div class="orders_container">
                    <?php foreach ($orders as $order): ?>
                        <div class="products_card">
                            <p>Название товара: <?php
                            $sql = 'SELECT * FROM flowers WHERE id = :id';
                            $stmt = $database->prepare($sql);
                            $stmt->bindParam(':id', $order['flower_id']);
                            $stmt->execute();
                            $temp = $stmt->fetch();
                            echo $temp['title'];
                            ?></p><br>
                            <p>Цена товара: <?php echo $order['order_price']; ?></p><br>
                            <img src="<?php echo $temp['image']; ?>" alt="">
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php } else { ?>
                <p>У вас нет заказов</p><br><br>
            <?php } ?>
        </div>
    </div>
</body>
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

</html>