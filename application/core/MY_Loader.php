<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH . "third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {

    /**
     * @var string
     */
    public $theme;
    public $admin_theme;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // load the core config file so we can set the default themes
        CI::$APP->config->load('core');
        $this->theme = CI::$APP->config->item('default_theme');
        $this->admin_theme = CI::$APP->config->item('default_admin_theme');
        $this->set_theme($this->theme);
    }


    /**
     * Change the theme
     *
     * @param $theme_name
     */
    public function set_theme($theme_name)
    {
        $this->theme = $theme_name;
    }


    /**
     * Override the Modular Extensions view function to allow theme overrides
     *
     * @param $view
     * @param array $vars
     * @param bool $return
     * @return string|void
     */
    public function view($view, $vars = array(), $return = FALSE) {
        $this->_ci_view_paths += array(FCPATH . 'themes/' . $this->theme . '/views/' => TRUE);

        list($path, $_view) = Modules::find($view, $this->_module, 'views/');

        if ($path != FALSE)
        {
            $this->_ci_view_paths += array($path => TRUE);
            $view = $_view;
        }

        return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    }

}