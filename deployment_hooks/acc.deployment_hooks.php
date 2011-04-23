<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Accessory
 *
 * The accessory simply displays the 3 most recent entries
 * from the Deployment Hook Log along with quick links to the
 * Deployment Hooks module homepage and the full Log page
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Deployment_hooks_acc {
	
	/**
	 * @var string  Accessory name
	 */
	public $name = 'Deployment Hooks Log';
	
	
	/**
	 * @var string  Accessory's HTML div ID value
	 */
	public $id = 'deployment_hooks_acc';
	
	
	/**
	 * @var string  Version number of add-on
	 */
	public $version = '0.1.0';
	
	
	/**
	 * @var string  Description of Accessory
	 */
	public $description = 'Display the recent deployment log from the Deployment Hooks module';
	
	
	/**
	 * @var array  Array for Accessory columns (or "sections")
	 */
	public $sections = array();
	
	
	/**
	 * @var object  EE's super object to be referenced in the constructor
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
		// Load our config settings
		$this->_EE->config->load('deployment_hooks');
	}
	// End function __construct()
	
	
	
	
	/**
	 * Set Sections
	 *
	 * Set each column (or "section") for the accessory
	 *
	 * @access     public
	 * @author     Erik Reagan  <erik@focuslabllc.com>
	 * @return     void
	 */
	public function set_sections()
	{
		
		// We use the HTML helper to easily build the ordered list and heading in the view file
		$this->_EE->load->helper('html');
		$this->_EE->lang->loadfile('deployment_hooks');
		
		/*
			TODO 	move this into the model
		*/
		$query = $this->_EE->db->order_by('deploy_timestamp', 'desc')
									  ->get('exp_deployment_hook_posts', 3);
		$data['deploy_log'] = $query->result();
		
		$this->_EE->cp->add_to_head('
			<!-- added by Deployment Hooks accessory -->
			<style type="text/css">
				#' . $this->id . ' h3 { color: #fff; margin-bottom: 5px; }
				#' . $this->id . ' table td { display: inline-block; margin-right: 35px; padding-right: 20px; border-right: 1px solid #445058; }
				#' . $this->id . ' ol { margin: 0 0 0 20px; width: 250px; }
				#' . $this->id . ' a {
						display: inline-block;
						margin: 15px 10px 5px 0;
						padding: 5px 20px;
						background: #5D7D92;
						color: #fff !important;
						-webkit-border-radius: 3px;
						-moz-border-radius: 3px;
						text-shadow: 0px 1px 0px #000;
				}
				#'.$this->id.' a:hover { background: #486272; }
			</style>
			<!-- end Deployment Hooks accessory additions -->
		');
		
		$this->sections[lang('dh:recent_deployment_notes')] = $this->_EE->load->view('accessory', $data, TRUE);
		
	}
	// End function set_sections()	
	
}
// End class Deployment_hooks_acc

/* End of file acc.deployment_hooks.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/acc.deployment_hooks.php */