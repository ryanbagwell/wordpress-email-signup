<?php
require_once(dirname(__FILE__).'/../php-form-helpers/form.class.php');

class BaseConnector {
    public $name = "Base Connector";
    public $slug = "base_connector";

    public function BaseConnector() {
        if ( is_admin() )
            $this->save_settings();

        $this->settings = $this->get_settings();
        
    }

  /*
   * This method is automatically called
   * by the ajax call to initiate the signup process. It should
   * be overidden in individual services classes
   */
    public function signup() {
        $this->error( 'not implemented' );
    }


    /*
     * Returns an array of form fields to print to
     * our admin screen. Use the library in 
     * lib/php-form-helpers
     */
    public function get_form_fields() {
        
        return array(
            
            new TextField($this->slugify_name('user_name'), 
                'User Name', array( 
                    'value' => get_option( $this->slugify_name('user_name') ),
                    'id' => $this->slugify_name('user-name') )),
                
            new PasswordField($this->slugify_name('password'),
                'Password', array(
                    'value' => get_option( $this->slugify_name('password') ),
                    'id' => $this->slugify_name('password') ))
                
        );
    }
    
    public function get_settings() {
        
        $settings = array();

        foreach( $this->get_form_fields() as $field ) {
            $s = $this->unslugify_name( $field->name );
            $settings[$s] = $field->attrs['value'];
        }
        
        return (object) $settings;
        
    }


    /*
     * Returns a slugified version of the given text to differentiate
     * between classes
     */
    public function slugify_name( $field_name ) {
        return strtolower( $this->get_slug()."_$field_name" );
    }
    
    /*
     * Removes the slug prefix from the given slug
     */
    public function unslugify_name( $slug ) {
        return str_replace( $this->get_slug()."_", '', $slug);
    }
    
    /*
     * Saves settings
     */
    public function save_settings() {
        foreach( $this->get_form_fields() as $field ) {       
            if ( array_key_exists( $field->attrs['name'], $_POST ) ) {
                update_option( $field->attrs['name'], $_POST[$field->attrs['name']] );
            }
        }
    }

    /*
     * Returns a slug prefix for use in
     * creating slugified
     */    
    public function get_slug() {
        $reflector = new ReflectionClass( get_class($this) );
        $filename = basename( $reflector->getFileName() );
        return str_replace( '.php', '', $filename );
    }
    
    /*
     * Prints a JSON error with the given message
     * to the screen
     */
    public function error($message = '') {
        
        $error = array(
           'result' => 'error',
           'message' => $message
        );
        
        $this->response( json_encode( $error ));
        
    }
    
    /*
     * Prints a JSON success message
     * to the screen
     */ 
    public function success($message = 'success') {

        $success = array(
           'result' => $message
        );
        
        $this->set_success_cookie();
        
        $this->response( json_encode( $success ) );
        
    }
    
    /*
     * Prints a JSON response to the screen
     * the provided response message.
     *
     */
    protected function response($response) {
        header('Content-type: application/json');
        die($response);
    }


    /*
     * Utility function to create a querystring from
     * an array of key => value pairs
     */
    public function make_querystring($params = array()) {
        $qs = array_map( array($this, '_mkqs'), 
            $params, array_keys($params));
        return implode('&', $qs);
    }
    
    /*
     * The make_querystring callback
     */ 
    private function _mkqs($v, $k) {
        return "$k=".urlencode(trim($v));
    }
    
    /*
     * Sets a success cookie that allows us
     * to check if the person has already signed up
     */
    private function set_success_cookie() {
        setcookie( "wordpress-email-signup", 1, mktime(60*60*24*30*12), '/');
    }

}