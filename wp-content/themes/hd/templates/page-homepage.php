<?php

/**
 * The template for displaying homepage
 * Template Name: Homepage
 * Template Post Type: page
 */

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Str;

get_header();

if (have_posts()) the_post();
if (post_password_required()) :
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
endif;

global $post;
$content = $post->post_content;
if (Str::stripSpace($content)) {
    echo '<section class="section homepage-section"><div class="grid-container">';
    echo '<div class="content clearfix">';

    // post content
    the_content();
    echo '</div></div>';
    echo '</section>';
}

// homepage widget
if (is_active_sidebar('w-homepage-sidebar')) :
    dynamic_sidebar('w-homepage-sidebar');
endif;

get_footer();