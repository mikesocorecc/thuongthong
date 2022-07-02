<?php

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Str;

$post_page_id = get_option( 'page_for_posts' );
$term = get_queried_object();

if ( $post_page_id && $post_page_id == $term->ID ) { // is posts page
	$desc = post_excerpt( $term, null );
} else {
	$desc = term_excerpt( $term, null );
}

// template-parts/parts/page-title.php
the_page_title_theme();

$archive_title = $term->name;
if (is_search()) {
    $archive_title = sprintf( __( 'Search Results for: %s', 'hd' ), get_search_query() );
}

?>
<section class="section archives archive-posts">
    <div class="grid-container">
		<?php //get_template_part( 'template-parts/posts/grid' ); ?>
        <?php
        $_class = '';
        if (is_active_sidebar('w-newsbar-sidebar'))
            $_class = ' l-7 has-sidebar';
        ?>
        <div class="grid-x grid-padding-x">
            <div class="cell<?=$_class?>">
                <div class="section posts-section vertical-posts">
                    <h2 class="heading-title h5"><?=$archive_title?></h2>
                    <?php if ( Str::stripSpace( $desc ) ) : ?>
                    <div class="archive-desc heading-desc"><?= $desc ?></div>
                    <?php endif; ?>
                    <?php get_template_part( 'template-parts/posts/list' ); ?>
                </div>
            </div>
            <?php
            // sidebar
            if (is_active_sidebar('w-newsbar-sidebar')) :
                echo '<div class="cell auto"><div class="sidebar-container">';
                dynamic_sidebar('w-newsbar-sidebar');
                echo '</div></div>';
            endif;
            ?>
        </div>
    </div>
</section>