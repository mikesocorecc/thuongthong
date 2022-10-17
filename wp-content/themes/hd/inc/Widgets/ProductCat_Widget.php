<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Helpers\Url;

if (!class_exists('ProductCat_Widget')) {
    class ProductCat_Widget extends Widget {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Products Categories', 'hd');
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display products categories list + Custom Fields', 'hd');
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

            // class
            $_class = $this->id;
            if ( $ACF->css_class ) {
                $_class = $_class . ' ' . $ACF->css_class;
            }

            ?>
            <section class="section product-cat-section productcat-section <?= $_class ?>">
                <div class="grid-container width-extra title-container">
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
                </div>
                <?php

                // loop
                if ( $ACF->product_cat ) :
                    if ( ! $ACF->full_width ) echo '<div class="grid-container width-extra">';
                ?>
                <div class="grid-x grid-productcat">
                    <?php
                    foreach ( $ACF->product_cat as $key => $term_id ) :
                        $term = get_term( $term_id );
                        if ($term) :
                            $thumbnail_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
                    ?>
                    <div class="cell cell-<?=$key?>">
                        <figure class="cover scale">
                            <a href="<?php echo get_term_link($term->term_id, 'product_cat'); ?>" class="after-overlay" aria-label="<?php echo esc_attr($term->name); ?>"><?php echo wp_get_attachment_image($thumbnail_id, 'medium'); ?></a>
                            <figcaption>
                                <h6><?php echo $term->name; ?></h6>
                                <a title="<?php echo esc_attr($term->name); ?>" class="link-overlay" href="<?php echo get_term_link($term->term_id, 'product_cat'); ?>"></a>
                            </figcaption>
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
                <?php if (!$ACF->full_width) echo '</div>'; ?>
                <?php endif; ?>
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
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>"/>
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
