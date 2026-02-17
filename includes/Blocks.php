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

		$blocks = [
			[
				'path'  => CMPRTBL_DIR . '/blocks/basic-table.php',
				'class' => 'Jakaria\\Tablentor\\Blocks\\Basic_Table',
			],
		];

		foreach ( $blocks as $block ) {
			if ( file_exists( $block['path'] ) ) {
				require_once $block['path'];

				if ( class_exists( $block['class'] ) ) {
					( new $block['class']() )->register();
				}
			}
		}
	}
}
