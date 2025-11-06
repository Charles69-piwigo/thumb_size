<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

// Installation du plugin avec les valeurs par défaut
function plugin_install()
{
    include(dirname(__FILE__).'/install/config_default.inc.php');
    
    conf_update_param('thumb_size', serialize($config_default), true);
}


// Désinstallation du plugin
function plugin_uninstall()
{
    conf_delete_param('thumb_size');
}

// Désactivation du plugin
function plugin_deactivate()
{

}

?>