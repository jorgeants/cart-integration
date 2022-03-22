<?php
  require "./configuration.php";
    
  \PagSeguro\Library::initialize();
  \PagSeguro\Library::cmsVersion()->setName("CartGateway")->setRelease("1.0.0");
  \PagSeguro\Library::moduleVersion()->setName("Payment")->setRelease("1.0.0");

  $plan = new \PagSeguro\Domains\Requests\DirectPreApproval\Plan();
  $plan->setRedirectURL('http://google.com.br');
  $plan->setReference('PLANO_RECORRENTE_1015_2');
  $plan->setPreApproval()->setName('Assinatura mensal do sistema');
  $plan->setPreApproval()->setCharge('AUTO');
  $plan->setPreApproval()->setPeriod('MONTHLY');
  $plan->setPreApproval()->setAmountPerPayment('100.00');
  $plan->setPreApproval()->setExpiration()->withParameters(
    1,
    'YEARS'
  );
  $plan->setPreApproval()->setCancelURL("http://google.com.br");
  $plan->setReviewURL('http://google.com.br');

  try {
      $response = $plan->register(
          new \PagSeguro\Domains\AccountCredentials(
            $_ENV['PAGSEGURO_EMAIL'],
            $_ENV['PAGSEGURO_TOKEN_SANDBOX']
          )
      );

      echo '<pre>';
      print_r($response);
  } catch (Exception $e) {
      die($e->getMessage());
  }
?>