<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;

if (!class_exists('PageTitle_Widget')) {
    class PageTitle_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Page Title', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display page title, background and breadcrumbs', 'hd' );
        }

        /**
         * Outputs the content.
         *
         * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
         * @param array $instance Settings for the Images Carousel widget instance.
         */
        public function widget($args, $instance)
        {
            /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            //$title = $instance['title'] ?: '';

            the_page_title_theme();
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
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hd' ); ?></label>
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