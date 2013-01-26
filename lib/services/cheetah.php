<?php
require_once('base.php');

class Cheetah extends BaseConnector {
	public $name = "Cheetah";
	public $auth_endpoint = 'https://ebm.cheetahmail.com/api/login1/';
	public $data_endpoint = 'https://ebm.cheetahmail.com/api/setuser1/';
	public $cookie_file = '/tmp/cheetah_login1_cookie.txt';
	
	public function Cheetah() {
		parent:: __construct();
		
		$this->user_name = get_option( 
		    $this->slugify_name('user_name') );
		
		$this->password = get_option( 
		    $this->slugify_name('password') );
		    
		$this->subscriber_list_id = get_option( 
		    $this->slugify_name('subscriber_list_id') );
	}
	
	/*
	 * The main signup function that is automatically called
	 * with a successful ajax request
	 */
	public function signup() {
	    
	    extract($_POST);
	    
	    if ( empty( $email ))
	        $this->error( 'You must specify an email.');
	        
	    if ( empty( $first_name ))
	        $this->error( 'You must provide your first name.');
	        
	    if ( empty( $last_name ))
	        $this->error( 'You must provide your last name.');
	    
	    if ( !$this->is_cookie_valid() )
	        $this->refresh_auth_cookie();
	    
	    $result = $this->send( $email, $first_name, $last_name );
	    
	    /*
	     * If the submission is successful, the server will return "OK"
	     */
	    if ( $result === 'OK' )
	        $this->success();
	        
	    /*
	     * Otherwise, send the client an error message
	     * and log it
	     */
	     $this->error( explode( ':',  $result ) );

}
	
	/*
	 * In adition to the default form fields in the base class,
	 * add another one to capture the ID of the email ist the user
	 * should be assigned to
	 */
	public function get_form_fields() {
	    $default_fields = parent::get_form_fields();
	    
        $attrs = array(
            'value' => get_option( $this->slugify_name('subscriber_list_id') )
        );
	    
	    $default_fields[] = new TextField( $this->slugify_name('subscriber_list_id'), 
            'Subscriber List ID', $attrs );
                
        return $default_fields;
    }

	/* 
	 * This checks if the cookie is older than 8 hours, or if
	 * it doesn't exist.
	 */
	public function is_cookie_valid() {
	    error_log("Email Signup: checking cookie validity");
        
        if ( !file_exists( $this->cookie_file ) )
            return false;

        if ( time() - filemtime( $this->cookie_file ) > (8*60*60) )
            return false;
            
        return true;

	}

    /*
     * The Cheetah API makes you get an authentication cookie, 
     * which is good for 8 hours. It must be saved locally, 
     * then re-requested when it expires.
     */
	public function refresh_auth_cookie() {
	    $endpoint = $this->auth_endpoint . "?name=$this->user_name&cleartext=$this->password";	   
        $c = curl_init($endpoint);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_COOKIEJAR, $this->cookie_file);
        $response = curl_exec($c);
        curl_close($c);
	}


	/*
	 * This makes the API call to Cheetah
	 */
	public function send($email, $first_name, $last_name) {
	    $endpoint = $this->data_endpoint."?email=$email&FNAME=$first_name&LNAME=$last_name";
	    
	    /*
	     * Add the list ID that the person should be signed up for
	     * if we've specified it in the backend
	     */
	    if ( !empty( $this->subscriber_list_id) )
	        $endpoint .= "&sub=$this->subscriber_list_id";
	    
	    $c = curl_init($endpoint);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_COOKIEFILE, $this->cookie_file);
        $response = curl_exec($c);
        curl_close($c);
        return trim($response);
 	}

}


?>