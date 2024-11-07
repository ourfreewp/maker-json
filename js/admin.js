( function( $, _ ) {
	var submit               = $( document.getElementById( 'submit' ) ),
		notificationArea     = $( document.getElementById( 'makerjson-notification-area' ) ),
		notificationTemplate = wp.template( 'makerjson-notice' ),
		editor               = wp.CodeMirror.fromTextArea( document.getElementById( 'makerjson_content' ), {
			lineNumbers: true,
			mode: 'application/json'
		} );

	function checkForMakerJsonFile( e ) {
		var spinner = $( '.existing-makerjson .spinner' );

		if ( false !== e ) {
			spinner.addClass( 'is-active' );
			e.preventDefault();
		}

		var makerjson_type = $('input[name=makerjson_type]').val();
		var wpnonce = $('input[name=_wpnonce]').val();

		$.get({
			url: window.ajaxurl,
			type: 'POST',
			data: {
				action: 'makerjson_check_for_existing_file',
				makerjson_type: (makerjson_type === "" || makerjson_type === undefined) ? null : makerjson_type,
				_wpnonce: wpnonce,
			},
			success: function(response) {
				spinner.removeClass( 'is-active' );
				if ( ! response.file_exist ) {
					// Maker.json not found
					$( '.existing-makerjson' ).hide();
				} else {
					$( '.existing-makerjson' ).show();
				}
			}
		});
	}

	// Call our check when we first load the page
	checkForMakerJsonFile( false );

	$( '.makerjson-rerun-check' ).on( 'click', checkForMakerJsonFile );

	submit.on( 'click', function( e ){
		e.preventDefault();

		var	textarea    = $( document.getElementById( 'makerjson_content' ) ),
			notices     = $( '.makerjson-notice' ),
			submit_wrap = $( 'p.submit' ),
			saveSuccess = false,
			spinner     = submit_wrap.find( '.spinner' );

		submit.attr( 'disabled', 'disabled' );
		spinner.addClass( 'is-active' );

		// clear any existing messages
		notificationArea.hide();
		notices.remove();

		// Copy the code mirror contents into form for submission.
		textarea.val( editor.getValue() );

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: $( '.makerjson-settings-form' ).serialize(),
			success: function( r ) {
				var templateData = {};

				spinner.removeClass( 'is-active' );

				if ( 'undefined' !== typeof r.sanitized ) {
					textarea.val( r.sanitized );
				}

				if ( 'undefined' !== typeof r.saved && r.saved ) {
					saveSuccess = true;
				} else {
					templateData.errors = {
						'error_message': makerjson.unknown_error
					}
				}

				if ( 'undefined' !== typeof r.errors && r.errors.length > 0 ) {
					templateData.errors = {
						'error_message': makerjson.error_message,
						'errors':        r.errors
					}
				}

				// Refresh after a successful save, otherwise show the error message.
				if ( saveSuccess ) {
					document.location = document.location + '&makerjson_saved=1';
				} else {
					notificationArea.html( notificationTemplate( templateData ) ).show();
				}

			}
		})
	});

	$( '.wrap' ).on( 'click', '#makerjson-ays-checkbox', function( e ) {
		if ( true === $( this ).prop( 'checked' ) ) {
			submit.removeAttr( 'disabled' );
		} else {
			submit.attr( 'disabled', 'disabled' );
		}
	} );

	editor.on( 'change', function() {
		$( '.makerjson-ays' ).remove();
		submit.removeAttr( 'disabled' );
	} );

} )( jQuery, _ );
