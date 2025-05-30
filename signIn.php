<?php

if (isset($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

include('database/connection.php');

$message = '';
$flag = true;
$username = '';
$email = '';
$password = '';
$re_password = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];

    if (empty($username)) {
        $message = 'Поле с именем пустое!';
        $flag = false;
    }

    if (empty($email)) {
        $message = 'Поле с электронной почтой пустое!';
        $flag = false;
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Некорректный адрес электронной почты';
        $flag = false;
    } else {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $email_check = $stmt->fetch();
        if ($email_check) {
            $message = 'Пользователь с такой электронной почтой уже существует';
            $flag = false;
        }
    }

    if (empty($password) || empty($re_password)) {
        $message = 'Пароль пустой';
        $flag = false;
    } elseif (strlen($password) < 6) {
        $message = 'Короткий пароль';
        $flag = false;
    } elseif ($password !== $re_password) {
        $message = 'Пароли не совпадают';
        $flag = false;
    }

    if ($flag) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $role = 'Пользователь';
        $sql = 'INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)';
        $stmt = $database->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hash);
        $stmt->bindParam(':role', $role);
        if ($stmt->execute()) {
            $message = 'Регистрация прошла успешно';
        } else {
            $message = 'Ошибка регистрации';
        }
    }
}

?>

<!-- sign_in start -->
<div class="log_in container">
    <div class="auth-card">
        <h2>Регистрация</h2>

        <form action="" method="post">
            <div class="input-group">
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                <label for="username">Имя пользователя</label>
                <div class="underline"></div>
            </div>
            
            <div class="input-group">
                <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <label for="email">Электронная почта</label>
                <div class="underline"></div>
            </div>

            <div class="input-group">
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                <label for="password">Пароль</label>
                <div class="underline"></div>
            </div>

            <div class="input-group">
                <input type="password" id="re_password" name="re_password" value="<?php echo htmlspecialchars($re_password); ?>" required>
                <label for="re_password">Повторите пароль</label>
                <div class="underline"></div>
            </div>

            <button class="login-btn">
                <span>Зарегистрироваться</span>
            </button>

        </form>

        <div class="divider">
            <span>или</span>
        </div>

        <a href="/?page=logIn" class="signup-link">
            Войти в аккаунт
        </a>

    </div>

</div>
<!-- sign_in end -->

<?php if (!empty($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>