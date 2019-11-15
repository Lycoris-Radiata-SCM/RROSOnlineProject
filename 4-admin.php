<?php
// Connect to database
include('includes/config.php');

// Get orders
$sql = "SELECT * FROM `form` FROM BY `dop` DESC LIMIT 30";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$form = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Order Form Admin Example</title>
    <link href="theme.css" rel="stylesheet">
  </head>
  <body>
    <h1>Form</h1>
    <table>
    <?php
    if (count($form)>0) {
      foreach ($form as $o) {
        echo "<tr><td>";
        foreach ($o as $k=>$v) {
          echo "<strong>$k</strong> - $v <br>";
        }
        echo "</td></tr>";
      }
    } else {
      echo "<tr><td>No request found.</td></tr>";
    }
    ?>
    </table>
  </body>
</html>