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

			$.ajax({
				method: 'POST',
				url: elementor_form_builder_obj.ajaxurl,
				data: formData,
				processData: false, // Prevent jQuery from processing the data
				contentType: false, // Let the browser set the correct content type
				success: function(res) {
					if ( res.success ) {
						msg_form.text(res.data.success_message);
						msg_form.css({
							'color': '#05b705',
							'margin-top': '5px',
							'display': 'block',
						});
						$( '.solaceform-form-msg' ).fadeIn( 300 );
					} else {
						msg_form.text(res.data.error_message);
						msg_form.css({
							'color': '#f72929',
							'margin-top': '5px',
							'display': 'block',
						});
						$( '.solaceform-form-msg' ).fadeIn( 300 );
					}

					// Clear all form input values
					form.find('input, textarea, select').val('')
					.prop('checked', false)   // Uncheck checkboxes and radios
					.prop('selected', false); // Deselect options in select dropdowns						

					// remove class active
					$( '.solaceform-form-button' ).removeClass( 'active' ).prop('disabled', false);

				},
				error: function(err) {
					const errorData = JSON.parse(err.responseText);
					const errorMessage = errorData?.data?.error_message;

					msg_form.text(errorMessage);
					msg_form.css({
						'color': '#f72929',
						'margin-top': '5px',
						'display': 'block',
					});
					$( '.solaceform-form-msg' ).fadeIn( 300 );

					// remove class active
					$( '.solaceform-form-button' ).removeClass( 'active' ).prop('disabled', false);
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
