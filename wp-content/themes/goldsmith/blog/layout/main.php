<?php
/**
* The main template file
*
*/

if ( is_search() ) {
    $name           = 'search';
    $sidebar        = 'goldsmith-search-sidebar';
    $default_layout = 'full-width';
} elseif ( is_archive() ) {
    $name           = 'archive';
    $sidebar        = 'goldsmith-archive-sidebar';
    $default_layout = 'full-width';
} else {
    $name           = 'index';
    $sidebar        = 'sidebar-1';
    $default_layout = 'right-sidebar';
}

$goldsmith_layout   = apply_filters('goldsmith_index_layout', goldsmith_settings( $name.'_layout', $default_layout ) );
$grid_column        = apply_filters('goldsmith_blog_grid_column', goldsmith_settings( 'grid_column', '3' ) );
$grid_tablet_column = apply_filters('goldsmith_blog_grid_mobile_column', goldsmith_settings( 'grid_mobile_column', '2' ) );
$grid_mobile_column = goldsmith_settings( 'grid_mobile_column_xs', '1' );

$masonry       = apply_filters('goldsmith_index_type', goldsmith_settings( 'index_type', 'masonry' ) );
$is_masonry    = 'masonry' == apply_filters('goldsmith_index_type', goldsmith_settings( 'index_type', 'masonry' ) ) ? ' goldsmith-masonry-container' : '';
$has_sidebar   = ! empty( goldsmith_settings( 'blog_sidebar_templates', null ) ) || is_active_sidebar( $sidebar ) ? true : false;
$layout_column = !$has_sidebar || 'full-width' == $goldsmith_layout ? 'col-lg-12 post-column' : 'col-lg-9 post-column';
$row_reverse   = (! empty( goldsmith_settings( 'blog_sidebar_templates', null ) ) || is_active_sidebar( $sidebar ) ) && 'left-sidebar' == $goldsmith_layout ? ' flex-lg-row-reverse' : '';
$post_style    = apply_filters('goldsmith_blog_post_style', goldsmith_settings( 'post_style', 'classic' ) );

if ( 'masonry' == $masonry ) {
    wp_enqueue_script( 'imagesloaded' );
    wp_enqueue_script( 'masonry' );
}
wp_enqueue_style( 'goldsmith-blog-post' );
?>

<div class="blog-area section-padding goldsmith-blog-<?php echo esc_attr( $post_style  ); ?>">
    <div class="container">
        <div class="row justify-content-lg-center<?php echo esc_attr( $row_reverse ); ?>">

            <!-- Sidebar column control -->
            <div class="<?php echo esc_attr( $layout_column ); ?>">
                <div class="row row-cols-<?php echo esc_attr( $grid_mobile_column ); ?> row-cols-sm-<?php echo esc_attr( $grid_tablet_column ); ?> row-cols-lg-<?php echo esc_attr( $grid_column.$is_masonry ); ?> goldsmith-posts-row">
                    <?php
                    if ( have_posts() ) {
                        while ( have_posts() ) {
                            the_post();
                            get_template_part( 'blog/style/'.$post_style );
                        }
                    } else {
                        // if there are no posts, read content none function
                        goldsmith_content_none();
                    }
                    ?>
                </div>
                <?php
                // this function working with wp reading settins + posts
                goldsmith_index_loop_pagination(true);
                ?>
            </div>
            <!-- End content column -->

            <!-- right sidebar -->
            <?php
            if ( $has_sidebar && ( 'right-sidebar' == $goldsmith_layout || 'left-sidebar' == $goldsmith_layout ) ) {
                get_sidebar();
            }
            ?>
            <!-- End right sidebar -->

        </div><!--End row -->
    </div><!--End container -->
</div><!--End #blog -->
