( function( $ ) {
	/**
	 * @param $scope The Widget wrapper element as a jQuery element
	 * @param $ The jQuery alias
	 */
	var WidgetSolaceformHandler = function( $scope, $ ) {
		const form = $scope.find( '.solaceform-form' );
		const post_id = form.data( 'post_id' );
		const el_id = form.data( 'el_id' );
		const msg_form = $scope.find( '.solaceform-form-msg' );
		let extension = '';
		$( '.solaceform-form input[type="file"]' ).each( function () {
			extension = $(this).attr( 'accept' );
		});

		form.on( 'submit', function(e) {
			e.preventDefault();

			const formDataSerialize = form.serialize();

			// Parse serialized form data into an object
			const urlParams = new URLSearchParams(formDataSerialize);
			let msg_email = '';

			// Convert URLSearchParams to an object
			urlParams.forEach((value, key) => {
				msg_email += key + ': ' + value + '<br/><br/>';
			});			
	
			const formData = new FormData(form[0]);
	
			// Add additional data to FormData
			formData.append('action', 'elementor_form_builder_form_ajax');
			formData.append('post_id', post_id);
			formData.append('el_id', el_id);
			formData.append('nonce', elementor_form_builder_obj.nonce);
			formData.append('dataSerialize', msg_email);
			formData.append('extension', extension);

			// Add class active
			$( '.solaceform-form-button' ).addClass( 'active' ).prop('disabled', true);

			// Clear all form input values
			form.find('input, textarea, select').val('')
			.prop('checked', false)   // Uncheck checkboxes and radios
			.prop('selected', false); // Deselect options in select dropdowns			

			$.ajax({
				method: 'POST',
				url: elementor_form_builder_obj.ajaxurl,
				data: formData,
				processData: false, // Prevent jQuery from processing the data
				contentType: false, // Let the browser set the correct content type
				success: function(res) {
					swal( {
						title: 'Success!',
						text: res.data.success_message,
						icon: 'success',
						dangerMode: false,
					} );

					// remove class active
					$( '.solaceform-form-button' ).removeClass( 'active' ).prop('disabled', false);

				},
				error: function(err) {
					const errorData = JSON.parse(err.responseText);
					const errorMessage = errorData?.data?.message;

					swal( {
						title: 'Error!',
						text: errorMessage,
						icon: 'error',
						dangerMode: true,
					} );

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
