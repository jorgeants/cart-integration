<?php
  require "./configuration.php";

  \PagSeguro\Library::initialize();
  \PagSeguro\Library::cmsVersion()->setName("CartGateway")->setRelease("1.0.0");
  \PagSeguro\Library::moduleVersion()->setName("Payment")->setRelease("1.0.0");

  echo '<pre>';
  print_r($_POST);

  // Main buyer data
  $buyerPhoneAreaCode = $_POST['areaCode'];
  $buyerPhone = $_POST['phone'];
  $buyerAddressStreet = $_POST['street'];
  $buyerAddressNumber = $_POST['number'];
  $buyerAddressComplement = $_POST['complement'];
  $buyerAddressDistrict = $_POST['district'];
  $buyerAddressCity = $_POST['city'];
  $buyerAddressState = $_POST['state'];
  $buyerAddressPostalCode = $_POST['postalCode'];
  
  $creditCard = new \PagSeguro\Domains\Requests\DirectPayment\CreditCard();

  $creditCard->setReceiverEmail($_ENV['PAGSEGURO_EMAIL']);
  $creditCard->setReference("PLANO_PARCELADO_1015_1");
  $creditCard->setCurrency("BRL");

  $creditCard->addItems()->withParameters(
    '0001',
    'Acesso ao sistema',
    1,
    1300.60
  );

  $creditCard->setSender()->setName($_POST['name']);
  $creditCard->setSender()->setEmail($_POST['email']);
  $creditCard->setSender()->setPhone()->withParameters(
    $buyerPhoneAreaCode,
    $buyerPhone
  );
  $creditCard->setSender()->setDocument()->withParameters(
    'CPF',
    $_POST['cpf']
  );

  // This is important (get with JS library)
  $creditCard->setSender()->setHash($_POST['senderHash']);

  $creditCard->setShipping()->setAddress()->withParameters(
    $buyerAddressStreet,
    $buyerAddressNumber,
    $buyerAddressDistrict,
    $buyerAddressPostalCode,
    $buyerAddressCity,
    $buyerAddressState,
    'BRA',
    $buyerAddressComplement,
  );

  // Set billing information for credit card
  $creditCard->setBilling()->setAddress()->withParameters(
    $buyerAddressStreet,
    $buyerAddressNumber,
    $buyerAddressDistrict,
    $buyerAddressPostalCode,
    $buyerAddressCity,
    $buyerAddressState,
    'BRA',
    $buyerAddressComplement,
  );

  // This is important (get with JS library)
  $creditCard->setToken($_POST['creditCardToken']);

  // Set the installment quantity and value (could be obtained using the Installments
  // service, that have an example here in \public\getInstallments.php)
  $creditCard->setInstallment()->withParameters($_POST['installmentCombo'], $_POST['installmentAmount']);

  $creditCard->setHolder()->setName($_POST['cardName']);
  $creditCard->setHolder()->setBirthdate($_POST['birthDate']);

  $creditCard->setHolder()->setPhone()->withParameters(
    $buyerPhoneAreaCode,
    $buyerPhone
  );

  $creditCard->setHolder()->setDocument()->withParameters(
    'CPF',
    $_POST['cpf']
  );

  $creditCard->setMode('DEFAULT');

  try {
      $result = $creditCard->register(
        \PagSeguro\Configuration\Configure::getAccountCredentials()
      );
      echo "<pre>";
      print_r($result);
  } catch (Exception $e) {
      echo "</br> <strong>";
      die($e->getMessage());
  }
?>