<?php

/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;

// header
get_header('shop');

$term = get_queried_object();

$is_sidebar = FALSE;
if (is_active_sidebar('w-product-sidebar') && !is_search()) :
    $is_sidebar = TRUE;
endif;

$term_title = $term->name;
if (is_search()) $term_title = get_search_query(FALSE);

the_page_title_theme();

/**
 * Hook: woocommerce_before_main_content.
 *
 * @see woocommerce_breadcrumb - 20 - removed
 * @see WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

if (is_shop() && is_active_sidebar('w-shop-sidebar')) :
    wc_get_template_part('shop', 'product');
else :

?>
<section class="section archive-products">
    <div class="grid-container width-extra">
        <?php
        /**
         * Hook: woocommerce_archive_description.
         *
         * @see woocommerce_taxonomy_archive_description - 10
         * @see woocommerce_product_archive_description - 10
         */
        do_action('woocommerce_archive_description');
        ?>
        <div class="grid-x grid-padding-x">
            <?php if (TRUE === $is_sidebar) : ?>
                <div class="cell sidebar-col medium-12 large-3">
                    <div class="sidebar--wrap">
                        <?php dynamic_sidebar('w-product-sidebar'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="archive-products-inner cell<?php if (TRUE === $is_sidebar) echo ' medium-12 large-9'; ?>">
                <?php
                if (woocommerce_product_loop()) :

                    /**
                     * Hook: woocommerce_before_shop_loop.
                     *
                     * @see woocommerce_output_all_notices - 8
                     * @see woocommerce_result_count - 20
                     * @see woocommerce_catalog_ordering - 30
                     */
                    do_action('woocommerce_before_shop_loop');
                ?>
                    <div class="grid-products grid-x<?php if (is_search()) echo ' main-content-search'; ?>">
                        <?php
                        if (wc_get_loop_prop('total')) :
                            // Start the Loop.
                            while (have_posts()) : the_post();

                                echo '<div class="cell">';
                                wc_get_template_part('content', 'product');
                                echo '</div>';
                            // End the loop.
                            endwhile;
                        endif;
                        wp_reset_postdata();

                        ?>
                    </div>
                <?php
                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @see woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');
                else :
                    // @see wc_no_products_found - 10
                    do_action('woocommerce_no_products_found');
                endif;

                ?>
            </div>
        </div>
    </div>
</section>
<?php endif;

/**
 * Hook: woocommerce_after_main_content.
 *
 */
do_action('woocommerce_after_main_content');

//... footer
get_footer('shop');