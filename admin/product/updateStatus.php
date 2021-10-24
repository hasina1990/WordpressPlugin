<?php 
global $table_prefix, $wpdb;
try
{
    if(!isset($_POST["id"]))
    {
        throw new Exception("invalid request URL.");
    }
    else if(!(int)$_POST["id"])
    {
        throw new Exception("invalid request URL.");
    }

    if(!isset($_POST["status"]))
    {
        throw new Exception("invalid request URL.");
    }
    else if(!(int)$_POST["status"])
    {
        throw new Exception("invalid request URL.");
    }

    $productTable = $table_prefix . 'tekzone_zoolz_products';
    $product = $wpdb->get_row("SELECT * FROM `".$productTable."` WHERE id='".(int)$_POST["id"]."'");
    if(!$product->id)
    {
        throw new Exception("Product id is not valid.");   
    }

    $status = "Inactive";
    if($_POST["status"] == 1)
    {
        $status = "Active";
    }

    $sql = "UPDATE `".$productTable."` SET status='".$status."' WHERE id='".$product->id."'";
    $result = $wpdb->query($wpdb->prepare($sql));

    $response["responseType"] = "success";
    $response["message"] = "Product Status is updated successfully.";
    $response["successHTML"] = '<ul class="success" style="list-style-type: none; padding-inline-end: 40px;"><li><div class="alert alert-success" role="alert">Product Status is updated successfully.</div></li></ul>';
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
?>