<?php
require_once('base.php');

class Listrack extends BaseConnector {
    public $name = "Listrack";

    /*
     * Our Constructor
     */
    public function Listrack() {

        parent:: __construct();

        //set the location of the temp file
        $this->cookie_file = sys_get_temp_dir() . "/" . $this->cookie_file;

        /*
         * To Do: make this dynamic so we don't alwyas have to add
         * a field
         */
        $this->post_url = get_option(
            $this->slugify_name('post_url') );

        $this->token = get_option(
            $this->slugify_name('token') );


    }

    /*
     * The main signup function that is automatically called
     * with a successful ajax request
     */
    public function signup() {

        extract($_POST);

        if ( empty($email) )
            $this->error('missing email');

        if ( wp_verify_nonce($_nonce, 'email-signup') !== 1 )
            $this->error('spam');

        $data = array(
            'email' => $email,
            'crvs' => $this->token,
            'CheckBox.Source.Modal' => 'off'
        );

        $curl = curl_init($this->post_url);
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLINFO_HEADER_OUT => 1));
        $result = curl_exec($curl);

        if ( stristr($result, 'Thank you') !== false)
            $this->success();

        $this->error('error');


    }

    /*
     * We need one param - the form post url
     */
    public function get_form_fields() {

        return array(

            new TextField($this->slugify_name('post_url'),
                'Listrack Post URL', array(
                    'value' => get_option( $this->slugify_name('post_url') ),
                    'id' => $this->slugify_name('post-url') )),


            new TextField($this->slugify_name('token'),
                'Listrack Form Token', array(
                    'value' => get_option( $this->slugify_name('token') ),
                    'id' => $this->slugify_name('token') )),



        );
    }



}


?>
