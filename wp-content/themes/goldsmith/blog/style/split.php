<?php
/**
* The template for displaying post content card style within loops
*
* This template can be editing
*
*/
$grid_column = apply_filters('goldsmith_blog_grid_column', goldsmith_settings( 'grid_column', '1' ) );
$size =  $grid_column == 1 ? goldsmith_loop_post_thumbnail_size() : [250,250];
?>

<div id="post-<?php echo get_the_ID() ?>" <?php post_class( 'goldsmith-blog-posts-item style-split' ); ?>>
    <div class="goldsmith-blog-post-item-inner">

        <div class="goldsmith-blog-post-thumb-wrapper">
            <div class="goldsmith-blog-post-thumb">
                <?php echo get_the_post_thumbnail( get_the_ID(), goldsmith_loop_post_thumbnail_size() ); ?>
                <a class="blog-thumb-link" href="<?php echo esc_url( get_permalink() ); ?>" title="<?php echo get_the_title(); ?>"></a>
            </div>
            <?php goldsmith_loop_post_first_category(); ?>
        </div>

        <div class="goldsmith-blog-post-content">
            <?php goldsmith_loop_post_title(); ?>
            <?php goldsmith_loop_post_excerpt(); ?>

            <div class="goldsmith-blog-post-meta goldsmith-inline-two-block">
                <?php echo goldsmith_loop_post_author('<h6 class="goldsmith-post-author">','</h6>', true); ?>
                <?php echo goldsmith_loop_post_date('<h6 class="goldsmith-post-date">','</span>', true); ?>
            </div>
        </div>

    </div>
</div>
