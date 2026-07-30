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
    <div class="step6-background-art" aria-hidden="true">
        <svg class="step6-cube step6-cube-small" viewBox="0 0 190 160">
            <g><path d="m52 24 70 12 29 57-42 42-69-13-28-57Z"/><path d="m12 65 68 12 42-41M80 77l29 58"/></g>
            <circle cx="12" cy="65" r="2.5"/>
        </svg>
        <svg class="step6-waves" viewBox="0 0 490 480" preserveAspectRatio="none">
            <g>
                <path d="M-35 55C47 86 55 207 140 245s157 4 258 55 74 142 131 169"/>
                <path d="M-43 67C37 99 47 218 132 257s162 7 260 56 72 135 132 165"/>
                <path d="M-50 80C28 113 39 230 124 269s166 9 261 57 72 128 135 155"/>
                <path d="M-57 94C18 127 31 242 116 281s170 12 263 58 71 120 137 145"/>
                <path d="M-63 109C8 142 24 254 109 293s173 14 264 59 70 112 140 134"/>
                <path d="M-69 125c67 33 85 141 170 180s176 16 265 60 70 104 143 124"/>
                <path d="M-75 142c63 32 84 136 169 175s178 18 266 61 68 96 145 114"/>
                <path d="M-80 160c58 31 82 130 167 169s181 21 267 62 68 88 147 104"/>
            </g>
        </svg>
        <svg class="step6-cube step6-cube-large" viewBox="0 0 310 270">
            <g><path d="m72 20 143 1 72 91-42 132-145 7-78-83Z"/><path d="m22 168 111-5 82-142M133 163l112 81M133 163l154-51"/></g>
            <circle cx="287" cy="112" r="3.5"/>
        </svg>
    </div>
    <div class="box-optin">
        <div class="step6-sparkle" aria-hidden="true">
            <svg viewBox="0 0 32 32" role="img"><path d="M16 2c.7 7.8 4.2 11.3 12 12-7.8.7-11.3 4.2-12 12-.7-7.8-4.2-11.3-12-12 7.8-.7 11.3-4.2 12-12Z"/><path d="M25 2c.25 2.75 1.5 4 4 4.25-2.5.25-3.75 1.5-4 4.25-.25-2.75-1.5-4-4-4.25 2.5-.25 3.75-1.5 4-4.25Z"/></svg>
        </div>
        <span class="step6_title"><?php esc_html_e('Almost there!', 'solace-extra'); ?></span>
        <span class="step6_desc"><?php esc_html_e('Just one last step to build your website.', 'solace-extra'); ?></span>
        <form>
            <div class='input'>
                <label class="field-wrap field-name">
                    <span class="screen-reader-text"><?php esc_html_e('Your First Name', 'solace-extra'); ?></span>
                    <input type='text' class='firstname' name='firstname' placeholder='<?php esc_attr_e('Your First Name', 'solace-extra'); ?>' />
                </label>
                <label class="field-wrap field-email">
                    <span class="screen-reader-text"><?php esc_html_e('Your Work Email', 'solace-extra'); ?></span>
                    <input type="email" class='email' name='email' placeholder='<?php esc_attr_e('Your Work Email', 'solace-extra'); ?>' />
                </label>
            </div>
            <div class='checkbox_agreement'>
                <input type="checkbox" id="agreement" name="agreement" value="1" required checked>
                <label for="agreement"><?php
                    printf(
                        /* translators: %s: link to the data privacy statement. */
                        esc_html__('I agree to receive your newsletters and accept the %s.', 'solace-extra'),
                        '<a href="' . esc_url('https://solacewp.com/privacy-policy/') . '" target="_blank" rel="noopener noreferrer">' . esc_html__('data privacy statement', 'solace-extra') . '</a>'
                    );
                ?></label>
            </div>
            <label class='note' style='display: none;'><?php esc_html_e('This will delete your previous WordPress starter template.', 'solace-extra'); ?></label>
            <button class="step6_submit"  id="submit-button" type="button" disabled>
                <svg class="button-rocket" viewBox="0 0 512 512" aria-hidden="true"><path d="M156.6 384.9l-53.8 53.8c-10.1 10.1-37.8 17.7-66.4 21.1 3.4-28.6 11-56.3 21.1-66.4l53.8-53.8 45.3 45.3zm296-341.5c-40.8-6.9-132.7-9.1-218.4 76.6l-58.6 58.6-73.5-7.4c-10.8-1.1-21.5 2.7-29.2 10.4l-62.3 62.3c-10.4 10.4-4.6 28.2 10 30.4l87 13.4 116.7 116.7 13.4 87c2.2 14.6 20 20.4 30.4 10l62.3-62.3c7.7-7.7 11.5-18.4 10.4-29.2l-7.4-73.5 58.6-58.6c85.7-85.7 83.5-177.6 76.6-218.4-1.4-8.4-7.9-14.9-16-16zm-86.3 161.2a42.7 42.7 0 1 1 0-85.4 42.7 42.7 0 0 1 0 85.4z"/></svg>
                <?php esc_html_e('Submit & Build My Website', 'solace-extra'); ?>
            </button>
            <div class="step6-divider"><span><?php esc_html_e('or', 'solace-extra'); ?></span></div>
            <div class="step6-alternatives">
                <button class="skip-this-step" type="button"><?php esc_html_e('Skip This Step', 'solace-extra'); ?></button>
                <button class="skip-no-thanks" type="button"><?php esc_html_e("No Thanks, I'll Sign Up Later", 'solace-extra'); ?></button>
            </div>
        </form>
        <div class="step6-trust" aria-label="<?php esc_attr_e('Service benefits', 'solace-extra'); ?>">
            <span class="trust-secure">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 5 6v5c0 4.7 2.8 8.1 7 10 4.2-1.9 7-5.3 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                <?php esc_html_e('Secure & Trusted', 'solace-extra'); ?>
            </span>
            <span class="trust-spam">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                <?php esc_html_e('No Spam. Ever.', 'solace-extra'); ?>
            </span>
            <span class="trust-instant">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8.5 12 2.3 2.3 4.8-5"/></svg>
                <?php esc_html_e('Easy & Instant', 'solace-extra'); ?>
            </span>
        </div>
    </div>
</div>
