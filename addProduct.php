<?php

if (!isset($_SESSION['role'])) {
    header('Location: /?page=404');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $previous_page = $_POST['previous_page'];

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += 1;
    } else {
        $_SESSION['cart'][$product_id] = [
            'name' => $title,
            'price' => $price,
            'description' => $description,
            'image' => $image,
            'quantity' => 1
        ];
    }

    header('Location: ' . $previous_page);
    exit();

} else {
    header('Location: /?page=404');
    exit();
}

?>