<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Model
 *
 * Database transactions for Deployment Hooks
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Deployment_hooks_model {
	
	/**
	 * @var object  the EE "superobject"
	 */
	private $_EE;
	
	
	
	
	/**
	 * Constructor
	 * 
	 * @access    public
	 * @author    Erik Reagan  <erik@focuslabllc.com>
	 * @return    void
	 */
	public function __construct()
	{
		$this->_EE =& get_instance();
	}
	// End function __construct()
	
	
	
	
	/**
	 * Get recent deployment posts
	 * 
	 * Used in the accessory and MCP file
	 * 
	 * @param      int  Limit to return
	 * @param      int  Offset
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     object
	 */
	public function get_recent_dh_posts($limit = 10, $offset = 0)
	{
		return $this->_EE->db->order_by('deploy_timestamp', 'desc')
									->get('exp_deployment_hook_posts', $limit, $offset);
	}
	// End function get_recent_dh_posts()
	
	
	
	
	/**
	 * Get add-on settings
	 * 
	 * Get our add-on settings from the extensions table
	 * Used in the MCP file
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     object
	 */
	public function get_settings()
	{
		return $this->_EE->db->select('settings')
									->where('enabled', 'y')
									->where('class', 'Deployment_hooks_ext')
									->limit(1)
									->get('extensions');
	}
	// End function get_settings()
	
	
	
	
	/**
	 * Get hook use
	 * 
	 * Used to see what extensions are installed for
	 * each hook in our module
	 * 
	 * @param     string  Hook to look for
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    object
	 */
	public function get_hook_use($hook = NULL)
	{
		return $this->_EE->db->group_by('class')
									->get_where('extensions',array('hook' => $hook));
	}
	// End function get_hook_use()
	
	
	
	
	/**
	 * Get total deployment posts
	 * 
	 * This number is used for pagination on the Log page only (MCP file)
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     int
	 */
	public function get_deployment_post_count()
	{
		return $this->_EE->db->count_all('deployment_hook_posts');
	}
	// End function get_deployment_post_count()
	
	
	
	
	/**
	 * Log deployment details after hook execution
	 * 
	 * Used in the MCP file only
	 * 
	 * @param     array  table data to insert
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function insert_deployment_log_post($data = NULL)
	{
		$query = $this->_EE->db->insert_string('deployment_hook_posts', $data);
		$this->_EE->db->query($query);
	}
	// End function insert_deployment_log_post()
	
}
// End class Deployment_hooks_model

/* End of file deployment_hooks_model.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/models/deployment_hooks_model.php */