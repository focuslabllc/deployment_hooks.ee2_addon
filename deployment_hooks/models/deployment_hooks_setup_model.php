<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Setup Model
 *
 * Database transactions required for installing, uninstalling
 * and saving settings for the Deployment Hooks add-on 
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Deployment_hooks_setup_model {
	
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
		$this->_EE->load->dbforge();
	}
	// End function __construct()
	
	
	
	/**
	 * Save extension settings
	 * 
	 * Only used in our extension save_settings method
	 * 
	 * @param      array  Pre-filtered form results
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function update_settings($settings = NULL)
	{
		$this->_EE->db->where('class', 'deployment_hooks_ext')
						  ->update('extensions', array('settings' => $settings));	
	}
	// End function update_settings()
	
	
	
	
	/**
	 * Insert Extension
	 * 
	 * Activate extension
	 * This is usually in the ext file but I want to guarantee
	 * that the ext is installed with the module so it's in the
	 * upd file instead.
	 * 
	 * @param     array  Default extension settings
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function insert_extension($data)
	{
		$this->_EE->db->insert('extensions', $data);
	}
	// End function insert_extension()
	
	
	
	
	/**
	 * Delete Extension
	 * 
	 * Activate extension
	 * This is usually in the ext file but I want to guarantee
	 * that the ext is installed with the module so it's in the
	 * upd file instead.
	 * 
	 * @param     array  Default extension settings
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function delete_extension()
	{
		$this->_EE->db->where('class', 'Deployment_hooks_ext')
						  ->delete('extensions');
	}
	// End function delete_extension()
	
	
	
	
	/**
	 * Insert module into db
	 * 
	 * This installs the module & actions
	 * 
	 * @param     array  Module data
	 * @param     mixed  Multi-dimensional array of action install data or FALSE
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function insert_module($mod_data, $action_array = FALSE)
	{
		$this->_EE->db->insert('modules', $mod_data);
		// Actions
		if ($action_array)
		{
			foreach ($action_array as $action) {
				$this->_EE->db->insert('actions', $action);
			}
		}		
	}
	// End function insert_module()
	
	
	
	
	/**
	 * Create table
	 * 
	 * Use DB forge to create our new table
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function create_dh_table()
	{
		$table_fields = array(
			'deploy_id'        => array('type' => 'INT', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'deploy_ip'        => array('type' => 'VARCHAR', 'constraint' => '15'),
			'deploy_timestamp' => array('type' => 'VARCHAR', 'constraint' => '20'),
			'deploy_data'      => array('type' => 'TEXT')
		);
		
		$this->_EE->dbforge->add_field($table_fields);
		$this->_EE->dbforge->add_key('deploy_id', TRUE);
		
		$this->_EE->dbforge->create_table('deployment_hook_posts');	
	}
	// End function create_dh_table()
	
	
	
	
	/**
	 * Delete module from db
	 * 
	 * This deletes the module & actions
	 * 
	 * @param     array  Module data
	 * @param     mixed  Multi-dimensional array of action install data or FALSE
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function delete_module()
	{
		$this->_EE->db->select('module_id');
		$query = $this->_EE->db->get_where('modules', array('module_name' => 'Deployment_hooks'));
		
		$this->_EE->db->where('module_id', $query->row('module_id'));
		$this->_EE->db->delete('module_member_groups');
		
		$this->_EE->db->where('module_name', 'Deployment_hooks');
		$this->_EE->db->delete('modules');
		
		$this->_EE->db->where('class', 'Deployment_hooks_mcp');
		$this->_EE->db->delete('actions');
	}
	// End function delete_module()
	
	
	
	
	/**
	 * Drop database
	 * 
	 * Use DB forge to drop our custom table
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function drop_dh_table()
	{
		$this->_EE->dbforge->drop_table('deployment_hook_posts');
	}
	// End function drop_dh_table()
	
}
// End class Deployment_hooks_setup_model

/* End of file deployment_hooks_setup_model.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/models/deployment_hooks_setup_model.php */