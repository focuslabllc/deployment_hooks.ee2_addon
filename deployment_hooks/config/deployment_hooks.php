<?php

$config['dh:version'] = "0.1.0";
$config['dh:description'] = "Adds unique hooks to allow automated actions during site deployments";
$config['dh:mod_url_base'] = BASE.AMP.'C=addons_modules'.AMP.'M=show_module_cp'.AMP.'module=deployment_hooks';
$config['dh:mod_menu'] = array(
	'dh:menu_home'     => $config['dh:mod_url_base'].AMP.'method=index',
	'dh:menu_log'      => $config['dh:mod_url_base'].AMP.'method=log',
	'dh:menu_settings' => BASE.AMP.'C=addons_extensions'.AMP.'M=extension_settings'.AMP.'file=deployment_hooks',
	'dh:menu_docs'     => $config['dh:mod_url_base'].AMP.'method=docs'
	);

?>