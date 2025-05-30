<?php

include('database/connection.php');

$message = '';
$flower = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    if (empty($id)) {
        $message = 'Не указан id товара!';
    } else {
        $sql = 'SELECT * FROM flowers WHERE id=:id';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $flower = $stmt->fetch();
        if (!$flower) {
            $message = 'Неверный id';
        }
    }
}

?>

<!-- card start -->
<div class="card_product container">
    <?php if (!empty($flower)) { ?>
        <img src="<?php echo $flower['image']; ?>" alt="">
        <div class="info_card">
            <h2><?php echo $flower['title'] ?></h2>
            <h3><?= number_format($flower['price'], 0, '', ' ') ?> ₽</h3>
            <p><?php echo $flower['description'] ?></p>
            <div class="btn_basket_show">
                <form action="/?page=addProduct" method="post">
                    <input type="hidden" name="id" value="<?php echo $flower['id']; ?>">
                    <input type="hidden" name="title" value="<?php echo $flower['title']; ?>">
                    <input type="hidden" name="price" value="<?php echo $flower['price']; ?>">
                    <input type="hidden" name="description" value="<?php echo $flower['description']; ?>">
                    <input type="hidden" name="image" value="<?php echo $flower['image']; ?>">
                    <input type="hidden" name="previous_page" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <button type="submit" style="cursor: pointer;">
                        Добавить в корзину
                    </button>
                </form>
            </div>
        <?php } ?>
    </div>
    <?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>
</div>
<!-- card end -->

<style>
    /* card start */
    .card_product {
        display: flex;
        align-items: center;
        gap: 40px;
        justify-content: center;
    }

    .card_product img {
        height: 500px;
        top: 0;
        left: 0;
    }

    .card_product h2 {
        font-size: 24px;
        color: #2E2E2E;
    }

    .card_product h3 {
        font-size: 20px;
        color: #2E2E2E;
        margin: 10px 0 20px 0;
    }

    .card_product p {
        font-size: 16px;
        color: #2E2E2E;
        margin-bottom: 20px;
    }

    .info_card {
        width: 400px;
    }

    .btn-cards {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .btn-edit {
        height: 40px;
        width: 150px;
        border-radius: 12px;
        background-color: rgb(175, 175, 175);
        text-align: center;
        font-size: 16px;
        padding: 10px 0;
    }

    .btn-edit a {
        color: #000000;
    }

    .btn-card a {
        color: #2E2E2E;
    }

    .btn-cards button {
        height: 40px;
        width: 150px;
        border-radius: 12px;
        background-color: rgb(171, 32, 32);
        text-align: center;
        font-size: 16px;
        padding: 10px 0;
        color: #ffffff;
        border: 1px solid rgb(171, 32, 32);
    }

    .btn_basket_show button {
        width: 220px;
        height: 40px;
        border-radius: 12px;
        background-color: #F6D0DD;
        border: 1px solid #F6D0DD;
        margin-top: 20px;
        font-size: 16px;
    }

    .info_card {
        width: 400px;
    }

    /* card end */
</style>