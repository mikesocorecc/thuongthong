<?php

if (!defined('ABSPATH')) {
	exit;
}

// ----------------------------------------

if (!function_exists('woo_cart_available')) {
	/**
	 * Validates whether the Woo Cart instance is available in the request
	 *
	 * @return bool
	 * @since 2.6.0
	 */
	function woo_cart_available()
	{
		$woo = WC();
		return $woo instanceof \WooCommerce && $woo->cart instanceof \WC_Cart;
	}
}

// ----------------------------------------

if (!function_exists('woo_cart_link_fragment')) {

	add_filter( 'woocommerce_add_to_cart_fragments', 'woo_cart_link_fragment', 11, 1 );

	/**
	 * Cart Fragments
	 * Ensure cart contents update when products are added to the cart via AJAX
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array            Fragments to refresh via AJAX
	 */
	function woo_cart_link_fragment($fragments)
	{
		global $woocommerce;

		ob_start();
		woo_cart_link();
		$fragments['a.header-cart-contents'] = ob_get_clean();

		ob_start();
		woo_handheld_footer_bar_cart_link();
		$fragments['a.footer-cart-contents'] = ob_get_clean();

		return $fragments;
	}
}

// ----------------------------------------

if (!function_exists('woo_handheld_footer_bar_cart_link')) {
	/**
	 * The cart callback function for the handheld footer bar
	 *
	 * @since 2.0.0
	 */
	function woo_handheld_footer_bar_cart_link()
	{
		if (!woo_cart_available()) {
			return;
		}
		?>
		<a class="footer-cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your shopping cart', 'hd'); ?>">
			<span class="count"><?php echo wp_kses_data(WC()->cart->get_cart_contents_count()); ?></span>
		</a>
		<?php
	}
}

// ----------------------------------------

if (!function_exists('woo_cart_link')) {

	/**
	 * Cart Link
	 * Displayed a link to the cart including the number of items present and the cart total
	 * @return void
	 * @since  1.0.0
	 */
	function woo_cart_link()
	{
		if (!woo_cart_available()) {
			return;
		}
		?>
		<a class="header-cart-contents" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php echo esc_attr__('View your shopping cart', 'hd'); ?>">
			<?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?>
			<span class="icon" data-glyph=""></span>
			<span class="count"><?php echo wp_kses_data(sprintf('%d', WC()->cart->get_cart_contents_count())); ?></span>
		</a>
		<?php
	}
}

// -------------------------------------------------------------

if (!function_exists('woo_header_cart')) {
	/**
	 * Display Header Cart
	 *
	 * @return void
	 * @uses  woocommerce_activated() check if WooCommerce is activated
	 * @since  1.0.0
	 */
	function woo_header_cart()
	{
		if (class_exists( '\WooCommerce' )) {
			if (is_cart()) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
			?>
			<ul id="site-header-cart" class="site-header-cart menu">
				<li class="<?php echo esc_attr($class); ?>">
					<?php woo_cart_link(); ?>
				</li>
				<li class="widget-menu-item">
					<?php the_widget('WC_Widget_Cart', 'title='); ?>
				</li>
			</ul>
			<?php
		}
	}
}

// -------------------------------------------------------------

if (!function_exists('woo_get_gallery_image_html')) {
	/**
	 * @param      $attachment_id
	 * @param bool $main_image
	 *
	 * @return string
	 */
	function woo_get_gallery_image_html($attachment_id, $main_image = false)
	{
		$flexslider = (bool)apply_filters(
			'woocommerce_single_product_flexslider_enabled',
			get_theme_support('wc-product-gallery-slider')
		);
		$gallery_thumbnail = wc_get_image_size('gallery_thumbnail');
		$thumbnail_size = apply_filters('woocommerce_gallery_thumbnail_size', array(
			$gallery_thumbnail['width'],
			$gallery_thumbnail['height']
		));

		$image_size = apply_filters(
			'woocommerce_gallery_image_size',
			$flexslider || $main_image ? 'woocommerce_single' : $thumbnail_size
		);
		$full_size = apply_filters(
			'woocommerce_gallery_full_size',
			apply_filters('woocommerce_product_thumbnails_large_size', 'full')
		);
		$thumbnail_src = wp_get_attachment_image_src($attachment_id, $thumbnail_size);
		$full_src = wp_get_attachment_image_src($attachment_id, $full_size);
		$alt_text = trim(wp_strip_all_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true)));
		$image = wp_get_attachment_image(
			$attachment_id,
			$image_size,
			false,
			apply_filters(
				'woocommerce_gallery_image_html_attachment_image_params',
				array(
					'title' => _wp_specialchars(
						get_post_field('post_title', $attachment_id),
						ENT_QUOTES,
						'UTF-8',
						true
					),
					'data-caption' => _wp_specialchars(
						get_post_field('post_excerpt', $attachment_id),
						ENT_QUOTES,
						'UTF-8',
						true
					),
					'data-src' => esc_url($full_src[0]),
					'data-large_image' => esc_url($full_src[0]),
					'data-large_image_width' => esc_attr($full_src[1]),
					'data-large_image_height' => esc_attr($full_src[2]),
					'class' => esc_attr($main_image ? 'wp-post-image' : ''),
				),
				$attachment_id,
				$image_size,
				$main_image
			)
		);
		$ratio = get_theme_mod_ssl('product_menu_setting');
		$ratio_class = $ratio;
		if ($ratio == 'default' || empty($ratio)) {
			$ratio_class = '1v1';
		}

		return '<div data-thumb="' . esc_url($thumbnail_src[0]) . '" data-thumb-alt="' . esc_attr($alt_text) . '" class="woocommerce-product-gallery__image"><a class="res ratio-' . $ratio_class . '" href="' . esc_url($full_src[0]) . '">' . $image . '</a></div>';
	}
}

// -------------------------------------------------------------

add_filter('woocommerce_product_single_add_to_cart_text', function () {
	return __('Thêm vào giỏ hàng', 'hd');
});


add_filter('woocommerce_product_add_to_cart_text', function () {
	return __('Thêm vào giỏ', 'hd');
});