<?php

$host='localhost';
$dbname='lycoris';
$username='root';
$password='';

try{
    $database=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username, $password);
}catch(PDOException $error){
    die('Ошибка подключения к бд: ' . $error->getMessage());
}

?>