<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;

if (!class_exists('ImagesCarousel_Widget')) {
    class ImagesCarousel_Widget extends Widget
    {
        /**
         * {@inheritdoc}
         */
        protected function widgetName()
        {
            return __('W - Images Carousel', 'hd' );
        }

        /**
         * {@inheritdoc}
         */
        protected function widgetDescription()
        {
            return __('Display the Images Carousel + Custom Fields', 'hd' );
        }

        /**
         * Outputs the content for the Images Carousel widget instance.
         *
         * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
         * @param array $instance Settings for the Images Carousel widget instance.
         */
        public function widget($args, $instance)
        {
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

            // banner cat
            $slides_query = false;
            if ($ACF->banner_cat) {
                $term = get_term_by('id', $ACF->banner_cat, 'banner_cat');
                if ($term) {
                    $slides_query = query_by_term($term, 'banner');
                }
            }

            ?>
            <section class="section images_carousel <?= $_class ?>">
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
                if ($slides_query) :
	                if (!$ACF->full_width) echo '<div class="grid-container width-extra">';

                ?>
                <div class="swiper-section<?php if ($ACF->marquee) echo ' swiper-marquee'; ?>">
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
                    if ($ACF->marquee) $_data["marquee"] = true;
                    if ($ACF->fade) $_data["fade"] = true;
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
                            // Load slides loop.
                            while ($slides_query->have_posts()) : $slides_query->the_post();
                                $post = get_post();

                                $ACF_banner = $this->acfFields($post->ID);
                                if ($ACF_banner->gallery) :
                                    foreach ($ACF_banner->gallery as $gal) :
                                        $attachment_meta = wp_get_attachment($gal);
                                        if (Str::stripSpace($attachment_meta->description) && filter_var($attachment_meta->description, FILTER_VALIDATE_URL)) {
                                            $_href = $attachment_meta->description;
                                        } else {
                                            $_href = false;
                                        }
                            ?>
                            <div class="swiper-slide">
                                <figure>
                                    <?php
                                    if ($_href) echo '<a class="after-overlay" href="' . $_href . '" title>';
                                    echo wp_get_attachment_image($gal, 'medium');
                                    if ($_href) echo '</a>';
                                    ?>
                                </figure>
                            </div>
                            <?php endforeach;
                            elseif (has_post_thumbnail()) :

                            ?>
                            <div class="swiper-slide">
                                <article class="item">
                                    <div class="overlay">
                                        <picture>
                                            <?php if ($ACF_banner->responsive_image) : ?>
                                            <source media="(max-width: 639.98px)" srcset="<?= attachment_image_src($ACF_banner->responsive_image, 'medium') ?>">
                                            <?php else : ?>
                                            <source media="(max-width: 639.98px)" srcset="<?= post_image_src($post->ID, 'medium') ?>">
                                            <source media="(max-width: 1023.98px)" srcset="<?= post_image_src($post->ID, 'large') ?>">
                                            <?php endif; ?>
                                            <img src="<?php echo post_image_src($post->ID, 'widescreen') ?>" alt="">
                                        </picture>
                                        <?php if ($ACF_banner->url) : ?>
                                        <a class="link-overlay _blank" href="<?= $ACF_banner->url ?>" title></a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="content-wrap">
                                        <div class="content-inner">
                                            <div class="inner">
                                                <?php if (Str::stripSpace($ACF_banner->sub_title)) : ?>
                                                <h6 class="sub-title"><?= $ACF_banner->sub_title ?></h6>
                                                <?php endif; ?>
                                                <?php if (Str::stripSpace($ACF_banner->html_title)) : ?>
                                                <h2 class="html-title h1 f-light"><?= $ACF_banner->html_title ?></h2>
                                                <?php endif; ?>
                                                <?php if (Str::stripSpace($ACF_banner->html_desc)) : ?>
                                                <div class="html-desc"><?= $ACF_banner->html_desc ?></div>
                                                <?php endif; ?>
                                                <?php if ($ACF_banner->url) : ?>
                                                <div class="btn-link"><a class="button<?php if ($ACF_banner->video_link) echo ' popup-box'; ?>" href="<?= $ACF_banner->url ?>" aria-label="<?php esc_attr_e($ACF_banner->button_name, 'hd' ); ?>"><?php echo __($ACF_banner->button_name, 'hd' ) ?></a></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <?php
                                endif;
                            endwhile;
                            wp_reset_postdata();
                            unset($ACF_banner);
                            ?>
                        </div>
                    </div>
                </div>
                <?php if (!$ACF->full_width) echo '</div>'; ?>
                <?php endif; ?>
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