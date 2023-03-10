( function ( $ ) {

	function recursiveAjaxCsvGeneration(url, data, button) {
		$.ajax({
			type: "POST",
			dataType: "json",
			url: url,
			data: data,
			success: function(response) {
				// Handle the response from the server here
				console.log(response);
				// Make another AJAX call if necessary
				if (response.success) {
					if (response.data.status === 'ready') {
						button.parent().find( "span.spinner" ).removeClass( "is-active" );
						button.prop( "disabled", false );

						// Trick for making downloadable link
						a = document.createElement('a');
						a.href = response.data.href;
						a.download = response.data.download;
						a.style.display = 'none';
						document.body.appendChild(a);
						a.click();
					} else {
						recursiveAjaxCsvGeneration(url, response.data, button);
					}

				} else {
					button.parent().find( "span.spinner" ).removeClass( "is-active" );
					button.prop( "disabled", false );

					if (response.data.error) {
						$('.csv-exporter-errors').text(response.data.error).show();
					}
				}
			},
			error: function(xhr, status, error) {
				// Handle errors here
				console.log(error);
			}
		});
	}

	$('#csv_exporter_button').on('click', function(e) {
		e.preventDefault();
		let button = $(this);
		button.prop( "disabled", true );
		button.parent().find( "span.spinner" ).addClass( 'is-active' );

		$('.csv-exporter-errors').text('').hide()

		let data = {
			nonce: csv_exporter.nonce,
			action: 'run_export'
		};

		recursiveAjaxCsvGeneration(csv_exporter.admin_url, data, button);
	});

} )( jQuery );
