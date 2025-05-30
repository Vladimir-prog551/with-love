<?php

include('database/connection.php');

$sql = 'SELECT * FROM flowers LIMIT 6';
$stmt = $database->prepare($sql);
$stmt->execute();
$flowers = $stmt->fetchAll();

?>

<body>
    <!-- banner start -->
    <div class="banner container">
        <div class="banner_left">
            <div class="bnr_l_top">
                <a href="https://vk.com" target="_blank">
                    <img src="assets/media/banner/vk.svg" alt="vk"></a>
                <a href="https://web.telegram.org">
                    <img src="assets/media/banner/telegram.svg" alt="tg" target="_blank"></a>
            </div>

            <div class="bnr_l_center">
                <img src="assets/media/banner/flower.svg" alt="">
                <h1>Цветы, которые <br>
                    расскажут о чувствах!</h1>
                <div class="link_banner">
                    <a href="/?page=catalog">В каталог</a>
                    <img src="assets/media/banner/link.svg" alt="">
                </div>
            </div>

            <div class="bnr_l_bottom">
                <img src="assets/media/banner/people.svg" alt="">
                <div class="text_stars">
                    <img src="assets/media/banner/stars.svg" alt="">
                    <p>+500 довольных клиентов</p>
                </div>
            </div>
        </div>

        <div class="banner_right">
            <img src="assets/media/banner/delivery.svg" alt="">
            <div class="bnt_r_text">
                <h4>Специальное предложение</h4>
                <p>Бесплатная доставка по городу при <br> заказе от 8 000 ₽.</p>
            </div>
        </div>
    </div>
    <!-- banner end -->

    <!-- advantages start -->
    <div class="advantages" id="advantages">
        <h2 class="container">Расскажем, почему <br> выбирают нас</h2>

        <div class="advantages_block container">
            <div class="advantage">
                <img src="assets/media/about/plus_1.svg" alt="">
                <h3>Широкий ассортимент</h3>
                <p>В “Ликорис” можно найти разнообразные цветы, чтопозволяет выбрать идеальный букет.
                </p>
            </div>

            <div class="advantage">
                <img src="assets/media/about/plus_2.svg" alt="">
                <h3>Индивидуальный подход</h3>
                <p>Флористы учитывают пожелания, чтобы создать букет, который идеально подойдет для вас.</p>
            </div>

            <div class="advantage">
                <img src="assets/media/about/plus_3.svg" alt="">
                <h3>Помощь флористов</h3>
                <p>Флористы помогут подобрать цветы, составить красивый букет, учитывая ваши пожелани.</p>
            </div>
        </div>
    </div>
    <!-- advantages end -->

    <!-- products start -->
    <section id="products">
        <div class="products container">
            <h2>Популярные букеты</h2>
            <div class="products_block">
                <?php foreach ($flowers as $flower): ?>
                    <div class="products_card"> <!--  -->
                        <a href="/?page=show&id=<?php echo $flower['id'] ?>">
                            <img src="<?php echo $flower['image'] ?>" alt="">
                            <div class="text_btn">
                                <div class="text_product">
                                    <h3><?php echo $flower['title']; ?></h3>
                                    <p><?php echo number_format((int) ($flower['price']), 0, '', ' '); ?> ₽</p>
                                </div>

                                <div class="btn_basket">
                                    <form action="/?page=addProduct" method="post">
                                        <input type="hidden" name="id" value="<?php echo $flower['id']; ?>">
                                        <input type="hidden" name="title" value="<?php echo $flower['title']; ?>">
                                        <input type="hidden" name="price" value="<?php echo $flower['price']; ?>">
                                        <input type="hidden" name="description"
                                            value="<?php echo $flower['description']; ?>">
                                        <input type="hidden" name="image" value="<?php echo $flower['image']; ?>">
                                        <input type="hidden" name="previous_page"
                                            value="<?php echo $_SERVER['REQUEST_URI'] . '#products'; ?>">
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

            <div class="but_catalog">
                <a href="/?page=catalog">Перейти в каталог</a>
            </div>
        </div>
    </section>
    <!-- products end -->

    <!-- advice start -->
    <section id="advice">
        <div class="advice container" id="advice">
            <h5>4 самых важных совета</h5>
            <h2>Советы флориста</h2>
            <div class="advice_block">
                <div class="advice_card">
                    <h4>01.</h4>
                    <h3>Подготовка букета <br> к вазе</h3>
                    <p>Вазу с цветами ставьте вдали от солнца, батарей и сквозняков. Освещение должно быть рассеянным, а
                        температура днем — до 20 ℃, ночью — до 15 ℃.</p>
                </div>

                <div class="advice_card">
                    <h4>02.</h4>
                    <h3>Температура и <br> освещение</h3>
                    <p>Снимите упаковку через 1.5-2 часа. Поставьте в чистую вазу с прохладной водой, удалите
                        листья с нижней части стеблей и обновите срез под струей воды.</p>
                </div>

                <div class="advice_card">
                    <h4>03.</h4>
                    <h3>Полив и <br> влажность</h3>
                    <p>Меняйте воду ежедневно, подрезая стебли. При сухом воздухе используйте увлажнитель или
                        проветривание,
                        убрав букет в другое место.</p>
                </div>

                <div class="advice_card">
                    <h4>04.</h4>
                    <h3>Подкормка для <br> цветов</h3>
                    <p>Для продления жизни букета используйте специальную подкормку “Кризал”. Народные методы
                        неэффективны и
                        могут навредить цветам. </p>
                </div>
            </div>
        </div>
    </section>
    <!-- advice end -->


    <!-- FAQ start -->
    <section id="faq">
        <div class="FAQ container" id="FAQ">
            <h2>У вас есть вопросы?</h2>
            <h2><span>У нас есть ответы.</span></h2>
            <div class="FAQ_block">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Где расположен магазин?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Наш магазин расположен по адресу г. Казань, ул. Баумана 21. Также вы можете оформить заказ
                            онлайн
                            с доставкой.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Какой режим работы у магазина?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Мы работаем с 8:00 до 21:00 ежедневно. Онлайн-заказы принимаются круглосуточно.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Как связаться с магазином?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Вы можете позвонить нам по телефону +7 (921) 327-22-23, написать в WhatsApp или заполнить
                            форму
                            обратной связи на сайте.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Можно ли вернуть или обменять букет?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Если вы обнаружили дефекты в букете, свяжитесь с нами в течение 24 часов, и мы решим вопрос.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Есть ли у вас скидки для постоянных клиентов?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Да, у нас действует программа лояльности. Подробности уточняйте у наших менеджеров.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Можно заказать цветы с открыткой?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Конечно! Укажите текст для открытки при оформлении заказа, и мы добавим её к вашему букету.
                        </p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Можно ли заказать доставку в конкретное время?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Да, мы предлагаем доставку в удобное для вас время. Укажите желаемый интервал при оформлении
                            заказа.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Можно ли забрать заказ самовывозом?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Да, вы можете забрать заказ самостоятельно. Уточните готовность заказа по телефону перед
                            визитом.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ end -->
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const faqItems = document.querySelectorAll('.faq-item');

        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            const toggle = item.querySelector('.faq-toggle');

            question.addEventListener('click', () => {
                faqItems.forEach(otherItem => {
                    if (otherItem !== item && otherItem.classList.contains('active')) {
                        otherItem.classList.remove('active');
                        otherItem.querySelector('.faq-toggle').textContent = '+';
                    }
                });

                item.classList.toggle('active');

                if (item.classList.contains('active')) {
                    toggle.textContent = '×';
                } else {
                    toggle.textContent = '+';
                }
            });
        });
    });
