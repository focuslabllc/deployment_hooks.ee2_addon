<?php

/**
 * Deployment Hooks English Lang file
 *
 * @package    DeploymentHooks
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/deployment_hooks.ee2_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

$lang = array(

	/**
	 * Required language lines
	 */

	'deployment_hooks_module_name' =>
	'Deployment Hooks',

	'deployment_hooks_module_description' =>
	'Adds unique hooks to allow automated actions during site deployments',



	/**
	 * Extension settings lines
	 */

	'dh:get_token' =>
	'Set your GET token for requests',

	'dh:get_token_short' =>
	'GET Token',

	'dh:get_token_extra' =>
	'This must be in your deployment hook URL for the actions to be triggered.
	<em>example: <u>answer2life=42</u></em>',

	'dh:ip_array' =>
	'Limit your deployment hooks to specific IPs for security',

	'dh:ip_array_short' =>
	'IP',

	'dh:ip_array_extra' =>
	'Leave blank for no restrictions or use a pipe delimited list to restrict.
	<em>example: <u>192.168.29.42|192.168.24.52</u></em>',

	'dh:http_auth' =>
	'Require HTTP authorization?',

	'dh:http_auth_extra' =>
	'Require a member username and password in your URL.
	<em>example: <u>https://username:password@yoursite.com/?ACT=XX</u></em>',

	'dh:http_auth_member_groups' =>
	'HTTP authorization Member Groups',

	'dh:http_auth_member_groups_extra' =>
	'Select which member groups can use HTTP authorization',

	'dh:settings_saved' =>
	'Your settings have been saved.',

	'dh:save_settings' =>
	'Save Settings',


	/**
	 * General lines (used in multiple places)
	 */

	'dh:pre_deployment' =>
	'Pre-Deployment',

	'dh:post_deployment' =>
	'Post-Deployment',

	'dh:menu_log' =>
	'Deployment Log',

	'dh:menu_home' =>
	'Deployment Hooks Home',

	'dh:menu_settings' =>
	'Settings',

	'dh:menu_docs' =>
	'Documentation',

	'dh:log_is_empty' =>
	'The Deployment Log is currently empty. Have you deployed anything yet?<br/><br/>If so, your settings may not be configured properly.',



	/**
	 * mcp.*.php file (and related views)
	 */

	'dh:problem_installing_hooks' =>
	'It looks like there was a problem installing your deployment hooks. Please uninstall and reinstall the module.',

	'dh:version' =>
	'Version',

	'dh:hook_not_used' =>
	'There are no extensions using this hook.',

	'dh:hook_location' =>
	'Hook Location',

	'dh:hook_url' =>
	'URL',

	'dh:hook_instructions' =>
	'Use the links below to setup your web hooks for pre and post deployment actions.',

	'dh:view_your_extensions' =>
	'View your extensions',

	'dh:to_make_changes' =>
	' to make any changes to the above items.',

	'dh:time' =>
	'Time',

	'dh:log' =>
	'Log',

	'dh:notice' =>
	'Notice',

	'dh:security_failed' =>
	'Security check failed. Killing hook.',

	'dh:security_passed' =>
	'Security check passed. Continuing.',

	'dh:get_token_failed' =>
	'Get token key/value did not match.',

	'dh:ip_array_failed' =>
	'IP Address of user was not in acceptable IP array.',

	'dh:http_auth_failed' =>
	'The HTTP Authorization failed.',





	// 'extensions_disabled' is already a core lang key
	'dh:extensions_disabled' =>
	'Extensions are disabled as a whole so your Deployment Hooks module will not be of any use until you change this.
	 Check your Extensions page or your site config file.',




	/**
	 * acc.*.php language lines
	 */

	'dh:recent_deployment_notes' =>
	'Recent Deployment Notes',

	'dh:full_deployment_log' =>
	'Full Deployment Log',

	'dh:mod_not_installed' =>
	'<strong>It appears you did not install the Deployment Hooks module properly.<br/>Please install it now by going to your Modules page and clicking Install in the appropriate row.</strong>',



	/**
	 * Log language lines
	 */

	'dh:pre_hook_initiated' =>
	'Pre-Deployment Hook initiated',

	'dh:post_hook_initiated' =>
	'Post-Deployment Hook initiated',

	'dh:pre_hook_completed' =>
	'Pre-Deployment Hook completed',

	'dh:post_hook_completed' =>
	'Post-Deployment Hook completed',

	'dh:no_extensions_pre_hook' =>
	'No active extensions using the pre-deployment hook.',

	'dh:no_extensions_post_hook' =>
	'No active extensions using the post-deployment hook.'


);

/* End of file deployment_hooks_lang.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/language/english/deployment_hooks_lang.php */