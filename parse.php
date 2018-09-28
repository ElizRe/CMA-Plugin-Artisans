<?php
function annuaire_artisans_page_parse($file)
{
    if ($file && $handle = fopen($file, "r")) {
        $row = 0;

        // DELETE FROM $wpdb->prefix . "artisan"
        global $wpdb;
        $wpdb->query("SET FOREIGN_KEY_CHECKS = 0");
        $table_name = $wpdb->prefix . "artisan";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $table_name = $wpdb->prefix . "art_website";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $table_name = $wpdb->prefix . "art_subactivity";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $table_name = $wpdb->prefix . "art_town";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $table_name = $wpdb->prefix . "art_district";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $table_name = $wpdb->prefix . "art_activity";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $table_name = $wpdb->prefix . "art_family";
        $wpdb->query("TRUNCATE TABLE $table_name");
        $wpdb->query("SET FOREIGN_KEY_CHECKS = 1");
        
        while (($data = fgetcsv($handle, 0, "\t"))!== false) {
            annuaire_artisans_insert($data);
            $row++;
        }
       
        fclose($handle);
        echo "<p>$row artisans importés</p>\n";
        echo 'Le fichier CSV a été importé avec succès';
    }
}
function annuaire_artisans_insert($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix ."artisan";

    // Table Website
    annuaire_artisans_insert_website($data[13], $data[14]);
    
    // Tables District & Town
    $town_id = annuaire_artisans_insert_district($data[18], $data[20], $data[19], $data[4]);

    // Tables family, activity & subactivity
    $subactivity_id = annuaire_artisans_insert_family($data[22], $data[16], $data[23]);

    $sql = $wpdb->prepare(
        "INSERT into `$table_name`(
            `rm_id`, 
            `address_1`, 
            `address_2`, 
            `telephone`, 
            `fax`, 
            `email`, 
            `business_name`, 
            `website_code`,
            `subactivity_id`,
            `town_id`,
            `level`

        ) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %d, %d,%d,%s)",
        $data[1],
        $data[2],
        $data[3],
        $data[5],
        $data[6],
        $data[7],
        $data[10],
        $data[13],
        $subactivity_id,
        $town_id,
        $data[24]
    );

    
    $query = $wpdb->query($sql);
}

function annuaire_artisans_insert_website($code, $type)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "art_website";

    // Specif code for artisan with a CMA website
    if ($type == "1 ANNUAIRE SANS MAIL") {
        $code = 11;
    }

    // Check if website type exist
    $exists = $wpdb->get_results("SELECT website_code FROM $table_name WHERE website_code = ".$code);
    
    // Create if not exists
    if (!$exists) {
        $type_artisan = annuaire_artisans_get_artisan_types($type);
        $sql = $wpdb->prepare("INSERT INTO $table_name (website_code, website_type, website_expert) VALUES (%d, %s, %s)", $code, $type, $type_artisan);
        $wpdb->query($sql);
    }
}

// Artisan type hardcoded values
function annuaire_artisans_get_artisan_types($type)
{
    $types = [
        "ANNUAIRE SANS MAIL"=> "Artisan",
        "VITRINE GRATUITE ANNEE 1"=> "Artisan",
        "VITRINE"=> "Artisan",
        "VITRINE RMA" => "Les Artisans de la Route des Métiers d'Art",
        "VITRINE ECODEFIS" => "Les artisans labélisés Eco Défis",
        "VITRINE ECO CONSTRUCTEUR" => "Les Artisans labélisés Eco Constructeur",
    ];

    return isset($types[$type]) ? $types[$type] : "";
}


