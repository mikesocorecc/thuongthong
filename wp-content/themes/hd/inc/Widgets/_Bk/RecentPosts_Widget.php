<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;

if (!class_exists('RecentPosts_Widget')) {
    class RecentPosts_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Filter Recent Posts', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display filter recent posts + Custom Fields', 'hd' );
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

            //...
            $r = query_by_terms($ACF->cat, 'category', 'post', $ACF->include_children, $ACF->number);
            if (!$r) return;

            ?>
            <aside class="aside recent-posts-container <?= $_class ?>">
                <div class="aside-inner">
                    <?php if ($title) : ?>
                    <h5 class="aside-title"><?php echo $title; ?></h5>
                    <?php endif;

                    $_class = ' class="no-thumbs"';
                    if ($ACF->show_img) :
                        $ratio       = get_theme_mod_ssl('news_menu_setting');
                        $ratio_class = $ratio;
                        if ('default' == $ratio or !$ratio) {
                            $ratio_class = '3v2';
                        }
                        $_class = ' class="has-thumbs"';
                    endif;

                    ?>
                    <ul<?=$_class?>>
                        <?php
                        $i = 0;

                        // Load slides loop.
                        while ($r->have_posts() && $i < $ACF->number) :
                            $r->the_post();
                        ?>
                        <li>
                            <?php if ($ACF->show_img) : ?>
                            <a class="d-block" href="<?= get_permalink() ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>" tabindex="0">
                                <div class="cover">
                                    <span class="after-overlay res ratio-<?= $ratio_class ?>"><?php echo get_the_post_thumbnail(null, 'medium'); ?></span>
                                </div>
                            </a>
                            <?php endif; ?>
                            <div class="cover-content">
                                <h6><a href="<?= get_permalink() ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title() ?></a></h6>
                                <div class="meta">
                                    <?php echo get_primary_term(); ?>
                                    <?php echo '<div class="time">' . humanize_time() . '</div>'; ?>
                                </div>
                                <?php if ($ACF->show_desc) echo loop_excerpt(); ?>
                            </div>
                        </li>
                        <?php
                            ++$i;
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </ul>
                    <?php if ($ACF->url) : ?>
                    <a href="<?= esc_url($ACF->url) ?>" class="viewmore" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                    <?php endif; ?>
                </div>
            </aside>
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