<?php
global $table_prefix, $wpdb;

try
{
    if(isset($_FILES["tekzone_company_logo_file"]["name"]) && $_FILES["tekzone_company_logo_file"]["name"] = trim($_FILES["tekzone_company_logo_file"]["name"]))
    {
        $imageFileType = strtolower(pathinfo($_FILES["tekzone_company_logo_file"]["name"],PATHINFO_EXTENSION));
        $tekzoneCompanyLogo = "tekzone_company_logo_file." . $imageFileType;
        $fileDetails = wp_upload_bits($tekzoneCompanyLogo , null, file_get_contents($_FILES['tekzone_company_logo_file']['tmp_name']));

        if(isset($fileDetails["url"]))
        {
            update_option( 'tekzone_company_logo', $fileDetails["url"]);
        }
    }

    if(isset($_FILES["tekzone_signature_company_logo_file"]["name"]) && $_FILES["tekzone_signature_company_logo_file"]["name"] = trim($_FILES["tekzone_signature_company_logo_file"]["name"]))
    {
        $imageFileType = strtolower(pathinfo($_FILES["tekzone_signature_company_logo_file"]["name"],PATHINFO_EXTENSION));
        $tekzoneCompanyLogo = "tekzone_signature_company_logo_file." . $imageFileType;
        $fileDetails = wp_upload_bits($tekzoneCompanyLogo , null, file_get_contents($_FILES['tekzone_signature_company_logo_file']['tmp_name']));

        if(isset($fileDetails["url"]))
        {
            update_option( 'tekzone_signature_company_logo', $fileDetails["url"]);
        }
    }
    
    if(isset($_POST["tekzone_stripe_api_key"]))    {
        $_POST["tekzone_stripe_api_key"] = trim($_POST["tekzone_stripe_api_key"]);
        update_option( 'tekzone_stripe_api_key', $_POST["tekzone_stripe_api_key"] );
    }

    if(isset($_POST["tekzone_stripe_publishable_key"]))    {
        $_POST["tekzone_stripe_publishable_key"] = trim($_POST["tekzone_stripe_publishable_key"]);
        update_option( 'tekzone_stripe_publishable_key', $_POST["tekzone_stripe_publishable_key"] );
    }

    if(isset($_POST["tekzone_zoolz_api_key"]))    {
        $_POST["tekzone_zoolz_api_key"] = trim($_POST["tekzone_zoolz_api_key"]);
        update_option( 'tekzone_zoolz_api_key', $_POST["tekzone_zoolz_api_key"] );
    }

    if(isset($_POST["tekzone_zoolz_url"]))    {
        $_POST["tekzone_zoolz_url"] = trim($_POST["tekzone_zoolz_url"]);
        update_option( 'tekzone_zoolz_url', $_POST["tekzone_zoolz_url"] );
    }

    if(isset($_POST["tekzone_smtp_host"]))    {
        $_POST["tekzone_smtp_host"] = trim($_POST["tekzone_smtp_host"]);
        update_option( 'tekzone_smtp_host', $_POST["tekzone_smtp_host"] );
    }

    if(isset($_POST["tekzone_smtp_username"]))    {
        $_POST["tekzone_smtp_username"] = trim($_POST["tekzone_smtp_username"]);
        update_option( 'tekzone_smtp_username', $_POST["tekzone_smtp_username"] );
    }

    if(isset($_POST["tekzone_smtp_password"]))    {
        $_POST["tekzone_smtp_password"] = trim($_POST["tekzone_smtp_password"]);
        update_option( 'tekzone_smtp_password', $_POST["tekzone_smtp_password"] );
    }
    
    if(isset($_POST["tekzone_smtp_port"]))    {
        $_POST["tekzone_smtp_port"] = trim($_POST["tekzone_smtp_port"]);
        update_option( 'tekzone_smtp_port', $_POST["tekzone_smtp_port"] );
    }

    if(isset($_POST["tekzone_website_admin_email"]))    {
        $_POST["tekzone_website_admin_email"] = trim($_POST["tekzone_website_admin_email"]);
        update_option( 'tekzone_website_admin_email', $_POST["tekzone_website_admin_email"] );
    }

    if(isset($_POST["tekzone_company_name"]))    {
        $_POST["tekzone_company_name"] = trim($_POST["tekzone_company_name"]);
        update_option( 'tekzone_company_name', $_POST["tekzone_company_name"] );
    }

    if(isset($_POST["tekzone_company_sender_email"]))    {
        $_POST["tekzone_company_sender_email"] = trim($_POST["tekzone_company_sender_email"]);
        update_option( 'tekzone_company_sender_email', $_POST["tekzone_company_sender_email"] );
    }

    if(isset($_POST["tekzone_company_reply_email"]))    {
        $_POST["tekzone_company_reply_email"] = trim($_POST["tekzone_company_reply_email"]);
        update_option( 'tekzone_company_reply_email', $_POST["tekzone_company_reply_email"] );
    }

    if(isset($_POST["tekzone_signature_company_name"]))    {
        $_POST["tekzone_signature_company_name"] = trim($_POST["tekzone_signature_company_name"]);
        update_option( 'tekzone_signature_company_name', $_POST["tekzone_signature_company_name"] );
    }

    if(isset($_POST["tekzone_signature_company_manager_name"]))    {
        $_POST["tekzone_signature_company_manager_name"] = trim($_POST["tekzone_signature_company_manager_name"]);
        update_option( 'tekzone_signature_company_manager_name', $_POST["tekzone_signature_company_manager_name"] );
    }
}
catch(Exception $e)
{
    $_SESSION["tekzone_zoolz_message"]["error"] = $e->getMessage();
}

wp_redirect(admin_url( 'admin.php?page=zoolz-settings' ));
?>