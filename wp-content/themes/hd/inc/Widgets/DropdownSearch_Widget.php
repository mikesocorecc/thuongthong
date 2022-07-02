<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Url;

if (!class_exists('DropdownSearch_Widget')) {
	class DropdownSearch_Widget extends Widget
	{
		/**
		 * {@inheritdoc}
		 */
		protected function widgetName()
		{
			return __('W - Dropdown Search', 'hd' );
		}

		/**
		 * {@inheritdoc}
		 */
		protected function widgetDescription()
		{
			return __('Dropdown Search', 'hd' );
		}

		/**
		 * Creating widget front-end
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget($args, $instance)
		{
			/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            $title = $instance['title'] ?: '';

			$search_kind = strip_tags($instance['search_kind']);
			$css_class      = isset($instance['css_class']) ? trim(strip_tags($instance['css_class'])) : '';

			// class
			$_class = $this->id;
			if ($css_class) {
				$_class = $_class . ' ' . $css_class;
			}

			$_unique_id = esc_attr(uniqid('search-form-'));
			$title = __('Search', 'hd' );
			$attr_title = esc_attr($title);
			$placeholder_title = esc_attr(__('Search ...', 'hd' ));
			$close_title = __('Close', 'hd' );

            ?>
			<div class="search-dropdown--wrap <?php echo $_class; ?>">
				<a class="trigger-s" title="<?php echo $attr_title; ?>" href="javascript:;" data-toggle="dropdown-<?= $_unique_id ?>" data-glyph="">
					<span><?php echo $title; ?></span>
				</a>
				<div role="search" class="dropdown-pane" id="dropdown-<?= $_unique_id ?>" data-dropdown data-auto-focus="true">
					<form role="form" action="<?php echo Url::home(); ?>" method="get" class="frm-search" method="get" accept-charset="UTF-8" data-abide novalidate>
						<div class="grid-container">
							<div class="frm-container">
								<input id="<?php echo $_unique_id; ?>" required pattern="^(.*\S+.*)$" type="search" name="s" value="<?php echo get_search_query(); ?>" placeholder="<?php echo $placeholder_title; ?>" title>
								<button class="btn-s" type="submit" data-glyph="">
									<span><?php echo $title; ?></span>
								</button>
								<button class="trigger-s-close" type="button" data-glyph="">
									<span><?php echo $close_title; ?></span>
								</button>
							</div>
						</div>
						<?php if ('product' == $search_kind) : ?>
                        <input type="hidden" name="post_type" value="product">
						<?php endif; ?>
					</form>
				</div>
			</div>
		<?php
		}

		/**
		 * Widget Backend
		 *
		 * @param array $instance
		 *
		 * @return void
		 */
		public function form($instance)
		{
			$instance = wp_parse_args(
				Cast::toArray($instance),
				[
					'title' => '',
					'search_kind' => 'product',
					'css_class' => '',
				]
			);
			$this->widgetArgs = $instance;

			$title = esc_attr($instance['title']);
			$search_kind = strip_tags($instance['search_kind']);
			$css_class = strip_tags($instance['css_class']);

		?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __('Title', 'hd' ); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('search_kind'); ?>"><?php echo __('Type the search', 'hd' ); ?> </label>
				<select class="postform widefat" name="<?php echo $this->get_field_name('search_kind') ?>" id="<?php echo $this->get_field_id('search_kind') ?>" title>
					<option value="blog" <?php if ('blog' == $search_kind) echo ' selected="selected"' ?>><?php echo __('Blog', 'hd' ) ?></option>
					<option value="product" <?php if ('product' == $search_kind) echo ' selected="selected"' ?>><?php echo __('Product', 'hd' ); ?></option>
				</select>
				<span style="display: block;margin-top: 5px"><?php echo __('Select a search type, news or products', 'hd' ); ?></span>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('css_class'); ?>"><?php echo __('CSS class', 'hd' ); ?> </label>
				<input class="widefat" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" type="text" value="<?php echo $css_class; ?>" />
				<span style="display: block;margin-top: 5px;"><?php echo __('Each class name is separated by a space', 'hd' ); ?></span>
			</p>
        <?php
		}

		/**
		 * @param array $newInstance
		 * @param array $oldInstance
		 * @return array
		 */
		public function update($newInstance, $oldInstance)
		{
			$newInstance['title'] = sanitize_text_field($newInstance['title']);
			$newInstance['search_kind'] = $newInstance['search_kind'];
			$newInstance['css_class']      = isset($newInstance['css_class']) ? sanitize_text_field($newInstance['css_class']) : '';
			return parent::update($newInstance, $oldInstance);
		}
	}
}