<?php
require_once(dirname(__FILE__).'/../php-form-helper/form.class.php');

class BaseConnector {
	public $name = "Base Connector";
	public $slug = "base_connector";


	public function BaseConnector() {
        $this->save_settings();

	}


	public function send() {


	}

	public function get_form_fields() {
	    
	    return array(
	        new TextField($this->slugify_name('user_name'), 
	            'User Name', array( 'value' => get_option( $this->slugify_name('user_name') ))), 
	        new PasswordField($this->slugify_name('password'), 
	            'Password', array( 'value' => get_option( $this->slugify_name('password') )))
	    );
	}
	
	public function slugify_name( $field_name ) {
	    return strtolower( $this->slug."_$field_name" );
	}
	
	public function save_settings() {	   
	    foreach( $this->get_form_fields() as $field ) {
	        if ( array_key_exists($field->attrs['name'], $_POST) ) {
	            update_option( $field->attrs['name'], $_POST[$field->attrs['name']] );
	        }
	    }
	}

}




?>