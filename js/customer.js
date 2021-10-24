jQuery.ajaxSetup({
    data: {
        ajax: "1"
    }
});

var HP = {};

HP.Customer = function(){};

HP.Customer.prototype = {

    _emailDialog:null,
    _productName:null,
    _productId:null,
    _productPrice:null,
    _productDesc:null,
    _productImage:null,

    setProductId: function(id)
    {
        this._productId = id;
        return this;
    },

    setProductImage: function(images)
    {
        this._productImage = images;
        return this;
    },

    setProductDescription: function(description)
    {
        this._productDesc = description;
        return this;
    },

    setProductPrice: function(price)
    {
        this._productPrice = price;
        return this;
    },

    setProductName: function(name)
    {
        this._productName = name;
        return this;
    },

    initiate: function()
    {
        var current =this;

        button = jQuery("#message");

        this._emailDialog = jQuery( "#tekzone-zoolz-customer-email-dialog" ).dialog({
                              autoOpen: false,
                              height: 'auto',
                              minHeight: 385,
                              minWidth: 650,
                              maxWidth: 800,
                              modal: true,
                              fluid: true,
                              modal: true,
                              dialogClass: 'customerValidateEmailModel',
                              closeOnEscape: false,
                              draggable:false,
                              resizable:false,
                              buttons: {
                                "Buy Now": current.validateProceedCheckoutForm,
                                Cancel: function() {
                                  current._emailDialog.dialog( "close" );
                                }
                              },
                              close: function() {}
                            });
    },

    validateProceedCheckoutForm:function(){
        jQuery("#tekzone-zoolz-customer-email-dialog-form").submit();
        return false;
    },

    proceedToCheckout: function()
    {
        jQuery("#tekzone_website_current_url_validate_email").val(window.location.href);
        jQuery("#customer-product-image").attr("src", this._productImage);
        jQuery("#customer-product-info").html("<b>"+this._productName+"</b>");
        jQuery("#customer-product-price").html(this._productPrice);
        jQuery("#customer-product-description").html(this._productDesc);
        jQuery("#customer-product-id").val(this._productId);
        this._emailDialog.dialog( "open" );
    },

    updateTips: function(t)
    {
        jQuery("#tekzone-zoolz-customer-email-dialog-message-error").text(t);
        jQuery("#tekzone-zoolz-customer-email-dialog-message-error").parent().parent().show();
    },

    checkLength: function(o, n, min, max)
    {
        if(o.val().length == 0)
        {
            o.addClass( "ui-state-error" );
            customer.updateTips( "Please Enter Email address." );
            return false;
        } else {
            return true;
          }
    },

    checkRegexp: function(o, regexp, n)
    {
        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass( "ui-state-error" );
            customer.updateTips( n );
            return false;
          } else {
            return true;
          }
    },

    buyNow: function()
    {
          var valid = true;

          email = jQuery( "#customer-email" );

          email.removeClass( "ui-state-error" );

         emailRegex = /^[a-zA-Z0-9.!#jQuery%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*jQuery/;
     
          valid = valid && customer.checkLength( email, "email", 6, 80 );
          valid = valid && customer.checkRegexp( email, emailRegex, "Please enter valid email address. eg. example@xyz.com" );
     
          if ( valid ) {

            jQuery("#tekzone-zoolz-customer-email-dialog-message-error").html("");
            jQuery("#tekzone-zoolz-customer-email-dialog-message-error").parent().parent().hide();

            jQuery("#loader-parent").show();

            form = jQuery("#tekzone-zoolz-customer-email-dialog-form");

            jQuery.ajax({
                type: form.attr("method"),
                url: form.attr("action"),
                data: form.serialize(),
                dataType: "json",
                success: function(n) {
                    if(n.responseType == "success")
                    {
                        window.location.replace(n.checkoutURL);
                    }
                    else if(n.responseType == "error")
                    {
                        jQuery("#tekzone-zoolz-customer-email-dialog-message-error").html(n.message);
                        jQuery("#tekzone-zoolz-customer-email-dialog-message-error").parent().parent().show();
                        jQuery("#loader-parent").hide();
                    }
                },
                error: function() {
                    console.log("error");
                },
                complete: function() {
                    console.log("complete");
                }
            });
          }

          return valid;
    }
};


