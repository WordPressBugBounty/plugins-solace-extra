<?php defined( 'ABSPATH' ) || exit; ?>
<?php $solace_extra_customizer_link = admin_url('customize.php'); ?>
<?php $solace_extra_myadmin = site_url(); ?>
<div class="wrap">
    <?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/header.php'; ?>
    <section class="start-templates">
        <div class="content-top">
            <div class="mycontainer">
                <div class="boxes">
                    <div class="col col1"></div>
                    <div class="col col2">
                        <h2>
                            <?php esc_html_e( 'Get Started With Our Free', 'solace-extra' ); ?>
                            <span><?php esc_html_e( 'Starter Templates', 'solace-extra' ); ?></span>
                        </h2>
                    </div>
                    <div class="col col3">
                        <div class="dropdown1">
                            <select name="filter1" id="filter1">
                                <option value="all"><?php esc_html_e( 'All', 'solace-extra' ); ?></option>
                                <option value="blog"><?php esc_html_e( 'Blog', 'solace-extra' ); ?></option>
                                <option value="news"><?php esc_html_e( 'News', 'solace-extra' ); ?></option>
                            </select>
                        </div>
                        <div class="dropdown2"></div>
                        <div class="dropdown3">
                            <select name="filter1" id="filter1">
                                <option value="populer"><?php esc_html_e( 'Populer', 'solace-extra' ); ?></option>
                                <option value="all"><?php esc_html_e( 'All', 'solace-extra' ); ?></option>
                                <option value="new"><?php esc_html_e( 'New', 'solace-extra' ); ?></option>
                            </select>
                        </div>
                        <div class="filter-license">
                            <select name="filter_license" id="filter_license">
                                <option value="all"><?php esc_html_e( 'All', 'solace-extra' ); ?></option>
                                <option value="free"><?php esc_html_e( 'Free', 'solace-extra' ); ?></option>
                            </select>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img class="decor1" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/starter-templates/decor1.png' ); ?>" alt="decor1" />
        </div>
        <div class="content-main">
            <?php 
            $solace_extra_show_posts = 9;
            $solace_extra_get_show_posts = 0;
            $solaceLoadMore = 0;
            ?>
            <aside>
                <div class="mycontainer">
                    <span class="title"><?php esc_html_e('Pick your layout', 'solace-extra'); ?></span>
                    <span class="desc">
                        <?php esc_html_e('Search in over', 'solace-extra'); ?>
                        <?php
                        // Get demo count from API
                        $solace_extra_api_url = SOLACE_EXTRA_DEMO_IMPORT_URL . 'api/wp-json/solace/v1/demo/';
                        $solace_extra_response = wp_remote_get($solace_extra_api_url);
                        
                        $solace_extra_demo_count = 0;
                        if (!is_wp_error($solace_extra_response)) {
                            $solace_extra_body = wp_remote_retrieve_body($solace_extra_response);
                            $solace_extra_data = json_decode($solace_extra_body, true);
                            
                            if (!empty($solace_extra_data) && is_array($solace_extra_data)) {
                                $solace_extra_domain = !empty($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
                                
                                if ('solacewp.com' !== $solace_extra_domain) {
                                    $solace_extra_data = array_filter($solace_extra_data, function($demo) {
                                        return !empty($demo['demo_status']) && 'draft' !== $demo['demo_status'] && 'pending' !== $demo['demo_status'];
                                    });
                                }
                                
                                $solace_extra_demo_count = absint(count($solace_extra_data));
                            }
                        }
                        echo '<span class="count">' . esc_html($solace_extra_demo_count) . '</span>';
                        ?>
                        <?php esc_html_e(' total layouts', 'solace-extra'); ?>
                    </span>
                    <div class="box-search">
                        <input type="text" class="search-input" placeholder="Search" value="" name="s">
                        <div class="box-btn">
                            <button type="submit" class="search-submit" tabindex="2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.0068 10.8948C14.1726 8.09749 13.7934 4.09906 11.1404 1.75874C8.48748 -0.581568 4.47297 -0.45903 1.9677 2.03874C-0.539287 4.54203 -0.66661 8.56354 1.677 11.2204C4.02062 13.8773 8.02658 14.2528 10.8232 12.0778L10.8595 12.1158L14.4154 15.6729C14.7431 16.0005 15.2742 16.0005 15.6019 15.6729C15.9295 15.3453 15.9295 14.8141 15.6019 14.4865L12.0448 10.9305C12.0325 10.9184 12.0198 10.9061 12.0068 10.8948ZM10.2666 3.22461C12.231 5.18853 12.231 8.37311 10.2668 10.3373C8.30263 12.3015 5.11806 12.3015 3.15387 10.3373C1.18956 8.37324 1.18956 5.18866 3.15374 3.22448C5.11793 1.26029 8.30237 1.26042 10.2666 3.22461Z" fill="#2EBBEF" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <span class="cat"><?php esc_html_e('Categories', 'solace-extra'); ?></span>
                    <form action="#">
                        <?php
                        if (!isset($_POST['nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'ajax-nonce')) {
                            // Get categories from API
                            $solace_extra_api_url = SOLACE_EXTRA_DEMO_IMPORT_URL . 'api/wp-json/solace/v1/category/';
                            $solace_extra_response = wp_remote_get($solace_extra_api_url);
                            
                            if (!is_wp_error($solace_extra_response)) {
                                $solace_extra_body = wp_remote_retrieve_body($solace_extra_response);
                                $solace_extra_data_category = json_decode($solace_extra_body, true);
                                
                                if (!empty($solace_extra_data_category) && is_array($solace_extra_data_category)) {
                                    $solace_extra_get_solace_type = !empty($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
                                    $solace_extra_arr_list_type = array('elementor', 'gutenberg');
                                    
                                    if (!empty($solace_extra_get_solace_type) && in_array($solace_extra_get_solace_type, $solace_extra_arr_list_type, true)) {
                                        foreach ($solace_extra_data_category as $solace_extra_value) {
                                            if (empty($solace_extra_value['category']) || $solace_extra_value['type'] !== $solace_extra_get_solace_type) {
                                                continue;
                                            }
                                            
                                            $solace_extra_value_sanitize = str_replace('&', '&amp;', $solace_extra_value['category']);
                                            ?>
                                            <div class="box-checkbox">
                                                <input type="checkbox" id="<?php echo esc_attr($solace_extra_value['category']); ?>" name="<?php echo esc_attr($solace_extra_value['category']); ?>" value="<?php echo esc_attr($solace_extra_value_sanitize); ?>">
                                                <label for="<?php echo esc_attr($solace_extra_value['category']); ?>"><?php echo esc_html($solace_extra_value['category']); ?></label>
                                            </div>
                                            <?php
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </form>
                </div>
                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img class="decor2" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/starter-templates/decor2.png' ); ?>" alt="decor2" />
                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img class="decor3" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/starter-templates/decor3.png' ); ?>" alt="decor3" />
                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img class="decor4" src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '../images/starter-templates/decor4.png' ); ?>" alt="decor4" />
            </aside>
            <main>
                <div class="mycontainer">
                    <?php
                    // Get demo data from API
                    $solace_extra_api_url = SOLACE_EXTRA_DEMO_IMPORT_URL . 'api/wp-json/solace/v1/demo/';
                    $solace_extra_response = wp_remote_get($solace_extra_api_url);
                    
                    $solace_extra_show_posts = 9;
                    $solace_extra_get_show_posts = 0;
                    $solace_extra_total_demo = 0;
                    $solace_extra_total_filtered = 0; // Initialize
                    
                    if (!is_wp_error($solace_extra_response)) {
                        $solace_extra_body = wp_remote_retrieve_body($solace_extra_response);
                        $solace_extra_data_demo = json_decode($solace_extra_body, true);
                        
                        if (!empty($solace_extra_data_demo) && is_array($solace_extra_data_demo)) {
                            $solace_extra_domain = !empty($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
                            
                            if ('solacewp.com' !== $solace_extra_domain) {
                                $solace_extra_data_demo = array_filter($solace_extra_data_demo, function($demo) {
                                    return !empty($demo['demo_status']) && 'draft' !== $demo['demo_status'] && 'pending' !== $demo['demo_status'];
                                });
                            }
                            
                            $solace_extra_total_demo = count($solace_extra_data_demo);
                            $solace_extra_get_solace_type = !empty($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
                            $solace_extra_arr_list_type = array('elementor', 'gutenberg');
                            
                            if (!empty($solace_extra_get_solace_type) && in_array($solace_extra_get_solace_type, $solace_extra_arr_list_type, true)) {
                                // Get filter license from URL or default to 'all'
                                $solace_extra_filter_license = !empty($_GET['filter_license']) ? sanitize_text_field(wp_unslash($_GET['filter_license'])) : 'all';
                                
                                // Get current page from cookie (single cookie for pagination)
                                $solace_extra_cookie_key = 'solaceLoadMore_page';
                                $solace_extra_current_page = !empty($_COOKIE[$solace_extra_cookie_key]) ? absint($_COOKIE[$solace_extra_cookie_key]) : 1;
                                
                                // Calculate limit based on page number
                                // Display all items from page 1 to current page
                                $solace_extra_limit = $solace_extra_current_page * $solace_extra_show_posts;
                                
                                // Filter demos by type and license
                                $solace_extra_filtered_demos = array();
                                foreach ($solace_extra_data_demo as $solace_extra_value) {
                                    if (empty($solace_extra_value['demo_image']) || empty($solace_extra_value['demo_type']) || $solace_extra_value['demo_type'] !== $solace_extra_get_solace_type) {
                                        continue;
                                    }
                                    
                                    // License filter
                                    $solace_extra_is_pro = false;
                                    if (isset($solace_extra_value['is_pro'])) {
                                        $solace_extra_is_pro = (bool)$solace_extra_value['is_pro'];
                                    } elseif (isset($solace_extra_value['isPro'])) {
                                        $solace_extra_is_pro = (bool)$solace_extra_value['isPro'];
                                    } elseif (isset($solace_extra_value['license'])) {
                                        $solace_extra_is_pro = ('pro' === strtolower($solace_extra_value['license']));
                                    }
                                    
                                    if ('free' === $solace_extra_filter_license && $solace_extra_is_pro) {
                                        continue;
                                    }
                                    if ('pro' === $solace_extra_filter_license && !$solace_extra_is_pro) {
                                        continue;
                                    }
                                    
                                    $solace_extra_filtered_demos[] = $solace_extra_value;
                                }
                                
                                $solace_extra_total_filtered = count($solace_extra_filtered_demos);
                                $solace_extra_index = 1;
                                $solace_extra_rendered_count = 0;
                                
                                foreach ($solace_extra_filtered_demos as $solace_extra_value) {
                                    // Use pagination based on page number
                                    // Display all items from page 1 to current page
                                    if ($solace_extra_index <= $solace_extra_limit) {
                                    
                                    $solace_extra_label_new = in_array('New', !empty($solace_extra_value['demo_category']) ? $solace_extra_value['demo_category'] : array(), true);
                                    $solace_extra_label_recommended = in_array('Recommended', !empty($solace_extra_value['demo_category']) ? $solace_extra_value['demo_category'] : array(), true);
                                    ?>
                                    <?php
                                    // Check if demo is pro
                                    $solace_extra_is_pro = false;
                                    if (isset($solace_extra_value['is_pro'])) {
                                        $solace_extra_is_pro = (bool)$solace_extra_value['is_pro'];
                                    } elseif (isset($solace_extra_value['isPro'])) {
                                        $solace_extra_is_pro = (bool)$solace_extra_value['isPro'];
                                    } elseif (isset($solace_extra_value['license'])) {
                                        $solace_extra_is_pro = ('pro' === strtolower($solace_extra_value['license']));
                                    }
                                    
                                    // Build class string
                                    $solace_extra_demo_classes = 'demo demo' . esc_attr($solace_extra_index);
                                    if ($solace_extra_is_pro) {
                                        $solace_extra_demo_classes .= ' is-pro';
                                    }
                                    ?>
                                    <div class='<?php echo esc_attr($solace_extra_demo_classes); ?>' data-url='<?php echo esc_attr($solace_extra_value['demo_link']); ?>' data-name='<?php echo esc_attr($solace_extra_value['title']); ?>'>
                                        <div class="box-image">
                                            <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
                                            <img src="<?php echo esc_url($solace_extra_value['demo_image']); ?>" alt="Demo Image" />
                                        </div>
                                        <div class="box-content">
                                            <div class="top-content">
                                                <?php if (!empty($solace_extra_value['title'])) : ?>
                                                    <span class="title"><?php echo esc_html($solace_extra_value['title']); ?></span>
                                                <?php endif; ?>
                                                <?php if ($solace_extra_label_recommended) : ?>
                                                    <span class="label-recommended"><?php esc_html_e('Recommended', 'solace-extra'); ?></span>
                                                <?php endif; ?>
                                                <?php if ($solace_extra_is_pro) : ?>
                                                    <span class="label pro"><?php esc_html_e('PRO', 'solace-extra'); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="bottom-content">
                                                <p><strong><?php echo esc_html__('Ideal for: ', 'solace-extra'); ?></strong><?php echo esc_html($solace_extra_value['demo_desc']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                        $solace_extra_rendered_count++;
                                    }
                                    $solace_extra_index++;
                                }
                                
                                // Set get_show_posts to rendered count
                                $solace_extra_get_show_posts = $solace_extra_rendered_count;
                                
                                // Output total filtered count for JavaScript
                                echo '<span class="total-filtered-count" style="display:none;">' . absint($solace_extra_total_filtered) . '</span>';
                            }
                        }
                    }
                    ?>
                </div>
                <?php 
                // Check if load more should be shown
                // Only show if remaining items >= posts_per_page (9)
                $solace_extra_should_show_load_more = false;
                if ($solace_extra_total_filtered > 0) {
                    $solace_extra_current_items = $solace_extra_current_page * $solace_extra_show_posts;
                    $solace_extra_remaining = $solace_extra_total_filtered - $solace_extra_current_items;
                    $solace_extra_should_show_load_more = ($solace_extra_remaining >= $solace_extra_show_posts); // Only show if remaining >= posts_per_page
                }
                ?>
                <?php if ($solace_extra_should_show_load_more) : ?>
                    <div class="box-load-more">
                        <button type="button" data-page="<?php echo esc_attr($solace_extra_current_page); ?>">
                            <?php esc_html_e('Load More', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json'); ?>" background="transparent" speed="1" style="width: 250px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </section>
    <footer class="bottom">
        <div class="mycontainer">
            <div class="box left">
                <a href="<?php echo esc_url($solace_extra_myadmin . '/wp-admin/admin.php?page=dashboard-video'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                        <path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l160 160c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L109.2 288 416 288c17.7 0 32-14.3 32-32s-14.3-32-32-32l-306.7 0L214.6 118.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-160 160z" />
                    </svg>
                    <span><?php esc_html_e('Back', 'solace-extra'); ?></span>
                </a>
            </div>

            <div class="box center">
                <a href="<?php echo esc_url($solace_extra_myadmin . '/wp-admin'); ?>">
                    <span><?php esc_html_e('Back to WordPress Dashboard', 'solace-extra'); ?></span>
                </a>
            </div>              

            <div class="box right" style="visibility: hidden;">
                <a href="#">
                    <span><?php esc_html_e('Next', 'solace-extra'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><path d="M438.6 278.6c12.5-12.5 12.5-32.8 0-45.3l-160-160c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L338.8 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l306.7 0L233.4 393.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l160-160z"/></svg>
                </a>
            </div>
        </div>
    </footer>    
</div>
<style>
.box-checkbox {
    justify-content: left;
    align-items: center;
    display: flex;
}
.mycontainer form {
    display: flex;
    flex-direction: column;
}
body.solace_page_dashboard-starter-templates .wrap section.start-templates .content-main aside .mycontainer form .box-checkbox label {
    line-height: 1.8;
    }
.mycontainer .box-checkbox input {
    margin: 0;
}
</style>