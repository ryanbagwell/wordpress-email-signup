var EmailSignup = function(el) {
	this.$el = $(el);
	
	this.$el.validate({
		messages: this.messages,
		errorPlacement: $.proxy(this.errorPlacement, this),
		submitHandler: $.proxy(this.submitHandler, this)
	});

};
/*
 * The WordPress AJAX URL to post to
 */
EmailSignup.prototype.ajaxURL = function() {

	if (typeof(ajaxURL) !== 'undefined')
		return ajaxURL;

	return '/wp-admin/admin-ajax.php';

};
/*
 * Error messages to be used for jquery validation
 */
EmailSignup.prototype.messages = {
	first_name: "Enter first name",
	last_name: "Enter last name",
	email: "Invalid email"
};
/*
 * The error handler for validation problems
 */
EmailSignup.prototype.errorHandler = function(error, el) {
	error.insertAfter(el.prev());
	error.prev().remove();
};
/*
 * Handles submission operations
 */
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
/*
 * Is called before the submission
 */
EmailSignup.prototype.beforeSubmit = function() {
	this.$el.find('input').fadeTo('fast', 0.5);
	this.$el.find('button').css('visibility', 'hidden');
	this.$el.find('.loading').css('display', 'block');
};
/*
 * Is called after a successful ajax call
 */
EmailSignup.prototype.ajaxSuccess = function(response) {

	if (response.result == 'error' ) {
		this.signupError();
		return;
	}
	
	this.signupSuccess();

};
/*
 * Called after an unsuccessful ajax call
 */
EmailSignup.prototype.ajaxError = function() {
	this.signupError();
};
/*
 * Handles a successufl signup
 */
EmailSignup.prototype.signupSuccess = function() {
	this.$el.find('.loading').hide();
	this.$el.fadeOut();
	this.$el.parent().find('.success').show();
	return;
};
/*
 * Handles an unsuccessful signup
 */
EmailSignup.prototype.signupError = function() {
	this.$el.fadeTo('fast', 1);
	alert('Sorry, there was an error. Please try again.');
};

