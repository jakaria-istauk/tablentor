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

	function n( value, fallback ) {
		return value !== undefined && value !== null ? value : fallback;
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
			searchAlignment: { type: 'string', default: 'right' },

			tableBgColor: { type: 'string', default: '#ffffff' },
			tableBorderColor: { type: 'string', default: '#d1d5db' },
			tableBorderWidth: { type: 'number', default: 1 },
			tableBorderRadius: { type: 'number', default: 0 },
			tablePadding: { type: 'string', default: '0px' },
			tableMargin: { type: 'string', default: '0px' },
			tableBoxShadow: { type: 'string', default: 'none' },
			columnWidth: { type: 'string', default: 'auto' },

			headerTextAlign: { type: 'string', default: 'left' },
			headerTextColor: { type: 'string', default: '#1f2937' },
			headerBgColor: { type: 'string', default: '#f4f4f5' },
			headerBorderColor: { type: 'string', default: '#d1d5db' },
			headerBorderWidth: { type: 'number', default: 1 },
			headerBorderRadius: { type: 'number', default: 0 },
			headerPadding: { type: 'string', default: '10px' },
			headerFontSize: { type: 'string', default: '16px' },
			headerFontWeight: { type: 'string', default: '600' },

			bodyTextAlign: { type: 'string', default: 'left' },
			bodyTextColor: { type: 'string', default: '#111827' },
			bodyBgColor: { type: 'string', default: '#ffffff' },
			bodyBorderColor: { type: 'string', default: '#d1d5db' },
			bodyBorderWidth: { type: 'number', default: 1 },
			bodyBorderRadius: { type: 'number', default: 0 },
			bodyPadding: { type: 'string', default: '10px' },
			bodyFontSize: { type: 'string', default: '15px' },
			bodyFontWeight: { type: 'string', default: '400' },

			searchInputBgColor: { type: 'string', default: '#ffffff' },
			searchInputTextColor: { type: 'string', default: '#111827' },
			searchInputBorderColor: { type: 'string', default: '#d1d5db' },
			searchInputBorderWidth: { type: 'number', default: 1 },
			searchInputBorderRadius: { type: 'number', default: 4 },
			searchInputPadding: { type: 'string', default: '8px 10px' },
			searchInputMargin: { type: 'string', default: '0 0 10px 0' },
			searchInputFontSize: { type: 'string', default: '14px' },
			searchInputWidth: { type: 'string', default: '220px' },

			imageWidth: { type: 'string', default: 'auto' },
			imageHeight: { type: 'string', default: 'auto' },
			imageBorderColor: { type: 'string', default: '#d1d5db' },
			imageBorderWidth: { type: 'number', default: 0 },
			imageBorderRadius: { type: 'number', default: 0 }
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
					columnsEl.push(
						el( RichText, {
							key: 'cell-' + rowIndex + '-' + colIndex,
							tagName: isHeadingRow ? 'th' : 'td',
							value: matrix[ rowIndex ][ colIndex ],
							placeholder: __( 'Write...', 'tablentor' ),
							allowedFormats: [ 'core/bold', 'core/italic', 'core/link', 'core/image' ],
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
					bodyRows.push( el( 'tr', { key: 'row-' + rowIndex }, columnsEl ) );
				}
			}

			var blockProps = useBlockProps( {
				className: 'wp-block-tablentor-basic-table ct-basic-table-container',
				style: {
					'--tablentor-search-align': attributes.searchAlignment === 'left' ? 'flex-start' : ( attributes.searchAlignment === 'center' ? 'center' : 'flex-end' ),
					'--tablentor-table-bg': attributes.tableBgColor,
					'--tablentor-table-border-color': attributes.tableBorderColor,
					'--tablentor-table-border-width': n( attributes.tableBorderWidth, 1 ) + 'px',
					'--tablentor-table-border-radius': n( attributes.tableBorderRadius, 0 ) + 'px',
					'--tablentor-table-padding': attributes.tablePadding,
					'--tablentor-table-margin': attributes.tableMargin,
					'--tablentor-table-box-shadow': attributes.tableBoxShadow,
					'--tablentor-column-width': attributes.columnWidth,

					'--tablentor-header-align': attributes.headerTextAlign,
					'--tablentor-header-text': attributes.headerTextColor,
					'--tablentor-header-bg': attributes.headerBgColor,
					'--tablentor-header-border-color': attributes.headerBorderColor,
					'--tablentor-header-border-width': n( attributes.headerBorderWidth, 1 ) + 'px',
					'--tablentor-header-border-radius': n( attributes.headerBorderRadius, 0 ) + 'px',
					'--tablentor-header-padding': attributes.headerPadding,
					'--tablentor-header-font-size': attributes.headerFontSize,
					'--tablentor-header-font-weight': attributes.headerFontWeight,

					'--tablentor-body-align': attributes.bodyTextAlign,
					'--tablentor-body-text': attributes.bodyTextColor,
					'--tablentor-body-bg': attributes.bodyBgColor,
					'--tablentor-body-border-color': attributes.bodyBorderColor,
					'--tablentor-body-border-width': n( attributes.bodyBorderWidth, 1 ) + 'px',
					'--tablentor-body-border-radius': n( attributes.bodyBorderRadius, 0 ) + 'px',
					'--tablentor-body-padding': attributes.bodyPadding,
					'--tablentor-body-font-size': attributes.bodyFontSize,
					'--tablentor-body-font-weight': attributes.bodyFontWeight,

					'--tablentor-search-bg': attributes.searchInputBgColor,
					'--tablentor-search-text': attributes.searchInputTextColor,
					'--tablentor-search-border-color': attributes.searchInputBorderColor,
					'--tablentor-search-border-width': n( attributes.searchInputBorderWidth, 1 ) + 'px',
					'--tablentor-search-border-radius': n( attributes.searchInputBorderRadius, 4 ) + 'px',
					'--tablentor-search-padding': attributes.searchInputPadding,
					'--tablentor-search-margin': attributes.searchInputMargin,
					'--tablentor-search-font-size': attributes.searchInputFontSize,
					'--tablentor-search-width': attributes.searchInputWidth,

					'--tablentor-image-width': attributes.imageWidth,
					'--tablentor-image-height': attributes.imageHeight,
					'--tablentor-image-border-color': attributes.imageBorderColor,
					'--tablentor-image-border-width': n( attributes.imageBorderWidth, 0 ) + 'px',
					'--tablentor-image-border-radius': n( attributes.imageBorderRadius, 0 ) + 'px'
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
						{ title: __( 'General', 'tablentor' ), initialOpen: true },
						el( RangeControl, {
							label: __( 'Rows', 'tablentor' ),
							min: 1,
							max: 30,
							value: rows,
							onChange: function ( value ) { updateSize( value, columns ); }
						} ),
						el( RangeControl, {
							label: __( 'Columns', 'tablentor' ),
							min: 1,
							max: 10,
							value: columns,
							onChange: function ( value ) { updateSize( rows, value ); }
						} ),
						el( ToggleControl, {
							label: __( 'Use first row as heading', 'tablentor' ),
							checked: !! attributes.headingRow,
							onChange: function ( value ) { setAttributes( { headingRow: value } ); }
						} ),
						el( ToggleControl, {
							label: __( 'Enable search', 'tablentor' ),
							checked: !! attributes.enableSearch,
							onChange: function ( value ) { setAttributes( { enableSearch: value } ); }
						} ),
						attributes.enableSearch ? el( TextControl, {
							label: __( 'Search placeholder', 'tablentor' ),
							value: attributes.searchPlaceholder,
							onChange: function ( value ) { setAttributes( { searchPlaceholder: value } ); }
						} ) : null,
						attributes.enableSearch ? el( SelectControl, {
							label: __( 'Search alignment', 'tablentor' ),
							value: attributes.searchAlignment,
							options: [
								{ label: __( 'Left', 'tablentor' ), value: 'left' },
								{ label: __( 'Center', 'tablentor' ), value: 'center' },
								{ label: __( 'Right', 'tablentor' ), value: 'right' }
							],
							onChange: function ( value ) { setAttributes( { searchAlignment: value } ); }
						} ) : null
					),
					el(
						PanelBody,
						{ title: __( 'Search Input', 'tablentor' ), initialOpen: false },
						el( TextControl, { label: __( 'Width', 'tablentor' ), value: attributes.searchInputWidth, onChange: function ( v ) { setAttributes( { searchInputWidth: v } ); } } ),
						el( TextControl, { label: __( 'Padding', 'tablentor' ), value: attributes.searchInputPadding, onChange: function ( v ) { setAttributes( { searchInputPadding: v } ); } } ),
						el( TextControl, { label: __( 'Margin', 'tablentor' ), value: attributes.searchInputMargin, onChange: function ( v ) { setAttributes( { searchInputMargin: v } ); } } ),
						el( TextControl, { label: __( 'Font size', 'tablentor' ), value: attributes.searchInputFontSize, onChange: function ( v ) { setAttributes( { searchInputFontSize: v } ); } } ),
						el( RangeControl, { label: __( 'Border width', 'tablentor' ), min: 0, max: 20, value: n( attributes.searchInputBorderWidth, 1 ), onChange: function ( v ) { setAttributes( { searchInputBorderWidth: v || 0 } ); } } ),
						el( RangeControl, { label: __( 'Border radius', 'tablentor' ), min: 0, max: 100, value: n( attributes.searchInputBorderRadius, 4 ), onChange: function ( v ) { setAttributes( { searchInputBorderRadius: v || 0 } ); } } ),
						el( 'p', null, __( 'Background', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.searchInputBgColor, onChange: function ( v ) { setAttributes( { searchInputBgColor: v || '#ffffff' } ); } } ),
						el( 'p', null, __( 'Text color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.searchInputTextColor, onChange: function ( v ) { setAttributes( { searchInputTextColor: v || '#111827' } ); } } ),
						el( 'p', null, __( 'Border color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.searchInputBorderColor, onChange: function ( v ) { setAttributes( { searchInputBorderColor: v || '#d1d5db' } ); } } )
					),
					el(
						PanelBody,
						{ title: __( 'Table Styling', 'tablentor' ), initialOpen: false },
						el( TextControl, { label: __( 'Column width', 'tablentor' ), value: attributes.columnWidth, onChange: function ( v ) { setAttributes( { columnWidth: v } ); } } ),
						el( TextControl, { label: __( 'Table padding', 'tablentor' ), value: attributes.tablePadding, onChange: function ( v ) { setAttributes( { tablePadding: v } ); } } ),
						el( TextControl, { label: __( 'Table margin', 'tablentor' ), value: attributes.tableMargin, onChange: function ( v ) { setAttributes( { tableMargin: v } ); } } ),
						el( TextControl, { label: __( 'Box shadow', 'tablentor' ), value: attributes.tableBoxShadow, onChange: function ( v ) { setAttributes( { tableBoxShadow: v } ); } } ),
						el( RangeControl, { label: __( 'Table border width', 'tablentor' ), min: 0, max: 20, value: n( attributes.tableBorderWidth, 1 ), onChange: function ( v ) { setAttributes( { tableBorderWidth: v || 0 } ); } } ),
						el( RangeControl, { label: __( 'Table border radius', 'tablentor' ), min: 0, max: 100, value: n( attributes.tableBorderRadius, 0 ), onChange: function ( v ) { setAttributes( { tableBorderRadius: v || 0 } ); } } ),
						el( 'p', null, __( 'Table background', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.tableBgColor, onChange: function ( v ) { setAttributes( { tableBgColor: v || '#ffffff' } ); } } ),
						el( 'p', null, __( 'Table border color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.tableBorderColor, onChange: function ( v ) { setAttributes( { tableBorderColor: v || '#d1d5db' } ); } } )
					),
					el(
						PanelBody,
						{ title: __( 'Table Heading', 'tablentor' ), initialOpen: false },
						el( SelectControl, { label: __( 'Alignment', 'tablentor' ), value: attributes.headerTextAlign, options: [ { label: __( 'Left', 'tablentor' ), value: 'left' }, { label: __( 'Center', 'tablentor' ), value: 'center' }, { label: __( 'Right', 'tablentor' ), value: 'right' } ], onChange: function ( v ) { setAttributes( { headerTextAlign: v } ); } } ),
						el( TextControl, { label: __( 'Padding', 'tablentor' ), value: attributes.headerPadding, onChange: function ( v ) { setAttributes( { headerPadding: v } ); } } ),
						el( TextControl, { label: __( 'Font size', 'tablentor' ), value: attributes.headerFontSize, onChange: function ( v ) { setAttributes( { headerFontSize: v } ); } } ),
						el( SelectControl, { label: __( 'Font weight', 'tablentor' ), value: attributes.headerFontWeight, options: [ { label: '400', value: '400' }, { label: '500', value: '500' }, { label: '600', value: '600' }, { label: '700', value: '700' } ], onChange: function ( v ) { setAttributes( { headerFontWeight: v } ); } } ),
						el( RangeControl, { label: __( 'Border width', 'tablentor' ), min: 0, max: 20, value: n( attributes.headerBorderWidth, 1 ), onChange: function ( v ) { setAttributes( { headerBorderWidth: v || 0 } ); } } ),
						el( RangeControl, { label: __( 'Border radius', 'tablentor' ), min: 0, max: 100, value: n( attributes.headerBorderRadius, 0 ), onChange: function ( v ) { setAttributes( { headerBorderRadius: v || 0 } ); } } ),
						el( 'p', null, __( 'Text color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.headerTextColor, onChange: function ( v ) { setAttributes( { headerTextColor: v || '#1f2937' } ); } } ),
						el( 'p', null, __( 'Background', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.headerBgColor, onChange: function ( v ) { setAttributes( { headerBgColor: v || '#f4f4f5' } ); } } ),
						el( 'p', null, __( 'Border color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.headerBorderColor, onChange: function ( v ) { setAttributes( { headerBorderColor: v || '#d1d5db' } ); } } )
					),
					el(
						PanelBody,
						{ title: __( 'Table Body', 'tablentor' ), initialOpen: false },
						el( SelectControl, { label: __( 'Alignment', 'tablentor' ), value: attributes.bodyTextAlign, options: [ { label: __( 'Left', 'tablentor' ), value: 'left' }, { label: __( 'Center', 'tablentor' ), value: 'center' }, { label: __( 'Right', 'tablentor' ), value: 'right' } ], onChange: function ( v ) { setAttributes( { bodyTextAlign: v } ); } } ),
						el( TextControl, { label: __( 'Padding', 'tablentor' ), value: attributes.bodyPadding, onChange: function ( v ) { setAttributes( { bodyPadding: v } ); } } ),
						el( TextControl, { label: __( 'Font size', 'tablentor' ), value: attributes.bodyFontSize, onChange: function ( v ) { setAttributes( { bodyFontSize: v } ); } } ),
						el( SelectControl, { label: __( 'Font weight', 'tablentor' ), value: attributes.bodyFontWeight, options: [ { label: '300', value: '300' }, { label: '400', value: '400' }, { label: '500', value: '500' }, { label: '600', value: '600' }, { label: '700', value: '700' } ], onChange: function ( v ) { setAttributes( { bodyFontWeight: v } ); } } ),
						el( RangeControl, { label: __( 'Border width', 'tablentor' ), min: 0, max: 20, value: n( attributes.bodyBorderWidth, 1 ), onChange: function ( v ) { setAttributes( { bodyBorderWidth: v || 0 } ); } } ),
						el( RangeControl, { label: __( 'Border radius', 'tablentor' ), min: 0, max: 100, value: n( attributes.bodyBorderRadius, 0 ), onChange: function ( v ) { setAttributes( { bodyBorderRadius: v || 0 } ); } } ),
						el( 'p', null, __( 'Text color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.bodyTextColor, onChange: function ( v ) { setAttributes( { bodyTextColor: v || '#111827' } ); } } ),
						el( 'p', null, __( 'Background', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.bodyBgColor, onChange: function ( v ) { setAttributes( { bodyBgColor: v || '#ffffff' } ); } } ),
						el( 'p', null, __( 'Border color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.bodyBorderColor, onChange: function ( v ) { setAttributes( { bodyBorderColor: v || '#d1d5db' } ); } } )
					),
					el(
						PanelBody,
						{ title: __( 'Table Images', 'tablentor' ), initialOpen: false },
						el( TextControl, { label: __( 'Image width', 'tablentor' ), value: attributes.imageWidth, onChange: function ( v ) { setAttributes( { imageWidth: v } ); } } ),
						el( TextControl, { label: __( 'Image height', 'tablentor' ), value: attributes.imageHeight, onChange: function ( v ) { setAttributes( { imageHeight: v } ); } } ),
						el( RangeControl, { label: __( 'Border width', 'tablentor' ), min: 0, max: 20, value: n( attributes.imageBorderWidth, 0 ), onChange: function ( v ) { setAttributes( { imageBorderWidth: v || 0 } ); } } ),
						el( RangeControl, { label: __( 'Border radius', 'tablentor' ), min: 0, max: 100, value: n( attributes.imageBorderRadius, 0 ), onChange: function ( v ) { setAttributes( { imageBorderRadius: v || 0 } ); } } ),
						el( 'p', null, __( 'Border color', 'tablentor' ) ),
						el( ColorPalette, { value: attributes.imageBorderColor, onChange: function ( v ) { setAttributes( { imageBorderColor: v || '#d1d5db' } ); } } )
					)
				),
				el(
					'div',
					blockProps,
					attributes.enableSearch ? el(
						'div',
						{ className: 'tablentor-bt-search' },
						el( 'input', {
							className: 'tablentor-bt-search-input',
							type: 'text',
							placeholder: attributes.searchPlaceholder || __( 'Search', 'tablentor' ),
							disabled: true
						} )
					) : null,
					el(
						'table',
						{ className: 'ct-basic-table' },
						attributes.headingRow && headerCells.length ? el( 'thead', null, el( 'tr', null, headerCells ) ) : null,
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
