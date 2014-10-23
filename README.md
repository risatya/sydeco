ci-fire-starter
===============

##INTRODUCTION

CI Fire Starter is simply a basic framework for starting a new project that doesn't have a complex API to learn or extra
functionality you probably don't need. It is not a CMS nor is it an application builder. It is a skeleton application
that has the basics already included and is ready for you, as a developer, to actually do what you do best... develop.
Here is what's included:

* CodeIgniter 2.2.0
* Modular Extensions by wiredesignz (includes form validation callback fix)
* Single base controller with Public, Private, Admin and API classes
* JSi18n Library to support internationalization in your JS files
* The latest version of jQuery
* Bootstrap 3
* Basic templating with automatic module view overrides
* Includes auto-loaded core config file
* Includes auto-loaded core language file
* Includes auto-loaded core helper file
    + Human-readable JSON string output for API functions
    + Array to CSV exporting
* Basic admin tool with authentication, dashboard, user management and contact email list

That should be the absolute basic things you need to get started on most projects. While there are many great CMS apps
(see below), sometimes you don't need a full CMS, or you need something much simpler than what's available, or you need
a completely customizable solution. That's why I created CI Fire Starter. I was tired of always having to do the same
things over and over again. So I took some best practices, included all the addons and functions I most commonly use,
and this was the end result, which I now use to start all my projects.

Please note I do NOT take credit for many of the methods used here... attribution is given where it is deserved. See
actual code for attribution comments.

I hope you find it useful. Feel free to fork it on Github and help make it better.

NOTE: This documentation assumes you are already familiar with PHP and CodeIgniter. If you need to learn more about
CodeIgniter, visit the user guide at http://ellislab.com/codeigniter/user-guide/. To learn more about PHP, visit
http://php.net/.

