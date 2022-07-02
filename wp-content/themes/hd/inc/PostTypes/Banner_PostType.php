<?php

namespace Webhd\PostTypes;

\defined( '\WPINC' ) || die;

if ( ! class_exists( 'Banner_PostType' ) ) {

	class Banner_PostType {
		public function __construct() {
			add_action( 'init', [ &$this, 'banner_post_type' ], 10 );
			add_action( 'init', [ &$this, 'banner_category' ], 10 );

			$this->_localFields();
		}

		public function banner_post_type() {
			$labels = [
				'name'                  => __( 'Banners', 'hd' ),
				'singular_name'         => __( 'Banner', 'hd' ),
				'menu_name'             => __( 'Banners', 'hd' ),
				'name_admin_bar'        => __( 'Banners', 'hd' ),
				'archives'              => __( 'Item Archives', 'hd' ),
				'attributes'            => __( 'Item Attributes', 'hd' ),
				'parent_item_colon'     => __( 'Parent Item:', 'hd' ),
				'all_items'             => __( 'All Banners', 'hd' ),
				'add_new_item'          => __( 'Add New Item', 'hd' ),
				'add_new'               => __( 'Add New', 'hd' ),
				'new_item'              => __( 'New Item', 'hd' ),
				'edit_item'             => __( 'Edit Item', 'hd' ),
				'update_item'           => __( 'Update Item', 'hd' ),
				'view_item'             => __( 'View Item', 'hd' ),
				'view_items'            => __( 'View Items', 'hd' ),
				'search_items'          => __( 'Search Items', 'hd' ),
				'not_found'             => __( 'Not found', 'hd' ),
				'not_found_in_trash'    => __( 'Not found in Trash', 'hd' ),
				'featured_image'        => __( 'Featured Image', 'hd' ),
				'set_featured_image'    => __( 'Set featured image', 'hd' ),
				'remove_featured_image' => __( 'Remove featured image', 'hd' ),
				'use_featured_image'    => __( 'Use as featured image', 'hd' ),
				'insert_into_item'      => __( 'Insert Item', 'hd' ),
				'uploaded_to_this_item' => __( 'Uploaded to this item', 'hd' ),
				'items_list'            => __( 'Items list', 'hd' ),
				'items_list_navigation' => __( 'Items list navigation', 'hd' ),
				'filter_items_list'     => __( 'Filter items list', 'hd' ),
			];
			$args   = [
				'label'               => __( 'Banners', 'hd' ),
				'description'         => __( 'Post Type Description', 'hd' ),
				'labels'              => $labels,
				'supports'            => [ 'title', 'editor', 'thumbnail', 'page-attributes' ],
				'taxonomies'          => [ 'banner_cat' ],
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 12,
				'menu_icon'           => 'dashicons-format-image',
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'exclude_from_search' => true,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
				'rewrite'             => [ 'slug' => 'banner' ],
				'show_in_rest'        => true,
			];

			register_post_type( 'banner', $args );
		}

		public function banner_category() {
			$labels  = [
				'name'                       => __( 'Banner Categories', 'hd' ),
				'singular_name'              => __( 'Banner Category', 'hd' ),
				'menu_name'                  => __( 'Banner Categories', 'hd' ),
				'all_items'                  => __( 'All Items', 'hd' ),
				'parent_item'                => __( 'Parent Item', 'hd' ),
				'parent_item_colon'          => __( 'Parent Item:', 'hd' ),
				'new_item_name'              => __( 'New Item Name', 'hd' ),
				'add_new_item'               => __( 'Add New Category', 'hd' ),
				'edit_item'                  => __( 'Edit Item', 'hd' ),
				'update_item'                => __( 'Update Item', 'hd' ),
				'view_item'                  => __( 'View Item', 'hd' ),
				'separate_items_with_commas' => __( 'Separate items with commas', 'hd' ),
				'add_or_remove_items'        => __( 'Add or remove items', 'hd' ),
				'choose_from_most_used'      => __( 'Choose from the most used', 'hd' ),
				'popular_items'              => __( 'Popular Items', 'hd' ),
				'search_items'               => __( 'Search Items', 'hd' ),
				'not_found'                  => __( 'Not Found', 'hd' ),
				'no_terms'                   => __( 'No items', 'hd' ),
				'items_list'                 => __( 'Items list', 'hd' ),
				'items_list_navigation'      => __( 'Items list navigation', 'hd' ),
			];
			$rewrite = [
				'slug'         => 'banners',
				'with_front'   => false,
				'hierarchical' => false,
			];
			$args    = [
				'labels'            => $labels,
				'hierarchical'      => true,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_tagcloud'     => false,
				'rewrite'           => $rewrite,
				'show_in_rest'      => true,
			];

			register_taxonomy( 'banner_cat', [ 'banner' ], $args );
		}

		/**
		 * @retun void
		 */
		private function _localFields() {
			if ( ! class_exists( '\ACF' ) || ! function_exists('acf_add_local_field_group') ) {
				return null;
			}

			acf_add_local_field_group( [
				'key'                   => 'group_5facf3b624b9a',
				'title'                 => __( 'Attributes of Banners', 'hd' ),
				'fields'                => [
					[
						'key'               => 'field_5facf4782c703',
						'label'             => __( 'Mobile Image', 'hd' ),
						'name'              => 'responsive_image',
						'type'              => 'image',
						'required'          => 0,
						'conditional_logic' => 0,
						'return_format'     => 'id',
						'preview_size'      => 'medium',
						'library'           => 'all',
						'mime_types'        => 'jpg,jpeg,png,webp',
					],
					[
						'key'               => 'field_616e40d247f4a',
						'label'             => __( 'Gallery', 'hd' ),
						'name'              => 'gallery',
						'type'              => 'gallery',
						'required'          => 0,
						'conditional_logic' => 0,
						'return_format'     => 'id',
						'preview_size'      => 'medium',
						'insert'            => 'append',
						'library'           => 'all',
						'mime_types'        => 'jpg,jpeg,png,webp',
					],
					[
						'key'               => 'field_5facf4c12c704',
						'label'             => __( 'Title extended', 'hd' ),
						'name'              => 'html_title',
						'type'              => 'wysiwyg',
						'required'          => 0,
						'conditional_logic' => 0,
						'default_value'     => '',
						'tabs'              => 'text',
						'media_upload'      => 0,
						'toolbar'           => 'minimal',
						'delay'             => 0,
					],
					[
						'key'               => 'field_609f98df40746',
						'label'             => __( 'Subtitle', 'hd' ),
						'name'              => 'sub_title',
						'type'              => 'text',
						'instructions'      => __( 'The subtitle is not prominent by default. However, some themes may show it.', 'hd' ),
						'required'          => 0,
						'conditional_logic' => 0,/* [
							[
								[
									'field'    => 'field_5facf4c12c704',
									'operator' => '!=empty',
								],
							],
						],*/
						'default_value'     => '',
						'placeholder'       => '',
					],
					[
						'key'               => 'field_5facf50c2c705',
						'label'             => __( 'Summary', 'hd' ),
						'name'              => 'html_desc',
						'type'              => 'wysiwyg',
						'required'          => 0,
						'conditional_logic' => 0,
						'default_value'     => '',
						'tabs'              => 'all',
						'toolbar'           => 'minimal',
						'media_upload'      => 0,
						'delay'             => 0,
					],
					[
						'key'               => 'field_5facf5582c706',
						'label'             => __( 'URL', 'hd' ),
						'name'              => 'url',
						'type'              => 'text',
						'required'          => 0,
						'conditional_logic' => 0,
						'default_value'     => '',
						'placeholder'       => 'https://dummy',
					],
					[
						'key'               => 'field_5fc4a0ab7bb9a',
						'label'             => __( 'Video link', 'hd' ),
						'name'              => 'video_link',
						'type'              => 'true_false',
						'required'          => 0,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_5facf5582c706',
									'operator' => '!=empty',
								],
							],
						],
						'default_value'     => 0,
						'ui'                => 1,
					],
					[
						'key'               => 'field_5fade4bbf66e0',
						'label'             => __( 'Button name', 'hd' ),
						'name'              => 'button_name',
						'type'              => 'text',
						'required'          => 0,
						'conditional_logic' => [
							[
								[
									'field'    => 'field_5facf5582c706',
									'operator' => '!=empty',
								],
							],
						],
					],
				],
				'location'              => [
					[
						[
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'banner',
						],
					],
				],
				'menu_order'            => 0,
				'instruction_placement' => 'field',
				'active'                => true,
				'show_in_rest'          => 1,
			] );
		}
	}
}