( function ( $ ) {
	'use strict';

	/**
	 * Editor view for the `tablentor-csv-media` control.
	 *
	 * Extends Elementor's Media control view and enforces CSV-only:
	 *  - upload picker limited to `.csv` (plupload extension filter)
	 *  - non-CSV selections (library or "Insert from URL") are rejected
	 *
	 * The PHP control (Control_Csv_Media) already filters the library grid
	 * to `text/csv`; this view covers the upload tab + URL tab gaps.
	 */
	function registerCsvMediaControl() {
		if ( ! window.elementor || ! elementor.modules || ! elementor.modules.controls ) {
			return;
		}

		var MediaControlView = elementor.modules.controls.Media;

		if ( ! MediaControlView ) {
			return;
		}

		var CsvMediaControlView = MediaControlView.extend( {

			initFrame: function () {
				MediaControlView.prototype.initFrame.apply( this, arguments );
				this.restrictUploadToCsv();
			},

			// Limit the upload file-picker to .csv while this frame is open.
			restrictUploadToCsv: function () {
				if ( ! window._wpPluploadSettings || ! _wpPluploadSettings.defaults ) {
					return;
				}

				var pluploadFilter = _wpPluploadSettings.defaults.filters.mime_types[ 0 ];
				var originalExtensions = pluploadFilter.extensions;

				this.frame.on( 'ready', function () {
					pluploadFilter.extensions = 'csv';
				} );

				this.frame.on( 'close', function () {
					pluploadFilter.extensions = originalExtensions;
				} );
			},

			// Validate before the base view stores the value.
			select: function () {
				var state = this.frame.state();
				var isCsv;

				if ( 'embed' === state.get( 'id' ) ) {
					isCsv = this.isCsvUrl( state.props.get( 'url' ) );
				} else {
					var attachment = state.get( 'selection' ).first();
					isCsv = attachment ? this.isCsvAttachment( attachment ) : false;
				}

				if ( ! isCsv ) {
					this.showCsvError();
					return;
				}

				MediaControlView.prototype.select.apply( this, arguments );
			},

			isCsvAttachment: function ( attachment ) {
				var subtype = ( attachment.get( 'subtype' ) || '' ).toLowerCase();
				var name = ( attachment.get( 'filename' ) || attachment.get( 'url' ) || '' );

				return 'csv' === subtype || this.isCsvUrl( name );
			},

			isCsvUrl: function ( url ) {
				url = ( url || '' ).split( '?' )[ 0 ].split( '#' )[ 0 ];
				return /\.csv$/i.test( url );
			},

			showCsvError: function () {
				var message = 'Only CSV (.csv) files are allowed.';

				if ( window.elementorCommon && elementorCommon.dialogsManager ) {
					elementorCommon.dialogsManager.createWidget( 'alert', {
						message: message,
					} ).show();
				} else {
					window.alert( message );
				}
			},
		} );

		elementor.addControlView( 'tablentor-csv-media', CsvMediaControlView );
	}

	$( window ).on( 'elementor:init', registerCsvMediaControl );
} )( jQuery );
