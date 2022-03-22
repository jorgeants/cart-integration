<?php
  require '../vendor/autoload.php';
 
  $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__, '../.env');
  $dotenv->load();

  \PagSeguro\Configuration\Configure::setEnvironment($_ENV['PAGSEGURO_ENV']);
  \PagSeguro\Configuration\Configure::setCharset($_ENV['PAGSEGURO_CHARSET']);
  \PagSeguro\Configuration\Configure::setLog($_ENV['PAGSEGURO_LOG_ACTIVE'], $_ENV['PAGSEGURO_LOG_LOCATION']);

  if ($_ENV['PAGSEGURO_ENV'] === 'sandbox') {
    \PagSeguro\Configuration\Configure::setAccountCredentials(
      $_ENV['PAGSEGURO_EMAIL'],
      $_ENV['PAGSEGURO_TOKEN_SANDBOX']
    );
    \PagSeguro\Configuration\Configure::setApplicationCredentials(
      $_ENV['PAGSEGURO_APP_ID_SANDBOX'],
      $_ENV['PAGSEGURO_APP_KEY_SANDBOX']
    );
  } else {
    \PagSeguro\Configuration\Configure::setAccountCredentials(
      $_ENV['PAGSEGURO_EMAIL'],
      $_ENV['PAGSEGURO_TOKEN_PRODUCTION']
    );
    \PagSeguro\Configuration\Configure::setApplicationCredentials(
      $_ENV['PAGSEGURO_APP_ID_PRODUCTION'],
      $_ENV['PAGSEGURO_APP_KEY_PRODUCTION']
    );
  }
?>