<?php defined( 'ABSPATH' ) || exit;

if ( empty( $_COOKIE['solace_page_access'] ) ) {
    $solace_extra_url = get_admin_url() . 'admin.php?page=dashboard-starter-templates&type=elementor';
    wp_safe_redirect( $solace_extra_url, 301 );
    exit;
}

// This is the wizard's last screen, the first page load after an import has
// finished, and by now every plugin the import activated has registered its post
// types -- so it is the right moment to persist the rewrite rules.
//
// It used to run unconditionally, which meant a full rule regeneration plus an
// option write on every visit and every refresh of this page, long after there
// was anything new to pick up. The flag the importer raises on completion makes
// it fire exactly once per import.
if ( get_option( 'solace_extra_flush_rewrite_after_import' ) ) {
    flush_rewrite_rules();
    delete_option( 'solace_extra_flush_rewrite_after_import' );
}

$solace_extra_customizer_link = admin_url( 'customize.php' ); 
?>

<script type="text/javascript">
    var ajaxurl = "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>";
</script>
<script>
// Scope wizard storage to the current site: on multisite (subdirectory) all subsites share one
// origin, so unscoped keys and path=/ cookies leak between parallel imports on different subsites.
window.solaceStorageKey = window.solaceStorageKey || function (key) {
	return key + '@' + ( window.location.pathname.split( '/wp-admin' )[0] || '/' );
};
window.solaceCookiePath = window.solaceCookiePath || function () {
	return ( window.location.pathname.split( '/wp-admin' )[0] || '' ) + '/wp-admin/';
};


    jQuery(document).ready(function($) {

        // =================== 2nd TRYING TO COMBINE AJAX FONT AND COLOR ===================
        var fontValue = localStorage.getItem(solaceStorageKey('solace_step5_font'));
        var new_solace_body_font = null;
        var new_solace_heading_font = null;

        if (fontValue) {
            try {
                var arrayFont = JSON.parse(fontValue);
                new_solace_body_font = arrayFont.new_solace_body_font_family || null;
                new_solace_heading_font = arrayFont.new_solace_heading_font_family_general || null;
            } catch (e) {
                console.error('Error parsing solace_step5_font:', e);
            }
        }

        var colorValue = localStorage.getItem(solaceStorageKey('solace_step5_color'));
        var new_base_color = null;
        var new_heading_color = null;
        var new_link_button_color = null;
        var new_link_button_hover_color = null;
        var new_button_color = null;
        var new_button_hover_color = null;
        var new_text_selection_color = null;
        var new_text_selection_bg_color = null;
        var new_border_color = null;
        var new_background_color = null;
        var new_page_title_text_color = null;
        var new_page_title_bg_color = null;
        var new_bg_menu_dropdown_color = null;

        if (colorValue) {
            try {
                var arrayColor = JSON.parse(colorValue);
                new_base_color = arrayColor.new_base_color || null;
                new_heading_color = arrayColor.new_heading_color || null;
                new_link_button_color = arrayColor.new_link_button_color || null;
                new_link_button_hover_color = arrayColor.new_link_button_hover_color || null;
                new_button_color = arrayColor.new_button_color || null;
                new_button_hover_color = arrayColor.new_button_hover_color || null;
                new_text_selection_color = arrayColor.new_text_selection_color || null;
                new_text_selection_bg_color = arrayColor.new_text_selection_bg_color || null;
                new_border_color = arrayColor.new_border_color || null;
                new_background_color = arrayColor.new_background_color || null;
                new_page_title_text_color = arrayColor.new_page_title_text_color || null;
                new_page_title_bg_color = arrayColor.new_page_title_bg_color || null;
                new_bg_menu_dropdown_color = arrayColor.new_bg_menu_dropdown_color || null;
            } catch (e) {
                console.error('Error parsing solace_step5_color:', e);
            }
        }

        console.log('BodyFont:', new_solace_body_font);
        console.log('HeadingFont:', new_solace_heading_font);
        console.log('BaseColor:', new_base_color);
        console.log('HeadingColor:', new_heading_color);

        var ajaxData = {
            action: 'update_solace_font_and_color',
            nonce: ajax_object.nonce,
        };

        // Only add values that are not null to the ajaxData object.
        if (new_solace_body_font) ajaxData.new_solace_body_font_family = new_solace_body_font;
        if (new_solace_heading_font) ajaxData.new_solace_heading_font_family_general = new_solace_heading_font;
        if (new_base_color) ajaxData.new_base_color = new_base_color;
        if (new_heading_color) ajaxData.new_heading_color = new_heading_color;
        if (new_link_button_color) ajaxData.new_link_button_color = new_link_button_color;
        if (new_link_button_hover_color) ajaxData.new_link_button_hover_color = new_link_button_hover_color;
        if (new_button_color) ajaxData.new_button_color = new_button_color;
        if (new_button_hover_color) ajaxData.new_button_hover_color = new_button_hover_color;
        if (new_text_selection_color) ajaxData.new_text_selection_color = new_text_selection_color;
        if (new_text_selection_bg_color) ajaxData.new_text_selection_bg_color = new_text_selection_bg_color;
        if (new_border_color) ajaxData.new_border_color = new_border_color;
        if (new_background_color) ajaxData.new_background_color = new_background_color;
        if (new_page_title_text_color) ajaxData.new_page_title_text_color = new_page_title_text_color;
        if (new_page_title_bg_color) ajaxData.new_page_title_bg_color = new_page_title_bg_color;
        if (new_bg_menu_dropdown_color) ajaxData.new_bg_menu_dropdown_color = new_bg_menu_dropdown_color;

        $.post(ajax_object.ajax_url, ajaxData)
            .done(function(response) {
                $('.cong_button').addClass('done_fonts');
                $('.cong_button').addClass('done_colors');
                console.log(response);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX request failed:', textStatus, errorThrown);

                if (jqXHR.responseText) {
                    console.log('Error response:', jqXHR.responseText);
                }
            });


        // Get the 'solaceDuration' value from local storage
        var solaceDuration = localStorage.getItem(solaceStorageKey('solaceDuration'));

        // Check if solaceDuration is not null or undefined
        if (solaceDuration) {
            // Convert solaceDuration to a Date object
            var startTime = new Date(solaceDuration);

            // Get the current time as the endTime
            var endTime = new Date();

            // Calculate the time difference in milliseconds
            var timeDifference = endTime - startTime;

            // Convert the time difference from milliseconds to seconds
            var timeDifferenceInSeconds = timeDifference / 1000;

            // Check if the time difference is at least 60 seconds
            if (timeDifferenceInSeconds >= 60) {
                // Calculate and display minutes and remaining seconds
                var minutes = Math.floor(timeDifferenceInSeconds / 60);
                var seconds = Math.floor(timeDifferenceInSeconds % 60);
                console.log(minutes + " minutes " + seconds + " seconds");
            } else {
                // Display the time difference in seconds
                console.log(timeDifferenceInSeconds + " seconds");
            }

            // Remove information imported
            localStorage.removeItem(solaceStorageKey('solaceRemoveImported'));
        }
    });

