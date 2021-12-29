$( document ).on('submit','.process-form',function(event){
    console.log( $( this ).serialize() );
    event.preventDefault();

	//var submitButton = $(this).find(':submit');
    //submitButton.addClass('button--processing');

	$(this).find('.form-control').removeClass( "invalid" );
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
                        $(fieldName).addClass( "invalid" );
                    });
                }
				//alert(response.message);
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

function closeModals(){
	$('.modal__overlay').hide();
	$('.modal__container').hide();
}

$('.modal__overlay').on('click', function(event) {
	event.preventDefault();
	closeModals();
});
$('.modalCloseButton').on('click', function(event) {
	event.preventDefault();
	closeModals();
});
$('#openLoginModal').on('click', function(event) {
	event.preventDefault();
	closeModals();
	$('.modal__overlay').show();
	$('#login-modal').show();
});
$('#openRegisterModal').on('click', function(event) {
	event.preventDefault();
	closeModals();
	$('.modal__overlay').show();
	$('#register-modal').show();
});
$('#openAccountModal').on('click', function(event) {
	event.preventDefault();
	closeModals();
	$('.modal__overlay').show();
	$('#account-modal').show();
});
$('#openBasketModal').on('click', function(event) {
	event.preventDefault();
	closeModals();
	$('.modal__overlay').show();
	$('#basket-modal').show();
});

function loginSuccess(){
	location.reload();
}

$('#account-logout-link').on('click', function(event) {
	event.preventDefault();

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to log out?",
		icon: "warning",
		buttons: {
			cancel: "Stay logged in",
			catch: {
				text: "Log out",
				value: true,
			},
		},
	}).then((value) => {
		if(value === true){
			$.ajax({
		        type: "POST",
		        url: '/process.php',
		        data: 'action=logout',
		        dataType: 'json',
		        success: function(response){
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
		            }else if(response.status == 'success'){
						location.reload();
		            }
		        }
		    });
		}
	});
});

$('#account-delete-link').on('click', function(event) {
	event.preventDefault();

	swal({
		title: "Are you sure?",
		text: "Are you sure you want to delete your account? This can't be undone.",
		icon: "warning",
		buttons: {
			cancel: "Nevermind",
			catch: {
				text: "Delete Account",
				value: true,
			},
		},
	}).then((value) => {
		if(value === true){
			$.ajax({
		        type: "POST",
		        url: '/process.php',
		        data: 'action=delete-account',
		        dataType: 'json',
		        success: function(response){
					if(response.status == 'error'){
						swal({
							title: "Hold up!",
							text: response.message,
							icon: "error",
						});
		            }else if(response.status == 'success'){
						location.reload();
		            }
		        }
		    });
		}
	});
});

function addToBasket(productid){
	event.preventDefault();

	$.ajax({
		type: "POST",
		url: '/process.php',
		data: 'action=addToBasket&productid='+productid,
		dataType: 'json',
		success: function(response){
			if(response.status == 'error'){
				swal({
					title: "Hold up!",
					text: response.message,
					icon: "error",
				});
			}else if(response.status == 'success'){
				location.reload();
			}
		}
	});
}
function quantityUp(productid){
	event.preventDefault();

	$.ajax({
		type: "POST",
		url: '/process.php',
		data: 'action=quantityUp&productid='+productid,
		dataType: 'json',
		success: function(response){
			if(response.status == 'error'){
				swal({
					title: "Hold up!",
					text: response.message,
					icon: "error",
				});
			}else if(response.status == 'success'){
				location.reload();
			}
		}
	});
}
function quantityDown(productid){
	event.preventDefault();

	$.ajax({
		type: "POST",
		url: '/process.php',
		data: 'action=quantityDown&productid='+productid,
		dataType: 'json',
		success: function(response){
			if(response.status == 'error'){
				swal({
					title: "Hold up!",
					text: response.message,
					icon: "error",
				});
			}else if(response.status == 'success'){
				location.reload();
			}
		}
	});
}


new Splide( '.splide', {
  type   : 'loop',
  arrows: false,
  perPage: 1,
  autoplay: true,
} ).mount();
