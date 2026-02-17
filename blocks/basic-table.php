<?php
namespace Jakaria\Tablentor\Blocks;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Basic_Table {

	public function register() {
		wp_register_script(
			'tablentor-basic-table-block-editor',
			CMPRTBL_ASSET_DIR . 'admin/js/basic-table-block.js',
			[ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-components', 'wp-block-editor' ],
			CMPRTBL_VERSION,
			true
		);

		wp_register_script(
			'tablentor-basic-table-block-front',
			CMPRTBL_ASSET_DIR . 'front/js/basic-table-block.js',
			[],
			CMPRTBL_VERSION,
			true
		);

		wp_register_style(
			'tablentor-basic-table-block',
			CMPRTBL_ASSET_DIR . 'front/css/basic-table-block.css',
			[],
			CMPRTBL_VERSION
		);

		wp_register_style(
			'tablentor-basic-table-block-editor',
			CMPRTBL_ASSET_DIR . 'admin/css/basic-table-block-editor.css',
			[ 'wp-edit-blocks' ],
			CMPRTBL_VERSION
		);

		register_block_type(
			'tablentor/basic-table',
			[
				'api_version'     => 2,
				'editor_script'   => 'tablentor-basic-table-block-editor',
				'editor_style'    => 'tablentor-basic-table-block-editor',
				'script'          => 'tablentor-basic-table-block-front',
				'style'           => 'tablentor-basic-table-block',
				'attributes'      => $this->get_attributes_schema(),
				'render_callback' => [ $this, 'render' ],
			]
		);
	}

	private function get_attributes_schema() {
		return [
			'rows' => [ 'type' => 'number', 'default' => 3 ],
			'columns' => [ 'type' => 'number', 'default' => 3 ],
			'cells' => [ 'type' => 'array', 'default' => [] ],
			'headingRow' => [ 'type' => 'boolean', 'default' => true ],
			'enableSearch' => [ 'type' => 'boolean', 'default' => false ],
			'searchPlaceholder' => [ 'type' => 'string', 'default' => 'Search' ],
			'searchAlignment' => [ 'type' => 'string', 'default' => 'right' ],

			'tableBgColor' => [ 'type' => 'string', 'default' => '#ffffff' ],
			'tableBorderColor' => [ 'type' => 'string', 'default' => '#d1d5db' ],
			'tableBorderWidth' => [ 'type' => 'number', 'default' => 1 ],
			'tableBorderRadius' => [ 'type' => 'number', 'default' => 0 ],
			'tablePadding' => [ 'type' => 'string', 'default' => '0px' ],
			'tableMargin' => [ 'type' => 'string', 'default' => '0px' ],
			'tableBoxShadow' => [ 'type' => 'string', 'default' => 'none' ],
			'columnWidth' => [ 'type' => 'string', 'default' => 'auto' ],

			'headerTextAlign' => [ 'type' => 'string', 'default' => 'left' ],
			'headerTextColor' => [ 'type' => 'string', 'default' => '#1f2937' ],
			'headerBgColor' => [ 'type' => 'string', 'default' => '#f4f4f5' ],
			'headerBorderColor' => [ 'type' => 'string', 'default' => '#d1d5db' ],
			'headerBorderWidth' => [ 'type' => 'number', 'default' => 1 ],
			'headerBorderRadius' => [ 'type' => 'number', 'default' => 0 ],
			'headerPadding' => [ 'type' => 'string', 'default' => '10px' ],
			'headerFontSize' => [ 'type' => 'string', 'default' => '16px' ],
			'headerFontWeight' => [ 'type' => 'string', 'default' => '600' ],

			'bodyTextAlign' => [ 'type' => 'string', 'default' => 'left' ],
			'bodyTextColor' => [ 'type' => 'string', 'default' => '#111827' ],
			'bodyBgColor' => [ 'type' => 'string', 'default' => '#ffffff' ],
			'bodyBorderColor' => [ 'type' => 'string', 'default' => '#d1d5db' ],
			'bodyBorderWidth' => [ 'type' => 'number', 'default' => 1 ],
			'bodyBorderRadius' => [ 'type' => 'number', 'default' => 0 ],
			'bodyPadding' => [ 'type' => 'string', 'default' => '10px' ],
			'bodyFontSize' => [ 'type' => 'string', 'default' => '15px' ],
			'bodyFontWeight' => [ 'type' => 'string', 'default' => '400' ],

			'searchInputBgColor' => [ 'type' => 'string', 'default' => '#ffffff' ],
			'searchInputTextColor' => [ 'type' => 'string', 'default' => '#111827' ],
			'searchInputBorderColor' => [ 'type' => 'string', 'default' => '#d1d5db' ],
			'searchInputBorderWidth' => [ 'type' => 'number', 'default' => 1 ],
			'searchInputBorderRadius' => [ 'type' => 'number', 'default' => 4 ],
			'searchInputPadding' => [ 'type' => 'string', 'default' => '8px 10px' ],
			'searchInputMargin' => [ 'type' => 'string', 'default' => '0 0 10px 0' ],
			'searchInputFontSize' => [ 'type' => 'string', 'default' => '14px' ],
			'searchInputWidth' => [ 'type' => 'string', 'default' => '220px' ],

			'imageWidth' => [ 'type' => 'string', 'default' => 'auto' ],
			'imageHeight' => [ 'type' => 'string', 'default' => 'auto' ],
			'imageBorderColor' => [ 'type' => 'string', 'default' => '#d1d5db' ],
			'imageBorderWidth' => [ 'type' => 'number', 'default' => 0 ],
			'imageBorderRadius' => [ 'type' => 'number', 'default' => 0 ],

			// Backward compatibility with earlier block attributes.
			'textAlign' => [ 'type' => 'string', 'default' => 'left' ],
			'borderColor' => [ 'type' => 'string', 'default' => '#d1d5db' ],
			'borderWidth' => [ 'type' => 'number', 'default' => 1 ],
			'cellPadding' => [ 'type' => 'string', 'default' => '10px' ],
		];
	}

	private function normalize_cells( $cells, $rows, $columns ) {
		$normalized = [];
		for ( $r = 0; $r < $rows; $r++ ) {
			$normalized_row = [];
			for ( $c = 0; $c < $columns; $c++ ) {
				$normalized_row[] = isset( $cells[ $r ][ $c ] ) ? $cells[ $r ][ $c ] : '';
			}
			$normalized[] = $normalized_row;
		}

		return $normalized;
	}

	private function sanitize_css_size( $value, $default, $allow_auto = false ) {
		$value = trim( (string) $value );
		if ( $allow_auto && 'auto' === $value ) {
			return $value;
		}
		if ( preg_match( '/^[0-9]+(\.[0-9]+)?(px|em|rem|%)$/', $value ) ) {
			return $value;
		}

		return $default;
	}

	private function sanitize_css_spacing( $value, $default ) {
		$value = trim( (string) $value );
		if ( preg_match( '/^([0-9]+(\.[0-9]+)?(px|em|rem|%))(\s+([0-9]+(\.[0-9]+)?(px|em|rem|%))){0,3}$/', $value ) ) {
			return $value;
		}

		return $default;
	}

	private function sanitize_css_shadow( $value, $default ) {
		$value = trim( (string) $value );
		if ( 'none' === strtolower( $value ) ) {
			return 'none';
		}

		if ( preg_match( '/^[#(),.%\s\-0-9a-zA-Z]+$/', $value ) ) {
			return $value;
		}

		return $default;
	}

	public function render( $attributes ) {
		$defaults = [];
		foreach ( $this->get_attributes_schema() as $key => $schema ) {
			$defaults[ $key ] = $schema['default'];
		}
		$settings = wp_parse_args( $attributes, $defaults );

		$rows    = max( 1, min( 30, (int) $settings['rows'] ) );
		$columns = max( 1, min( 10, (int) $settings['columns'] ) );
		$cells   = $this->normalize_cells( is_array( $settings['cells'] ) ? $settings['cells'] : [], $rows, $columns );

		$legacy_align = in_array( $settings['textAlign'], [ 'left', 'center', 'right' ], true ) ? $settings['textAlign'] : 'left';
		$heading_row  = ! empty( $settings['headingRow'] );
		$enable_search = ! empty( $settings['enableSearch'] );

		$search_placeholder = sanitize_text_field( $settings['searchPlaceholder'] );
		$search_align_map = [ 'left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end' ];
		$search_alignment = isset( $search_align_map[ $settings['searchAlignment'] ] ) ? $search_align_map[ $settings['searchAlignment'] ] : 'flex-end';

		$table_bg = sanitize_hex_color( $settings['tableBgColor'] ) ?: '#ffffff';
		$table_border_color = sanitize_hex_color( $settings['tableBorderColor'] ) ?: ( sanitize_hex_color( $settings['borderColor'] ) ?: '#d1d5db' );
		$table_border_width = max( 0, min( 20, (int) ( isset( $attributes['tableBorderWidth'] ) ? $settings['tableBorderWidth'] : $settings['borderWidth'] ) ) ) . 'px';
		$table_border_radius = max( 0, min( 100, (int) $settings['tableBorderRadius'] ) ) . 'px';
		$table_padding = $this->sanitize_css_spacing( $settings['tablePadding'], '0px' );
		$table_margin = $this->sanitize_css_spacing( $settings['tableMargin'], '0px' );
		$table_box_shadow = $this->sanitize_css_shadow( $settings['tableBoxShadow'], 'none' );
		$column_width = $this->sanitize_css_size( $settings['columnWidth'], 'auto', true );

		$header_text_align = in_array( $settings['headerTextAlign'], [ 'left', 'center', 'right' ], true ) ? $settings['headerTextAlign'] : $legacy_align;
		$header_text = sanitize_hex_color( $settings['headerTextColor'] ) ?: '#1f2937';
		$header_bg = sanitize_hex_color( $settings['headerBgColor'] ) ?: '#f4f4f5';
		$header_border_color = sanitize_hex_color( $settings['headerBorderColor'] ) ?: '#d1d5db';
		$header_border_width = max( 0, min( 20, (int) $settings['headerBorderWidth'] ) ) . 'px';
		$header_border_radius = max( 0, min( 100, (int) $settings['headerBorderRadius'] ) ) . 'px';
		$header_padding = $this->sanitize_css_spacing( $settings['headerPadding'], '10px' );
		$header_font_size = $this->sanitize_css_size( $settings['headerFontSize'], '16px' );
		$header_font_weight = in_array( (string) $settings['headerFontWeight'], [ '300', '400', '500', '600', '700', '800', '900' ], true ) ? (string) $settings['headerFontWeight'] : '600';

		$body_text_align = in_array( $settings['bodyTextAlign'], [ 'left', 'center', 'right' ], true ) ? $settings['bodyTextAlign'] : $legacy_align;
		$body_text = sanitize_hex_color( $settings['bodyTextColor'] ) ?: '#111827';
		$body_bg = sanitize_hex_color( $settings['bodyBgColor'] ) ?: '#ffffff';
		$body_border_color = sanitize_hex_color( $settings['bodyBorderColor'] ) ?: '#d1d5db';
		$body_border_width = max( 0, min( 20, (int) $settings['bodyBorderWidth'] ) ) . 'px';
		$body_border_radius = max( 0, min( 100, (int) $settings['bodyBorderRadius'] ) ) . 'px';
		$body_padding = $this->sanitize_css_spacing( isset( $attributes['bodyPadding'] ) ? $settings['bodyPadding'] : $settings['cellPadding'], '10px' );
		$body_font_size = $this->sanitize_css_size( $settings['bodyFontSize'], '15px' );
		$body_font_weight = in_array( (string) $settings['bodyFontWeight'], [ '300', '400', '500', '600', '700', '800', '900' ], true ) ? (string) $settings['bodyFontWeight'] : '400';

		$search_input_bg = sanitize_hex_color( $settings['searchInputBgColor'] ) ?: '#ffffff';
		$search_input_text = sanitize_hex_color( $settings['searchInputTextColor'] ) ?: '#111827';
		$search_input_border_color = sanitize_hex_color( $settings['searchInputBorderColor'] ) ?: '#d1d5db';
		$search_input_border_width = max( 0, min( 20, (int) $settings['searchInputBorderWidth'] ) ) . 'px';
		$search_input_border_radius = max( 0, min( 100, (int) $settings['searchInputBorderRadius'] ) ) . 'px';
		$search_input_padding = $this->sanitize_css_spacing( $settings['searchInputPadding'], '8px 10px' );
		$search_input_margin = $this->sanitize_css_spacing( $settings['searchInputMargin'], '0 0 10px 0' );
		$search_input_font_size = $this->sanitize_css_size( $settings['searchInputFontSize'], '14px' );
		$search_input_width = $this->sanitize_css_size( $settings['searchInputWidth'], '220px', true );

		$image_width = $this->sanitize_css_size( $settings['imageWidth'], 'auto', true );
		$image_height = $this->sanitize_css_size( $settings['imageHeight'], 'auto', true );
		$image_border_color = sanitize_hex_color( $settings['imageBorderColor'] ) ?: '#d1d5db';
		$image_border_width = max( 0, min( 20, (int) $settings['imageBorderWidth'] ) ) . 'px';
		$image_border_radius = max( 0, min( 100, (int) $settings['imageBorderRadius'] ) ) . 'px';

		$style_vars = [
			'--tablentor-search-align:' . $search_alignment,
			'--tablentor-table-bg:' . $table_bg,
			'--tablentor-table-border-color:' . $table_border_color,
			'--tablentor-table-border-width:' . $table_border_width,
			'--tablentor-table-border-radius:' . $table_border_radius,
			'--tablentor-table-padding:' . $table_padding,
			'--tablentor-table-margin:' . $table_margin,
			'--tablentor-table-box-shadow:' . $table_box_shadow,
			'--tablentor-column-width:' . $column_width,
			'--tablentor-header-align:' . $header_text_align,
			'--tablentor-header-text:' . $header_text,
			'--tablentor-header-bg:' . $header_bg,
			'--tablentor-header-border-color:' . $header_border_color,
			'--tablentor-header-border-width:' . $header_border_width,
			'--tablentor-header-border-radius:' . $header_border_radius,
			'--tablentor-header-padding:' . $header_padding,
			'--tablentor-header-font-size:' . $header_font_size,
			'--tablentor-header-font-weight:' . $header_font_weight,
			'--tablentor-body-align:' . $body_text_align,
			'--tablentor-body-text:' . $body_text,
			'--tablentor-body-bg:' . $body_bg,
			'--tablentor-body-border-color:' . $body_border_color,
			'--tablentor-body-border-width:' . $body_border_width,
			'--tablentor-body-border-radius:' . $body_border_radius,
			'--tablentor-body-padding:' . $body_padding,
			'--tablentor-body-font-size:' . $body_font_size,
			'--tablentor-body-font-weight:' . $body_font_weight,
			'--tablentor-search-bg:' . $search_input_bg,
			'--tablentor-search-text:' . $search_input_text,
			'--tablentor-search-border-color:' . $search_input_border_color,
			'--tablentor-search-border-width:' . $search_input_border_width,
			'--tablentor-search-border-radius:' . $search_input_border_radius,
			'--tablentor-search-padding:' . $search_input_padding,
			'--tablentor-search-margin:' . $search_input_margin,
			'--tablentor-search-font-size:' . $search_input_font_size,
			'--tablentor-search-width:' . $search_input_width,
			'--tablentor-image-width:' . $image_width,
			'--tablentor-image-height:' . $image_height,
			'--tablentor-image-border-color:' . $image_border_color,
			'--tablentor-image-border-width:' . $image_border_width,
			'--tablentor-image-border-radius:' . $image_border_radius,
		];

		$container_id = wp_unique_id( 'tablentor-bt-block-' );
		ob_start();
		?>
		<div id="<?php echo esc_attr( $container_id ); ?>" class="wp-block-tablentor-basic-table ct-basic-table-container" style="<?php echo esc_attr( implode( ';', $style_vars ) ); ?>">
			<?php if ( $enable_search ) : ?>
				<div class="tablentor-bt-search">
					<input class="tablentor-bt-search-input" type="text" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" />
				</div>
			<?php endif; ?>
			<table class="ct-basic-table">
				<?php if ( $heading_row ) : ?>
					<thead>
						<tr>
							<?php for ( $col = 0; $col < $columns; $col++ ) : ?>
								<th><?php echo wp_kses_post( $cells[0][ $col ] ); ?></th>
							<?php endfor; ?>
						</tr>
					</thead>
				<?php endif; ?>
				<tbody>
					<?php
					$start_row = $heading_row ? 1 : 0;
					for ( $row = $start_row; $row < $rows; $row++ ) :
						?>
						<tr>
							<?php for ( $col = 0; $col < $columns; $col++ ) : ?>
								<td><?php echo wp_kses_post( $cells[ $row ][ $col ] ); ?></td>
							<?php endfor; ?>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>
		<?php

		return ob_get_clean();
	}
}
