<div class="tekzone-zoolz-content section-inner">
<?php global $table_prefix, $wpdb; ?>
<?php
try {
     
    $productTable = $table_prefix . 'tekzone_zoolz_products';
    $products = $wpdb->get_results("SELECT * FROM `".$productTable."` WHERE status=1 ORDER BY id ASC");

    if(!count($products))
    {
        throw new Exception("No Zoolz Plans are available yet to proceed checkout.");
    }
?>
 
<div class="container">
    <div id="loader-parent" class="loader-parent" style="display: none;">
        <div id="loader" class="loader"></div>
    </div>

    <?php include_once 'validateEmailPopup.php'; ?>
    <?php include_once 'loginPopupContent.php'; ?>
    <?php include_once 'forgotPasswordPopupContent.php'; ?>

    <?php if(isset($_SESSION["tekzone_zoolz_success_payment_on_zoolz"]) && ((int)$_SESSION["tekzone_zoolz_success_payment_on_zoolz"] == 1)): unset($_SESSION["tekzone_zoolz_success_payment_on_zoolz"]);?>
        <?php
            ob_start();
            include_once 'successPopupContent.php';
            $message = ob_get_contents();
            ob_end_clean();

            echo $message;
        ?>
    <?php endif; ?>

    <div class="row">
        <div class="col-12">
            <h1 style="text-align: center; color: #27324E;">Choose your backup solution</h1>  
        </div>
        <div class="col-12">
            <p style="text-align: center; color: #27324E; font-size: 20px;"><a href="javascript:void(0);" onclick="openAlreadyUserLoggedInDialog();">Existing user?</a></p>
        </div>
    </div> 

    <div class="row">
        <div class="col-12">
            <div id="message" class="messages">
                <?php if(isset($_SESSION["tekzone_zoolz_message"]["success"]) && $_SESSION["tekzone_zoolz_message"]["success"] = trim($_SESSION["tekzone_zoolz_message"]["success"])):?>
                <ul class="success" style="list-style-type: none; padding-inline-end: 40px;"><li><div class="alert alert-success" role="alert"><?php echo $_SESSION["tekzone_zoolz_message"]["success"]; ?></div></li></ul>
                <?php unset($_SESSION["tekzone_zoolz_message"]["success"]); ?>
                <?php endif; ?>

                <?php if(isset($_SESSION["tekzone_zoolz_message"]["error"]) && $_SESSION["tekzone_zoolz_message"]["error"] = trim($_SESSION["tekzone_zoolz_message"]["error"])):?>
                <ul class="danger" style="list-style-type: none; padding-inline-end: 40px;"><li><div class="alert alert-danger" role="alert"><?php echo $_SESSION["tekzone_zoolz_message"]["error"]; ?></div></li></ul>
                <?php unset($_SESSION["tekzone_zoolz_message"]["error"]); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row">

        <?php foreach ($products as $rowCount => $product): ?>

            <?php if($rowCount%3==0 && $rowCount!=0): ?>
                <div class="clearfix"></div>
            <?php endif; ?>

            <div class="col-xs-12 col-sm-4 col-lg-4 text-center product-grid-item" style="margin-bottom: 20px;">
                <div style="display: inline-block;">
                    <?php if($product->image): ?>
                        <?php $imageSrc = getTekzoneMediaProductURL($product->image); ?>
                        <img onclick="customer.setProductId('<?php echo $product->id; ?>').setProductImage('<?php echo $imageSrc; ?>').setProductName('<?php echo $product->name; ?>').setProductPrice('<?php echo $price; ?>').setProductDescription('<?php echo $product->desc; ?>').proceedToCheckout();" height="auto" style="width: 200px;" src="<?php echo getTekzoneMediaProductURL($product->image); ?>" class="img-pad" alt="<?php echo $product->name; ?>">
                    <?php else: ?>
                        <?php $imageSrc = getTekzoneIconsURL("palceholder.png"); ?>
                        <img onclick="customer.setProductId('<?php echo $product->id; ?>').setProductImage('<?php echo $imageSrc; ?>').setProductName('<?php echo $product->name; ?>').setProductPrice('<?php echo $price; ?>').setProductDescription('<?php echo $product->desc; ?>').proceedToCheckout();" height="auto" style="width: 200px;" src="<?php echo getTekzoneIconsURL("palceholder.png"); ?>" class="img-pad" alt="<?php echo $product->name; ?>">
                    <?php endif; ?>
                </div>
                <br>
                <h3 onclick="customer.setProductId('<?php echo $product->id; ?>').setProductImage('<?php echo $imageSrc; ?>').setProductName('<?php echo $product->name; ?>').setProductPrice('<?php echo $price; ?>').setProductDescription('<?php echo $product->desc; ?>').proceedToCheckout();"><?php echo $product->name; ?></h3>
                <!-- <p style="text-align: inherit; font-size: 20px;"><?php //echo $product->name; ?></p> -->
                <?php
                    if ($product->currency == 'GBP'){
                        $currency_symbol = '£';
                    }elseif ($product->currency == 'USD'){
                        $currency_symbol = '$';
                    }elseif ($product->currency == 'EUR'){
                        $currency_symbol = '€';
                    }

                    $price = $currency_symbol."".number_format((float)round(($product->price_in_cents / 100), 2), 2, '.', '');
                ?>
                <p style="text-align: inherit; font-size: 20px;"><h2><?php echo $price; ?></h2></p>
                <p style="text-align: inherit; font-size: 20px; min-height: 100px;"><?php echo $product->desc; ?></p>
                <p class="form-submit">
                    <input type="button" name="Proceed to Checkout" value="Proceed to Checkout" onclick="customer.setProductId('<?php echo $product->id; ?>').setProductImage('<?php echo $imageSrc; ?>').setProductName('<?php echo $product->name; ?>').setProductPrice('<?php echo $price; ?>').setProductDescription('<?php echo $product->desc; ?>').proceedToCheckout();" class="button submit">
                </p>   
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php  } catch (Exception $e) {
?>

<div class="row">
    <div class="col-12">
        <p style="text-align: center; color: #27324E;"><?php echo $e->getMessage(); ?></p>  
    </div>
</div>

<?php
     
 } 
   ?>

</div>