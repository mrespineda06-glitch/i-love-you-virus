<?php
$pdo = new PDO("mysql:host=localhost;dbname=vending", "root", "");

$mode = $_GET['mode'] ?? '';
$product = intval($_GET['product'] ?? 1);

if ($mode == 'coin') {
    $credits = intval($_GET['credits'] ?? 0);
    $pdo->query("UPDATE products SET credits = credits + $credits WHERE id = $product");
    echo "OK";
}

if ($mode == 'checkCoin') {
    $stmt = $pdo->query("SELECT price, credits FROM products WHERE id = $product");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['credits'] >= $row['price']) {
        $pdo->query("UPDATE products SET credits = 0 WHERE id = $product");
        $pdo->query("UPDATE vending_state SET command = 'DISPENSE' WHERE product_id = $product");
        echo "READY";
    } else {
        echo "WAIT";
    }
}

if ($mode == 'check') {
    $stmt = $pdo->query("SELECT command FROM vending_state WHERE product_id = $product");
    $command = $stmt->fetchColumn();
    if ($command == "DISPENSE") {
        $pdo->query("UPDATE vending_state SET command = '' WHERE product_id = $product");
        echo "DISPENSE";
    } else {
        echo "WAIT";
    }
}

if ($mode == 'dispense') {
    $pdo->query("UPDATE vending_state SET command = 'DISPENSE' WHERE product_id = $product");
    echo "DISPENSE SET";
}
