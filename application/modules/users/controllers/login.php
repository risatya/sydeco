<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends Public_Controller {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // load the users model
        $this->load->model('users_model');

        // load the users language file
        $this->lang->load('users');
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
     * Validate login credentials
     */
    public function index()
    {
        if ($this->session->userdata('logged_in'))
        {
            $logged_in_user = $this->session->userdata('logged_in');
            if ($logged_in_user['is_admin'])
                redirect('admin');
            else
                redirect(base_url());
        }

        // set form validation rules
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('username', lang('users input username_email'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', lang('users input password'), 'required|trim|xss_clean|callback__check_login');

        if ($this->form_validation->run($this) == TRUE)
        {
            if ($this->session->userdata('redirect'))
            {
                $redirect = $this->session->userdata('redirect');
                $this->session->unset_userdata('redirect');
                redirect($redirect);
            }
            else
            {
                $logged_in_user = $this->session->userdata('logged_in');
                if ($logged_in_user['is_admin'])
                    redirect('admin');
                else
                    redirect(base_url());
            }
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title' => lang('users title login'),
            'css_files'  => array(
                '/themes/default/css/login.css'
            )
        ));
        $data = $this->header_data;

        // load views
        $data['content'] = $this->load->view('login', NULL, TRUE);
        $this->load->view('template', $data);
    }


    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/


    /**
     * Verify the login credentials
     *
     * @param $password
     * @return bool
     */
    function _check_login($password)
    {
        $login = $this->users_model->login($this->input->post('username', TRUE), $password);

        if ($login)
        {
            $this->session->set_userdata('logged_in', $login);
            return TRUE;
        }

        $this->form_validation->set_message('_check_login', lang('users error invalid_login'));
        return FALSE;
    }

}
