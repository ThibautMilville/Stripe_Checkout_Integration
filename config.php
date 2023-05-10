<?php
  // PRODUCT DETAILS
  $productName = "Demo product";
  $productID = "DP12345";
  $productPrice = 75;
  $currency = "usd";

  // STRIPE API CONFIGURATION (SWITCH TO PRODUCTION API KEYS FOR REAL TRANSACTIONS)
  define('STRIPE_API_KEY', 'sk_test_51N6GaFDobsEbj3fA0yJ3c3Z98s0JNNs2pSthQL0AiPshjHSdSH35Qo6BQrujOCfet0RceFCqAy85DIL3Q2MyCUtH003XYWak2R');
  define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51N6GaFDobsEbj3fA6bXlDS6EdFve4jhh5A52BF8QZrjqdAiH3pMBp3GQ05k7hoPMjt3hz078FrsNuM3tQEntbyz0003avTi9XX');
  define('STRIPE_SUCCESS_URL', 'http://localhost:8888/projets/stripe_checkout_integration/payment-success.php');
  define('STRIPE_CANCEL_URL', 'http://localhost:8888/projets/stripe_checkout_integration/payment-cancel.php');

  // DATABASE CONFIGURATION
  define('DB_HOST', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', 'root');
  define('DB_NAME', 'stripe_checkout');
?>