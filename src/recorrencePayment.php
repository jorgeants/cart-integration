<?php
  require "./configuration.php";

  \PagSeguro\Library::initialize();
  \PagSeguro\Library::cmsVersion()->setName("CartGateway")->setRelease("1.0.0");
  \PagSeguro\Library::moduleVersion()->setName("Payment")->setRelease("1.0.0");

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
  
  $preApproval = new \PagSeguro\Domains\Requests\DirectPreApproval\Accession();
  $preApproval->setPlan('8E2D2814131381E9948EEF9211877A06');
  $preApproval->setReference('PLANO_RECORRENTE_1015_2');

  $preApproval->setSender()->setName($_POST['name']);
  $preApproval->setSender()->setEmail($_POST['email']);
  $preApproval->setSender()->setHash($_POST['senderHash']);
  $preApproval->setSender()->setPhone()->withParameters(
    $buyerPhoneAreaCode,
    $buyerPhone
  );
  $preApproval->setSender()->setAddress()->withParameters(
    $buyerAddressStreet,
    $buyerAddressNumber,
    $buyerAddressDistrict,
    $buyerAddressPostalCode,
    $buyerAddressCity,
    $buyerAddressState,
    'BRA',
    $buyerAddressComplement,
  );
  $document = new \PagSeguro\Domains\DirectPreApproval\Document();
  $document->withParameters('CPF', $_POST['cpf']);
  $preApproval->setSender()->setDocuments($document);

  // Credit Card Object - Payment
  $preApproval->setPaymentMethod()->setCreditCard()->setToken($_POST['creditCardToken']);

  // Main buyer documents data: Dados do títular do cartão
  $preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setName($_POST['cardName']);
  $preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setBirthDate($_POST['birthDate']);
  $document = new \PagSeguro\Domains\DirectPreApproval\Document();
  $document->withParameters('CPF', $_POST['cpf']);
  $preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setDocuments($document);
  $preApproval->setPaymentMethod()->setCreditCard()->setHolder()->setPhone()->withParameters(
    $buyerPhoneAreaCode,
    $buyerPhone
  );
  $preApproval->setPaymentMethod()->setCreditCard()->setHolder()
    ->setBillingAddress()
    ->withParameters(
      $buyerAddressStreet,
      $buyerAddressNumber,
      $buyerAddressDistrict,
      $buyerAddressPostalCode,
      $buyerAddressCity,
      $buyerAddressState,
      'BRA',
      $buyerAddressComplement,
    );
  
  try {
    $response = $preApproval->register(
      new \PagSeguro\Domains\AccountCredentials(
        $_ENV['PAGSEGURO_EMAIL'],
        $_ENV['PAGSEGURO_TOKEN_SANDBOX']
      )
    );
  } catch (Exception $e) {
    die($e->getMessage());
  }
?>