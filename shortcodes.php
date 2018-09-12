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

/* add ajax to dropdown select page annuaireartisanslot*/

function artisan_form_ajax_activity()
{
    global $wpdb;

    check_ajax_referer('artisan_nonce');

    $family = intval($_POST['family']);
    $form = '';

    //Activity
    if ($family) {
        $form .= '<div class="form-group">';
        $form .= '<label for="activity">Sélectionnez une activité</label>';
        $form .= '<br>';
        $form .= '<select name="activity" id="s3">';
                $form .= '<option value="0">Toutes les activités</option>';
        $table_name = $wpdb->prefix . 'art_activity';
        $filter = $wpdb->get_results("select * from $table_name WHERE SUBSTR(cma_id,1,1) = " . $family);
        foreach ($filter as $row) {
            if ($activity == $row->cma_id) {
                $form .= '<option selected="selected" value="' . $row->cma_id . '">' . $row->activity_name . '</option>';
            } else {
                $form .= '<option value="' . $row->cma_id . '">' . $row->activity_name . '</option>';
            }
        }
        $form .= '</div>';
        $form .= '<br>';
        $form .= '</select>';
    }

    echo $form;
    die();
}
add_action('wp_ajax_artisan_form_ajax_activity', 'artisan_form_ajax_activity');
add_action('wp_ajax_nopriv_artisan_form_ajax_activity', 'artisan_form_ajax_activity');

function artisan_form_ajax_cantons()
{
    global $wpdb;

    check_ajax_referer('artisan_nonce');

    $district = intval($_POST['district']);
    $form = '';

    //Town
    if ($district) {
        $form .= '<div class="form-group">';
        $form .= '<label for="town">Sélectionnez une communes</label>';
        $form .= '<br>';
        $form .= '<select name="town" id="s6">';
        $form .= '<option value="0">Toutes les communes</option>';
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

    echo $form;
    die();
}
add_action('wp_ajax_artisan_form_ajax_cantons', 'artisan_form_ajax_cantons');
add_action('wp_ajax_nopriv_artisan_form_ajax_cantons', 'artisan_form_ajax_cantons');

/* end of ajax coding *

/* main code to use dropdown menu annuaire artisan page*/
// on page annuaire artisans this is the shortcode artisans-form
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

    $form .= '<div class="artisans-filters">';

     // expertise
    $form .= '<div class="form-group">';

    $form .= '<label for="website">Sélectionnez une démarche </label>';
    $form .= '<br>';
    $form .= '<select name="website" id="s1">
                <option value="0">Tous les Artisans</option>';

    $table_name = $wpdb->prefix . 'art_website';
    // coding to avoid showing duplicates of Artisan from database
    $filter = $wpdb->get_results("select * from $table_name WHERE website_expert != 'Artisan'");
    foreach ($filter as $row) {
        if ($website == $row->website_code) {
            $form .= '<option selected="selected" value="' . $row->website_code . '">' . $row->website_expert . '</option>';
        } else {
            $form .= '<option value="' . $row->website_code . '">' . $row->website_expert . '</option>';
        }
    }
    $form .= '</select>';
    $form .= ' </div>';

    // Family of Activities
    $form .= '<div class="form-group">';
    $form .= '<label for ="family">Sélectionnez un secteur d\'activités</label>';
    $form .= '<br>';
    $form .= '<select name="family" id="s2">';
    $form .= '<option value="0">Tous les secteurs d\'activités</option>';
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
        $form .= '<div id="ss2">';
        $form .= '<label for="activity">Sélectionnez une activité</label>';
        $form .= '<br>';
        $form .= '<select name="activity" id="s3">';
        $form .= '<option value="0">Tous les activités</option>';
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
        $form .= ' </div>';
    } else {
        $form .= '<div id="ss2"></div>';
    }

    $form .= ' </div>';

    // District
    $form .= '<div class="form-group">';
    $form .= '<label for="district">Sélectionnez un canton</label>';
    $form .= '<br>';
    $form .= '<select name="district" id="s5">';
    $form .= '<option value="0">Tous les cantons</option>';
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
        $form .= '<div id="ss5">';
        $form .= '<label for="town">Sélectionnez une commune</label>';
        $form .= '<br>';
        $form .= '<select name="town" id="s6">';
        $form .= '<option value="0">Toutes les communes</option>';
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
        $form .= ' </div>';
    } else {
        $form .= '<div id="ss5"></div>';
    }
    $form .= ' </div>';


    $form .= '</div>';
    $form .= '<br /><input type="submit" class="btn btn-success" value="Afficher les résultats">';

    return $form;
}


