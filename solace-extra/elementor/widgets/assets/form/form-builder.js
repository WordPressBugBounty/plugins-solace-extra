( function( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetSolaceformHandler = function( $scope, $ ) {
		const form = $scope.find( '.solaceform-form' );
		const post_id = form.data( 'post_id' );
		const post_id2 = $( '.solace-custom-header-footer').attr('post-id');
		const el_id = form.data( 'el_id' );
		const msg_form = $scope.find( '.solaceform-form-msg span' );
		let extension = '';
		$( '.solaceform-form input[type="file"]' ).each( function () {
			extension = $(this).attr( 'accept' );
		});

		// Handle checkbox required attribute logic
		form.on('change', 'input[type="checkbox"][data-required="true"]', function() {
			const $currentCheckbox = $(this);
			const $checkboxWarp = $currentCheckbox.closest('.solaceform-checkbox-warp');
			
			// Only proceed if we're in a .solaceform-checkbox-warp container
			if ($checkboxWarp.length > 0) {
				const isChecked = $currentCheckbox.is(':checked');
				
				if (isChecked) {
					// When a required checkbox is checked, remove required from all other checkboxes in the same container
					$checkboxWarp.find('input[type="checkbox"]').not($currentCheckbox).removeAttr('required');
				} else {
					// When a required checkbox is unchecked, add required back to all other required checkboxes in the same container
					$checkboxWarp.find('input[type="checkbox"][data-required="true"]').not($currentCheckbox).attr('required', 'required');
				}
			}
		});

		form.on( 'submit', function(e) {
			e.preventDefault();

			// The email body is composed server-side from the widget's own field
			// definitions. This used to build it here and post it as
			// 'dataSerialize', which meant the message that left the site's mail
			// server was whatever the browser said it was. FormData already carries
			// every field under its own name, which is all the server needs.
			const formData = new FormData(form[0]);

			// Add additional data to FormData
			formData.append('action', 'elementor_form_builder_form_ajax');
			formData.append('post_id', post_id);
			formData.append('post_id2', post_id2);
			formData.append('el_id', el_id);
			formData.append('nonce', elementor_form_builder_obj.nonce);
			formData.append('extension', extension);

			// Add class active
			$( '.solaceform-form-button' ).addClass( 'active' ).prop('disabled', true);

			// Show a message in the form's status area, green for success and red
			// otherwise. Kept in one place so every outcome -- including the ones
			// below where the server reply could not be read -- notifies the
			// visitor instead of failing silently.
			const showMsg = function( text, isSuccess ) {
				msg_form.text( text || '' );
				msg_form.css({
					'color': isSuccess ? '#05b705' : '#f72929',
					'margin-top': '5px',
					'display': 'block',
				});
				$( '.solaceform-form-msg' ).fadeIn( 300 );
			};

			// Pull a message out of whatever came back. jQuery may hand us a parsed
			// object or, when the body was not clean JSON (a stray PHP notice, an
			// HTML error page), the raw string -- which must not be fed to the
			// success/error logic as if it were the expected shape.
			const readData = function( res ) {
				if ( res && typeof res === 'object' ) {
					return res;
				}
				if ( typeof res === 'string' ) {
					try {
						return JSON.parse( res );
					} catch ( e ) {
						return null;
					}
				}
				return null;
			};

			const resetForm = function() {
				// Clear all form input values
				form.find('input, textarea, select').val('')
				.prop('checked', false)   // Uncheck checkboxes and radios
				.prop('selected', false); // Deselect options in select dropdowns
			};

			const reenableButton = function() {
				$( '.solaceform-form-button' ).removeClass( 'active' ).prop('disabled', false);
			};

			$.ajax({
				method: 'POST',
				url: elementor_form_builder_obj.ajaxurl,
				data: formData,
				processData: false, // Prevent jQuery from processing the data
				contentType: false, // Let the browser set the correct content type
				success: function(res) {
					const data = readData( res );

					if ( data && data.success ) {
						showMsg( data.data && data.data.success_message, true );
						resetForm();
					} else if ( data && data.data ) {
						showMsg( data.data.error_message, false );
					} else {
						// A 200 whose body we could not parse -- treat as a failure
						// so the visitor is told, rather than left guessing.
						showMsg( 'Something went wrong. Please try again.', false );
					}

					reenableButton();
				},
				error: function(err) {
					const data = readData( err && err.responseText );
					const errorMessage = ( data && data.data && data.data.error_message )
						? data.data.error_message
						: 'Something went wrong. Please try again.';

					showMsg( errorMessage, false );
					reenableButton();
				}
			});
		});
	};
	

	$( window ).on(
		'elementor/frontend/init',
		function() {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/solaceform.default', WidgetSolaceformHandler )
		}
	)
} )( jQuery )
