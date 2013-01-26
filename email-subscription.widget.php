<?php

class EmailSignupWidget extends WP_Widget {

	function EmailSignupWidget() {
		parent::WP_Widget('email_signup', 'Email Signup Form');
		
	}

	function widget() { ?>
	    <section id="subscribe">
    	    <h3><i></i><?php echo get_option('email_signup_default_widget_title', 'Email Signup' ); ?></h3>
	    
        <?php require_once( dirname(__FILE__).'/templates/widget/email-signup.widget.html.php' ); ?>
        
        </section>
	<? }

}

?>
