<?php

/**
 * @var    string   Version number
 * 
 * Also define the version constant to be
 * used in the extension and accessory files
 */
$config['dh:version'] = "1.0.0";
if ( ! defined('DH_VERSION'))
{
	define('DH_VERSION',$config['dh:version']);
}


/**
 * @var    string   Description of module
 */
$config['dh:description'] = "Adds unique hooks to allow automated actions during site deployments";


/**
 * @var    string   URL base for inner add-on linking
 */
$config['dh:mod_url_base'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=deployment_hooks';


/**
 * @var    array   Module menu configuration
 */
$config['dh:mod_menu'] = array(
	'dh:menu_home'     => $config['dh:mod_url_base'].AMP.'method=index',
	'dh:menu_log'      => $config['dh:mod_url_base'].AMP.'method=log',
	'dh:menu_settings' => BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=deployment_hooks',
	'dh:menu_docs'     => $config['dh:mod_url_base'].AMP.'method=docs'
);


/**
 * @var    array   Default extension settings
 */
$config['dh:default_settings'] = array(
	'dh:get_token' => '',
	'dh:ip_array'  => ''
);


/**
 * @var    array   Extension hook setup (multi-dimensional for multiple hooks)
 */
$config['dh:ext_hook'] = array(
	'class'     => 'Deployment_hooks_ext',
	'method'    => 'so_long',
	'hook'      => 'thanks_for_all_the_fish',
	'settings'  => serialize($config['dh:default_settings']),
	'priority'  => 10,
	'version'   => $config['dh:version'],
	'enabled'   => 'y'
);


/**
 * @var     array  For installing the module
 */
$config['dh:module_data'] = array(
	'module_name'        => 'Deployment_hooks',
	'module_version'     => $config['dh:version'],
	'has_cp_backend'     => 'y',
	'has_publish_fields' => 'n'
);


/* End of file deployment_hooks.php */
/* Location: ./system/expressionengine/third_party/deployment_hooks/config/deployment_hooks.php */