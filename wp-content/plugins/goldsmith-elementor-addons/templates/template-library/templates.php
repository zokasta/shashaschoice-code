<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'masonry' );
?>
<script type="text/template" id="tmpl-goldsmithTemplateLibrary__header-logo">
    <span class="goldsmithTemplateLibrary__logo-wrap">
		<i class="goldsmith goldsmith-addons"></i>
	</span>
    <span class="goldsmithTemplateLibrary__logo-title">NINETHEME {{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'goldsmith' ); ?></span>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
		<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__header-menu-responsive">
	<div class="elementor-component-tab goldsmithTemplateLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'goldsmith' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'goldsmith' ); ?></span>
	</div>
	<div class="elementor-component-tab goldsmithTemplateLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'goldsmith' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'goldsmith' ); ?></span>
	</div>
	<div class="elementor-component-tab goldsmithTemplateLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'goldsmith' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'goldsmith' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__header-actions">
	<div id="goldsmithTemplateLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'goldsmith' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'goldsmith' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ goldsmith.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button goldsmithTemplateLibrary__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'goldsmith' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'goldsmith' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__templates">
	<div id="goldsmithTemplateLibrary__toolbar">
		<div id="goldsmithTemplateLibrary__toolbar-filter" class="goldsmithTemplateLibrary__toolbar-filter">
			<# if (goldsmith.library.getTypeTags()) { var selectedTag = goldsmith.library.getFilter( 'tags' ); #>
				<# if ( selectedTag ) { #>
				<span class="goldsmithTemplateLibrary__filter-btn">{{{ goldsmith.library.getTags()[selectedTag] }}} <i class="eicon-caret-right"></i></span>
				<# } else { #>
				<span class="goldsmithTemplateLibrary__filter-btn"><?php esc_html_e( 'Filter', 'goldsmith' ); ?> <i class="eicon-caret-right"></i></span>
				<# } #>
				<ul id="goldsmithTemplateLibrary__filter-tags" class="goldsmithTemplateLibrary__filter-tags">
					<li data-tag="">All</li>
					<# _.each(goldsmith.library.getTypeTags(), function(slug) {
						var selected = selectedTag === slug ? 'active' : '';
						#>
						<li data-tag="{{ slug }}" class="{{ selected }}">{{{ goldsmith.library.getTags()[slug] }}}</li>
					<# } ); #>
				</ul>
			<# } #>
		</div>
		<div id="goldsmithTemplateLibrary__toolbar-counter"></div>
		<div id="goldsmithTemplateLibrary__toolbar-search">
			<label for="goldsmithTemplateLibrary__search" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'goldsmith' ); ?></label>
			<input id="goldsmithTemplateLibrary__search" placeholder="<?php esc_attr_e( 'Search', 'goldsmith' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>

	<div class="goldsmithTemplateLibrary__templates-window">
		<div id="goldsmithTemplateLibrary__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__template">
	<div class="goldsmithTemplateLibrary__template-body elementor-template-library-template-body" data-col="template-col-{{ col }}" id="goldsmithTemplate-{{ template_id }}">

		<div class="goldsmithTemplateLibrary__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
        <img class="goldsmithTemplateLibrary__template-thumbnail" src="{{ thumbnail }}">
        <div class="goldsmithTemplateLibrary__template-name">{{ title }}</div>
	</div>
	<div class="goldsmithTemplateLibrary__template-footer">
		{{{ goldsmith.library.getModal().getTemplateActionButton( obj ) }}}

		<a href="#" class="elementor-button goldsmithTemplateLibrary__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'goldsmith' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-goldsmithTemplateLibrary__empty">

	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg'; ?>" class="elementor-template-library-no-results" />
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Goldsmith Library?', 'goldsmith' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://ninetheme.com/themes/goldsmith/fashion/" target="_blank"><?php echo __( 'Click here', 'goldsmith' ); ?></a>
	</div>
</script>
