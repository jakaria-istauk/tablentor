<?php
if( !function_exists('ct_pri') ):
function ct_pri( $data ) {
	echo '<pre>';
	if( is_object( $data ) || is_array( $data ) ) {
		print_r( $data );
	}
	else {
		var_dump( $data );
	}
	echo '</pre>';
}
endif;

if( !function_exists('tablentor_widgets_list') ):
function tablentor_widgets_list() {

	return [
		'basic-table' => [
			'class' => 'Jakaria\\Tablentor\\Basic_Table',
			'path'  => CMPRTBL_DIR . "/widgets/basic-table/basic-table.php"
		],
		'span-table' => [
			'class' => 'Jakaria\\Tablentor\\Span_Table',
			'path'  => CMPRTBL_DIR . "/widgets/span-table.php"
		]
	];
}
endif;