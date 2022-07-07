<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $upsells ) :
    ?>
	<section class="up-sells products upsells upsell-products">
		<?php
		$heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) );

		if ( $heading ) :
			?>
            <h5 class="heading-title"><?php echo esc_html( $heading ); ?></h5>
		<?php endif; ?>
        <div class="list-wrapper list-product-wrapper carousel-wrapper carousel-related-wrapper">
            <div class="owl-carousel owl-theme">
				<?php
				foreach ( $upsells as $upsell ) :

					echo '<div class="item--inner">';
					$post_object = get_post( $upsell->get_id() );
					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
					wc_get_template_part( 'content', 'product' );
					echo '</div>';

				endforeach;
				?>
            </div>
        </div>
	</section>
	<?php
endif;

wp_reset_postdata();
