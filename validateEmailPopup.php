<div id="tekzone-zoolz-customer-email-dialog" title="Proceed to Checkout" class="modal" style="background: white;padding: 40px;"> 

    <div style="background: white; margin: 0 auto;">

        <div style="border-radius:100px; height:100px; width:100px; /*background: #F8FAF5;*/ margin:0 auto;">
            <img src="<?php echo get_option("tekzone_company_logo"); ?>" style="margin-left: -8px;">
        </div>

        <h1 class="customerValidateEmailModelTitle" style="margin-top: 0px;">Proceed To Checkout</h1> 

        <button onclick="closValidateEmailDialog();" type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close" style="right: .5em; top: 15px;" ><span class="ui-button-icon ui-icon ui-icon-closethick"></span><span class="ui-button-icon-space"> </span>Close</button>

        <div id="tekzone-zoolz-customer-email-dialog-message" class="messages">
            <ul class="success" style="list-style-type: none; padding-inline-end: 5px;padding-inline-start:5px;display: none;"><li><div class="alert alert-success" id="tekzone-zoolz-customer-email-dialog-message-success" role="alert">success</div></li></ul>
            <ul class="danger" style="list-style-type: none; padding-inline-end: 5px;padding-inline-start:5px;display: none;"><li><div class="alert alert-danger" id="tekzone-zoolz-customer-email-dialog-message-error" role="alert">error</div></li></ul>
        </div>

        <div class="customer-product-info">
            <div class="customer-product-info-left">
                <img id="customer-product-image" src="<?php echo getTekzoneIconsURL("palceholder.png"); ?>" width="200px" height="200px">
            </div>
            <div class="customer-product-info-right">
                <div id="customer-product-info" style="display: inline-block;margin-top: 5%;text-align: inherit; font-size: 20px;"></div>
                <div id="customer-product-price" style="margin-top:10px;margin-bottom: 5px;font-size:30px;font-weight:550;line-height:1.1;"></div>
                <div id="customer-product-description" style="display: inline-block;text-align: inherit; font-size: 20px;"></div>
            </div>
        </div>
        <div style="clear: both;padding-bottom:25px;"></div>

          <form id="tekzone-zoolz-customer-email-dialog-form" method="post" action="<?php echo getValidateEmailPostUrl(); ?>" name="tekzone-zoolz-customer-email-dialog-form">
            <input type="hidden" name="action" value="post_new_thing">
            <input type="hidden" name="product[id]" value="0" id="customer-product-id">
            <input type="hidden" name="tekzone_website_current_url" value="" id="tekzone_website_current_url_validate_email">

            <div class="form-group">
                <label>Email</label>
                <input id="customer-email" type="text" name="email" class="form-control" value="" />
            </div>

            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" id="customer-company-name" value="" class="form-control" >
            </div>

            <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
          </form>
    </div>
</div>