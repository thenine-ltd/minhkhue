<?php
/**
 * Template library templates
 */

defined( 'ABSPATH' ) || exit;
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'masonry' );
?>
<script type="text/template" id="tmpl-stylerTemplateLibrary__header-logo">
    <span class="stylerTemplateLibrary__logo-wrap">
		<i class="styler styler-addons"></i>
	</span>
    <span class="stylerTemplateLibrary__logo-title">NINETHEME {{{ title }}}</span>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__header-back">
	<i class="eicon-" aria-hidden="true"></i>
	<span><?php echo __( 'Back to Library', 'styler' ); ?></span>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__header-menu">
	<# _.each( tabs, function( args, tab ) { var activeClass = args.active ? 'elementor-active' : ''; #>
		<div class="elementor-component-tab elementor-template-library-menu-item {{activeClass}}" data-tab="{{{ tab }}}">{{{ args.title }}}</div>
	<# } ); #>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__header-menu-responsive">
	<div class="elementor-component-tab stylerTemplateLibrary__responsive-menu-item elementor-active" data-tab="desktop">
		<i class="eicon-device-desktop" aria-hidden="true" title="<?php esc_attr_e( 'Desktop view', 'styler' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Desktop view', 'styler' ); ?></span>
	</div>
	<div class="elementor-component-tab stylerTemplateLibrary__responsive-menu-item" data-tab="tab">
		<i class="eicon-device-tablet" aria-hidden="true" title="<?php esc_attr_e( 'Tab view', 'styler' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Tab view', 'styler' ); ?></span>
	</div>
	<div class="elementor-component-tab stylerTemplateLibrary__responsive-menu-item" data-tab="mobile">
		<i class="eicon-device-mobile" aria-hidden="true" title="<?php esc_attr_e( 'Mobile view', 'styler' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Mobile view', 'styler' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__header-actions">
	<div id="stylerTemplateLibrary__header-sync" class="elementor-templates-modal__header__item">
		<i class="eicon-sync" aria-hidden="true" title="<?php esc_attr_e( 'Sync Library', 'styler' ); ?>"></i>
		<span class="elementor-screen-only"><?php esc_html_e( 'Sync Library', 'styler' ); ?></span>
	</div>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__preview">
    <iframe></iframe>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__header-insert">
	<div id="elementor-template-library-header-preview-insert-wrapper" class="elementor-templates-modal__header__item">
		{{{ styler.library.getModal().getTemplateActionButton( obj ) }}}
	</div>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__insert-button">
	<a class="elementor-template-library-template-action elementor-button stylerTemplateLibrary__insert-button">
		<i class="eicon-file-download" aria-hidden="true"></i>
		<span class="elementor-button-title"><?php esc_html_e( 'Insert', 'styler' ); ?></span>
	</a>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__loading">
	<div class="elementor-loader-wrapper">
		<div class="elementor-loader">
			<div class="elementor-loader-boxes">
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
				<div class="elementor-loader-box"></div>
			</div>
		</div>
		<div class="elementor-loading-title"><?php esc_html_e( 'Loading', 'styler' ); ?></div>
	</div>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__templates">
	<div id="stylerTemplateLibrary__toolbar">
		<div id="stylerTemplateLibrary__toolbar-filter" class="stylerTemplateLibrary__toolbar-filter">
			<# if (styler.library.getTypeTags()) { var selectedTag = styler.library.getFilter( 'tags' ); #>
				<# if ( selectedTag ) { #>
				<span class="stylerTemplateLibrary__filter-btn">{{{ styler.library.getTags()[selectedTag] }}} <i class="eicon-caret-right"></i></span>
				<# } else { #>
				<span class="stylerTemplateLibrary__filter-btn"><?php esc_html_e( 'Filter', 'styler' ); ?> <i class="eicon-caret-right"></i></span>
				<# } #>
				<ul id="stylerTemplateLibrary__filter-tags" class="stylerTemplateLibrary__filter-tags">
					<li data-tag="">All</li>
					<# _.each(styler.library.getTypeTags(), function(slug) {
						var selected = selectedTag === slug ? 'active' : '';
						#>
						<li data-tag="{{ slug }}" class="{{ selected }}">{{{ styler.library.getTags()[slug] }}}</li>
					<# } ); #>
				</ul>
			<# } #>
		</div>
		<div id="stylerTemplateLibrary__toolbar-counter"></div>
		<div id="stylerTemplateLibrary__toolbar-search">
			<label for="stylerTemplateLibrary__search" class="elementor-screen-only"><?php esc_html_e( 'Search Templates:', 'styler' ); ?></label>
			<input id="stylerTemplateLibrary__search" placeholder="<?php esc_attr_e( 'Search', 'styler' ); ?>">
			<i class="eicon-search"></i>
		</div>
	</div>

	<div class="stylerTemplateLibrary__templates-window">
		<div id="stylerTemplateLibrary__templates-list"></div>
	</div>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__template">
	<div class="stylerTemplateLibrary__template-body elementor-template-library-template-body" data-col="template-col-{{ col }}" id="stylerTemplate-{{ template_id }}">

		<div class="stylerTemplateLibrary__template-preview">
			<i class="eicon-zoom-in-bold" aria-hidden="true"></i>
		</div>
        <img class="stylerTemplateLibrary__template-thumbnail" src="{{ thumbnail }}">
        <div class="stylerTemplateLibrary__template-name">{{ title }}</div>
	</div>
	<div class="stylerTemplateLibrary__template-footer">
		{{{ styler.library.getModal().getTemplateActionButton( obj ) }}}

		<a href="#" class="elementor-button stylerTemplateLibrary__preview-button">
			<i class="eicon-device-desktop" aria-hidden="true"></i>
			<?php esc_html_e( 'Preview', 'styler' ); ?>
		</a>
	</div>
</script>

<script type="text/template" id="tmpl-stylerTemplateLibrary__empty">

	<div class="elementor-template-library-blank-icon">
		<img src="<?php echo ELEMENTOR_ASSETS_URL . 'images/no-search-results.svg'; ?>" class="elementor-template-library-no-results" />
	</div>
	<div class="elementor-template-library-blank-title"></div>
	<div class="elementor-template-library-blank-message"></div>
	<div class="elementor-template-library-blank-footer">
		<?php esc_html_e( 'Want to learn more about the Styler Library?', 'styler' ); ?>
		<a class="elementor-template-library-blank-footer-link" href="https://ninetheme.com/themes/styler/fashion/" target="_blank"><?php echo __( 'Click here', 'styler' ); ?></a>
	</div>
</script>
