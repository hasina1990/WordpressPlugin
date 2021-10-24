<?php
/**
 * @package Tekzone Zoolz
 * @version 1.0.0
 */
/*
Plugin Name: Tekzone Zoolz
Plugin URI: http://wordpress.org/plugins/tekzone-zoolz/
Description: description will add later
Author: Hasina Ansari
Version: 1.0.0
Author URI: https://tekzoneweb.co.in/
*/


//https://wordpress.stackexchange.com/questions/73622/add-an-admin-page-but-dont-show-it-on-the-admin-menu/203156
//https://www.davidangulo.xyz/how-to-create-crud-operations-plugin-in-wordpress/


include_once'lib'.DIRECTORY_SEPARATOR.'php-mailer'.DIRECTORY_SEPARATOR.'PHPMailer.php';
include_once'lib'.DIRECTORY_SEPARATOR.'php-mailer'.DIRECTORY_SEPARATOR.'SMTP.php';
include_once'lib'.DIRECTORY_SEPARATOR.'php-mailer'.DIRECTORY_SEPARATOR.'Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

add_action( 'init', 'tekzone_zoolz_session_start' );
add_action( 'init', 'tekzone_zoolz_frontend_urls' );

function tekzone_zoolz_session_start()
{
    if(!session_id()){
        session_start();
    }
}

function tekzone_zoolz_frontend_urls() {
    if(false !== strpos($_SERVER[ 'REQUEST_URI' ], getValidateEmailPostUrl())) {
        add_filter( 'the_posts', 'postvalidateEmail' );
    }

    if(false !== strpos($_SERVER[ 'REQUEST_URI' ], getPostLogintUrl())) {
        add_filter( 'the_posts', 'postLogin' );
    }

    if(false !== strpos($_SERVER[ 'REQUEST_URI' ], getPostForgotPasswordUrl())) {
        add_filter( 'the_posts', 'postForgotPassword' );
    }
}

function postvalidateEmail() {

    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'validateemail.php';
}

function postLogin() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'login.php';
}

function postForgotPassword() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'forgotPassword.php';
}

function getPostForgotPasswordUrl()
{
    return plugins_url( 'forgotPassword.php', __FILE__ );
}

function getPostLogintUrl()
{
    return plugins_url( 'login.php', __FILE__ );
}

function getValidateEmailPostUrl()
{
    return plugins_url( 'validateemail.php', __FILE__ );
}

function getDownloadSetupsonSuccessPageURL()
{
    return plugins_url( 'downloadSetup.php', __FILE__ );
}

function getStripePaymentSuccessURL()
{
    return plugins_url( 'success.php', __FILE__ );
}

function getStripePaymentCancelURL()
{
    return plugins_url( 'cancel.php', __FILE__ );
}


function getCheckoutUrl()
{
    return plugins_url( 'checkout.php', __FILE__ );
}

function tekzone_zoolz_product_list_frontend() {

    checkIfAllConfigurationSettingsAreInitiatedOrNot();

    wp_enqueue_script('jquery');
    wp_enqueue_script('tekzone_zoolz_jquery_ui_script', plugins_url( DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'jquery-ui.js', __FILE__ ));
    wp_enqueue_script('tekzone_zoolz_customer_script', plugins_url( DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'customer.js', __FILE__ ));
    wp_enqueue_script('jquery_validate_min_js', plugins_url( DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'jquery.validate.min.js', __FILE__ ));

    wp_enqueue_style( 'bootstrap-style', plugins_url( DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap.css', __FILE__ ) );
    wp_enqueue_style( 'jquery-ui-style', plugins_url( DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'jquery-ui.css', __FILE__ ) );
    wp_enqueue_style( 'tekzone-zoolz-style', plugins_url( DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'tekzone-zoolz.css', __FILE__ ) );
    
    ob_start();
    include_once __DIR__.DIRECTORY_SEPARATOR.'products.php';
    $message = ob_get_contents();
    ob_end_clean();
 
    return $message;
} 

add_shortcode('tekzone_zoolz_products', 'tekzone_zoolz_product_list_frontend'); 

function getTekzoneIconsURL($filename)
{
    return plugins_url( DIRECTORY_SEPARATOR.'icons'.DIRECTORY_SEPARATOR.$filename, __FILE__ );
}

function getTekzoneMediaProductURL($filename)
{
    return plugins_url( DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.$filename, __FILE__ );
}

function getTekzoneMediaProductBasePath($filename)
{
    $dir = __DIR__.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR;
    return $dir.$filename;
}

function getTekzoneMediaProductGallary()
{
    $dir = __DIR__.DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'product';

    $images = array();

    if (is_dir($dir)){
      if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
            if($file == "." || $file=="..")
            {
                continue;
            }

            $images[] = plugins_url( DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.$file, __FILE__ );
        }
        closedir($dh);
      }
    }
    
    return $images;
}


