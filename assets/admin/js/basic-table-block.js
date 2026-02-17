( function ( blocks, i18n, element, components, blockEditor ) {
	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var RichText = blockEditor.RichText;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var RangeControl = components.RangeControl;
	var ToggleControl = components.ToggleControl;
	var TextControl = components.TextControl;
	var ColorPalette = components.ColorPalette;
	var SelectControl = components.SelectControl;

	function normalizeCells( cells, rows, columns ) {
		var source = Array.isArray( cells ) ? cells : [];
		var normalized = [];
		var r;
		var c;

		for ( r = 0; r < rows; r++ ) {
			var nextRow = [];
			var sourceRow = Array.isArray( source[ r ] ) ? source[ r ] : [];
			for ( c = 0; c < columns; c++ ) {
				nextRow.push( sourceRow[ c ] || '' );
			}
			normalized.push( nextRow );
		}

		return normalized;
	}

	blocks.registerBlockType( 'tablentor/basic-table', {
		apiVersion: 2,
		title: __( 'Basic Table', 'tablentor' ),
		description: __( 'Build and design a table directly in Gutenberg.', 'tablentor' ),
		icon: 'table-col-after',
		category: 'widgets',
		attributes: {
			rows: { type: 'number', default: 3 },
			columns: { type: 'number', default: 3 },
			cells: { type: 'array', default: [] },
			headingRow: { type: 'boolean', default: true },
			enableSearch: { type: 'boolean', default: false },
			searchPlaceholder: { type: 'string', default: 'Search' },
			textAlign: { type: 'string', default: 'left' },
			headerBgColor: { type: 'string', default: '#f4f4f5' },
			headerTextColor: { type: 'string', default: '#1f2937' },
			bodyBgColor: { type: 'string', default: '#ffffff' },
			bodyTextColor: { type: 'string', default: '#111827' },
			borderColor: { type: 'string', default: '#d1d5db' },
			cellPadding: { type: 'string', default: '10px' }
		},
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;
			var rows = Math.max( 1, Math.min( 30, parseInt( attributes.rows, 10 ) || 1 ) );
			var columns = Math.max( 1, Math.min( 10, parseInt( attributes.columns, 10 ) || 1 ) );
			var matrix = normalizeCells( attributes.cells, rows, columns );

			function updateSize( nextRows, nextColumns ) {
				setAttributes( {
					rows: nextRows,
					columns: nextColumns,
					cells: normalizeCells( matrix, nextRows, nextColumns )
				} );
			}

			function updateCell( rowIndex, colIndex, value ) {
				var next = normalizeCells( matrix, rows, columns );
				next[ rowIndex ][ colIndex ] = value;
				setAttributes( { cells: next } );
			}

			var headerCells = [];
			var bodyRows = [];
			var rowIndex;
			var colIndex;

			for ( rowIndex = 0; rowIndex < rows; rowIndex++ ) {
				var isHeadingRow = attributes.headingRow && rowIndex === 0;
				var columnsEl = [];

				for ( colIndex = 0; colIndex < columns; colIndex++ ) {
					var tagName = isHeadingRow ? 'th' : 'td';
					columnsEl.push(
						el( RichText, {
							key: 'cell-' + rowIndex + '-' + colIndex,
							tagName: tagName,
							value: matrix[ rowIndex ][ colIndex ],
							placeholder: __( 'Write...', 'tablentor' ),
							allowedFormats: [ 'core/bold', 'core/italic', 'core/link' ],
							onChange: ( function ( r, c ) {
								return function ( value ) {
									updateCell( r, c, value );
								};
							}( rowIndex, colIndex ) )
						} )
					);
				}

				if ( isHeadingRow ) {
					headerCells = columnsEl;
				} else {
					bodyRows.push(
						el( 'tr', { key: 'row-' + rowIndex }, columnsEl )
					);
				}
			}

			var blockProps = useBlockProps( {
				className: 'wp-block-tablentor-basic-table ct-basic-table-container',
				style: {
					'--tablentor-header-bg': attributes.headerBgColor,
					'--tablentor-header-text': attributes.headerTextColor,
					'--tablentor-body-bg': attributes.bodyBgColor,
					'--tablentor-body-text': attributes.bodyTextColor,
					'--tablentor-border': attributes.borderColor,
					'--tablentor-align': attributes.textAlign,
					'--tablentor-cell-padding': attributes.cellPadding
				}
			} );

			return el(
				element.Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Content', 'tablentor' ), initialOpen: true },
						el( RangeControl, {
							label: __( 'Rows', 'tablentor' ),
							min: 1,
							max: 30,
							value: rows,
							onChange: function ( value ) {
								updateSize( value, columns );
							}
						} ),
						el( RangeControl, {
							label: __( 'Columns', 'tablentor' ),
							min: 1,
							max: 10,
							value: columns,
							onChange: function ( value ) {
								updateSize( rows, value );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Use first row as heading', 'tablentor' ),
							checked: !! attributes.headingRow,
							onChange: function ( value ) {
								setAttributes( { headingRow: value } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Enable search', 'tablentor' ),
							checked: !! attributes.enableSearch,
							onChange: function ( value ) {
								setAttributes( { enableSearch: value } );
							}
						} ),
						attributes.enableSearch
							? el( TextControl, {
								label: __( 'Search placeholder', 'tablentor' ),
								value: attributes.searchPlaceholder,
								onChange: function ( value ) {
									setAttributes( { searchPlaceholder: value } );
								}
							} )
							: null
					),
					el(
						PanelBody,
						{ title: __( 'Style', 'tablentor' ), initialOpen: false },
						el( SelectControl, {
							label: __( 'Text alignment', 'tablentor' ),
							value: attributes.textAlign,
							options: [
								{ label: __( 'Left', 'tablentor' ), value: 'left' },
								{ label: __( 'Center', 'tablentor' ), value: 'center' },
								{ label: __( 'Right', 'tablentor' ), value: 'right' }
							],
							onChange: function ( value ) {
								setAttributes( { textAlign: value } );
							}
						} ),
						el( TextControl, {
							label: __( 'Cell padding (e.g. 10px)', 'tablentor' ),
							value: attributes.cellPadding,
							onChange: function ( value ) {
								setAttributes( { cellPadding: value } );
							}
						} ),
						el( 'p', null, __( 'Header background', 'tablentor' ) ),
						el( ColorPalette, {
							value: attributes.headerBgColor,
							onChange: function ( value ) {
								setAttributes( { headerBgColor: value || '#f4f4f5' } );
							}
						} ),
						el( 'p', null, __( 'Header text color', 'tablentor' ) ),
						el( ColorPalette, {
							value: attributes.headerTextColor,
							onChange: function ( value ) {
								setAttributes( { headerTextColor: value || '#1f2937' } );
							}
						} ),
						el( 'p', null, __( 'Body background', 'tablentor' ) ),
						el( ColorPalette, {
							value: attributes.bodyBgColor,
							onChange: function ( value ) {
								setAttributes( { bodyBgColor: value || '#ffffff' } );
							}
						} ),
						el( 'p', null, __( 'Body text color', 'tablentor' ) ),
						el( ColorPalette, {
							value: attributes.bodyTextColor,
							onChange: function ( value ) {
								setAttributes( { bodyTextColor: value || '#111827' } );
							}
						} ),
						el( 'p', null, __( 'Border color', 'tablentor' ) ),
						el( ColorPalette, {
							value: attributes.borderColor,
							onChange: function ( value ) {
								setAttributes( { borderColor: value || '#d1d5db' } );
							}
						} )
					)
				),
				el(
					'div',
					blockProps,
					attributes.enableSearch
						? el(
							'div',
							{ className: 'tablentor-bt-search' },
							el( 'input', {
								className: 'tablentor-bt-search-input',
								type: 'text',
								placeholder: attributes.searchPlaceholder || __( 'Search', 'tablentor' ),
								disabled: true
							} )
						)
						: null,
					el(
						'table',
						{ className: 'ct-basic-table' },
						attributes.headingRow && headerCells.length
							? el( 'thead', null, el( 'tr', null, headerCells ) )
							: null,
						el( 'tbody', null, bodyRows )
					)
				)
			);
		},
		save: function () {
			return null;
		}
	} );
}( window.wp.blocks, window.wp.i18n, window.wp.element, window.wp.components, window.wp.blockEditor ) );