var customer = new HP.Customer();

jQuery(document).ready(function(){
   customer.initiate(); 
});



jQuery(document).ready(function(){

    /*jQuery.validator.addMethod("customerloginpasswordcheck", function(value) {
       return /^[A-Za-z0-9\d=!\-@._*]*jQuery/.test(value) // consists of only these
           && /[a-z]/.test(value) // has a lowercase letter
           && /\d/.test(value) // has a digit
    });   */ 

    jQuery("#tekzone-zoolz-customer-email-dialog-form").validate({
            rules:{
                email:{required:true,email:true},
            },
            submitHandler: function(form) {

                jQuery("#tekzone-zoolz-customer-email-dialog-message-error").html("");
                jQuery("#tekzone-zoolz-customer-email-dialog-message-error").parent().parent().hide();

                jQuery("#loader-parent").show();

                form = jQuery("#tekzone-zoolz-customer-email-dialog-form");

                jQuery.ajax({
                    type: form.attr("method"),
                    url: form.attr("action"),
                    data: form.serialize(),
                    dataType: "json",
                    success: function(n) {
                        if(n.responseType == "success")
                        {
                            window.location.replace(n.checkoutURL);
                        }
                        else if(n.responseType == "error")
                        {
                            jQuery("#tekzone-zoolz-customer-email-dialog-message-error").html(n.message);
                            jQuery("#tekzone-zoolz-customer-email-dialog-message-error").parent().parent().show();
                            jQuery("#loader-parent").hide();
                        }
                    },
                    error: function() {
                        console.log("error");
                    },
                    complete: function() {
                        console.log("complete");
                    }
                });
            }
        });

    jQuery("#tekzone-zoolz-customer-login-dialog-form").validate({
            rules:{
                customer_login_email:{required:true,email:true},
                customer_login_password:{required: true,/*customerloginpasswordcheck: true,*/minlength: 8}
            },
            messages:{
                customer_login_password: {
                    customerloginpasswordcheck: "The password does not meet the criteria!"
                }
            },
            submitHandler: function(form) {

                jQuery("#loader-parent").show();

                form = jQuery("#tekzone-zoolz-customer-login-dialog-form");

                jQuery.ajax({
                    type: form.attr("method"),
                    url: form.attr("action"),
                    data: form.serialize(),
                    dataType: "json",
                    success: function(n) {
                        if(n.responseType == "success")
                        {
                            window.location.replace(n.redirectUrl);
                        }
                        else if(n.responseType == "error")
                        {
                            jQuery("#tekzone-zoolz-customer-login-dialog-message-error").html(n.message);
                            jQuery("#tekzone-zoolz-customer-login-dialog-message-error").parent().parent().show();
                            jQuery("#loader-parent").hide();
                        }
                    },
                    error: function() {
                        console.log("error");
                    },
                    complete: function() {
                        console.log("complete");
                    }
                });
            }
        });

    jQuery("#tekzone-zoolz-customer-forgot-password-dialog-form").validate({
            rules:{
                customer_forgot_password_email:{required:true,email:true},
            },
            submitHandler: function(form) {
                jQuery("#loader-parent").show();

                form = jQuery("#tekzone-zoolz-customer-forgot-password-dialog-form");

                jQuery.ajax({
                    type: form.attr("method"),
                    url: form.attr("action"),
                    data: form.serialize(),
                    dataType: "json",
                    success: function(n) {
                        if(n.responseType == "success")
                        {
                            window.location.replace(n.redirectUrl);
                        }
                        else if(n.responseType == "error")
                        {
                            jQuery("#tekzone-zoolz-customer-forgot-password-dialog-message-error").html(n.message);
                            jQuery("#tekzone-zoolz-customer-forgot-password-dialog-message-error").parent().parent().show();
                            jQuery("#loader-parent").hide();
                        }
                    },
                    error: function() {
                        console.log("error");
                    },
                    complete: function() {
                        console.log("complete");
                    }
                });
            }
        });
});

jQuery(window).resize(function () {
    fluidDialog();
});

// catch dialog if opened within a viewport smaller than the dialog width
jQuery(document).on("dialogopen", ".ui-dialog", function (event, ui) {
    fluidDialog();
});