</script>

<style>
    .btn_basket img {
        border-radius: 0;
    }\/* Стили для faq-item */
.faq-item {
    margin-bottom: 10px; /* Отступ между элементами FAQ */
    border: 1px solid #eee; /* Пример границы */
    border-radius: 5px;
    overflow: hidden; /* Важно для скрытия текста ответа */
}

/* Стили для вопроса */
.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #f9f9f9;
    cursor: pointer;
    font-weight: bold;
}

.faq-question h3 {
    margin: 0;
    font-size: 1.1em;
}

/* Стили для переключателя (+/-) */
.faq-toggle {
    font-size: 1.5em;
    line-height: 1;
    transition: transform 0.3s ease; /* Плавное вращение при открытии/закрытии */
}

/* Стили для блока ответа */
.faq-answer {
    max-height: 0; /* КЛЮЧЕВОЙ МОМЕНТ: Изначально высота 0 */
    overflow: hidden; /* КЛЮЧЕВОЙ МОМЕНТ: Скрывает все, что выходит за пределы */
    transition: max-height 0.3s ease-out, padding 0.3s ease-out; /* Плавный переход */
    padding: 0 15px; /* Изначально отступы по вертикали тоже 0 */
}

.faq-answer p {
    margin: 15px 0; /* Отступы для текста внутри ответа */
}


/* Состояние "активен" */
.faq-item.active .faq-answer {
    max-height: 200px; /* Установите достаточную высоту для вашего контента */
    padding: 15px; /* Возвращаем отступы для видимого контента */
}

.faq-item.active .faq-toggle {
    /* Поворачиваем "плюс" в "минус" при активности */
    transform: rotate(45deg); /* Если используете "×", то можно и не вращать, или сделать просто трансформацию */
}
</style>