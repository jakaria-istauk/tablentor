<?php
namespace Jakaria\Tablentor;

use Elementor\Control_Media;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CSV Media control.
 *
 * A thin extension of Elementor's Media control that restricts the
 * library/upload to CSV files only. The CSV-only enforcement on the
 * upload picker and on selection lives in the matching editor JS view
 * (assets/admin/js/csv-media-control.js), registered under the same type.
 *
 * Stored value matches Control_Media: [ 'url' => ..., 'id' => ... ].
 *
 * @package Plugin
 * @subpackage Controls
 */
class Control_Csv_Media extends Control_Media {

	const TYPE = 'tablentor-csv-media';

	public function get_type() {
		return self::TYPE;
	}

	protected function get_default_settings() {
		$settings = parent::get_default_settings();

		// Restrict the wp.media library grid to CSV attachments.
		$settings['media_types'] = [ 'text/csv' ];

		// CSV is not an image: drop image-only AI + dynamic-tag integrations.
		$settings['ai']      = [ 'active' => false ];
		$settings['dynamic'] = [ 'active' => false ];

		return $settings;
	}
}
