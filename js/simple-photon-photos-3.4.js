jQuery(function() {
	var is_spp_init = false;

	function spp_init() {
		jQuery('.spp-tr').show(); // Is this needed? It was in beta versions

		// Width Adjustment Slider
		jQuery('.spp-width-slider').each( function() {
			var self = this;
			jQuery(self).slider( {
				value: parseInt( jQuery(self).parents('tr').find('.attachments-spp-width').val() ),
				min: 16,
				max: parseInt( jQuery(self).parents('tr').find('.attachments-spp-width').val() ),
				step: 1,
				slide: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-width').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-width').trigger( 'change' ); // This is needed so the media modal recgonizes the value (attachments_fields_to_save)
				}
			} );
		} );
		jQuery('.attachments-spp-width').on( 'change', function() {
			var val = parseInt( jQuery(this).val() ), slider_max = parseInt( jQuery(this).parents('tr').find('.spp-width-slider').slider( 'option', 'max' ) ), slider_min = parseInt( jQuery(this).parents('tr').find('.spp-width-slider').slider( 'option', 'min' ) );

			if ( val > slider_max ) { // More than max width
				jQuery(this).parents('tr').find('.spp-width-slider').slider( 'value', slider_max );
				jQuery(this).val( slider_max );
			}
			else if ( val < slider_min ) { // Less than min width
				jQuery(this).parents('tr').find('.spp-width-slider').slider( 'value', slider_min );
				jQuery(this).val( slider_min );
			}
			else { // Between min and max width
				jQuery(this).parents('tr').find('.spp-width-slider').slider( 'value', val );
			}
		} );

		// Height Adjustment Slider
		jQuery('.spp-height-slider').each( function() {
			var self = this;
			jQuery(self).slider( {
				value: parseInt( jQuery(self).parents('tr').find('.attachments-spp-height').val() ),
				min: 16,
				max: parseInt( jQuery(self).parents('tr').find('.attachments-spp-height').val() ),
				step: 1,
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-height').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-height').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-height').on( 'change', function() {
			var val = parseInt( jQuery(this).val() ), slider_max = parseInt( jQuery(this).parents('tr').find('.spp-height-slider').slider( 'option', 'max' ) ), slider_min = parseInt( jQuery(this).parents('tr').find('.spp-height-slider').slider( 'option', 'min' ) );

			if ( val > slider_max ) { // More than max height
				jQuery(this).parents('tr').find('.spp-height-slider').slider( 'value', slider_max );
				jQuery(this).val( slider_max );
			}
			else if ( val < slider_min ) { // Less than min height
				jQuery(this).parents('tr').find('.spp-height-slider').slider( 'value', slider_min );
				jQuery(this).val( slider_min );
			}
			else // Between min and max height
				jQuery(this).parents('tr').find('.spp-height-slider').slider( 'value', val );
		} );

		// Resize/Fit Options
		jQuery('.spp-resize-checkbox').on( 'change', function() {
			// Ensure that both cannot be checked
			if ( jQuery(this).is( ':checked' ) && jQuery('.spp-fit-checkbox').is( ':checked' ) )
				jQuery('.spp-fit-checkbox').attr( 'checked', false )
		} );
		jQuery('.spp-fit-checkbox').on( 'change', function() {
			// Ensure that both cannot be checked
			if ( jQuery(this).is( ':checked' ) && jQuery('.spp-resize-checkbox').is( ':checked' ) )
				jQuery('.spp-resize-checkbox').attr( 'checked', false )
		} );

		// Brightness Adjustment Slider
		jQuery('.spp-brightness-slider').each( function() {
			var self = this;
			jQuery(self).slider( {
				value: 0,
				min: -255,
				max: 255,
				step: 1,
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-brightness').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-brightness').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-brightness').on( 'change', function() {
			var val = parseInt( jQuery(this).val() );

			if ( val > 255) { // More than 255
				jQuery(this).parents('tr').find('.spp-brightness-slider').slider( 'value', 255 );
				jQuery(this).val( 255 );
			}
			else if ( val < -255 ) { // Less than 255
				jQuery(this).parents('tr').find('.spp-brightness-slider').slider( 'value', -255 );
				jQuery(this).val( -255 );
			}
			else // Between -255 and 255
				jQuery(this).parents('tr').find('.spp-brightness-slider').slider( 'value', val );
		} );

		// Contrast Adjustment Slider
		jQuery('.spp-contrast-slider').each( function() {
			var self = this;
			jQuery(self).slider( {
				value: 0,
				min: -100,
				max: 100,
				step: 1,
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-contrast').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-contrast').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-contrast').on( 'change', function() {
			var val = parseInt( jQuery(this).val() );

			if ( val > 100) { // More than 100
				jQuery(this).parents('tr').find('.spp-contrast-slider').slider( 'value', 100 );
				jQuery(this).val( 100 );
			}
			else if ( val < -100 ) { // Less than 100
				jQuery(this).parents('tr').find('.spp-contrast-slider').slider( 'value', -100 );
				jQuery(this).val( -100 );
			}
			else // Between -100 and 100
				jQuery(this).parents('tr').find('.spp-contrast-slider').slider( 'value', val );
		} );

		// Colorize Red Adjustment Slider
		jQuery('.spp-colorize-red-slider').each( function() {
			var self = this;
			jQuery(self).slider({
				value: 0,
				min: 0,
				max: 255,
				step: 1,
				range: 'min',
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-colorize-red').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-colorize-red').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-colorize-red').on( 'change', function() {
			var val = parseInt( jQuery(this).val() );

			if ( val > 255) { // More than 100
				jQuery(this).parents('tr').find('.spp-colorize-red-slider').slider( 'value', 255 );
				jQuery(this).val( 255 );
			}
			else if ( val < 0 ) { // Less than 100
				jQuery(this).parents('tr').find('.spp-colorize-red-slider').slider( 'value', 0 );
				jQuery(this).val( 0 );
			}
			else // Between -100 and 100
				jQuery(this).parents('tr').find('.spp-colorize-red-slider').slider( 'value', val );
		} );

		// Colorize Green Adjustment Slider
		jQuery('.spp-colorize-green-slider').each( function() {
			var self = this;
			jQuery(self).slider({
				value: 0,
				min: 0,
				max: 255,
				step: 1,
				range: 'min',
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-colorize-green').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-colorize-green').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-colorize-green').on( 'change', function() {
			var val = parseInt( jQuery(this).val() );

			if ( val > 255) { // More than 100
				jQuery(this).parents('tr').find('.spp-colorize-green-slider').slider( 'value', 255 );
				jQuery(this).val( 255 );
			}
			else if ( val < 0 ) { // Less than 100
				jQuery(this).parents('tr').find('.spp-colorize-green-slider').slider( 'value', 0 );
				jQuery(this).val( 0 );
			}
			else // Between -100 and 100
				jQuery(this).parents('tr').find('.spp-colorize-green-slider').slider( 'value', val );
		} );

		// Colorize Blue Adjustment Slider
		jQuery('.spp-colorize-blue-slider').each( function() {
			var self = this;
			jQuery(self).slider({
				value: 0,
				min: 0,
				max: 255,
				step: 1,
				range: 'min',
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-colorize-blue').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-colorize-blue').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-colorize-blue').on( 'change', function() {
			var val = parseInt( jQuery(this).val() );

			if ( val > 255) { // More than 100
				jQuery(this).parents('tr').find('.spp-colorize-blue-slider').slider( 'value', 255 );
				jQuery(this).val( 255 );
			}
			else if ( val < 0 ) { // Less than 100
				jQuery(this).parents('tr').find('.spp-colorize-blue-slider').slider( 'value', 0 );
				jQuery(this).val( 0 );
			}
			else // Between -100 and 100
				jQuery(this).parents('tr').find('.spp-colorize-blue-slider').slider( 'value', val );
		} );

		// Smooth Adjustment Slider
		jQuery('.spp-smooth-slider').each( function() {
			var self = this;
			jQuery(self).slider( {
				value: 0,
				min: 0,
				max: 256,
				step: 1,
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-smooth').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-smooth').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-smooth').on( 'change', function() {
			var val = parseInt( jQuery(this).val() );

			if ( val > 256) { // More than 256
				jQuery(this).parents('tr').find('.spp-smooth-slider').slider( 'value', 256 );
				jQuery(this).val( 256 );
			}
			else if ( val < 0 ) { // Less than 0
				jQuery(this).parents('tr').find('.spp-smooth-slider').slider( 'value', 0 );
				jQuery(this).val( 0 );
			}
			else // Between 0 and 255
				jQuery(this).parents('tr').find('.spp-smooth-slider').slider( 'value', val );
		} );

		// Zoom Adjustment Slider
		jQuery('.spp-zoom-slider').each( function() {
			var self = this;
			jQuery(self).slider( {
				value: 0,
				min: 0,
				max: 10,
				step: 0.5,
				slide: function(event, ui) {
					jQuery(self).parents('tr').find('.attachments-spp-zoom').val( ui.value );
				},
				stop: function( event, ui ) {
					jQuery(self).parents('tr').find('.attachments-spp-zoom').trigger( 'change' );
				}
			} );
		} );
		jQuery('.attachments-spp-zoom').on( 'change', function() {
			var val = ( Math.round( parseFloat( jQuery(this).val() ) * 2 ) / 2 ).toFixed( 1 ); // Round to nearest half, 1 decimal place

			if ( val > 10) { // More than 10
				jQuery(this).parents('tr').find('.spp-zoom-slider').slider( 'value', 10 );
				jQuery(this).val( 10 );
			}
			else if ( val < 0 ) { // Less than 0
				jQuery(this).parents('tr').find('.spp-zoom-slider').slider( 'value', 0 );
				jQuery(this).val( 0 );
			}
			else { // Between 0 and 10
				jQuery(this).parents('tr').find('.spp-zoom-slider').slider( 'value', val );
				jQuery(this).val( val ); // Rounded value
			}
		} );

		// Hide all Simple Photon Photo table rows
		jQuery(document).on('click', '.media-button-insert', function() {
			jQuery('.spp-tr').hide();
		});

		is_spp_init = true;
	}


	// Initalize Simple Photon Photos on document load
	spp_init();

	// Media Library
	if ( jQuery('.describe-toggle-on').length > 1 ) {
		jQuery(document).one( 'click', '.describe-toggle-on', function() {
			spp_init();
		});
	}
	// Media Uploader (after image is uploaded)
	else {
		jQuery(document).bind( 'DOMNodeInserted', function( e ) {
			if ( e.target.className.indexOf('media-item') !== -1 && !is_spp_init)
				spp_init();
		});
	}
});