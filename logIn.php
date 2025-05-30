<?php

if (!empty($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$message = '';
$flag = true;
$email = '';
$password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email)) {
        $message = 'Пустое поле адреса электронной почты';
        $flag = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Некорректный адрес электронной почты';
        $flag = false;
    }

    if (empty($password)) {
        $message = 'Пароль не введён';
        $flag = false;
    }

    if ($flag) {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['cart'] = [];
                header('Location: /?page=homepage');
                exit();
            } else {
                $message = 'Неверный пароль';
            }
        } else {
            $message = 'Указанного пользователя не существует';
        }
    }
}

?>

<!-- log_in start -->
<div class="log_in container">
    <div class="auth-card">
        <h2>Вход в аккаунт</h2>

        <form action="" method="post">
            <div class="input-group">
                <input type="text" id="email" name="email" value="<?php echo $email ?>" required>
                <label for="email">Логин</label>
                <div class="underline"></div>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" value="<?php echo $password ?>" required>
                <label for="password">Пароль</label>
                <div class="underline"></div>
            </div>

            <button class="login-btn">
                <span>Войти</span>
            </button>
        </form>

        <div class="divider">
            <span>или</span>
        </div>

        <a href="/?page=signIn" class="signup-link">
            Создать новый аккаунт
        </a>
    </div>
</div>
<!-- log_in end -->

<?php
if (!empty($message)) {
    ?>
    <p><?php echo $message; ?> </p>
    <?php
}
?>