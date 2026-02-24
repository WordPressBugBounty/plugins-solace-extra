<?php 
defined( 'ABSPATH' ) || exit;

if ( empty( $_COOKIE['solace_page_access'] ) ) {
    $solace_extra_redirect_url = get_admin_url() . 'admin.php?page=dashboard-starter-templates&type=elementor';
    wp_safe_redirect( $solace_extra_redirect_url, 301 ); 
    exit;
}

function solace_extra_getGoogleFontsFamilyName($googleFontsUrl) {
    $url_parts = wp_parse_url($googleFontsUrl);
    $query_string = isset($url_parts['query']) ? $url_parts['query'] : '';

    parse_str($query_string, $query_params);

    $font_family = isset($query_params['family']) ? $query_params['family'] : '';

    return $font_family;
}

// Font Awesome
wp_enqueue_style('solace-fontawesome', get_template_directory_uri() . '/assets-solace/fontawesome/css/all.min.css', array(), '5.15.4', 'all');

function solace_extra_upload_logo() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' )) {
        $solace_extra_upload_response = array('error' => 'Invalid nonce7!');
        echo wp_json_encode($solace_extra_upload_response);
        wp_die();
    }

    if (isset($_FILES['logo'])) {
        $file = esc_url( esc_url_raw( $_FILES['logo'] ) );
        $upload_overrides = array('test_form' => false);
        $upload_result = wp_handle_upload($file, $upload_overrides);

        if (!empty($upload_result['url'])) {
            // Set the logo URL in theme mods
            set_theme_mod('custom_logo', $upload_result['url']);

            $solace_extra_upload_response = array(
                'success' => true,
                'data' => array(
                    'url' => $upload_result['url']
                )
            );
            echo wp_json_encode($solace_extra_upload_response);
        } else {
            $solace_extra_upload_response = array(
                'success' => false
            );
            echo wp_json_encode($solace_extra_upload_response);
        }
    }
    die();
}

?>

<div class="wrap wrap-step5">
<?php require_once plugin_dir_path(dirname(__FILE__)) . 'partials/header.php'; ?>

