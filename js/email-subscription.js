jQuery(document).ready(function($) {

	$('#sign-up').validate({
		submitHandler: function(form) {
			var data = $(form).serialize() +'&action=email_signup';
			
			$.post(ajaxURL, data, function(response) {
				console.log(response);
			}, 'text');

			return false;
		}

	});
});
