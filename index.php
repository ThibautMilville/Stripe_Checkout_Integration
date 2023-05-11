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
</head>
<body>
  <div class="container">
    <h1>Stripe checkout Integration</h1>
    <div class="item">
      <!-- PRODUCT DETAILS -->
      <h2><?php echo $productName ?></h2>
      <img src="https://www.beatsbydre.com/content/dam/beats/web/product/headphones/solo3-wireless/pdp/product-carousel/black/pc-solo3-black-thrqtr-left.jpg">
      <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Incidunt neque accusantium minus ipsum, magni eum placeat pariatur sit. Nobis provident illum soluta veniam doloremque. Magnam ex beatae laboriosam ipsum minus dolorem provident magni est, aliquam, corporis possimus. Quaerat numquam porro laborum commodi, esse distinctio, aliquid alias dolor voluptates impedit fugiat. Ducimus optio eaque animi unde repudiandae corrupti deserunt, facilis cumque quae suscipit harum magnam alias numquam iste at voluptates maiores consectetur sit. Vitae dolorem doloribus, culpa amet eligendi praesentium eveniet nisi, quia cum sapiente obcaecati incidunt, omnis ipsa. Dignissimos, qui sapiente! Ut facilis ducimus, rem voluptatem amet sequi soluta atque.</p>
      <!-- PAYMENT BUTTON -->
      <button class="stripe-button" id="payButton">
        <div class="spinner hidden"></div>
        <span id="buttonText">Pay now</span>
      </button>
    </div>
  </div>
</body>
</html>