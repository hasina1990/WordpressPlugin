<?php
require __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'wp-load.php';
nocache_headers();
?>
<?php global $table_prefix, $wpdb; ?>

<?php

require('lib'.DIRECTORY_SEPARATOR.'stripe-php-master'.DIRECTORY_SEPARATOR.'init.php');

 // Set your secret key. Remember to switch to your live secret key in production!
  // See your keys here: https://dashboard.stripe.com/account/apikeys
  \Stripe\Stripe::setApiKey(get_option("tekzone_stripe_api_key"));

if ($_GET['session_id'] && $_GET["zoolz_order_session_id"]){

    $session = \Stripe\Checkout\Session::retrieve($_GET['session_id']); 
    $customer = \Stripe\Customer::retrieve($session['customer']);
    $intent = \Stripe\PaymentIntent::retrieve($session['payment_intent']);

    $orderTable = $table_prefix . 'tekzone_zoolz_orders';
    $zoolzOrder = $wpdb->get_row("SELECT * FROM `".$orderTable."` WHERE MD5(CONCAT('TEKZONE','||', id))='".$_GET["zoolz_order_session_id"]."'");
    if(!$zoolzOrder)
    {
        throw new Exception("Order is not valid.");
    }

    $orderUpdateData = json_decode(json_encode($zoolzOrder),1);
    $orderUpdateData["payment_session"] = json_encode($intent);
     
    if (is_array($intent->charges->data)){
            $charge = $intent->charges->data[0];
    }else{
            $charge = $intent->charges->data;
    }

    if($charge['amount_refunded'] == 0 && empty($charge['failure_code']) && $charge['paid'] == 1 && $charge['captured'] == 1){ 

        if ($zoolzOrder->payment_status == 2) {
            $_SESSION["tekzone_zoolz_message"]["success"] = "Account created successfully on ".$zoolzOrder->purchased_on.".";
        }else{
            require('lib'.DIRECTORY_SEPARATOR.'ZoolzPHP.php');
    
            $config_wsdl_url = get_option("tekzone_zoolz_url");
            $config_authToken = get_option("tekzone_zoolz_api_key");
        
            $zoolz = new ZoolzPHP($config_wsdl_url, $config_authToken);

            $orderUpdateData["name"] = $charge['billing_details']['name'];
            $orderUpdateData["address"] = json_encode($charge['billing_details']['address']);

            if(isset($charge['billing_details']['phone']) && trim($charge['billing_details']['phone']))
            {
                $orderUpdateData["phone"] = $charge['billing_details']['phone'];
            }

            $password = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ".md5($charge['billing_details']['name']."||".$customer->email."||".$zoolzOrder->company)."0123456789@#$"),0,15);

            $orderUpdateData["zoolz_account_password"] = $password;
        
            $user = array(
                    'name' => $charge['billing_details']['name'],
                    'email'=> $customer->email,
                    'password'=> $password,// "London@2013",
                    'companyName'=> (trim($zoolzOrder->company))?$zoolzOrder->company:'',
                    'planID'=> $session['client_reference_id'],
                    'sendEmail'=> false,
                    'language' => '1'   
                        );

                $zoolz->debug(true);
                $return = $zoolz->CreateAccount($user);

                $orderUpdateData["zoolz_account_result"] = json_encode($return);
                
                if ($return->{'CreateAccountResult'}->{'Code'} == "Success"){

                    $orderUpdateData["payment_status"] = 2;
                    $orderUpdateData["payment_message"] = "Your zoolz account is created successfully.";

                    $_SESSION["tekzone_zoolz_success_payment_on_zoolz"] = 1;
                }else{

                    $orderUpdateData["payment_status"] = 4;
                    $orderUpdateData["payment_message"] = "Your zoolz account is created successfully.";

                    $_SESSION["tekzone_zoolz_message"]["error"] = "Account could not be created. ". $return->{'CreateAccountResult'}->{'Message'}.". Refund initiated and will credit into original payment method used within 7 working days.";
                    $re = \Stripe\Refund::create([
                            'payment_intent' => $session['payment_intent'],
                    ]);
                }
        }       
                
    }else {

        $orderUpdateData["payment_status"] = 3;
        $orderUpdateData["payment_message"] = "Payment unsuccessful.";
        $_SESSION["tekzone_zoolz_message"]["error"] = "Payment unsuccessful.";
    } 

$orderUpdateData["updated_date"] = date("Y-m-d H:i:s");

$sql = "UPDATE `".$orderTable."` SET name='%s', address='%s', phone='%s', payment_session='%s', payment_status='%s', `payment_message`='%s', `zoolz_account_result`='%s', `zoolz_account_password`='%s', `updated_date`='%s' WHERE id='%s'";

$result = $wpdb->query($wpdb->prepare($sql,$orderUpdateData["name"],$orderUpdateData["address"],$orderUpdateData["phone"],$orderUpdateData["payment_session"],$orderUpdateData["payment_status"],$orderUpdateData["payment_message"],$orderUpdateData["zoolz_account_result"],$orderUpdateData["zoolz_account_password"],$orderUpdateData["updated_date"],$orderUpdateData["id"]));

if($orderUpdateData["payment_status"] == 2)
{
   tekzoneZoolzSendSuccessfullOrderCreationEmail(json_decode(json_encode($orderUpdateData)));
}

if(isset($_GET["current_web_url"]) && $_GET["current_web_url"]=trim($_GET["current_web_url"]))
{
    header("Location: ".$_GET["current_web_url"]);
}
else
{
    header("Location: ".get_site_url());
}

}