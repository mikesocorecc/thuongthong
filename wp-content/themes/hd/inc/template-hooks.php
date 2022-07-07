<?php

/**
 * Template Filters
 * @author WEBHD
 */
\defined('\WPINC') || die;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use Webhd\Themes\SVG_Icons;

// -------------------------------------------------------------
// wp_head
// -------------------------------------------------------------

// wp_head
add_action('wp_head', '__critical_inline_css', 1);
add_action('wp_head', '__extra_header', 10);

/**
 * @return void
 */
function __critical_inline_css()
{
    $inline_css = "";
    if ($inline_css) :
        echo '<style id="hd-inline-css">' . $inline_css . '</style>';
    endif;
}

/**
 * @return void
 */
function __extra_header()
{
    //echo '<link rel="preconnect" href="https://fonts.gstatic.com">';
    //echo "<link rel=\"manifest\" href=\"/manifest.json\">";

    $theme_color = get_theme_mod_ssl('theme_color_setting');
    if ($theme_color) {
        echo '<meta name="theme-color" content="' . $theme_color . '" />';
    }

    $fb_appid = get_theme_mod_ssl('fb_menu_setting');
    if ($fb_appid) {
        echo '<meta property="fb:app_id" content="' . $fb_appid . '" />';
    }
}

// -------------------------------------------------------------
// wp_footer
// -------------------------------------------------------------

// wp_footer
add_action('wp_footer', '__extra_footer', 99);
function __extra_footer() {}

// -------------------------------------------------------------
// off_canvas
// -------------------------------------------------------------

add_action('off_canvas', '__off_canvas_button', 10);

/**
 * @return void
 */
function __off_canvas_button()
{
    // mobile navigation
    $position = get_theme_mod_ssl('offcanvas_menu_setting');
    if (!in_array($position, ['left', 'right', 'top', 'bottom'])) {
        $position = 'left';
    }

    get_template_part('template-parts/header/navigation-' . $position . '-offcanvas');
}

// -------------------------------------------------------------
// before_header
// -------------------------------------------------------------

// before_header actions
add_action('before_header', '__before_header_extra', 14);

/**
 * @return void
 */
function __before_header_extra()
{
    if (function_exists('wp_body_open')) {
        wp_body_open();
    } else {
        do_action('wp_body_open');
    }

?>
    <a class="skip-link screen-reader-text" href="#site-navigation"><?php echo __('Skip to navigation', 'hd'); ?></a>
    <a class="skip-link screen-reader-text" href="#main-content"><?php echo __('Skip to main content', 'hd'); ?></a>
    <?php
}

// -------------------------------------------------------------
// header
// -------------------------------------------------------------

// header
add_action('header', '__topheader', 10);
add_action('header', '__header', 10);

/**
 * @return void
 */
function __topheader()
{
    $topheader_left = is_active_sidebar('w-topheader-left-sidebar');
    $topheader_right = is_active_sidebar('w-topheader-right-sidebar');
    if ($topheader_left || $topheader_right) :

    ?>
    <div class="top-header" id="top-header">
        <div class="grid-container width-extra">
            <?php
            if ($topheader_left) :
                echo '<div class="left top-header-inner">';
                dynamic_sidebar('w-topheader-left-sidebar');
                echo '</div>';
            endif;
            if ($topheader_right) :
                echo '<div class="right top-header-inner">';
                dynamic_sidebar('w-topheader-right-sidebar');
                echo '</div>';
            endif;
            ?>
        </div>
    </div>
    <?php
    endif;
}

// ----------------------------
/**
 * @return void
 */
function __header() {

    $header_sidebar = is_active_sidebar('w-header-sidebar');
    ?>
    <div data-sticky-container>
        <div class="inside-header" id="inside-header" data-sticky data-options="marginTop:0;" data-top-anchor="top-header:bottom">
            <div class="grid-container width-extra">
                <div class="header-inner">
                    <div class="left">
                        <?php if ( $icon_logo = site_logo('icon')) : ?>
                        <div class="icon-logo"><?=$icon_logo?></div>
                        <?php endif; ?>
                        <?php get_template_part('template-parts/header/primary-menu'); ?>
                    </div>
                    <div class="site-logo">
                        <?php site_title_or_logo(); ?>
                    </div>
                    <div class="right">
                        <?php if (has_nav_menu('second-nav')) : ?>
                        <nav id="second-nav" class="navigation" role="navigation" aria-label="<?php echo esc_attr__('Second Navigation', 'hd'); ?>">
                            <?php echo second_nav(); ?>
                        </nav>
                        <?php endif; if ($header_sidebar) : ?>
                        <div class="widget-group">
                            <?php dynamic_sidebar('w-header-sidebar'); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}

// -------------------------------------------------------------
// footer
// -------------------------------------------------------------

// footer
add_action('footer', '__footer_widgets', 10);
add_action('footer', '__footer_credit', 11);

/**
 * @return void
 */
