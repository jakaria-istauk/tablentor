( function () {
	function initBasicTableSearch( container ) {
		var input = container.querySelector( '.tablentor-bt-search-input' );
		var table = container.querySelector( '.ct-basic-table' );

		if ( ! input || ! table ) {
			return;
		}

		var rows = table.querySelectorAll( 'tbody tr' );
		if ( ! rows.length ) {
			rows = table.querySelectorAll( 'tr' );
		}

		input.addEventListener( 'keyup', function () {
			var value = input.value.toLowerCase();
			rows.forEach( function ( row ) {
				var text = row.textContent.toLowerCase();
				row.style.display = text.indexOf( value ) > -1 ? '' : 'none';
			} );
		} );
	}

	function initAll() {
		var tables = document.querySelectorAll( '.wp-block-tablentor-basic-table' );
		tables.forEach( function ( container ) {
			initBasicTableSearch( container );
		} );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
}() );
