<?php defined('ABSPATH') || exit; ?>
<?php $customizer_link = admin_url('customize.php'); 
$comingsoon = FALSE;?>
<div class="wrap">
<?php require_once plugin_dir_path(dirname(__FILE__)) . 'partials/header.php'; ?>

<?php
if (! class_exists('\Elementor\Plugin')) {?>
<div class="container">
        <div class="coming-soon-container">
            <h1>Elementor Plugin is Not Active</h1>
            <p>Please Activate Elementor Plugin First to Start Solace SiteBuilder.</p>
        </div>
    </div>
<?php
}
if ($comingsoon) {?>
    <div class="container">
        <div class="coming-soon-container">
            <h1>Coming Soon</h1>
            <p>We're working on something amazing. Stay tuned!</p>
        </div>
    </div>
<?php
} else {
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended --- We need this to get all the terms and taxonomy. Traditional WP_Query would have been expensive here. 
    $part = isset($_GET['part']) ? sanitize_text_field(wp_unslash($_GET['part'])) : '';

    $args = array(
        'post_type' => 'solace-sitebuilder',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    if ($part) {
        if ($part == '404') {
            if (function_exists('solace_pro_parts')) {
            }else {
                $part = "header";
            }
        }
        $key = '_solace_' . $part . '_status';
        // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- We need this
        $args['meta_query'] = array(
            array(
                'key'     => $key,
                'compare' => 'EXISTS',
            ),
        );
    }

    $query = new WP_Query($args);

    // Debug hasil query
    if ($query->have_posts()) {
        // error_log('Post ditemukan: ' . $query->found_posts);
    } else {
        // error_log('Tidak ada post ditemukan untuk part: ' . $part);
    }
    ?>

    <div class='solace-sitebuilder'>
        <div class='parts'>
            <a href="<?php echo esc_url(admin_url('admin.php?page=dashboard-sitebuilder')); ?>" class="button all <?php echo (empty($part) ? 'active' : ''); ?>">
                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?>
                <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/all-layouts.png'); ?>" />
                <span>All Layout</span>
            </a>
            <span class='label'>Website Parts</span>
            <a href="<?php echo esc_url(add_query_arg('part', 'header', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>" class="button <?php echo (isset($part) && $part === 'header' ? 'active' : ''); ?>">
            <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?>
                <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/header.png'); ?>" />
                <span>Header</span>
            </a>
            <a href="<?php echo esc_url(add_query_arg('part', 'footer', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>" class="button <?php echo (isset($part) && $part === 'footer' ? 'active' : ''); ?>">
            <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?>
                <img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/footer.png'); ?>" />
                <span>Footer</span>
            </a>

            <?php if (function_exists('solace_pro_parts')) {
                echo wp_kses_post( solace_pro_parts($part) );
            } else { ?>
                <a href="#" class="button nf">
                    <div class='desc'><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/singleproduct.png'); ?>" />
                        <span>Shop Single Product</span></div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span> <!-- Icon Lock -->
                </a>
                <a href="#" class="button nf">
                    <div class='desc'><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/shopproductcategories.png'); ?>" />
                        <span>Shop Product Categories</span></div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span> <!-- Icon Lock -->
                </a>
                <a href="#" class="button nf">
                    <div class='desc'><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/blogsinglepost.png'); ?>" />
                        <span>Blog Single Post</span></div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span> <!-- Icon Lock -->
                </a>
                <a href="#" class="button nf">
                    <div class='desc'><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/blogarchive.png'); ?>" />
                        <span>Blog Archive</span></div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span> <!-- Icon Lock -->
                </a>
                <a href="#" class="button nf">
                    <div class='desc'><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/404.png'); ?>" />
                        <span>404</span></div>
                    <span class="dashicons dashicons-lock" style="margin-left: 5px;"></span> <!-- Icon Lock -->
                </a>
            <?php }?>

        </div>
        <div class='list'>
            <div class="headlist">
                <span class='label'>Start customizing every part of your website</span>
                <?php if ($part) : ?>
                    <?php if ($part == 404) : ?>
                        <button id="save-404" class="button"> <?php echo esc_html__('Add New', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    <?php else : ?>
                        <a href="#" class="button addnew">
                            <?php echo esc_html__('Add New', 'solace-extra'); ?>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if ($part) : ?>
            <?php //if ($query->have_posts()) : 
                // error_log('ada post');?>
                <?php if ($part == 404) : ?>
                    <div class="solace-list-view <?php echo esc_attr($part); ?>">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <div class="solace-template-item">
                                <div class="solace-list-item">
                                    <div class="list-column title-column">
                                        <?php the_title(); ?>
                                    </div>
                                    <div class="group action">
                                        <div class="list-column delete-column">
                                            <a href="<?php echo get_delete_post_link(); ?>" class="button delete">
                                                <?php echo esc_html__('Delete', 'solace-extra'); ?>
                                            </a>
                                        </div>
                                        <div class="list-column rename-column">
                                            <a href="#" data-post-id="<?php echo esc_attr($post_id); ?>" class="solace-rename-button button rename">
                                                <?php echo esc_html__('Rename', 'solace-extra'); ?>
                                            </a>
                                        </div>
                                        <div class="list-column edit-column">
                                            <a href="<?php echo esc_url(admin_url('post.php?post=' . get_the_ID() . '&action=elementor')); ?>" class="button edit-page">
                                                <?php echo esc_html__('Edit Page', 'solace-extra'); ?>
                                            </a>
                                        </div>
                                        <div class="list-column status-column">
                                            <label class="switch">
                                                <?php
                                                $show_status = get_post_meta(get_the_ID(), '_solace_'.$part.'_status', true);
                                                echo '<!-- Show Status: ' . esc_html($show_status) . ' -->'; // Debugging output
                                                ?>
                                                
                                                <label class="switch">
                                                <input type="checkbox" class="status-switch" data-part="<?php echo esc_html($part);?>" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>" <?php checked($show_status, '1'); ?> />
                                                <span class="slider round"></span>
                                                </label>

                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else : ?>
                    <div class="solace-list-view <?php echo esc_attr($part); ?>">
                        <?php while ($query->have_posts()) : $query->the_post(); 
                        $post_id = get_the_ID(); 
                        $meta_key = '_solace_' . $part . '_conditions';
                        $conditions = get_post_meta($post_id, $meta_key, true);

                        // error_log('Post ID: ' . $post_id); 
                        ?>
                        <div class="solace-template-item">
                            <div class="solace-list-item">
                                <div class="conditions-data" data-conditions='<?php echo wp_json_encode($conditions); ?>'></div>

                                <div class="list-column title-column">
                                    <?php the_title(); ?>
                                </div>
                                <?php
                                if ($part == 'header' || $part == 'footer') {?>
                                <div class="group instances">
                                    <span class="label">Instances:&nbsp;</span>
                                    <span class="label listinstances">
                                        <?php
                                        $label = "";
                                        if (is_array($conditions)) {
                                            $is_first = true;
                                    
                                            foreach ($conditions as $condition) {
                                                $value = $condition['value'];
                                                $type = $condition['type'];
                                    
                                                $class = ($type === 'include') ? 'text-include' : 'text-exclude';

                                                if (!$is_first) {
                                                    echo ', ';
                                                }
                                                $is_first = false;
                                                ?>
                                                <span class="<?php echo esc_attr($class); ?>">
                                                    <?php echo esc_html(solace_get_condition_label($value)); ?>
                                                </span>
                                                <?php
                                            }
                                        } else {
                                            echo 'Conditions data is not a valid array.';
                                        }
                                        ?>
                                    </span>
                                    <div class="list-column conditions-column">
                                    <a href="#" class="button edit-conditions-button" data-post-id="<?php echo esc_attr($post_id); ?> " data-conditions='<?php echo wp_json_encode($conditions); ?>'>
                                        <?php echo esc_html__('Edit Conditions', 'solace-extra'); ?>
                                    </a>
                                    </div>
                                </div>
                                <?php }?>
                                <div class="group action">
                                    <div class="list-column delete-column">
                                        <a href="<?php 
                                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, WordPress.Security.ValidatedSanitizedInput.MissingUnslash,WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.NonceVerification.Recommended --- We need this.
echo wp_nonce_url(admin_url('admin-post.php?action=delete_post&id=' . get_the_ID() . '&part=' . urlencode($_GET['part'])), 'delete_post_' . get_the_ID()); ?>" class="button delete">
                                            <?php echo esc_html__('Delete', 'solace-extra'); ?>
                                        </a>
                                    </div>
                                    <div class="list-column rename-column">
                                        <a href="#" data-post-id="<?php echo esc_attr($post_id); ?>" class="solace-rename-button button rename">
                                            <?php echo esc_html__('Rename', 'solace-extra'); ?>
                                        </a>
                                    </div>
                                    <div class="list-column edit-column">
                                        <a href="<?php echo esc_url(admin_url('post.php?post=' . get_the_ID() . '&action=elementor')); ?>" class="button edit-page">
                                            <?php echo esc_html__('Edit Part', 'solace-extra'); ?>
                                        </a>
                                    </div>
                                    <div class="list-column status-column">
                                        <label class="switch">
                                            <?php
                                            $show_status = get_post_meta(get_the_ID(), '_solace_'.$part.'_status', true);
                                            echo '<!-- Show Status: ' . esc_html($show_status) . ' -->'; // Debugging output
                                            ?>
                                            
                                            <label class="switch">
                                            <input type="checkbox" class="status-switch" data-part="<?php echo esc_html($part);?>" data-post-id="<?php echo esc_attr( get_the_ID() ); ?>" <?php checked($show_status, '1'); ?> />
                                            <span class="slider round"></span>
                                            </label>

                                        </label>
                                    </div>
                                </div>

                            </div>
                            <?php
                            if ($part == 'header' || $part == 'footer') {?>
                            <div class="preview-container" style="position: relative; overflow: hidden;">
                            <div class="iframe-overlay"></div> 

                            <div class="custom-header-content" 
                                    id="custom-header-content" 
                                    style="position: relative; width: 100%;height: 100%; overflow: hidden;">
                                    <iframe 
                                        id="responsive-iframe"
                                        src="<?php echo esc_url(admin_url('admin-ajax.php?action=get_elementor_content&post_id=' . $post_id)); ?>" 
                                        style="
                                            width: 1400px; /* Fixed iframe width */
                                            height: 254.7125px; /* Fixed iframe height for 1400px width */
                                            border: none;
                                            transform-origin: top left; /* Maintain scaling origin */
                                        ">
                                    </iframe>
                                </div>
                            </div>
                            <?php } ?>

                        </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>

                <!-- Overlay -->
                <div id="edit-conditions-overlay" class="solace-overlay" style="display:none;"></div>
                <div id="add-new-overlay" class="solace-overlay" style="display:none;"></div>


                <!-- Popup "Add New" -->
                <div id="add-new-popup" class="solace-popup" data-popup-type="add" style="display:none;">
                    <?php wp_nonce_field('solace_conditions_nonce_action', 'solace_conditions_nonce_action'); ?>
                    <div class="solace-popup-content">
                        <span class="close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Where do you want to display your template?', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('Set the conditions that determine where your template is used throughout your website.', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('For example, choose "Entire Site" to display the template accross your site.', 'solace-extra'); ?></span>
                        <div id="new-conditions-container">
                            <div class="condition-item">
                                <span class="icon-container">
                                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/plus-include.svg' ); ?>" />
                                </span>
                                <select name="condition_1_exclude_include" class="condition-exclude-include-select">
                                    <option value="include"><?php echo esc_html__('Include', 'solace-extra'); ?></option>
                                    <option value="exclude"><?php echo esc_html__('Exclude', 'solace-extra'); ?></option>
                                </select>
                                <select name="condition_1_include[]" class="condition-select rico1">
                                    <option value=""> — Select —</option>
                                    <optgroup label="Basic" class="counts-undefined">
                                        <option value="basic-global"> Entire Website</option>
                                        <option value="basic-singulars"> All Singulars </option>
                                        <option value="basic-archives"> All Archives </option>
                                    </optgroup>
                                    <optgroup label="Special Pages" class="counts-undefined">
                                        <option value="special-404"> 404 Page </option>
                                        <option value="special-search"> Search Page </option>
                                        <option value="special-blog"> Blog / Posts Page </option>
                                        <option value="special-front"> Front Page </option>
                                        <option value="special-date"> Date Archive </option>
                                        <option value="special-author"> Author Archive </option>
                                    </optgroup>
                                    <optgroup label="Posts" class="counts-undefined">
                                        <option value="post|all"> All Posts </option>
                                        <option value="post|all|taxarchive|category"> All Categories Archive </option>
                                        <option value="post|all|taxarchive|post_tag"> All Tags Archive </option>
                                    </optgroup>
                                    <optgroup label="Pages" class="counts-undefined">
                                        <option value="page|all"> All Pages </option>
                                    </optgroup>
                                    <?php if (class_exists('WooCommerce')) : ?>
                                        <optgroup label="Products" class="counts-undefined">
                                            <option value="product|all">All Products</option>
                                            <!-- <option value="product|all|archive">All Products Archive</option> -->
                                            <option value="product|all|taxarchive|product_cat">All Product Categories Archive</option>
                                            <option value="product|all|taxarchive|product_tag">All Product Tags Archive</option>
                                        </optgroup>
                                    <?php endif; ?>
                                </select>

                                <a href="#" class="delete-condition dashicons dashicons-trash hide" title="Delete Condition"></a>
                            </div>
                            
                        </div>
                        <div class="solace-popup-footer">
                            <button id="addnew-add-condition" class="button newaddcondition"><?php echo esc_html__('Add Condition', 'solace-extra'); ?></button>
                        </div>
                        <button id="save-new-conditions" class="button"><?php echo esc_html__('Save and Add Template', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Popup "404" -->
                <div id="add-404-popup" class="solace-404-popup-overlay" style="display:none;">
                    <div class="solace-404-popup-content">
                        <span class="solace-close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('404', 'solace-extra'); ?></span>
                        <input type="text" id="solace-404-field" placeholder="Enter 404 title" />
                        <button id="solace-save-404" class="solace-404-button solace-404-button-primary"><?php echo esc_html__('Save and Add Template', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Popup "Edit Conditions" -->
                <div id="edit-conditions-popup" class="solace-popup" data-popup-type="" style="display:none;">
                    <div class="solace-popup-content">
                        <span class="close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Where do you want to display your template?', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('Set the conditions that determine where your template is used throughout your website.', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('For example, choose "Entire Site" to display the template across your site.', 'solace-extra'); ?></span>
                        <div id="edit-conditions-container">
                            
                        </div>
                        <div class="solace-popup-footer">
                            <button id="add-condition" class="button addcondition"><?php echo esc_html__('Add Condition', 'solace-extra'); ?></button>
                        </div>
                        <button id="save-conditions" class="button savenclose"><?php echo esc_html__('Save Conditions', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>
                <div id="solace-rename-popup" class="solace-rename-popup-overlay" style="display:none;">
                    <div class="solace-rename-popup-content">
                        <span class="solace-close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Rename', 'solace-extra'); ?></span>
                        <label><?php echo esc_html__('New Title', 'solace-extra'); ?></label>
                        <input type="text" id="solace-rename-field" placeholder="Enter new title" />
                        <button id="solace-save-rename" class="solace-rename-button solace-rename-button-primary"><?php echo esc_html__('Save', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <?php else : ?>
                <div class="card-grid">
                    <?php /*if ($query->have_posts()) : */
                        $status_header = solace_get_part_status('header');
                        $status_footer = solace_get_part_status('footer');
                        $status_singleproduct = solace_get_part_status('singleproduct');
                        $status_shopproduct = solace_get_part_status('shopproduct');
                        $status_blogsinglepost = solace_get_part_status('blogsinglepost');
                        $status_blogarchive = solace_get_part_status('blogarchive');
                        ?>
                        <a class="part-header <?php echo esc_attr($status_header['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'header', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                            <div class="card" style="background-image: url('<?php 
                            echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $status_header['image'] ); 
                            ?>');">
                                <div class="card-body"></div>
                                <div class="card-footer <?php echo esc_attr($status_header['active_blue']);?>">
                                    <span class='title'>Header</span>
                                    <label class="switch layout">
                                        <input type="checkbox" class="all-status-switch <?php echo $status_header['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="header" <?php echo $status_header['is_checked'] ? 'checked' : ''; ?> />
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </a>
                        <a class="part-footer <?php echo esc_attr($status_footer['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'footer', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                            <div class="card" style="background-image: url('<?php 
                            echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $status_footer['image']); 
                            ?>');">
                                <div class="card-body"></div>
                                <div class="card-footer <?php echo esc_attr($status_footer['active_blue']);?>">
                                    <span class='title'>Footer</span>
                                    <label class="switch layout">
                                        <input type="checkbox" class="all-status-switch <?php echo $status_footer['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="footer" <?php echo $status_footer['is_checked'] ? 'checked' : ''; ?> />
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </div>
                        </a>
                        
                        <?php if (function_exists('solace_pro_card')) {
                                echo wp_kses_post( solace_pro_card($part) );?>
                                <a class="part-singleproduct <?php echo esc_attr($status_singleproduct['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'singleproduct', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                                    <div class="card" style="background-image: url('<?php 
                                    echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $status_singleproduct['image']); 
                                    ?>');">
                                        <div class="card-body"></div>
                                        <div class="card-footer <?php echo esc_attr($status_singleproduct['active_blue']);?>">
                                            <span class='title'>Shop Single Product</span>
                                            <label class="switch layout">
                                                <input type="checkbox" class="all-status-switch <?php echo $status_singleproduct['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="singleproduct" <?php echo $status_singleproduct['is_checked'] ? 'checked' : ''; ?> />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </a>
                                <a class="part-shopproduct <?php echo esc_attr($status_shopproduct['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'shopproduct', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                                    <div class="card" style="background-image: url('<?php 
                                    echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $status_shopproduct['image']); 
                                    ?>');">
                                        <div class="card-body"></div>
                                        <div class="card-footer <?php echo esc_attr($status_shopproduct['active_blue']);?>">
                                            <span class='title'>Shop Product Categories</span>
                                            <label class="switch layout">
                                                <input type="checkbox" class="all-status-switch <?php echo $status_shopproduct['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="shopproduct" <?php echo $status_shopproduct['is_checked'] ? 'checked' : ''; ?> />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </a>
                                <a class="part-blogsinglepost <?php echo esc_attr($status_blogsinglepost['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'blogsinglepost', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                                    <div class="card" style="background-image: url('<?php 
                                    echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $status_blogsinglepost['image']); 
                                    ?>');">
                                        <div class="card-body"></div>
                                        <div class="card-footer <?php echo esc_attr($status_blogsinglepost['active_blue']);?>">
                                            <span class='title'>Blog Single Post</span>
                                            <label class="switch layout">
                                                <input type="checkbox" class="all-status-switch <?php echo $status_blogsinglepost['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="blogsinglepost" <?php echo $status_blogsinglepost['is_checked'] ? 'checked' : ''; ?> />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </a>
                                <a class="part-blogarchive <?php echo esc_attr($status_blogarchive['lock_class']); ?>" href="<?php echo esc_url(add_query_arg('part', 'blogarchive', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                                    <div class="card" style="background-image: url('<?php 
                                    echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $status_blogarchive['image']); 
                                    ?>');">
                                        <div class="card-body"></div>
                                        <div class="card-footer <?php echo esc_attr($status_blogarchive['active_blue']);?>">
                                            <span class='title'>Blog Archive</span>
                                            <label class="switch layout">
                                                <input type="checkbox" class="all-status-switch <?php echo $status_blogarchive['is_disabled'] ? 'disabled-checkbox' : ''; ?>" data-part-global="blogarchive" <?php echo $status_blogsinglepost['is_checked'] ? 'checked' : ''; ?> />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                </a>
                               
                                <a href="<?php echo esc_url(add_query_arg('part', '404', admin_url('admin.php?page=dashboard-sitebuilder'))); ?>">
                                    <div class="card" style="background-image: url('<?php 
                                        $image = 'footer.svg';
                                        echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $image); 
                                    ?>');">
                                        <div class="card-body"></div>
                                        <div class="card-footer">
                                            <span class='title'>404</span>
                                        </div>
                                    </div>
                                </a>
                            <?php 
                            } else { ?>
                            <a class="part-singleproduct lock" >
                            <div class="card" style="background-image: url('<?php 
                                    $image = 'singleproduct.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $image ); 
                                ?>');">
                                    <div class="card-body">
                                        <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Coming Soon', 'solace-extra' ); ?>
                                        </button>
                                    </div>
                                    <div class="card-footer">
                                        <span class='title'>Shop Single Product</span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-shopproductcategories lock">
                            <div class="card" style="background-image: url('<?php 
                                    $image = 'shopproductcategories.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Coming Soon', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'>Shop Product Categories</span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-blogsinglepost lock" >
                            <div class="card" style="background-image: url('<?php 
                                    $image = 'blogsinglepost.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $image ); 
                                ?>');">
                                    <div class="card-body">
                                        <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Coming Soon', 'solace-extra' ); ?>
                                        </button>
                                    </div>
                                    <div class="card-footer">
                                        <span class='title'>Blog Single Post</span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-blogarchive lock" >
                                <div class="card" style="background-image: url('<?php 
                                    $image = 'blogarchive.svg';
                                    echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $image ); 
                                ?>');">
                                    <div class="card-body"> <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Coming Soon', 'solace-extra' ); ?>
                                        </button></div>
                                    <div class="card-footer">
                                        <span class='title'>Blog Archive</span>
                                    </div>
                                </div>
                            </a>
                            <a class="part-404 lock">
                                <div class="card" style="background-image: url('<?php 
                                    $image = '404.svg';
                                    echo esc_url(SOLACE_EXTRA_ASSETS_URL . 'images/' . $image); 
                                ?>');">
                                    <div class="card-body">
                                        <span class="dashicons dashicons-lock"></span> 
                                        <button type="button">
                                            <?php esc_html_e( 'Coming Soon', 'solace-extra' ); ?>
                                        </button>
                                    </div>
                                    <div class="card-footer ">
                                        <span class='title'>404</span>
                                    </div>
                                </div>
                            </a>
                        <?php }?>
                        
                    <?php //endif; ?>
                    

                    <a href="#">
                        <div class="card newpart" style="background-image: url('<?php 
                            $image = 'sitebuilder-addnew.svg';
                            echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/' . $image );?>'); 
                            background-position: center; 
                            background-repeat: no-repeat; 
                            background-size: auto;
                            position: relative;">
                            <span class='label'>Add New</label>
                        </div>
                    </a>
                </div>
                <!-- Popup "Add New" -->
                <div id="all-add-new-overlay" class="solace-overlay" style="display:none;"></div>
                <div id="all-add-new-popup" class="solace-popup" data-popup-type="add" style="display:none;">
                    <?php wp_nonce_field('solace_conditions_nonce_action', 'solace_conditions_nonce_action'); ?>
                    <div class="solace-popup-content">
                        <span class="close-popup">&times;</span>
                        <span class="addnew-title"><?php echo esc_html__('Where do you want to display your template?', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('Set the conditions that determine where your template is used throughout your website.', 'solace-extra'); ?></span>
                        <span class='desc'><?php echo esc_html__('For example, choose "Entire Site" to display the template accross your site.', 'solace-extra'); ?></span>
                        <span class='chooseparts'>Choose Parts<select name="part" class="condition-part-select">
                            <option value="header"><?php echo esc_html__('Header', 'solace-extra'); ?></option>
                            <option value="footer"><?php echo esc_html__('Footer', 'solace-extra'); ?></option>
                            <?php 
                            if (function_exists('solace_pro_option_404')) {
                                echo wp_kses_post( solace_pro_option_404() );
                            } 
                            ?>
                        </select></span>
                        <div id="all-new-conditions-container">
                            <div class="condition-item">
                                <span class="icon-container">
                                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/plus-include.svg' ); ?>" />
                                </span>
                                <select name="condition_1_exclude_include" class="condition-exclude-include-select">
                                    <option value="include"><?php echo esc_html__('Include', 'solace-extra'); ?></option>
                                    <option value="exclude"><?php echo esc_html__('Exclude', 'solace-extra'); ?></option>
                                </select>
                                <select name="condition_1_include[]" class="condition-select rico1">
                                    <option value=""> — Select —</option>
                                    <optgroup label="Basic" class="counts-undefined">
                                        <option value="basic-global"> Entire Website</option>
                                        <option value="basic-singulars"> All Singulars </option>
                                        <option value="basic-archives"> All Archives </option>
                                    </optgroup>
                                    <optgroup label="Special Pages" class="counts-undefined">
                                        <option value="special-404"> 404 Page </option>
                                        <option value="special-search"> Search Page </option>
                                        <option value="special-blog"> Blog / Posts Page </option>
                                        <option value="special-front"> Front Page </option>
                                        <option value="special-date"> Date Archive </option>
                                        <option value="special-author"> Author Archive </option>
                                    </optgroup>
                                    <optgroup label="Posts" class="counts-undefined">
                                        <option value="post|all"> All Posts </option>
                                        <option value="post|all|taxarchive|category"> All Categories Archive </option>
                                        <option value="post|all|taxarchive|post_tag"> All Tags Archive </option>
                                    </optgroup>
                                    <optgroup label="Pages" class="counts-undefined">
                                        <option value="page|all"> All Pages </option>
                                    </optgroup>
                                    <?php if (class_exists('WooCommerce')) : ?>
                                        <optgroup label="Products" class="counts-undefined">
                                            <option value="product|all">All Products</option>
                                            <!-- <option value="product|all|archive">All Products Archive</option> -->
                                            <option value="product|all|taxarchive|product_cat">All Product Categories Archive</option>
                                            <option value="product|all|taxarchive|product_tag">All Product Tags Archive</option>
                                        </optgroup>
                                    <?php endif; ?>
                                </select>

                                <a href="#" class="delete-condition dashicons dashicons-trash hide" title="Delete Condition"></a>
                            </div>
                            
                        </div>
                        <div class="solace-popup-footer">
                            <button id="partnew-add-condition" class="button partnewaddcondition"><?php echo esc_html__('Add Condition', 'solace-extra'); ?></button>
                        </div>
                        <button id="partnew-save-new-conditions" class="button"><?php echo esc_html__('Save and Add Template', 'solace-extra'); ?>
                            <div class="box-bubble">
                                <dotlottie-player src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/starter/loadmore.json' ); ?>" background="transparent" speed="1" style="width: 350px; height: 130px;" loop autoplay></dotlottie-player>
                            </div>
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php
    }?>
</div>

<?php wp_reset_postdata(); ?>
