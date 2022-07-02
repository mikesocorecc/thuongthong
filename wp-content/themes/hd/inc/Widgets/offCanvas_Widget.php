<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;

if (!class_exists('offCanvas_Widget')) {
	class offCanvas_Widget extends Widget
	{
		/**
		 * {@inheritdoc}
		 */
		protected function widgetName()
		{
			return __('W - offCanvas Button', 'hd' );
		}

		/**
		 * {@inheritdoc}
		 */
		protected function widgetDescription()
		{
			return __('Display offCanvas Button', 'hd' );
		}

		/**
		 * Creating widget front-end
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget($args, $instance)
		{
            $title = $instance['title'] ?: '';

            ?>
			<div class="off-canvas-content" data-off-canvas-content>
				<button class="menu-lines" type="button" data-open="offCanvasMenu" aria-label="button">
					<span class="menu-txt"><?php echo __('Menu', 'hd' ); ?></span>
					<span class="line line-1"></span>
					<span class="line line-2"></span>
					<span class="line line-3"></span>
				</button>
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
				]
			);
			$this->widgetArgs = $instance;

			$title    = $instance['title'];
		    ?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hd' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
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