function fluidDialog() {
    var jQueryvisible = jQuery(".ui-dialog:visible");
    // each open dialog
    jQueryvisible.each(function () {
        var jQuerythis = jQuery(this);
        var dialog = jQuerythis.find(".ui-dialog-content").data("ui-dialog");
        // if fluid option == true
        if (dialog.options.fluid) {
            var wWidth = jQuery(window).width();
            // check window width against dialog width
            if (wWidth < (parseInt(dialog.options.maxWidth) + 50))  {
                // keep dialog from filling entire screen
                jQuerythis.css("max-width", "90%");
            } else {
                // fix maxWidth bug
                jQuerythis.css("max-width", dialog.options.maxWidth + "px");
            }
            //reposition dialog
            dialog.option("position", dialog.options.position);
        }
    });

}

function closCustomerSuccessfullPaymentDialog()
{
    jQuery( "#tekzone-zoolz-customer-successfull-payment-dialog" ).dialog( "close" );
}

function openAlreadyUserLoggedInDialog()
{
    jQuery("#tekzone_website_current_url_login").val(window.location.href);
    jQuery( "#tekzone-zoolz-customer-login-dialog" ).dialog({
          autoOpen: true,
          dialogClass: 'customerLoggedInModel',
          height: 'auto',
          minHeight: 385,
          minWidth: 500,
          maxWidth: 800,
          modal: true,
          fluid: true,
          closeOnEscape: false,
          draggable:false,
          resizable:false,
          buttons: {
                    Login: alreadyUserLoggedIn,
                    Cancel: closAlreadyUserLoggedInDialog
                  },
          open: function( event, ui ) {},
          close: function() {}
        });
}

function closAlreadyUserLoggedInDialog()
{
    jQuery( "#tekzone-zoolz-customer-login-dialog" ).dialog( "close" );
}

function alreadyUserLoggedIn()
{
    jQuery("#tekzone-zoolz-customer-login-dialog-form").submit();
    return false;
}

function openForgotPasswordDialog()
{
    if(jQuery( "#tekzone-zoolz-customer-login-dialog" ).hasClass("ui-dialog-content"))
    {
        jQuery( "#tekzone-zoolz-customer-login-dialog" ).dialog( "close" );
    }

    jQuery("#tekzone_website_current_url_forgot_password").val(window.location.href);

    jQuery( "#tekzone-zoolz-customer-forgot-password-dialog" ).dialog({
          autoOpen: true,
          dialogClass: 'customerForgotPasswordModel',
          height: 'auto',
          minHeight: 385,
          minWidth: 500,
          maxWidth: 800,
          modal: true,
          fluid: true,
          closeOnEscape: false,
          draggable:false,
          resizable:false,
          buttons: {
                    resetPassword:{ 
                        text: 'Reset Password',
                        click : getResetPasswordLink
                    },
                    Cancel: closForgotPasswordDialog
                  },
          open: function( event, ui ) {},
          close: function() {}
        });
}

function closValidateEmailDialog()
{
    jQuery( "#tekzone-zoolz-customer-email-dialog" ).dialog( "close" );
}

function closForgotPasswordDialog()
{
    jQuery( "#tekzone-zoolz-customer-forgot-password-dialog" ).dialog( "close" );
}

function getResetPasswordLink()
{
    jQuery("#tekzone-zoolz-customer-forgot-password-dialog-form").submit();
    return false;
}

jQuery(document).ready(function(){
    setTimeout(function(){
        if(jQuery(".tekzone-zoolz-content").parent().hasClass("entry-content"))
        {
            jQuery(".tekzone-zoolz-content").parent().removeClass("entry-content");
        }
    }, 10);
    setTimeout(function(){ 
        jQuery( "#tekzone-zoolz-customer-successfull-payment-dialog" ).dialog({
                          autoOpen: true,
                          dialogClass: 'successfullPaymentModel',
                          height: 'auto',
                          minHeight: 385,
                          width: 'auto',
                          maxWidth: 800,
                          modal: true,
                          fluid: true,
                          closeOnEscape: false,
                          draggable:false,
                          resizable:false,
                          buttons: {},
                          open: function( event, ui ) {},
                          close: function() {}
                        });

     }, 1000);
    });