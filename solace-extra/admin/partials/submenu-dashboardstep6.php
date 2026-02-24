<?php defined( 'ABSPATH' ) || exit; ?>
<?php $solace_extra_customizer_link = admin_url('customize.php'); ?>
<?php $solace_extra_myadmin = site_url(); ?>

<?php

if ( empty( $_COOKIE['solace_page_access'] ) ) {
    $solace_extra_url = get_admin_url() . 'admin.php?page=dashboard-starter-templates&type=elementor';
    wp_safe_redirect( $solace_extra_url, 301 ); 
    exit;
}

$solace_extra_saved_key = get_option( 'solace_license_key', '' );
$status    = get_option( 'solace_license_status', '' );
$solace_extra_license_info = false;
$solace_extra_is_license_valid = false;

// Check license if function exists (from solace-extra-pro plugin)
if ( function_exists( 'solace_check_license' ) && ! empty( $solace_extra_saved_key ) ) {
    $solace_extra_license_info = solace_check_license( $solace_extra_saved_key );

    if ( $solace_extra_license_info && isset( $solace_extra_license_info->license ) ) {
        // Check if license status is valid
        $solace_extra_license_status = $solace_extra_license_info->license;
        $solace_extra_is_license_valid = ( 'valid' === $solace_extra_license_status || 'active' === $solace_extra_license_status );

        // Determine if selected demo is PRO using server-side API (no UI flicker)
        $solace_extra_selected_demo_is_pro = false;
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading demo slug from URL for display only.
        $solace_extra_selected_demo_slug = ! empty( $_GET['demo'] ) ? sanitize_key( wp_unslash( $_GET['demo'] ) ) : '';
        if ( ! empty( $solace_extra_selected_demo_slug ) ) {
            $solace_extra_api_demo_url = SOLACE_EXTRA_DEMO_IMPORT_URL . 'api/wp-json/solace/v1/demo/';
            $solace_extra_api_demo_response = wp_remote_get( $solace_extra_api_demo_url );
            if ( ! is_wp_error( $solace_extra_api_demo_response ) ) {
                $solace_extra_api_demo_body = wp_remote_retrieve_body( $solace_extra_api_demo_response );
                $solace_extra_api_demo_data = json_decode( $solace_extra_api_demo_body, true );
                if ( ! empty( $solace_extra_api_demo_data ) && is_array( $solace_extra_api_demo_data ) ) {
                    foreach ( $solace_extra_api_demo_data as $solace_extra_demo_item ) {
                        if ( empty( $solace_extra_demo_item['demo_link'] ) ) {
                            continue;
                        }
                        $solace_extra_link = trim( $solace_extra_demo_item['demo_link'] );
                        $solace_extra_link = rtrim( $solace_extra_link, '/' );
                        $solace_extra_parts = explode( '/', $solace_extra_link );
                        $solace_extra_slug  = strtolower( end( $solace_extra_parts ) );
                        if ( $solace_extra_slug === strtolower( $solace_extra_selected_demo_slug ) ) {
                            if ( isset( $solace_extra_demo_item['is_pro'] ) ) {
                                $solace_extra_selected_demo_is_pro = (bool) $solace_extra_demo_item['is_pro'];
                            } elseif ( isset( $solace_extra_demo_item['isPro'] ) ) {
                                $solace_extra_selected_demo_is_pro = (bool) $solace_extra_demo_item['isPro'];
                            } elseif ( isset( $solace_extra_demo_item['license'] ) ) {
                                $solace_extra_selected_demo_is_pro = ( 'pro' === strtolower( $solace_extra_demo_item['license'] ) );
                            }
                            break;
                        }
                    }
                }
            }
        }        

        if ( $solace_extra_selected_demo_is_pro && ! $solace_extra_is_license_valid ) {
            $solace_extra_url = get_admin_url() . 'admin.php?page=dashboard-starter-templates&type=elementor';
            wp_safe_redirect( $solace_extra_url, 301 ); 
            exit;
        }
    }
}
?>

<div class="wrap-step6 wrap">
    <?php require_once plugin_dir_path(dirname(__FILE__)) . 'partials/header.php'; ?>
    <div class="box-optin">
        <span class="step6_title"><?php esc_html_e('Okay, just one last step ', 'solace-extra'); ?></span>
        <span class="step6_desc"><?php esc_html_e('Tell us a litle bit about yourself', 'solace-extra'); ?></span>
        <form>
            <div class='input'>
                <input type='text' class='firstname' name='firstname' placeholder='Your First Name' />
                <input type="email" class='email' name='email' placeholder='Your Work Email' />
            </div>
            <div class='checkbox_agreement'>
                <input type="checkbox" id="agreement" name="agreement" value="1" required checked>
                <label for="agreement"><?php esc_html_e('I agree to receive your newsletters and accept the data privacy statement.', 'solace-extra'); ?></label>
            </div>
            <label class='note' style='display: none;'><?php esc_html_e('This will delete your previous WordPress starter template.', 'solace-extra'); ?></label>
            <button class="step6_submit"  id="submit-button" type="button" disabled>
                <?php esc_html_e('Submit & Build My Website', 'solace-extra'); ?>
            </button>
            <button class="skip-this-step" type="button">
                <?php esc_html_e('Skip this step', 'solace-extra'); ?>
            </button>
            <button class="skip-no-thanks" type="button">
                <?php esc_html_e("No Thanks, I'll Sign Up Later", 'solace-extra'); ?>
            </button>
        </form>
    </div>
</div>
