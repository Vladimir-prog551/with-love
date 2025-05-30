<?php

if (!isset($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temp = $_POST['temp'];
    if ($temp === 'add') {
        $product_id_post = $_POST['product_id'];
        $_SESSION['cart'][$product_id_post]['quantity'] += 1;
    } elseif ($temp === 'minus') {
        $product_id_post = $_POST['product_id'];
        $_SESSION['cart'][$product_id_post]['quantity'] -= 1;

        // Если количество стало 0 или меньше, удаляем товар из корзины
        if ($_SESSION['cart'][$product_id_post]['quantity'] <= 0) {
            unset($_SESSION['cart'][$product_id_post]);
        }
    } else {
        $product_id_post = $_POST['product_id'];
        unset($_SESSION['cart'][$product_id_post]);
    }
    header('Location: /?page=cart');
    exit();
}

$cart = $_SESSION['cart'];

?>

<div class="basket">
    <?php if (!empty($cart)) {
        $sum = 0;
        foreach ($cart as $product_id => $product): ?>
            <div class="cart_product">
                <img src="<?php echo $product['image']; ?>"><br>
                <div class="name_price">
                    <p><?php echo $product['name']; ?></p><br>
                    <p><?php echo $product['description']; ?></p><br>
                    <p>Стоимость: <?php echo htmlspecialchars(number_format((int) ($product['price']), 0, '', ' ')); ?>₽</p><br>
                    <div class="btn_basket2">
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                            <input type="hidden" name="temp" value="delete">
                            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer;">
                                <img src="assets/media/icon/delete.svg" alt="cross">
                            </button>
                        </form>
                    </div>
                    <div style="display: flex;align-items: center;gap: 10px;">
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                            <input type="hidden" name="temp" value="minus">
                            <button class="btn">-</button>
                        </form>
                        <p><?php echo $product['quantity']; ?></p>
                        <form action="" method="post">
                            <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                            <input type="hidden" name="temp" value="add">
                            <button class="btn">+</button>
                        </form>
                        <div class="name_price">
                            <p><?php echo htmlspecialchars(number_format((int) ($product['price'] * $product['quantity']), 0, '', ' ')); ?> ₽
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php $sum += $product['price'] * $product['quantity']; endforeach; ?>
        <p>Общая стоимость товаров в вашей корзине: <?php echo htmlspecialchars(number_format((int) ($sum), 0, '', ' ')); ?>
            ₽</p><br><br>
        <a href="/?page=order">Заказать</a><br>
    <?php } else { ?>
        <p>Корзина пуста</p><br><br>
    <?php } ?>
    <a href="/?page=listOrders"">Список ваших заказов</a>
</div>
<style>
    /* Нежная базовая стилизация */
    body {
        font-family: 'Montserrat', 'Arial', sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #fff9fb;
        color: #5a5a5a;
    }

    .basket {
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

    .name_price {
        flex: 1;
        position: relative;
        padding-right: 40px;
    }

    .name_price p:first-child {
        font-size: 18px;
        font-weight: 500;
        color: #7a6a7a;
        margin-bottom: 12px;
        letter-spacing: 0.3px;
    }

    .name_price p:nth-child(2) {
        font-size: 17px;
        color: #e8a1a8;
        font-weight: 500;
    }

    /* Кнопка удаления */
    .btn_basket2 {
        position: absolute;
        top: 0;
        right: 0;
    }

    .btn_baske2 button {
        background: none;
        border: none;
        padding: 8px;
        border-radius: 50%;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn_basket2 img {
        height: 30px;
        width: 30px;
        opacity: 0.7;
        transition: opacity 0.3s;
    }

    .btn_basket2 button:hover img {
        opacity: 0.9;
    }

    /* Общая стоимость */
    .basket>p {
        font-size: 18px;
        font-weight: 500;
        text-align: right;
        margin: 40px 0 30px;
        color: #8a7a8a;
        letter-spacing: 0.5px;
    }

    /* Кнопки */
    .basket>a {
        display: inline-block;
        padding: 15px 35px;
        background-color: #F6D0DD;
        color: #000;
        text-decoration: none;
        border-radius: 15px;
        font-weight: 500;
        margin-right: 15px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        border: none;
        letter-spacing: 0.5px;
    }

    .basket>a:hover {
        background-color: #ff9eaf;
    }

    /* Пустая корзина */
    .basket>p:first-child {
        text-align: center;
        font-size: 18px;
        color: #c9a8b3;
        padding: 60px 0;
        font-weight: 400;
        letter-spacing: 0.5px;
    }

    /* Адаптив для 1400px */
    @media (max-width: 1400px) {
        .basket {
            padding: 35px 22px;
        }

        .cart_product {
            gap: 25px;
            padding: 22px;
        }

        .cart_product img {
            height: 160px;
            width: 160px;
        }
    }

    /* Адаптив для 1000px */
    @media (max-width: 1000px) {
        .basket {
            padding: 30px 18px;
        }

        .cart_product {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
        }

        .cart_product img {
            height: 150px;
            width: 100%;
            max-width: 220px;
            align-self: center;
        }

        .name_price {
            padding-right: 0;
            width: 100%;
        }

        .btn_basket2 {
            top: -10px;
            right: -10px;
        }

        .basket>a {
            padding: 14px 30px;
        }
    }

    /* Адаптив для 393px (мобильные) */
    @media (max-width: 393px) {
        .basket {
            padding: 25px 15px;
        }

        .cart_product {
            padding: 18px;
            margin-bottom: 20px;
            border-radius: 14px;
        }

        .cart_product img {
            height: 140px;
            max-width: 180px;
        }

        .name_price p:first-child {
            font-size: 17px;
            padding-right: 40px;
        }

        .name_price p:nth-child(2) {
            font-size: 16px;
        }

        .btn_basket2 button {
            padding: 7px;
        }

        .btn_basket2 img {
            height: 18px;
            width: 18px;
        }

        .basket>p {
            font-size: 17px;
            margin: 35px 0 25px;
        }

        .basket>a {
            width: 100%;
            text-align: center;
            margin: 10px 0;
            padding: 14px;
        }
    }
</style>