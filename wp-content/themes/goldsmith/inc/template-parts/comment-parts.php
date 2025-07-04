<?php

/*************************************************
## Post Comment Customization
*************************************************/

if ( ! function_exists( 'goldsmith_custom_commentlist' ) ) {
    // Theme custom comment list
    function goldsmith_custom_commentlist($comment, $args, $depth) {
        $GLOBALS['comment'] = $comment; ?>
        <li <?php comment_class('nt-comment-item'); ?> id="li-comment-<?php comment_ID() ?>">
            <div id="comment-<?php comment_ID(); ?>">
                <div class="nt-comment-left">
                    <div class="nt-comment-avatar">
                        <?php echo get_avatar($comment,$size='80' ); ?>
                    </div>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <em><?php esc_html_e('Your comment is awaiting moderation.', 'goldsmith') ?></em>
                        <br />
                    <?php endif; ?>
                </div>
                <div class="nt-comment-right">
                    <div class="nt-comment-author comment__author-name">
                        <?php echo get_comment_author_link(); ?>
                    </div>
                    <div class="nt-comment-date">
                        <span class="post-meta__item __date-post">
                            <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
                                <?php printf(esc_html__('%1$s at %2$s', 'goldsmith'), get_comment_date(),  get_comment_time()) ?>
                            </a>
                            <?php edit_comment_link(esc_html__('(Edit)', 'goldsmith'),'  ','') ?>
                        </span>
                    </div>
                    <div class="nt-comment-content nt-goldsmith-content nt-clearfix"><?php comment_text() ?></div>
                    <div class="nt-comment-date post-meta">
                        <div class="nt-comment-reply-content post-meta__item"><?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?></div>
                    </div>
                </div>
            </div>
        <?php
    }
}



// add class comment form button
if ( ! function_exists( 'goldsmith_addclass_form_button' ) ) {
    function goldsmith_addclass_form_button( $arg ) {
        $arg['class_submit'] = 'goldsmith-btn-medium goldsmith-btn goldsmith-bg-black';
        return $arg;
    }
    // run the comment form defaults through the newly defined filter
    add_filter( 'comment_form_defaults', 'goldsmith_addclass_form_button' );
}
