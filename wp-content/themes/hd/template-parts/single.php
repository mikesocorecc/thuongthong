<?php

\defined( '\WPINC' ) || die;

if (have_posts()) the_post();
if (post_password_required()) :
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
endif;

// template-parts/parts/page-title.php
the_page_title_theme();

global $post;

?>

<section class="section single-post">
    <div class="grid-container width-extra">
        <?php get_template_part('template-parts/parts/sharing'); ?>
        <div class="col-content">
            <?php echo post_terms($post->ID); ?>
            <?php echo post_excerpt($post, 'excerpt', true); ?>
            <?php get_template_part('template-parts/parts/upseo'); ?>
            <div class="content clearfix">
                <?php
                // post content
                the_content();

                the_hashtags();
                get_template_part('template-parts/parts/inline-share');
                get_template_part('template-parts/parts/pagination-nav');

                // If comments are open or we have at least one comment, load up the comment template.
                the_comment_html();

                ?>
            </div>
        </div>
    </div>
</section>