function tekzone_zoolz_head_javascripts() {
    wp_enqueue_script('jquery_tekzone_validate_script', "https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js");
    wp_enqueue_script('jquery_tekzone_ui_script', plugins_url( DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'jquery-ui.js', __FILE__ ));
    wp_enqueue_style( 'prefix-style', plugins_url( DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'jquery-ui.css', __FILE__ ) );
    wp_enqueue_style( 'tekzone-zoolz-style', plugins_url( DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'tekzone-zoolz.css', __FILE__ ) );
}


add_action('admin_enqueue_scripts', 'tekzone_zoolz_head_javascripts');

function tekzone_zoolz_admin_menu() {
    global $team_page;
    add_menu_page( __( 'Zoolz', 'tekzone-zoolz' ), __( 'Zoolz', 'tekzone-zoolz' ), 'edit_posts', 'tekzone-zoolz', '', 'dashicons-groups', 6 ) ;
    add_submenu_page('tekzone-zoolz', __('Products', 'tekzone-zoolz-products' ), __( 'Products', 'tekzone-zoolz-products' ), 'edit_posts', 'zoolz-products', 'tekzone_zoolz_products_form_page_handler', 'dashicons-groups', 1 ) ;
    add_submenu_page('tekzone-zoolz', __('Settings', 'tekzone-zoolz-setting' ), __( 'Settings', 'tekzone-zoolz-setting' ), 'edit_posts', 'zoolz-settings', 'tekzone_zoolz_setting_form_page_handler', 'dashicons-groups', 2 ) ;
    add_submenu_page('', __('Zoolz Plans', 'tekzone-zoolz-plans' ), __( 'Zoolz Plans', 'tekzone-zoolz-plans' ), 'edit_posts', 'tekzone-zoolz-plans', 'tekzone_zoolz_plans_form_page_handler', 'dashicons-groups', 2 ) ;
    add_submenu_page('', __('Zoolz Product View', 'tekzone-zoolz-product-view' ), __( 'Zoolz Product View', 'tekzone-zoolz-product-view' ), 'edit_posts', 'tekzone-zoolz-product-view', 'tekzone_zoolz_product_view_form_page_handler', 'dashicons-groups', 2 ) ;
    add_submenu_page('', __('Zoolz Product Delete', 'tekzone-zoolz-product-delete' ), __( 'Zoolz Product Delete', 'tekzone-zoolz-product-delete' ), 'edit_posts', 'tekzone-zoolz-product-delete', 'tekzone_zoolz_product_delete_form_page_handler', 'dashicons-groups', 2 ) ;
    add_submenu_page('', __('Zoolz Product Update', 'tekzone-zoolz-product-update' ), __( 'Zoolz Product Update', 'tekzone-zoolz-product-update' ), 'edit_posts', 'tekzone-zoolz-product-update', 'tekzone_zoolz_product_update_form_page_handler', 'dashicons-groups', 2 ) ;

    remove_submenu_page('tekzone-zoolz','tekzone-zoolz');
}
add_action( 'admin_menu', 'tekzone_zoolz_admin_menu' );

add_action( 'admin_post_update_tekzone_product', 'tekzone_zoolz_product_update_post_form_page_handler' );
add_action( 'admin_post_update_tekzone_product_status', 'tekzone_zoolz_product_update_status_post_form_page_handler' );
add_action( 'admin_post_update_tekzone_zoolz_setting', 'tekzone_zoolz_setting_update_post_form_page_handler' );

function tekzone_zoolz_setting_update_post_form_page_handler() {
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'updateSetting.php';
}

function tekzone_zoolz_setting_form_page_handler() {
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'settings.php';
}

function tekzone_zoolz_products_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'products.php';
}

function tekzone_zoolz_plans_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'zoolzplans.php';
}

