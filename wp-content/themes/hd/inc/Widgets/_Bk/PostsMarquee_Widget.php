<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;

if (!class_exists('PostsMarquee_Widget')) {
    class PostsMarquee_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Posts Marquee', 'hd');
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display posts marquee + Custom Fields', 'hd' );
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
            $title = apply_filters('widget_title', $title, $instance, $this->id_base);

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
            <section class="section posts-marquee-section <?= $_class ?>">
                <?php if (!$ACF->full_width) echo '<div class="grid-container">'; ?>
                <?php if ($title) : ?>
                <h2 class="heading-title"><?php echo $title; ?></h2>
                <?php endif;

                if ($ACF->list_posts) :

                ?>
                <div class="swiper-section swiper-marquee">
                    <?php

                    $swiper_class = '';
                    $_data["autoview"] = true;
                    $_data["marquee"] = true;
                    $_data["autoplay"] = true;
                    $_data["loop"] = true;

                    if ($ACF->pagination) $_data["pagination"] = "dynamic";
                    if ($ACF->gap) {
                        $_data["gap"] = true;
                        $swiper_class .= ' gap';
                    }
                    if ($ACF->delay) $_data["delay"] = $ACF->delay;
                    if ($ACF->speed) $_data["speed"] = $ACF->speed;

                    $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

                    ?>
                    <div class="w-swiper swiper">
                        <div class="swiper-wrapper<?= $swiper_class ?>" data-options='<?= $_data;?>'>
                            <?php
                            foreach ( $ACF->list_posts as $post_id ) :
                                $post = get_post( $post_id );
                                if ($post) :

                            ?>
                            <div class="swiper-slide">
                                <article class="item">
                                    <h6 data-glyph="">
                                        <a href="<?= get_permalink($post) ?>" title="<?php echo esc_attr(get_the_title($post)); ?>"><?= get_the_title($post) ?></a>
                                    </h6>
                                </article>
                            </div>
                            <?php endif; endforeach;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                </div>
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