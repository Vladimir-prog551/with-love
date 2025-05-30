<?php

if (!isset($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

session_destroy();
$_SESSION = array();
header('Location: /?page=homepage');
exit();

?>