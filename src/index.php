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
    .cart-integration {
      max-width: 50%;
    }
    .paymentTypeSelect label {
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="cart-integration">
    <div class="container-sm">
      <div class="row">
        <div class="col">
          <h1>Pagamento</h1>
          <hr />
          <form id="paymentForm" action="recorrencePayment.php" method="post">
            <fieldset class="pt-2 pb-2">
              <legend>Informações pessoais</legend>

              <div class="mb-3">
                <label for="name" class="form-label">
                  Nome completo
                </label>
                <input type="text" class="form-control" id="name" name="name" />
              </div>

              <div class="mb-3">
                <div class="row">
                  <div class="col">
                    <label for="cpf" class="form-label">
                      CPF
                    </label>
                    <input type="text" class="form-control" id="cpf" name="cpf" maxLength="11" />
                    <div id="cpfHelp" class="form-text">Somente números</div>
                  </div>

                  <div class="col">
                    <label for="birthDate" class="form-label">
                      Data de Nascimento
                    </label>
                    <input type="text" class="form-control" id="birthDate" name="birthDate" placeholder="00/00/0000" />
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="birthDate" class="form-label">
                  E-mail
                </label>
                <input type="e-mail" class="form-control" id="email" name="email" />
              </div>

              <div class="mb-3">
                <div class="row">
                  <div class="col-3">
                    <label for="areaCode" class="form-label">
                      DDD
                    </label>
                    <input type="text" class="form-control" id="areaCode" name="areaCode" maxLength="2" />
                    <div id="areaCodeHelp" class="form-text">Somente números</div>
                  </div>

                  <div class="col">
                    <label for="phone" class="form-label">
                      Telefone
                    </label>
                    <input type="text" class="form-control" id="phone" name="phone" maxLength="9" />
                    <div id="phoneHelp" class="form-text">Somente números</div>
                  </div>
                </div>
              </div>
            </fieldset>

            <hr />

            <fieldset class="pt-2 pb-2">
              <legend>Endereço</legend>

              <div class="mb-3">
                <div class="row">
                  <div class="col">
                    <label for="street" class="form-label">
                      Endereço
                    </label>
                    <input type="text" class="form-control" id="street" name="street" />
                  </div>

                  <div class="col-3">
                    <label for="number" class="form-label">
                      Número
                    </label>
                    <input type="text" class="form-control" id="number" name="number" />
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row">
                  <div class="col">
                    <label for="complement" class="form-label">
                      Complemento
                    </label>
                    <input type="text" class="form-control" id="complement" name="complement" />
                  </div>

                  <div class="col">
                    <label for="district" class="form-label">
                      Bairro
                    </label>
                    <input type="text" class="form-control" id="district" name="district" />
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <div class="row">
                  <div class="col">
                    <label for="city" class="form-label">
                      Cidade
                    </label>
                    <input type="text" class="form-control" id="city" name="city" />
                  </div>

                  <div class="col">
                    <label for="state" class="form-label">
                      Estado
                    </label>
                    <select class="form-select" id="state" name="state">
                      <option selected>Selecione um estado</option>
                      <option value="AC">Acre</option>
                      <option value="AL">Alagoas</option>
                      <option value="AP">Amapá</option>
                      <option value="AM">Amazonas</option>
                      <option value="BA">Bahia</option>
                      <option value="CE">Ceará</option>
                      <option value="DF">Distrito Federal</option>
                      <option value="ES">Espírito Santo</option>
                      <option value="GO">Goiás</option>
                      <option value="MA">Maranhão</option>
                      <option value="MT">Mato Grosso</option>
                      <option value="MS">Mato Grosso do Sul</option>
                      <option value="MG">Minas Gerais</option>
                      <option value="PA">Pará</option>
                      <option value="PB">Paraíba</option>
                      <option value="PR">Paraná</option>
                      <option value="PE">Pernambuco</option>
                      <option value="PI">Piauí</option>
                      <option value="RJ">Rio de Janeiro</option>
                      <option value="RN">Rio Grande do Norte</option>
                      <option value="RS">Rio Grande do Sul</option>
                      <option value="RO">Rondônia</option>
                      <option value="RR">Roraima</option>
                      <option value="SC">Santa Catarina</option>
                      <option value="SP">São Paulo</option>
                      <option value="SE">Sergipe</option>
                      <option value="TO">Tocantins</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="mb-3">
                <label for="postalCode" class="form-label">
                  CEP
                </label>
                <input type="text" class="form-control" id="postalCode" name="postalCode" />
                <div id="postalCodeHelp" class="form-text">Somente números</div>
              </div>

              <input type="hidden" id="senderHash" name="senderHash" />
            </fieldset>

            <hr />

            <fieldset class="pt-2 pb-2">
              <legend>Forma de pagamento</legend>
              <div class="row">
                <div class="col">
                  <div class="border rounded p-3 paymentTypeSelect">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="paymentOption" id="recorrencyType" value="1" checked>
                      <label class="form-check-label" for="recorrencyType">
                        <h5>Recorrente</h5>
                        <p>O pagamento é feito mensalmente em seu cartão de crédito, e não compromete seu limite. Porém é necessário ter o valor do limite da assinatura todo o mês para que não ocorra o cancelamento.<p>
                      </label>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="border rounded p-3 paymentTypeSelect">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="paymentOption" id="installmentType" value="2">
                      <label class="form-check-label" for="installmentType">
                        <h5>Parcelado</h5>
                        <p>A cobrança é feito uma única vez em seu cartão de crédito, divido na quantidade de parcelas selecionada após infomar os dados do cartão. O valor de R$ 1300,60, poderá ser parcelado em até 12x sem juros.<p>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </fieldset>

            <hr />

            <fieldset class="pt-2 pb-2">
              <legend>Cartão de Crédito</legend>
              <div class="mb-3">
                <div class="row">
                  <div class="col">
                    <label for="creditCardNumber" class="form-label">Número do cartão</label> 
                    <input type="text" class="form-control creditcard" id="creditCardNumber" name="creditCardNumber">
                  </div>
                  <!-- <div class="col-2"> -->
                    <!-- <label for="creditCardBrand" class="form-label">Bandeira</label> -->
                    <input type="hidden" class="form-control creditcard" id="creditCardBrand" name="creditCardBrand">
                  <!-- </div> -->
                </div>
              </div>

              <div class="mb-3">
                <div>
                  <label for="cardName" class="form-label">Nome como está no cartão</label> 
                  <input type="text" class="form-control creditcard" id="cardName" name="cardName">
                </div>
              </div>

              <div class="md-3">
                <div class="row">
                  <div class="col">
                    <label for="creditCardExpMonth" class="form-label">Validade mês (mm)</label>
                    <input type="text" class="form-control creditcard" id="creditCardExpMonth" name="creditCardExpMonth" size="2">
                  </div>

                  <div class="col">
                    <label for="creditCardExpYear" class="form-label">Validate ano (yyyy)</label>
                    <input type="text" class="form-control creditcard" id="creditCardExpYear" name="creditCardExpYear" size="4">
                  </div>

                  <div class="col">
                    <label for="creditCardCvv" class="form-label">CVV</label>
                    <input type="text" class="form-control creditcard" id="creditCardCvv" name="creditCardCvv">

                    <input type="hidden" id="creditCardToken" name="creditCardToken">
                  </div>
                </div>
              </div>
            </fieldset>

            <hr />

            <fieldset id="installmentOptions" class="pt-2 pb-2">
              <legend>Parcelamento</legend>
              <div class="md-3">
                <div class="row">
                  <div class="col">
                    <input type="hidden" id="checkoutValue" name="checkoutValue" value="1300,60">
                    <select class="form-select" id="installmentCombo" name="installmentCombo">
                      <option selected>Selecione uma opção</option>
                    </select>
                    <input type="hidden" id="installmentAmount" name="installmentAmount">
                  </div>
                </div>
              </div>
            </fieldset>

            <div class="pt-4 pb-4">
              <button class="btn btn-lg btn-primary" type="submit">Finalizar o pedido</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript">
	// Session ID
	PagSeguroDirectPayment.setSessionId('<?php echo $session; ?>');
  
  $(document).ready(function() {
    $("#installmentOptions").hide();
    $("input[type=radio][name=paymentOption]").on('change', 
      function() {
        switch ($(this).val()) {
          case '2':
            $("#paymentForm").attr("action", "directPayment.php");
            $("#installmentOptions").show();
            break;
          default:
            $("#paymentForm").attr("action", "recorrencePayment.php");
            $("#installmentOptions").hide();
            break;
        }
      }
    );

    $("#name").focus(() => {
      PagSeguroDirectPayment.onSenderHashReady(function(response) {
        if(response?.status == 'error') {
          return false;
        }
        $("#senderHash").val(response?.senderHash);	
      });
    });

    // Get CreditCard Brand and check if is Internationl
    $("#creditCardNumber").keyup(function(){
      if ($("#creditCardNumber").val().length >= 6) {
        PagSeguroDirectPayment.getBrand({
          cardBin: $("#creditCardNumber").val().substring(0,6),
          success: function(response) { 
              $("#creditCardBrand").val(response?.brand?.name);
              $("#creditCardCvv").attr('size', response?.brand?.cvvSize);
          },
          error: function(response) {
            console.log(response);
          }
        })
      };
    })

    function printError(error){
      $.each(error?.errors, (function(key, value){
        console.log("Foi retornado o código " + key + " com a mensagem: " + value);
      }));
    }

    function getPaymentMethods(valor){
      PagSeguroDirectPayment.getPaymentMethods({
        amount: valor,
        success: function(response) {
          console.log(response);
        },
        error: function(response) {
          console.log(JSON.stringify(response));
        }
      })
    }	

    // Generates the creditCardToken
    $("#creditCardCvv").keyup(function(){
      if ($("#creditCardNumber").val().length >= 3) {
        var param = {
          cardNumber: $("#creditCardNumber").val(),
          cvv: $("#creditCardCvv").val(),
          expirationMonth: $("#creditCardExpMonth").val(),
          expirationYear: $("#creditCardExpYear").val(),
          success: function(response) {
            $("#creditCardToken").val(response?.card?.token);
          },
          error: function(response) {
            printError(response);
          }
        }
        // parâmetro opcional para qualquer chamada
        if($("#creditCardBrand").val() != '') {
          param.brand = $("#creditCardBrand").val();
        }
        PagSeguroDirectPayment.createCardToken(param);
      }
    });
    // $("#generateCreditCardToken").click(function(){
    //   var param = {
    //     cardNumber: $("#creditCardNumber").val(),
    //     cvv: $("#creditCardCvv").val(),
    //     expirationMonth: $("#creditCardExpMonth").val(),
    //     expirationYear: $("#creditCardExpYear").val(),
    //     success: function(response) {
    //       console.log(response);
    //       $("#creditCardToken").val(response?.card?.token);
    //     },
    //     error: function(response) {
    //       console.log(response);
    //       printError(response);
    //     }
    //   }
    //   // parâmetro opcional para qualquer chamada
    //   if($("#creditCardBrand").val() != '') {
    //     param.brand = $("#creditCardBrand").val();
    //   }
    //   PagSeguroDirectPayment.createCardToken(param);
    // });

    // Check installment
    // $("#installmentCheck").click(function(){
    //   if($("#creditCardBrand").val() != '') {
    //     getInstallments();
    //   } else {
    //     alert("Uma bandeira deve estar selecionada");
    //   }
    // })
    console.log('input', $("input[name=creditCardToken]"));
    $("input[name=creditCardToken]").on('input', function(){
      console.log('creditCardToken input');
      if (
        $("input[type=radio][name=paymentOption]").val() == '1' &&
        $(this).val() != '' &&
        $("#creditCardBrand").val() != ''
      ) {
        console.log('getInstallments');
        getInstallments();
      } else {
        alert("Uma bandeira deve estar selecionada");
      }
    });

    function getInstallments(){
      var brand = $("#creditCardBrand").val();
      PagSeguroDirectPayment.getInstallments({
        amount: $("#checkoutValue").val().replace(",", "."),
        brand: brand,
        maxInstallmentNoInterest: 12,
        success: function(response) {
          var installments = response?.installments[brand];
          buildInstallmentSelect(installments);
        },
        error: function(response) {
          console.log(response);
        }
      })
    };

    function buildInstallmentSelect(installments){
      $.each(installments, (function(key, value){
        $("#installmentCombo").append("<option value = "+ value?.quantity +" data-amount="+ value?.installmentAmount.toFixed(2) +">" + value?.quantity + "x de " + value?.installmentAmount.toFixed(2) + " - Total de " + value?.totalAmount.toFixed(2)+"</option>");
      }))
    };

    $("#installmentCombo").on('change', function() {
      if ($(this).val()) {
        $("installmentAmount").val($(this).data("amount"));
      }
    });
  });
</script>
</html>