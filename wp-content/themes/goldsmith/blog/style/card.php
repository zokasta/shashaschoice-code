<?php
/**
 * The template for displaying post content card style within loops
 *
 * This template can be editing
 *
 */

$size = goldsmith_loop_post_thumbnail_size();
$bg   = get_the_post_thumbnail_url( get_the_ID(), $size );
?>

<div id="post-<?php echo get_the_ID() ?>" <?php post_class( 'goldsmith-blog-posts-item style-card' ); ?>>
    <div class="goldsmith-blog-post-item-inner" data-background="<?php echo esc_url( $bg ); ?>">

        <?php goldsmith_loop_post_first_category(); ?>

        <div class="goldsmith-blog-post-content">
            <div class="goldsmith-blog-post-meta goldsmith-inline-two-block">
                <?php echo goldsmith_loop_post_author('<h6 class="goldsmith-post-author">','</h6>', true); ?>
                <?php echo goldsmith_loop_post_date('<h6 class="goldsmith-post-date">','</h6>', true); ?>
            </div>
            <?php goldsmith_loop_post_title(); ?>
            <?php goldsmith_loop_post_excerpt(); ?>
        </div>

    </div>
</div>
