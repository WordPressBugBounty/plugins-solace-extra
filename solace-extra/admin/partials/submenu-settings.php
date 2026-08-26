<?php
/**
 * Solace Extra settings screen.
 *
 * @package Solace_Extra
 */

defined( 'ABSPATH' ) || exit;

if ( ! current_user_can( 'manage_options' ) ) {
	wp_die( esc_html__( 'You do not have permission to access this page.', 'solace-extra' ) );
}
?>

<div class="wrap solace-extra-settings">
	<h1><?php esc_html_e( 'Solace Extra Settings', 'solace-extra' ); ?></h1>

	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
		settings_fields( 'solace_extra_settings' );
		do_settings_sections( 'solace-extra-settings' );
		submit_button( __( 'Save Settings', 'solace-extra' ) );
		?>
	</form>
</div>
