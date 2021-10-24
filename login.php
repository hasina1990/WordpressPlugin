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
    if(!isset($_POST["customer_login_email"]))
    {
        throw new Exception("inavlid data posted.");
    }
    elseif(!$_POST["customer_login_email"] = trim($_POST["customer_login_email"]))
    {
        throw new Exception("Please enter email address.");
    }
    elseif(!filter_var($_POST["customer_login_email"], FILTER_VALIDATE_EMAIL))
    {
        throw new Exception("Please enter email address in valid format.");
    }

    if(!isset($_POST["customer_login_password"]))
    {
        throw new Exception("inavlid data posted.");
    }
    elseif(!$_POST["customer_login_password"] = trim($_POST["customer_login_password"]))
    {
        throw new Exception("Please enter password.");
    }

    $config_wsdl_url = get_option("tekzone_zoolz_url");
    $config_authToken = get_option("tekzone_zoolz_api_key");
    $zoolz = new ZoolzPHP($config_wsdl_url, $config_authToken);

    $zoolZResponse = $zoolz->GetAccountInfoUsingEmailAndPass($_POST["customer_login_email"], $_POST["customer_login_password"]);

    if(!isset($zoolZResponse->GetAccountInfoByEmailAndPassResult->Code))
    {
        throw new Exception("Error Processing Request");
    }

    if(strtolower($zoolZResponse->GetAccountInfoByEmailAndPassResult->Code) !== "success")
    {
        throw new Exception("Authentication is not successfull.");
    }

    $_SESSION["tekzone_zoolz_success_payment_on_zoolz"] = 1;
    $_SESSION["tekzone_zoolz_success_login_on_zoolz"] = 1;

    $response["responseType"] = "success";
    $response["message"] = "Authentication is successfull.";

    if(isset($_POST["tekzone_website_current_url"]) && $_POST["tekzone_website_current_url"]= trim($_POST["tekzone_website_current_url"]))
    {
        $response["redirectUrl"] = $_POST["tekzone_website_current_url"];
    }
    else
    {
        $response["redirectUrl"] = get_site_url();
    }
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