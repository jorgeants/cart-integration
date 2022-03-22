<?php
  require "./configuration.php";

  $url = "https://ws.sandbox.pagseguro.uol.com.br/v2/sessions";

	$credenciais = array(
	  "email" => $_ENV["PAGSEGURO_EMAIL"],
		"token" => $_ENV["PAGSEGURO_TOKEN_SANDBOX"]
	);
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($credenciais));
	$resultado = curl_exec($curl);
	curl_close($curl);
	$session = simplexml_load_string($resultado)->id;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cart Integration</title>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

  <style>
    form {
      display: flex;
      flex-direction: column;
      width: 40%;
    }
    form input {
      margin-bottom: 1rem;
    }
    .cart-container {
      padding: 30px;
    }
  </style>
</head>
<body>
  <div class="cart-container">
    <h1>Pagamento</h1>
    <form action="recorrencePayment.php" method="post">
      <h4>Informações pessoais</h4>
      <label for="name">Nome completo</label>
      <input type="text" id="name" name="name" />

      <label for="cpf">CPF</label>
      <input type="text" name="cpf" />

      <label for="birthDate">Data de Nascimento</label>
      <input type="text" name="birthDate" />

      <label for="email">E-mail</label>
      <input type="text" name="email" />

      <label for="areaCode">DDD</label>
      <input type="text" name="areaCode" />

      <label for="phone">Telefone</label>
      <input type="text" name="phone" />

      <h4>Endereço</h4>
      <label for="postalCode">CEP</label>
      <input type="text" name="postalCode" />

      <label for="street">Endereço</label>
      <input type="text" name="street" />

      <label for="number">Número</label>
      <input type="text" name="number" />

      <label for="complement">Complemento</label>
      <input type="text" name="complement" />

      <label for="district">Bairro</label>
      <input type="text" name="district" />

      <label for="city">Cidade</label>
      <input type="text" name="city" />

      <label for="state">Estado</label>
      <input type="text" name="state" />

      <input type="hidden" id="senderHash" class="creditcard" name="senderHash">

      <fieldset>
        <legend class="text-center">Cartão de Crédito</legend>
        <div class="row mx-md-n5">
          <div class="col px-md-5">
            <div class="p-3 border bg-light">
              <!-- <div class="row"> -->
                <!-- <div class="col-sm-4"> -->
                  <!-- <input type="hidden" id="creditCardToken" class="form-control" name="creditCardToken" disabled> -->
                  <div>
                    <label for="creditCardNumber">Número do cartão:</label> 
                    <input type="text" class="form-control" id="creditCardNumber" class="creditcard" name="creditCardNumber">
                  </div>
                  <div>
                    <label for="creditCardBrand">Bandeira:</label>
                    <input type="text" class="form-control" id="creditCardBrand" class="creditcard" name="creditCardBrand" disabled>
                  </div>
                  <div>
                    <label for="cardName">Nome como está no cartão</label> 
                    <input type="text" class="form-control" id="cardName" class="creditcard" name="creditCardNumber">
                  </div>
                <!-- </div> -->
                <!-- <div class="col-sm-4"> -->
                  <label for="creditCardExpMonth">Validade Mês (mm):</label>
                  <input type="text" class="form-control" id="creditCardExpMonth" class="creditcard" name="creditCardExpMonth" size="2">

                  <label for="creditCardExpYear">Ano (yyyy):</label>
                  <input type="text" class="form-control" id="creditCardExpYear" class="creditcard" name="creditCardExpYear" size="4">
                <!-- </div> -->
                <!-- <div class="col-sm-4"> -->
                  <label for="creditCardCvv">CVV:</label>
                  <input type="text" class="form-control" id="creditCardCvv" class="creditcard" name="creditCardCvv">

                  <label> Token:</label>
                  <input type="text" id="creditCardToken" class="form-control" name="creditCardToken" disabled>
                  <button type="button" class="btn btn-info" id="generateCreditCardToken">Gerar Token</button>
                <!-- </div> -->
              <!-- </div> -->
            </div>
          </div>
        </div>
      </fieldset>

      <br>

      <fieldset>
        <legend class="text-center">Parcelamento</legend>
        <div class="row mx-md-n5">
          <div class="col px-md-5">
            <div class="p-3 border bg-light">
              Valor do Checkout:
              <input class="form-control" type="text" id="checkoutValue" name="checkoutValue">
              <button class="btn btn-info" id="installmentCheck">Ver Parcelamento</button>
            </div>
            <p>
              <select id="InstallmentCombo"></select>
            </p>
          </div>
        </div>
      </fieldset>

      <button type="submit">Pagar</button>
    </form>
  </div>
