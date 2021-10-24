<div class="wrap">
    <h1 id="add-new-user">Delete Zoolz Product</h1>
<?php 

global $table_prefix, $wpdb;

try
{
    if(!isset($_GET["id"]))
    {
        throw new Exception("Invalid request url.");
    }

    $productTable = $table_prefix . 'tekzone_zoolz_products';
    $product = $wpdb->get_row("SELECT * FROM `".$productTable."` WHERE id='".(int)$_GET["id"]."'");

    if(!$product)
    {
        throw new Exception("Invalid request url.");
    }


    if(isset($_POST["id"]) && count($_POST)>0)
    {
        $wpdb->delete( $productTable, array( 'id' => $product->id ) );
        ?>

<script type="text/javascript">window.location.href='<?php echo admin_url( 'admin.php?page=zoolz-products' );?>'</script>
        <?php
        exit;
    }


    ?>
<div class="welcome-panel" style="color: #721c24;background-color: #f8d7da;border-color: #f5c6cb;">

   <form  method="post">
            <input type="hidden" name="id" value="<?php echo $product->id; ?>"/>
            <h2>Are you sure you want to delete this plan record?</h2>
            <p>
                <input type="submit" value="Yes" class="button button-primary button-hero" style="color: #fff;background-color: #dc3545;border-color: #dc3545;cursor: pointer;">
                <a href="<?php echo admin_url( 'admin.php?page=zoolz-products' ); ?>" class="button button-primary button-hero" style="color: #fff;background-color: #6c757d;border-color: #6c757d;" role="button">No</a>
            </p>
    </form>
</div>

    <?php
}
catch(Exception $e)
{
?>
<div class="wp-die-message">Page Request is not valid.</div>
<p class="submit"><a href="<?php echo admin_url( 'admin.php?page=zoolz-products' ); ?>" class="page-title-action aria-button-if-js" aria-expanded="false" role="button">Go Back</a></p>
<?php
}

?>
</div>