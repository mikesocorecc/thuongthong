<?php

namespace Webhd\Widgets;

\defined( '\WPINC' ) || die;

use Webhd\Helpers\Cast;
use Webhd\Helpers\Str;
use WP_Widget;

abstract class Widget extends WP_Widget {
	/**
	 * @var string
	 */
	protected string $prefix = 'w-';

	/**
	 * @var array
	 */
	protected array $widgetArgs;

	public function __construct() {
		$className = ( new \ReflectionClass( $this ) )->getShortName();
		$className = str_replace( [ '_Widget', 'Widget' ], '', $className );
		$baseId    = $this->prefix . Str::dashCase( $className );

		parent::__construct( $baseId, $this->widgetName(), $this->widgetOptions() );
	}

	/**
	 * @param $id
	 *
	 * @return object|null
	 */
	protected function acfFields( $id ): ?object {
		if ( ! class_exists( '\ACF' ) ) {
			return null;
		}

		return Cast::toObject( get_fields( $id ) );
	}

	/**
	 * @return string|void
	 */
	protected function widgetDescription() {
		return '';
	}

	/**
	 * @return string|void
	 */
	protected function widgetName() {
		return __( 'Unknown Widget', 'hd' );
	}

	/**
	 * @return array
	 */
	protected function widgetOptions() {
		return [
			'description'                 => $this->widgetDescription(),
			'name'                        => $this->widgetName(),
			'customize_selective_refresh' => true,
			'show_instance_in_rest'       => true,
		];
	}
}