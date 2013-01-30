var EmailSignup = function(el) {
  $(el).validate({
	  messages: this.messages,
		errorPlacement: this.errorPlacement,
		submitHandler: this.submitHandler
	});
};
EmailSignup.prototype.messages = {
  first_name: "Enter first name",
	last_name: "Enter last name",
	email: "Invalid email"
};
EmailSignup.prototype.errorHandler = function(error, el) {
  error.insertAfter(el.prev());
	error.prev().remove();
};
EmailSignup.prototype.submitHandler = function(form) {

	$(form).fadeTo('fast', 0.5);
	$(form).find('.loading').css('display', 'block');

	var data = $(form).serialize() + '&action=email_signup';

	$.post(ajaxURL, data, function (response) {
		if (response.result === 'success') {
			$(form).find('.loading').hide();
			$(form).fadeOut();
			$(form).parent().find('.success').show();
			return;
		}

		$(form).fadeTo('fast', 1);
		alert('Sorry, there was an error. Please try again.');

	}, 'json');

	return false;

};
