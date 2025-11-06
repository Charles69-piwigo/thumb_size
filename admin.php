<?php

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

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

// Récupération de la configuration actuelle
$params = isset($conf['thumb_size']) ? @unserialize($conf['thumb_size']) : [];
if (!is_array($params)) {
    $params = $config_default;
}

// Fonction de validation des paramètres
function validate_params($input_params, $config_default) {
    $valid_qualities = ['IMG_SQUARE', 'IMG_SMALL', 'IMG_MEDIUM', 'IMG_LARGE'];
    $valid_dimcrop = ['contain', 'cover'];
    
    $params = array(
        'largeur' => isset($input_params['largeur']) ? intval($input_params['largeur']) : $config_default['largeur'],
        'hauteur' => isset($input_params['hauteur']) ? intval($input_params['hauteur']) : $config_default['hauteur'],
        'qualite' => isset($input_params['qualite']) && in_array($input_params['qualite'], $valid_qualities) 
                     ? $input_params['qualite'] : $config_default['qualite'],
        'dimcrop' => isset($input_params['dimcrop']) && in_array($input_params['dimcrop'], $valid_dimcrop)
                     ? $input_params['dimcrop'] : $config_default['dimcrop'],
        'album_la' => isset($input_params['larg']) ? intval($input_params['larg']) : $config_default['album_la'],
        'album_ha' => isset($input_params['haut']) ? intval($input_params['haut']) : $config_default['album_ha'],
    );
    
    // Limites raisonnables pour les dimensions
    $params['largeur'] = max(50, min(2000, $params['largeur']));
    $params['hauteur'] = max(50, min(2000, $params['hauteur']));
    $params['album_la'] = max(200, min(2000, $params['album_la']));
    $params['album_ha'] = max(200, min(2000, $params['album_ha']));
    
    return $params;
}

// Fonction de sauvegarde sécurisée
function save_config($params) {
    global $page;
    conf_update_param('thumb_size', serialize($params));
    array_push($page['infos'], l10n('Paramètres sauvegardés dans la base de données'));
}

//========================================================================
// Sauvegarde de la configuration
//==========================================================================

if (isset($_POST['submit']))
{
    $params = validate_params($_POST, $config_default);
    save_config($params);
}

//========================================================================
// Réinitialisation aux valeurs par défaut Piwigo
//========================================================================

if (isset($_POST['reinit']))
{
    $params = $config_init;
    save_config($params);
}

//========================================================================
// Application des valeurs recommandées
//========================================================================

if (isset($_POST['defaut']))
{
    $params = $config_default;
    save_config($params);
}

//========================================================================
// Configuration du template
//========================================================================

$template->assign(
    array(
        'LARGEUR'    => isset($params['largeur']) ? $params['largeur'] : $config_default['largeur'],
        'HAUTEUR'    => isset($params['hauteur']) ? $params['hauteur'] : $config_default['hauteur'],
        'QUALITE'    => isset($params['qualite']) ? $params['qualite'] : $config_default['qualite'],
        'DIMCROP'    => isset($params['dimcrop']) ? $params['dimcrop'] : $config_default['dimcrop'],
        'ALBUM_LA'   => isset($params['album_la']) ? $params['album_la'] : $config_default['album_la'],
        'ALBUM_HA'   => isset($params['album_ha']) ? $params['album_ha'] : $config_default['album_ha'],
    )
);

// Affichage de la page de configuration
$template->set_filenames(array('plugin_admin_content' => dirname(__FILE__) . '/template/admin.tpl'));
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');

?>