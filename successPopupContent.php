<div id="tekzone-zoolz-customer-successfull-payment-dialog" title="Successfull Registration" class="modal" style="background: white; text-align: center;overflow: hidden;padding: 50px;"> 
    <div style="background: white; margin: 0 auto;">
        <button onclick="closCustomerSuccessfullPaymentDialog();" type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close" style="right: .5em; top: 15px;" ><span class="ui-button-icon ui-icon ui-icon-closethick"></span><span class="ui-button-icon-space"> </span>Close</button>
          
        <div style="border-radius:100px; height:100px; width:100px; /*background: #F8FAF5;*/ margin:0 auto;">
            <img src="<?php echo get_option("tekzone_company_logo"); ?>" style="margin-left: -8px;">
        </div>

        <h1 class="successfullPaymentTitle">Congratulations!</h1> 

        <?php if(isset($_SESSION["tekzone_zoolz_success_login_on_zoolz"]) && $_SESSION["tekzone_zoolz_success_login_on_zoolz"]==1): ?>
            <p style='color: #404F5E;font-family: "Nunito Sans", "Helvetica Neue", sans-serif;font-size:20px;margin: 0;'>You have successfully logged in.</p>
            <?php unset($_SESSION["tekzone_zoolz_success_login_on_zoolz"]); ?>
        <?php else: ?>
            <p style='color: #404F5E;font-family: "Nunito Sans", "Helvetica Neue", sans-serif;font-size:20px;margin: 0;'>Your account has been created successfully.</p>
        <?php endif; ?>
        
        <p style='color: #404F5E;font-family: "Nunito Sans", "Helvetica Neue", sans-serif;font-size:20px;margin: 0;margin-bottom: 20px;'>Click an image to download your backup program.</p>
        <div class="row">
          <div class="column">
            <img src="<?php echo getTekzoneIconsURL('windows.png'); ?>"><br/><button class="button submit"><a href='<?php echo getDownloadSetupsonSuccessPageURL(); ?>?setup=windows' target="_blank" style="color: #fff; text-decoration: none;">Download</a></button>    
          </div>
          <div class="column">
            <img src="<?php echo getTekzoneIconsURL('mac.png'); ?>"><br/><button class="button submit"><a href='<?php echo getDownloadSetupsonSuccessPageURL(); ?>?setup=mac' target="_blank" style="color: #fff; text-decoration: none;">Download</a></button>
          </div>
          <div class="column">
            <img src="<?php echo getTekzoneIconsURL('android.png'); ?>"><br/><button class="button submit"><a href='<?php echo getDownloadSetupsonSuccessPageURL(); ?>?setup=android' target="_blank" style="color: #fff; text-decoration: none;">Download</a></button>
          </div>
          <div class="column">
            <img src="<?php echo getTekzoneIconsURL('ios.png'); ?>"><br/><button class="button submit"><a href='<?php echo getDownloadSetupsonSuccessPageURL(); ?>?setup=ios' target="_blank" style="color: #fff; text-decoration: none;">Download</a></button>
          </div>
        </div>
    </div>
</div>