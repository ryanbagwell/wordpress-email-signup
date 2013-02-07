<?php

require_once('base.php');
require_once(dirname(__FILE__).'/../exactarget/exacttarget_soap_client.php');

class Exacttarget extends BaseConnector {
    public $name = "Exacttarget";
    public $slug = "exact_target";

    public function Exacttarget() {
        parent:: __construct();
    }
    
    public function signup() {
        
        extract( $_POST );
        
        if ( empty( $email_address ))
            $this->error( 'You must specify an email.');
            
        if ( empty( $first_name ))
            $this->error( 'You must provide your first name.');
            
        if ( empty( $last_name ))
            $this->error( 'You must provide your last name.');
        
        try {
            $client = $this->get_client();
            
            $subscriber = new ExactTarget_Subscriber();
            $subscriber->EmailAddress = $email_address;
            $subscriber->Attributes = array_merge( $subscriber->Attributes, array(
                $this->get_attribute('FirstName', $first_name),
                $this->get_attribute('LastName', $last_name),
                $this->get_attribute( $this->settings->extra_param_name,
                    $this->settings->extra_param_value)
            ));
            
            $object = new SoapVar($subscriber, SOAP_ENC_OBJECT, 
                'Subscriber', "http://exacttarget.com/wsdl/partnerAPI");
            $request = new ExactTarget_CreateRequest();
            $request->Options = NULL;
            $request->Objects = array($object);
            
            $results = $client->Create($request);
            error_log( $results );

            $this->success();
            
            
        } catch (SoapFault $e) {
            $this->error( $e->faultstring );
        }
  
    }
    
    /*
     * Creates an instance of the ExactTarget client from the 
     * official ExactTarget PHP library
     */
    public function get_client() {
        
        $wsdl = get_option( $this->slugify_name('wsdl_url'), null);
        
        if ( empty( $wsdl) )
            $this->error('Please specify an API path.');

        $client = new ExactTargetSoapClient($wsdl, array('trace'=>1));
        $client->username = $this->settings->user_name;
        $client->password = $this->settings->password;
        return $client;
    }
    
    /*
     * Creates a new attribute to send along with 
     * the email address
     */
    public function get_attribute($name, $value) {
        $attribute = new ExactTarget_Attribute();
        $attribute1->Name = $name;
        $attribute1->Value = $value;
        return $attribute;
    }
    
    /*
     * In adition to the default form fields in the base class,
     * add another one to specify the WSDL url
     */
    public function get_form_fields() {
        $default_fields = parent::get_form_fields();
        
        $default_fields[] = new TextField( $this->slugify_name('wsdl_url'), 
            'WSDL Url', array( 'value' => get_option( 
                $this->slugify_name('wsdl_url') )) );
            
        $default_fields[] = new TextField( $this->slugify_name('extra_param_name'), 
            'Extra Param Name', array( 'value' => get_option( 
                $this->slugify_name('extra_param_name') )) );
            
        $default_fields[] = new TextField( $this->slugify_name('extra_param_value'), 
            'Extra Param Value', array( 'value' => get_option( 
                $this->slugify_name('extra_param_value') )) );
                
        return $default_fields;
    }

}