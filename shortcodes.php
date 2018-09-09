<?php

// load my plugin css and bootstrap into the website's front-end
function artisancss_enqueue_style()
{
    wp_enqueue_style('erstyle', plugins_url('assets/css/erstyle.css', __FILE__));
    wp_enqueue_style('artisan-style', plugins_url('assets/css/bootstrap.min.css', __FILE__));
}
// load bootstrap js into the website's front-end

function artisanjs_enqueue_script()
{
    wp_enqueue_script('bootstrap_js', 'https://code.jquery.com/jquery-3.3.1.slim.min.js');
    wp_enqueue_script('popper_js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
}

add_action('wp_enqueue_scripts', 'artisancss_enqueue_style', 'artisanjs_enqueue_script');


/* main code to use dropdown menu annuaire artisan page*/

function artisans_form($atts)
{
    global $wpdb;

    // Form values
    
    $website = !empty($_GET['website']) ? (int) $_GET['website'] : null;
    $family = !empty($_GET['family']) ? (int) $_GET['family'] : null;
    $activity = !empty($_GET['activity']) ? (int) $_GET['activity'] : null;
    $district = !empty($_GET['district']) ? (int) $_GET['district'] : null;
    $town = !empty($_GET['town']) ? (int) $_GET['town'] : null;

    $form = '<form method="GET">';

     // expertise
    $form .= '<div class="form-group">';
    $form .= '<label for {
        ="website">Sélectionnez une démarche</label>';
    $form .= '<br>';
    $form .= '<select name="website" id="s1"
                onchange="this.form.submit()">';
    $table_name = $wpdb->prefix . 'art_website';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($website == $row->website_code) {
            $form .= '<option selected="selected" value="' . $row->website_code . '">' . $row->website_expert . '</option>';
        } else {
            $form .= '<option value="' . $row->website_code . '">' . $row->website_expert . '</option>';
        }
    }
    $form .= ' </div>';
    $form .= '<br>';
    $form .= '</select>';

    // Family of Activities
    $form .= '<div class="form-group">';
    $form .= '<label for="family">Sélectionnez un secteur d\'activités</label>';
    $form .= '<br>';
    $form .= '<select name="family" id="s2"     
                onchange="this.form.submit()">
              <option value="">Sélectionnez un secteur d\'activités</option>';
    $table_name = $wpdb->prefix . 'art_family';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($family == $row->family_id) {
            $form .= '<option selected="selected" value="' . $row->family_id . '">' . $row->family_name . '</option>';
        } else {
            $form .= '<option value="' . $row->family_id . '">' . $row->family_name . '</option>';
        }
    }
    $form .= ' </div>';
    $form .= '<br>';
    $form .= '</select>';

 // Activity
    if ($family) {
        $form .= '<div class="form-group">';
        $form .= '<label for="activity">Sélectionnez une activité</label>';
        $form .= '<br>';
        $form .= '<select name="activity" id="s3"   
                onchange="this.form.submit()">';
        $table_name = $wpdb->prefix . 'art_activity';
        $filter = $wpdb->get_results("select * from $table_name WHERE SUBSTR(cma_id,1,1) = " . $family);
        foreach ($filter as $row) {
            if ($activity == $row->cma_id) {
                $form .= '<option selected="selected" value="' . $row->cma_id . '">' . $row->activity_name . '</option>';
            } else {
                $form .= '<option value="' . $row->cma_id . '">' . $row->activity_name . '</option>';
            }
        }
        $form .= ' </div>';
        $form .= '<br>';
        $form .= '</select>';
    }

    // District
        $form .= '<div class="form-group">';
        $form .= '<label for="district">Sélectionnez un canton</label>';
        $form .= '<br>';
        $form .= '<select name="district" id="s5"   
                onchange="this.form.submit()">';
    $table_name = $wpdb->prefix . 'art_district';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($district == $row->district_id) {
            $form .= '<option selected="selected" value="' . $row->district_id . '">' . $row->district_name . '</option>';
        } else {
            $form .= '<option value="' . $row->district_id . '">' . $row->district_name . '</option>';
        }
    }
    $form .= ' </div>';
    $form .= '<br>';
    $form .= '</select>';


    // Town
    if ($district) {
        $form .= '<div class="form-group">';
        $form .= '<label for="town">Sélectionnez une commune</label>';
        $form .= '<br>';
        $form .= '<select name="town" id="s6">
        onchange="this.form.submit()">';
        $table_name = $wpdb->prefix . 'art_town';
        $filter = $wpdb->get_results("select * from $table_name WHERE district_id = " . $district);
        foreach ($filter as $row) {
            if ($town == $row->town_id) {
                $form .= '<option selected="selected" value="' . $row->town_id . '">' . $row->town_name . '</option>';
            } else {
                $form .= '<option value="' . $row->town_id . '">' . $row->town_name . '</option>';
            }
        }
        $form .= ' </div>';
        $form .= '<br>';
        $form .= '</select>';
    }
    $form .= '<br>';
    $form .= '<br>';
    $form .= ' <input type="submit" class="btn btn-success" value="Afficher les résultats">';
    $form .= '</form>';

    return $form;
}