</body>

<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	// Session ID
	PagSeguroDirectPayment.setSessionId('<?php echo $session; ?>');
	console.log('<?php echo $session; ?>');
  
  $(document).ready(function() {
    $("#name").focus(() => {
      PagSeguroDirectPayment.onSenderHashReady(function(response) {
        if(response.status == 'error') {
          console.log(response.message);
          return false;
        }
        console.log(response?.senderHash);
        $("#senderHash").val(response?.senderHash);	
      });
    });

    // Get CreditCard Brand and check if is Internationl
    $("#creditCardNumber").keyup(function(){
      if ($("#creditCardNumber").val().length >= 6) {
        PagSeguroDirectPayment.getBrand({
          cardBin: $("#creditCardNumber").val().substring(0,6),
          success: function(response) { 
              console.log(response);
              $("#creditCardBrand").val(response['brand']['name']);
              $("#creditCardCvv").attr('size', response['brand']['cvvSize']);
          },
          error: function(response) {
            console.log(response);
          }
        })
      };
    })

    function printError(error){
      $.each(error['errors'], (function(key, value){
        console.log("Foi retornado o código " + key + " com a mensagem: " + value);
      }));
    }

    function getPaymentMethods(valor){
      PagSeguroDirectPayment.getPaymentMethods({
        amount: valor,
        success: function(response) {
          // console.log(JSON.stringify(response));
          console.log(response);
        },
        error: function(response) {
          console.log(JSON.stringify(response));
        }
      })
    }	

    // Generates the creditCardToken
    $("#generateCreditCardToken").click(function(){
      var param = {
        cardNumber: $("#creditCardNumber").val(),
        cvv: $("#creditCardCvv").val(),
        expirationMonth: $("#creditCardExpMonth").val(),
        expirationYear: $("#creditCardExpYear").val(),
        success: function(response) {
          console.log(response);
          // $("#creditCardToken").val(response['card']['token']);
          $("#creditCardToken").val(response?.card?.token);
        },
        error: function(response) {
          console.log(response);
          printError(response);
        }
      }
        // parâmetro opcional para qualquer chamada
        if($("#creditCardBrand").val() != '') {
          param.brand = $("#creditCardBrand").val();
        }
        PagSeguroDirectPayment.createCardToken(param);
    })

    // Check installment
    $("#installmentCheck").click(function(){
      if($("#creditCardBrand").val() != '') {
        getInstallments();
      } else {
        alert("Uma bandeira deve estar selecionada");
      }
    })

    function getInstallments(){
      var brand = $("#creditCardBrand").val();
      PagSeguroDirectPayment.getInstallments({
        amount: $("#checkoutValue").val().replace(",", "."),
        brand: brand,
        maxInstallmentNoInterest: 12,
        success: function(response) {
          var installments = response['installments'][brand];
          buildInstallmentSelect(installments);
        },
        error: function(response) {
          console.log(response);
        }
      })
    }

    function buildInstallmentSelect(installments){
      $.each(installments, (function(key, value){
        $("#InstallmentCombo").append("<option value = "+ value['quantity'] +">" + value['quantity'] + "x de " + value['installmentAmount'].toFixed(2) + " - Total de " + value['totalAmount'].toFixed(2)+"</option>");
      }))
    }
  });
</script>
</html>