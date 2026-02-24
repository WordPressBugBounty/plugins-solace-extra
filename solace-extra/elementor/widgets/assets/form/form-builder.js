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

			const formDataSerialize = form.serialize();

			// Parse serialized form data into an object
			const urlParams = new URLSearchParams(formDataSerialize);
			let msg_email = '';

			// // Convert URLSearchParams to an object
			// urlParams.forEach((value, key) => {
			// 	msg_email += key + ': ' + value + '<br/><br/>';
			// });			

			form.find('input, textarea, select').each(function() {
				const el = $(this);
			
				// Get the custom label from `get_label` attribute
				const label = el.attr('get_label');
				const value = el.val();
			
				// Skip radio/checkbox if they are not checked
				if ((el.is(':radio') || el.is(':checkbox')) && !el.is(':checked')) {
					return; // Important: avoid unselected radios/checkboxes
				}
			
				// Append label and value to the email message string
				// For input type="number", this works the same as text
				if ( label ) {
					msg_email += label + ': ' + value + '<br/><br/>';
				} else {
					msg_email += value + '<br/><br/>';
				}
			});
	
			const formData = new FormData(form[0]);
	
			// Add additional data to FormData
			formData.append('action', 'elementor_form_builder_form_ajax');
			formData.append('post_id', post_id);
			formData.append('post_id2', post_id2);
			formData.append('el_id', el_id);
			formData.append('nonce', elementor_form_builder_obj.nonce);
			formData.append('dataSerialize', msg_email);
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
