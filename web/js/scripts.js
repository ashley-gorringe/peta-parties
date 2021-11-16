$( document ).on('submit','.process-form',function(event){
    console.log( $( this ).serialize() );
    event.preventDefault();

	//var submitButton = $(this).find(':submit');
    //submitButton.addClass('button--processing');

	$(this).find('.form-control').removeClass( "is-invalid" );

    $.ajax({
        type: "POST",
        url: '/process.php',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(response){
            console.log(response);
            if(response.status == 'error'){
                //submitButton.removeClass('button--processing');

                if(typeof response.errorFields !== "undefined"){
                    var errorFields = response.errorFields;
                    errorFields.forEach(function(fieldName) {
						var fieldName = '[name="'+fieldName+'"]';
                        $(fieldName).addClass( "is-invalid" );
                    });
                }
				swal({
					title: "Hold up!",
					text: response.message,
					icon: "error",
				});
            }else if(response.status == 'success'){
                //submitButton.removeClass('button--processing');

                if(typeof response.successRedirect !== 'undefined'){
                    window.location.href = response.successRedirect;
                }
                if(typeof response.successCallback !== 'undefined'){
                    console.log('Callback Function: '+response.successCallback);
                    window[response.successCallback](response.successCallbackParams);
                }
                if(typeof response.message !== 'undefined'){
                    alert(response.message);
                }
            }
        }
    });
});

$('#logOutLink').on('click', function(event) {
	event.preventDefault();
	$.ajax({
        type: "POST",
        url: '/process.php',
        data: 'action=logout',
        dataType: 'json',
        success: function(response){
            if(response.status == 'success'){

                if(typeof response.successRedirect !== 'undefined'){
                    window.location.href = response.successRedirect;
                }
                if(typeof response.successCallback !== 'undefined'){
                    console.log('Callback Function: '+response.successCallback);
                    window[response.successCallback](response.successCallbackParams);
                }
                if(typeof response.message !== 'undefined'){
                    alert(response.message);
                }
            }
        }
    });
});

$('.cancelApplicationButton').on('click', function(event) {
	event.preventDefault();

	var applicationid = $(this).data('applicationid');

	swal({
		title: "Are you sure?",
		text: "If you cancel this Application you will not be able to recover it later.",
		icon: "warning",
		buttons: {
			cancel: "Nevermind",
			catch: {
				text: "Cancel Application",
				value: true,
			},
		},
	}).then((value) => {
		if(value === true){
			$.ajax({
		        type: "POST",
		        url: '/process.php',
		        data: 'action=application-cancel&applicationid='+applicationid,
		        dataType: 'json',
		        success: function(response){
					if(response.status == 'error'){
		                //submitButton.removeClass('button--processing');

		                if(typeof response.errorFields !== "undefined"){
		                    var errorFields = response.errorFields;
		                    errorFields.forEach(function(fieldName) {
								var fieldName = '[name="'+fieldName+'"]';
		                        $(fieldName).addClass( "is-invalid" );
		                    });
		                }
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
		            }else if(response.status == 'success'){

		                if(typeof response.successRedirect !== 'undefined'){
		                    window.location.href = response.successRedirect;
		                }
		                if(typeof response.successCallback !== 'undefined'){
		                    console.log('Callback Function: '+response.successCallback);
		                    window[response.successCallback](response.successCallbackParams);
		                }
		                if(typeof response.message !== 'undefined'){
		                    alert(response.message);
		                }
		            }
		        }
		    });
		}
	});
});