function artisans_results($atts)
{
    global $wpdb;

    $list = '';
    $lien = "vitrines/". $print->rm_id.".htm";

    // Form values
    $website = !empty($_GET['website']) ? (int) $_GET['website'] : null;
    $family= !empty($_GET['family']) ? (int) $_GET['family'] : null;
    $activity= !empty($_GET['activity']) ? (int) $_GET['activity'] : null;
    $district = !empty($_GET['district']) ? (int) $_GET['district'] : null;
    $town = !empty($_GET['town']) ? (int)$_GET['town'] : null;


    // Get the results of selections
    
    $table_name  = $wpdb->prefix . 'artisan';
    $table_name2 = $wpdb->prefix . 'art_subactivity';
    $table_name3 = $wpdb->prefix . 'art_town';
    $table_name4 = $wpdb->prefix . 'art_website';

    $results = $wpdb->get_results("SELECT rm_id,website_expert,subactivity_name,business_name,address_1,
        address_2,telephone,fax,email,postal_code,
        artisan.website_code,town_name,sub.subactivity_id,cma
               FROM $table_name as artisan
               JOIN $table_name2 as sub
               ON artisan.subactivity_id=sub.subactivity_id
               JOIN $table_name3 as town
               ON artisan.town_id=town.town_id
               JOIN  $table_name4 as web
               on artisan.website_code=web.website_code
               WHERE
               artisan.website_code = $website 
               AND
               sub.cma = $activity 
               AND artisan.town_id = $town");

    $list .='<div class="container-fluid">';
    $list .= '<div class="row">';
    foreach ($results as $print) {
        $list .= '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">';
        $list .= '<h2>'. $print->business_name.'</h2>';
        $list .= '<div class="our-services-wrapper mb-60">';
        $list .= '<div class="services-inner">';
        $list .= '<div class="our-services-img">';
        $list .= '<img src="http://localhost:8888/wp-content/uploads/2018/09/artisan.png" width="68px" alt="artisan">';
        $list .= '</div>';
        $list .= '<div class="our-services-text">';
        $list .= '<p>Qualification Artisanale: '. $print->website_expert.'</p>';
        $list .= '<p>Activité:  '. $print->subactivity_name.'</p>';
        $list .= '<p>'. $print->address_1.', '. $print->address_2.'</p>';
        $list .= '<p>'. $print->town_name.',  '. $print->postal_code.'</p>';
        $list .= '<p>Tél: ' . $print->telephone.'</p>';
        $list .= '<p>Fax: ' . $print->fax.'</p>';
        $list .= '<p>Courriel: </p>';
        $list .= '<a href="mailto:'. $print->email.'">'. $print->email.'</a>';
        $list .= '<p>Vitrine:</p>';
        $list .= '<a href="http://www.cma-cahors.fr/vitrines/'.$print->rm_id.'.htm">'.$print->business_name.'</a>';
        $list .= '</div>';
        $list .= '</div>';
        $list .= '</div>';
        $list .= '</div>';
    }
    $list .= '</div>';
    $list .= '</div>';


    return $list;
}
