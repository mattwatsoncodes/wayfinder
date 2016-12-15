jQuery( document ).ready( function( $ ) {

	// Initialise Select2 on the dropdowns
	$('.wayfinder-search-box .wayfinder-search-box__type').select2({
	    dropdownAutoWidth: true,
	    width: 'auto',
	})
	$('.wayfinder-search-box .wayfinder-search-box__action').select2({
	    dropdownAutoWidth: true,
	    width: 'auto',
	})

	// Handle the action filtering
	$('.wayfinder-search-box .wayfinder-search-box__type').on( 'change', function() {
		$('.wayfinder-search-box .wayfinder-search-box__action').select2().empty();
		$('.wayfinder-search-box .wayfinder-search-box__action').unbind( 'change', mkdo_wf_submit_form );
		var parent_id = $(this).val();
		var data = {
			action: 'mkdo_wf_populate_actions',
			security: ajax_nonce,
			parent_id: parent_id,
		};
		$.ajax( {
			type:    'POST',
			url:     ajax_url,
			data:    data,
			success: function( response ) {
				$('.wayfinder-search-box .wayfinder-search-box__action').select2( {
					data: JSON.parse( response ),
				} );
				var selected = $('.wayfinder-search-box__action-value').val();
				if ( '' == selected ) {
					selected = 0;
				}
				$('.wayfinder-search-box .wayfinder-search-box__action').val( selected );
				$('.wayfinder-search-box .wayfinder-search-box__action').trigger( 'change' );
				$('.wayfinder-search-box .wayfinder-search-box__action').bind( 'change', mkdo_wf_submit_form );
			},
			error: function( error ) {
				console.log('Error:');
				console.log( error );
			}
		} );
	});

	// Init the filter
	$('.wayfinder-search-box .wayfinder-search-box__type').trigger( 'change' );

	// Trigger form submit on change
	function mkdo_wf_submit_form() {
		$(this).closest('form').submit();
	};
} );