function annuaire_artisans_insert_district($town_name, $district_id, $district_name, $postal_code)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "art_district";
    $table_name2 = $wpdb->prefix . "art_town";

    // Check if district exists
    $exists = $wpdb->get_results("SELECT district_id FROM $table_name WHERE district_id = ".$district_id);
    
    // Create if not exists
    if (!$exists) {
        $sql = $wpdb->prepare("INSERT INTO $table_name(district_id, district_name) VALUES(%d, %s)", $district_id, $district_name);
        $wpdb->query($sql);
    }

    // Check if town exists
    $result = $wpdb->get_results('SELECT town_id FROM '.$table_name2.' WHERE town_name = "'.$town_name.'" and postal_code = "'.substr($postal_code, 0, 5) . '"');
    
    // Create if not exists
    if (!$result) {
        $sql = $wpdb->prepare("INSERT INTO ".$table_name2." (town_name, postal_code, district_id) VALUES(%s, %s, %d)", $town_name, substr($postal_code, 0, 5), $district_id);
        $wpdb->query($sql);

        // Get new town id created by mysql
        $town_id = $wpdb->insert_id;
    } else {
        $town_id = $result[0]->town_id;
    }

    return $town_id;
}


function annuaire_artisans_insert_family($cma_id, $subactivity_name, $aprm_id)
{
    global $wpdb;
    $table_name = $wpdb->prefix . "art_family";
    $table_name2 = $wpdb->prefix . "art_activity";
    $table_name3 = $wpdb->prefix . "art_subactivity";

    $family_id = substr($cma_id, 0, 1);

    // Check if family exists
    $exists = $wpdb->get_results("SELECT family_id FROM $table_name WHERE family_id = ".$family_id);
    
    // Create if not exists
    if (!$exists) {
        $family_name = annuaire_artisans_get_family_name($family_id);
        if ($family_name) {
            $sql = $wpdb->prepare("INSERT INTO $table_name(family_id, family_name) VALUES(%d, %s)", $family_id, $family_name);
            $wpdb->query($sql);
        }
    }

    // Check if activity exists
    $exists = $wpdb->get_results("SELECT cma_id FROM $table_name2 WHERE cma_id = ".$cma_id);

    // Create if not exists
    if (!$exists) {
         $activity_name = annuaire_artisans_get_activity_name($cma_id);
        if ($activity_name) {
            $sql = $wpdb->prepare("INSERT INTO $table_name2(family_id,cma_id, activity_name) VALUES(%d,%d,%s)", $family_id, $cma_id, $activity_name);
            $wpdb->query($sql);
        }
    }

    // Check if subactivity exists
    $exists = $wpdb->get_results("SELECT subactivity_id FROM $table_name3 WHERE subactivity_name = '".addslashes($subactivity_name) . "'");
    
    // Create if not exists
    if (!$exists) {
        $sql = $wpdb->prepare("REPLACE INTO ".$table_name3."(cma,aprm,subactivity_name) VALUES(%d,%s,%s)", $cma_id, $aprm_id, $subactivity_name);
        $wpdb->query($sql);
           // Get new subactivity id created by mysql
        $subactivity_id = $wpdb->insert_id;
    } else {
        $subactivity_id = $exists[0]->subactivity_id;
    }
    return $subactivity_id;
}


// Artisan type hardcoded values
function annuaire_artisans_get_family_name($family_id)
{
    $family_name = [
    1 => "Alimentation",
    2 => "Bâtiment et habitat",
    3 => "Fabrication",
    4 => "Santé, soins et beauté",
    5 => "Services aux particuliers et aux entreprises",
    6 => "Véhicules : mécanique, réparation et construction",
    7 => "Artisanat d'Art",
    8 => "Autres activités",
    ];

    return isset($family_name[$family_id]) ? $family_name[$family_id] : "";
}

