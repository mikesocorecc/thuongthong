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

if ( ! class_exists( 'Acf_Plugin' ) ) {
	class Acf_Plugin {
		public function __construct() {

            $this->_optionsPage();

            $this->_fieldPosts();
            $this->_fieldTerms();
            $this->_fieldMenus();

			add_filter( 'acf/fields/wysiwyg/toolbars', [ &$this, 'wysiwyg_toolbars' ] );
			add_filter( 'wp_nav_menu_objects', [ &$this, 'wp_nav_menu_objects' ], 11, 2 );
		}

		// -------------------------------------------------------------

		/**
		 * @return void
		 */
		private function _optionsPage() {}

        // -------------------------------------------------------------

        /**
         * @return void
         */
        private function _fieldPosts() {

            //--------------------------------------
            // Thêm danh sách gợi ý cho bài viết
            //--------------------------------------
            acf_add_local_field_group( [
                'key'                   => 'group_5fd0534041759',
                'title'                 => __( 'Related Posts', 'hd' ),
                'fields'                => [
                    [
                        'key'           => 'field_5fd07b44abc49',
                        'label'         => __( 'Suggested articles', 'hd' ),
                        'name'          => 'up_seo',
                        'type'          => 'post_object',
                        'required'      => 0,
                        'post_type'     => [
                            0 => 'post',
                            1 => 'page',
                        ],
                        'taxonomy'      => '',
                        'allow_null'    => 1,
                        'multiple'      => 1,
                        'return_format' => 'id',
                        'ui'            => 1,
                    ],
                ],
                'location'              => [
                    [
                        [
                            'param'    => 'post_type',
                            'operator' => '==',
                            'value'    => 'post',
                        ],
                    ],
                ],
                'menu_order'            => 0,
                'instruction_placement' => 'field',
                'active'                => true,
                'show_in_rest'          => 1,
            ] );

            //--------------------------------------
            // Thêm bình luận cho bài viết
            //--------------------------------------
            acf_add_local_field_group( [
                'key'                   => 'group_5ff28b5f3f716',
                'title'                 => __( 'Other Discussions', 'hd' ),
                'fields'                => [
                    [
                        'key'               => 'field_5ff28d38db57e',
                        'label'             => __( 'Facebook Comment', 'hd' ),
                        'name'              => 'facebook_comment',
                        'type'              => 'true_false',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'message'           => __( 'Enable comments on Facebook', 'hd' ),
                        'default_value'     => 0,
                        'ui'                => 0,
                    ],
                    [
                        'key'               => 'field_608a71db60706',
                        'label'             => __( 'Zalo Comment', 'hd' ),
                        'name'              => 'zalo_comment',
                        'type'              => 'true_false',
                        'required'          => 0,
                        'conditional_logic' => 0,
                        'message'           => __( 'Enable comments on Zalo', 'hd' ),
                        'default_value'     => 0,
                        'ui'                => 0,
                    ],
                ],
                'location'              => [
//					[
//						[
//							'param'    => 'post_type',
//							'operator' => '==',
//							'value'    => 'page',
//						],
//					],
                    [
                        [
                            'param'    => 'post_type',
                            'operator' => '==',
                            'value'    => 'post',
                        ],
                    ],
                ],
                'menu_order'            => 0,
                'instruction_placement' => 'field',
                'active'                => true,
                'show_in_rest'          => 1,
            ] );
        }

        // -------------------------------------------------------------

        /**
         * @return void
         */
        private function _fieldTerms() {

            //--------------------------------------
            // Thứ tự và ảnh đại diện chuyên mục
            //--------------------------------------
            acf_add_local_field_group( [
                'key'                   => 'group_5fdc116006846',
                //'title'        => __( 'Attributes', 'hd' ),
                'fields'                => [
                    [
                        'key'           => 'field_5fdc11a7a7c56',
                        'label'         => __( 'Thumbnail', 'hd' ),
                        'name'          => 'term_thumb',
                        'type'          => 'image',
                        'required'      => 0,
                        'return_format' => 'id',
                        'preview_size'  => 'thumbnail',
                        'library'       => 'all',
                    ],
                    [
                        'key'           => 'field_6194ac46987f7',
                        'label'         => __( 'Menu order', 'hd' ),
                        'name'          => 'term_order',
                        'type'          => 'number',
                        'required'      => 0,
                        'default_value' => 0,
                        'min'           => '',
                        'max'           => '',
                    ],
                ],
                'location'              => [
                    [
                        [
                            'param'    => 'taxonomy',
                            'operator' => '==',
                            'value'    => 'category',
                        ],
                    ],
                    [
                        [
                            'param'    => 'taxonomy',
                            'operator' => '==',
                            'value'    => 'banner_cat',
                        ],
                    ],
                ],
                'menu_order'            => 0,
                'instruction_placement' => 'field',
                'active'                => true,
                'show_in_rest'          => 1,
            ] );

            //--------------------------------------
            // Kiểu hiển thị chuyên mục
            //--------------------------------------
            acf_add_local_field_group( [
                'key'                   => 'group_619f4ab590bc9',
                //'title' => __('Kiểu hiển thị', 'hd),
                'fields'                => [
                    [
                        'key'           => 'field_619f4ad9072ab',
                        'label'         => __( 'Display types', 'hd' ),
                        'name'          => 'display_type',
                        'type'          => 'select',
                        'required'      => 0,
                        'choices'       => [
                            'items'         => __( 'Post', 'hd' ),
                            'subcategories' => __( 'Sub-category', 'hd' ),
                            'both'          => __( 'Both', 'hd' ),
                        ],
                        'default_value' => 'items',
                        'allow_null'    => 0,
                        'multiple'      => 0,
                        'ui'            => 0,
                        'return_format' => 'value',
                        'ajax'          => 0,
                    ],
                ],
                'location'              => [
                    [
                        [
                            'param'    => 'taxonomy',
                            'operator' => '==',
                            'value'    => 'category',
                        ],
                    ],
                ],
                'menu_order'            => 1,
                'instruction_placement' => 'field',
                'active'                => true,
                'show_in_rest'          => 1,
            ] );
        }

        // -------------------------------------------------------------

        /**
         * @return void
         */
        private function _fieldMenus() {

            //--------------------------------------
            // Thêm icon, ảnh, nhãn... cho menus
            //--------------------------------------
            acf_add_local_field_group( [
                'key'                   => 'group_618e3f6a09e18',
                //'title' => __( 'Menu item attributes', 'hd' ),
                'fields'                => [
                    [
                        'key'               => 'field_618e40398f73e',
                        'label'             => __( 'Icon Image', 'hd' ),
                        'name'              => 'icon_image',
                        'type'              => 'image',
                        'required'          => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_618e3f855921f',
                                    'operator' => '==empty',
                                ],
                            ],
                        ],
                        'return_format'     => 'id',
                        'preview_size'      => 'thumbnail',
                        'library'           => 'all',
                    ],
                    [
                        'key'               => 'field_618e3f855921f',
                        'label'             => __( 'SVG, Web Component, Icon Font', 'hd' ),
                        'name'              => 'icon_svg',
                        'type'              => 'textarea',
                        'required'          => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_618e40398f73e',
                                    'operator' => '==empty',
                                ],
                            ],
                        ],
                        'rows'              => 2,
                    ],
                    [
                        'key'               => 'field_618e41946bf9f',
                        'label'             => __( 'Label', 'hd' ),
                        'name'              => 'label_text',
                        'type'              => 'text',
                        'instructions'      => __( '"New", "Hot", "Featured" ... là nhãn gắn sau tiêu đề', 'hd' ),
                        'required'          => 0,
                        'conditional_logic' => 0,
                    ],
                    [
                        'key'               => 'field_618e612039aba',
                        'label'             => __( 'Label Color', 'hd' ),
                        'name'              => 'label_color',
                        'type'              => 'color_picker',
                        'required'          => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_618e41946bf9f',
                                    'operator' => '!=empty',
                                ],
                            ],
                        ],
                        'enable_opacity'    => 1,
                        'return_format'     => 'string',
                    ],
                    [
                        'key'               => 'field_618e616c351a7',
                        'label'             => __( 'Label Background', 'hd' ),
                        'name'              => 'label_background',
                        'type'              => 'color_picker',
                        'required'          => 0,
                        'conditional_logic' => [
                            [
                                [
                                    'field'    => 'field_618e41946bf9f',
                                    'operator' => '!=empty',
                                ],
                            ],
                        ],
                        'enable_opacity'    => 1,
                        'return_format'     => 'string',
                    ],
                ],
                'location'              => [
                    [
                        [
                            'param'    => 'nav_menu_item',
                            'operator' => '==',
                            'value'    => 'all',
                        ],
                    ],
                ],
                'menu_order'            => 2,
                'instruction_placement' => 'field',
                'active'                => true,
                'show_in_rest'          => 1,
            ] );
        }

		// -------------------------------------------------------------

        /**
         * @param $items
         * @param $args
         * @return mixed
         */
		public function wp_nav_menu_objects( $items, $args ) {
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