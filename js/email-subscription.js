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

	var data = $(form).serialize() + '&action=email_signup';

  $.post(this.ajaxURL(), data, this.ajaxSuccess, this.ajaxError, 'json');
  
  return false;

};
EmailSignup.prototype.beforeSubmit = function() {
  this.$el.find('input').fadeTo('fast', 0.5);
  this.$el.find('button').css('visibility', 'hidden');
	this.$el.find('.loading').css('display', 'block');
};
EmailSignup.prototype.ajaxSuccess = function(response) {
  console.log('ajaxSuccess');
  $(form).find('.loading').hide();
  $(form).fadeOut();
  $(form).parent().find('.success').show();
  return;
};
EmailSignup.prototype.ajaxError = function(response) {
    console.log('ajaxError');
  $(form).fadeTo('fast', 1);
  alert('Sorry, there was an error. Please try again.');
};

