<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Update file
 *
 * The upd file contains the methods for installing the add-on
 * We're using a unique approach to this because our Accessory
 * and Module cannot function without the Extension and vise versa
 * so we're installing everything within what is typically just the module
 * installation file.
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

require_once(PATH_THIRD . 'deployment_hooks/config/deployment_hooks.php');

class Deployment_hooks_upd { 
	
	/**
	 * @var string  add-on version number
	 */
	var $version = DH_VERSION;
	
	
	
	
	/**
	 * Class constructor
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function __construct() 
	{ 
		$this->_EE =& get_instance();
		// EE's use of CI's loader is crazy buggy. I shouldn't have to load resources this way. It gets around the bugs though.
		$this->_EE->load->model('../third_party/deployment_hooks/models/deployment_hooks_setup_model','Deployment_hooks_setup_model');
		$this->_EE->load->config('../third_party/deployment_hooks/config/deployment_hooks.php');
	}
	// End function __construct()
	
	
	
	
	/**
	 * Install our add-on
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	public function install() 
	{
		$this->_install_module();
		$this->_install_extension();
		$this->_install_accessory();
		return TRUE;
	}
	// End function install()
	
	
	
	
	/**
	 * Uninstall add-on
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	public function uninstall()
	{
		$this->_uninstall_module();
		$this->_uninstall_extension();
		$this->_uninstall_accessory();
		return TRUE;
	}
	// End function uninstall()
	
	
	
	
	/**
	 * Update add-on
	 * 
	 * Run each update necessary between version updates
	 * 
	 * @param      string  current version number
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	public function update($current = '')
	{
		return FALSE;
	}
	// End function update()
	
	
	
	
	/**
	 * Install module
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _install_module()
	{
		
		// Actions for our install transaction
		$actions = array(
			array(
				'class'   => 'Deployment_hooks_mcp',
				'method'  => 'deployment_pre_hook'
			),
			array(
				'class'   => 'Deployment_hooks_mcp',
				'method'  => 'deployment_post_hook'
			)
		);
		
		// Install our module
		$this->_EE->Deployment_hooks_setup_model->insert_module($this->_EE->config->item('dh:module_data'),$actions);
		// Build our custom db table
		$this->_EE->Deployment_hooks_setup_model->create_dh_table();
		
		return TRUE;	
	}
	// End function _install_module()
	
	
	
	
	/**
	 * Install extension
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _install_extension()
	{
		$this->_EE->Deployment_hooks_setup_model->insert_extension($this->_EE->config->item('dh:ext_hook'));
	}
	// End function _install_extension()
	
	
	
	
	/**
	 * Install accessory
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _install_accessory()
	{
		// nothin special here. not yet at least.
	}
	// End function _install_accessory()
	
	
	
	
	/**
	 * Uninstall module
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _uninstall_module()
	{
		$this->_EE->Deployment_hooks_setup_model->delete_module();
		if ( ! $this->_EE->config->item('dh:dev_mode'))
		{
			$this->_EE->Deployment_hooks_setup_model->drop_dh_table();
		}
	}
	// End function _uninstall_module()
	
	
	
	
	/**
	 * Uninstall extension
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _uninstall_extension()
	{
		$this->_EE->Deployment_hooks_setup_model->delete_extension();
	}
	// End function _uninstall_extension()
	
	
	
	
	/**
	 * Install accessory
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _uninstall_accessory()
	{
		// nothin special here. not yet at least.	
	}
	// End function _uninstall_accessory()
	
}
// End class Deployment_hooks_upd

/* End of file upd.deployment_hooks.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/upd.deployment_hooks.php */