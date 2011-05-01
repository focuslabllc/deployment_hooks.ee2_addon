<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// tab indented @ 3 spaces

/**
 * Deployment Hooks Module CP
 * 
 * The mcp file contains all views and related functions for the
 * module's control panel interface
 * 
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Deployment_hooks_mcp {
	
	/**
	 * @var array  The response built across our module and extensions
	 */
	public $response = array();
	
	
	/**
	 * @var bool  Debug mode
	 */
	public $debug = FALSE;
	
	
	/**
	 * @var string  Base URL for inner-module linking
	 */
	private $_url_base;
	
	
	/**
	 * @var object  EE super object to be set in the constructor
	 */
	private $_EE;
	
	
	/**
	 * @var array  Add-on's settings to be pulled from DB
	 */
	private $_settings;
	
	
	
	
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
		$this->_debug = ($this->_EE->session->userdata['group_id'] == '1' OR $this->_EE->config->item('dh:dev_mode')) ? TRUE : FALSE ;
		
		// load our model for access in all methods
		$this->_EE->load->model('deployment_hooks_model','Deployment_hooks_model');
		
		// Get our add-on's settings
		$settings = $this->_EE->Deployment_hooks_model->get_settings();
		
		if ($settings->num_rows() > 0 && $settings->row('settings')  != '')
		{
			// Load the string helper to strip slashes on array items
			$this->_EE->load->helper('string');
			$this->_settings = strip_slashes(unserialize($settings->row('settings')));
		}
		
		
		// There's a chance this class will be loaded upon a deployment ACT request
		// So we don't want to process any of this juicy goodness if that's the case
		if ( ! $this->_EE->input->get('ACT'))
		{
			
			// Load our config settings
			$this->_EE->load->config('deployment_hooks');
			
			// Setup our module's URL base for quicker link building between module pages
			// Defined in our config file located in deployment_hooks/config/deployment_hooks.php
			$this->_url_base = $this->_EE->config->item('dh:mod_url_base');
			
			// Setup our module's navigation elements
			// Menu is defined in our config file
			$this->_EE->cp->set_right_nav($this->_EE->config->item('dh:mod_menu'));
			
			// Move this out to a view? Some other approach?
			// ordered and unordered lists look kinda crappy in tables
			// but we want them to look nice for our Log page/view
			$this->_EE->cp->add_to_head('
				<style type="text/css" media="screen">
					table ol { list-style: numeric; margin: 5px 5px 5px 30px; }
					table ol li { padding: 3px 0; }
				</style>
			');
			
		}
		// End if ( ! $this->_EE->input->get('ACT'))
	}
	// End function __construct()
	
	
	
	
	/**
	 * Module homepage data and view load
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 * @return     void
	 */
	public function index()
	{
		
		$this->_EE->load->library('table');
		$this->_EE->load->helper(array('form','html'));
		
		$this->_EE->cp->set_variable('cp_page_title', lang('deployment_hooks_module_name'));
		
		
		// Quickly verify that we have successfully registered our actions
		$data['actions_registered'] = ($this->_EE->cp->fetch_action_id('Deployment_hooks_mcp', 'deployment_pre_hook')) ? TRUE : FALSE ;
		
		// Setup an array of our hook substrings so we can keep things DRY
		$hooks = array('pre','post');
		
		// We run the same code against each hook so we'll just use a foreach on our array we just created
		foreach ($hooks as $which)
		{
			
			// Build our pre and post action urls
			$data[$which.'_action_url']  = $this->_EE->config->config['base_url']
			                             . QUERY_MARKER
			                             . 'ACT='
			                             . $this->_EE->cp->fetch_action_id('Deployment_hooks_mcp', 'deployment_'.$which.'_hook');
			$data[$which.'_action_url']  .= ($this->_settings['dh:get_token'] != '') ? '&'.$this->_settings['dh:get_token'] : '' ;
			
			// Used in our view for conditioals on displaying extensions or a 'not in use' message
			$data[$which.'_is_used']  = $this->_EE->extensions->active_hook('deployment_hooks_'.$which.'_deploy');
			
			if ($data[$which.'_is_used'])
			{
				// our pre-development hook is active so we grab the extensions from the DB.
				// technically I can use the EE superobject to get this information but the way
				// I'd be sorting through things is silly. It's easier (and not any more intensive)
				// to run a query to fetch data in the format I want/need
				$query = $this->_EE->Deployment_hooks_model->get_hook_use('deployment_hooks_'.$which.'_deploy');
				foreach ($query->result() as $query) {
					$query->name = ucwords(str_replace('_', ' ', str_replace('_ext','',$query->class)));
					$data[$which.'_extensions'][] = $query;
				}
			}
			// End conditional if ($data[$which.'_is_used'])
			
		}
		// End loop foreach ($hooks as $which)
		
		
		// Pass our deployment hook extension settings.
		// Not used in the view file yet - but might need it later
		$data['settings'] = $this->_settings;
		
		// Setup a basic notices array. So far there's only 1 possible but there may be more later
		$data['notices'] = array();
		if ($this->_EE->config->item('allow_extensions') == 'n')
		{
			$data['notices'][] = lang('dh:extensions_disabled');
		}
		
		
		return $this->_EE->load->view('cp_index', $data, TRUE);
		
	}
	// End function index()
	
	
	
	
	/**
	 * Display the Deployment Log module view
	 *	
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function log()
	{
		
		$this->_EE->cp->set_variable('cp_page_title', $this->_EE->lang->line('dh:menu_log'));
		$this->_EE->cp->set_breadcrumb($this->_url_base, lang('deployment_hooks_module_name'));
		
		$this->_EE->load->library(array('table','pagination'));
		$this->_EE->load->helper('html');

		//  Check for pagination
		$total = $this->_EE->Deployment_hooks_model->get_deployment_post_count();
		$per_page = 10;
		$deployments = $this->_EE->Deployment_hooks_model->get_recent_dh_posts($per_page, $this->_EE->input->get('rownum'));
		// Pass the relevant data to the paginate class so it can display the "next page" links
		$p_config = $this->pagination_config('log', $per_page, $total);
		$this->_EE->pagination->initialize($p_config);

		$data['pagination'] = $this->_EE->pagination->create_links();
		$data['deployment_posts'] = $deployments->result();
		
		return $this->_EE->load->view('log', $data, TRUE);
		
	}
	// End function log()
	
	
	
	
	/**
	 * Documentation view for module
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     string
	 */
	public function docs()
	{
		$this->_EE->cp->set_variable('cp_page_title', lang('dh:menu_docs'));
		$this->_EE->cp->set_breadcrumb($this->_url_base, lang('deployment_hooks_module_name'));
		
		return $this->_EE->load->view('docs','',TRUE);
	}
	// End function docs()
	
	
	
	
	/**
	 * Build pagination
	 * straight from the EE docs basically
	 *
	 * @param      string
	 * @param      int
	 * @param      int
	 * @access     private
	 * @author     EllisLab
	 * @link       http://expressionengine.com/user_guide/development/module_tutorial.html
	 * @return     array
	 */
	private function pagination_config($method, $per_page, $total_rows)
	{
			$config['base_url'] = $this->_url_base.AMP.'method='.$method;
			$config['total_rows'] = $total_rows;
			$config['per_page'] = $per_page;
			$config['page_query_string'] = TRUE;
			$config['query_string_segment'] = 'rownum';
			$config['full_tag_open'] = '<p id="paginationLinks">';
			$config['full_tag_close'] = '</p>';
			$config['prev_link'] = '<img src="'
			                     . $this->_EE->cp->cp_theme_url
			                     . 'images/pagination_prev_button.gif" width="13" height="13" alt="<" />';
			$config['next_link'] = '<img src="'
			                     . $this->_EE->cp->cp_theme_url
			                     . 'images/pagination_next_button.gif" width="13" height="13" alt=">" />';
			$config['first_link'] = '<img src="'
			                      . $this->_EE->cp->cp_theme_url
			                      . 'images/pagination_first_button.gif" width="13" height="13" alt="<<" />';
			$config['last_link'] = '<img src="'
			                      . $this->_EE->cp->cp_theme_url
			                      . 'images/pagination_last_button.gif" width="13" height="13" alt=">>" />';
			
			return $config;
	}
	// End function pagination()
	
	
	
	
	/**
	 * The "pre" deployment method
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function deployment_pre_hook()
	{
		$this->_deployment_hook_master('pre');
	}
	// End function deployment_pre_hook()
	
	
	
	
	/**
	 * The "post" deployment method
	 *
	 * @access     public
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	public function deployment_post_hook()
	{
		$this->_deployment_hook_master('post');
	}
	// End function deployment_post_hook()
	
	
	
	
	/**
	 * Deployment hook method used for pre and post calls
	 *
	 * @param      string  (pre|post)
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     void
	 */
	private function _deployment_hook_master($which)
	{
		// For some reason we have to load the language file manually on action methods
		$this->_EE->lang->loadfile('deployment_hooks');
		
		$this->response[] = lang('dh:'.$which.'_hook_initiated');
		
		// Run our "security" checks
		if ( ! $this->_security_checks())
		{
			// We failed the security check so let's log that and return
			$this->response[] = lang('dh:security_failed');
			$this->_log_deployment();
			if ($this->_debug)
			{
				exit('Security check failed. Check Deployment Hooks log for more info.');
			} else {
				exit;
			}
		} else {
			$this->response[] = lang('dh:security_passed');
		}
		// If we've made it this far we either don't have a security token to match OR it passed
		
		
		// Only call the extension if the hook is active
		if ($this->_EE->extensions->active_hook('deployment_hooks_'.$which.'_deploy') === TRUE)
		{
			$ext_response = $this->_EE->extensions->call('deployment_hooks_'.$which.'_deploy');
			$this->response = is_array($ext_response) ? array_merge($this->response,$ext_response) : $this->response ;
		} else {
			$this->response[] = lang('dh:no_extensions_'.$which.'_hook');
		}
		
		$this->response[] = lang('dh:'.$which.'_hook_completed');
		
		$this->_log_deployment();
		
		// Maybe add in another hook here to allow devs to do something else with the "response"
		// Example - take the response array and send it to Basecamp via their API as a
		// private message to a specified project.
		
		if ($this->_debug)
		{
			exit(ucwords($which).'-deployment hook complete.');
		} else {
			exit;
		}
	}
	// End function _deployment_hook_master()
	
	
	
	
	/**
	 * Run security checks on deployment hook action call
	 *
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 * @return     bool
	 */
	private function _security_checks()
	{
		
		$pass = TRUE;
		
		// If our settings are left blank then they still have a value of an empty string
		// To make this code simpler we'll unset the keys if the value is an empty string
		foreach ($this->_settings as $key => $value) {
			if ($value == '')
			{
				unset($this->_settings[$key]);
			}
		}
		
		// Don't go further if our settings are both blank
		if ( ! isset($this->_settings['dh:get_token']) AND ! isset($this->_settings['dh:ip_array']))
		{
			return $pass;
		}
		
		// If the GET key/value token is defined but doesn't check out - trigger a fail
		$get_token = isset($this->_settings['dh:get_token']) ? explode('=',$this->_settings['dh:get_token']) : FALSE ; 
		if ( ! $get_token OR $this->_EE->input->get($get_token[0],TRUE) != @$get_token[1])
		{
			$this->response[] = lang('dh:get_token_failed');
			$pass = FALSE;
		}
		
		// If we're restricting to a set if IPs make sure the server is on the whitelist
		$ip_array = isset($this->_settings['dh:ip_array']) ? explode('|',$this->_settings['dh:ip_array']) : FALSE ;
		if ($ip_array AND ! in_array($this->_EE->input->ip_address(), $ip_array))
		{
			$this->response[] = lang('dh:ip_array_failed') . ' (IP was ' . $this->_EE->input->ip_address() . ')';
			$pass = FALSE;
		}
		
		return $pass;
		
	}
	// End function security_checks()
	
	
	
	
	/**
	 * Log the response to the database
	 *
	 * @param      array
	 * @access     private
	 * @author     Erik Reagan <erik@focuslabllc.com>
	 */
	private function _log_deployment()
	{
		
		if ( ! empty($this->response) )
		{
			$data = array(
				'deploy_data'      => serialize($this->_EE->db->escape_str($this->response)),
				'deploy_timestamp' => $this->_EE->localize->now,
				'deploy_ip'        => $this->_EE->input->ip_address()
			);
			$this->_EE->Deployment_hooks_model->insert_deployment_log_post($data);
		}
		
		$this->response = NULL;
		
	}
	// End function log_deployment()
	
	
}
// End class Deployment_hooks_mcp

/* End of file mcp.deployment_hooks.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/mcp.deployment_hooks.php */