function tekzone_zoolz_product_view_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'view.php';  
}

function tekzone_zoolz_product_delete_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'delete.php';
}


function tekzone_zoolz_product_update_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'update.php';
}

function tekzone_zoolz_product_update_post_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'updatePost.php';
}

function tekzone_zoolz_product_update_status_post_form_page_handler() {
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    include_once __DIR__.DIRECTORY_SEPARATOR.'admin'.DIRECTORY_SEPARATOR.'product'.DIRECTORY_SEPARATOR.'updateStatus.php';
}

function tekzonzoolz_activate() { 
    tekzone_initiate_database();
}

register_activation_hook( __FILE__, 'tekzonzoolz_activate' );

function tekzonzoolz_deactivate() {
    tekzone_release_database();
}

register_deactivation_hook( __FILE__, 'tekzonzoolz_deactivate' );

function tekzone_release_database()
{
    // WP Globals
    global $table_prefix, $wpdb;

    // products Table
    $customerTable = $table_prefix . 'tekzone_zoolz_products';

    // Drop products Table if  exist
    if( $wpdb->get_var( "show tables like '$customerTable'" ) == $customerTable ) {

        // Query - Drop Table
        $sql = "DROP TABLE IF EXISTS `$customerTable`;";

        $wpdb->query($sql);
    }

    // orders Table
    $customerTable = $table_prefix . 'tekzone_zoolz_orders';

    // Drop orders Table if  exist
    if( $wpdb->get_var( "show tables like '$customerTable'" ) == $customerTable ) {

        // Query - Drop Table
        $sql = "DROP TABLE IF EXISTS `$customerTable`;";

        $wpdb->query($sql);
    }

    if(get_option("tekzone_stripe_api_key"))    {
        delete_option( "tekzone_stripe_api_key");
    }

    if(get_option("tekzone_stripe_publishable_key"))    {
        delete_option( "tekzone_stripe_publishable_key");
    }

    if(get_option("tekzone_zoolz_api_key"))    {
        delete_option( "tekzone_zoolz_api_key");
    }

    if(get_option("tekzone_zoolz_url"))    {
        delete_option( "tekzone_zoolz_url" );
    }


    if(get_option("tekzone_smtp_host"))    {
        delete_option( "tekzone_smtp_host");
    }

    if(get_option("tekzone_smtp_username"))    {
        delete_option( "tekzone_smtp_username");
    }

    if(get_option("tekzone_smtp_password"))    {
        delete_option( "tekzone_smtp_password");
    }

    if(get_option("tekzone_smtp_port"))    {
        delete_option( "tekzone_smtp_port" );
    }

    if(get_option("tekzone_website_admin_email"))    {
        delete_option( "tekzone_website_admin_email" );
    }

    if(get_option("tekzone_company_name"))    {
        delete_option( "tekzone_company_name" );
    }

    if(get_option("tekzone_company_logo"))    {
        delete_option( "tekzone_company_logo" );
    }

    if(get_option("tekzone_company_sender_email"))    {
        delete_option( "tekzone_company_sender_email" );
    }

    if(get_option("tekzone_company_reply_email"))    {
        delete_option( "tekzone_company_reply_email" );
    }

    if(get_option("tekzone_signature_company_name"))    {
        delete_option( "tekzone_signature_company_name" );
    }

    if(get_option("tekzone_signature_company_logo"))    {
        delete_option( "tekzone_signature_company_logo" );
    }

    if(get_option("tekzone_signature_company_manager_name"))    {
        delete_option( "tekzone_signature_company_manager_name" );
    }
}

