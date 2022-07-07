<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;
global $product;

// Ensure visibility.
if (empty($product) || false === wc_get_loop_product_visibility($product->get_id()) || !$product->is_visible()) {
    return;
}
?>
<article <?php wc_product_class('item', $product); ?>>
    <?php
    $link = apply_filters('woocommerce_loop_product_link', get_the_permalink(), $product);
    $thumbnail = woocommerce_get_product_thumbnail();

    $ratio = get_theme_mod_ssl('product_menu_setting');
    $ratio_class = $ratio;
    if ($ratio == 'default' || empty($ratio))
        $ratio_class = '1v1';

    ?>
    <a class="d-block" href="<?= get_permalink() ?>" aria-label="<?php the_title_attribute() ?>" tabindex="0">
        <div class="cover">
            <span class="after-overlay res auto scale ratio-<?= $ratio_class ?>"><?php echo get_the_post_thumbnail(null, 'medium'); ?></span>
        </div>
    </a>
    <div class="cover-content">
        <?php
        // loop/rating.php
        woocommerce_template_loop_rating();
        ?>
        <h6><a title="<?php the_title_attribute() ?>" href="<?= $link ?>"><?php the_title(); ?></a></h6>
        <?php
        if ($product->get_price()) :
            // loop/price.php
            woocommerce_template_loop_price();
            // loop/sale-flash.php
            woocommerce_show_product_loop_sale_flash();
        ?>
            <div class="product-button">
                <?php
                if ( $product->managing_stock() || $product->is_in_stock() ) {
                    // loop/add-to-cart.php
                    woocommerce_template_loop_add_to_cart();
                }
                else {
                    ?>
                    <span class="out-of-stock"><?php echo __('Out of stock', 'hd');?></span>
                <?php
                }
                ?>
            </div>
        <?php
        else :
        ?>
            <div class="noprice"><a class="contact-for-price" title="<?php echo __('Contact', 'hd'); ?>" href="<?= $link ?>" data-id="<?php echo $post->ID; ?>" data-title="<?php the_title_attribute() ?>" data-url="<?php echo $link; ?>"><?php echo __('Contact', 'hd'); ?></a></div>
        <?php endif; ?>
    </div>
</article>