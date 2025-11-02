<?php
/*
Plugin Name: thumb_size
Version: 2.0
Description: Choisir la dimension et la qualité des vignettes dans la gestion par lot
Plugin URI:
Author: Charles69
Author URI:
Has Settings: webmaster
*/

// ============  VERSIONS =========================================================
 // historique des versions
 /*
  version 2.1 - 02/11/2025
  corrigé css dans menu de configuration
  
  version 2.0 - 02/11/2025 (diffusé)
  réécriture complète du code. le fichier batch_manager_global.tpl n'est plus modifié
  réécriture du menu de configuration

  version 1.1 - 09/04/2025 (diffusé)
  corrigé conflit avec le plugin Add_head_element

  version 1.0 - 04/04/2025  (diffusé)
  ajout de 'Reinitialiser' qui rétablit les valeurs originales et le fichier php
  
  version 0.0 - 31/03/2025
  menu configuration du choix des dim des vignettes , retaillé ou redimensionné
  ajouté configuration qualité image , et dimension bloc 'sélection album'
  sauvegarde de la qualité : modification du fichier php par prog
     
  version 0.0 - 30/03/2025
  modification de batch_manager_global.tpl directement
  menu configuration du choix des dim des vignettes , retaillé ou redimensionné
  sauvegarde en bdd
  
*/

global $conf;

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');


//if (mobile_theme()) return;

define('TBS_DIR' , basename(dirname(__FILE__)));
define('TBS_PATH' , PHPWG_PLUGINS_PATH . TBS_DIR . '/');
//define('TBS_ADMIN' , get_root_url().'admin.php?page=plugin-'.TBS_DIR);


add_event_handler('loading_lang', 'thumb_size_loading_lang');	  
function thumb_size_loading_lang(){
  load_language('plugin.lang', TBS_PATH);
}

// SCRIPT =======================================================================================
/*

add_event_handler('loc_begin_page_header', 'thumb_add_css');
function thumb_add_css()
{
    global $page, $template;
    //print_r($page) ;
    
    $template->append('head_elements', '
    <link rel="stylesheet" href="plugins/thumb_size/template/thumb.css" />
    ');
    
}
*/    

//ajout dans le header de theAdminPage
add_event_handler('loc_begin_page_header', 'thumb_perso',20 );

function thumb_perso() {
    global $template, $conf ;
    if (defined('IN_ADMIN') and IN_ADMIN) { // On est dans l'admin
        $conf['thumb_size'] = unserialize($conf['thumb_size']);
        $largeur = $conf['thumb_size']['largeur'];
        $hauteur = $conf['thumb_size']['hauteur'];
        $qualite = $conf['thumb_size']['qualite'];
        $dimcrop = $conf['thumb_size']['dimcrop']; 
        $album_la = $conf['thumb_size']['album_la']; 
        $album_ha = $conf['thumb_size']['album_ha']; 
                

        // Assigner les variables au template
        $template->assign(array(
            'LARGEUR' => $largeur,
            'HAUTEUR' => $hauteur,
            'QUALITE' => $qualite,
            'DIMCROP' => $dimcrop,
            'ALBUM_LA' => $album_la,
            'ALBUM_HA' => $album_ha,

        ));

        // Charger le template
        $template->set_filenames(array('thumb_header' => realpath(TBS_PATH . 'template/thumb_mod.tpl')));

        // Ajouter directement au head_elements
        $template->assign_var_from_handle('thumb_header_content', 'thumb_header');
        $template->append('head_elements', $template->get_template_vars('thumb_header_content'));
    }
}



add_event_handler('loc_end_admin', 'tbs_change_batch_thumb_global');

function tbs_change_batch_thumb_global()
{
    global $template, $conf, $page;

    // On vérifie qu'on est bien sur la page "Batch Manager global"
    
    if (
        !defined('IN_ADMIN')
        || empty($page['page'])
        || $page['page'] !== 'batch_manager'
        || empty($page['mode'])
        || $page['mode'] !== 'global'
    ) {
        return;
    }
        

    // Récupère la config enregistrée par le plugin
    $params = isset($conf['thumb_size']) ? @unserialize($conf['thumb_size']) : [];
    if (empty($params['qualite'])) return;

    // Transforme la chaîne ('IMG_MEDIUM', etc.) en constante PHP
    if (!defined($params['qualite'])) return;
    $type = constant($params['qualite']);

    // Paramètres d'image selon la qualité choisie
    $thumb_params = ImageStdParams::get_by_type($type);

    // Récupère les miniatures préparées par la page
    $images = $template->get_template_vars('thumbnails');
    if (!is_array($images)) return;

    // Remplace les dérivés "square" par ta version personnalisée
    foreach ($images as &$img) {
        $img['derivatives']['square'] = new DerivativeImage($thumb_params, $img['path']);
    }

    // Réinjecte dans le template
    $template->assign('thumbnails', $images);
}







//--------------------------------------------------------------------------------------------
?>