![Welcome Screen](http://s2.postimg.org/io7hm3qqh/Screen_Shot_2013_10_08_at_2_20_04_PM.png?raw=true)

##MODULAR

CI Fire Starter is set up to be modular, thanks to wiredesign's Modular Extensions
(https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc). The modules are located in
/application/modules.

CI Fire Starter comes with 4 modules:
* api - a place to build API functions
* contact - a simple contact page with CAPTCHA
* users - a basic authentication module for registering and logging in and out
* welcome - the default landing page

In addition to the modules, there are some core functions:
* admin


##BASE CLASSES

Phil Sturgeon wrote a very helpful blog post years ago called "CodeIgniter Base Classes: Keeping it DRY"
(http://philsturgeon.co.uk/blog/2010/02/CodeIgniter-Base-Classes-Keeping-it-DRY). The methods he described have been
applied to CI Fire Starter. There is a file in /application/core called MY_Controller.php which includes the Public,
Private, Admin and API base classes. This allows you to use shared functionality.

####MY_Controller

This extends the MX (Modular Extensions) controller and defines header data that will get passed to views as well as has
a setting to enable or disable the CI Profiler.

####Understanding Header Data

* site_title    : the title of the website which gets inserted into the document head
* keywords      : meta keywords inserted into the document head
* description   : meta description inserted into the document head
* css_files     : an array of css files to insert into the document head
* js_files      : an array of javascript libraries to insert into the document body
* js_files_i18n : an array of javascript files with internationalization to insert into the document body (see more about these below)

Header data can be appended and/or overwritten from any controller function.

####Public_Controller

This extends MY_Controller and drives all your public pages (home page, etc). Any controller that extends
Public_Controller will be open for the whole world to see.

####Private_Controller

This extends MY_Controller and drives all your private pages (user profile, etc). Any controller that extends
Private_Controller will require authentication. Specific page requests are stored in session and will redirect upon
successful authentication.

####Admin_Controller

This extends MY_Controller and drives all your administration pages. Any controller that extends Admin_Controller will
require authentication from a user with administration privileges. Specific page requests are stored in session and will
redirect upon successful authentication.

####API_Controller

This extends MY_Controller and drives all your API functions. Any controller that extends API_Controller will be open
for the whole world to see (see below for details).



##CORE FILES

####Core Config

In application/config there is a file core.php. This file allows you to set site-wide variables. It is set up with site
name, site version, default templates, pagination settings, enable/disable the profiler and error delimiters.

####Core Language

In application/language/english is a file core_lang.php. This file allows you to set language variables that could be
used throughout the entire site (such as the words Home or Logout).

####Core Helper

In application/helper is a file core_helper.php. This includes the following useful functions:
* display_json($array) - used to output an array as JSON in a human-readable format - used by the API
* json_indent($array) - this is the function that actually creates the human-readable JSON string
* array_to_csv($array, $filename) - exports an array into a CSV file (see admin user list)



##LIBRARIES

####Jsi18n

In application/libraries is a file Jsi18n.php. This clever library allows you to internationalize your JavaScript files
through CI language files and was inspired by Alexandros D on coderwall.com (https://coderwall.com/p/j88iog).

Load this library in the autoload.php file or manually in your controller:

    $this->load->library('jsi18n');

In your language file:

    $lang['alert_message'] = "This is my alert message!";

In your JS files, place your language key inside double braces:

    function myFunction() {
        alert("{{alert_message}}");
    }

Render the Javascript file in your template file:

    <script type="text/javascript"><?php echo $this->jsi18n->translate("/themes/admin/js/my_javascript_i18n.js"); ?></script>

OR in your header data array:

    $this->header_data = array_merge_recursive($this->header_data, array(
        'js_files_i18n' => array(
            $this->jsi18n->translate("/themes/admin/js/my_javascript_i18n.js")
        )
    ));

####MY_Form_validation

In application/libraries is a file My_Form_validation.php. This small library fixes the issue with validation callback
functions not working when using Modular Extensions. This library is automatically loaded, so the only difference is you
have to include $this in your validation:

    if ($this->form_validation->run() == FALSE)

becomes

    if ($this->form_validation->run($this) == FALSE)

You can see this being used in the auth module login controller. For more about this fix, check out Mahbubur Rahman's
blog (http://www.mahbubblog.com/php/form-validation-callbacks-in-hmvc-in-codeigniter/).



##USER MANAGEMENT

CI Fire Starter comes with a simple user management tool in the administration tool. It does use a database table called
'users'. This tool demonstrates a lot of basic but important functionality:

* Sortable list columns
* Search filters
* Pagination with user-changeable items/page
* Exporting lists to CSV
* Form validation
* Harnessing the power of Bootstrap to accelerate development

![User Administration](http://s12.postimg.org/5oc54gnnx/Screen_Shot_2013_10_08_at_2_21_23_PM.png?raw=true)

Please note: user 1 is the main administrator - DO NOT MANUALLY DELETE. You can not delete this user from within the
admin tool.



##THEMES

No, I did not include a templating library, such as Smarty, nor did I utilize CI's built in parser. If you really wanted
to include one, you could check out Phil Sturgeon's CI-Dwoo extension (https://github.com/philsturgeon/codeigniter-dwoo).
With that said, I did include the ability to override module views with theme views. See the two different
welcome_message.php files: a) application/modules/welcome/views/welcome_message.php and
b) themes/default/views/welcome_message.php. B will override A as long as it exists. This is handy if you need to make
a temporary change to a view and don't want to lose the original view. The overrides are handled from within
application/core/MY_Loader.php.

####Override Default Themes

In addition to overriding module views from within your theme, you can also override a theme from any controller:

    $this->set_theme('[THEME FOLDER]');



##APIS

With the API class, you can quite easily create API functions for external applications. There is no security on these,
so if you need a more robust solution, such as authentication and API keys, check out Phil Sturgeon's CI Rest Server
(https://github.com/philsturgeon/codeigniter-restserver).

![Sample JSON String](http://s18.postimg.org/w8wflbka1/Screen_Shot_2013_10_08_at_2_38_35_PM.png?raw=true)



##SYSTEM REQUIREMENTS

* PHP version 5.1.6 or newer
* A database: MySQL (4.1+), MySQLi, MS SQL, Postgres, Oracle, SQLite, and ODBC
* PHP GD extension for CAPTCHA to work
* PHP Mcrypt extension if you want to use the Encryption class



##INSTALLATION

This is really simple:

* Create a new database and import the included sql file
    + default administrator username/password is admin/admin
* Modify the application/config/config.php
    + line 183: set your log threshold - I usually set it to 1 for production environments
    + line 227: set your encryption key
* Modify the application/config/core.php
    + set your site name
* Modify application/config/database.php and connect to your database
* Upload all files to your server
* Make sure the /captcha/ folder has write permission
* Visit your new URL
    + The default welcome page includes links to the admin tool and the private user profile page
* Make sure you log in to admin and change the administrator password!



##CONCLUSION

Well, that's it in a nutshell. As I said earlier, CI Fire Starter does not attempt to be a full-blown CMS. You'd have
to build that functionality on your own. If you want a great CMS built on CodeIgniter, or need a more robust starting point, check out one of these
awesome apps:

* HeroFramework: http://www.heroframework.com/
* Halogy: http://www.halogy.com/
* PyroCMS: https://www.pyrocms.com/ (though last I heard, Phil Sturgeon was converting it from CodeIgniter to Laravel)
* Expression Engine: http://ellislab.com/expressionengine (from the creators of CodeIgniter)
* GoCart: http://gocartdv.com/ (shopping cart)
* Open-Blog: http://www.open-blog.org/ (this is my other project - currently working on a complete rewrite, and it's
  been slow going)
* Bonfire: http://cibonfire.com/ (this is more of an application builder than a full CMS)
* FuelCMS: http://getfuelcms.com/
* CMS Canvas: http://www.cmscanvas.com/



##WHAT'S NEW

####Version 2.1.0
07/25/2014

* Upgraded from CI 2.1.4 to CI 2.2.0

####Version 2.0.0
05/06/2014

Too many to list them all, but here are some of the major changes:

* Added database-driven settings administration
* Included TinyMCE WYSIWYG editor
* Included Bootstrap DatePicker and modified to work with Bootstrap 3.x
* Removed separate auth module and merged into user module
* Added user self-registration and forgot password functionality to the user module
* Removed separate login template
* Added database-driven menus with sub-menu capabilities and built-in Bootstrap formatting
* Added a CAPTCHA-protected contact page with an admin tool to view messages
* Enabled CSRF protection on all forms
* Enabled database session handling
* Tons of code cleanup and miscellaneous improvements

####Version 1.0.1
10/10/2013

* Removed admin template includes
* Made login more secure using salt
* Modified users table to handle the login change
    + password field is now char(128)
    + added salt field char(128)
* Added this what-new.txt file
* Added road-map.txt

####Version 1.0.0
10/08/2013

* Initial version



##ROADMAP

* Build a media manager module
