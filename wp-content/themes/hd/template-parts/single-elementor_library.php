<?php

/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 * @package HelloElementor
 */

\defined( '\WPINC' ) || die;

if (have_posts()) the_post();

?>
<section class="section single-elementor" style="margin-top: 150px;">
	<div class="col-content clearfix">
		<?php
		// post content
		the_content();
		?>
	</div>
</section>