<?php 
    // Verify nonce
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['nonce'] ) ), 'ajax-nonce' ) ) {
        $solace_extra_step5_response = array( 'error' => 'Invalid nonce8!' );
    }

    $solace_extra_demo_url = ! empty( $_GET['demo'] ) ? sanitize_key( wp_unslash( $_GET['demo'] ) ) : '';
    $solace_extra_demo_url = trailingslashit( SOLACE_EXTRA_DEMO_IMPORT_URL ) . $solace_extra_demo_url;

    $solace_extra_iframe_url = $solace_extra_demo_url;

    // Trim whitespace from the beginning and end of the URL
    $solace_extra_demo_url = trim( $solace_extra_demo_url );

    // Check if the URL does not end with a slash
    if ( substr( $solace_extra_demo_url, -1 ) !== '/' ) {
        // If it does not, append a slash to the end of the URL
        $solace_extra_demo_url .= '/';
    }

    $solace_extra_preview_url = $solace_extra_demo_url; 

    // $bodyClasses = solace_extra_getBodyClasses( $solace_extra_preview_url );
    $solace_extra_css_url = $solace_extra_demo_url . 'core/views/solace/style-main-new.min.css';

    // $backgroundColor = solace_extra_getBodyBackgroundColor( $solace_extra_preview_url, $solace_extra_css_url );

    $solace_extra_api_url = $solace_extra_demo_url . 'wp-json/elementor-api/v1/settings?timestamp=' . time();

    $solace_extra_color_palettes   = array();
    $solace_extra_palette_font_scheme = array();
    
    $solace_extra_response = wp_remote_get( $solace_extra_api_url, array( 'timeout' => 30 ) );
    
    if ( $solace_extra_response !== false ) {
        // $data = json_decode($response, true);
        $solace_extra_body = wp_remote_retrieve_body( $solace_extra_response );
        $solace_extra_data = json_decode( $solace_extra_body, true );
    
        if ( $solace_extra_data ) {
            // echo "From Elementor API: $solace_extra_api_url<br />";
            // echo "<pre>";
            // print_r($data);
            // echo "</pre>";
            $solace_extra_api_base_color            = $solace_extra_data['colors_data']['base_color'];
            $solace_extra_api_heading_color         = $solace_extra_data['colors_data']['heading_color'];
            $solace_extra_api_link_button_color     = $solace_extra_data['colors_data']['link_button_color'];
            $solace_extra_api_link_button_hover_color = $solace_extra_data['colors_data']['link_button_hover_color'];
            $solace_extra_api_button_color          = $solace_extra_data['colors_data']['button_color'];
            $solace_extra_api_button_hover_color    = $solace_extra_data['colors_data']['button_hover_color'];
            $solace_extra_api_text_selection_color  = $solace_extra_data['colors_data']['text_selection_color'];
            $solace_extra_api_text_selection_bg_color = $solace_extra_data['colors_data']['text_selection_bg_color'];
            $solace_extra_api_border_color          = $solace_extra_data['colors_data']['border_color'];
            $solace_extra_api_background_color      = $solace_extra_data['colors_data']['background_color'];
            $solace_extra_api_page_title_text_color = $solace_extra_data['colors_data']['page_title_text_color'];
            $solace_extra_api_page_title_bg_color   = $solace_extra_data['colors_data']['page_title_bg_color'];
            $solace_extra_api_bg_menu_dropdown_color = ! empty( $solace_extra_data['colors_data']['bg_menu_dropdown_color'] )
                ? $solace_extra_data['colors_data']['bg_menu_dropdown_color']
                : $solace_extra_data['colors_data']['border_color'];

$solace_extra_colors_data_from_api = "
--e-global-color-primary: $solace_extra_api_button_color;
--e-global-color-secondary: $solace_extra_api_page_title_bg_color;
--e-global-color-text: $solace_extra_api_base_color;
--e-global-color-accent: $solace_extra_api_bg_menu_dropdown_color;
--sol-color-base-font: $solace_extra_api_base_color;
--e-global-color-text: $solace_extra_api_base_color;
--e-global-color-solcolorbasefont: $solace_extra_api_base_color;
--e-global-color-solcolorheading: $solace_extra_api_heading_color;
--sol-color-heading: $solace_extra_api_heading_color;
--e-global-color-solcolorlinkbuttoninitial: $solace_extra_api_link_button_color;
--sol-color-link-button-initial: $solace_extra_api_link_button_color;
--e-global-color-solcolorlinkbuttonhover: $solace_extra_api_link_button_hover_color;
--sol-color-link-button-hover: $solace_extra_api_link_button_hover_color;
--e-global-color-solcolorbuttoninitial: $solace_extra_api_button_color;
--sol-color-button-initial: $solace_extra_api_button_color;
--e-global-color-solcolorbuttonhover: $solace_extra_api_button_hover_color;
--sol-color-button-hover: $solace_extra_api_button_hover_color;
--e-global-color-solcolorselectioninitial: $solace_extra_api_text_selection_color;
--sol-color-selection-initial: $solace_extra_api_text_selection_color;
--e-global-color-solcolorselectionhigh: $solace_extra_api_text_selection_bg_color;
--sol-color-selection-high: $solace_extra_api_text_selection_bg_color;
--e-global-color-solcolorborder: $solace_extra_api_border_color;
--e-global-color-solcolorbackground: $solace_extra_api_background_color;
--sol-color-background: $solace_extra_api_background_color;
--e-global-color-solcolorheadpagetitletexting: $solace_extra_api_page_title_text_color;
--sol-color-page-title-text: $solace_extra_api_page_title_text_color;
--e-global-color-solcolorpagetitletext: $solace_extra_api_page_title_text_color;
--e-global-color-solcolorpagetitlebackground: $solace_extra_api_page_title_bg_color;
--sol-color-page-title-background: $solace_extra_api_page_title_bg_color;
--e-global-color-secondary: $solace_extra_api_page_title_bg_color;
--sol-color-bg-menu-dropdown: $solace_extra_api_bg_menu_dropdown_color;
--sol-color-border: $solace_extra_api_border_color;";

            for ( $solace_extra_i = 2; $solace_extra_i <= 6; $solace_extra_i++ ) {
                $solace_extra_color_palettes[] = array(
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['base_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['heading_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['link_button_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['link_button_hover_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['button_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['button_hover_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['text_selection_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['text_selection_bg_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['border_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['background_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['page_title_text_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['page_title_bg_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['bg_menu_dropdown_color'],
                    $solace_extra_data['color_scheme']['solace_colors_elementor_' . $solace_extra_i]['publish'],
                );
            }

            

            for ( $solace_extra_i = 1; $solace_extra_i <= 8; $solace_extra_i++ ) {
                $solace_extra_palette_font_scheme[ $solace_extra_i ] = array(
                    $solace_extra_data['palette_font_scheme']['solace_palette_font_elementor_' . $solace_extra_i]['base_font'],
                    $solace_extra_data['palette_font_scheme']['solace_palette_font_elementor_' . $solace_extra_i]['heading_font'],
                    $solace_extra_data['palette_font_scheme']['solace_palette_font_elementor_' . $solace_extra_i]['image_url'],
                );
            }
            $solace_extra_defaultx_font = ! empty( $solace_extra_data['default_elementor_font']['base_font'] ) ? $solace_extra_data['default_elementor_font']['base_font'] : 'Manrope';
            $solace_extra_default_elementor_font_base = 'https://fonts.googleapis.com/css?family=' . $solace_extra_defaultx_font;
            $solace_extra_default_elementor_font_heading = 'https://fonts.googleapis.com/css?family=' . $solace_extra_data['default_elementor_font']['heading_font'];

            $solace_extra_palette_font_scheme[1] = array(
                $solace_extra_default_elementor_font_base,
                $solace_extra_default_elementor_font_heading,
                '',
            );
            // print_r($solace_extra_palette_font_scheme[0]);            
        } else {
            esc_html_e( 'Failed to decode JSON response.', 'solace-extra');
        }
    } else {
        esc_html_e( 'Failed to fetch response from the API.', 'solace-extra');
    }

    ?>
    <!-- <div class="simple-plugin-columns" style="max-width: 1240px;height: 94vh;"> -->
    <div class="simple-plugin-columns" style="height: 94vh;">
        <!-- <div class="loading-overlay">
            <dotlottie-player src="<?php //echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/step5/loading-overlay.json' ); ?>" background="transparent" speed="1" style="width: 300px; height: 300px;" loop autoplay></dotlottie-player>

        </div> -->
        <div class='col-left' >
            <div class="palette-buttons">
                <div class="selected-demo">
                    <span class='demotitle'><?php esc_html_e( 'Selected template', 'solace-extra'); ?></span>
                    <div class='labeldemo'></div>
                </div>
                <hr  />
                <div class="logo-buttons">
                    <span class='titlelogo'><?php esc_html_e( 'Logo', 'solace-extra'); ?></span>
                    <!-- <form id="upload-logo-form" action="" method="post" enctype="multipart/form-data">
                        <input type="submit" value="Upload Your Logo" id="upload-logo-formx">
                    </form> -->
                    <button id='upload-media-button' class='button'>Upload Your Logo</button>
                    
                    <a href="#" class="logo_default"><i class="fas fa-undo"></i></a>
                </div>
                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?>
                <img class="logo" src="" alt="Logo">
                <hr />
                <span class='titlecolor'><?php esc_html_e( 'Change Color Palette', 'solace-extra'); ?></span>
                <div class='colorlist'>
                    <a href="#" id="color-1" class="color active change-styles-btn" data-styles="<?php echo esc_attr( $solace_extra_colors_data_from_api ); ?>">
                        <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_api_base_color ); ?>;'></span>
                        <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_api_heading_color ); ?>;'></span>
                        <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_api_button_color ); ?>;'></span>
                        <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_api_background_color ); ?>;'></span>
                    </a>
                    
                    <?php
                    $solace_extra_count = 2;
                    for ( $solace_extra_i = 0; $solace_extra_i <= 6; $solace_extra_i++ ) {
                        if ( isset( $solace_extra_color_palettes[ $solace_extra_i ] ) ) {
                            $solace_extra_base_color = $solace_extra_color_palettes[ $solace_extra_i ][0];
                            if ( ! empty( $solace_extra_base_color ) && 'on' === $solace_extra_color_palettes[ $solace_extra_i ][13] && $solace_extra_count <= 4 ) {
                                $solace_extra_base_color            = $solace_extra_color_palettes[ $solace_extra_i ][0];
                                $solace_extra_heading_color         = $solace_extra_color_palettes[ $solace_extra_i ][1];
                                $solace_extra_link_button_color     = $solace_extra_color_palettes[ $solace_extra_i ][2];
                                $solace_extra_link_button_hover_color = $solace_extra_color_palettes[ $solace_extra_i ][3];
                                $solace_extra_button_color          = $solace_extra_color_palettes[ $solace_extra_i ][4];
                                $solace_extra_button_hover_color    = $solace_extra_color_palettes[ $solace_extra_i ][5];
                                $solace_extra_text_selection_color  = $solace_extra_color_palettes[ $solace_extra_i ][6];
                                $solace_extra_text_selection_bg_color = $solace_extra_color_palettes[ $solace_extra_i ][7];
                                $solace_extra_border_color          = $solace_extra_color_palettes[ $solace_extra_i ][8];
                                $solace_extra_background_color      = $solace_extra_color_palettes[ $solace_extra_i ][9];
                                $solace_extra_page_title_text_color = $solace_extra_color_palettes[ $solace_extra_i ][10];
                                $solace_extra_page_title_bg_color   = $solace_extra_color_palettes[ $solace_extra_i ][11];
                                $solace_extra_bg_menu_dropdown_color = $solace_extra_color_palettes[ $solace_extra_i ][12];
$solace_extra_colors_data_from_palette = "
--e-global-color-primary: $solace_extra_button_color;
--e-global-color-secondary: $solace_extra_page_title_bg_color;
--e-global-color-text: $solace_extra_base_color;
--e-global-color-accent: $solace_extra_bg_menu_dropdown_color;
--sol-color-base-font: $solace_extra_base_color;
--e-global-color-solcolorbasefont: $solace_extra_base_color;
--e-global-color-solcolorheading: $solace_extra_heading_color;
--sol-color-heading: $solace_extra_heading_color;
--e-global-color-solcolorlinkbuttoninitial: $solace_extra_link_button_color;
--sol-color-link-button-initial: $solace_extra_link_button_color;
--e-global-color-solcolorlinkbuttonhover: $solace_extra_link_button_hover_color;
--sol-color-link-button-hover: $solace_extra_link_button_hover_color;
--e-global-color-solcolorbuttoninitial: $solace_extra_button_color;
--sol-color-button-initial: $solace_extra_button_color;
--e-global-color-solcolorbuttonhover: $solace_extra_button_hover_color;
--sol-color-button-hover: $solace_extra_button_hover_color;
--e-global-color-solcolorselectioninitial: $solace_extra_text_selection_color;
--sol-color-selection-initial: $solace_extra_text_selection_color;
--e-global-color-solcolorselectionhigh: $solace_extra_text_selection_bg_color;
--sol-color-selection-high: $solace_extra_text_selection_bg_color;
--e-global-color-solcolorborder: $solace_extra_border_color;
--e-global-color-solcolorbackground: $solace_extra_background_color;
--sol-color-background: $solace_extra_background_color;
--e-global-color-solcolorheadpagetitletexting: $solace_extra_page_title_text_color;
--sol-color-page-title-text: $solace_extra_page_title_text_color;
--e-global-color-solcolorpagetitletext: $solace_extra_page_title_text_color;
--e-global-color-solcolorpagetitlebackground: $solace_extra_page_title_bg_color;
--sol-color-page-title-background: $solace_extra_page_title_bg_color;
--e-global-color-secondary: $solace_extra_page_title_bg_color;
--sol-color-bg-menu-dropdown: $solace_extra_bg_menu_dropdown_color;
--sol-color-border: $solace_extra_border_color;";?>
                                <a href="#" id="color-<?php echo esc_attr( $solace_extra_count ); ?>" class="color change-styles-btn" data-styles="<?php echo esc_attr( $solace_extra_colors_data_from_palette );?>">
                                    <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_base_color ); ?>;'></span>
                                    <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_heading_color ); ?>;'></span>
                                    <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_button_color ); ?>;'></span>
                                    <span class='color_hex' style='background-color:<?php echo esc_html( $solace_extra_background_color ); ?>;'></span>
                                </a>
                            <?php
                            $solace_extra_count++;
                            }
                        }
                    }?>
                </div>
                <hr />
                <span class='titlecolor'><?php esc_html_e( 'Change Font Style', 'solace-extra'); ?></span>                
                <div class="fontlist">
                <?php
                    $solace_extra_fontlist = '<div class="fontlist">';
                    foreach ( $solace_extra_palette_font_scheme as $solace_extra_index => $solace_extra_font_pair ){
                        if ( ! empty( $solace_extra_font_pair[0] ) ) {
                            $solace_extra_font1 = solace_extra_getGoogleFontsFamilyName( $solace_extra_font_pair[0] );
                            $solace_extra_font2 = solace_extra_getGoogleFontsFamilyName( $solace_extra_font_pair[1] );

                            // Membuat CSS untuk font styles
                            $solace_extra_font_data_from_scheme = "
--bodyfontfamily: '$solace_extra_font1';
--e-global-typography-primary-font-family: '$solace_extra_font1';
--e-global-typography-secondary-font-family: '$solace_extra_font1';
--e-global-typography-text-font-family: '$solace_extra_font1';
p:'$solace_extra_font1';
--e-global-typography-solace_h1_font_family_general-font-family: '$solace_extra_font2';
--e-global-typography-solace_h2_font_family_general-font-family: '$solace_extra_font2';
--e-global-typography-solace_h3_font_family_general-font-family: '$solace_extra_font2';
--e-global-typography-solace_h4_font_family_general-font-family: '$solace_extra_font2';
--e-global-typography-solace_h5_font_family_general-font-family: '$solace_extra_font2';
--e-global-typography-solace_h6_font_family_general-font-family: '$solace_extra_font2';";

                            // Membuat URL untuk Google Fonts
                            $solace_extra_google_fonts_url = "https://fonts.googleapis.com/css?family=" . urlencode( $solace_extra_font1 ) . "|" . urlencode( $solace_extra_font2 );

                            // Memuat Google Fonts ke halaman WordPress
                            wp_enqueue_style( 'solace-google-fonts-' . $solace_extra_index, esc_url( $solace_extra_google_fonts_url ), array(), $this->version, false );
                            ?>

                            <!-- Link untuk mengubah font styles -->
                            <a href="#" class="font tooltip change-font-styles-btn" data-font-styles="<?php echo esc_attr( $solace_extra_font_data_from_scheme ); ?>">
                                <span class="font tooltip" id="font-<?php echo esc_html( $solace_extra_index ); ?>" fontname="<?php echo esc_html( $solace_extra_font1 ) . ' & '. esc_html( $solace_extra_font2 ); ?>">
                                    <div class="f_group">
                                        <span class="font1" style="font-family: <?php echo esc_html( $solace_extra_font1 ); ?>;">A</span>
                                        <span class="font2" style="font-family: <?php echo esc_html( $solace_extra_font2 ); ?>;">a</span>
                                    </div>
                                </span>
                            </a>

                            <?php

                        }
                    }
                    ?>
                    </div>
                <hr />
                <?php 
                // Determine if selected demo is PRO using server-side API (no UI flicker)
                $solace_extra_selected_demo_is_pro = false;
                $solace_extra_selected_demo_slug  = ! empty( $_GET['demo'] ) ? sanitize_key( wp_unslash( $_GET['demo'] ) ) : '';
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
                                $solace_extra_demo_link = trim( $solace_extra_demo_item['demo_link'] );
                                $solace_extra_demo_link = rtrim( $solace_extra_demo_link, '/' );
                                $solace_extra_parts     = explode( '/', $solace_extra_demo_link );
                                $solace_extra_slug      = strtolower( end( $solace_extra_parts ) );
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

                $solace_extra_saved_key       = get_option( 'solace_license_key', '' );
                $status                       = get_option( 'solace_license_status', '' );
                $solace_extra_license_info    = false;
                $solace_extra_is_license_valid = false;

                // Check license if function exists (from solace-extra-pro plugin)
                if ( function_exists( 'solace_check_license' ) && ! empty( $solace_extra_saved_key ) ) {
                    $solace_extra_license_info = solace_check_license( $solace_extra_saved_key );
            
                    if ( $solace_extra_license_info && isset( $solace_extra_license_info->license ) ) {
                        // Check if license status is valid
                        $solace_extra_license_status  = $solace_extra_license_info->license;
                        $solace_extra_is_license_valid = ( 'valid' === $solace_extra_license_status || 'active' === $solace_extra_license_status );
                    }
                }                
                ?>
                <?php if ( ! ( $solace_extra_selected_demo_is_pro && ! $solace_extra_is_license_valid ) ) : ?>
                    <div class="box-delete">
                        <input type="checkbox" id="delete-imported" name="delete-imported" >
                        <label for="delete-imported"><?php esc_html_e( 'Delete Previously imported sites', 'solace-extra' ); ?></label>
                    </div>
                <?php endif; ?>
                
                    <?php  
                    $solace_extra_data_plugin          = Solace_Extra_Admin::get_required_plugin();
                    $solace_extra_elementor_plugin_slug = 'elementor/elementor.php';
                    $solace_extra_elementor_installed   = Solace_Extra_Admin::is_plugin_installed( $solace_extra_elementor_plugin_slug );
                    $solace_extra_elementor_active      = class_exists( 'Elementor\Plugin' );

                    $solace_extra_wc_plugin_slug = 'woocommerce/woocommerce.php';
                    $solace_extra_wc_installed   = Solace_Extra_Admin::is_plugin_installed( $solace_extra_wc_plugin_slug );
                    $solace_extra_wc_active      = class_exists( 'WooCommerce' );

                    if ( isset( $solace_extra_data_plugin['page_builder'] ) && $solace_extra_data_plugin['page_builder'] && ! $solace_extra_elementor_active ) : ?>
                        <div class="box-required-plugin-elementor">
                            <input type="checkbox" id="required-plugin-elementor" name="required-plugin-elementor" />
                            <label for="required-plugin-elementor">
                                <span class="text">
                                    <?php
                                    if ( $solace_extra_elementor_installed ) {
                                        esc_html_e( 'Activate Elementor plugin', 'solace-extra' );
                                    } else {
                                        esc_html_e( 'Install and activate Elementor plugin', 'solace-extra' );
                                    }
                                    ?>
                                </span>
                                <span class="text required"><?php esc_html_e( '(required)', 'solace-extra' ); ?></span>
                                <a href="<?php echo esc_url( 'https://wordpress.org/plugins/elementor/' ); ?>" target="_blank">
                                    <span class="dashicons dashicons-admin-links"></span>
                                </a>
                            </label>
                        </div>
                    <?php endif; ?>


                    <?php if ( isset( $solace_extra_data_plugin['ecommerce'] ) && $solace_extra_data_plugin['ecommerce'] && ! $solace_extra_wc_active ) : ?>
                        <div class="box-required-plugin-ecommerce">
                            <input type="checkbox" id="required-plugin-ecommerce" name="required-plugin-ecommerce" />
                            <label for="required-plugin-ecommerce">
                                <span class="text">
                                    <?php
                                    if ( $solace_extra_wc_installed ) {
                                        esc_html_e( 'Activate WooCommerce plugin', 'solace-extra' );
                                    } else {
                                        esc_html_e( 'Install and activate WooCommerce plugin', 'solace-extra' );
                                    }
                                    ?>
                                </span>
                                <span class="text required"><?php esc_html_e( '(required)', 'solace-extra' ); ?></span>
                                <a href="<?php echo esc_url( 'https://wordpress.org/plugins/woocommerce/' ); ?>" target="_blank">
                                    <span class="dashicons dashicons-admin-links"></span>
                                </a>
                            </label>
                        </div>
                    <?php endif; ?>
                
                <?php $solace_extra_class_pro_active_status = ''; ?>
                <?php if ( $solace_extra_selected_demo_is_pro && ! $solace_extra_is_license_valid ) : ?>
                    <?php $solace_extra_class_pro_active_status = 'pro-not-active'; ?>
                <?php endif; ?>
                <div id="solace-extra-action-button" class="<?php echo esc_attr( $solace_extra_class_pro_active_status ); ?>">
                    <a href="#" id="solace-extra-back-link">
                        <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?>
                        <img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/sol-back.png' ); ?>" /></a>
                    <?php
                    $solace_extra_toggle_btn_continue = 'deactive';
                    if ( isset( $solace_extra_data_plugin['page_builder'] ) && isset( $solace_extra_data_plugin['ecommerce'] ) ) {
                        if ( $solace_extra_data_plugin['page_builder'] && $solace_extra_data_plugin['ecommerce'] ) {
                            if ( class_exists( 'Elementor\Plugin' ) && class_exists( 'WooCommerce' ) ) {
                                $solace_extra_toggle_btn_continue = 'active';
                            }  
                        } else if ( $solace_extra_data_plugin['page_builder'] && ! $solace_extra_data_plugin['ecommerce'] ) {
                            if ( class_exists( 'Elementor\Plugin' ) ) {
                                $solace_extra_toggle_btn_continue = 'active';
                            }  
                        }
                    }
                    ?>
                    <?php if ( $solace_extra_selected_demo_is_pro && ! $solace_extra_is_license_valid ) : ?>
                        <?php 
                        $url = get_admin_url() . 'admin.php?page=dashboard-starter-templates&type=elementor';
                        wp_redirect( $url, 301 ); 
                        exit;
                        ?>
                    <?php else : ?>
                        <button id="solace-extra-continue" disabled>
                            <?php esc_html_e( 'Continue', 'solace-extra' ); ?>
                        </button>
                    <?php endif; ?>                    

                </div>
                <?php if ( ! ( $solace_extra_selected_demo_is_pro && ! $solace_extra_is_license_valid ) ) : ?>
                    <div class="container-license">
                        <div class="box-title-license">
                            <p class="title-license">
                                <?php esc_html_e( 'Image License', 'solace-extra' ); ?>
                            </p>
                            <svg class="arrow-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M201.4 374.6c12.5 12.5 32.8 12.5 45.3 0l160-160c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L224 306.7 86.6 169.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l160 160z"/></svg>
                        </div>
                        <div class="box-content-license">
                            <?php esc_html_e( 'Images are included in the installation. However, you need to license the images before you can use them in your website or you can replace them with your own.', 'solace-extra' ); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
        </div>
        <div class='col-right iframeContainer'  style="position: relative; width: 100%; height: 94vh; overflow: auto;background-image: url('<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/loading-website.png' ); ?>');">
            <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?>
            <img class='urlbar' src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/step5/urlbar4.png'  ); ?>"  />
            <iframe id='solaceIframe' src='<?php echo esc_attr( $solace_extra_iframe_url ); ?>' ></iframe>
        </div>
    </div>
