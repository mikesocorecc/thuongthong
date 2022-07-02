<?php

namespace Webhd\Widgets;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Helpers\Url;

if (!class_exists('CustomerCarousel_Widget')) {
	class CustomerCarousel_Widget extends Widget
	{
		/**
		 * {@inheritdoc}
		 */
		protected function widgetName()
		{
			return __('W - Customers Carousel', 'hd' );
		}

		/**
		 * {@inheritdoc}
		 */
		protected function widgetDescription()
		{
            return __('Display the Customers Carousel + Custom Fields', 'hd' );
		}

		/**
		 * Creating widget front-end
		 *
		 * @param array $args
		 * @param array $instance
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

            if ($ACF->noi_dung) :

            ?>
            <section class="section carousel-customers <?= $_class ?>">
                <?php if (!$ACF->full_width) echo '<div class="grid-container width-extra">'; ?>
                <div class="swiper-section">
                    <?php
                    $swiper_class = ' autoview';
                    $_data["autoview"] = true;
                    $_data["autoplay"] = true;
                    $_data["loop"] = true;

                    if ($ACF->pagination) $_data["pagination"] = "dynamic";
                    if ($ACF->navigation) $_data["navigation"] = true;

                    $_data = json_encode($_data, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE);

                    ?>
                    <div class="w-swiper swiper">
                        <div class="swiper-wrapper<?= $swiper_class ?>" data-options='<?= $_data;?>'>
                            <?php foreach ($ACF->noi_dung as $item) : ?>
                            <div class="swiper-slide">
                                <div class="item-inner">
                                    <?php if (Str::stripSpace($item['content'])) : ?>
                                    <div class="desc" data-glyph=""><?=$item['content']?></div>
                                    <?php endif; ?>
                                    <?php if ($item['ten'] || $item['img']) : ?>
                                    <div class="info">
                                        <span class="img"><?php echo wp_get_attachment_image($item['img'], 'thumbnail');?></span>
                                        <span class="ten"><?php echo $item['ten']?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php if (!$ACF->full_width) echo '</div>'; ?>
            </section>
		<?php endif;
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