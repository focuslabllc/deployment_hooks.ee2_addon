<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Model
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
	
	
	
	
	
	
	
}
// End class Deployment_hooks_model

/* End of file deployment_hooks_model.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/models/deployment_hooks_model.php */