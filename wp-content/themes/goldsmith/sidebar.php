<?php
/**
* The sidebar containing the main widget area
*
* @package WordPress
* @subpackage Goldsmith
* @since Goldsmith 1.0
*/
$sidebar = 'sidebar-1';
if ( class_exists( 'Redux' ) ) {
    if ( is_search() ) {
        $sidebar = 'goldsmith-search-sidebar';
    } elseif ( is_archive() ) {
        $sidebar = 'goldsmith-archive-sidebar';
    } else {
        $sidebar = 'sidebar-1';
    }
}
if ( class_exists('\Elementor\Frontend' ) && ! empty( goldsmith_settings( 'blog_sidebar_templates' ) ) ) {

    $template_id = apply_filters( 'goldsmith_translated_template_id', intval( goldsmith_settings( 'blog_sidebar_templates' ) ) );
    $frontend = new \Elementor\Frontend;
    printf('<div class="col-lg-3"><div class="blog-sidebar nt-sidebar-elementor">%1$s</div></div>', $frontend->get_builder_content_for_display( $template_id, false ) );

} else {

    if ( is_active_sidebar( $sidebar ) ) {
        ?>
        <div id="nt-sidebar" class="nt-sidebar goldsmith-blog-sidebar col-12 col-sm-6 col-lg-3">
            <div class="blog-sidebar nt-sidebar-inner">
                <?php dynamic_sidebar( $sidebar ); ?>
            </div>
        </div>
        <?php
    }
}
?>
