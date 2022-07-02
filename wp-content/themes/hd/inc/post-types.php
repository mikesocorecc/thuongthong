<?php
/**
 * register_widget functions
 * @author   WEBHD
 */
\defined( '\WPINC' ) || die;

use Webhd\PostTypes\Banner_PostType;

class_exists( Banner_PostType::class ) && ( new Banner_PostType );