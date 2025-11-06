<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');



// Installation du plugin avec les valeurs par défaut

function plugin_install()
{
    include(dirname(__FILE__).'/install/config_default.inc.php');
    
    conf_update_param('thumb_size', serialize($config_default), true);
}

/*
// Installation du plugin avec les valeurs par défaut
function plugin_install()
{
   include(dirname(__FILE__).'/install/config_default.inc.php');
  $query = '
INSERT INTO ' . CONFIG_TABLE . ' (param,value,comment)
VALUES ("thumb_size" , "'.addslashes(serialize($config_default)).'" , "Thumb Batch Size parameters");';
  pwg_query($query);
}
*/  

// Désinstallation du plugin
function plugin_uninstall()
{
    conf_delete_param('thumb_size');
}

/*
// Désinstallation du plugin
function plugin_uninstall()
{
  $query = 'DELETE FROM ' . CONFIG_TABLE . ' WHERE param="thumb_size" LIMIT 1;';
  pwg_query($query);
}
*/  


// Désactivation du plugin
function plugin_deactivate()
{

}



//---------------------------------------------------------------------------
?>