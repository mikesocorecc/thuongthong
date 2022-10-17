<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Helpers\Url;

if (!class_exists('ProductCatCarousel_Widget')) {
    class ProductCatCarousel_Widget extends Widget {

        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Products Categories Carousels', 'hd');
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display products categories carousels + Custom Fields', 'hd');
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

            // glyph_icon
            $glyph_icon = '';
            if (Str::stripSpace($ACF->glyph_icon)) {
                $glyph_icon = ' data-glyph-after="' . $ACF->glyph_icon . '"';
            }

            //...
            $fullwidth = '';
            if ( ! $ACF->full_width ) {
                $fullwidth = ' grid-container width-extra';
            }

            ?>
            <section class="section product-cat-carousel-section <?= $_class ?>">
                <div class="title-container<?=$fullwidth?>">
                    <?php if ($title) : ?>
                    <?php if (Str::stripSpace($ACF->link_title)) : ?>
                    <a class="d-block" href="<?=$ACF->link_title?>" title="<?=esc_attr($title)?>">
                    <?php endif; ?>
                        <h2 class="heading-title"<?=$glyph_icon?>><?php echo $title; ?></h2>
                    <?php if (Str::stripSpace($ACF->link_title)) echo '</a>'; ?>
                    <?php endif; ?>
                    <?php
                    // menu list

                    if ($ACF->menu_list) :
                    ?>
                    <ul class="menu_list">
                        <?php foreach ( $ACF->menu_list as $key => $term_id ) :
                            $term = get_term( $term_id );
                        ?>
                        <li><a href="<?php echo get_term_link($term_id, 'product_cat'); ?>" title="<?php echo esc_attr($term->name); ?>"><?php echo $term->name; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
                <?php  if ($ACF->cat_list) : ?>
                <div class="items-container<?=$fullwidth?>">
                    <div class="swiper-section">
                        <?php
                        $swiper_class = '';
                        if ($ACF->gap) {
                            $_data["gap"] = true;
                            $swiper_class .= ' gap';
                        } elseif ($ACF->smallgap) {
                            $_data["smallgap"] = $ACF->smallgap;
                            $swiper_class .= ' smallgap';
                        }

                        if ($ACF->navigation) $_data["navigation"] = true;
                        if ($ACF->pagination) $_data["pagination"] = "dynamic";
                        if ($ACF->delay) $_data["delay"] = $ACF->delay;
                        if ($ACF->speed) $_data["speed"] = $ACF->speed;
                        if ($ACF->autoplay) $_data["autoplay"] = true;
                        if ($ACF->loop) $_data["loop"] = true;
                        if ($ACF->centered) $_data["centered"] = true;

                        if (!$ACF->number_desktop || !$ACF->number_tablet || !$ACF->number_mobile) {
                            $_data["autoview"] = true;
                            $swiper_class .= ' autoview';
                        } else {
                            $_data["desktop"] = $ACF->number_desktop;
                            $_data["tablet"] = $ACF->number_tablet;
                            $_data["mobile"] = $ACF->number_mobile;
                        }

                        if ($ACF->row > 1) {
                            $_data["row"] =  $ACF->row;
                            $_data["loop"] = false;
                        }

                        $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

                        ?>
                        <div class="w-swiper swiper">
                            <div class="swiper-wrapper<?= $swiper_class ?>" data-options='<?= $_data;?>'>
                                <?php
                                foreach ( $ACF->cat_list as $key => $term_id ) :
                                    $term = get_term( $term_id );
                                    if ($term) :
                                        $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
                                ?>
                                <div class="swiper-slide">
                                    <a class="d-block" href="<?php echo get_term_link($term_id, 'product_cat'); ?>" title="<?php echo esc_attr($term->name); ?>">
                                        <div class="cover">
                                            <span class="after-overlay res scale ratio-3v2"><?php echo wp_get_attachment_image($thumbnail_id, 'medium'); ?></span>
                                        </div>
                                        <div class="cover-content">
                                            <h6><?php echo $term->name; ?></h6>
                                        </div>
                                    </a>
                                </div>
                                <?php
                                    endif;
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                    <?php if ($ACF->url) : ?>
                    <a href="<?= esc_url($ACF->url) ?>" class="viewmore button" title="<?php echo esc_attr($ACF->button_text) ?>"><?php echo $ACF->button_text; ?></a>
                    <?php endif; ?>
                </div>
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