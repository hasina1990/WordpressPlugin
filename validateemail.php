<?php

require __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'wp-load.php';

nocache_headers();

?>
<?php global $table_prefix, $wpdb; ?>
<?php

require('lib'.DIRECTORY_SEPARATOR.'ZoolzPHP.php');

$response = array();

try
{
    if(!isset($_POST["product"]["id"]))
    {
        throw new Exception("inavlid data posted.");
    }

    if(!isset($_POST["email"]))
    {
        throw new Exception("inavlid data posted.");
    }
    elseif(!$_POST["email"] = trim($_POST["email"]))
    {
        throw new Exception("Please enter valid email address.");
    }
    elseif(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
    {
        throw new Exception("Please enter email address in valid format.");
    }

    $companyName = "";
    if(isset($_POST["company_name"]) && $_POST["company_name"]=trim($_POST["company_name"]))
    {
        $companyName = $_POST["company_name"];
    }

    $productTable = $table_prefix . 'tekzone_zoolz_products';

    $product = $wpdb->get_row("SELECT * FROM `".$productTable."` WHERE id='".(int)$_POST["product"]["id"]."'");

    if(!$product)
    {
        throw new Exception("Selected product is not valid.");
    }


    $config_wsdl_url = get_option("tekzone_zoolz_url");
    $config_authToken = get_option("tekzone_zoolz_api_key");
    $zoolz = new ZoolzPHP($config_wsdl_url, $config_authToken);
    $zoolZResponse = $zoolz->GetAccountInfoByEmail($_POST["email"]);

    if(!isset($zoolZResponse->GetAccountInfoByEmailResult->Code))
    {
        throw new Exception("Error Processing Request");
    }

    if(strtolower($zoolZResponse->GetAccountInfoByEmailResult->Code) == "success")
    {
        throw new Exception("An account with this email address ".$_POST["email"]." already exists.".'<br/>Please <a style="color: #337ab7;" href="javascript:void(0);" onclick="openAlreadyUserLoggedInDialog();">login</a> or <a style="color: #337ab7;" href="javascript:void(0);" onclick="openForgotPasswordDialog();" >reset</a> your password', 100000000000001);
    }

    $response["responseType"] = "success";
    $response["message"] = "Account is ready to process with zoolz.";
    $response["checkoutURL"] = getCheckoutUrl()."?id=".$_POST["product"]["id"]."&email=".$_POST["email"]."&company_name=".$companyName."&current_web_url=".$_POST["tekzone_website_current_url"];
    $response["checkoutEmail"] = $_POST["email"];

    //$_SESSION["tekzone_zoolz_message"]["success"] = "Email is eligible to process with Zoolz.";
}
catch(Exception $e)
{
    $response["responseType"] = "error";
    $response["message"] = $e->getMessage();
    $response["errorHTML"] = '<ul class="danger" style="list-style-type: none; padding-inline-end: 40px;"><li><div class="alert alert-danger" role="alert">'.$e->getMessage().'</div></li></ul>';
}
ob_clean();
header('Content-Type: application/json'); 
echo json_encode($response);
die;