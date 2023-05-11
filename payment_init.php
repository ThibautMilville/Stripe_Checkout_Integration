<?php
// INCLUDE THE CONFIGURATION FILE
require_once('config.php');
// INCLUDE THE STRIPE PHP LIBRARY
require_once('stripe-php/init.php');

// SET API KEY
$stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

// DEFAULT RESPONSE
$response = array(
  'status' => 0,
  'error' => array(
    'message' => 'Invalid Request!'
  )
);

// GET REQUEST DATA
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $input = file_get_contents('php://input');
  $request = json_decode($input);
}

// DISPLAY A DEFAULT ERROR IF THE JSON CAN'T BE DECODED
if (json_last_error() !== JSON_ERROR_NONE) {
  http_response_code(400);
  echo json_encode($response);
  exit;
}

if (!empty($request->createCheckoutSession)) {
  // CONVERT PRODUCT PRICE TO CENT WITH 2 DECIMALS
  $stripeAmount = round($productPrice * 100, 2);

  // CREATE NEW CHECKOUT SESSION FOR THE ORDER
  try {
    $checkout_session = $stripe->checkout->sessions->create([
      'line_items' => [
        [
          'price_data' => [
            'product_data' => [
              'name' => $productName,
              'metadata' => [
                'pro_id' => $productId
              ]
            ],
            'unit_amount' => $stripeAmount,
            'currency' => $currency,
          ],
          'quantity' => 1,
        ]
        ],
        'mode' => 'payment',
        'success_url' => STRIPE_SUCCESS_URL . '?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => STRIPE_CANCEL_URL,
    ]);
  } catch (Exception $e) {
    $api_error = $e->getMessage();
  }

  // CHECK FOR API ERRORS
  if (empty($api_error) && $checkout_session) {
    $response = array(
      'status' => 1,
      'message' => 'Checkout session created successfully!',
      'sessionId' => $checkout_session->id
    );
  } else {
    $response = array(
      'status' => 0,
      'error' => array(
        'message' => 'Checkout session creation failed! ' . $api_error
      )
    );
  }
}

// RETURN RESPONSE
echo json_encode($response);
?>