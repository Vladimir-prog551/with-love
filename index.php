<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ликорис</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/media/logo/logo.png" type="image/x-icon">
</head>

<body>
    <!-- header start -->
    <header class="container">
        <a href="/?page=homepage">
            <div class="logo">
                <div class="logo_img">
                    <img src="assets/media/logo/logo.png" alt="">
                </div>
                <div class="logo_text">
                    <img src="assets/media/logo/logo_text.svg" alt="">
                </div>
            </div>
        </a>
        <div class="menu">
            <input type="checkbox" id="burger-toggle" class="burger-checkbox">
            <label for="burger-toggle" class="burger-button">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </label>
            <ul class="menu-list">
                <li><a href="/?page=homepage" class="menu-item">Главная</a></li>
                <li><a href="/?page=about" class="menu-item">О нас</a></li>
                <li><a href="/?page=catalog" class="menu-item">Каталог</a></li>
                <li><a href="#advice" class="menu-item">Советы</a></li>
                <li><a href="#FAQ" class="menu-item">FAQ</a></li>
                <?php if (isset($_SESSION['username'])) { ?>
                    <li>
                        <div class="user_name">
                            <a href="/?page=profile"><?php
                            echo $_SESSION['username'];
                            ?></a>
                        </div>
                    </li>
                    <li><a href="/?page=cart"><img src="assets/media/catalog/basket.svg" alt="" style="height: 30px;"></a></li>
                <?php } else { ?>
                    <li><a href="/?page=logIn" class="menu-item btn-exit">Войти</a></li>
                <?php } ?>

                <?php if (isset($_SESSION['username'])) { ?>
                    <a href="/?page=logOut">
                        <div class="exit">
                            <img src="assets/media/header/exit.png" alt="">
                        </div>
                    </a>
                <?php } else { ?>
                    <a href="/?page=logIn">
                        <div class="enter">
                            <p>Войти</p>
                        </div>
                    </a>
                <?php } ?>
            </ul>
        </div>
    </header>
    <!-- header end -->

    <?php
    if (isset($_GET['page'])) {
        $page = $_GET['page'];
        if ($page === 'homepage') {
            include('homepage.php');
        } elseif ($page === 'catalog') {
            include('catalog.php');
        } elseif ($page === 'about') {
            include('about.php');
        } elseif ($page === 'logIn') {
            include('logIn.php');
        } elseif ($page === 'signIn') {
            include('signIn.php');
        } elseif ($page === 'logOut') {
            include('logOut.php');
        } elseif ($page === 'show') {
            include('show.php');
        } elseif ($page === 'adminPanel') {
            include('adminPanel.php');
        } elseif ($page === 'create') {
            include('create.php');
        } elseif ($page === 'edit') {
            include('edit.php');
        } elseif ($page === '404') {
            include('404.php');
        } elseif ($page === 'cart') {
            include('cart.php');
        } elseif ($page === 'addProduct') {
            include('addProduct.php');
        } elseif ($page === 'order') {
            include('order.php');
        } elseif ($page === 'listOrders') {
            include('listOrders.php');
        } elseif ($page === 'editOrder') {
            include('editOrder.php');
        } elseif ($page === 'profile') {
            include('profile.php');
        } else {
            include('404.php');
        }
    } else {
        include('homepage.php');
    }
    ?>

    <!-- footer start -->
    <footer>
        <div class="footer_block container">
            <div class="left_footer">
                <div class="first_left">
                    <img src="assets/media/footer/Ликорис.svg" alt="">
                </div>
                <div class="second_left">
                    <div class="nav_block">
                        <nav>
                            <a href="/?page=homepage">Главная</a>
                            <a href="/?page=about">О нас</a>
                            <a href="/?page=catalog">Каталог</a>
                            <a href="/?page=homepage#advice">Советы</a>
                            <a href="/?page=homepage#faq">FAQ</a>
                        </nav>
                    </div>

                    <div class="info_block">
                        <a href="tel:+7(921)327-22-23">
                            <img src="assets/media/footer/phone.svg" alt="">+7 (921) 327-22-23
                        </a>
                        <a href="mailto:lycoris@gmail.com">
                            <img src="assets/media/footer/email.svg" alt="">lycoris@gmail.com
                        </a>
                        <a href="https://yandex.ru/maps/-/CHuAY6NY">
                            <img src="assets/media/footer/address.svg" alt="">г. Казань, ул. Баумана 21
                        </a>
                        <a href="https://t.me/shaalsi">
                            <img src="assets/media/footer/user.svg" alt="">Разработчик: Шабанова Алёна
                        </a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Админ') { ?>
                            <a href="/?page=adminPanel">
                                <img src="assets/media/footer/admin.svg" alt="" height="20px">Админ-панель
                            </a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="right_block">
                <h2>Свяжитесь с нами!</h2>
                <p>Если остались вопросы по продуктам <br> и услугам, будем рады вам помочь!</p>
                <label for="">номер телефона</label><br>
                <input type="tel" placeholder="+7 (000) 000-00-00">
                <div class="btn_footer">
                    <a href="">Отправить</a>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end -->
</body>
<style>
    .exit img {
        height: 30px;
        display: block;
        margin: 0 auto;
    }

    li {
        text-align: center;
    }
</style>

</html>