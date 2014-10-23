<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Forgot extends Public_Controller {

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
     * Prompts user for email to send reset password email to
     */
    function index()
    {
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('email', lang('users input email'), 'required|trim|xss_clean|valid_email|callback__check_email');

        if ($this->form_validation->run($this) == TRUE)
        {
            // save the changes
            $results = $this->users_model->reset_password($this->input->post());

            if ($results)
            {
                // build email
                $reset_url  = base_url('login');
                $email_msg  = lang('core email start');
                $email_msg .= sprintf(lang('users msg email_password_reset'), $this->settings->site_name, $results['new_password'], $reset_url, $reset_url);
                $email_msg .= lang('core email end');

                // send email
                $this->load->library('email');
                $config['protocol'] = 'sendmail';
                $config['mailpath'] = '/usr/sbin/sendmail -f' . $this->settings->site_email;
                $this->email->initialize($config);
                $this->email->clear();
                $this->email->from($this->settings->site_email, $this->settings->site_name);
                $this->email->reply_to($this->settings->site_email, $this->settings->site_name);
                $this->email->to($this->input->post('email', TRUE));
                $this->email->subject(sprintf(lang('users msg email_password_reset_title'), $results['first_name']));
                $this->email->message($email_msg);
                $this->email->send();
                #echo $this->email->print_debugger();

                $this->session->set_flashdata('message', sprintf(lang('users msg password_reset_success'), $results['first_name']));
            }
            else
            {
                $this->session->set_flashdata('error', lang('core error process'));
            }

            // redirect home and display message
            redirect(base_url());
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title' => lang('users title forgot')
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'cancel_url' => base_url('login'),
            'user'       => NULL
        );

        // load views
        $data['content'] = $this->load->view('forgot_form', $content_data, TRUE);
        $this->load->view('template', $data);
    }


    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/


    /**
     * Make sure email exists
     *
     * @param string $email
     * @return bool|int
     */
    function _check_email($email)
    {
        if ( ! $this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users error email_not_exists'), $email));
            return FALSE;
        }
        else
            return $email;
    }

}
