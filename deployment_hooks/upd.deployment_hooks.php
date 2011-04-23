<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Update file
 *
 * The upd file contains the methods for installing the add-on
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Deployment_hooks_upd { 
	
	/*
		TODO 	use config file here
	*/
	/**
	 * @var string  add-on version number
	 */
	var $version = '1.0.0'; 
	
	
	
	
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
	}
	// End function __construct()
	
	
	
	
	/**
	 * Install our add-on
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	function install() 
	{
		/*
			TODO 	use config file here
		*/
		$data = array(
			'module_name'        => 'Deployment_hooks',
			'module_version'     => $this->version,
			'has_cp_backend'     => 'y',
			'has_publish_fields' => 'n'
		);
		/*
			TODO 	use model file where db transaction occurs
		*/
		$this->_EE->db->insert('modules', $data);
		
		$pre_hook = array(
			'class'   => 'Deployment_hooks_mcp',
			'method'  => 'deployment_pre_hook'
		);
		
		$post_hook = array(
			'class'   => 'Deployment_hooks_mcp',
			'method'  => 'deployment_post_hook'
		);	
		
		$this->_EE->db->insert('actions', $pre_hook);
		$this->_EE->db->insert('actions', $post_hook);
		
		
		$this->_EE->load->dbforge();
		
		$table_fields = array(
			'deploy_id'        => array('type' => 'INT', 'constraint' => '10', 'unsigned' => TRUE, 'auto_increment' => TRUE),
			'deploy_ip'        => array('type' => 'VARCHAR', 'constraint' => '15'),
			'deploy_timestamp' => array('type' => 'VARCHAR', 'constraint' => '20'),
			'deploy_data'      => array('type' => 'TEXT')
		);
		
		$this->_EE->dbforge->add_field($table_fields);
		$this->_EE->dbforge->add_key('deploy_id', TRUE);
		
		$this->_EE->dbforge->create_table('deployment_hook_posts');
		
		return TRUE;
		/*
			TODO  Add installation of extension here
		*/
	}
	// End function install()
	
	
	
	
	/**
	 * Uninstall add-on
	 * 
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	function uninstall()
	{
		$this->_EE->load->dbforge();
		
		$this->_EE->db->select('module_id');
		/*
			TODO 	use config here
		*/
		$query = $this->_EE->db->get_where('modules', array('module_name' => 'Deployment_hooks'));
		
		$this->_EE->db->where('module_id', $query->row('module_id'));
		$this->_EE->db->delete('module_member_groups');
		
		$this->_EE->db->where('module_name', 'Deployment_hooks');
		$this->_EE->db->delete('modules');
		
		$this->_EE->db->where('class', 'Deployment_hooks_mcp');
		$this->_EE->db->delete('actions');
		
		$this->_EE->dbforge->drop_table('deployment_hook_posts');
				
		return TRUE;
		/*
			TODO  uninstall extension here as well
		*/
		
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
	function update($current = '')
	{
		return FALSE;
	}
	// End function update()
	
}
// End class Deployment_hooks_upd


/* End of file upd.deployment_hooks.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/upd.deployment_hooks.php */