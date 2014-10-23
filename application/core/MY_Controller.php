<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Base classes similar to the methods described by Phil Sturgeon
 * See http://philsturgeon.co.uk/blog/2010/02/CodeIgniter-Base-Classes-Keeping-it-DRY
 */

class MY_Controller extends MX_Controller {

    /**
     * Common data
     */
    public $settings;
    public $header_data;
    public $current_uri;
    public $error;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load core model
        $this->load->model('core_model');

        // get settings
        $settings = $this->core_model->get_settings();
        $this->settings = new stdClass();
        foreach ($settings as $setting)
            $this->settings->{$setting['name']} = $setting['value'];

        // get current uri
        $this->current_uri = "/" . uri_string();

        // Set global header data - can be merged with or overwritten in module controllers
        $this->header_data = array(
            'site_title'    => $this->settings->site_name,
            'site_version'  => $this->config->item('site_version'),
            'keywords'      => $this->settings->meta_keywords,
            'description'   => $this->settings->meta_description,
            'css_files'     => array(),
            'js_files'      => array(),
            'js_files_i18n' => array()
        );

        // set the time zone
        $timezones = $this->config->item('timezones');
        date_default_timezone_set($timezones[$this->settings->timezones]);

        // enable the profiler?
        $this->output->enable_profiler($this->config->item('profiler'));
    }

}


/**
 * Base Public Class - used for all public pages
 */
class Public_Controller extends MY_Controller {

    /**
     * @var
     */
    public $public_nav;
    public $private_nav;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the public and private navigation
        $this->public_nav  = $this->core_model->get_nav('public', 2, $this->current_uri);
        $this->private_nav = $this->core_model->get_nav('private', 2, $this->current_uri);
    }

}


/**
 * Base Private Class - used for all private pages
 */
class Private_Controller extends MY_Controller {

    /**
     * @var
     */
    public $user;
    public $public_nav;
    public $private_nav;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // must be logged in
        if ( ! $this->session->userdata('logged_in'))
        {
            if (current_url() != base_url())
            {
                //store requested URL to session - will load once logged in
                $data = array('redirect' => current_url());
                $this->session->set_userdata($data);
            }

            redirect('login');
        }

        // get current user
        $this->user = $this->session->userdata('logged_in');

        // load the public and private navigation
        $this->public_nav  = $this->core_model->get_nav('public', 2, $this->current_uri);
        $this->private_nav = $this->core_model->get_nav('private', 2, $this->current_uri);
    }

}


/**
 * Base Admin Class - used for all administration pages
 */
class Admin_Controller extends MY_Controller {

    /**
     * @var
     */
    public $user;
    public $admin_nav;


    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();

        // load the configured admin theme
        $this->load->set_theme($this->load->admin_theme); // could also do $this->load->set_theme($this->config->item('default_admin_theme')), but the current method is shorter

        // must be logged in
        if ( ! $this->session->userdata('logged_in'))
        {
            if (current_url() != base_url())
            {
                //store requested URL to session - will load once logged in
                $data = array('redirect' => current_url());
                $this->session->set_userdata($data);
            }

            redirect('login');
        }

        // make sure this user is setup as admin
        $this->user = $this->session->userdata('logged_in');
        if ( ! $this->user['is_admin'])
            redirect(base_url());

        // load the admin language file
        $this->lang->load('admin');

        // load the admin navigation
        $this->admin_nav = $this->core_model->get_nav('admin', 1, $this->current_uri);

        // set up global header data
        $this->header_data = array_merge_recursive($this->header_data, array(
            'css_files'     => array(
                base_url("themes/admin/css/admin.css")
            ),
            'js_files_i18n' => array(
                $this->jsi18n->translate("/themes/admin/js/admin_i18n.js")
            )
        ));
    }

}


/**
 * Base API Class - used for all API calls
 */
class API_Controller extends MY_Controller {

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

}
