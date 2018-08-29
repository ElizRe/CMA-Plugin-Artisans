<?php

//$wpdb->show_errors();
// print_r( $wpdb->queries );
function prefix_enqueue()
{
    // JS
    wp_register_script('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js');
    wp_enqueue_script('prefix_bootstrap');

    // CSS
    wp_register_style('prefix_bootstrap', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css');
    wp_enqueue_style('prefix_bootstrap');
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
    $form .= '<select name="website" id="s1" 	
   				onchange="this.form.submit()">
			  <option value="">Sélectionnez une démarche</option>';
    $table_name = $wpdb->prefix . 'art_website';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($website == $row->website_code) {
            $form .= '<option selected="selected" value="' . $row->website_code . '">' . $row->website_expert . '</option>';
        } else {
            $form .= '<option value="' . $row->website_code . '">' . $row->website_expert . '</option>';
        }
    }
    $form .= '</select>';

    // Family of Activities
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
    $form .= '</select>';

 // Activity
    if ($family) {
        $form .= '<select name="activity" id="s3" 	
   				onchange="this.form.submit()">
			  <option value="">Sélectionnez une activité</option>';
        $table_name = $wpdb->prefix . 'art_activity';
        $filter = $wpdb->get_results("select * from $table_name WHERE SUBSTR(cma_id,1,1) = " . $family);
        foreach ($filter as $row) {
            if ($activity == $row->cma_id) {
                $form .= '<option selected="selected" value="' . $row->cma_id . '">' . $row->activity_name . '</option>';
            } else {
                $form .= '<option value="' . $row->cma_id . '">' . $row->activity_name . '</option>';
            }
        }
        $form .= '</select>';
    }

    // District
    $form .= '<select name="district" id="s5" 	
   				onchange="this.form.submit()">
			  <option value="">Sélectionnez un canton</option>';
    $table_name = $wpdb->prefix . 'art_district';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($district == $row->district_id) {
            $form .= '<option selected="selected" value="' . $row->district_id . '">' . $row->district_name . '</option>';
        } else {
            $form .= '<option value="' . $row->district_id . '">' . $row->district_name . '</option>';
        }
    }
    $form .= '</select>';


    // Town
    if ($district) {
        $form .= '<select name="town" id="s6">
        onchange="this.form.submit()">
			  <option value="">Sélectionnez une commune</option>';
        $table_name = $wpdb->prefix . 'art_town';
        $filter = $wpdb->get_results("select * from $table_name WHERE district_id = " . $district);
        foreach ($filter as $row) {
            if ($town == $row->town_id) {
                $form .= '<option selected="selected" value="' . $row->town_id . '">' . $row->town_name . '</option>';
            } else {
                $form .= '<option value="' . $row->town_id . '">' . $row->town_name . '</option>';
            }
        }
        $form .= '</select>';
    }

    $form .= ' <input type="submit" value="Afficher les résultats">';
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


    // $results = '@todo: get the results with params : expertise : ' . $expertise . ' and activity : ' . $activity . ' and activity ' and district :' . $district . ' and town : ' . $town;
    
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

    foreach ($results as $print) {
        $list .= ' <div class="form-group">';
        $list .= ' <label>Qualification Artisan:</label>' . $print->website_expert.'</div>';
        $list .= '<div class="form-group">';
        $list .= ' <label>Secteur activités:</label>'. $print->subactivity_name.'</div>';
        $list .= ' <div class="form-group">';
        $list .= ' <label>Business Name:</label>' . $print->business_name.'</div>';
        $list .= ' <div class="form-group">';
        $list .= ' <label>Address:</label>' . $print->address_1.'</div>';
        $list .= ' <div class="form-group">';
        $list .= ' <label>Address:</label>' . $print->address_2.'</div>';
        $list .= ' <div class="form-group">';
        $list .= ' <label>Téléphone:</label>0' . $print->telephone.'</div>';
        $list .= ' <div class="form-group">';
        $list .= ' <label>Fax:</label>0' . $print->fax.'</div>';
        $list .= ' <div class="form-group">';
        $list .= ' <label>email:</label>' . $print->email.'</div>';
        $list .= ' <div class="form-group">';
        $list .= '<label>Commune:</label>'. $print->town_name.'</div>';

        $list .= ' <div class="form-group">';
          $list .= ' <label>Code Postal:</label>'. $print->postal_code.'</div>';
        $list .= '  </div>';
        $list .= '  </div>';
    }

    return $list;
}
