<?php

class EmailSignupWidget extends WP_Widget {
  public $default_widget_template = 'templates/widget/email-signup.widget.html.php';

	function EmailSignupWidget() {
		parent::WP_Widget('email_signup', 'Email Signup Form');
		
	}

	function widget() {
    require_once( $this->get_template() );
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
