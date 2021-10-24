<div class="wrap">
    <h1 id="add-new-user">View Zoolz Product</h1>
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

    ?>


    <table class="wp-list-table widefat striped" role="presentation">
        <tbody>
            <tr class="form-field form-required">
                <th scope="row"><b>Name</b></th>
                <td><p><?php echo $product->name; ?></p></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Image</b></th>
                <td>
                    <?php if($product->image): ?>
                        <img width="100" height="100" src = "<?php echo getTekzoneMediaProductURL($product->image); ?>" alt = "<?php echo $product->name ?>" />
                    <?php else:?>
                        <p><?php echo "No image uploaded."; ?></p>
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Price</b></th>
                <td><p><?php echo round($product->price_in_cents/100, 2); ?></p></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Zoolz Price</b></th>
                <td><p><?php echo round($product->zoolz_price_in_cents/100, 2); ?></p></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Plan ID</b></th>
                <td><p><?php echo $product->plan_id; ?></p></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Currency</b></th>
                <td><p>
                    <?php if($product->currency == "GBP"): ?>British Pound (£)
                    <?php elseif($product->currency == "EUR"): ?>Euro (€)
                    <?php elseif($product->currency == "USD"): ?>US Doller ($)    
                    <?php endif; ?>
                </p></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Status</b></th>
                <td><p><?php echo $product->status; ?></p></td>
            </tr>
            <tr class="form-field form-required">
                <th scope="row"><b>Description</b></th>
                <td><p><?php echo $product->desc; ?></p></td>
            </tr>
        </tbody>
    </table>

    <?php
}
catch(Exception $e)
{
?>
<div class="wp-die-message">Page Request is not valid.</div>
<?php
}

?>



    
<p class="submit"><a href="<?php echo admin_url( 'admin.php?page=zoolz-products' ); ?>" class="page-title-action aria-button-if-js" aria-expanded="false" role="button">Go Back</a></p>
</div>