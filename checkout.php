<?php
require __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'wp-load.php';
nocache_headers();
?>
<?php global $table_prefix, $wpdb; ?>
<?php

require('lib'.DIRECTORY_SEPARATOR.'stripe-php-master'.DIRECTORY_SEPARATOR.'init.php');

try
{
    if (empty($_GET['id'])) {
        $_SESSION["tekzone_zoolz_message"]["error"] = "id is not found.";

        if(isset($_GET["current_web_url"]) && $_GET["current_web_url"]=trim($_GET["current_web_url"]))
        {
            header("Location: ".$_GET["current_web_url"]);
        }
        else
        {
            header("Location: ".get_site_url());
        }
        
        exit;
    }

    $productID = $_GET['id'];

    $productTable = $table_prefix . 'tekzone_zoolz_products';
    $product = $wpdb->get_row("SELECT * FROM `".$productTable."` WHERE id='".(int)$productID."'");
    if(!$product)
    {
        throw new Exception("Selected product is not valid.");
    }

    $description = strip_tags(str_replace("<br>", ", ", $product->desc));

    if($product->image)    {
        $imageURL = getTekzoneMediaProductURL($product->image);
    }
    else    {
        $imageURL = getTekzoneIconsURL("palceholder.png");
    }

    $stripe = [
      "secret_key"      => get_option("tekzone_stripe_api_key"),//"sk_test_ZTkzeo5XiHsZOZ3QzQTPf2I2",
      "publishable_key" => get_option("tekzone_stripe_publishable_key")//"pk_test_dYQdyCYRKaR4cItfPOSRuapT",
    ];

    $orderTable = $table_prefix . 'tekzone_zoolz_orders';

    $initiateCheckoutData = array();
    $initiateCheckoutData["email"] = $_GET['email'];
    $initiateCheckoutData["company"] = $_GET['company_name'];
    $initiateCheckoutData["product_id"] = $product->id;
    $initiateCheckoutData["product_name"] = $product->name;
    $initiateCheckoutData["zoolz_plan_id"] = $product->plan_id;
    $initiateCheckoutData["zoolz_plan_name"] = $product->zoolz_plan_name;
    $initiateCheckoutData["payment_amount"] = $product->price_in_cents;
    $initiateCheckoutData["currency"] = $product->currency;
    $initiateCheckoutData["payment_status"] = 1;
    $initiateCheckoutData["purchased_on"] = date("Y-m-d H:i:s");

    $wpdb->insert( $orderTable, $initiateCheckoutData, array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));

    if($wpdb->insert_id)
    {
        $insertedId = $wpdb->insert_id;

        $orderRefNumber = "PO".str_pad($insertedId, 8, '0', STR_PAD_LEFT);

        $sql = "UPDATE `".$orderTable."` SET order_ref='%s' WHERE id='%s'";
        $result = $wpdb->query($wpdb->prepare($sql, $orderRefNumber, $insertedId));

        $checkoutSessionId = md5('TEKZONE||'.$insertedId);
    }
    else
    {
        throw new Exception("Oops! Something went wrong. Please try again later.");
    }


 // Set your secret key. Remember to switch to your live secret key in production!
  // See your keys here: https://dashboard.stripe.com/account/apikeys
  \Stripe\Stripe::setApiKey(get_option("tekzone_stripe_api_key"));


  $session = \Stripe\Checkout\Session::create([
    'payment_method_types' => ['card'],
    'line_items' => [[
      'price_data' => [
        'currency' => $product->currency,
        'product_data' => [
          'name' => $product->name,
          'images' => array($imageURL)
        ],
        'unit_amount' => $product->price_in_cents,
      ],
      'quantity' => 1,
      'description' => $description
    ]],
    'billing_address_collection' => 'required',
    'customer_email' => $_GET['email'],
    'client_reference_id' => $product->plan_id,
    'mode' => 'payment',
    'success_url' => getStripePaymentSuccessURL().'?session_id={CHECKOUT_SESSION_ID}&zoolz_order_session_id='.$checkoutSessionId.'&current_web_url='.$_GET["current_web_url"],
    'cancel_url' => getStripePaymentCancelURL()."?current_web_url=".$_GET["current_web_url"],
  ]);


}
catch(Exception $e)
{    
    $_SESSION["tekzone_zoolz_message"]["error"] = $e->getMessage();

    if(isset($_GET["current_web_url"]) && $_GET["current_web_url"]=trim($_GET["current_web_url"]))
    {
        header("Location: ".$_GET["current_web_url"]);
    }
    else
    {
        header("Location: ".get_site_url());
    }
}

?>
  <html>
  <head>
    <title>Stripe Server Side Integration</title>

  </head>
  <body>
    <div id="loader-parent" class="loader-parent" style="display: inline-block;">
        <div id="loader" class="loader"></div>
    </div>

    <script src="https://js.stripe.com/v3/"></script>
   <script type="text/javascript">
      // Create an instance of the Stripe object with your publishable API key
      var stripe = Stripe('<?php echo $stripe['publishable_key']; ?>');
      var session = "<?php echo $session['id']; ?>";
     
        // Create a new Checkout Session using the server-side endpoint you
        // created in step 3.
       

        stripe.redirectToCheckout({ sessionId: session })
 
        .then(function(result) {
          // If `redirectToCheckout` fails due to a browser or network
          // error, you should display the localized error message to your
          // customer using `error.message`.
          if (result.error) {
            alert(result.error.message);
          }
        })
        .catch(function(error) {
          console.error('Error:', error);
        });
     
    </script>
   </body>
   </html>
  