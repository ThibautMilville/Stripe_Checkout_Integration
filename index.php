<?php
  require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stripe checkout integration</title>
  <link rel="stylesheet" href="style/style.css">
  <!-- STRIPE SCRIPT -->
  <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
  <div class="container">
    <h1>Stripe checkout Integration</h1>
    <div class="item">
      <!-- DISPLAY ERRORS RETURNED BY CHECKOUT SESSION -->
      <div id="paymentResponse" class="hidden"></div>
      <!-- PRODUCT DETAILS -->
      <h2><?php echo $productName ?></h2>
      <img src="https://www.beatsbydre.com/content/dam/beats/web/product/headphones/solo3-wireless/pdp/product-carousel/black/pc-solo3-black-thrqtr-left.jpg">
      <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Cum possimus praesentium dolorem adipisci ut, natus delectus libero. Sunt aspernatur in error voluptatum, rem nemo laboriosam est consectetur quaerat libero nam neque eaque, non nisi ipsum dolorum necessitatibus labore placeat veniam asperiores! Debitis earum harum, hic soluta cum dicta doloremque qui!</p>
      <h3>Prix : <?php echo $productPrice ?> $</h3>
      <!-- PAYMENT BUTTON -->
      <button class="stripe-button" id="payButton">
        <div class="spinner hidden" id="spinner"></div>
        <span id="buttonText">Pay now</span>
      </button>
    </div>
  </div>
  <script>
    // SET STRIPE PUBLISHABLE KEY TO INITIALIZE STRIPE.JS
    const stripe = Stripe('<?php echo STRIPE_PUBLISHABLE_KEY ?>');

    // SELECT PAYMENT BUTTON
    const payBtn = document.querySelector('#payButton');

    // PAYMENT REQUEST HANDLER
    payBtn.addEventListener("click", function(evt){
      setLoading(true);

      createCheckoutSession().then(function(data){
        if(data.sessionId){
          stripe.redirectToCheckout({
            sessionId: data.sessionId,
          }).then(handleResult);
        }else{
          handleResult(data);
        }
      })
    })
    // CREATE A CHECKOUT SESSION
    const createCheckoutSession = function(stripe){
      return fetch("payment_init.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          createCheckoutSession: 1,
        })
      }).then(function(result){
        return result.json();
      })
    }
    // HANDLE ANY ERRORS RETURNED FROM PAYMENT REQUEST
    const handleResult = function(result){
      if(result.error){
        showMessage(result.error.message);
      }
      setLoading(false);
    }
    // SHOW A SPINNER ON PAYMENT PROCESSING
    const setLoading = function(isLoading){
      if(isLoading){
        // DISABLE THE BUTTON AND SHOW A SPINNER
        payBtn.disabled = true;
        document.querySelector("#spinner").classList.remove("hidden");
        document.querySelector("#buttonText").classList.add("hidden");
      }else{
        // ENABLE THE BUTTON AND HIDE THE SPINNER
        payBtn.disabled = false;
        document.querySelector("#spinner").classList.add("hidden");
        document.querySelector("#buttonText").classList.remove("hidden");
      }
    }
    // DISPLAY MESSAGE
    function showMessage(messageText){
      const messageContainer = document.querySelector("#paymentResponse");
      messageContainer.classList.remove("hidden");
      messageContainer.textContent = messageText;
      // SET TIMEOUT TO HIDE MESSAGE AFTER 5 SECONDS
      setTimeout(function(){
        messageContainer.classList.add("hidden");
        messageText.textContent = "";
      }, 5000);
    }
  </script>
</body>
</html>