<?php
// paymongo.php - Create a Payment Intent
// Use your PayMongo SECRET key
$secret_key = "sk_test_yourkeyhere";

// Price of product (in centavos)
$amount = intval($_POST['amount']) * 100; // PHP to centavos

$data = [
    "data" => [
        "attributes" => [
            "amount" => $amount,
            "payment_method_allowed" => ["gcash", "paymaya", "card", "grab_pay"],
            "currency" => "PHP",
        ]
    ]
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.paymongo.com/v1/payment_intents");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_USERPWD, $secret_key . ":");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
