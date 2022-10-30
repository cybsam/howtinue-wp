jQuery(document).ready(function($) {
	// Switch tabs inside Widget form
	$( document ).on( 'click', '.ephd-cp__channels-form .ephd-admin__form-tab', function() {

		let target_key = $( this ).data( 'target' ),
			parent_wrap = $( this ).closest( '.ephd-cp__channels-form' );

		parent_wrap.find( '.ephd-admin__form-tab' ).removeClass( 'ephd-admin__form-tab--active' );
		parent_wrap.find( '.ephd-admin__form-tab-wrap' ).removeClass( 'ephd-admin__form-tab-wrap--active' );

		$( this ).addClass( 'ephd-admin__form-tab--active' );
		parent_wrap.find( '.ephd-admin__form-tab-wrap--' + target_key ).addClass( 'ephd-admin__form-tab-wrap--active' );
	});
});