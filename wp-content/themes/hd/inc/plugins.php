<?php
/**
 * Plugins functions
 *
 * @author   WEBHD
 */
\defined( '\WPINC' ) || die;

use Webhd\Plugins\ACF\Acf_Plugin;
use Webhd\Plugins\Cf7_Plugin;
use Webhd\Plugins\RankMath_Plugin;
use Webhd\Plugins\LiteSpeed_Plugin;
use Webhd\Plugins\Elementor_Plugin;
use Webhd\Plugins\Woocommerce\Woocommerce_Plugin;

class_exists( '\ACF' ) && class_exists( Acf_Plugin::class ) && ( new Acf_Plugin );
class_exists( '\WPCF7' ) && class_exists( Cf7_Plugin::class ) && ( new Cf7_Plugin );
class_exists( '\RankMath' ) && class_exists( RankMath_Plugin::class ) && ( new RankMath_Plugin );
class_exists( '\Elementor\Plugin' ) && class_exists( Elementor_Plugin::class ) && ( new Elementor_Plugin );
class_exists( '\WooCommerce' ) && class_exists( Woocommerce_Plugin::class ) && ( new Woocommerce_Plugin );

defined( 'LSCWP_BASENAME' ) && class_exists( LiteSpeed_Plugin::class ) && ( new LiteSpeed_Plugin );