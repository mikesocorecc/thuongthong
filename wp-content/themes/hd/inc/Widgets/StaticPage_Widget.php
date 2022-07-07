<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('StaticPage_Widget')) {
    class StaticPage_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Brief Introduction', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display brief introduction section', 'hd' );
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
            $title = $instance['title'] ?: '';

            // ACF attributes
            $ACF = $this->acfFields( 'widget_' . $args['widget_id'] );
            if ( is_null( $ACF ) ) {
                wp_die( __( 'Required: "Advanced Custom Fields" plugin', 'hd' ) );
            }

            // class
            $_class = $this->id;
            if ( $ACF->css_class ) {
                $_class = $_class . ' ' . $ACF->css_class;
            }

            ?>
            <section class="section brief-intro <?= $_class ?>">
                <div class="<?=$ACF->kind_view?> width-extra brief-intro-inner">
                    <div class="left-col">
                        <?php if ($ACF->sub_title) : ?>
                        <h6 class="sub-title"><?php echo $ACF->sub_title; ?></h6>
                        <?php
                        endif;
                        if ($title) :
                        ?>
                        <h2 class="heading-title"><?php echo $title; ?></h2>
                        <?php endif;
                        if (Str::stripSpace($ACF->html_title)) :
                            echo '<div class="html-title">';
                            echo $ACF->html_title;
                            echo '</div>';
                        endif;
                        if (Str::stripSpace($ACF->html_desc)) : ?>
                        <div class="html-desc"><?php echo $ACF->html_desc; ?></div>
                        <?php endif; ?>
                        <?php if ($ACF->url) : ?>
                        <a href="<?= esc_url($ACF->url); ?>" class="view-more button" title="<?php echo esc_attr($ACF->button_text); ?>"><?php echo $ACF->button_text; ?></a>
                        <?php endif; ?>
                    </div>
                    <?php if (Str::stripSpace($ACF->background_img)) : ?>
                    <div class="right-col background-col">
                        <span class="bg"><?php echo wp_get_attachment_image($ACF->background_img, 'large');?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php
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