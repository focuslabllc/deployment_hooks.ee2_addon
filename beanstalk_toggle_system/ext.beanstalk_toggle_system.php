<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Beanstalk Toggle System
 * 
 * Extension to automatically turn the EE system off and back on
 * based on web hook requests from the beanstalkapp.com repo deployments
 * Requires Deployment Hooks module
 * 
 * This is a sample extension for Deployment Hooks. The code isn't meant to be perfect. ;)
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Beanstalk_toggle_system_ext {
	
	/**
	 * @var string  Extension name
	 */
	public $name = 'Beanstalk Toggle System';
	
	
	/**
	 * @var string  Version number
	 */
	public $version = '1.0.0';
	
	
	/**
	 * @var string  Description of extension
	 */
	public $description = 'Uses the Deployment Hooks Module to automatically turn the EE system off and back on before and after deployments';
	
	
	/**
	 * @var string  Do settings exist? (y|n)
	 */
	public $settings_exist = 'n';
	
	
	/**
	 * @var string  Documentation URL
	 */
	public $docs_url = '';
	
	
	/**
	 * @var array  Extension settings array
	 */
	public $settings = array();
	
	
	/**
	 * @var object  EE "superobject"
	 */
	private $_EE;
	
	
	
	
	/**
	 * Constructor
	 *
	 * @param      mixed  Settings array or empty string if none exist.
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	function __construct($settings = '')
	{
		$this->_EE =& get_instance();
		$this->settings = $settings;	
	}
	// End function __construct()
	
	
	
	
	/**
	 * Turn the EE system off in the database
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function turn_system_off()
	{
		return $this->_toggle_system('off');
	}
	// End function turn_system_off()
	
	
	
	
	/**
	 * Turn the EE system on in the database
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function turn_system_on()
	{
		return $this->_toggle_system('on');
	}
	// End function turn_system_on()
	
	
	
	
	/**
	 * Alter the session object
	 * 
	 * Turn the system off in a clever way.
	 *
	 * @param      object  The session object
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     object  The session object
	 */
	public function sessions_end($sess)
	{
		
		// Conditional for "safety" check. If for any strange reason the Pre deployment hook is
		// executed and the system is turned off with this hook, but the Post deployment hook
		// Does not execute - we need a "backup" to get the system running. That backup is by
		// adding $config['disable_beanstalk_system_toggle'] = 'y' to the config.php file
		if ($this->_EE->config->config['disable_beanstalk_system_toggle'] !== 'y')
		{
			$this->_EE->config->config['is_system_on'] = 'n';
		}
		
		return $sess;
		
	}
	// End function sessions_end()
	
	
	
	
	/**
	 * Toggle the system (in a way)
	 *
	 * @param      string  (on|off)
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @access     private
	 * @return     void
	 */
	private function _toggle_system($which = 'on')
	{
		$response = array();
		
		// Because of how Beanstalk sends data we need to grab the data before moving on
		// We only process anything if the PHP input isn't empty and is decoded from json to an object
		// http://help.beanstalkapp.com/kb/deployments/web-hooks-for-deployments
		$data = file_get_contents('php://input');
		
		// To use test data uncomment the following block:
		// $data = '{
		// 	"author":"author username",
		// 	"repository":"beanstalk",
		// 	"author_name":"John Smith",
		// 	"comment":"example",
		// 	"author_email":"johnsmith@example.com",
		// 	"server":"server example",
		// 	"environment":"development",
		// 	"revision":"5aslkjdhl98yq3uihlkjhalks",
		// 	"deployed_at":"deployed at date",
		// 	"repository_url":"git@example.beanstalkapp.com:/example.git",
		// 	"source":"beanstalkapp"
		// }';
		
		if ( ! empty($data))
		{
			// use json_decode() to turn our json string into an object
			// Requires PHP 5.2.0
			$json_obj = json_decode($data);
			
			// Verify the data worked out as expected.
			if (is_object($json_obj))
			{
				
				// We're good. Toggle the system now
				// You could also run additional checks against the data provided for the sake of security
				
				// Since we're keeping things "DRY" we're using a single method to both enable and disable
				// this hook in our extension. The hook/method installed "disbaled" so we're simply changing
				// a 'y' to an 'n' or vise versa. Here's where we actually make that decision.
				
				$where_enabled = ($which == 'off') ? 'n' : 'y' ;
				$update_enabled = ($which == 'off') ? 'y' : 'n' ;
				
				// Update our extension hook for sessions_end
				// effectively turning the system on and off via a 3rd party extenision
				$this->_EE->db->where(
										array(
											'class'   => __CLASS__,
											'enabled' => $where_enabled,
											'hook'    => 'sessions_end'
										)
									 )
								  ->update('extensions',array('enabled' => $update_enabled));
				
				$response[] = $this->name . ": Successfully turned ".$which." the system after rev <code>" . substr($json_obj->revision, 0, 8) . "</code>";
			} else {
				// PHP input didn't come in json so we assume there was an error or a bad request
				$response[] = $this->name . ": Data not received in the expected format. Not executing any code.";
			}
		} else {
			// if we're here then we aren't in debug mode, we don't have a revision file OR no POST data has been sent
			$response[] = $this->name . ": Data returned empty. (<code>php://input</code>)";
		}
		
		return $response;
	}
	// End function _toggle_system()
	
	
	
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function activate_extension()
	{
		
		$hooks = array(
			array(
				'class'    => __CLASS__,
				'method'   => 'turn_system_off',
				'hook'     => 'deployment_hooks_pre_deploy',
				'settings' => '',
				'priority' => 10,
				'version'  => $this->version,
				'enabled'  => 'y'
			),
			array(
				'class'    => __CLASS__,
				'method'   => 'turn_system_on',
				'hook'     => 'deployment_hooks_post_deploy',
				'settings' => '',
				'priority' => 10,
				'version'  => $this->version,
				'enabled'  => 'y'
			),
			array(
				'class'    => __CLASS__,
				'method'   => 'sessions_end',
				'hook'     => 'sessions_end',
				'settings' => '',
				'priority' => 10,
				'version'  => $this->version,
				'enabled'  => 'n' // notice this is installed but disabled. we use the deployment hooks to enable and disable this particular hook/method combo
			)
		);
		
		foreach ($hooks as $data) {
			$this->_EE->db->insert('extensions', $data);
		}
		
	}
	// End function activate_extension()
	
	
	
	
	/**
	 * Disable Extension
	 * 
	 * This method removes information from the exp_extensions table
	 *
	 * @access     public
	 * @return     void
	 */
	public function disable_extension()
	{
		$this->_EE->db->where('class', __CLASS__);
		$this->_EE->db->delete('extensions');
	}
	// End disable_extension()
	
}
// End class Beanstalk_toggle_system_ext

/* End of file ext.beanstalk_toggle_system.php */
/* Location: ./system/expressionengine/third_party/beanstalk_toggle_system/ext.beanstalk_toggle_system.php */