</script>

<?php $solace_extra_myadmin = site_url(); ?>
<style>
    div.cong_button {
        display: none!important;
    }

    div.cong_button.done_fonts.done_colors {
        display: flex!important;
    }
</style>
<div class="wrap wrap-congratulations">
    <?php require_once plugin_dir_path(dirname(__FILE__)) . 'partials/header.php'; ?>
    <div class="box-congrat">
        <div class="cong-success" aria-hidden="true">
            <span class="cong-sparkles">
                <svg class="cong-sparkle sparkle-one" viewBox="0 0 24 24"><path d="M12 0q1.1 10.9 12 12-10.9 1.1-12 12-1.1-10.9-12-12Q10.9 10.9 12 0Z"/></svg>
                <svg class="cong-sparkle sparkle-two" viewBox="0 0 24 24"><path d="M12 0q1.1 10.9 12 12-10.9 1.1-12 12-1.1-10.9-12-12Q10.9 10.9 12 0Z"/></svg>
                <svg class="cong-sparkle sparkle-three" viewBox="0 0 24 24"><path d="M12 0q1.1 10.9 12 12-10.9 1.1-12 12-1.1-10.9-12-12Q10.9 10.9 12 0Z"/></svg>
                <svg class="cong-sparkle sparkle-four" viewBox="0 0 24 24"><path d="M12 0q1.1 10.9 12 12-10.9 1.1-12 12-1.1-10.9-12-12Q10.9 10.9 12 0Z"/></svg>
                <svg class="cong-sparkle sparkle-five" viewBox="0 0 24 24"><path d="M12 0q1.1 10.9 12 12-10.9 1.1-12 12-1.1-10.9-12-12Q10.9 10.9 12 0Z"/></svg>
                <svg class="cong-sparkle sparkle-six" viewBox="0 0 24 24"><path d="M12 0q1.1 10.9 12 12-10.9 1.1-12 12-1.1-10.9-12-12Q10.9 10.9 12 0Z"/></svg>
            </span>
            <svg viewBox="0 0 24 24"><path class="success-circle" d="M21.1 11.1a9.2 9.2 0 1 1-5.45-7.55"/><path d="m8 11.5 3.1 3.1L21 4.7"/></svg>
        </div>
        <span class="cong_title"><?php esc_html_e('Your website is ready!', 'solace-extra'); ?></span>
        <span class="cong_desc">
            <?php esc_html_e('Your import is complete and everything is set up.', 'solace-extra'); ?><br>
            <?php esc_html_e('You can now explore your new website.', 'solace-extra'); ?>
        </span>
        <div class="cong-progress" aria-label="<?php esc_attr_e('Import completed', 'solace-extra'); ?>">
            <div class="cong-step"><span class="step-check"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 12.5 3.2 3.2L17.5 8"/></svg></span><span><?php esc_html_e('Import Content', 'solace-extra'); ?></span></div>
            <div class="cong-step"><span class="step-check"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 12.5 3.2 3.2L17.5 8"/></svg></span><span><?php esc_html_e('Configure Settings', 'solace-extra'); ?></span></div>
            <div class="cong-step"><span class="step-check"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 12.5 3.2 3.2L17.5 8"/></svg></span><span><?php esc_html_e('Install Plugins', 'solace-extra'); ?></span></div>
            <div class="cong-step"><span class="step-check"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m7 12.5 3.2 3.2L17.5 8"/></svg></span><span><?php esc_html_e('Setup Complete', 'solace-extra'); ?></span></div>
        </div>
        <a class="button" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( site_url() ); ?>">
            <div class="cong_button">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9"/><path d="M19 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1h6"/></svg>
                <?php esc_html_e( 'Check Website', 'solace-extra' ); ?>
            </div>
        </a>
        <div class="cong-trust" aria-label="<?php esc_attr_e('Service benefits', 'solace-extra'); ?>">
            <span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 5 6v5c0 4.7 2.8 8.1 7 10 4.2-1.9 7-5.3 7-10V6l-7-3Z"/><path d="m9 12 2 2 4-4"/></svg><?php esc_html_e('Secure & Trusted', 'solace-extra'); ?></span>
            <span><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="13" r="7"/><path d="M12 9v4l2.5 1.5M7 3 3.5 6.5M17 3l3.5 3.5M7 20l-1.5 1.5M17 20l1.5 1.5"/></svg><?php esc_html_e('No Spam. Ever.', 'solace-extra'); ?></span>
            <span><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m13 2-8 12h7l-1 8 8-12h-7l1-8Z"/></svg><?php esc_html_e('Fast & Reliable', 'solace-extra'); ?></span>
        </div>
    </div>
</div>
