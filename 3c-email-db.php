<?php
// SEND EMAIL
$to = "YOUR@EMAIL.COM"; // ! SET YOUR EMAIL !
$subject = "Order Received";
$message = "<html><body>";
$message .= "<p>An order has been receieved on " . date("j M Y H:i:s") . "</p>";
$message .= "<table>";
foreach ($_POST as $k=>$v) {
  $message .= "<tr><td><strong>$k:</strong></td><td>$v</td></tr>";
}
$message .= "</table></body></html>";
$headers = [
  'MIME-Version: 1.0',
  'Content-type: text/html; charset=utf-8',
  'From: sys@your-site.com' // ! CHANGE THIS AS WELL !
];
$headers = implode("\r\n", $headers);
$pass = @mail($to, $subject, $message, $headers);

// SAVE TO DATABASE
// Connect to database
if ($pass) {
  require "3b-config.php";
  try {
    $pdo = new PDO(
      "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET,
      DB_USER, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
      ]
    );
  }
  catch (Exception $ex) {
    print_r($ex);
    $pass = false;
  }
}

// Save entry
if ($pass) {
  try {
    $sql = "INSERT INTO `form` (`name`, `email`, `tel`, `qty`, `notes`) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      $_POST['name'], $_POST['email'], $_POST['tel'],
      $_POST['qty'], $_POST['notes']
    ]);
  } catch (Exception $ex) {
    $pass = false;
  }
}

// SHOW RESULT ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Order Form Example</title>
    <link href="theme.css" rel="stylesheet">
  </head>
  <body>
    <h1><?=$pass ? "ORDER RECEIEVED" : "OPPS! AN ERROR HAS OCCURRED!"?></h1>
    <p>
      <?=$pass ? "We have receieved your own and will get back to you ASAP." : "Please refresh the page and try again."?>
    </p>
  </body>
</html>