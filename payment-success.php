<?php
// INCLUDE CONFIGURATION FILE
require_once('config.php');
// INCLUDE DATABASE CONNECTION FILE
require_once('db_connect.php');

$payment_id = $statusMsg = '';
$status = 'error';

// CHECK WHETHER STRIPE CHECKOUT IS EMPTY OR NOT
if (!empty($_GET['session_id'])) {
  // GET THE SESSION ID
  $session_id = $_GET['session_id'];

  // FETCH TRANSACTION DATA FROM THE DATABASE IF ALREADY EXISTS
  $sqlQ = "SELECT * FROM transactions WHERE stripe_checkout_session_id = ?";
  $stmt = $db->prepare($sqlQ);
  $stmt->bind_param("s", $session_id);
  $db_session_id = $session_id;
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    // TRANSACTION DETAILS
    $transData = $result->fetch_assoc();
    $payment_id = $transData['id'];
    $transactionID = $transData['txn_id'];
    $paidAmount = $transData['paid_amount'];
    $paidCurrency = $transData['paid_amount_currency'];
    $payment_status = $transData['payment_status'];

    $customer_name = $transData['customer_name'];
    $customer_email = $transData['customer_email'];

    $status = 'success';
    $statusMsg = "Your Payment has been Successful!";
  } else {
    // INCLUDE THE STRIPE PHP LIBRARY
    require_once('stripe-php/init.php');

    // SET API KEY
    $stripe = new \Stripe\StripeClient(STRIPE_API_KEY);

    // FETCH THE CHECKOUT SESSION DETAILS TO DISPLAY THE JSON RESULT ON THE SUCCESS PAGE
    try {
      $checkout_session = $stripe->checkout->sessions->retrieve($session_id);
    } catch (Exception $e) {
      $api_error = $e->getMessage();
    }

    if (empty($api_error) && $checkout_session) {
      // GET CUSTOMER DETAILS
      $customer_details = $checkout_session->customer_details;

      // RETRIEVE THE DETAILS OF A PAYMENT INTENT
      try {
        $paymentIntent = $stripe->paymentIntents->retrieve($checkout_session->payment_intent);
      } catch (\Stripe\Exception\ApiErrorException $e) {
        $api_error = $e->getMessage();
      }

      if (empty($api_error) && $paymentIntent) {
        // CHECK WHETHER THE PAYMENT WAS SUCCESSFUL
        if (!empty($paymentIntent) && $paymentIntent->status == 'succeeded') {
          // TRANSACTION DETAILS
          $transactionID = $paymentIntent->id;
          $paidAmount = $paymentIntent->amount;
          $paidAmount = ($paidAmount / 100);
          $paidCurrency = $paymentIntent->currency;
          $payment_status = $paymentIntent->status;

          // CUSTOMER INFO
          $customer_name = $customer_email = '';
          if (!empty($customer_details)) {
            $customer_name = !empty($customer_details->name) ? $customer_details->name : '';
            $customer_email = !empty($customer_details->email) ? $customer_details->email : '';
          }
          // CHECK IF ANY TRANSACTION DATA EXISTS WITH THE SAME TRANSACTION ID
          $sqlQ = "SELECT id FROM transactions WHERE txn_id = ?";
          $stmt = $db->prepare($sqlQ);
          $stmt->bind_param("s", $transactionID);
          $stmt->execute();
          $result = $stmt->get_result();
          $prevRow = $result->fetch_assoc();

          if (!empty($prevRow)) {
            $payment_id = $prevRow['id'];
          } else {
            // INSERT TRANSACTION DATA INTO THE DATABASE
            $sqlQ = "INSERT INTO transactions (customer_name, customer_email,
            item_name, item_number, item_price, item_price_currency, paid_amount, paid_amount_currency,
            txn_id, payment_status, stripe_checkout_session_id, created, modified)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW(),NOW())";
            $stmt = $db->prepare($sqlQ);
            $stmt->bind_param(
              "sssssssssss",
              $customer_name,
              $customer_email,
              $productName,
              $productID,
              $productPrice,
              $currency,
              $paidAmount,
              $paidCurrency,
              $transactionID,
              $payment_status,
              $session_id
            );
            $insert = $stmt->execute();

            if ($insert) {
              $payment_id = $stmt->insert_id;
            }
          }
          $status = 'success';
          $statusMsg = "Your payment has been successful!";
        } else {
          $statusMsg = "Transaction has been failed!";
        }
      } else {
        $statusMsg = "Unable to fetch the transaction details! $api_error";
      }
    } else {
      $statusMsg = "Invalid transaction! $api_error";
    }
  }
} else {
  $statusMsg = "Invalid request!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment success</title>
  <link rel="stylesheet" href="style/style.css">
</head>

<body>
  <div class="container">
    <div class="status">
      <!-- DIFFERENT DISPLAY IN CASE OF SUCCESS OR ERROR -->
      <?php if (!empty($payment_id)) { ?>
        <!-- DISPLAY IN CASE OF SUCCESS -->
        <h1 class="<?php echo $status; ?>"><?php echo $statusMsg; ?></h1>

        <h4>Payment Information</h4>
        <p><b>Reference number:</b> <?php echo $payment_id ?></p>
        <p><b>Transaction ID:</b> <?php echo $transactionID ?></p>
        <p><b>Paid Amount:</b> <?php echo $paidAmount . ' ' . $paidCurrency; ?></p>
        <p><b>Payment Status:</b> <?php echo $payment_status; ?></p>

        <h4>Customer Information</h4>
        <p><b>Name:</b> <?php echo $customer_name; ?></p>
        <p><b>Email:</b> <?php echo $customer_email; ?></p>

        <h4>Product Information</h4>
        <p><b>Name:</b> <?php echo $productName; ?></p>
        <p><b>Price:</b> <?php echo $productPrice . ' ' . $currency; ?></p>
      <?php } else { ?>
        <!-- DISPLAY IN CASE OF ERROR -->
        <h1 class="error">Your payment has been failed</h1>
        <p class="error"><?php echo $statusMsg; ?></p>
      <?php } ?>
    </div>
    <a href="index.php" class="btn-link">BACK TO PRODUCT PAGE</a>
  </div>
</body>

</html>