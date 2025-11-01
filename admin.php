<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');


define('TBS_BATCH' , get_root_url().'admin/');
//echo TBS_BATCH ;

global $template, $conf, $page;

load_language('plugin.lang', TBS_PATH);

$template->assign('qualite_op', [
    ['value' => 'IMG_SQUARE', 'label' => 'IMG_SQUARE 120x120'],
    ['value' => 'IMG_SMALL',  'label' => 'IMG_SMALL  576x432'],
    ['value' => 'IMG_MEDIUM', 'label' => 'IMG_MEDIUM 792x594'],
    ['value' => 'IMG_LARGE',  'label' => 'IMG_LARGE 1008x756']
]);

include(dirname(__FILE__).'/install/config_default.inc.php');
include(dirname(__FILE__).'/install/config_init.inc.php');

$params = isset($conf['thumb_size']) ? @unserialize($conf['thumb_size']) : [];
//echo print_r($params) ;

$qualite_old = $params['qualite'];
//echo $qualite_old ;

//========================================================================
// Sauvegarde de la configuration
//==========================================================================

if (isset($_POST['submit']))
{
  $params  = array(
    'largeur'          => $_POST['largeur'],
    'hauteur'          => $_POST['hauteur'],
    'qualite'		       => $_POST['qualite'],
    'dimcrop'          => $_POST['dimcrop'],
    'album_la'         => $_POST['larg'],
    'album_ha'         => $_POST['haut'],
  );

    $query = '
    UPDATE ' . CONFIG_TABLE . '
    SET value="' . addslashes(serialize($params)) . '"
    WHERE param="thumb_size"
    LIMIT 1';
    pwg_query($query);
    
    array_push($page['infos'], l10n('Paramètres sauvegardés dans la base de données'));

    //----------------------------------------------------------------------------------
    // modificationde de batch_manager_global.php
    // Chemin du fichier à modifier
        $file_path = TBS_BATCH . 'batch_manager_global.php';
      //  echo 'chemin= ' , $file_path  ;

    // Lire le contenu du fichier
        $file_content = file_get_contents($file_path);

    // valeur à remplacer
        $val_old = '$thumb_params = ImageStdParams::get_by_type('.$qualite_old.');' ;
        //echo $val_old ;

        $qualite_new = $params['qualite'];
        $val_new = '$thumb_params = ImageStdParams::get_by_type('.$qualite_new.');' ;
        //echo $val_new ;

    // Remplacer old par new
    if ($val_new !== $val_old) {

        $file_content_modifie = str_replace($val_old, $val_new, $file_content);
        
        // Réécrire le fichier avec la modification
        file_put_contents($file_path, $file_content_modifie);
        //echo "Fichier modifié avec succès.";
    }
    
  
}

//*************************************************************************
// 

if (isset($_POST['reinit']))
{
  $params  = $config_init ;

    $query = '
    UPDATE ' . CONFIG_TABLE . '
    SET value="' . addslashes(serialize($params)) . '"
    WHERE param="thumb_size"
    LIMIT 1';
    pwg_query($query);
    
    array_push($page['infos'], l10n('Paramètres sauvegardés dans la base de données'));

    


}

//========================================================================

if (isset($_POST['defaut']))
{
  $params  = $config_default ;

    $query = '
    UPDATE ' . CONFIG_TABLE . '
    SET value="' . addslashes(serialize($params)) . '"
    WHERE param="thumb_size"
    LIMIT 1';
    pwg_query($query);
    
    array_push($page['infos'], l10n('Paramètres sauvegardés dans la base de données'));

    //----------------------------------------------------------------------------------
    // modificationde de batch_manager_global.php
    // Chemin du fichier à modifier
        $file_path = TBS_BATCH . 'batch_manager_global.php';
        //echo 'chemin= ' , $file_path  ;

    // Lire le contenu du fichier
        $file_content = file_get_contents($file_path);

    // valeur à remplacer
        $val_old = '$thumb_params = ImageStdParams::get_by_type('.$qualite_old.');' ;
        //echo $val_old ;

        $qualite_new = $params['qualite'];
        $val_new = '$thumb_params = ImageStdParams::get_by_type('.$qualite_new.');' ;
        //echo $val_new ;

    // Remplacer old par new
    if ($val_new !== $val_old) {

        $file_content_modifie = str_replace($val_old, $val_new, $file_content);
        
        // Réécrire le fichier avec la modification
        file_put_contents($file_path, $file_content_modifie);
        //echo "Fichier modifié avec succès.";
    }
    
  
}





//========================================================================






// Configuration du template
$template->assign(
  array(
    'LARGEUR'          => $params['largeur'],
    'HAUTEUR'          => $params['hauteur'],
    'QUALITE'		       => $params['qualite'],
    'DIMCROP'  		     => $params['dimcrop'],
    'ALBUM_LA'  		   => $params['album_la'],
    'ALBUM_HA'  		   => $params['album_ha'],
    
  )
);


/// affichage de la page configuration
$template->set_filenames(array('plugin_admin_content' => dirname(__FILE__) . '/template/admin.tpl'));
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');


// --------------------------------------------------
?>