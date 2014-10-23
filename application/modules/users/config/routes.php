<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Routes
 */

$route['login']         = "users/login/index";
$route['logout']        = "users/logout/index";
$route['profile']       = "users/profile/index";
$route['register']      = "users/register/index";
$route['user/validate'] = "users/register/validate";
$route['forgot']        = "users/forgot/index";
