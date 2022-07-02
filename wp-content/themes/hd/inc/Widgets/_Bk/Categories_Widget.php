<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Helpers\Url;

if (!class_exists('Categories_Widget')) {
    class Categories_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Categories', 'hd');
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display categories list + Custom Fields', 'hd');
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
            <section class="section categories-section <?= $_class ?>">
                <?php if (!$ACF->full_width) echo '<div class="grid-container width-extra">'; ?>
                <?php if ($title) : ?>
                <h2 class="heading-title"><?php echo $title; ?></h2>
                <?php endif;
                if (Str::stripSpace($ACF->html_desc)) : ?>
                <div class="html-desc"><?php echo $ACF->html_desc; ?></div>
                <?php endif;

                if ($ACF->cat) :

                ?>
                <div class="grid-x">
                    <?php foreach ( $ACF->cat as $i => $term_id ) :
                        $term = get_term( $term_id );
                        if ($term) :
                            $term_thumb = acf_term_thumb($term, 'term_thumb', "medium", true);
                            if ( ! $term_thumb ) {
                                $term_thumb = '<img src="' . Url::pixelImg() . '" alt>';
                            }

                            //$ratio_class = '16v9';

//                            $ratio = get_theme_mod_ssl('news_menu_setting');
//                            $ratio_class = $ratio;
//                            if ('default' == $ratio or !$ratio) {
//                                $ratio_class = '3v2';
//                            }

                    ?>
                    <div class="cell cell-<?=$i?>">
                        <figure class="cover">
                            <span class="after-overlay scale"><?php echo $term_thumb; ?></span>
                            <figcaption>
                                <h6><?php echo $term->name; ?></h6>
                            </figcaption>
                            <a class="link-overlay" href="<?php echo get_term_link($term->term_id, 'category'); ?>"></a>
                        </figure>
                    </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
                <?php if ($ACF->url) : ?>
                <a href="<?= esc_url($ACF->url) ?>" class="viewmore" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                <?php endif; ?>
                <?php endif; if (!$ACF->full_width) echo '</div>'; ?>
            </section>
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
                <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'hd'); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                       name="<?php echo $this->get_field_name('title'); ?>" type="text"
                       value="<?php echo esc_attr($instance['title']); ?>"/>
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