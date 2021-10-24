<?php global $table_prefix, $wpdb; ?>
<div class="wrap">
    <h1 class="wp-heading-inline">Zoolz Products</h1>
    <a href="<?php echo admin_url( 'admin.php?page=tekzone-zoolz-plans' ); ?>" class="page-title-action aria-button-if-js" aria-expanded="false" role="button">Fetch All Plans</a>
    <hr class="wp-header-end">

    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th width="15%">Image</th>
          <th width="10%">#</th>
          <th width="25%">Name</th>
          <th width="5%">Price</th>
          <th width="10%">Zoolz Price</th>
          <th width="10%">Status</th>
          <th width="20%">Description</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php

            $productTable = $table_prefix . 'tekzone_zoolz_products';

          $result = $wpdb->get_results("SELECT * FROM `".$productTable."` ORDER BY id ASC");
          foreach ($result as $product) { ?>
            <?php $currency='';?>
            <?php if($product->currency == "GBP"): $currency = "£"; ?>
            <?php elseif($product->currency == "EUR"): $currency = "€"; ?>
            <?php elseif($product->currency == "USD"): $currency = "$"; ?>    
            <?php endif; ?>

              <tr>
                <td width="15%">
                    <?php if($product->image): ?>
                        <img width="100" height="100" src = "<?php echo getTekzoneMediaProductURL($product->image); ?>" alt = "<?php echo $product->name ?>" />
                    <?php else:?>
                        <img width="100" height="100" src = "<?php echo getTekzoneIconsURL("palceholder.png"); ?>" alt = "<?php echo $product->name ?>" />
                    <?php endif; ?>
                </td>
                <td width="10%"><?php echo $product->id ?><br/>(<?php echo $product->plan_id ?>)</td>
                <td width="25%"><?php echo $product->name ?><br/>(<?php echo $product->zoolz_plan_name ?>)</td>
                <td width="5%"><?php echo $currency; ?><?php echo number_format((float)round($product->price_in_cents/100, 2), 2, '.', '') ?></td>
                <td width="10%"><?php echo $currency; ?><?php echo number_format((float)round($product->zoolz_price_in_cents/100, 2), 2, '.', '') ?></td>
                <td width="10%">
                    <?php if($product->status == "Active"): ?>
                        <label class="tekzoneswitch">
                          <input type="checkbox" checked="checked" onChange="tekzoneproductStatusSwitcher(this);" data-id="<?php echo $product->id; ?>">
                          <span class="tekzoneslider round"></span>
                        </label>
                    <?php else: ?>
                        <label class="tekzoneswitch">
                          <input type="checkbox" onChange="tekzoneproductStatusSwitcher(this);" data-id="<?php echo $product->id; ?>">
                          <span class="tekzoneslider round"></span>
                        </label>
                    <?php endif; ?>
                </td>
                <td width="20%"><?php echo $product->desc ?></td>
                <td>
                    <ul style="margin:0px;">
                        <li><a href="<?php echo admin_url( 'admin.php?page=tekzone-zoolz-product-view&id='.$product->id ); ?>">View</a></li>
                        <li><a href="<?php echo admin_url( 'admin.php?page=tekzone-zoolz-product-update&id='.$product->id ); ?>">Edit</a></li>
                        <li><a href="<?php echo admin_url( 'admin.php?page=tekzone-zoolz-product-delete&id='.$product->id ); ?>">Delete</a></li>
                    </ul>
                </td>
              </tr>
        <?php
          }
        ?>
      </tbody>  
    </table>
</div>
<script type="text/javascript">
    function tekzoneproductStatusSwitcher(obj)
        {
            status = 2;
            if(jQuery(obj).is(":checked"))
            {
                status = 1;
            }

            id=jQuery(obj).data("id");

            url = '<?php echo admin_url("admin-post.php"); ?>';

            jQuery.ajax({
                type: "post",
                url: url,
                data: {"action": "update_tekzone_product_status", "status": status, "id": id},
                dataType: "json",
                success: function(n) {
                    if(n.responseType == "success")
                    {
                        jQuery("#message").html(n.successHTML);
                        jQuery("#message").show();
                    }
                    else
                    {
                        jQuery("#message").html(n.errorHTML);
                        jQuery("#message").show();
                    }
                },
                error: function() {
                    //console.log("error");
                },
                complete: function() {
                    //console.log("complete");
                }
            });

        }
</script>