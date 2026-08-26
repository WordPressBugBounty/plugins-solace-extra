<?php
/**
 * Solace dashboard screen.
 *
 * @package Solace_Extra
 */

defined( 'ABSPATH' ) || exit;

$solace_extra_theme          = wp_get_theme();
$solace_extra_theme_name     = $solace_extra_theme->get( 'Name' );
$solace_extra_is_theme       = 'solace' === get_template() || 'solace' === get_stylesheet();
$solace_extra_customizer_url = admin_url( 'customize.php' );
$solace_extra_templates_url  = admin_url( 'admin.php?page=dashboard-starter-templates&type=elementor' );
$solace_extra_support_url    = 'https://wordpress.org/support/plugin/solace-extra/';
$solace_extra_is_pro         = function_exists( 'solace_pro_card' ) && solace_pro_card();
$solace_extra_theme_update   = false;
$solace_extra_theme_updates  = get_site_transient( 'update_themes' );

if ( is_object( $solace_extra_theme_updates ) && ! empty( $solace_extra_theme_updates->response ) ) {
	$solace_extra_theme_update = isset( $solace_extra_theme_updates->response['solace'] );
}

if ( $solace_extra_is_pro ) {
	$solace_extra_support_url = SOLACE_UPGRADE_BASE_URL . 'submit-ticket/';
}

$solace_extra_theme_status_title = $solace_extra_is_theme ? __( 'Theme Active', 'solace-extra' ) : __( 'Theme Inactive', 'solace-extra' );
$solace_extra_theme_status_text  = $solace_extra_is_theme
	? __( 'Solace Theme is active and ready to use.', 'solace-extra' )
	: sprintf(
		/* translators: %s: current theme name. */
		__( '%s is currently active.', 'solace-extra' ),
		$solace_extra_theme_name
	);
if ( ! $solace_extra_is_theme ) {
	$solace_extra_update_status_title = __( 'Updates Unavailable', 'solace-extra' );
	$solace_extra_update_status_text  = __( 'Activate Solace to manage theme updates.', 'solace-extra' );
} elseif ( $solace_extra_theme_update ) {
	$solace_extra_update_status_title = __( 'Update Available', 'solace-extra' );
	$solace_extra_update_status_text  = __( 'A newer version of Solace is available.', 'solace-extra' );
} else {
	$solace_extra_update_status_title = __( 'Updates Current', 'solace-extra' );
	$solace_extra_update_status_text  = __( 'You are using the latest version of Solace.', 'solace-extra' );
}

$solace_extra_site_check = get_transient( 'solace_extra_dashboard_site_check' );

if ( ! is_array( $solace_extra_site_check ) || ! isset( $solace_extra_site_check['status_code'] ) ) {
	$solace_extra_site_response = wp_remote_get(
		home_url( '/' ),
		array(
			'timeout'             => 5,
			'redirection'         => 3,
			'limit_response_size' => 1024,
		)
	);

	$solace_extra_site_check = array(
		'status_code' => is_wp_error( $solace_extra_site_response ) ? 0 : (int) wp_remote_retrieve_response_code( $solace_extra_site_response ),
	);

	set_transient( 'solace_extra_dashboard_site_check', $solace_extra_site_check, 5 * MINUTE_IN_SECONDS );
}

$solace_extra_site_status_code = (int) $solace_extra_site_check['status_code'];
$solace_extra_site_is_online   = $solace_extra_site_status_code >= 200 && $solace_extra_site_status_code < 400;

if ( $solace_extra_site_is_online ) {
	$solace_extra_site_status_title = __( 'Site Online', 'solace-extra' );
	$solace_extra_site_status_text  = __( 'Your site is live and accessible to visitors.', 'solace-extra' );
} elseif ( $solace_extra_site_status_code > 0 ) {
	$solace_extra_site_status_title = __( 'Site Unavailable', 'solace-extra' );
	$solace_extra_site_status_text  = sprintf(
		/* translators: %d: homepage HTTP response status code. */
		__( 'Your homepage returned HTTP status %d.', 'solace-extra' ),
		$solace_extra_site_status_code
	);
} else {
	$solace_extra_site_status_title = __( 'Site Unavailable', 'solace-extra' );
	$solace_extra_site_status_text  = __( 'Your homepage could not be reached.', 'solace-extra' );
}

$solace_extra_php_version        = phpversion();
$solace_extra_theme_version      = $solace_extra_theme->get( 'Version' );
$solace_extra_updates_url        = admin_url( 'update-core.php' );

