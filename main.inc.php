<?php
/*
Plugin Name: thumb_size
Version: 2.3a
Description: Choisir la dimension et la qualité des vignettes dans la gestion par lot
Plugin URI: https://piwigo.org/ext/extension_view.php?eid=1015
Author: Charles69
Author URI:
Has Settings: webmaster
*/

// ============  VERSIONS =========================================================
 // historique des versions
 /*
  version 2.3a - 13/01/2026 
  déplacement du sélecteur de nombre de photos en haut  
  corrigé : langue uk par défaut

  version 2.3 - 19/12/2025
  ajouté Plugin URI pour mise à jour automatique    

  version 2.2 - 06/11/2025  
  le fichier batch_manager_global.tpl n'est plus modifié , cette fois c'est sûr ;)
  utilisation de conf_update_param() conf_delete_param() safe_unserialize()
  ajouté IMG_XLARGE

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


define('TBS_DIR', basename(dirname(__FILE__)));
define('TBS_PATH', PHPWG_PLUGINS_PATH . TBS_DIR . '/');


//===================== CHARGEMENT DES LANGUES , UK PAR DEFAUT ==================
// Charger d'abord l'anglais comme base
load_language('plugin.lang', TBS_PATH, array('language' => 'en_UK', 'no_fallback' => true));

// Puis charger la langue de l'utilisateur (qui écrasera l'anglais si c'est du français)
load_language('plugin.lang', TBS_PATH);



//=====================================================================================
add_event_handler('loc_begin_page_header', 'thumb_perso', 20);

function thumb_perso() {
    //echo "<!-- DEBUG: thumb_perso appelée -->\n";
    global $template, $conf, $params;
    
    if (!defined('IN_ADMIN') || !IN_ADMIN) {
        return;
    }
    
    // Récupération de la configuration
    if (!isset($conf['thumb_size'])) {
        return;
    }
    
    $params = safe_unserialize($conf['thumb_size']);
    if (!is_array($params)) {
        return;
    }
    
    // Assigner les variables au template avec valeurs par défaut
    $template->assign(array(
        'LARGEUR'  => isset($params['largeur']) ? $params['largeur'] : 300,
        'HAUTEUR'  => isset($params['hauteur']) ? $params['hauteur'] : 300,
        'QUALITE'  => isset($params['qualite']) ? $params['qualite'] : 'IMG_MEDIUM',
        'DIMCROP'  => isset($params['dimcrop']) ? $params['dimcrop'] : 'contain',
        'ALBUM_LA' => isset($params['album_la']) ? $params['album_la'] : 600,
        'ALBUM_HA' => isset($params['album_ha']) ? $params['album_ha'] : 700,
    ));

    // Charger le template
    $template_file = realpath(TBS_PATH . 'template/thumb_mod.tpl');
    if ($template_file) {
        $template->set_filenames(array('thumb_header' => $template_file));
        $template->assign_var_from_handle('thumb_header_content', 'thumb_header');
        $template->append('head_elements', $template->get_template_vars('thumb_header_content'));
    }


// Déplacer le sélecteur de pagination (sur l'onglet global - par défaut ou explicite)
if (!isset($_GET['mode']) || $_GET['mode'] === 'global') {
    $js_move_pagination = '
    <style>
    .pagination-per-page {
        margin-top: 15px;
    }
    </style>
    <script>
    jQuery(document).ready(function($) {
        $(\'.pagination-per-page\').insertAfter(\'.filterActions\');
    });
    </script>
    ';
    $template->append('head_elements', $js_move_pagination);
}

}

//==============================================================================================

add_event_handler('loc_end_element_set_global', 'thumb_size_modifier_thumbnails');

function thumb_size_modifier_thumbnails() {
    global $template, $conf;
    
    // Récupérer les paramètres depuis la base de données
    $params = safe_unserialize($conf['thumb_size']);
    
    // Définir le type de dérivative
    $derivative_type = IMG_SQUARE; // valeur par défaut
    
    if (isset($params['qualite'])) {
        switch ($params['qualite']) {
            case 'IMG_SQUARE':
            case IMG_SQUARE:
                $derivative_type = IMG_SQUARE;
                break;
            case 'IMG_SMALL':
            case IMG_SMALL:
                $derivative_type = IMG_SMALL;
                break;
            case 'IMG_MEDIUM':
            case IMG_MEDIUM:
                $derivative_type = IMG_MEDIUM;
                break;
            case 'IMG_LARGE':
            case IMG_LARGE:
                $derivative_type = IMG_LARGE;
            case 'IMG_XLARGE':
            case IMG_XLARGE:
                $derivative_type = IMG_XLARGE;    
                break;
        }
    }
    
    // Récupérer les paramètres pour le type choisi
    $custom_params = ImageStdParams::get_by_type($derivative_type);
    
    // Remplacer thumb_params dans le template
    $template->assign('thumb_params', $custom_params);
    
    // Modifier les miniatures déjà générées
    $thumbnails = $template->get_template_vars('thumbnails');
    
    if (is_array($thumbnails) && !empty($thumbnails)) {
        foreach ($thumbnails as $key => &$thumbnail) {
            // Recréer le SrcImage à partir des données
            $src_image = new SrcImage($thumbnail);
            
            // Remplacer l'objet thumb
            $thumbnail['thumb'] = new DerivativeImage($custom_params, $src_image);
        }
        unset($thumbnail); // Important : casser la référence
        
        $template->assign('thumbnails', $thumbnails);
    }
}

?>