// Artisan activity hardcoded values
function annuaire_artisans_get_activity_name($cma_id)
{
    $activity_name = [
    101 => "Abattage  préparation de viandes",
    102 =>"Biscuiterie",
    103 => "Boucherie, charcuterie, triperie",
    104 => "Boulangerie, Pâtisserie",
    105 => "Conserverie",
    106 =>"Fabrication aliments pour animaux",
    107 => "Fabrication de boissons alcoolisées",
    108 =>"Fabrication de boissons non alcoolisées",
    109 => "Préparation de plats cuisinés à emporter, crèpes, beignets...",
    110 =>"Fabrication de produits laitiers",
    111 => "Fabrication  transformation de produits élaborés",
    112 => "Pâtisserie, chocolaterie, confiserie",
    113 => "Poissonnerie",
    115 => "Torréfaction de café",
    116 => "Transformation de poissons, crustacés, mollusques",
    117 => "Transformation de produits végétaux",
    201 => "Autres travaux de finition",
    202 => "Autres travaux d'installation",
    203 =>"Carrelage, revêtements sol et mur",
    204 =>"Charpente, couverture, zinguerie, étanchéïté",
    205=>"Electricité, antennes,alarmes",
    206=>"Fabrication de matériaux de construction",
    207=> "Fabrication de systèmes de chauffage",
    208=> "Fabrication et pose de bâches,stores",
    209=> "Maçonnerie, piscines",
    210=>"Menuiseries",
    211=>"Miroiterie",
    212=>"Plâtrerie",
    213=>"Plomberie, chauffage, climatisation",
    214=>"Restauration, rénovation",
    215=>"Terrassement,travaux publics, assainissement",
    216=>"Travail de la pierre",
    217=> "Travail des métaux",
    218=>"Travail du bois et dérivés",
    219=>"Travaux de démolition",
    220=>"Travaux de peinture",
    221=>"Travaux d'isolation",
    301=>"Machines",
    302=>"Matériel électrique,électronique, informatique, optique",
    303=>"Meubles",
    304=>"Produits bois et dérivés",
    305=>"Produits chimiques et plastiques",
    306=>"Systèmes mécaniques, hydrauliques, énergies renouvelables",
    307=>"Fabrication diverses",
    308=>"Fabrication et travail des métaux",
    309=>"Produits minéraux non métalliques",
    310=>"Fabrication,transformation de textiles",
    401=>"Coiffure",
    402=>"Esthétique",
    403=>"Fabrication de produits cosmétiques",
    404=>"Fabrication de prothèses",
    405=>"Fabrication d'équipements médicaux",
    406=>"Optique,lunetterie",
    407=>"Soins mortuaires",
    501=>"Compositions florales",
    502=>"Déménagement",
    503=>"Frigoriste",
    504=>"Imprimerie et arts graphiques",
    505=>"Nettoyage et entretien",
    506=>"Photographies",
     507=>"Pose de grillages, clôtures",
     508=>"Réparation de machines et équipements",
     509=>"Services divers",
     510=>"Soins aux animaux",
     511=>"Taxidermie",
     512=>"Taxis, ambulances",
     513=>"Textile,cuir, habillement",
     514=>"Traitement des eaux usées",
     515=>"Travail des métaux",
     516=>"Travaux administratifs",
     517=>"Travaux de voiries",
     601=>"Aménagement de véhicules",
     602=>"Carrosseie",
     603=>"Construction de véhicules et pièces",
     604=>"Contrôle technique",
     605=>"Electricité",
     606=>"Fabrication",
     607=>"Mécanique",
     701=>"Bijouterie fantaisie",
     702=>"Fabrication d'objets de décoration",
     703=>"Fabrication d'objets bois",
     704=>"Fabrication, réparation d'instruments de musique",
     705=>"Ferronnerie d'Art",
     706=>"Horlogerie, joaillerie, bijouterie",
     707=>"Poterie, céramique, émail",
     708=>"Reliure",
     709=>"Restauration d'objets d'Art",
     710=>"Travail du verre et vitrail",
     711=>"Vannerie, cannage, rempaillage",
     712=>"Ebénisterie, restauration de meubles",
     801=>"Désinfection, dératisation, désinsectisation",
     802=>"Exploitation de carrière, extraction",
     803=>"Imprimerie,sérigraphie",
     804=>"Reproduction d'enregistrements",
     805=>"Traitement et collecte des déchets et matériaux",
     806=>"Travaux d'artifice et de forage",

    ];

    return isset($activity_name[$cma_id]) ? $activity_name[$cma_id] : "";
}
