<?php global $table_prefix, $wpdb; ?>

<?php 


include_once __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'ZoolzPHP.php';

$config_wsdl_url = get_option("tekzone_zoolz_url");
$config_authToken = get_option("tekzone_zoolz_api_key");

$zoolz = new ZoolzPHP($config_wsdl_url, $config_authToken);
$zoolz->debug(true);
$return = $zoolz->GetPlansInfo();
$zoolzPlans = json_decode($return->{'GetPlansInfoResult'}->{'JSON'} );


$productTable = $table_prefix . 'tekzone_zoolz_products';

$existingPlans = $wpdb->get_col("SELECT plan_id FROM `".$productTable."`");
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Zoolz Plans</h1>
    <a href="<?php echo admin_url( 'admin.php?page=zoolz-products' ); ?>" class="page-title-action aria-button-if-js" aria-expanded="false" role="button">Back</a>
    <hr class="wp-header-end">
    <table class="wp-list-table widefat striped">
      <thead>
        <tr>
          <th width="5%">#</th>
          <th width="15%">Name</th>
          <th width="5%">HotCapacity</th>
          <th width="5%">ColdCapacity</th>
          <th width="5%">Users</th>
          <th width="10%">Type</th>
          <th width="10%">SubFreq</th>
          <th width="15%">CreateDate</th>
          <th width="10%">Cost</th>
          <th width="5%">TrialPeriod</th>
          <th width="15%">DB Refresh</th>
        </tr>
      </thead>
      <tbody>
        <?php
          foreach ($zoolzPlans as $_plan) { ?>

              <?php $newPlansToBeInserted = array(); ?>
              <tr>
                <td width="5%"><?php echo $_plan->ID ?></td>
                <td width="15%"><?php echo $_plan->Name ?></td>
                <td width="5%"><?php echo $_plan->HotCapacity ?></td>
                <td width="5%"><?php echo $_plan->ColdCapacity ?></td>
                <td width="5%"><?php echo $_plan->Users ?></td>
                <td width="10%"><?php echo $_plan->Type ?></td>
                <td width="10%"><?php echo $_plan->SubFreq ?></td>
                <td width="15%"><?php echo $_plan->CreateDate ?></td>
                <td width="10%"><?php echo $_plan->Cost ?></td>
                <td width="10%"><?php echo $_plan->TrialPeriod ?></td>
                <td width="10%">
                    <?php if(in_array($_plan->ID, $existingPlans)): ?>
                            Plan already exists.
                    <?php else: 

                        $newPlansToBeInserted["name"] = $_plan->Name;
                        $newPlansToBeInserted["zoolz_plan_name"] = $_plan->Name;
                        $newPlansToBeInserted["price_in_cents"] = (float)substr($_plan->Cost,8)*100;
                        $newPlansToBeInserted["zoolz_price_in_cents"] = $newPlansToBeInserted["price_in_cents"]*1.5;
                        $newPlansToBeInserted["plan_id"] = $_plan->ID;
                        $newPlansToBeInserted["currency"] = 'GBP';
                        $newPlansToBeInserted["status"] = 'Inactive';
                        $newPlansToBeInserted["desc"] = 'Users: '.$_plan->Users.'<br>Type: '.$_plan->Type.'<br>Capacity: '.$_plan->HotCapacity.' :: '.$_plan->ColdCapacity;

                        $wpdb->insert( $productTable, $newPlansToBeInserted, array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));

                        if($wpdb->insert_id)
                        {
                            echo "Records created successfully.";
                        }
                        else
                        {
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                    endif; ?>

                </td>
              </tr>
        <?php
          }
        ?>
      </tbody>  
    </table>
</div>