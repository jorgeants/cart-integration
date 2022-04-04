<?php
  // echo '<pre>';
  // print_r($_POST);
  // file_put_contents('php://stderr', print_r($_POST, TRUE))

  require "./configuration.php";

  \PagSeguro\Library::initialize();
  \PagSeguro\Library::cmsVersion()->setName("CartGateway")->setRelease("1.0.0");
  \PagSeguro\Library::moduleVersion()->setName("Payment")->setRelease("1.0.0");

  try {
    file_put_contents('php://stderr', print_r($_POST, TRUE));
    if (\PagSeguro\Helpers\Xhr::hasPost()) {
        $response = \PagSeguro\Services\PreApproval\Notification::check(
            \PagSeguro\Configuration\Configure::getAccountCredentials()
        );
    } else {
        throw new \InvalidArgumentException($_POST);
    }

    echo "<pre>";
    file_put_contents('php://stderr', print_r($response, TRUE));
} catch (Exception $e) {
    die($e->getMessage());
}
?>