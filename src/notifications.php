<?php
  echo '<pre>';
  print_r($_POST);
  file_put_contents('php://stderr', print_r($_POST, TRUE))
?>