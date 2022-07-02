<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('LinkBox_Widget')) {
    class LinkBox_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - LinkBox', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display link box + Custom Fields', 'hd');
        }

        /**
         * Creating widget front-end
         *
         * @param array $args
         * @param array $instance
         */
        public function widget($args, $instance) {

            /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
            $title = $instance['title'] ?: '';

            // ACF attributes
            $ACF = $this->acfFields( 'widget_' . $args['widget_id'] );
            if ( is_null( $ACF ) ) {
                wp_die( __( 'Required: "Advanced Custom Fields" plugin', 'hd' ) );
            }

            //...
            $glyph = '';
            if (Str::stripSpace($ACF->glyph)) {
                $glyph = ' data-glyph="' . $ACF->glyph . '"';
            }

            ?>
            <div class="linkbox">
                <span class="linbox-inner"<?=$glyph?>>
                    <?php
                    if (Str::stripSpace($ACF->url)) {
                        $_class = '';
                        if ($ACF->_blank) $_class = ' class="_blank"';
                        echo '<a' . $_class . ' href="' . $ACF->url . '" title="' . $title . '">';
                        echo '<span>' . $title . '</span>';
                        echo '</a>';
                    }
                    ?>
                </span>
            </div>
            <?php
        }

        /**
         * @param $instance
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
         *
         * @return array
         */
        public function update($newInstance, $oldInstance)
        {
            $newInstance['title'] = sanitize_text_field($newInstance['title']);
            return parent::update($newInstance, $oldInstance);
        }
    }
}