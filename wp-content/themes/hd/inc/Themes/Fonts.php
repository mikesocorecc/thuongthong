<?php

namespace Webhd\Themes;

\defined( '\WPINC' ) || die;

/**
 * Fonts Class
 * @author   WEBHD
 */
if ( ! class_exists( 'Fonts' ) ) {
	class Fonts {
		public function __construct() {
            add_action('wp_head', [ &$this, '__preconnect'], 2);
			add_action( 'wp_enqueue_scripts', [ &$this, 'enqueue_scripts' ], 99 );
		}

        /** ---------------------------------------- */

        /**
         * @return void
         */
        public function __preconnect() {
            echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
            echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
        }

		/** ---------------------------------------- */

		public function enqueue_scripts() {
			wp_enqueue_style("fonts-style", get_template_directory_uri() . '/assets/css/fonts.css', [], W_THEME_VERSION);
            wp_enqueue_style( "montserrat-font", 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap', [] );
            wp_enqueue_style( "cg-font", 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500&display=swap', [] );
            wp_enqueue_style( "norican-font", 'https://fonts.googleapis.com/css2?family=Norican&display=swap', [] );

			//wp_register_script("fontawesome-kit", "https://kit.fontawesome.com/870d5b0bdf.js", [], false, true);
			//wp_script_add_data("fontawesome-kit", "defer", true);
			//wp_enqueue_script('fontawesome-kit');
		}
	}
}
