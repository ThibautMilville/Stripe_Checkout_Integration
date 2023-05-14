<?php
  // PRODUCT DETAILS
  $productName = "Demo product";
  $productID = "DP12345";
  $productPrice = 75;
  $currency = "usd";

  // STRIPE API CONFIGURATION (SWITCH TO PRODUCTION API KEYS FOR REAL TRANSACTIONS)
  define('STRIPE_API_KEY', 'PUT_YOUR_SECRET_KEY_HERE');
  define('STRIPE_PUBLISHABLE_KEY', 'PUT_YOUR_PUBLISHABLE_KEY_HERE');
  define('STRIPE_SUCCESS_URL', 'http://localhost:8888/projets/stripe_checkout_integration/payment-success.php');
  define('STRIPE_CANCEL_URL', 'http://localhost:8888/projets/stripe_checkout_integration/payment-cancel.php');

  // DATABASE CONFIGURATION
  define('DB_HOST', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', 'root');
  define('DB_NAME', 'stripe_checkout');
?>