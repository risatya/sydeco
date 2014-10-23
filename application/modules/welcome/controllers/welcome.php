<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the language file
        $this->lang->load('welcome');
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
	 * Default
     */
	public function index()
	{
        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title' => sprintf(lang('welcome title'), $this->settings->site_name)
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'welcome_message' => $this->settings->welcome_message
        );

        // load views
        $data['content'] = $this->load->view('welcome', $content_data, TRUE);
		$this->load->view('template', $data);
	}

}
