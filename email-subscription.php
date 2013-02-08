<?php
/**
 * Plugin Name: Email Subscription
 *
 * Description: A simple email subscription widget that connects
 * various third-party providers
 *
 * Plugin URI: http://www.hzdg.com
 *
 *
 * Version: 1.0
 * Author: Ryan Bagwell
 * Author URI: http://www.ryanbagwell.com
 *
 * PHP version 5
 *
 * @category WordPress
 * @package  Wordpress_Email_Signup
 * @author   Ryan Bagwell <ryan@ryanbagwell.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  GIT 0.1
 * @link     https://github.com/ryanbagwell/wordpress-email-signup
 * 
 */

require_once 'email-subscription.widget.php';

/**
 * EmailSignup
 *
 * The base plugin class.
 *
 * @category WordPress
 * @package  Wordpress_Email_Signup
 * @author   Ryan Bagwell <ryan@ryanbagwell.com>
 * @license  http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version  GIT 0.1
 * @link     https://github.com/ryanbagwell/wordpress-email-signup
 */
class EmailSignup extends WP_Widget
{

    /**
     * Our constructor
     *
     * @return null
     *
     */
    public function EmailSignup()
    {
    
        wp_register_script(
            'jquery-validate', 
            plugins_url(basename(dirname(__FILE__))  . '/js/jquery.validate.js'), 
            'jquery', '1.0', true
        );

        wp_register_script(
            'email-subscription', 
            plugins_url(basename(dirname(__FILE__)) .'/js/email-subscription.js'), 
            'jquery-validate', '1.0', true
        );

        wp_register_script(
            'jquery-infield-label', 
            plugins_url(
                basename(dirname(__FILE__)) . '/js/jquery.infieldlabel.min.js'
            ), 
            'jquery', '1.0', false
        );

        add_action(
            'wp_ajax_nopriv_email_signup', 
            array($this, 'submit_email')
        );

        add_action(
            'wp_ajax_email_signup', 
            array($this, 'submit_email')
        );

        add_action(
            'widgets_init', 
            array($this, 'start_widget')
        );

        add_action(
            'admin_menu', 
            array($this, 'add_settings')
        );
            
        $this->load_services();
        
        $this->connectors = $this->services;

        if (!is_admin())
            add_action(
                'wp_enqueue_scripts',
                array($this, 'print_scripts')
            );

        add_action(
            'admin_print_scripts',
            array($this, 'print_admin_scripts')
        );

    }

    /**
     * Initializes the widget
     *
     * @return null
     */
    public function start_widget()
    {
        register_widget('EmailSignupWidget');
    }


    /**
     * Prints the HTML signup form to the page
     *
     * @return null
     */
    public function show_signup_form()
    {
        include_once dirname(__FILE__).'/form.tpl.php';
    }


    /**
     * Prints the necessary JS
     *
     * @return null
     */
    public function print_scripts()
    {
        echo "<script type='text/javascript'>
            var ajaxURL='".admin_url('admin-ajax.php')."';</script>";
        
        //this can be uncommented later when we get the require.js stuff figured out
        // wp_enqueue_script( 'jquery' );
        // wp_enqueue_script( 'jquery-validate' );
        // wp_enqueue_script('email-subscription');
    }
    
    /**
     * Prints the necessary JS
     *
     * @return null
     */
    public function print_admin_scripts()
    {
        wp_enqueue_script('jquery-infield-label');
    }   

    
    /**
     * Calls the signup() method on the selected third-party service, which
     * sends the data to that service
     *
     * @return null
     */
    public function submit_email()
    {
        $service = $this->get_selected_service();
        $response = $service->signup();
    }
    
    /**
     * The method that adds the admin menu item
     *
     * @return null
     */
    public function add_settings()
    {
        add_options_page(
            'Email Signup Options',
            'Email Signups',
            'manage_options',
            'email-signups.php',
            array($this, 'options_html')
        );
    }

    /**
     * The method that adds the admin html page
     *
     * @return null
     */
    public function options_html()
    {

        if (array_key_exists('selected_submit_handler', $_POST))
            update_option(
                'selected_submit_handler',
                $_POST['selected_submit_handler']
            );
            
        if (array_key_exists('email_signup_default_widget_title', $_POST))
            update_option(
                'email_signup_default_widget_title',
                $_POST['email_signup_default_widget_title']
            );

        include_once 'lib/php-form-helpers/form.class.php';
        
        include_once 'templates/admin/admin.tpl.php';

    }
    
    /**
     * Makes the available third-party API services available,
     * and places them in a $this->services array
     *
     * @return null
     */
    public function load_services()
    {
        $services_dir = dirname(__FILE__).'/lib/services/';
        $files = scandir($services_dir);
        $this->services = array();
        foreach($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $name = str_replace('.php', '', $file);
            if ($name == 'base') continue;
            include_once $services_dir . $file;
            $classname = ucfirst($name);
            $this->services[$classname] = new $classname();
        }
    }
    
    /**
     * Returns the selected third-party service
     *
     * @return null
     */
    public function get_selected_service()
    {
        $this->load_services();
        $selected_service = get_option('selected_submit_handler', false);
        return $this->services[$selected_service];
        
    }
    
}

$email_signup = new EmailSignup()

?>