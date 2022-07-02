<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('MiniCart_Widget')) {
	class MiniCart_Widget extends Widget {
		/**
		 * {@inheritdoc}
		 */
		protected function widgetName()
		{
			return __('W - Mini Cart', 'hd');
		}

		/**
		 * {@inheritdoc}
		 */
		protected function widgetDescription()
		{
			return __('Display the mini cart', 'hd');
		}

		/**
		 * Outputs the content for the Images Carousel widget instance.
		 *
		 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
		 * @param array $instance Settings for the Images Carousel widget instance.
		 */
		public function widget($args, $instance)
		{
            $title = $instance['title'] ?: '';
			if (!empty($title)) {
				echo '<span class="widget-title">' . $title . '</span>';
			}

            //...
			woo_header_cart();
		}

		/**
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
				]
			);
			$this->widgetArgs = $instance;
			?>
            <p>
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hd'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
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
			return parent::update($newInstance, $oldInstance);
		}
	}
}