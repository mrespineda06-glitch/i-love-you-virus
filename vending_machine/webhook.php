<?php
// webhook.php - PayMongo webhook listener

$input = file_get_contents("php://input");
$event = json_decode($input, true);

// Log for debugging
file_put_contents("paymongo_log.txt", $input . "\n", FILE_APPEND);

if ($event['data']['attributes']['status'] === "succeeded") {
    $amount = $event['data']['attributes']['amount'] / 100;
    $payment_id = $event['data']['id'];

    // Example: Mark payment success and trigger vending
    $pdo = new PDO("mysql:host=localhost;dbname=vending", "root", "");
    $pdo->query("UPDATE vending_state SET command = 'DISPENSE' WHERE product_id = 1");

    http_response_code(200);
    echo "OK";
}