// on page annuaire artisans this is the shortcode artisans-results
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

    $sql = "SELECT rm_id,website_expert,subactivity_name,
            business_name,address_1,address_2,telephone,fax,email,
            postal_code,level,artisan.website_code,town_name,
            sub.subactivity_id,cma
               FROM $table_name as artisan
               JOIN $table_name2 as sub
               ON artisan.subactivity_id=sub.subactivity_id
               JOIN $table_name3 as town
               ON artisan.town_id=town.town_id
               JOIN  $table_name4 as web
               on artisan.website_code=web.website_code
               WHERE 1 ";

    if ($website) {
        $sql .= "AND artisan.website_code = $website ";
    }
    if ($family) {
        //$sql .= "AND xxxx = $family ";
    }
    if ($activity) {
        $sql .= "AND sub.cma = $activity ";
    }
    if ($district) {
        //$sql .= "AND xxxx = $district ";
    }
    if ($town) {
        $sql .= "AND artisan.town_id = $town ";
    }
        
    $sql .= "GROUP BY (artisan.rm_id)";

    $results = $wpdb->get_results($sql);

    $list .='<div class="container-fluid">';
    $list .= '<div class="row">';
    foreach ($results as $print) {
        $list .= '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">';
        $list .= '<h2 class="artisan">'. $print->business_name.'</h2>';
        $list .= '<div class="our-services-wrapper mb-60">';
        $list .= '<div class="services-inner">';
        $list .= '</p><p>';
        if ($print->level == "MAITRE ARTISAN") {
            $list .= '<div class="our-services-img">';
            $list .= '<img src="' . plugins_url('images/expertartisan.png', __FILE__) . '"> ';
            $list .= '</div>';
        } elseif ($print->level == "MAITRE ARTISAN EN METIERS D'ART") {
            $list .= '<div class="our-services-img">';
            $list .= '<img src="' . plugins_url('images/expertartisan.png', __FILE__) . '"> ';
            $list .= '</div>';
        } elseif ($print->level == "ARTISAN") {
            $list .= '<div class="our-services-img">';
            $list .= '<img src="' . plugins_url('images/artisan.png', __FILE__) . '"> ';
            $list .= '</div>';
        } elseif ($print->level == "ARTISAN EN METIERS D'ART") {
            $list .= '<div class="our-services-img">';
            $list .= '<img src="' . plugins_url('images/artisan.png', __FILE__) . '"> ';
            $list .= '</div>';
        }
        $list .= '<div class="our-services-text">';
        $list .= '<p class="activity"><span>Activité:</span><br/><br/>'. $print->subactivity_name.'</p>';
        if ($print->level) {
            $list .= '<p class="title"><span>Titre:</span><br /><br />'. $print->level.'</p>';
        }
        $list .= '<p></p>';
        if ($print->website_code =="44") {
            $list .= '<p class="expert"><span>Les Démarches remarquables:</span><br /><br />'. $print->website_expert.'</p>';
        } elseif ($print->website_code == "66") {
            $list .= '<p class="expert"><span>Les Démarches remarquables:</span><br /><br />'. $print->website_expert.'</p>';
        } elseif ($print->website_code == "76") {
            $list .= '<p class="expert"><span>Les Démarches remarquables:</span><br /><br />'. $print->website_expert.'</p>';
        }
        $list .= '<p>'. $print->address_1.', '. $print->address_2.'<br />';
        $list .= $print->town_name.',  '. $print->postal_code.'</p><p>';
        if ($print->telephone) {
            $list .= 'Tél: 0' . $print->telephone.'<br />';
        }
        if ($print->fax) {
            $list .= 'Fax: 0' . $print->fax.'<br />';
        }
        $list .= '</p><p>';
        if ($print->email) {
            $list .= '<a href="mailto:'. $print->email.'">'. $print->email.'</a>';
        }
        $list .= '</p>';
        
        $list .= '<p></p>';
        if ($print->website_code != "11") {
            $list .= '<p>Vitrine:<br />';
            $list .= '<a href="http://www.cma-cahors.fr/vitrines/'.$print->rm_id.'.htm" target="_blank">'.$print->business_name.'</a></p>';
        }
        $list .= '</div>';
        $list .= '</div>';
        $list .= '</div>';
        $list .= '</div>';
    }
    $list .= '</div>';
    $list .= '</div>';


    return $list;
}
