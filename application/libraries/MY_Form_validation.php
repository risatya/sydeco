<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This is a fix to allow form validation callback functions to play nice with Modular Extensions
 * See http://www.mahbubblog.com/php/form-validation-callbacks-in-hmvc-in-codeigniter/
 */

class MY_Form_validation extends CI_Form_validation {
    function run($module = '', $group = '') {
        (is_object($module)) && $this->CI =& $module;
        return parent::run($group);
    }
}

/* End of file MY_Form_validation.php */
/* Location: ./application/libraries/MY_Form_validation.php */