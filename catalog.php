<?php

include('database/connection.php');

// вывод категорий
$sql = 'SELECT * FROM categories';
$stmt = $database->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();

$category = '';
$id = '';
$search = '';

// получение категории из ссылки
if (!empty($_GET['category'])) {
    $category = $_GET['category'];
}

// получение поискового запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search = $_POST['search'];
}

// запросы на 2 случая - с поисковым запросом или без
if (empty($search)) {
    $sql = 'SELECT * FROM flowers';
} else {
    $search = '%' . $search . '%';
    $sql = 'SELECT * FROM flowers WHERE title LIKE :search';
}

// запрос на тот случай, если есть и категория
if (!empty($category)) {
    // получаем id категории, чтобы сделать запрос для цветов
    $sql_with_category = 'SELECT * FROM categories WHERE name = :category';
    $stmt_with_category = $database->prepare($sql_with_category);
    $stmt_with_category->bindParam(':category', $category);
    $stmt_with_category->execute();
    $category_id = $stmt_with_category->fetch();
    $id = $category_id['id'];

    // запросы на 2 случая - если есть поисковой запрос или если нет
    if (!empty($search)) {
        $sql = $sql . ' AND category_id = :category_id';
    } else {
        $sql = $sql . ' WHERE category_id = :category_id';
    }
}

$stmt = $database->prepare($sql);

// проверяем, какие параметры связать
if (!empty($search)) {
    $stmt->bindParam(':search', $search);
}
if (!empty($category)) {
    $stmt->bindParam(':category_id', $id);
}

$stmt->execute();
$flowers = $stmt->fetchAll();

?>

<section id="catalog">
    <!-- catalog start -->
    <div class="products container">

        <!-- filter start -->
        <div class="search">
            <form action="" method="post">
                <input type="text" name="search" placeholder="Поиск"
                    value="<?php echo htmlspecialchars(str_replace(['%'], '', $search)); ?>">
                <button>Найти</button>
            </form>
        </div>
        <div class="filter">
            <a href="/?page=catalog&category=">
                <div class="filter_item <?php if (empty($_GET['category'])) {
                    echo 'active';
                } ?>" tabindex="0">Все
                </div>
            </a>

            <?php foreach ($categories as $category): ?>
                <a href="/?page=catalog&category=<?php echo $category['name'] ?>">
                    <div class="filter_item <?php
                    if (isset($_GET['category']) && $_GET['category'] == $category['name']) {
                        echo 'active';
                    }
                    ?>" tabindex="0"><?php echo $category['name'] ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- filter end -->

        <?php if (!empty($flowers)) { ?>

            <div class="products_block">
                <?php foreach ($flowers as $flower): ?>
                    <div class="products_card">
                        <a href="/?page=show&id=<?php echo $flower['id'] ?>">
                            <img src="<?php echo $flower['image'] ?>" alt="">
                            <div class="text_btn">
                                <div class="text_product">
                                    <h3><?php echo $flower['title']; ?></h3>
                                    <p><?php echo htmlspecialchars(number_format((int) ($flower['price']), 0, '', ' ')); ?> ₽
                                    </p>
                                </div>
                                <div class="btn_basket">
                                    <form action="/?page=addProduct" method="post">
                                        <input type="hidden" name="id" value="<?php echo $flower['id']; ?>">
                                        <input type="hidden" name="title" value="<?php echo $flower['title']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $flower['price']; ?>">
                                        <input type="hidden" name="description" value="<?php echo $flower['description']; ?>">
                                        <input type="hidden" name="image" value="<?php echo $flower['image']; ?>">
                                        <input type="hidden" name="previous_page"
                                            value="<?php echo $_SERVER['REQUEST_URI'] . '#catalog'; ?>">
                                        <button type="submit"
                                            style="background: none; border: none; padding: 0; cursor: pointer;">
                                            <img src="assets/media/catalog/basket.svg" alt="basket">
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php } else { ?>
            <p>Нет товаров</p>
        <?php } ?>

    </div>
    <!-- catalog end -->
</section>

<style>
    .search {
        display: flex;
        justify-content: center;
        width: 100%;
        margin: 20px 0;
    }

    .search form {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .search input {
        width: 300px;
        height: 40px;
        padding: 0 15px;
        border-radius: 12px;
        border: 1px solid #000;
    }

    .search button {
        height: 40px;
        padding: 0 20px;
        border-radius: 12px;
        background: #F6D0DD;
        border: 1px solid #F6D0DD;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search button:hover {
        background: #e8b8ca;
    }

    form {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter a {
        color: #2d2d2d;
    }

    .btn_basket img {
        border-radius: 0;
    }
</style>