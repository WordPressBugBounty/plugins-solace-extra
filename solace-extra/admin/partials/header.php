<?php defined( 'ABSPATH' ) || exit; ?>
<?php $myadmin = site_url(); ?>
<nav>
    <div class="mycontainer">
        <div class="box-logo">
            <a href="<?php echo esc_url( $myadmin . '/wp-admin/admin.php?page=dashboard'); ?>">
                <div class="logo2">
                <?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/solace-logo-light.png' ); ?>" />
                </div>
                <div class="version">
                    <span>0.0.1</span>
                </div>
            </a>
        </div>
        <div class="box-menu">
            <ul>
                <li class="dashboard">
                    <a href="<?php echo esc_url( $myadmin . '/wp-admin/admin.php?page=solace'); ?>">
                        <?php esc_html_e( 'Dashboard', 'solace-extra' ); ?>
                    </a>
                </li>
                <li class="sitebuilder">
                    <a href="<?php echo esc_url( $myadmin . '/wp-admin/admin.php?page=dashboard-sitebuilder'); ?>">
                        <?php esc_html_e( 'Site Builder', 'solace-extra' ); ?>
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url( $myadmin . '/wp-admin/customize.php'); ?>">
                        <?php esc_html_e( 'Customization', 'solace-extra' ); ?>
                    </a>
                </li>
                <li class="starter">
                <a href="<?php echo esc_url( $myadmin . '/wp-admin/admin.php?page=dashboard-starter-templates&type=elementor'); ?>">
                        <?php esc_html_e( 'Starter Templates', 'solace-extra' ); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>