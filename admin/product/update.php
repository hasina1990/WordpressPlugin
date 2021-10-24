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


    $productImages = getTekzoneMediaProductGallary();
?>

<style>
    .error
    {
        color: red;
        float:left;
    }
</style>

<div class="wrap">
    <h1 id="add-new-user">Update Records</h1>
    <div id="ajax-response"></div>
    <p>Please edit the input values and submit to update the plan.</p>

    <form action="<?php echo admin_url("admin-post.php"); ?>" method="post" name="update-product" id="update-product" class="validate" novalidate="novalidate" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_tekzone_product">

        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="product_name">Name <span class="description">(required)</span></label></th>
                    <td><input name="product_name" type="text" id="product_name" value="<?php echo $product->name; ?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">

                    <th scope="row">
                        <label for="product_image">Image <span class="description">(required)</span></label>
                    </th>                        
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <td width="25%">
                                        <?php if($product->image): ?>
                                            <img width="100" height="100" id = "product-image" src = "<?php echo getTekzoneMediaProductURL($product->image); ?>" alt = "new image" />
                                        <?php else: ?>
                                            <img width="100" height="100"  id = "product-image" src= "#" alt = "new image" style="display: none;" />
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <input type="file" class="" name="fileToUpload" id="fileToUpload"><?php if(count($productImages)>0):?><b>OR</b>&nbsp;<a class="button" style="border-color: #000;font-size: 14px;margin-bottom: 5px;margin-left: 30px;" href="javascript:void(0);" onclick="showProductGallary();">select from uploaded images</a><?php endif; ?>
                                        <input name="product_image" type="hidden" id="product-image-value" value="<?php echo $product->image; ?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                </tr>

                <tr class="form-field form-required">
                    <th scope="row"><label for="product_zoolz_price">Zoolz Price <span class="description">(required)</span></label></th>
                    <td><input name="product_zoolz_price" readonly="readonly" type="text" id="product_zoolz_price" value="<?php echo round($product->zoolz_price_in_cents / 100, 2); ?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="product_price">Price <span class="description">(required)</span></label></th>
                    <td><input name="product_price" type="text" id="product_price" value="<?php echo round($product->price_in_cents / 100, 2); ?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="product_plan_id">Plan Id <span class="description">(required)</span></label></th>
                    <td><input name="product_plan_id" readonly="readonly" type="text" id="product_plan_id" value="<?php echo $product->plan_id; ?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="product_currency">Currency <span class="description">(required)</span></label></th>
                    <td>
                        <select name="product_currency" id="product_currency">
                            <option value="GBP" <?php if($product->currency == "GBP"): ?> selected="selected"<?php endif; ?>>British Pound (£)</option>
                            <option value="EUR" <?php if($product->currency == "EUR"): ?> selected="selected"<?php endif; ?>>Euro (€)</option>            
                            <option value="USD" <?php if($product->currency == "USD"): ?> selected="selected"<?php endif; ?>>US Doller ($)</option>            
                        </select>

                        <!-- <input name="product_currency" type="text" id="product_currency" value="<?php echo $product->currency; ?>" aria-required="true" autocapitalize="none" autocorrect="off"> -->
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="product_status">Status</label></th>
                    <td>
                        <select name="product_status" id="product_status">
                            <option value="Active" <?php if($product->status == "Active"): ?> selected="selected"<?php endif; ?>>Active</option>
                            <option value="Inactive" <?php if($product->status != "Active"): ?> selected="selected"<?php endif; ?>>Inactive</option>            
                        </select>
                    </td>
                </tr>
                <tr class="form-field">
                    <th scope="row"><label for="product_desc">Plan description (Include HTML to format the text)</label></th>
                    <td>
                        <textarea name="product_desc" id="product_desc"><?php echo $product->desc; ?></textarea>
                    </td>
                </tr>  
            </tbody>
        </table>
        
        <input type="hidden" name="id" value="<?php echo $product->id; ?>"/>
        <p class="submit"><input type="submit" name="update-product" id="update-product" class="button button-primary" value="submit"><a href="<?php echo admin_url( 'admin.php?page=zoolz-products' ); ?>" class="page-title-action aria-button-if-js" aria-expanded="false" role="button">Go Back</a></p>
    </form>
</div>


<div id="product-existing-images" title="Product Images" class="modal"> 

    <div class="wrap">
        <?php $totalImages = count($productImages); ?>
        <?php if(count($productImages)>0):?>
        <table class="wp-list-table widefat striped">
            <tbody>
                <?php foreach($productImages as $count => $_image): ?>
                    <?php if($count==0):?>
                        <tr>
                    <?php elseif($count%3==0):?>
                        </tr><tr>
                    <?php endif; ?>
                    <td>
                        <img src="<?php echo $_image; ?>" class="img-pad" width="150" height="150" alt="product">
                        <br/>
                         <a class="button button-primary" href="javascript:void(0);" data-image-name="<?php echo basename($_image); ?>" data-image-filepath="<?php echo $_image; ?>" onclick="selectImage(this)" style="color: #fff; text-decoration: none;">select</a>
                    </td>
                    <?php $count = $count+1;  ?>
                <?php endforeach; ?>

                <?php if($totalImages%3!==0):?>
                        </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>


<script type="text/javascript">

    var productGallaryDialog = null;


    jQuery("document").ready(function(){


        productGallaryDialog = jQuery( "#product-existing-images" ).dialog({
                              autoOpen: false,
                              height: 500,
                              width: 700,
                              modal: true,
                              closeOnEscape: false,
                              draggable:false,
                              resizable:false,
                              buttons: {},
                              close: function() {
                              }
                            });

        jQuery("#update-product").validate({
            rules:{
                product_name:{required:true},
               // product_image:{required:true},
                product_currency:{required:true},
                product_status:{required:true},
                product_desc:{required:true},
                product_plan_id:{
                    required: true,
                    number: true
                },
                product_price:{
                    required: true,
                    number: true,
                    min:0,
                }
            },
            submitHandler: function(form) {
               form.submit();
            }
        });
    });


function showProductGallary() {
 
   productGallaryDialog.dialog( "open" );
}

function selectImage(input) {
    jQuery('#product-image-value').val(jQuery(input).data("image-name"));
   jQuery('#product-image').attr('src', jQuery(input).data("image-filepath"));
    jQuery('#product-image').show();
    productGallaryDialog.dialog( "close" );
}   


function display(input) {
   if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(event) {
        jQuery('#product-image-value').val("newimage");
         jQuery('#product-image').attr('src', event.target.result);
         jQuery('#product-image').show();
      }
      reader.readAsDataURL(input.files[0]);
   }
}

jQuery("#fileToUpload").change(function() {
   display(this);
});

</script>

<?php
}
catch(Exception $e)
{ ?>

    <div class="wrap">
        <div class="wp-die-message">Page Request is not valid.</div>
        <p class="submit"><a href="<?php echo admin_url( 'admin.php?page=zoolz-products' ); ?>" class="page-title-action aria-button-if-js" aria-expanded="false" role="button">Go Back</a></p>
    </div>
<?php } ?>