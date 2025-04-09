<?php
/*
Plugin Name: thumb_size
Version: 1.1
Description: Choisir la dimension et la qualité des vignettes dans la gestion par lot
Plugin URI:
Author: Charles69
Author URI:
Has Settings: webmaster
*/

// ============  VERSIONS =========================================================
 // historique des versions
 /*

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

//ajout dans le header de theAdminPage
add_event_handler('loc_begin_page_header', 'thumb_perso',20 );

function thumb_perso() {
    global $template, $conf ;
    if (defined('IN_ADMIN') and IN_ADMIN) { // On est dans l'admin
        $conf['thumb_size'] = unserialize($conf['thumb_size']);
        $largeur = $conf['thumb_size']['largeur'];
        $hauteur = $conf['thumb_size']['hauteur'];
        $dimcrop = $conf['thumb_size']['dimcrop']; 
        $album_la = $conf['thumb_size']['album_la']; 
        $album_ha = $conf['thumb_size']['album_ha']; 
                

        // Assigner les variables au template
        $template->assign(array(
            'LARGEUR' => $largeur,
            'HAUTEUR' => $hauteur,
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








//--------------------------------------------------------------------------------------------
?>