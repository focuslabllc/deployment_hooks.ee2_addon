<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Extension
 *
 * This extension is solely for storing settings for Deployment Hooks
 * We install with a fake hook because extensions can't install without
 * a hook apparently. Lovely.
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once(PATH_THIRD . 'deployment_hooks/config/deployment_hooks.php');

class Deployment_hooks_ext {
	
	/**
	 * @var string  Extension name
	 */
	public $name = 'Deployment Hooks Settings';
	
	
	/**
	 * @var string  Extension version number  
	 */
	public $version = DH_VERSION;
	
	
	/**
	 * @var string  Extension description
	 */
	public $description = 'Settings for Deployment Hooks module';
	
	
	/**
	 * @var string  Do Extensions exist? (y|n)
	 */
	public $settings_exist = 'y';
	
	
	/**
	 * @var string  Extensions documentation URL
	 */
	public $docs_url = 'http://focuslabllc.com/';
	
	
	/**
	 * @var array  Extension settings array
	 */
	public $settings = array();
	
	/**
	 * @var object  The EE super object to be referenced in our {@link __construct()}
	 */
	private $_EE;
	
	
	
	
	/**
	 * Constructor
	 *
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @param     mixed     Settings array or empty string if none exist.
	 * @return    void
	 */
	public function __construct($settings='')
	{
		$this->_EE =& get_instance();
		// Can't seem to load my model or config files the easy way due to path issues
		// Using the alternative
		// $this->_EE->load->model('Deployment_hooks_setup_model');
		// $this->_EE->load->config('deployment_hooks');
		$this->_EE->load->add_package_path(PATH_THIRD.'deployment_hooks/');
		$this->_EE->load->model('deployment_hooks_setup_model', 'Deployment_hooks_setup_model');
		$this->_EE->load->config('deployment_hooks');
	}
	// End function __construct()
	
	
	
	
	/**
	 * Basic settings method
	 * 
	 * This method is actually not called because we use a
	 * custom settings form ({@link settings_form()}) below
	 * We use the custom settings form to custom validate values
	 * and send the user to our module CP homepage after submission
	 * rather than the Utilities > Extensions page
	 *
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    array
	 */
	public function settings()
	{
		// Default settings are stored in config/deployment_hooks.php
		return $this->_EE->config->item('dh:default_settings');
	}
	// End function settings()
	
	
	
	
	/**
	 * Custom settings form
	 * 
	 * There's a nice description of why we use this
	 * in the {@link settings()} code block
	 *
	 * @param      array
	 * @access     public
	 * @return     void
	 */
	public function settings_form($current)
	{
		// We would load the form helper and table library but they already
		// seem to be avaiable within our view file so I commented them both out
		// $this->_EE->load->helper('form');
		// $this->_EE->load->library('table');
		
		$this->_EE->cp->set_variable('cp_page_title', lang('deployment_hooks_module_name') . ' ' . lang('settings'));
		
		// Setup our module's navigation elements
		// Menu is defined in our config file
		$this->_EE->cp->set_right_nav($this->_EE->config->item('dh:mod_menu'));
		
		$data['settings'] = $current;
		
		return $this->_EE->load->view('settings_form',$data,TRUE);
		
	}
	// End function settings_form()
	
	
	
	
	/**
	 * Save Add-on Settings
	 *
	 * Validate and process the add-on settings form
	 *
	 * @access     public
	 * @return     void
	 */
	public function save_settings()
	{
		if (empty($_POST))
		{
			show_error($this->_EE->lang->line('unauthorized_access'));
		}
		
		// We no need submit key/value
		unset($_POST['submit']);
		foreach ($_POST as $key => $value) {
			$_POST[$key] = $this->_EE->input->post($key,TRUE);
		}
		
		/**
		 * I'd love to have some form validation here but I can't seem to use
		 * the CI validation library callback functions within the context
		 * of extension settings. Skipping this for now.
		 * 
		 * @link http://expressionengine.com/forums/viewthread/187258/
		 */
		
		// if (FALSE)
		// {
		// 	// error occurred.
		// 	// display message and redirect to settings page.
		// 	$this->_EE->session->set_flashdata(
		// 		'message_failure', 
		// 		'message'
		// 	);
		// 	$this->_EE->functions->redirect(
		// 		BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=deployment_hooks'
		// 	);
		// }
		
		// Update settings in the db
		$this->_EE->Deployment_hooks_setup_model->update_settings(serialize($_POST));
		
		// Success, redirect to module home page with success message
		$this->_EE->session->set_flashdata(
			'message_success',
			$this->_EE->lang->line('dh:settings_saved')
		);
		$this->_EE->functions->redirect($this->_EE->config->item('dh:mod_url_base'));
		
	}
	// End function save_settings()
	
	
	
	
	/**
	 * Validate GET settings data
	 * 
	 * @param     string   Form submission value
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    bool
	 */
	public function _validate_get($str)
	{
		// exit(__METHOD__);
	}
	// End function validate_get()
	
	
	
	
	/**
	 * Validate GET settings data
	 * 
	 * @param     string   Form submission value
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    bool
	 */
	public function _validate_ips($str)
	{
		// exit(__METHOD__);
	}
	// End function validate_get()
	
	
	
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 * Or does it.....(see below)
	 *
	 * @access    public
	 * @return    void
	 */
	public function activate_extension()
	{
		
		/**
		 * We actually have to install with at least 1 hook otherwise the extension
		 * doesn't actually get installed. It makes sense to a degree - but we're just
		 * using the extension file to store our add-on's settings. Hopefully
		 * one day this won't exist.
		 * @link http://expressionengine.com/forums/viewthread/176691/
		 */
		
		/**
		 * We aren't using this to activate the extension. We can guarantee the installation of
		 * our extension by doing this in the module UPD file. That way a user can't choose to not
		 * install the extension which would kill our settings approach. So, nothing to see here.
		 */
		
	}
	// End function activate_extension()
	
	
	
	
	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 * Or does it.....(see above)
	 *
	 * @access    public
	 * @return    void
	 */
	public function disable_extension()
	{
		// Nothing here either. See comments for activate_extension()
	}
	// End function disable_extension()
	
}
// End class Deployment_hooks_ext

/* End of file ext.deployment_hooks.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/ext.deployment_hooks.php */