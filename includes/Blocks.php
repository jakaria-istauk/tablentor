<?php
namespace Jakaria\Tablentor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Blocks {

	public function register_blocks() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

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
				'render_callback' => [ $this, 'render_basic_table_block' ],
			]
		);
	}

	private function get_attributes_schema() {
		return [
			'rows' => [
				'type'    => 'number',
				'default' => 3,
			],
			'columns' => [
				'type'    => 'number',
				'default' => 3,
			],
			'cells' => [
				'type'    => 'array',
				'default' => [],
			],
			'headingRow' => [
				'type'    => 'boolean',
				'default' => true,
			],
			'enableSearch' => [
				'type'    => 'boolean',
				'default' => false,
			],
			'searchPlaceholder' => [
				'type'    => 'string',
				'default' => 'Search',
			],
			'textAlign' => [
				'type'    => 'string',
				'default' => 'left',
			],
			'headerBgColor' => [
				'type'    => 'string',
				'default' => '#f4f4f5',
			],
			'headerTextColor' => [
				'type'    => 'string',
				'default' => '#1f2937',
			],
			'bodyBgColor' => [
				'type'    => 'string',
				'default' => '#ffffff',
			],
			'bodyTextColor' => [
				'type'    => 'string',
				'default' => '#111827',
			],
			'borderColor' => [
				'type'    => 'string',
				'default' => '#d1d5db',
			],
			'cellPadding' => [
				'type'    => 'string',
				'default' => '10px',
			],
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

	private function sanitize_css_size( $value, $default ) {
		$value = trim( (string) $value );
		if ( preg_match( '/^[0-9]+(\.[0-9]+)?(px|em|rem|%)$/', $value ) ) {
			return $value;
		}

		return $default;
	}

	public function render_basic_table_block( $attributes ) {
		$defaults = [];
		foreach ( $this->get_attributes_schema() as $key => $schema ) {
			$defaults[ $key ] = $schema['default'];
		}

		$settings = wp_parse_args( $attributes, $defaults );

		$rows    = max( 1, min( 30, (int) $settings['rows'] ) );
		$columns = max( 1, min( 10, (int) $settings['columns'] ) );
		$cells   = $this->normalize_cells( is_array( $settings['cells'] ) ? $settings['cells'] : [], $rows, $columns );

		$heading_row        = ! empty( $settings['headingRow'] );
		$enable_search      = ! empty( $settings['enableSearch'] );
		$search_placeholder = sanitize_text_field( $settings['searchPlaceholder'] );
		$text_align         = in_array( $settings['textAlign'], [ 'left', 'center', 'right' ], true ) ? $settings['textAlign'] : 'left';
		$header_bg          = sanitize_hex_color( $settings['headerBgColor'] ) ?: '#f4f4f5';
		$header_text        = sanitize_hex_color( $settings['headerTextColor'] ) ?: '#1f2937';
		$body_bg            = sanitize_hex_color( $settings['bodyBgColor'] ) ?: '#ffffff';
		$body_text          = sanitize_hex_color( $settings['bodyTextColor'] ) ?: '#111827';
		$border_color       = sanitize_hex_color( $settings['borderColor'] ) ?: '#d1d5db';
		$cell_padding       = $this->sanitize_css_size( $settings['cellPadding'], '10px' );
		$container_id       = wp_unique_id( 'tablentor-bt-block-' );

		$style = sprintf(
			'--tablentor-header-bg:%1$s;--tablentor-header-text:%2$s;--tablentor-body-bg:%3$s;--tablentor-body-text:%4$s;--tablentor-border:%5$s;--tablentor-align:%6$s;--tablentor-cell-padding:%7$s;',
			esc_attr( $header_bg ),
			esc_attr( $header_text ),
			esc_attr( $body_bg ),
			esc_attr( $body_text ),
			esc_attr( $border_color ),
			esc_attr( $text_align ),
			esc_attr( $cell_padding )
		);

		ob_start();
		?>
		<div id="<?php echo esc_attr( $container_id ); ?>" class="wp-block-tablentor-basic-table ct-basic-table-container" style="<?php echo esc_attr( $style ); ?>">
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
