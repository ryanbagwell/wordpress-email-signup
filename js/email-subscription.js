var EmailSignup = function(el) {
	this.$el = $(el);
	
	this.$el.validate({
		messages: this.messages,
		errorPlacement: $.proxy(this.errorPlacement, this),
		submitHandler: $.proxy(this.submitHandler, this)
	});

};
EmailSignup.prototype.ajaxURL = function() {

	if (typeof(ajaxURL) !== 'undefined')
		return ajaxURL;

	return '/wp-admin/admin-ajax.php';

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

	this.beforeSubmit();
	
	$.ajax({
		type: "POST",
		url: this.ajaxURL(),
		data: $(form).serialize() + '&action=email_signup',
		success: $.proxy(this.ajaxSuccess, this),
		error: $.proxy(this.ajaxError, this),
		dataType: 'json'
	});
	
	return false;

};
EmailSignup.prototype.beforeSubmit = function() {
	this.$el.find('input').fadeTo('fast', 0.5);
	this.$el.find('button').css('visibility', 'hidden');
	this.$el.find('.loading').css('display', 'block');
};
EmailSignup.prototype.ajaxSuccess = function(response) {
	this.$el.find('.loading').hide();
	this.$el.fadeOut();
	this.$el.parent().find('.success').show();
	return;
};
EmailSignup.prototype.ajaxError = function(response) {
	this.$el.fadeTo('fast', 1);
	alert('Sorry, there was an error. Please try again.');
};

