<noscript>This form requires that you have javascript enabled to work properly please enable javascript in your browser.</noscript>

<?php


function artisans_form($atts)
{
    global $wpdb;

    // Form values
    
    $expertise = !empty($_GET['expertise']) ? (int) $_GET['expertise'] : null;
    $activity = !empty($_GET['activity']) ? (int) $_GET['activity'] : null;
    $subactivity = !empty($_GET['subactivity']) ? (int) $_GET['subactivity'] : null;
    $district = !empty($_GET['district']) ? (int) $_GET['district'] : null;
    $town = !empty($_GET['town']) ? (int) $_GET['town'] : null;

    $form = '<form method="GET">';

     // expertise
    $form .= '<select name="expertise" id="s1" 	
   				onchange="this.form.submit()">
			  <option value="">Sélectionnez une démarche</option>';
    $table_name = $wpdb->prefix . 'art_expertise';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($expertise == $row->expertise_id) {
            $form .= '<option selected="selected" value="' . $row->expertise_id . '">' . $row->expertise_name . '</option>';
        } else {
            $form .= '<option value="' . $row->expertise_id . '">' . $row->expertise_name . '</option>';
        }
    }
    $form .= '</select>';
    // Activity
    $form .= '<select name="activity" id="s2" 	
   				onchange="this.form.submit()">
			  <option value="">Sélectionnez un secteur d-activites</option>';
    $table_name = $wpdb->prefix . 'art_activity';
    $filter = $wpdb->get_results("select * from $table_name");
    foreach ($filter as $row) {
        if ($activity == $row->nafa_id) {
            $form .= '<option selected="selected" value="' . $row->nafa_id . '">' . $row->activity_type . '</option>';
        } else {
            $form .= '<option value="' . $row->nafa_id . '">' . $row->activity_type . '</option>';
        }
    }
    $form .= '</select>';

 // SubActivity
    if ($activity) {
        $form .= '<select name="subactivity" id="s3" 	
   				onchange="this.form.submit()">
			  <option value="">Sélectionnez une activité</option>';
        $table_name = $wpdb->prefix . 'art_subactivity';
        $filter = $wpdb->get_results("select * from $table_name WHERE nafa_id = " . $activity);
        foreach ($filter as $row) {
            if ($subactivity == $row->nafa_id) {
                $form .= '<option selected="selected" value="' . $row->nafa_id . '">' . $row->subactivity_type . '</option>';
            } else {
                $form .= '<option value="' . $row->nafa_id . '">' . $row->subactivity_type . '</option>';
            }
        }
        $form .= '</select>';
    }

    // District
    $form .= '<select name="district" id="s4" 	
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
        $form .= '<select name="town" id="s5">
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

    // Form values
    $expertise = !empty($_GET['expertise']) ? (int) $_GET['expertise'] : null;
    $activity= !empty($_GET['activity']) ? (int) $_GET['activity'] : null;
    $subactivity= !empty($_GET['subactivity']) ? (int) $_GET['subactivity'] : null;
    $district = !empty($_GET['district']) ? (int) $_GET['district'] : null;
    $town = !empty($_GET['town']) ? (int) $_GET['town'] : null;


    $results = '@todo: get the results with params : expertise : ' . $expertise . ' and activity : ' . $activity . ' and subactivity : ' . $subactivity . ' and district :' . $district . ' and town : ' . $town;

    return $results;
}
