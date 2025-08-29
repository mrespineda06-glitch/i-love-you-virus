<?php
// index.php (Frontend UI)

$pdo = new PDO("mysql:host=localhost;dbname=vending", "root", "");

// Get product list
$stmt = $pdo->query("SELECT id, name, price, stock FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
  <title>Smart Vending Machine</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 20px;
    }

    h1 {
      text-align: center;
    }

    .product {
      background: #fff;
      padding: 15px;
      margin: 10px auto;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    button {
      padding: 10px 15px;
      margin: 5px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .online {
      background: #28a745;
      color: white;
    }

    .coin {
      background: #007bff;
      color: white;
    }
  </style>
</head>

<body>

  <h1>Smart Vending Machine</h1>

  <?php foreach ($products as $p): ?>
    <div class="product">
      <h3><?= htmlspecialchars($p['name']) ?></h3>
      <p>Price: ₱<?= $p['price'] ?></p>
      <p>Stock: <?= $p['stock'] ?></p>
      <button class="online" onclick="payOnline(<?= $p['id'] ?>, <?= $p['price'] ?>)">Pay Online</button>

      <button class="coin" onclick="payCoin(<?= $p['id'] ?>)">Insert Coin</button>
    </div>
  <?php endforeach; ?>

  <script>
    // Simulate online payment
    function payOnline(productId, amount) {
      fetch("paymongo.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded"
          },
          body: "amount=" + amount
        })
        .then(res => res.json())
        .then(data => {
          let client_key = data.data.attributes.client_key;
          let redirect_url = "https://checkout.paymongo.com/" + client_key;
          window.location.href = redirect_url;
        });
    }


    // Coin mode: Wait for coin slot credits
    function payCoin(productId) {
      alert("Please insert coins into the machine...");
      // Backend will track coin insertion (ESP32 updates credits)
      // Once enough credits are received, backend sets DISPENSE
      checkCoinStatus(productId);
    }

    function checkCoinStatus(productId) {
      fetch("vending.php?mode=checkCoin&product=" + productId)
        .then(res => res.text())
        .then(data => {
          if (data === "READY") {
            alert("Enough coins inserted! Dispensing...");
          } else {
            setTimeout(() => checkCoinStatus(productId), 2000);
          }
        });
    }
  </script>

</body>

</html>