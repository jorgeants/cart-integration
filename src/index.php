<?php
  require 'vendor/autoload.php';
 
  $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
  $dotenv->load();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart Integration</title>
</head>
<body>
  <?php
    echo getenv('PAGSEGURO_ENV')."\n";
    echo getenv('PAGSEGURO_EMAIL')."\n";
    echo getenv('9EA66B76C5F346E1B432D4F47B07B656')."\n";
  ?>
  <button>Pagar</button>
</body>
</html>