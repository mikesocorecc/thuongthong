<?php

namespace Webhd\Plugins\Woocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

// If plugin - 'Woocommerce' not exist then return.
if ( ! class_exists( '\WooCommerce' ) ) {
	return;
}

require __DIR__ . '/woocommerce.php';

if ( ! class_exists( 'Woocommerce_Plugin' ) ) {
	class Woocommerce_Plugin {
		public function __construct() {

			add_action( 'after_setup_theme', [ &$this, 'woocommerce_setup' ], 31 );
			add_action( 'woocommerce_share', [ &$this, 'woocommerce_share' ], 10 );
			add_action( 'wp_enqueue_scripts', [ &$this, 'enqueue_scripts' ], 100 );

			add_action('woocommerce_before_shop_loop', [&$this, 'begin_wrapper'], 7);
			add_action('woocommerce_before_shop_loop', [&$this, 'end_wrapper'], 31);
			add_action('woocommerce_email', [&$this, 'woocommerce_email_hooks'], 100, 1);

			// Show only lowest prices in WooCommerce variable products
			add_filter( 'woocommerce_variable_sale_price_html', [ &$this, 'variation_price_format' ], 10, 2 );
			add_filter( 'woocommerce_variable_price_html', [ &$this, 'variation_price_format' ], 10, 2 );

			add_filter( 'body_class', [ &$this, 'woocommerce_body_class' ] );

            //
            // remove header woo
            //
            // https://stackoverflow.com/questions/57321805/remove-header-from-the-woocommerce-administrator-panel
            add_action( 'admin_head', function (){
                remove_action( 'in_admin_header', array( 'Automattic\WooCommerce\Internal\Admin\Loader', 'embed_page_header' ) );
                remove_action( 'in_admin_header', array( 'Automattic\WooCommerce\Admin\Loader', 'embed_page_header' ) );

                echo '<style>#wpadminbar + #wpbody { margin-top: 0 !important; }</style>';
            });

            // -------------------------------------------------------------

            add_filter('woocommerce_product_single_add_to_cart_text', function () {
                return __('Thêm vào giỏ hàng', 'hd');
            });

            add_filter('woocommerce_product_add_to_cart_text', function () {
                return __('Thêm vào giỏ', 'hd');
            });

            // ---------------------------------------------------------------

            add_action('wp', [&$this, 'visited_product_cookie']);
		}

		/** ---------------------------------------- */
		/** ---------------------------------------- */
        /**
         * @return void
         */
        public function visited_product_cookie()
        {
            if (!is_singular('product')) {
                return;
            }

            global $post;

            if (empty($_COOKIE['woocommerce_recently_viewed'])) {
                $viewed_products = [];
            } else {
                $viewed_products = wp_parse_id_list((array) explode('|', wp_unslash($_COOKIE['woocommerce_recently_viewed'])));
            }

            $keys = array_flip($viewed_products);

            if (isset($keys[$post->ID])) {
                unset($viewed_products[$keys[$post->ID]]);
            }

            $viewed_products[] = $post->ID;

            if (count($viewed_products) > 22) {
                array_shift($viewed_products);
            }

            wc_setcookie('woocommerce_recently_viewed', implode('|', $viewed_products));
        }

		/**
		 * @param $mailer
		 */
		public function woocommerce_email_hooks($mailer)
		{
			add_action('woocommerce_order_status_pending_to_on-hold_notification', array(
				$mailer->emails['WC_Email_Customer_On_Hold_Order'],
				'trigger'
			));
		}

		/** ---------------------------------------- */
		/** ---------------------------------------- */

		public function begin_wrapper()
		{
			echo '<div class="woocommerce-sorting">';
		}

		public function end_wrapper()
		{
			echo '</div>';
		}

		/** ---------------------------------------- */
		/** ---------------------------------------- */

		/**
		 * @param $price
		 * @param $product
		 *
		 * @return string
		 */
		public function variation_price_format( $price, $product ) {

			// Main Price
			$prices = [ $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) ];
			$price  = $prices[0] !== $prices[1] ? sprintf( __( 'From: %1$s', 'hd' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

			// Sale Price
			$prices = [
				$product->get_variation_regular_price( 'min', true ),
				$product->get_variation_regular_price( 'max', true )
			];
			sort( $prices );
			$saleprice = $prices[0] !== $prices[1] ? sprintf( __( 'From: %1$s', 'hd' ), wc_price( $prices[0] ) ) : wc_price( $prices[0] );

			if ( $price !== $saleprice ) {
				$price = '<del>' . $saleprice . $product->get_price_suffix() . '</del> <ins>' . $price . $product->get_price_suffix() . '</ins>';
			}

			return $price;
		}

		/** ---------------------------------------- */
		/** ---------------------------------------- */

		/**
		 * Add 'woocommerce-active' class to the body tag
		 *
		 * @param array $classes css classes applied to the body tag.
		 *
		 * @return array $classes modified to include 'woocommerce-active' class
		 */
		public function woocommerce_body_class( $classes ) {
			$classes[] = 'wc-active';

			return $classes;
		}


		/** ---------------------------------------- */
		/** ---------------------------------------- */

		/**
		 * woocommerce_share action
		 * @return void
		 */
		public function woocommerce_share() {
			get_template_part( 'template-parts/parts/sharing' );
		}

		/** ---------------------------------------- */
		/** ---------------------------------------- */

		/**
		 * Woocommerce setup
		 *
		 * @return void
		 */
		public function woocommerce_setup() {

            add_theme_support('woocommerce');

			// Enabling WooCommerce product gallery features (are off by default since WC 3.0.0).
			// Add support for WC features.
			add_theme_support( 'wc-product-gallery-zoom' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );

			// Remove default WooCommerce wrappers.
			remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper' );
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end' );
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar' );

			remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);

			/** */

			// Declare WooCommerce support.
			add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function ( $size ) {
				return [
					'width'  => 150,
					'height' => 150,
					'crop'   => 0,
				];
			} );

			// Trim zeros in price decimals
			add_filter( 'woocommerce_price_trim_zeros', '__return_true' );

			/**
			 * @param array $args
			 *
			 * @return array
			 */
			add_filter( 'woocommerce_product_tag_cloud_widget_args', function ( array $args ) {
				$args['smallest'] = '11';
				$args['largest']  = '19';
				$args['unit']     = 'px';
				$args['number']   = 12;

				return $args;
			} );

			/**
			 * @param array $args
			 * @return array
			 */
			add_filter('woocommerce_output_related_products_args', function (array $args) {
				$args['columns'] = 4;
				$args['posts_per_page'] = 12;

				return $args;
			}, 20, 1);

			/**
			 * @param $orders
			 *
			 * @return array
			 */
			add_filter('woocommerce_catalog_orderby', function ($orders) {
				$orders = array(
					'menu_order' => __('Ordering', 'hd'),
					'popularity' => __('Popularity', 'hd'),
					'rating' => __('Average rating', 'hd'),
					'date' => __('Latest', 'hd'),
					'price' => __('Price: low to high', 'hd'),
					'price-desc' => __('Price: high to low', 'hd'),
				);

				return $orders;
			}, 100, 1);

			/**
			 * @param $defaults
			 *
			 * @return array
			 */
			add_filter( 'woocommerce_breadcrumb_defaults', function ($defaults) {
				$defaults = [
					'delimiter'   => '',
					'wrap_before' => '<ol id="crumbs" class="breadcrumbs" aria-label="breadcrumbs">',
					'wrap_after'  => '</ol>',
					'before'      => '<li><span property="itemListElement" typeof="ListItem">',
					'after'       => '</span></li>',
					'home'        => _x( 'Home', 'breadcrumb', 'hd' ),
				];

				return $defaults;
			}, 11, 1 );
		}

		/** ---------------------------------------- */
		/** ---------------------------------------- */

		/**
		 * @return void
		 */
		public function enqueue_scripts() {

			$gutenberg_widgets_off = get_theme_mod_ssl( 'gutenberg_use_widgets_block_editor_setting' );
			$gutenberg_off         = get_theme_mod_ssl( 'use_block_editor_for_post_type_setting' );
			if ( $gutenberg_widgets_off && $gutenberg_off ) {

				// Remove WooCommerce block CSS
				wp_deregister_style( 'wc-blocks-vendors-style' );
				wp_deregister_style( 'wc-block-style' );
			}
		}
	}
}