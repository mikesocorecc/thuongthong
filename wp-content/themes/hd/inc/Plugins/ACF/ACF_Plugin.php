<?php

namespace Webhd\Plugins\ACF;

/**
 * ACF Plugins
 * @author   WEBHD
 */
\defined( '\WPINC' ) || die;

use Webhd\Helpers\Cast;

// If plugin - 'ACF' not exist then return.
if ( ! class_exists( '\ACF' ) ) {
	return;
}

if ( ! class_exists( 'ACF_Plugin' ) ) {
	class ACF_Plugin {
		public function __construct() {

			//$this->_optionsPage();

			add_filter( 'acf/fields/wysiwyg/toolbars', [ &$this, 'wysiwyg_toolbars' ] );
			add_filter( 'wp_nav_menu_objects', [ &$this, 'wp_nav_menu_objects' ], 11, 1 );
		}

		// -------------------------------------------------------------

		/**
		 * @return void
		 */
		//private function _optionsPage() {}

		// -------------------------------------------------------------

		/**
		 * @param $items
		 *
		 * @return mixed
		 */
		public function wp_nav_menu_objects( $items ) {
			foreach ( $items as &$item ) {

				$title = $item->title;
				$fields = get_fields( $item );

				if ($fields) {
					$fields = Cast::toObject( $fields );

					if ( $fields->icon_svg ?? false ) {
						$item->classes[] = 'icon-menu';
						$title = $fields->icon_svg . '<span>' . $item->title . '</span>';
					}
					else if ( $fields->icon_image ?? false ) {
						$item->classes[] = 'img-menu';
						$title = '<img width="32px" height="32px" alt src="' . attachment_image_src( $fields->icon_image ) . '" loading="lazy" />' . '<span>' . $item->title . '</span>';
					}

					if ( $fields->label_text ?? false ) {
						$_str = '';
						if ($fields->label_color) $_str .= 'color:' . $fields->label_color . ';';
						if ($fields->label_background) $_str .= 'background-color:' . $fields->label_background . ';';

						$_style = $_str ? ' style="' . $_str . '"' : '';
						$title .= '<sup' . $_style . '>' . $fields->label_text . '</sup>';
					}

					$item->title = $title;
					unset($fields);
				}
			}

			return $items;
		}

		// -------------------------------------------------------------

		/**
		 * @param $toolbars
		 *
		 * @return mixed
		 */
		public function wysiwyg_toolbars( $toolbars ) {
			// Add a new toolbar called "Minimal" - this toolbar has only 1 row of buttons
			$toolbars['Minimal']    = [];
			$toolbars['Minimal'][1] = [
				'formatselect',
				'bold',
				'italic',
				'underline',
				'link',
				'unlink',
				'forecolor',
				//'blockquote'
			];

			// remove the 'Basic' toolbar completely (if you want)
			//unset( $toolbars['Full' ] );
			//unset( $toolbars['Basic' ] );

			// return $toolbars - IMPORTANT!
			return $toolbars;
		}
	}
}