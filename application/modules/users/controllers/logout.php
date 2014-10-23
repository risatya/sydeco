<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends Public_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * Logout
     */
    public function index()
    {
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        redirect('login');
    }

}