<?php
require __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'wp-load.php';
nocache_headers();
?>
<?php global $table_prefix, $wpdb; ?>

<?php

$_SESSION["tekzone_zoolz_message"]["error"] = "You cancelled your order!, Did you forget a product. Please select and continue to shopping.";

if(isset($_GET["current_web_url"]) && $_GET["current_web_url"]=trim($_GET["current_web_url"]))
{
    header("Location: ".$_GET["current_web_url"]);
}
else
{
    header("Location: ".get_site_url());
}

?>