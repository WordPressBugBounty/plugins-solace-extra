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

			$.ajax({
				method: 'POST',
				url: elementor_form_builder_obj.ajaxurl,
				data: formData,
				processData: false, // Prevent jQuery from processing the data
				contentType: false, // Let the browser set the correct content type
				success: function(res) {
					msg_form.text(res.data.success_message);
	
					if (res.data.redirect) {
						window.location.href = res.data.redirect_to;
					}
	
				},
				error: function(err) {
					const errorData = JSON.parse(err.responseText);
					const errorMessage = errorData?.data?.message;
					msg_form.text(errorMessage);
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