</div>

<script type="text/javascript">
    var ajaxurl = "<?php echo esc_url( admin_url('admin-ajax.php') ); ?>";
</script>
<script>

    jQuery(document).ready(function($) { 

        function updateContinueButtonState() {
            const needsElementor = $('#required-plugin-elementor').length;
            const needsWC = $('#required-plugin-ecommerce').length;

            const isElementorChecked = $('#required-plugin-elementor').is(':checked');
            const isWCChecked = $('#required-plugin-ecommerce').is(':checked');

            const $btn = $('#solace-extra-continue');

            const shouldEnable = (!needsElementor || isElementorChecked) && (!needsWC || isWCChecked);

            if (shouldEnable) {
                $btn.removeAttr('disabled')
                    .removeClass('disabled')
                    .addClass('active');
            } else {
                $btn.attr('disabled', 'disabled')
                    .addClass('disabled')
                    .removeClass('active');
            }
        }


        $('#required-plugin-elementor, #required-plugin-ecommerce').on('change', updateContinueButtonState);
        updateContinueButtonState();

        $('#solace-extra-continue').on('click', function(e) {
            e.preventDefault(); // prevent default always first
            const $btn = $(this);
            if ($btn.hasClass('disabled')) {
                alert('disable');
                return false;
            }
        });
		// Upgrade button click handler
		$('#solace-extra-upgrade').on('click', function(e) {
			e.preventDefault();
			var upgradeUrl = 'http://pro.solacewp.com/';
			window.open(upgradeUrl, '_blank');
		});
    });

    // let solHasSetPreviewHeight = false
    const datax = { type: 'deleteLocal', value: '' };

    setTimeout(() => {
        postMessageToIframex(datax);
    }, 5000); // 3000 milidetik = 3 detik
    
    const iframex = document.getElementById('solaceIframe');

    function postMessageToIframex(data) {
        // Mengirim pesan ke iframe dengan data yang ditentukan
        iframex.contentWindow.postMessage(data, '<?php echo esc_js( untrailingslashit( SOLACE_EXTRA_DEMO_IMPORT_URL ) ); ?>'); // Sesuaikan URL sesuai dengan domain iframe
        // iframe.contentWindow.postMessage(data, 'https://stagging-solace.djavaweb.com'); // Sesuaikan URL sesuai dengan domain iframe
        console.log('Post message sent to iframe:', data);
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const defa = "";
        const data = { type: 'deleteLocal', value: defa };
        postMessageToIframex(data);
        console.log('domloaded');
    });

    function clearStoredStyles() {
        localStorage.removeItem('appliedStyles');
        localStorage.removeItem('appliedFontStyles');
        document.body.style.cssText = ''; // Reset style di elemen body
        console.log('Stored styles cleared from LocalStorage');
    }

    clearStoredStyles();


    jQuery(document).ready(function($) { 
        
        let demoUrl = localStorage.getItem('solaceDemoName');
        demoUrl = '<?php echo esc_js( trailingslashit( SOLACE_EXTRA_DEMO_IMPORT_URL ) ); ?>' + demoUrl;
        // let demoName = localStorage.getItem('solaceDemoName');
        let demoName = getParameterByName('demo');
        demoName = demoName.replace(/-/g, ' ').replace(/\b\w/g, char => char.toUpperCase());

        console.log (demoName);
        $('.labeldemo').text(demoName);

        if (demoUrl) {
            console.log('localStorage Data:', demoUrl);
        }else {
            const demoType = getParameterByName('demoType');
            window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-starter-templates&type=' + demoType;
        }
            
        var colorPalette2 = <?php echo wp_json_encode( $solace_extra_color_palettes[0] ); ?>;
        var colorPalette3 = <?php echo wp_json_encode( $solace_extra_color_palettes[1] ); ?>;
        var colorPalette4 = <?php echo wp_json_encode( $solace_extra_color_palettes[2] ); ?>;
        var colorPalette5 = <?php echo wp_json_encode( $solace_extra_color_palettes[3] ); ?>;
        var colorPalette6 = <?php echo wp_json_encode( $solace_extra_color_palettes[4] ); ?>;

        var defaultColorValue = {
            new_base_color: '<?php echo sanitize_hex_color( $solace_extra_api_base_color ); ?>',
            new_heading_color: '<?php echo sanitize_hex_color( $solace_extra_api_heading_color ); ?>',
            new_link_button_color: '<?php echo sanitize_hex_color( $solace_extra_api_link_button_color ); ?>',
            new_link_button_hover_color: '<?php echo sanitize_hex_color( $solace_extra_api_link_button_hover_color ); ?>',
            new_button_color: '<?php echo sanitize_hex_color( $solace_extra_api_button_color ); ?>',
            new_button_hover_color: '<?php echo sanitize_hex_color( $solace_extra_api_button_hover_color ); ?>',
            new_text_selection_color: '<?php echo sanitize_hex_color( $solace_extra_api_text_selection_color ); ?>',
            new_text_selection_bg_color: '<?php echo sanitize_hex_color( $solace_extra_api_text_selection_bg_color ); ?>',
            new_border_color: '<?php echo sanitize_hex_color( $solace_extra_api_border_color ); ?>',
            new_background_color: '<?php echo sanitize_hex_color( $solace_extra_api_background_color ); ?>',
            new_page_title_text_color: '<?php echo sanitize_hex_color( $solace_extra_api_page_title_text_color ); ?>',
            new_page_title_bg_color: '<?php echo sanitize_hex_color( $solace_extra_api_page_title_bg_color ); ?>',
            new_bg_menu_dropdown_color: '<?php echo ! empty( $solace_extra_api_bg_menu_dropdown_color ) ? sanitize_hex_color( $solace_extra_api_bg_menu_dropdown_color ) : sanitize_hex_color( $solace_extra_api_border_color ); ?>'
        }

        console.log(defaultColorValue);
        var jsonColorString = JSON.stringify(defaultColorValue);
        localStorage.setItem('solace_step5_color', jsonColorString);


        var bodyFontFamily = "";
        var headingFontFamily = "";
        var selectedColorPalette ="";

        // set property for body font
        document.documentElement.style.setProperty('--bodyfontfamily', "'<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][0] ) );?>'");
        $('.preview h1').css('font-family', 'var(--bodyfontfamily)');

        //set property for heading font
        $('.elementor-heading-title').css('font-family','<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][1] ) );?>');
        document.documentElement.style.setProperty('--e-global-typography-primary-font-family','<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][1] ) );?>');

        var defaultFontValue = { 
            new_solace_body_font_family: '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][0] ) );?>',
            new_solace_heading_font_family_general: '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][1] ) );?>'
        };
        console.log(defaultFontValue);
        var jsonFontString = JSON.stringify(defaultFontValue);
        localStorage.setItem('solace_step5_font', jsonFontString);
        
        var link1 = document.createElement('link');
        link1.href = '<?php echo esc_html( $solace_extra_palette_font_scheme[1][0] );?>';
        link1.rel = 'stylesheet';
        $('head').append(link1);
        var link2 = document.createElement('link');
        link2.href = '<?php echo esc_html( $solace_extra_palette_font_scheme[1][1] );?>';
        link2.rel = 'stylesheet';
        $('head').append(link2);

        var previewElement = document.querySelector('.preview');

        function getParameterByName(name) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(window.location.search);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        var firstload = true;

        // Remove the 'solaceRemoveImported' value from localStorage
        localStorage.removeItem('solaceRemoveImported');

        // Attach a click event listener to the checkbox inside the '.box-delete' element
        $('.box-delete input').click(function(e){
            // Check if the checkbox is checked
            var isChecked = $('#delete-imported').is(':checked');

            // Get the updated status of the checkbox
            var updatedStatus = $(this).is(':checked');

            // Update the status to a string value: "remove" if checked, "skip" if unchecked
            if ( updatedStatus ) {
                updatedStatus = "remove";
            } else {
                updatedStatus = "skip";
            }

            // Store the updated status in localStorage with the key 'solaceRemoveImported'
            localStorage.setItem('solaceRemoveImported', updatedStatus);
        });

        $('#solace-extra-back-link').click(function(){
            const demoType = getParameterByName('type');
            let admin = step5.site_url + '/wp-admin/admin.php?page=dashboard-starter-templates&type=' + demoType;
            window.location.replace(admin);
        })

        $('#color-1').click(function(e) {
            e.preventDefault();
            $('.colorlist .color').removeClass('active');
            $(this).addClass('active');
            
            var colorValue = { 
                new_base_color: "<?php echo sanitize_hex_color( $solace_extra_api_base_color ); ?>",
                new_heading_color: "<?php echo sanitize_hex_color( $solace_extra_api_heading_color ); ?>",
                new_link_button_color: "<?php echo sanitize_hex_color( $solace_extra_api_link_button_color ); ?>",
                new_link_button_hover_color: "<?php echo sanitize_hex_color( $solace_extra_api_link_button_hover_color ); ?>",
                new_button_color: "<?php echo sanitize_hex_color( $solace_extra_api_button_color ); ?>",
                new_button_hover_color: "<?php echo sanitize_hex_color( $solace_extra_api_button_hover_color ); ?>",
                new_text_selection_color: "<?php echo sanitize_hex_color( $solace_extra_api_text_selection_color ); ?>",
                new_text_selection_bg_color: "<?php echo sanitize_hex_color( $solace_extra_api_text_selection_bg_color ); ?>",
                new_border_color: "<?php echo sanitize_hex_color( $solace_extra_api_border_color ); ?>",
                new_background_color: "<?php echo sanitize_hex_color( $solace_extra_api_background_color ); ?>",
                new_page_title_text_color: "<?php echo sanitize_hex_color( $solace_extra_api_page_title_text_color ); ?>",
                new_page_title_bg_color: "<?php echo sanitize_hex_color( $solace_extra_api_page_title_bg_color ); ?>",
                new_bg_menu_dropdown_color: "<?php echo sanitize_hex_color( $solace_extra_api_bg_menu_dropdown_color ); ?>",
            };
            console.log(colorValue);
            var jsonString = JSON.stringify(colorValue);
            localStorage.setItem('solace_step5_color', jsonString);
        });

        $('#color-2').click(function(e) {
            e.preventDefault();
            $('.colorlist .color').removeClass('active');
            $(this).addClass('active');
            
            var colorValue = { 
                new_base_color: "<?php echo esc_js( $solace_extra_color_palettes[0][0] ); ?>",
                new_heading_color: "<?php echo esc_js( $solace_extra_color_palettes[0][1] ); ?>",
                new_link_button_color: "<?php echo esc_js( $solace_extra_color_palettes[0][2] ); ?>",
                new_link_button_hover_color: "<?php echo esc_js( $solace_extra_color_palettes[0][3] ); ?>",
                new_button_color: "<?php echo esc_js( $solace_extra_color_palettes[0][4] ); ?>",
                new_button_hover_color: "<?php echo esc_js( $solace_extra_color_palettes[0][5] ); ?>",
                new_text_selection_color: "<?php echo esc_js( $solace_extra_color_palettes[0][6] ); ?>",
                new_text_selection_bg_color: "<?php echo esc_js( $solace_extra_color_palettes[0][7] ); ?>",
                new_border_color: "<?php echo esc_js( $solace_extra_color_palettes[0][8] ); ?>",
                new_background_color: "<?php echo esc_js( $solace_extra_color_palettes[0][9] ); ?>",
                new_page_title_text_color: "<?php echo esc_js( $solace_extra_color_palettes[0][10] ); ?>",
                new_page_title_bg_color: "<?php echo esc_js( $solace_extra_color_palettes[0][11] ); ?>",
                new_bg_menu_dropdown_color: "<?php echo esc_js( $solace_extra_color_palettes[0][12] ); ?>"
            };

            console.log(colorValue);
            var jsonString = JSON.stringify(colorValue);
            localStorage.setItem('solace_step5_color', jsonString);
        });
        $('#color-3').click(function(e) {
            e.preventDefault();
            $('.colorlist .color').removeClass('active');
            $(this).addClass('active');
            
            var colorValue = { 
                new_base_color: "<?php echo esc_js( $solace_extra_color_palettes[1][0] ); ?>",
                new_heading_color: "<?php echo esc_js( $solace_extra_color_palettes[1][1] ); ?>",
                new_link_button_color: "<?php echo esc_js( $solace_extra_color_palettes[1][2] ); ?>",
                new_link_button_hover_color: "<?php echo esc_js( $solace_extra_color_palettes[1][3] ); ?>",
                new_button_color: "<?php echo esc_js( $solace_extra_color_palettes[1][4] ); ?>",
                new_button_hover_color: "<?php echo esc_js( $solace_extra_color_palettes[1][5] ); ?>",
                new_text_selection_color: "<?php echo esc_js( $solace_extra_color_palettes[1][6] ); ?>",
                new_text_selection_bg_color: "<?php echo esc_js( $solace_extra_color_palettes[1][7] ); ?>",
                new_border_color: "<?php echo esc_js( $solace_extra_color_palettes[1][8] ); ?>",
                new_background_color: "<?php echo esc_js( $solace_extra_color_palettes[1][9] ); ?>",
                new_page_title_text_color: "<?php echo esc_js( $solace_extra_color_palettes[1][10] ); ?>",
                new_page_title_bg_color: "<?php echo esc_js( $solace_extra_color_palettes[1][11] ); ?>",
                new_bg_menu_dropdown_color: "<?php echo esc_js( $solace_extra_color_palettes[1][12] ); ?>"
            };

            console.log(colorValue);
            var jsonString = JSON.stringify(colorValue);
            localStorage.setItem('solace_step5_color', jsonString);
        });
        $('#color-4').click(function(e) {
            e.preventDefault();
            $('.colorlist .color').removeClass('active');
            $(this).addClass('active');
            
            var colorValue = { 
                new_base_color: "<?php echo esc_js( $solace_extra_color_palettes[2][0] ); ?>",
                new_heading_color: "<?php echo esc_js( $solace_extra_color_palettes[2][1] ); ?>",
                new_link_button_color: "<?php echo esc_js( $solace_extra_color_palettes[2][2] ); ?>",
                new_link_button_hover_color: "<?php echo esc_js( $solace_extra_color_palettes[2][3] ); ?>",
                new_button_color: "<?php echo esc_js( $solace_extra_color_palettes[2][4] ); ?>",
                new_button_hover_color: "<?php echo esc_js( $solace_extra_color_palettes[2][5] ); ?>",
                new_text_selection_color: "<?php echo esc_js( $solace_extra_color_palettes[2][6] ); ?>",
                new_text_selection_bg_color: "<?php echo esc_js( $solace_extra_color_palettes[2][7] ); ?>",
                new_border_color: "<?php echo esc_js( $solace_extra_color_palettes[2][8] ); ?>",
                new_background_color: "<?php echo esc_js( $solace_extra_color_palettes[2][9] ); ?>",
                new_page_title_text_color: "<?php echo esc_js( $solace_extra_color_palettes[2][10] ); ?>",
                new_page_title_bg_color: "<?php echo esc_js( $solace_extra_color_palettes[2][11] ); ?>",
                new_bg_menu_dropdown_color: "<?php echo esc_js( $solace_extra_color_palettes[2][12] ); ?>"
            };

            console.log(colorValue);
            var jsonString = JSON.stringify(colorValue);
            localStorage.setItem('solace_step5_color', jsonString);
        });

        $('#font-1').addClass('active');
        
        $('#font-1').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[1][1] ) );?>';

            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);

            
        });

        $('#font-2').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[2][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[2][1] ) );?>';

            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);

            
        });

        $('#font-3').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[3][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[3][1] ) );?>';

            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);
            
        });

        $('#font-4').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[4][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[4][1] ) );?>';

            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);
            
        });

        $('#font-5').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[5][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[5][1] ) );?>';
            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);
            
        });

        $('#font-6').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[6][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[6][1] ) );?>';

            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);
            
        });

        $('#font-7').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[7][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[7][1] ) );?>';
            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);
                
        });

        $('#font-8').click(function(e) {
            e.preventDefault();
            $('.fontlist .font').removeClass('active');
            $(this).addClass('active');
            bodyFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[8][0] ) );?>';
            headingFontFamily = '<?php echo esc_html( solace_extra_getGoogleFontsFamilyName( $solace_extra_palette_font_scheme[8][1] ) );?>';

            var fontValue = { 
                new_solace_body_font_family: bodyFontFamily,
                new_solace_heading_font_family_general: headingFontFamily
            };
            console.log(fontValue);
            var jsonString = JSON.stringify(fontValue);
            localStorage.setItem('solace_step5_font', jsonString);
            
        });

        // Active / Disable style button continue
        $('.box-required-plugin-elementor input, .box-required-plugin-ecommerce input').change(function() {
            let requiredPluginElementor = $('.box-required-plugin-elementor input').prop('checked');
            let requiredPluginWoocommerce = $('.box-required-plugin-ecommerce input').prop('checked');

            // Input checkbox Elementor && Ecommerce found.
            if ( $('.box-required-plugin-elementor input').length > 0 && $('.box-required-plugin-ecommerce input').length > 0 ) {

                // Checkbox CHECKED (2 checked) Elementor && Ecommerce
                if (requiredPluginElementor && requiredPluginWoocommerce) {
                    $('#solace-extra-continue').addClass('active');
                }
                
                // Checkbox Elementor UNCHECKED
                if (!requiredPluginElementor) {
                    $('#solace-extra-continue').removeClass('active');
                }

                // Checkbox Ecommerce UNCHECKED
                if (!requiredPluginWoocommerce) {
                    $('#solace-extra-continue').removeClass('active');
                }

            } else if ( $('.box-required-plugin-elementor input').length > 0 ) { // Input checkbox Elementor found.

                // Checkbox CHECKED (1 checked) Elementor
                if (requiredPluginElementor) {
                    $('#solace-extra-continue').addClass('active');
                } else {
                    $('#solace-extra-continue').removeClass('active');
                }
            
            } else if ( $('.box-required-plugin-ecommerce input').length > 0 ) { // Input checkbox Ecommerce found.

                // Checkbox CHECKED (1 checked) Ecommerce
                if (requiredPluginWoocommerce) {
                    $('#solace-extra-continue').addClass('active');
                } else {
                    $('#solace-extra-continue').removeClass('active');
                }

            }
        });

        $('#solace-extra-continue').click(function(e) {

            let requiredPluginElementor = $('.box-required-plugin-elementor input').prop('checked');
            let requiredPluginWoocommerce = $('.box-required-plugin-ecommerce input').prop('checked');

            // Active page builder + ecommerce
            if (required_plugin.plugins.page_builder && required_plugin.plugins.ecommerce) {

                // Input checkbox Elementor found.
                if ( $('.box-required-plugin-elementor input').length > 0 ) {
                    // Input checkbox Elementor not checked.
                    if ( ! requiredPluginElementor ) {
                        swal({
                            title: "Warning!",
                            text: "This template needs the required plugins to continue the import process. Please check the checkbox to Install and activate the required plugins.",
                            icon: "warning"
                        });
                        return;
                    }
                }

                // Input checkbox Woocommerce found.
                if ( $('.box-required-plugin-ecommerce input').length > 0 ) {
                    // Input checkbox Woocommerce not checked.
                    if ( ! requiredPluginWoocommerce ) {
                        swal({
                            title: "Warning!",
                            text: "This template needs the required plugins to continue the import process. Please check the checkbox to Install and activate the required plugins.",
                            icon: "warning"
                        });
                        return;
                    }
                }

                if ( typeof requiredPluginElementor !== 'undefined' && typeof requiredPluginWoocommerce !== 'undefined' ) {
                    if (!requiredPluginElementor || !requiredPluginWoocommerce) {
                        swal({
                            title: "Warning!",
                            text: "This template needs the required plugins to continue the import process. Please check the checkbox to Install and activate the required plugins.",
                            icon: "warning"
                        });
                        return;
                    }
                }

            // Active page builder
            } else if (required_plugin.plugins.page_builder) {

                // Input checkbox Elementor found.
                if ( $('.box-required-plugin-elementor input').length > 0 ) {
                    // Input checkbox Elementor not checked.
                    if ( ! requiredPluginElementor ) {
                        swal({
                            title: "Warning!",
                            text: "This template needs the required plugins to continue the import process. Please check the checkbox to Install and activate the required plugins.",
                            icon: "warning"
                        });
                        return;
                    }
                }                

                if ( typeof requiredPluginElementor !== 'undefined' ) {
                    if (!requiredPluginElementor) {
                        swal({
                            title: "Warning!",
                            text: "This template needs the required plugins to continue the import process. Please check the checkbox to Install and activate the required plugins.",
                            icon: "warning"
                        });
                        return;
                    }
                }
            }

            // if (attachment_id){
            //     setLogoURL(attachment_id);
            // }

            var solaceStep6 = localStorage.getItem('solaceStep6');
            console.log ('done step6? :'+solaceStep6);
            if (solaceStep6 === 'success') {
                const demoType = getParameterByName('type');
                const demoUrl = '<?php echo esc_js( trailingslashit( SOLACE_EXTRA_DEMO_IMPORT_URL ) ); ?>' + getParameterByName('demo');
                const demoName = getParameterByName('demo');

                // Function to get the current time
                function getCurrentTime() {
                    var now = new Date();
                    return now.toLocaleString(); // Using local date and time format
                }

                if (localStorage.getItem('solaceDuration')) {
                    // If it exists, remove it first
                    localStorage.removeItem('solaceDuration');
                }

                // Get the time when the button is clicked
                var currentTime = getCurrentTime();

                // Save the time value to local storage with the name 'solaceDuration'
                localStorage.setItem('solaceDuration', currentTime);

                // Check if the 'solaceListDemo' key already exists in local storage
                if (localStorage.getItem('solaceRemoveDataDemo')) {
                    // Retrieve the existing value
                    let existingValue = localStorage.getItem('solaceRemoveDataDemo');

                    // Combine the existing value with the new data, separated by a comma
                    let combinedValue = existingValue + ',' + demoName;

                    // Save the combined value back to local storage
                    localStorage.setItem('solaceRemoveDataDemo', combinedValue);
                } else {
                    // If 'solaceRemoveDataDemo' doesn't exist in local storage, create it and store the new data
                    localStorage.setItem('solaceRemoveDataDemo', demoName);
                }                

                window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();

            } else {
                const demoType = getParameterByName('type');
                const demoUrl = '<?php echo esc_js( trailingslashit( SOLACE_EXTRA_DEMO_IMPORT_URL ) ); ?>' + getParameterByName('demo');
                const demoName = getParameterByName('demo');
                window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-step6&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();
            }

            /**
             * Checks if an item stored in local storage under the specified key has expired.
             * If the item has expired, it removes the item from storage and redirects the user 
             * to a specified page with query parameters. Additionally, it checks if the item 
             * has been expired for more than 7 days.
             * 
             * @param {string} key - The key of the item in local storage to retrieve and check.
             */            
            function solaceGetWithExpiry(key) {
                // // Simulating the stored item
                // let testItemStr = JSON.stringify({
                //     value: 'success',
                //     expiry: new Date(new Date().getTime() - (8 * 24 * 60 * 60 * 1000)).toISOString() // Expiry date 8 days ago
                // });

                // // Parse the stored item to an object
                // let testItem = JSON.parse(testItemStr);
                // const testNow = new Date(); // Current date and time

                // // Convert the expiry date string back to a Date object
                // const testExpiryDate = new Date(testItem.expiry);

                // // Log current date, expiry date, and comparison for debugging
                // console.log("Current date (testNow):", testNow);
                // console.log("Expiry date (expired 8 days ago):", testExpiryDate);
                // console.log("Expiry: ", testNow > testExpiryDate);

                // return;

                // Retrieve item from local storage by key
                const itemStr = localStorage.getItem(key);
                if (!itemStr) {
                    return null; // Return null if item doesn't exist
                }

                // Parse the stored item to an object
                const item = JSON.parse(itemStr);
                const now = new Date(); // Current date and time

                // Convert the expiry date string back to a Date object
                const expiryDate = new Date(item.expiry);

                // Log current date, expiry date, and comparison for debugging
                // console.log(now);
                // console.log(expiryDate);
                // console.log(now > expiryDate);

                // Get query parameters for redirection
                const demoType = getParameterByName('type');
                const demoUrl = '<?php echo esc_js( trailingslashit( SOLACE_EXTRA_DEMO_IMPORT_URL ) ); ?>' + getParameterByName('demo');
                const demoName = getParameterByName('demo');                 

                // Check if the current date is past the expiry date
                if (now > expiryDate) {
                    // Remove the expired item from local storage
                    localStorage.removeItem(key);

                    window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-step6&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();
                } else {

                    window.location = pluginUrl.admin_url + 'admin.php?page=dashboard-progress&type=' + demoType + '&demo=' + demoName + '&timestamp=' + new Date().getTime();
                }
            }

            // Call the function to check if data with the key 'solace-skip-no-thanks' has expired
            solaceGetWithExpiry('solace-skip-no-thanks');

        });

        $( '.container-license .box-title-license' ).click(function(e) {
            $( '.container-license .box-title-license svg.arrow-icon' ).toggleClass( 'active' );
            $( '.box-content-license' ).slideToggle( 300 );
        });
    });

</script>
