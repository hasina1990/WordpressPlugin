<?php global $table_prefix, $wpdb; ?>

<div class="wrap">
    <h1 id="add-new-user">Company to Company Details</h1>
    <div id="ajax-response"></div>

    <form action="<?php echo admin_url("admin-post.php"); ?>" method="post" name="zoolz-setting" id="zoolz-setting" class="validate" novalidate="novalidate" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update_tekzone_zoolz_setting">
        <h2 class="title">Stripe Setting</h2>
        <p>Api Key and Publisher Key needed from your stripe payment account.</p>
        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_stripe_api_key">Stripe API Key</label></th>
                    <td><input name="tekzone_stripe_api_key" type="text" id="tekzone_stripe_api_key" value="<?php if(get_option('tekzone_stripe_api_key')): echo get_option('tekzone_stripe_api_key'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_stripe_publishable_key">Stripe Publishable Key</label></th>
                    <td><input name="tekzone_stripe_publishable_key" type="text" id="tekzone_stripe_publishable_key" value="<?php if(get_option('tekzone_stripe_publishable_key')): echo get_option('tekzone_stripe_publishable_key'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
            </tbody>
        </table>

        <h2 class="title">Zoolz Settings</h2>
        <p>Zoolz Api credentials needed here.</p>

        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_zoolz_api_key">Zoolz API Key</label></th>
                    <td><input name="tekzone_zoolz_api_key" type="text" id="tekzone_zoolz_api_key" value="<?php if(get_option('tekzone_zoolz_api_key')): echo get_option('tekzone_zoolz_api_key'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_zoolz_url">Zoolz API URL</label></th>
                    <td><input name="tekzone_zoolz_url" type="text" id="tekzone_zoolz_url" value="<?php if(get_option('tekzone_zoolz_url')): echo get_option('tekzone_zoolz_url'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
            </tbody>
        </table>

        <h2 class="title">SMTP Settings</h2>
        <p>Add your SMTP credentails.</p>

        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_smtp_host">Host</label></th>
                    <td><input name="tekzone_smtp_host" type="text" id="tekzone_smtp_host" value="<?php if(get_option('tekzone_smtp_host')): echo get_option('tekzone_smtp_host'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_smtp_username">Username</label></th>
                    <td><input name="tekzone_smtp_username" type="text" id="tekzone_smtp_username" value="<?php if(get_option('tekzone_smtp_username')): echo get_option('tekzone_smtp_username'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_smtp_password">Password</label></th>
                    <td><input name="tekzone_smtp_password" type="password" id="tekzone_smtp_password" value="<?php if(get_option('tekzone_smtp_password')): echo get_option('tekzone_smtp_password'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_smtp_port">Port</label></th>
                    <td><input name="tekzone_smtp_port" type="text" id="tekzone_smtp_port" value="<?php if(get_option('tekzone_smtp_port')): echo get_option('tekzone_smtp_port'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
            </tbody>
        </table>


        <h2 class="title">Company</h2>
        <p>Add your company name and website emails to receive and send emails.</p>

        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_company_name">Name</label></th>
                    <td><input name="tekzone_company_name" type="text" id="tekzone_company_name" value="<?php if(get_option('tekzone_company_name')): echo get_option('tekzone_company_name'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_company_sender_email">Sender Email</label></th>
                    <td><input name="tekzone_company_sender_email" type="text" id="tekzone_company_sender_email" value="<?php if(get_option('tekzone_company_sender_email')): echo get_option('tekzone_company_sender_email'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_company_reply_email">Reply Email</label></th>
                    <td><input name="tekzone_company_reply_email" type="text" id="tekzone_company_reply_email" value="<?php if(get_option('tekzone_company_reply_email')): echo get_option('tekzone_company_reply_email'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_website_admin_email">Webiste Admin Email</label></th>
                    <td><input name="tekzone_website_admin_email" type="text" id="tekzone_website_admin_email" value="<?php if(get_option('tekzone_website_admin_email')): echo get_option('tekzone_website_admin_email'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_company_logo">Logo (Used in dialogs)</label></th>
                    <td>
                        <?php if(get_option('tekzone_company_logo')): ?>
                            <img width="100" id = "tekzone-company-logo-image" src = "<?php echo get_option('tekzone_company_logo'); ?>" alt = "new image" />
                        <?php endif; ?>
                        <input type="file" name="tekzone_company_logo_file" accept=".png,.jpg,.jpeg,.gif">
                    </td>
                </tr>
            </tbody>
        </table>


        <h2 class="title">Signature Company</h2>
        <p>Add signature comapny details.</p>

        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_signature_company_name">Name</label></th>
                    <td><input name="tekzone_signature_company_name" type="text" id="tekzone_signature_company_name" value="<?php if(get_option('tekzone_signature_company_name')): echo get_option('tekzone_signature_company_name'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_signature_company_manager_name">Manager Name</label></th>
                    <td><input name="tekzone_signature_company_manager_name" type="text" id="tekzone_signature_company_manager_name" value="<?php if(get_option('tekzone_signature_company_manager_name')): echo get_option('tekzone_signature_company_manager_name'); endif;?>" aria-required="true" autocapitalize="none" autocorrect="off"></td>
                </tr>
                <tr class="form-field form-required">
                    <th scope="row"><label for="tekzone_signature_company_logo">Logo (Used in email signatures)</label></th>
                    <td>
                        <?php if(get_option('tekzone_signature_company_logo')): ?>
                            <img width="100" id = "tekzone-signature-company-logo-image" src = "<?php echo get_option('tekzone_signature_company_logo'); ?>" alt = "new image" />
                        <?php endif; ?>
                        <input type="file" name="tekzone_signature_company_logo_file" accept=".png,.jpg,.jpeg,.gif">
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit"><input type="submit" name="update-setting" id="update-setting" class="button button-primary" value="submit"></p>
    </form>
</div>