if ( ! function_exists( 'wp_get_update_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/update.php';
}

$solace_extra_update_data   = wp_get_update_data();
$solace_extra_updates_count      = isset( $solace_extra_update_data['counts']['total'] ) ? (int) $solace_extra_update_data['counts']['total'] : 0;
$solace_extra_health_label       = __( 'Not Tested', 'solace-extra' );
$solace_extra_health_class       = '';
$solace_extra_health_transient   = get_transient( 'health-check-site-status-result' );

if ( is_string( $solace_extra_health_transient ) && '' !== $solace_extra_health_transient ) {
	$solace_extra_health_data = json_decode( $solace_extra_health_transient, true );

	if ( is_array( $solace_extra_health_data ) ) {
		$solace_extra_health_label       = __( 'Good', 'solace-extra' );
		$solace_extra_health_class       = 'is-good';
		$solace_extra_health_critical    = isset( $solace_extra_health_data['critical'] ) ? (int) $solace_extra_health_data['critical'] : 0;
		$solace_extra_health_recommended = isset( $solace_extra_health_data['recommended'] ) ? (int) $solace_extra_health_data['recommended'] : 0;

		if ( $solace_extra_health_critical > 0 ) {
			$solace_extra_health_label = __( 'Critical', 'solace-extra' );
			$solace_extra_health_class = 'is-critical';
		} elseif ( $solace_extra_health_recommended > 0 ) {
			$solace_extra_health_label = __( 'Recommended', 'solace-extra' );
			$solace_extra_health_class = 'is-recommended';
		}
	}
}

$solace_extra_pagespeed_api_key    = trim( (string) get_option( 'solace_extra_pagespeed_api_key', '' ) );
$solace_extra_pagespeed_has_key    = '' !== $solace_extra_pagespeed_api_key;
$solace_extra_pagespeed_result     = get_option( 'solace_extra_pagespeed_result', array() );
$solace_extra_pagespeed_score      = isset( $solace_extra_pagespeed_result['score'] ) && is_numeric( $solace_extra_pagespeed_result['score'] )
	? max( 0, min( 100, (int) $solace_extra_pagespeed_result['score'] ) )
	: null;
$solace_extra_pagespeed_tested_at  = isset( $solace_extra_pagespeed_result['tested_at'] ) ? absint( $solace_extra_pagespeed_result['tested_at'] ) : 0;
$solace_extra_pagespeed_class      = 'is-good';
$solace_extra_pagespeed_settings_url = admin_url( 'admin.php?page=dashboard-settings' );

if ( null !== $solace_extra_pagespeed_score ) {
	if ( $solace_extra_pagespeed_score < 50 ) {
		$solace_extra_pagespeed_class = 'is-critical';
	} elseif ( $solace_extra_pagespeed_score < 90 ) {
		$solace_extra_pagespeed_class = 'is-warning';
	}
}
?>

<div class="wrap solace-home">
	<?php require plugin_dir_path( dirname( __FILE__ ) ) . 'partials/header.php'; ?>

	<main class="solace-home__container">
		<section class="solace-home__hero" aria-labelledby="solace-home-title">
			<div class="solace-home__hero-slider" data-autoplay="6000" tabindex="0">
				<div class="solace-home__hero-track">
					<article class="solace-home__hero-slide is-active" aria-hidden="false">
						<div class="solace-home__welcome">
							<h1 id="solace-home-title"><?php esc_html_e( 'Welcome to Solace', 'solace-extra' ); ?></h1>
							<p><?php esc_html_e( 'Solace is fast, fully customisable & beautiful WordPress theme suitable for blog, personal portfolio, business website and WooCommerce storefront.', 'solace-extra' ); ?></p>
							<p><?php esc_html_e( 'It is very lightweight and offers unparalleled speed.', 'solace-extra' ); ?></p>
							<a class="solace-home__primary-button" href="<?php echo esc_url( $solace_extra_customizer_url ); ?>">
								<?php esc_html_e( 'Start Customizing', 'solace-extra' ); ?>
								<span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
							</a>
						</div>

						<div class="solace-home__status-list">
							<div class="solace-home__status">
								<span class="solace-home__status-icon<?php echo esc_attr( $solace_extra_is_theme ? '' : ' solace-home__status-icon--unavailable' ); ?>" aria-hidden="true">
									<?php if ( $solace_extra_is_theme ) : ?>
										<svg viewBox="0 0 40 40" focusable="false"><circle cx="20" cy="20" r="13.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m13.5 20.2 4.3 4.3 9-10" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"/></svg>
									<?php else : ?>
										<svg viewBox="0 0 40 40" focusable="false"><circle cx="20" cy="20" r="13.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m15 15 10 10m0-10L15 25" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2.2"/></svg>
									<?php endif; ?>
								</span>
								<h2><?php echo esc_html( $solace_extra_theme_status_title ); ?></h2>
								<p><?php echo esc_html( $solace_extra_theme_status_text ); ?></p>
							</div>

							<div class="solace-home__status">
								<span class="solace-home__status-icon<?php echo esc_attr( $solace_extra_site_is_online ? '' : ' solace-home__status-icon--unavailable' ); ?>" aria-hidden="true">
									<?php if ( $solace_extra_site_is_online ) : ?>
										<svg viewBox="0 0 40 40" focusable="false"><circle cx="20" cy="20" r="13.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="M6.8 20h26.4M20 6.5c4.1 3.7 6.3 8.2 6.3 13.5S24.1 29.8 20 33.5M20 6.5c-4.1 3.7-6.3 8.2-6.3 13.5s2.2 9.8 6.3 13.5M9.5 13.5h21M9.5 26.5h21" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.7"/></svg>
									<?php else : ?>
										<svg viewBox="0 0 40 40" focusable="false"><circle cx="20" cy="20" r="13.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m15 15 10 10m0-10L15 25" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2.2"/></svg>
									<?php endif; ?>
								</span>
								<h2><?php echo esc_html( $solace_extra_site_status_title ); ?></h2>
								<p><?php echo esc_html( $solace_extra_site_status_text ); ?></p>
							</div>

							<div class="solace-home__status">
								<span class="solace-home__status-icon" aria-hidden="true">
									<svg viewBox="0 0 40 40" focusable="false"><path d="M30.8 14.8A12.3 12.3 0 0 0 9 14l-2.8 3.1h8.7M9.2 25.2A12.3 12.3 0 0 0 31 26l2.8-3.1h-8.7" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"/><path d="m6.2 10.7.1 6.4 6.3-.1M33.8 29.3l-.1-6.4-6.3.1" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"/></svg>
								</span>
								<h2><?php echo esc_html( $solace_extra_update_status_title ); ?></h2>
								<p><?php echo esc_html( $solace_extra_update_status_text ); ?></p>
							</div>
						</div>
					</article>

					<article class="solace-home__hero-slide" aria-hidden="true">
						<div class="solace-home__welcome">
							<h1><?php esc_html_e( 'Welcome to Solace', 'solace-extra' ); ?></h1>
							<p><?php esc_html_e( 'Solace is fast, fully customisable & beautiful WordPress theme suitable for blog, personal portfolio, business website and WooCommerce storefront.', 'solace-extra' ); ?></p>
							<p><?php esc_html_e( 'It is very lightweight and offers unparalleled speed.', 'solace-extra' ); ?></p>
							<a class="solace-home__primary-button" href="<?php echo esc_url( $solace_extra_customizer_url ); ?>">
								<?php esc_html_e( 'Start Customizing', 'solace-extra' ); ?>
								<span class="dashicons dashicons-arrow-right-alt" aria-hidden="true"></span>
							</a>
						</div>

						<div class="solace-home__metrics">
							<div class="solace-home__metric">
								<span class="solace-home__metric-icon solace-home__metric-icon--php" aria-hidden="true">
									<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
									<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/metric-php.svg' ); ?>" alt="" />
								</span>
								<div class="solace-home__metric-content">
									<p class="solace-home__metric-label"><?php esc_html_e( 'PHP Version', 'solace-extra' ); ?></p>
									<h2 class="solace-home__metric-value"><?php echo esc_html( $solace_extra_php_version ); ?></h2>
								</div>
							</div>

							<div class="solace-home__metric">
								<span class="solace-home__metric-icon solace-home__metric-icon--theme" aria-hidden="true">
									<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
									<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/metric-theme.svg' ); ?>" alt="" />
								</span>
								<div class="solace-home__metric-content">
									<p class="solace-home__metric-label"><?php esc_html_e( 'Current Theme', 'solace-extra' ); ?></p>
									<h2 class="solace-home__metric-value"><?php echo esc_html( $solace_extra_theme_name . ' ' . $solace_extra_theme_version ); ?></h2>
								</div>
							</div>

							<div class="solace-home__metric solace-home__metric--pagespeed" data-pagespeed-card>
								<span class="solace-home__metric-icon solace-home__metric-icon--pagespeed" aria-hidden="true">
									<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
									<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/metric-pagespeed.svg' ); ?>" alt="" />
								</span>
								<div class="solace-home__metric-content">
									<p class="solace-home__metric-label"><?php esc_html_e( 'PageSpeed Score', 'solace-extra' ); ?></p>
									<?php if ( ! $solace_extra_pagespeed_has_key ) : ?>
										<h2 class="solace-home__metric-value" data-pagespeed-value><?php esc_html_e( 'Setup Required', 'solace-extra' ); ?></h2>
										<a class="solace-home__metric-link" href="<?php echo esc_url( $solace_extra_pagespeed_settings_url ); ?>"><?php esc_html_e( 'Add API Key', 'solace-extra' ); ?></a>
									<?php else : ?>
										<h2 class="solace-home__metric-value <?php echo esc_attr( null !== $solace_extra_pagespeed_score ? $solace_extra_pagespeed_class : '' ); ?>" data-pagespeed-value>
											<?php echo null !== $solace_extra_pagespeed_score ? esc_html( $solace_extra_pagespeed_score . ' / 100' ) : esc_html__( 'Not Tested', 'solace-extra' ); ?>
										</h2>
										<button type="button" class="solace-home__metric-link solace-home__pagespeed-test" data-pagespeed-test>
											<?php echo null !== $solace_extra_pagespeed_score ? esc_html__( 'Retest', 'solace-extra' ) : esc_html__( 'Run Test', 'solace-extra' ); ?>
										</button>
										<p class="solace-home__pagespeed-status" data-pagespeed-status aria-live="polite">
											<?php
											if ( $solace_extra_pagespeed_tested_at > 0 ) {
												printf(
													/* translators: %s: date and time of the latest PageSpeed test. */
													esc_html__( 'Mobile · Last tested %s', 'solace-extra' ),
													esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $solace_extra_pagespeed_tested_at ) )
												);
											}
											?>
										</p>
									<?php endif; ?>
								</div>
							</div>

							<div class="solace-home__metric">
								<span class="solace-home__metric-icon solace-home__metric-icon--health" aria-hidden="true">
									<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
									<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/metric-health.svg' ); ?>" alt="" />
								</span>
								<div class="solace-home__metric-content">
									<p class="solace-home__metric-label"><?php esc_html_e( 'Site Health', 'solace-extra' ); ?></p>
									<h2 class="solace-home__metric-value <?php echo esc_attr( $solace_extra_health_class ); ?>"><?php echo esc_html( $solace_extra_health_label ); ?></h2>
								</div>
							</div>

							<div class="solace-home__metric solace-home__metric--updates">
								<span class="solace-home__metric-icon solace-home__metric-icon--updates" aria-hidden="true">
									<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
									<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/metric-updates.svg' ); ?>" alt="" />
								</span>
								<div class="solace-home__metric-content">
									<p class="solace-home__metric-label"><?php esc_html_e( 'Updates', 'solace-extra' ); ?></p>
									<?php if ( $solace_extra_updates_count > 0 ) : ?>
										<h2 class="solace-home__metric-value is-warning"><?php
										/* translators: %d: number of available updates. */
										echo esc_html( sprintf( _n( '%d Available', '%d Available', $solace_extra_updates_count, 'solace-extra' ), $solace_extra_updates_count ) );
										?></h2>
										<a class="solace-home__metric-link" href="<?php echo esc_url( $solace_extra_updates_url ); ?>"><?php esc_html_e( 'View Updates', 'solace-extra' ); ?></a>
									<?php else : ?>
										<h2 class="solace-home__metric-value is-good"><?php esc_html_e( 'Up to date', 'solace-extra' ); ?></h2>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</article>
				</div>

				<div class="solace-home__hero-controls">
					<button type="button" class="solace-home__hero-arrow solace-home__hero-arrow--prev" aria-label="<?php esc_attr_e( 'Previous slide', 'solace-extra' ); ?>">
						<span class="dashicons dashicons-arrow-left-alt2" aria-hidden="true"></span>
					</button>

					<nav class="solace-home__hero-dots" aria-label="<?php esc_attr_e( 'Hero slides', 'solace-extra' ); ?>">
						<button type="button" class="is-active" aria-label="<?php esc_attr_e( 'Show status overview', 'solace-extra' ); ?>" aria-current="true" data-slide="0"></button>
						<button type="button" aria-label="<?php esc_attr_e( 'Show site metrics', 'solace-extra' ); ?>" data-slide="1"></button>
					</nav>

					<button type="button" class="solace-home__hero-arrow solace-home__hero-arrow--next" aria-label="<?php esc_attr_e( 'Next slide', 'solace-extra' ); ?>">
						<span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		</section>

		<section class="solace-home__features" aria-label="<?php esc_attr_e( 'Get started with Solace', 'solace-extra' ); ?>">
			<article class="solace-home__feature">
				<div class="solace-home__feature-copy">
					<h2><?php esc_html_e( 'Starter Templates', 'solace-extra' ); ?></h2>
					<p><?php esc_html_e( 'Install a demo site in a few clicks.', 'solace-extra' ); ?></p>
					<a class="solace-home__primary-button" href="<?php echo esc_url( $solace_extra_templates_url ); ?>"><?php esc_html_e( 'Start Now', 'solace-extra' ); ?></a>
				</div>
				<div class="solace-home__feature-image">
					<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
					<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/new-start-template2.png' ); ?>" alt="" />
				</div>
			</article>

			<article class="solace-home__feature">
				<div class="solace-home__feature-copy">
					<h2><?php esc_html_e( 'Customize Solace', 'solace-extra' ); ?></h2>
					<p><?php esc_html_e( 'Customize any part of your website.', 'solace-extra' ); ?></p>
					<a class="solace-home__primary-button" href="<?php echo esc_url( $solace_extra_customizer_url ); ?>"><?php esc_html_e( 'Customize', 'solace-extra' ); ?></a>
				</div>
				<div class="solace-home__feature-image">
					<?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?>
					<img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/customizer.png' ); ?>" alt="" />
				</div>
			</article>
		</section>

		<section class="solace-home__resources" aria-label="<?php esc_attr_e( 'Solace resources', 'solace-extra' ); ?>">
			<article class="solace-home__resource">
				<div class="solace-home__resource-content">
					<span class="solace-home__resource-icon"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/contact.svg' ); ?>" alt="" /></span>
					<div><h2><?php esc_html_e( 'Need some help?', 'solace-extra' ); ?></h2><p><?php esc_html_e( 'We would love to be of any assistance.', 'solace-extra' ); ?></p></div>
				</div>
				<a href="<?php echo esc_url( $solace_extra_support_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open Ticket', 'solace-extra' ); ?></a>
			</article>

			<article class="solace-home__resource">
				<div class="solace-home__resource-content">
					<span class="solace-home__resource-icon"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/docs.svg' ); ?>" alt="" /></span>
					<div><h2><?php esc_html_e( 'Documentation', 'solace-extra' ); ?></h2><p><?php esc_html_e( 'Learn any aspect of Solace Theme.', 'solace-extra' ); ?></p></div>
				</div>
				<a href="https://solacewp.com/documentation/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Start reading', 'solace-extra' ); ?></a>
			</article>

			<article class="solace-home__resource">
				<div class="solace-home__resource-content">
					<span class="solace-home__resource-icon"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/pro.svg' ); ?>" alt="" /></span>
					<div>
						<h2><?php echo $solace_extra_is_pro ? esc_html__( 'Pro Activated', 'solace-extra' ) : esc_html__( 'GO PRO', 'solace-extra' ); ?></h2>
						<p><?php echo $solace_extra_is_pro ? esc_html__( 'Thank you for purchasing Solace Extra Pro.', 'solace-extra' ) : esc_html__( 'Full access to premium features.', 'solace-extra' ); ?></p>
					</div>
				</div>
				<?php if ( $solace_extra_is_pro ) : ?>
					<span class="solace-home__active-label"><?php esc_html_e( 'Active', 'solace-extra' ); ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( SOLACE_UPGRADE_URL ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade', 'solace-extra' ); ?></a>
				<?php endif; ?>
			</article>

			<article class="solace-home__resource">
				<div class="solace-home__resource-content">
					<span class="solace-home__resource-icon"><?php // phpcs:ignore PluginCheck.CodeAnalysis.ImageFunctions.NonEnqueuedImage ?><img src="<?php echo esc_url( SOLACE_EXTRA_ASSETS_URL . 'images/dashboard/sugges.svg' ); ?>" alt="" /></span>
					<div><h2><?php esc_html_e( 'Suggestions', 'solace-extra' ); ?></h2><p><?php esc_html_e( 'Let us know how we can improve.', 'solace-extra' ); ?></p></div>
				</div>
				<a href="https://solacewp.com/suggestions/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact Us', 'solace-extra' ); ?></a>
			</article>
		</section>
	</main>
</div>
