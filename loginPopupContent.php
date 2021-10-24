<div id="tekzone-zoolz-customer-login-dialog" title="Already user?" class="modal" style="background: white; overflow: hidden;"> 

    <div style="background: white; margin: 0 auto;">

        <div style="border-radius:100px; height:100px; width:100px; /*background: #F8FAF5;*/ margin:0 auto;">
            <img src="<?php echo get_option("tekzone_company_logo"); ?>" style="margin-left: -8px;">
        </div>

        <h1 class="customerLoggedInModelTitle" style="margin-top: 0px;">Sign In</h1> 

        <button onclick="closAlreadyUserLoggedInDialog();" type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close" title="Close" style="right: .5em; top: 15px;" ><span class="ui-button-icon ui-icon ui-icon-closethick"></span><span class="ui-button-icon-space"> </span>Close</button>

        <div id="tekzone-zoolz-customer-login-dialog-message" class="messages">
            <ul class="success" style="list-style-type: none; padding-inline-end: 5px;padding-inline-start:5px;display: none;"><li><div class="alert alert-success" id="tekzone-zoolz-customer-login-dialog-message-success" role="alert">success</div></li></ul>
            <ul class="danger" style="list-style-type: none; padding-inline-end: 5px;padding-inline-start:5px;display: none;"><li><div class="alert alert-danger" id="tekzone-zoolz-customer-login-dialog-message-error" role="alert">error</div></li></ul>
        </div>

          <form id="tekzone-zoolz-customer-login-dialog-form" method="post" action="<?php echo getPostLogintUrl(); ?>" name="tekzone-zoolz-customer-login-dialog-form">

                <input type="hidden" name="action" value="post_new_thing">
                <input type="hidden" name="tekzone_website_current_url" value="" id="tekzone_website_current_url_login">

                <div class="form-group">
                    <label>Email</label>
                    <input id="customer-login-email" type="text" name="customer_login_email" class="form-control" value="" />
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input id="customer-login-password" type="password" name="customer_login_password" value="" class="form-control" >
                </div>

                <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
          </form>

          <div style="margin-top: 10px;display: inline-block;"><a style="color: #d65050;" href='javascript:void(0);' onclick="openForgotPasswordDialog();" >Forgot Password?</a></div>
          
    </div>
</div>