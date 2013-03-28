<?php

class EmailSignupWidget extends WP_Widget {
  public $default_widget_template = 'templates/widget/email-signup.widget.html.php';

	function EmailSignupWidget() {
		parent::WP_Widget('email_signup', 'Email Signup Form');

        wp_register_script( 'jquery-validation',
            sprintf('%s/%s/%s', WP_PLUGIN_URL, basename(dirname(__FILE__)), 'js/jquery.validate.js'),
            'jquery');

        wp_register_script( 'email-subscription',
            sprintf('%s/%s/%s', WP_PLUGIN_URL, basename(dirname(__FILE__)), 'js/email-subscription.js'),
            'jquery-vaidation');

		if ( !is_admin() )
		    $this->load_scripts();

	}

	function widget() {

        $email_plugin_path = WP_PLUGIN_URL.'/'.basename(dirname(__FILE__));

        require_once( $this->get_template() );
	}


	function load_scripts() {

        if ( is_active_widget( false, false, $this->id_base, true) === false )
            return;

        //wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-validation' );
        wp_enqueue_script( 'email-subscription' );

	}


	/*
	 * Finds a file named email-signup.widget.html.php
	 * if it exists in the theme. If not, uses the default
	 * widget template file
	 */
	public function get_template() {

        $theme_iterator = new RecursiveDirectoryIterator(TEMPLATEPATH.'/');
        $file_iterator = new RecursiveIteratorIterator($theme_iterator);
        $matches = new RegexIterator($file_iterator,
            '/email-signup\.widget\.html\.php/',
            RecursiveRegexIterator::GET_MATCH);
        $matches = iterator_to_array($matches);

        if ( count( $matches ) > 0 )
            return key($matches);

        return $this->default_widget_template;

  }

}

?>
