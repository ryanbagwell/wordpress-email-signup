<?php
require_once(dirname(__FILE__).'/../php-form-helper/form.class.php');

class BaseConnector {
	public $name = "Base Connector";
	public $slug = "base_connector";


	public function BaseConnector() {
	    if ( is_admin() )
            $this->save_settings();
	}


	public function signup() {


	}

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
	
	public function slugify_name( $field_name ) {
	    return strtolower( $this->get_slug()."_$field_name" );
	}
	
	public function save_settings() {
	    foreach( $this->get_form_fields() as $field ) {	      
	        if ( array_key_exists( $field->attrs['name'], $_POST ) ) {
	            update_option( $field->attrs['name'], $_POST[$field->attrs['name']] );
	        }
	    }
	}
	
	public function get_slug() {
	    $reflector = new ReflectionClass(get_class($this));
	    $filename = basename($reflector->getFileName());
	    return str_replace('.php', '', $filename);
	}


	
	/*
	 * 
	 *
	 */
	public function error($message = '') {
	    
	    $error = array(
	       'result' => 'error',
	       'message' => $message
	    );
	    
	    $this->response( json_encode( $error ));
	    
	}
	
	public function success() {

        $success = array(
	       'result' => 'success'  
	    );
	    
	    $this->response( json_encode( $success ));
	}
	
	/*
	 *
	 *
	 */
	public function response($response) {
	    header('Content-type: application/json');
	    
	    die($response);
	}


}




?>