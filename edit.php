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

$message = '';
$flower = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $id = $_GET['id'];
    $category_id = $_POST['category_id'];
    $old_image = $_POST['old_image'];

    if (empty($title) || empty($description) || empty($price) || empty($category_id)) {
        $message = 'Пустые поля - ошибка';
    } else {
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $tmp = $_FILES['image']['tmp_name']; // временное имя файла
            $name = $_FILES['image']['name']; // оригинальное имя файла
            $ext = pathinfo($name, PATHINFO_EXTENSION); // расширение файла
            $newName = uniqid() . '.' . $ext; // новое имя файла
            $newDirection = 'assets/media/catalog/' . $newName; // путь сохранения файла

            if (move_uploaded_file($tmp, $newDirection)) {
                unlink($old_image);
                $sql = 'UPDATE flowers SET title = :title, description = :description, price = :price, category_id = :category_id, image = :image WHERE id = :id';
                $stmt = $database->prepare($sql);
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':category_id', $category_id);
                $stmt->bindParam(':image', $newDirection);
                if ($stmt->execute()) {
                    $message = 'Товар отредактирован';
                } else {
                    $message = 'Ошибка редактирования';
                }
            } else {
                $message = 'Не получилось сохранить фото';
            }
        } else {
            $sql = 'UPDATE flowers SET title = :title, description = :description, price = :price, category_id = :category_id WHERE id = :id';
            $stmt = $database->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':category_id', $category_id);
            if ($stmt->execute()) {
                $message = 'Товар отредактирован';
            } else {
                $message = 'Ошибка редактирования';
            }
        }
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (empty($id)) {
        $message = 'Не указан id товара';
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

<?php if (!empty($flower)) { ?>

    <div class="edit-block container">
        <a href="/?page=adminPanel">
            <button class="btn_in_admin">
                Вернуться назад
            </button>
        </a>
        <h1>Редактировать</h1>

        <div class="edit-form">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="">Название</label><br>
                <input type="text" name="title" placeholder="title" value="<?php echo $flower['title'] ?>"><br>
                <label for="">Описание</label><br>
                <textarea name="description" placeholder="description"><?php echo $flower['description'] ?></textarea><br>
                <label for="">Стоимость</label><br>
                <input type="number" name="price" placeholder="price" value="<?php echo $flower['price'] ?>"> <br>
                <label for="">Категория</label><br>
                <div>
                    <?php foreach ($categories as $category): ?>
                        <div class="input_category">
                            <input type="radio" name="category_id" value="<?php echo $category['id'] ?>" <?php
                               if ($category['id'] === $flower['category_id']) {
                                   echo 'checked';
                               }
                               ?>>
                            <label for=""><?php echo $category['name'] ?></label><br>
                        </div>
                    <?php endforeach; ?>
                </div>
                <br>
                <label for="">Изображение</label><br>
                <img src="<?php echo $flower['image'] ?>" alt=""><br>
                <div class="file-input">
                    <input type="file" name="image"> <br>
                    <input type="hidden" name="old_image" value="<?php echo $flower['image'] ?>">
                </div>
                <div class="create-btn">
                    <input type="submit" value="Редактировать">
                </div>
            </form>
        </div>
    <?php } ?>
</div>

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

    @media screen and (min-width: 1400px) {
        h1 {
            font-size: 36px;
            font-weight: 500;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .edit-form label {
            font-size: 20px;
        }

        .edit-form input {
            background-color: #fff;
            margin-top: 10px;
            margin-bottom: 20px;
            height: 40px;
            width: 453px;
            border-radius: 12px;
            padding: 0 10px;
            border: 1px solid #000000;
            font-size: 16px;
        }

        .edit-form textarea {
            margin-top: 10px;
            width: 453px;
            height: 200px;
            border-radius: 12px;
            border: 1px solid #2E2E2E;
            background-color: #fff;
            padding: 10px;
            font-size: 16px;
            color: #2E2E2E;
            margin-bottom: 20px;
        }

        .create-btn input {
            width: 300px;
        }

        .file-input input {
            border: none;
        }

        .edit-form img {
            height: 300px;
            margin-top: 10px;
        }

        .create-btn input {
            background-color: rgb(147, 147, 147);
            border: 1px solid rgb(147, 147, 147);
            padding: 10px 0;
        }

        .input_category {
            display: flex;
            align-items: center;
        }

        .input_category input {
            height: 20px;
            width: 50px;
        }

        .input_category label {
            font-size: 16px;
            margin-bottom: 10px;
            margin-left: -5px;
        }
    }

    @media screen and (min-width: 1000px) {
        h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .edit-form label {
            font-size: 18px;
        }

        .edit-form input {
            background-color: #fff;
            margin-top: 10px;
            margin-bottom: 20px;
            height: 40px;
            width: 400px;
            border-radius: 12px;
            padding: 0 10px;
            border: 1px solid #000000;
            font-size: 14px;
        }

        .edit-form textarea {
            margin-top: 10px;
            width: 400px;
            height: 200px;
            border-radius: 12px;
            border: 1px solid #2E2E2E;
            background-color: #fff;
            padding: 10px;
            font-size: 14px;
            color: #2E2E2E;
            margin-bottom: 20px;
        }

        .create-btn input {
            width: 300px;
        }

        .file-input input {
            border: none;
        }

        .edit-form img {
            height: 300px;
            margin-top: 10px;
        }

        .create-btn input {
            background-color: rgb(147, 147, 147);
            border: 1px solid rgb(147, 147, 147);
            padding: 10px 0;
        }

        .input_category {
            display: flex;
            align-items: center;
        }

        .input_category input {
            height: 20px;
            width: 50px;
        }

        .input_category label {
            font-size: 16px;
            margin-bottom: 10px;
            margin-left: -5px;
        }
    }

    @media screen and (max-width: 392px) {
        .edit-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 30px;
            margin-top: 30px;
        }

        .edit-form label {
            font-size: 16px;
        }

        .edit-form input {
            background-color: #fff;
            margin-top: 10px;
            margin-bottom: 20px;
            height: 40px;
            width: 350px;
            border-radius: 12px;
            padding: 0 10px;
            border: 1px solid #000000;
            font-size: 12px;
        }

        .edit-form textarea {
            margin-top: 10px;
            width: 350px;
            height: 120px;
            border-radius: 12px;
            border: 1px solid #2E2E2E;
            background-color: #fff;
            padding: 10px;
            font-size: 12px;
            color: #2E2E2E;
            margin-bottom: 20px;
        }

        .create-btn input {
            width: 300px;
        }

        .file-input input {
            border: none;
        }

        .edit-form img {
            height: 300px;
            margin-top: 10px;
        }

        .create-btn input {
            background-color: rgb(147, 147, 147);
            border: 1px solid rgb(147, 147, 147);
            padding: 10px 0;
        }

        .input_category {
            display: flex;
            align-items: center;
        }

        .input_category input {
            height: 20px;
            width: 50px;
        }

        .input_category label {
            font-size: 14px;
            margin-bottom: 10px;
            margin-left: -5px;
        }
    }
</style>