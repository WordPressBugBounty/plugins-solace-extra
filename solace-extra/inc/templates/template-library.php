<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;

?>
<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__header-logo">
	<span class="solaceExtraTemplateLibrary__logo-wrap">
		<svg width="14" height="24" viewBox="0 0 14 24" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><rect width="14" height="24" fill="url(#pattern0_40_3)"/><defs><pattern id="pattern0_40_3" patternContentUnits="objectBoundingBox" width="1" height="1"><use xlink:href="#image0_40_3" transform="scale(0.0714286 0.0416667)"/></pattern><image id="image0_40_3" width="14" height="24" preserveAspectRatio="none" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAYCAYAAADKx8xXAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAANNSURBVHgBdZN7TNNXFMc/rbWdnWMKZM5ntsVNYzKXCHOSLIxkmf6xWTK3OWYy5mIiUaMmGh+gwWoUH5iqaKhvfABqFJQm+CKI70RTRTQalShEbQqCVCoPC7TX3/1xSyqRk/x+v3Pv9/c995zvORf6NqN8xQ3F2pDNVXGMt5fm8Hck9iGCQTr37DjFRTp3TidRrmtyOBU6gG9RIiMjCYZwpIp5pIjziOosViusn/aYpOPbhksUIIgAcPzKV20naPLlUREXR38VTH6pSmeWuIAo+g/b03TO6KyEEQxo3seN1iJeOn5juAqmE3KSGddVjv+FA5ddZdS4nltcW0BKZx7+sjSSFcEsX7YxfPLmKPdajuDJ/p3PFGbRiQ4eIg4i6jdRqNLSRaneyG5NlI7Cf/kpMtjMJAa1n6KmLosj1NrJ3ziVSVKMqsUs0eoI3V5CZkTtenruZeQGi2lw/sVEHWnaQEVYoUAhHTO/4CN1sq7ilfmkyaDeXC77nbh/GcMwuW/SdA2GiUEfjfUmXWqROpZPd2ZS2eChZPpkTMc12D6FCaeXU3mnihxjr9b3rH8cz+IBVgaHjERrpJDcGzWY/qKNN/HfkG6iD4u1ElvqwtbRRZQ4TdB7h6sDRzDMnMrXtZmUG7uC3dF6m0Zos5j5ctohSv7PIDpmOJ9HpTJalmE1E2WKGcW34Z8NRnoymDCa78ym7onytGMNtdP5XlXFZcwX5YiCf0ho8vLQFs9QcYmW1hauafrGBo7jHWTBYgjwtndWetPbdnBZFCNeO6meN5GYMDhlHNGilE7/bi2QspdrcevO3VUUaKTXu/5grML0SUn/mZjmwzyr30KZnGfUMHjWcJ2ufJqfZrBGESxh8PlmTsqUtyf3aKAH22pjiCjUeu+1czN4mNonDgok4F7KXHEFcX0uaYogBTMkJWFqdFIWcOFb+AND8GZxTj9+Jctkv+q2cCwsHOqePl7PCnEW4V7AjB5d6tbhChftz6VGufpdLE0jUZwj8CiLvRFCdk+XyEfkTGOS9Fv34JPf2XHEtpbwwr+fB/JeKlI/IlsxeTwfn8ig8lUjtcZ6og0DuTsynj/35JIwu4j7ihDkA6YfnT2V78UOPHkzSIlIy0Af9g75vTk7EnMFigAAAABJRU5ErkJggg=="/></defs></svg>
	</span>
	<span class="solaceExtraTemplateLibrary__logo-title">{{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php esc_html_e( 'Back to Library', 'solace-extra' ); ?></span>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
	<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{
		args.title }}}
	</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__header-menu-responsive">
	<div class="elementor-component-tab solaceExtraTemplateLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'solace-extra' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'solace-extra' ); ?></span>
	</div>
	<div class="elementor-component-tab solaceExtraTemplateLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'solace-extra' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'solace-extra' ); ?></span>
	</div>
	<div class="elementor-component-tab solaceExtraTemplateLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'solace-extra' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'solace-extra' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__header-actions">
	<div id="solaceExtraTemplateLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'solace-extra' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'solace-extra' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__preview">
	<iframe></iframe>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ solaceExtra.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button solaceExtraTemplateLibrary__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'solace-extra' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__pro-button">
	<a class="elementor-template-library-template-action elementor-button solaceExtraTemplateLibrary__pro-button" href="https://pro.solacewp.com/" target="_blank">
		<i class="eicon-external-link-square" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Get Pro', 'solace-extra' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'solace-extra' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__templates">
	<div id="solaceExtraTemplateLibrary__toolbar">
		<div id="solaceExtraTemplateLibrary__toolbar-filter" class="solaceExtraTemplateLibrary__toolbar-filter">
			<# if (solaceExtra.library.getTypeTags()) { var selectedTag = solaceExtra.library.getFilter( 'tags' ); #>
			<span class="solaceExtraTemplateLibrary__filter-btn"><?php esc_html_e( 'Category', 'solace-extra' ); ?> <i
						class="eicon-caret-right"></i></span>
			<select id="solaceExtraTemplateLibrary__filter-select" class="solaceExtraTemplateLibrary__filter-select" data-elementor-filter="tags">
				<option value="">All</option>
				<# var tagsMap = solaceExtra.library.getTags(); #>
				<# _.each(solaceExtra.library.getTypeTags(), function(slug){ #>
					<option value="{{ slug }}" {{ selectedTag === slug ? 'selected' : '' }}>{{{ tagsMap && tagsMap[slug] ? tagsMap[slug] : slug }}}</option>
				<# }); #>
			</select>
			<# } #>
		</div>
		<div id="solaceExtraTemplateLibrary__toolbar-counter"></div>
		<div id="solaceExtraTemplateLibrary__toolbar-search">
			<label for="solaceExtraTemplateLibrary__search" class="elementor-screen-only">
				<?php esc_html_e( 'Search Templates:', 'solace-extra' ); ?>
			</label>
			<input id="solaceExtraTemplateLibrary__search" placeholder="<?php esc_attr_e( 'Search', 'solace-extra' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>

	<div class="solaceExtraTemplateLibrary__templates-window">
		<div id="solaceExtraTemplateLibrary__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__template">
	<div class="solaceExtraTemplateLibrary__template-body" id="solaceExtraTemplate-{{ template_id }}">
		<div class="solaceExtraTemplateLibrary__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>

		<# if ( thumbnail ) { #>
		<img class="solaceExtraTemplateLibrary__template-thumbnail" src="{{ thumbnail }}" alt="thumbnail">
		<# } else { 
			var iframeSrc = '';
			if ( custom && preview ) {
				iframeSrc = preview;
			} else {
				iframeSrc = url;
			}
		#>
		<div class="solaceExtraTemplateLibrary__iframe-wrapper" style="
			width: 100%;
			height: 185px;
			height: 367px;
			overflow: hidden;
			position: relative;
		">
			<iframe 
			style="
				transform: scale(0.25);
				transform-origin: top left;
				width: 1500px;
				height: 600px;
				height: 100vw;
				height: 65vw;
				height: 1200px;
				height: 1500px;
				border: none;
			"
			class="solaceExtraTemplateLibrary__template-thumbnail" src="{{ iframeSrc }}" frameborder="0" allowfullscreen></iframe>
		</div>
		<# } #>
		<# if ( obj.isPro ) { #>
		<span class="solaceExtraTemplateLibrary__template-badge"><?php esc_html_e( 'Pro', 'solace-extra' ); ?></span>
		<# } #>
	</div>
	<div class="solaceExtraTemplateLibrary__template-footer">
		{{{ solaceExtra.library.getModal().getTemplateActionButton( obj ) }}}
		<a href="#" class="elementor-button solaceExtraTemplateLibrary__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'solace-extra' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-solaceExtraTemplateLibrary__empty">
	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo esc_url( ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg' ); ?>" class="elementor-template-library-no-results" alt="no-result"/>
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Solace Extra Library?', 'solace-extra' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://solacewp.com/docs/" target="_blank"><?php esc_html_e( 'Click here', 'solace-extra' ); ?></a>
	</div>
</script>