function tekzone_initiate_database()
{
    if(!get_option("tekzone_stripe_api_key"))    {
        add_option( "tekzone_stripe_api_key", "");
    }

    if(!get_option("tekzone_stripe_publishable_key"))    {
        add_option( "tekzone_stripe_publishable_key", "");
    }

    if(!get_option("tekzone_zoolz_api_key"))    {
        add_option( "tekzone_zoolz_api_key", "");
    }

    if(!get_option("tekzone_zoolz_url"))    {
        add_option( "tekzone_zoolz_url", "");
    }


    if(!get_option("tekzone_smtp_host"))    {
        add_option( "tekzone_smtp_host", "");
    }

    if(!get_option("tekzone_smtp_username"))    {
        add_option( "tekzone_smtp_username", "");
    }

    if(!get_option("tekzone_smtp_password"))    {
        add_option( "tekzone_smtp_password", "");
    }

    if(!get_option("tekzone_smtp_port"))    {
        add_option( "tekzone_smtp_port", "");
    }

    if(!get_option("tekzone_website_admin_email"))    {
        add_option( "tekzone_website_admin_email", "");
    }

    if(!get_option("tekzone_company_name"))    {
        add_option( "tekzone_company_name", "");
    }

    if(!get_option("tekzone_company_logo"))    {
        add_option( "tekzone_company_logo", "");
    }

    if(!get_option("tekzone_company_sender_email"))    {
        add_option( "tekzone_company_sender_email", "");
    }

    if(!get_option("tekzone_company_reply_email"))    {
        add_option( "tekzone_company_reply_email", "");
    }

    if(!get_option("tekzone_signature_company_name"))    {
        add_option( "tekzone_signature_company_name", "");
    }

    if(!get_option("tekzone_signature_company_logo"))    {
        add_option( "tekzone_signature_company_logo", "");
    }

    if(!get_option("tekzone_signature_company_manager_name"))    {
        add_option( "tekzone_signature_company_manager_name", "");
    }

    // WP Globals
    global $table_prefix, $wpdb;

    // product Table
    $customerTable = $table_prefix . 'tekzone_zoolz_products';

    // Create product Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable'" ) != $customerTable ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `name` varchar(255) NOT NULL, ";
        $sql .= " `zoolz_plan_name` varchar(255) NOT NULL, ";
        $sql .= " `image` varchar(255) NULL, ";
        $sql .= " `price_in_cents` decimal(10,0) NOT NULL, ";
        $sql .= " `zoolz_price_in_cents` decimal(10,0) NOT NULL, ";
        $sql .= " `plan_id` int(11) NOT NULL, ";
        $sql .= " `currency` enum('GBP','USD','EUR','') NOT NULL DEFAULT 'GBP', ";
        $sql .= " `status` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive', ";
        $sql .= " `desc` text NULL, ";
        $sql .= " `sort_order` int(11) NOT NULL, ";       
        $sql .= " PRIMARY KEY `id` (`id`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";


        // Include Upgrade Script
        require_once( ABSPATH . DIRECTORY_SEPARATOR.'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }


    // orders Table
    $customerTable = $table_prefix . 'tekzone_zoolz_orders';

    // Create orders Table if not exist
    if( $wpdb->get_var( "show tables like '$customerTable'" ) != $customerTable ) {

        // Query - Create Table
        $sql = "CREATE TABLE `$customerTable` (";
        $sql .= " `id` int(11) NOT NULL auto_increment, ";
        $sql .= " `order_ref` varchar(255) NOT NULL, ";
        $sql .= " `email` varchar(255) NOT NULL, ";
        $sql .= " `name` varchar(255) NOT NULL, ";
        $sql .= " `address` varchar(255) NULL, ";
        $sql .= " `company` varchar(255) NULL, ";
        $sql .= " `phone` varchar(255) NULL, ";
        $sql .= " `product_id` int(11) NOT NULL, ";
        $sql .= " `product_name` varchar(255) NULL, ";
        $sql .= " `zoolz_plan_name` varchar(255) NULL, ";
        $sql .= " `zoolz_plan_id` varchar(255) NULL, ";
        $sql .= " `payment_session` text NULL, ";   
        $sql .= " `purchased_on` datetime NOT NULL DEFAULT current_timestamp(), ";   
        $sql .= " `payment_amount` decimal(10,0) NOT NULL, ";
        $sql .= " `currency` enum('GBP','USD','EUR','') NOT NULL DEFAULT 'GBP', ";
        $sql .= " `payment_status` int(11) NOT NULL, ";
        $sql .= " `payment_message` text NULL, ";  
        $sql .= " `zoolz_account_result` text DEFAULT NULL, ";  
        $sql .= " `zoolz_account_password` varchar(255) DEFAULT NULL, ";  
        $sql .= " `updated_date` datetime DEFAULT NULL, ";               
        $sql .= " PRIMARY KEY `id` (`id`), ";
        $sql .= " UNIQUE KEY `id` (`id`),";
        $sql .= " UNIQUE KEY `order_ref` (`order_ref`) ";
        $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        // Include Upgrade Script
        require_once( ABSPATH . DIRECTORY_SEPARATOR.'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'upgrade.php' );
    
        // Create Table
        dbDelta( $sql );
    }
}

function tekzoneZoolzSendSuccessfullOrderCreationEmail($zoolzOrder)
{
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    ob_start();
    include_once 'emailTemplate'.DIRECTORY_SEPARATOR.'successfullPayment.html';
    $message = ob_get_contents();
    ob_end_clean();

    $purchasedOn = date("Y-m-d H:i:s", strtotime($zoolzOrder->purchased_on));
    $customerName = ucwords($zoolzOrder->name);

    $message = str_replace("{{purchased_product_name}}",$zoolzOrder->product_name, $message);
    $message = str_replace("{{purchased_plan_date}}",$purchasedOn, $message);
    $message = str_replace("{{customer_company_name}}",$zoolzOrder->company, $message);
    $message = str_replace("{{zoolz_order_po_number}}",$zoolzOrder->order_ref, $message);

    $message = str_replace("{{customer_name}}",$customerName, $message);
    $message = str_replace("{{customer_email}}",$zoolzOrder->email, $message);
    $message = str_replace("{{customer_password}}",$zoolzOrder->zoolz_account_password, $message);
    $message = str_replace("{{company_name}}", get_option('tekzone_company_name'), $message);
    $message = str_replace("{{company_logo}}", get_option('tekzone_company_logo'), $message);
    $message = str_replace("{{company_reply_email}}", get_option('tekzone_company_reply_email'), $message);

    $message = str_replace("{{signature_company_name}}", get_option('tekzone_signature_company_name'), $message);
    $message = str_replace("{{signature_company_logo}}", get_option('tekzone_signature_company_logo'), $message);
    $message = str_replace("{{signature_company_manager_name}}", get_option('tekzone_signature_company_manager_name'), $message);

    $mail = new PHPMailer(true);

    try {

      // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = get_option('tekzone_smtp_host');                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = get_option('tekzone_smtp_username');                     //SMTP username
        $mail->Password   = get_option('tekzone_smtp_password');                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = get_option('tekzone_smtp_port');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(get_option('tekzone_company_sender_email'), get_option('tekzone_company_name'));
        $mail->addReplyTo(get_option('tekzone_company_reply_email'), get_option('tekzone_company_name'));
        $mail->addAddress($zoolzOrder->email, ($customerName)?$customerName:$zoolzOrder->email);
        $mail->AddCC("ansari.hasina@gmail.com", "Hasina Ansari");

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Thank you for your order.';
        $mail->Body    = $message;

        $mail->send();

    } catch (Exception $e) {
        $_SESSION["tekzone_zoolz_message"]["error"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function tekzoneZoolzSendForgetPasswordEmailToAdmin($emailData)
{   
    checkIfAllConfigurationSettingsAreInitiatedOrNot();

    ob_start();
    include_once 'emailTemplate'.DIRECTORY_SEPARATOR.'forgotPasswordMailForAdmin.html';
    $message = ob_get_contents();
    ob_end_clean();

    $message = str_replace("{{company_name}}", get_option('tekzone_company_name'), $message);
    $message = str_replace("{{company_logo}}", get_option('tekzone_company_logo'), $message);
    $message = str_replace("{{company_reply_email}}", get_option('tekzone_company_reply_email'), $message);
    $message = str_replace("{{signature_company_name}}", get_option('tekzone_signature_company_name'), $message);
    $message = str_replace("{{signature_company_logo}}", get_option('tekzone_signature_company_logo'), $message);
    $message = str_replace("{{signature_company_manager_name}}", get_option('tekzone_signature_company_manager_name'), $message);
    $message = str_replace("{{customer_email}}",$emailData["customer_email"], $message);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = get_option('tekzone_smtp_host');                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = get_option('tekzone_smtp_username');                     //SMTP username
        $mail->Password   = get_option('tekzone_smtp_password');                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = get_option('tekzone_smtp_port');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(get_option('tekzone_company_sender_email'), get_option('tekzone_company_name'));
        $mail->addReplyTo(get_option('tekzone_company_reply_email'), get_option('tekzone_company_name'));
        $mail->addAddress(get_option('tekzone_website_admin_email'), get_option('tekzone_website_admin_email'));
        $mail->AddCC("ansari.hasina@gmail.com", "Hasina Ansari");

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset Email';
        $mail->Body    = $message;

        $mail->send();

    } catch (Exception $e) {
        $_SESSION["tekzone_zoolz_message"]["error"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function tekzoneZoolzSendForgetPasswordEmailToCustomer($emailData)
{
    checkIfAllConfigurationSettingsAreInitiatedOrNot();
    ob_start();
    include_once 'emailTemplate'.DIRECTORY_SEPARATOR.'forgotPasswordMailForCustomer.html';
    $message = ob_get_contents();
    ob_end_clean();

    $message = str_replace("{{company_name}}", get_option('tekzone_company_name'), $message);
    $message = str_replace("{{company_logo}}", get_option('tekzone_company_logo'), $message);
    $message = str_replace("{{company_reply_email}}", get_option('tekzone_company_reply_email'), $message);
    $message = str_replace("{{signature_company_name}}", get_option('tekzone_signature_company_name'), $message);
    $message = str_replace("{{signature_company_logo}}", get_option('tekzone_signature_company_logo'), $message);
    $message = str_replace("{{signature_company_manager_name}}", get_option('tekzone_signature_company_manager_name'), $message);
    $message = str_replace("{{customer_name}}",$emailData["customer_name"], $message);
    $message = str_replace("{{admin_email}}",get_option('tekzone_website_admin_email'), $message);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = get_option('tekzone_smtp_host');                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = get_option('tekzone_smtp_username');                     //SMTP username
        $mail->Password   = get_option('tekzone_smtp_password');                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
        $mail->Port       = get_option('tekzone_smtp_port');                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom(get_option('tekzone_company_sender_email'), get_option('tekzone_company_name'));
        $mail->addReplyTo(get_option('tekzone_company_reply_email'), get_option('tekzone_company_name'));
        $mail->addAddress($emailData["customer_email"], $emailData["customer_name"]);
        $mail->AddCC("ansari.hasina@gmail.com", "Hasina Ansari");

        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Password Reset Email';
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        $_SESSION["tekzone_zoolz_message"]["error"] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function checkIfAllConfigurationSettingsAreInitiatedOrNot()
{
    try
    {
        $error = false;
        if(!get_option("tekzone_stripe_api_key"))    {
            $error = "tekzone_stripe_api_key";
        }

        if(!get_option("tekzone_stripe_publishable_key"))    {
            $error = "tekzone_stripe_publishable_key";
        }

        if(!get_option("tekzone_zoolz_api_key"))    {
            $error = "tekzone_zoolz_api_key";
        }

        if(!get_option("tekzone_zoolz_url"))    {
            $error = "tekzone_zoolz_url";
        }

        if(!get_option("tekzone_smtp_host"))    {
            $error = "tekzone_smtp_host";
        }

        if(!get_option("tekzone_smtp_username"))    {
            $error = "tekzone_smtp_username";
        }

        if(!get_option("tekzone_smtp_password"))    {
            $error = "tekzone_smtp_password";
        }

        if(!get_option("tekzone_smtp_port"))    {
            $error = "tekzone_smtp_port";
        }

        if(!get_option("tekzone_website_admin_email"))    {
            $error = "tekzone_website_admin_email";
        }

        if(!get_option("tekzone_company_name"))    {
            $error = "tekzone_company_name";
        }

        if(!get_option("tekzone_company_logo"))    {
            $error = "tekzone_company_logo";
        }

        if(!get_option("tekzone_company_sender_email"))    {
            $error = "tekzone_company_sender_email";
        }

        if(!get_option("tekzone_company_reply_email"))    {
            $error = "tekzone_company_reply_email";
        }

        if(!get_option("tekzone_signature_company_name"))    {
            $error = "tekzone_signature_company_name";
        }

        if(!get_option("tekzone_signature_company_logo"))    {
            $error = "tekzone_signature_company_logo";
        }

        if(!get_option("tekzone_signature_company_manager_name"))    {
            $error = "tekzone_signature_company_manager_name";
        }

        if($error !== false)    {
            throw new Exception("Zoolz Configuration Settings are missing. Please update all configuration.");
        }

    }
    catch(Exception $e)
    {
        echo '<div class="update-message notice inline notice-warning notice-alt" style="margin-top:50px;"><p>'.$e->getMessage().'</p></div>';
        die;

    }
}