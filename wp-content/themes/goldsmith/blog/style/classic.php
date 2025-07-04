<?php
/**
 * The template for displaying post content default style within loops
 *
 * This template can be editing
 *
 */

$post_style = goldsmith_settings( 'post_style', 'classic' );
?>

<div id="post-<?php echo get_the_ID() ?>" <?php post_class( 'goldsmith-blog-posts-item style-'.$post_style ); ?>>
    <div class="goldsmith-blog-post-item-inner">

        <?php if ( is_sticky() ) { ?>
            <span class="blog-sticky"><?php esc_html_e( 'Featured', 'goldsmith' ); ?></span>
        <?php } ?>

        <?php if ( has_post_thumbnail() ) { ?>
            <div class="goldsmith-blog-thumb image-<?php echo apply_filters('goldsmith_post_image_size_style', goldsmith_settings( 'post_image_style', 'default' ) ); ?>">
                <?php goldsmith_loop_post_thumbnail(); ?>
                <?php goldsmith_loop_post_first_category(); ?>
            </div>
        <?php } ?>

        <div class="goldsmith-blog-post-content">
            <div class="goldsmith-blog-post-meta">
                <?php echo goldsmith_loop_post_author('<h6 class="goldsmith-post-meta-title">','</h6>', true); ?>
                <?php echo goldsmith_loop_post_date('<span class="goldsmith-post-meta-date">','</span>', true); ?>
            </div>
            <?php
                goldsmith_loop_post_title();
                goldsmith_loop_post_excerpt();
            ?>
            <?php if ( ! get_the_title() ) { ?>
                <a class="blog-read-more-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo get_the_title(); ?>"><?php esc_html_e( 'Read More', 'goldsmith' ); ?></a>
            <?php } ?>
        </div>

    </div>
</div>
