<?php
/*
Plugin Name: Email Subscription
Plugin URI: http://www.hzdg.com
Description: A simple email subscription widget that connects various third-party providers
Version: 1.0
Author: Ryan Bagwell
Author URI: http://www.ryanbagwell.com
*/


class EmailSignup extends WP_Widget  {


	public function EmailSignup() {
	
		wp_register_script('jquery-validate', 
			plugins_url( '/js/jquery-validate.js' ), 'jquery', '1.0', true );

		wp_register_script('email-subscription', 
			plugins_url('js/email-sbuscription.js', __FILE__), 'jquery-validate', '1.0', true );

		wp_register_script('jquery-set-field-titles', 
			plugins_url( basename(__DIR__) . '/js/jquery.setFieldTitles.js' ), 
			    'jquery', '1.0', false );

		add_action( 'wp_ajax_nopriv_email_signup', 
			array($this, 'submit_email') );

		add_action( 'wp_ajax_email_signup', 
			array($this, 'submit_email') );

		add_action( 'widgets_init', 
			array($this, 'start_widget') );

		add_action( 'admin_menu', 
			array($this, 'add_settings') );

		$this->connectors = $this->load_services();

		if (!is_admin())
			add_action( 'wp_enqueue_scripts', array($this, 'print_scripts') );
			
		add_action( 'admin_print_scripts', array($this, 'print_admin_scripts') );

	}

	public function start_widget() {
		$this->WP_Widget( 'email-signup-widget', 'Email Signup Widget');
	}


	/*
	 * Prints the HTML signup form to the page
	 */
	public function show_signup_form() {		
		require_once(dirname(__FILE__).'/form.tpl.php');
	}


	/*
	 * Prints the necessary JS
	 */
	public function print_scripts() {
		echo "<script type='text/javascript'>var ajaxURL='".admin_url('admin-ajax.php')."';</script>";
		wp_enqueue_script( 'jquery-validate' );
		wp_enqueue_script('email-subscription');
	}
	
	/*
	 * Prints the necessary JS
	 */
	public function print_admin_scripts() {
		wp_enqueue_script('jquery-set-field-titles');
	}	

	public function submit_email() {
		die();
	}
	
	public function add_settings() {
		add_options_page( 'Email Signup Options', 'Email Signups', 
			'manage_options', 'email-signups.php', array($this, 'options_html') );
	}

	public function options_html() {
	    if ( array_key_exists( 'selected_submit_handler', $_POST) )
	        update_option( 'selected_submit_handler', $_POST['selected_submit_handler']);
	        //echo get_option('selected_submit_handler');
		require_once('lib/php-form-helper/form.class.php');
		require_once('templates/admin/admin.tpl.php');
	}
	
	public function load_services() {
	    $services_dir = dirname(__FILE__).'/lib/services/';
	    $files = scandir( $services_dir );
	    $this->services = array();
	    foreach( $files as $file ) {
	        if ( $file == '.' || $file == '..' ) continue;
	        $name = str_replace('.php', '', $file);
	        if ($name == 'base') continue;
	        require_once($services_dir . $file);
	        $classname = ucfirst($name);
	        echo $classname;
            $this->services[$name] = new $classname();
        }
	}
}

$email_signup = new EmailSignup()

?>