<?php
/**
* Plugin Name: Annuaire Artisans
* Plugin URI: http://www.cma-cahors.fr/
* Description: permettre de télécharger l'annuaire
* des artisans en format excel.
* Version: 1.0
* Author: Elizabeth Reed
* License: GPL12
*/
require('create_temp.php');
require('create_artisan.php');
require('parse.php');
require('shortcodes.php');
/**
 * Include CSS file for Artisan.
 */
function load_custom_wp_admin_style($hook)
{
        // Load only on ?page=mypluginname
    if ($hook != 'tools_page_annuaire_artisans') {
            return;
    }
        wp_enqueue_style('style', plugins_url('css/style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');


add_action('admin_menu', 'annuaire_artisans');


register_activation_hook(__FILE__, 'annuaire_artisans_create_artisan');

function annuaire_artisans()
{
    $parent_slug = 'tools.php';
        $page_title = 'Annuaire Artisans';
        $menu_title = 'Annuaire Artisans';
        $capability = 'edit_pages';
        $menu_slug = 'annuaire_artisans';
        $function = 'annuaire_artisans_page_display';
        $icon_url = '';

        add_submenu_page($parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url);
}

function annuaire_artisans_page_display()
{
    $error_message = '';

    if (!empty($_POST['submit'])) {
        // If the "Select Input File" input field is empty
        if (empty($_FILES['artisan_csv']['name'])) {
            $error_message .= '* '.__('Aucun fichier sélectionné. Veuillez entrer un fichier ');
        } else {
            // Check that "Input File" has proper .csv file extension
            $ext = pathinfo($_FILES['artisan_csv']['name'], PATHINFO_EXTENSION);
            if ($ext !== 'csv') {
                $error_message .= '* '.__('Le fichier ne contient pas l\'extension de fichier .csv. Veuillez choisir un 				fichier .csv valide');
            } else {
                annuaire_artisans_page_parse($_FILES['artisan_csv']['tmp_name']);
            }
        }
    }

    if ($error_message) {
        echo '<p>'.$error_message.'</p>';
    }

    ?>
    
<div class="container">
    <form role="form" method="post" input type="file" enctype="multipart/form-data" id="file" accept=".csv">
        <label>Sélectionner le ficher artisan_csv:</label>
        </br>
        <input name="artisan_csv" type="file">
        </br>
        <input name="submit" value="Importer" type="submit">
    </form>
</div>

    <?php
}


global $annuaire_artisans_db_version;
$annuaire_artisans_db_version = '1.0';




add_shortcode('artisans-form', 'artisans_form');
add_shortcode('artisans-results', 'artisans_results');
?>