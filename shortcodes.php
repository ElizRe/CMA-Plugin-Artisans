<?php

// load my plugin css and bootstrap into the website's front-end
function myplugin_enqueue_style()
{
    
   // wp_enqueue_style('myplugin-style2', plugins_url('assets/css/erstyle.css', __FILE__));

    wp_enqueue_style('myplugin-style', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css');
    wp_enqueue_script('jquery');
}

function myplugin_enqueue_script()
{

    wp_enqueue_script('bootstrap_js', 'https://code.jquery.com/jquery-3.3.1.slim.min.js');
    wp_enqueue_script('popper_js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js');
}
add_action('wp_enqueue_scripts', 'myplugin_enqueue_style', 'myplugin_enqueue_script');

/* my test to use ajax in my form */

add_action('wp_ajax_my_action', 'my_action');
add_action('wp_ajax_nopriv_my_action', 'my_action');

if (is_admin()) {
    add_action('wp_ajax_my_frontend_action', 'my_frontend_action');
    add_action('wp_ajax_nopriv_my_frontend_action', 'my_frontend_action');
    add_action('wp_ajax_my_backend_action', 'my_backend_action');
    // Add other back-end action hooks here
} else {
    // Add non-Ajax front-end action hooks here
}


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
    $form .= '<label for="website">Sélectionnez une démarche</label>';
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

    // Form values
    $website = !empty($_GET['website']) ? (int) $_GET['website'] : null;
    $family= !empty($_GET['family']) ? (int) $_GET['family'] : null;
    $activity= !empty($_GET['activity']) ? (int) $_GET['activity'] : null;
    $district = !empty($_GET['district']) ? (int) $_GET['district'] : null;
    $town = !empty($_GET['town']) ? (int)$_GET['town'] : null;


    // Get the results with params : expertise : ' . $expertise . ' and activity : ' . $activity . ' and activity ' and district :' . $district . ' and town : ' . $town;
    
    $table_name = $wpdb->prefix . 'artisan';
    $results = $wpdb->get_results("SELECT    website_expert,subactivity_name,business_name,address_1,
               address_2,telephone,fax,email,postal_code,
               artisan.website_code,town_name, sub.subactivity_id,cma
               FROM $table_name as artisan
               JOIN wp_cma46_art_subactivity as sub
               ON artisan.subactivity_id=sub.subactivity_id
               JOIN wp_cma46_art_town as town
               ON artisan.town_id=town.town_id
               JOIN wp_cma46_art_website as web
               on artisan.website_code=web.website_code
               WHERE
               artisan.website_code= $website AND
               sub.cma = $activity AND artisan.town_id = $town");

    $list .='<div class="container-fluid">';
    $list .= '<div class="row">';
    foreach ($results as $print) {
        $list .= '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">';
        $list .= '<h2>'. $print->business_name.'</h2>';
        $list .= '<div class="our-services-wrapper mb-60">';
        $list .= '<div class="services-inner">';
        $list .= '<div class="our-services-img">';
        $list .= '<img src="https://www.orioninfosolutions.com/assets/img/icon/Agricultural-activities.png" width="68px" alt="">';
        $list .= '</div>';
        $list .= '<div class="our-services-text">';
        $list .= '<p>Qualification Artisan:'. $print->website_expert.'</p>';
        $list .= '<p>Secteur activités:'. $print->subactivity_name.'</p>';
        $list .= '<p>Business Name:'. $print->business_name.'</p>';
        $list .= '<p>Address:'. $print->address_1.','. $print->address_2.'</p>';
        $list .= '<p>Commune:'. $print->town_name.'</p>';
        $list .= '<p>Code Postal:'. $print->postal_code.'</p>';
        $list .= '<p>Téléphone:0' . $print->telephone.'</p>';
        $list .= '<p>Fax:0' . $print->fax.'</p>';
        $list .= '<p>email:' . $print->email.'</p>';
        $list .= '</div>';
        $list .= '</div>';
        $list .= '</div>';
        $list .= '</div>';
    }
    $list .= '</div>';
    $list .= '</div>';


    return $list;
}
