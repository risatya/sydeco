<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * Dashboard
     */
    public function index()
    {
        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title' => lang('admin title admin')
        ));
        $data = $this->header_data;

        // load views
        $data['content'] = $this->load->view('admin/dashboard', NULL, TRUE);
        $this->load->view('admin_template', $data);
    }


    /**
     * Settings Editor
     */
    public function settings()
    {
        // get settings
        $settings = $this->core_model->get_settings();

        // form validations
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        foreach ($settings as $setting)
            $this->form_validation->set_rules($setting['name'], $setting['label'], $setting['validation']);

        if ($this->form_validation->run($this) == TRUE)
        {
            $user = $this->session->userdata('logged_in');

            // save the settings
            $saved = $this->core_model->save_settings($this->input->post(), $user['id']);

            if ($saved)
            {
                $this->session->set_flashdata('message', lang('admin settings msg save_success'));

                // reload the new settings
                $settings = $this->core_model->get_settings();
                foreach ($settings as $setting)
                    $this->settings->{$setting['name']} = $setting['value'];
            }
            else
                $this->session->set_flashdata('error', lang('admin settings error save_failed'));

            // reload the page
            redirect('admin/settings');
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title' => lang('admin settings title')
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'settings'   => $settings,
            'cancel_url' => "/admin",
        );

        // load views
        $data['content'] = $this->load->view('admin/settings_form', $content_data, TRUE);
        $this->load->view('admin_template', $data);
    }

}
