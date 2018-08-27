<?php
function annuaire_artisans_page_parse($file)
{
    if ($file && $handle = fopen($file, "r")) {
        $row = 0;
        // DELETE FROM $wpdb->prefix . 'artisan'
        global $wpdb;
        $table_name = $wpdb->prefix . 'artisantemp';
        $delete = $wpdb->query("TRUNCATE TABLE $table_name");
        
        while (($data = fgetcsv($handle, 0, "\t"))!== false) {
            $num = count($data);
            echo "<p> $num fields in line $row: <br /></p>\n";
            $row++;
            if ($row !== 1) {
                annuaire_artisans_insert($data);
            }
        }
        fclose($handle);
    }
}

function annuaire_artisans_insert($data)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'artisantemp';
    
    print_r($data);
    echo '<br />';

    $sql = $wpdb->prepare("INSERT into `$table_name`(`rm_id`, `address_1`, `address_2`, `postal_code`, `telephone`, `fax`, `email`, `business_name`, `website_code`, `website_type`, `subactivity_type`, `town_name`, `district_name`, `district_id`, `cma_id`,`aprm_id`) VALUES (%d, %s, %s, %s, %s, %s, %s,%s, %d, %s, %s, %s, %s, %d,%d,%s)", $data[0], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7], $data[9], $data[10], $data[13], $data[14], $data[16]);

    
    $query = $wpdb->query($sql);
}
