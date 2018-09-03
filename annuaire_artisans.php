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

require('create_artisan.php');
require('parse.php');
require('shortcodes.php');
/** add action and filter hooks*/

function er_enqueue()
{
    wp_register_style('er_bootstrap', get_template_directory_uri().'/assets/css/bootstrap.min.css');
    //wp_register_script('js-upload-files', get_template_directory_uri().'/assets/js/js-upload-files.js');
    wp_register_script('er_bootstrap', get_template_directory_uri().'/assets/js/bootstrap.min.js');
}



/**
 * Include CSS file for Artisan.
 */
function load_custom_wp_admin_style($hook)
{
        // Load only on page=tools/annuaire_artisans
    if ($hook != 'tools_page_annuaire_artisans'and 'page_annuaireartisanslot') {
            return;
    }
        wp_enqueue_style('style', plugins_url('assets/css/erstyle.css', __FILE__));
        wp_enqueue_style('er_bootstrap', plugins_url('assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_script('jquery');
       // wp_enqueue_script('js-upload-files', plugins_url('assets/js/js-upload-files.js', __FILE__));
        wp_enqueue_script('er_bootstrap', plugins_url('assets/js/bootstrap.min.js', __FILE__));
}
add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');
add_action('wp_enqueue_scripts', 'er_enqueue');

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
             <!-- Standard Form -->
<div class="container">
    <form role="form" method="post" input type="file" enctype="multipart/form-data" id="file" accept=".csv">
        <label>Sélectionner le ficher artisan_csv:</label>
        </br>
        <input name="artisan_csv" class="btn btn-secondary"value="Chercher"type="file">
        </br>
        <input name="submit" class="btn btn-success"value="Importer" type="submit">
    <input class="btn btn-danger" type="reset" value="Reset">
    </form>
</div>
    <?php
}


    global $annuaire_artisans_db_version;
    $annuaire_artisans_db_version = '1.0';




    add_shortcode('artisans-form', 'artisans_form');
    add_shortcode('artisans-results', 'artisans_results');
?>