<?php

/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.3.1
 */

if (!defined('ABSPATH')) {
	exit;
}

$total   = isset($total) ? $total : wc_get_loop_prop('total_pages');
$current = isset($current) ? $current : wc_get_loop_prop('current_page');
$base    = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
$format  = isset($format) ? $format : '';

if ($total <= 1) return;

// http://codex.wordpress.org/Function_Reference/paginate_links
$paginate_links = paginate_links(
	apply_filters(
		'woocommerce_pagination_args',
		[   // WPCS: XSS ok.
			'base'      => $base,
			'format'    => $format,
			'add_args'  => FALSE,
			'current'   => max(1, $current),
			'total'     => $total,
			'prev_next' => TRUE,
			'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path d="M25.1 247.5l117.8-116c4.7-4.7 12.3-4.7 17 0l7.1 7.1c4.7 4.7 4.7 12.3 0 17L64.7 256l102.2 100.4c4.7 4.7 4.7 12.3 0 17l-7.1 7.1c-4.7 4.7-12.3 4.7-17 0L25 264.5c-4.6-4.7-4.6-12.3.1-17z"/></svg>',
			'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><path d="M166.9 264.5l-117.8 116c-4.7 4.7-12.3 4.7-17 0l-7.1-7.1c-4.7-4.7-4.7-12.3 0-17L127.3 256 25.1 155.6c-4.7-4.7-4.7-12.3 0-17l7.1-7.1c4.7-4.7 12.3-4.7 17 0l117.8 116c4.6 4.7 4.6 12.3-.1 17z"/></svg>',
			'type'      => 'list',
			'end_size'  => 3,
			'mid_size'  => 3,
		]
	)
);

$paginate_links = str_replace("<ul class='page-numbers'>", '<ul class="pagination">', $paginate_links);
$paginate_links = str_replace('<li><span class="page-numbers dots">', '<li><a href="#">', $paginate_links);
$paginate_links = str_replace('</span>', '</a>', $paginate_links);
$paginate_links = str_replace("<li><span class='page-numbers current'>", '<li class="current">', $paginate_links);
$paginate_links = str_replace("<li><a href='#'>&hellip;</a></li>", '<li><span class="dots">&hellip;</span></li>', $paginate_links);
$paginate_links = preg_replace('/\s*page-numbers/', '', $paginate_links);
$paginate_links = preg_replace('/\s*class=""/', '', $paginate_links);

echo '<nav class="w-woocommerce-pagination" aria-label="Pagination">';
echo $paginate_links;
echo '</nav>';