function __footer_widgets() {
    $rows    = Cast::toInt(get_theme_mod_ssl('footer_row_setting'));
    $regions = Cast::toInt(get_theme_mod_ssl('footer_col_setting'));

    ?>
    <footer id="colophon" class="footer-widgets" role="contentinfo">
        <?php
        for ($row = 1; $row <= $rows; $row++) :

            // Defines the number of active columns in this footer row.
            for ($region = $regions; 0 < $region; $region--) {
                if (is_active_sidebar('w-footer-' . esc_attr($region + $regions * ($row - 1)))) {
                    $columns = $region;
                    break;
                }
            }

            if (isset($columns)) :
            ?>
                <div class="grid-container width-extra row-<?php echo $row; ?>">
                    <div class="grid-x">
                        <?php
                        for ($column = 1; $column <= $columns; $column++) :
                            $footer_n = $column + $regions * ($row - 1);
                            if (is_active_sidebar('w-footer-' . esc_attr($footer_n))) :
                        ?>
                                <div class="cell footer-widget footer-widget-<?php echo esc_attr($column); ?>">
                                    <?php dynamic_sidebar('w-footer-' . esc_attr($footer_n)); ?>
                                </div>
                        <?php
                            endif;
                        endfor;
                        ?>
                    </div>
                </div>
        <?php
                unset($columns);
            endif;
        endfor;
        ?>
    </footer><!-- #colophon -->
<?php
}

// ----------------------------

/**
 * @return void
 */
function __footer_credit() {
    ?>
    <footer class="footer-credit">
        <div class="grid-container width-extra">
            <div class="align-middle grid-x grid-padding-x align-justify">
                <?php if (has_nav_menu('policy-nav')) : ?>
                <div class="cell nav">
                    <?php echo term_nav(); ?>
                </div>
                <?php endif; ?>
                <?php if (is_active_sidebar('w-extra-sidebar')) : ?>
                <div class="cell extra">
                    <?php dynamic_sidebar('w-extra-sidebar'); ?>
                </div>
                <?php endif; ?>
                <div class="cell copyright">
                    <div class="copyright-inner">
                        <p>
                            <span class="cr">&copy;&nbsp;<?= date('Y') ?>&nbsp;<?= get_bloginfo('name') ?>, All rights reserved.</span>
                            <span class="hd">&nbsp;<?php echo sprintf('<a class="_blank hide" href="https://webhd.vn/thiet-ke-website/" title="%1$s">%1$s</a><span class="hide">&nbsp;%2$s&nbsp;</span><a class="_blank" href="https://webhd.vn/" title="%3$s">%3$s</a>', __('Thiết kế web', 'hd'), __('by', 'hd'), __('Webhd Agency', 'hd')) ?></span>
                        </p>
                        <?php
                        $GPKD = get_theme_mod_ssl('GPKD_setting');
                        if (Str::stripSpace($GPKD))
                            echo '<p class="gpkd">' . $GPKD . '</p>'
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
<?php
}

// -------------------------------------------------------------
// before_footer
// -------------------------------------------------------------

// before footer
add_action('before_footer', '__before_fixed_extra', 31);
add_action('before_footer', '__before_footer_extra', 32);

/**
 * @return void
 */
function __before_fixed_extra()
{
    if (is_active_sidebar('w-fixedfooter-sidebar')) {
        echo '<div class="fixedfooter-section draggable">';
        dynamic_sidebar('w-fixedfooter-sidebar');
        echo '</div>';
    }
}

//---------------------------------------

/**
 * @return void
 */
function __before_footer_extra()
{
    if (is_active_sidebar('w-topfooter-sidebar')) {
        dynamic_sidebar('w-topfooter-sidebar');
    }
}

// -------------------------------------------------------------
// before_content
// -------------------------------------------------------------

// before content
add_action('before_content', '__before_content_extra', 10);
function __before_content_extra() {}

// ------------------------------------------------------
// ------------------------------------------------------

/**
 * @param $item_output
 * @param $item
 * @param $depth
 * @param $args
 *
 * @return string|string[]
 */
add_filter('walker_nav_menu_start_el', function ($item_output, $item, $depth, $args) {

    // Change SVG icon inside social links menu if there is supported URL.
    if ('social-nav' === $args->theme_location && class_exists(SVG_Icons::class)) {
        $svg = SVG_Icons::get_social_link_svg($item->url, 24);
        if (!empty($svg)) {
            $item_output = str_replace($args->link_before, $svg, $item_output);
        }
    }

    return $item_output;

}, 12, 4);

// -------------------------------------------------------------

/**
 * @param array $args
 *
 * @return array
 */
add_filter('widget_tag_cloud_args', function (array $args) {
    $args['smallest'] = '10';
    $args['largest']  = '19';
    $args['unit']     = 'px';
    $args['number']   = 12;

    return $args;
});

// -------------------------------------------------------------

// add class to achor link
add_filter('nav_menu_link_attributes', function ($atts) {

    //$atts['class'] = "nav-link";
    return $atts;

}, 100, 1);

// -------------------------------------------------------------
// optimize load
// -------------------------------------------------------------

add_filter('defer_script_loader_tag', function ($arr) {
    $arr = [
        'google-recaptcha' => 'defer',
        'wpcf7-recaptcha'  => 'defer',
        'contact-form-7'   => 'defer',
        'comment-reply'    => 'delay',
        'wp-embed'         => 'delay',
        'admin-bar'        => 'delay',
        'fixedtoc-js'      => 'delay',
        'backtop'          => 'delay',
        'shares'           => 'delay',
        'draggable'        => 'delay',
    ];

    return $arr;
}, 10, 1);

// -------------------------------------------------------------

add_filter('defer_style_loader_tag', function ($arr) {
    $arr = [
        'dashicons',
        'fixedtoc-style',
        'contact-form-7',
        'rank-math',
        'fonts-style',
    ];

    return $arr;
}, 10, 1);