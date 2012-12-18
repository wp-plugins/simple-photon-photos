// Hide all Simple Photon Photos table rows if on attachment page
jQuery(document).ready(function() {
	jQuery('.spp-tr').hide();
});

jQuery(document).bind( 'DOMNodeInserted', function( e ) {
	if ( e.target.className.indexOf('media-item') !== -1)
		jQuery('.spp-tr').hide();
});