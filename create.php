<?php

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Админ') {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$sql = 'SELECT * FROM categories';
$stmt = $database->prepare($sql);
$stmt->execute();
$categories = $stmt->fetchAll();


$title = '';
$description = '';
$price = '';
$category_id = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category_id = $_POST['category_id'];

    if (empty($title) || empty($description) || empty($price) || empty($category_id) || empty($_FILES['image']) || $_FILES['image']['error'] !== 0) {
        $message = 'Ошибка в введённых данных';
    } else {
        $tmp = $_FILES['image']['tmp_name']; // временное имя файла
        $name = $_FILES['image']['name']; // оригинальное имя файла
        $ext = pathinfo($name, PATHINFO_EXTENSION); // расширение файла
        $newName = uniqid() . '.' . $ext; // новое имя файла
        $newDirection = 'assets/media/catalog/' . $newName; // путь сохранения файла

        if (move_uploaded_file($tmp, $newDirection)) {
            $sql = 'INSERT INTO flowers(title, description, price, category_id, image) VALUES (:title, :description, :price, :category_id, :image)';
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':image', $newDirection);

            if ($stmt->execute()) {
                $message = 'Товар успешно создан!';
            } else {
                $message = 'Ошибка при создании товара';
            }
        } else {
            $message = 'Не получилось сохранить фото';
        }
    }
}

?>

<div class="panel container">
    <div class="left_menu">
        <a href="/?page=adminPanel&action=products">Список товаров</a>
        <a href="/?page=adminPanel&action=orders">Список заказов</a>
        <a href="/?page=adminPanel&action=users">Список пользователей</a>
        <a href="/?page=create">Добавление товара</a>
    </div>
    <div class="right_content">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="input-group">
                <input type="text" id="title" name="title" placeholder="Название товара"
                    value="<?php echo htmlspecialchars($title); ?>">
                <label for="title"></label>
            </div>
            <div class="input-group">
                <textarea name="description" placeholder="Описание товара"
                    id="description"><?php echo htmlspecialchars($description); ?></textarea>
                <label for="description"></label>
            </div>
            <div class="input-group">
                <input type="number" id="price" name="price" placeholder="Цена товара"
                    value="<?php echo htmlspecialchars($price); ?>">
                <label for="price"></label>
            </div>
            <label for="">Категория</label>
            <div>
                <?php foreach ($categories as $category): ?>
                    <input 
                        type="radio" 
                        name="category_id" 
                        value="<?php echo $category['id']; ?>" 
                        <?php if ($category_id == $category['id']) {
                            echo 'checked';
                        } ?>
                    >
                    <label for=""><?php echo $category['name'] ?></label>
                <?php endforeach; ?>
            </div>
            <br><label for="">Изображение</label>
            <div class="input-group">
                <input id="image" type="file" name="image">
            </div>
            <button>Создать</button>
        </form>

        <?php if (!empty($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>

    </div>
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

    .input-group textarea {
        width: 100%;
        padding: 15px 0 5px 0;
        font-size: 16px;
        border: none;
        border-bottom: 1px solid #ddd;
        outline: none;
        background: transparent;
        transition: all 0.3s ease;
    }

    .input-group textarea:focus {
        border-bottom-color: transparent;
    }

    .input-group textarea:focus+label,
    .input-group textarea:valid+label {
        transform: translateY(-20px);
        font-size: 12px;
        color: #F6D0DD;
    }

    button {
        height: 40px;
        padding: 0 20px;
        border-radius: 12px;
        background: #F6D0DD;
        border: 1px solid #F6D0DD;
        cursor: pointer;
        transition: all 0.3s;
    }

    button:hover {
        background: #e8b8ca;
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

        .btns_action img {
            height: 25px;
        }
    }
</style>