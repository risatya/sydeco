<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends Public_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the language file
        $this->lang->load('users');

        // load the users model
        $this->load->model('users_model');
    }


    /**************************************************************************************
     * PUBLIC FUNCTIONS
     **************************************************************************************/


    /**
	 * Registration Form
     */
	public function index()
	{
        // validators
        $this->form_validation->set_error_delimiters($this->config->item('error_delimeter_left'), $this->config->item('error_delimeter_right'));
        $this->form_validation->set_rules('username', lang('admin input username'), 'required|trim|xss_clean|min_length[5]|max_length[30]|callback__check_username');
        $this->form_validation->set_rules('first_name', lang('users input first_name'), 'required|trim|xss_clean|min_length[2]|max_length[32]');
        $this->form_validation->set_rules('last_name', lang('users input last_name'), 'required|trim|xss_clean|min_length[2]|max_length[32]');
        $this->form_validation->set_rules('email', lang('users input email'), 'required|trim|xss_clean|max_length[128]|valid_email|callback__check_email');
        $this->form_validation->set_rules('password', lang('users input password'), 'required|trim|xss_clean|min_length[5]');
        $this->form_validation->set_rules('password_repeat', lang('users input password_repeat'), 'required|trim|xss_clean|matches[password]');

        if ($this->form_validation->run($this) == TRUE)
        {
            // save the changes
            $validation_code = $this->users_model->create_profile($this->input->post());

            if ($validation_code)
            {
                // build the validation URL
                $encrypted_email = sha1($this->input->post('email', TRUE));
                $validation_url  = base_url('user/validate') . "?e={$encrypted_email}&c={$validation_code}";

                // build email
                $email_msg  = lang('core email start');
                $email_msg .= sprintf(lang('users msg email_new_account'), $this->settings->site_name, $validation_url, $validation_url);
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
                $this->email->subject(sprintf(lang('users msg email_new_account_title'), $this->input->post('first_name', TRUE)));
                $this->email->message($email_msg);
                $this->email->send();
                #echo $this->email->print_debugger();

                $this->session->set_flashdata('message', sprintf(lang('users msg register_success'), $this->input->post('first_name', TRUE)));
            }
            else
            {
                $this->session->set_flashdata('error', lang('users error register_failed'));
            }

            // redirect home and display message
            redirect(base_url());
        }

        // setup page header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'page_title' => lang('users title register')
        ));
        $data = $this->header_data;

        // set content data
        $content_data = array(
            'cancel_url'        => base_url(),
            'user'              => NULL,
            'password_required' => TRUE
        );

        // load views
        $data['content'] = $this->load->view('profile_form', $content_data, TRUE);
        $this->load->view('template', $data);
	}


    /**
     * Validate new account
     */
    public function validate()
    {
        // get codes
        $encrypted_email = $this->input->get('e');
        $validation_code = $this->input->get('c');

        // validate account
        $validated = $this->users_model->validate_account($encrypted_email, $validation_code);

        if ($validated)
            $this->session->set_flashdata('message', lang('users msg validate_success'));
        else
            $this->session->set_flashdata('error', lang('users error validate_failed'));

        redirect(base_url());
    }


    /**************************************************************************************
     * PRIVATE VALIDATION CALLBACK FUNCTIONS
     **************************************************************************************/


    /**
     * Make sure username is available
     *
     * @param string $username
     * @return bool|int
     */
    function _check_username($username)
    {
        if ($this->users_model->username_exists($username))
        {
            $this->form_validation->set_message('_check_username', sprintf(lang('users error username_exists'), $username));
            return FALSE;
        }
        else
            return $username;
    }


    /**
     * Make sure email is available
     *
     * @param string $email
     * @return bool|int
     */
    function _check_email($email)
    {
        if ($this->users_model->email_exists($email))
        {
            $this->form_validation->set_message('_check_email', sprintf(lang('users error email_exists'), $email));
            return FALSE;
        }
        else
            return $email;
    }

}