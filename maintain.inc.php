<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');



// Installation du plugin avec les valeurs par défaut
function plugin_install()
{
   include(dirname(__FILE__).'/install/config_default.inc.php');
  $query = '
INSERT INTO ' . CONFIG_TABLE . ' (param,value,comment)
VALUES ("thumb_size" , "'.addslashes(serialize($config_default)).'" , "Thumb Batch Size parameters");';
  pwg_query($query);
}

// Désinstallation du plugin
function plugin_uninstall()
{

 //reinit_php();

  
  $query = 'DELETE FROM ' . CONFIG_TABLE . ' WHERE param="thumb_size" LIMIT 1;';
  pwg_query($query);


}


// Désactivation du plugin
function plugin_deactivate()
{

//reinit_php() ;



}


/// remet le fichier admin/batch_manager_global.php dans son état d'origine
function reinit_php()
{
  
  define('TBS_BATCH' , get_root_url().'admin/');


  
  $params = isset($conf['thumb_size']) ? @unserialize($conf['thumb_size']) : [];
  
  $qualite_old = $params['qualite'];
// modificationde de batch_manager_global.php
    // Chemin du fichier à modifier
    $file_path = TBS_BATCH . 'batch_manager_global.php';
    //echo $file_path ;

// Lire le contenu du fichier
    $file_content = file_get_contents($file_path);

// valeur à remplacer
    $val_old = '$thumb_params = ImageStdParams::get_by_type('.$qualite_old.');' ;
    //echo $val_old ;

    $qualite_new = $params['qualite'];
    $val_new = '$thumb_params = ImageStdParams::get_by_type(IMG_SQUARE);' ;
    //echo $val_new ;

// Remplacer old par new
if ($val_new !== $val_old) {


    $file_content_modifie = str_replace($val_old, $val_new, $file_content);

    // Réécrire le fichier avec la modification
    //echo "Fichier modifié avec succès.";


}

}
//---------------------------------------------------------------------------
?>