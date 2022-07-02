<?php
/**
 * register_widget functions
 *
 * @author   WEBHD
 */
\defined('\WPINC') || die;

use Webhd\Widgets\Cf7_Widget;
use Webhd\Widgets\MiniCart_Widget;
use Webhd\Widgets\DropdownSearch_Widget;
use Webhd\Widgets\Search_Widget;
use Webhd\Widgets\offCanvas_Widget;
use Webhd\Widgets\ImagesCarousel_Widget;
use Webhd\Widgets\LinkBox_Widget;
use Webhd\Widgets\PostsCarousel_Widget;
use Webhd\Widgets\StaticPage_Widget;
use Webhd\Widgets\CustomerCarousel_Widget;
use Webhd\Widgets\ProductCat_Widget;
use Webhd\Widgets\FilterTabsProducts_Widget;

if (!function_exists('__register_widgets')) {

    /**
     * Registers a WP_Widget widget
     *
     * @return void
     */
    function __register_widgets()
    {
        class_exists('\WPCF7') && class_exists('\ACF') && class_exists(Cf7_Widget::class) && register_widget(new Cf7_Widget);
        class_exists( '\WooCommerce' ) && class_exists(MiniCart_Widget::class) && register_widget( new MiniCart_Widget );

        class_exists('\ACF') && class_exists(ImagesCarousel_Widget::class) && register_widget(new ImagesCarousel_Widget);
        class_exists('\ACF') && class_exists(LinkBox_Widget::class) && register_widget(new LinkBox_Widget);
        class_exists('\ACF') && class_exists(PostsCarousel_Widget::class) && register_widget(new PostsCarousel_Widget);
        class_exists('\ACF') && class_exists(StaticPage_Widget::class) && register_widget(new StaticPage_Widget);
        class_exists('\ACF') && class_exists(CustomerCarousel_Widget::class) && register_widget(new CustomerCarousel_Widget);
        class_exists('\ACF') && class_exists(ProductCat_Widget::class) && register_widget(new ProductCat_Widget);
        class_exists('\ACF') && class_exists(FilterTabsProducts_Widget::class) && register_widget(new FilterTabsProducts_Widget);

        class_exists(DropdownSearch_Widget::class) && register_widget(new DropdownSearch_Widget);
        class_exists(Search_Widget::class) && register_widget(new Search_Widget);
        class_exists(offCanvas_Widget::class) && register_widget(new offCanvas_Widget);
    }

    /** */
    $widgets_block_editor_off = get_theme_mod_ssl('use_widgets_block_editor_setting');
    if ($widgets_block_editor_off) {
        add_action('widgets_init', '__register_widgets', 10);
    }
}