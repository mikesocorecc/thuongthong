<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('Posts_Widget')) {
    class Posts_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Posts', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display posts + Custom Fields', 'hd' );
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
            $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

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

            //...
            $r = query_by_terms($ACF->cat, 'category', 'post', $ACF->include_children, $ACF->number);
            if (!$r) return;

            ?>
            <section class="section posts-section <?= $_class ?>">
                <?php if (!$ACF->full_width) echo '<div class="grid-container">'; ?>
                <?php if ($title) : ?>
                <h2 class="heading-title h5"><?php echo $title; ?></h2>
                <?php endif;
                if (Str::stripSpace($ACF->html_desc)) : ?>
                <div class="heading-desc"><?php echo $ACF->html_desc; ?></div>
                <?php endif;

                ?>
                <div class="posts-list">
                    <?php
                    $i = 0;

                    // Load slides loop.
                    while ($r->have_posts() && $i < $ACF->number) :
                        $r->the_post();

                        if (0 === $i) echo '<div class="first">';
                        elseif (1 === $i) echo '<div class="second">';

                        get_template_part('template-parts/posts/loop', null, Cast::toArray($ACF));
                        if (0 === $i) echo '</div>';

                        ++$i;
                    endwhile;
                    if (1 < $i) echo '</div>';
                    wp_reset_postdata();

                    ?>
                </div>
                <?php if ($ACF->url) : ?>
                <a href="<?= esc_url($ACF->url) ?>" class="viewmore" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                <?php endif; ?>
                <?php if (!$ACF->full_width) echo '</div>'; ?>
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