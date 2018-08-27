<?php

$sql = $wpdb->prepare('INSERT INTO  wp_cma46_art_district (district_id, district_name)
SELECT  district_id, district_name
FROM wp_cma46_artisantemp
WHERE NOT EXISTS (SELECT district_id from wp_cma46_art_district WHERE wp_cma46_artisantemp.district_id = wp_cma46_art_district.district_id)
GROUP BY district_id,district_name');
//this seems to work
$sql1 = $wpdb->prepare('INSERT INTO  wp_cma46_art_town (town_name,postal_code, district_id)
SELECT DISTINCT town_name,left(postal_code, 5), district_id FROM wp_cma46_artisantemp
WHERE NOT EXISTS (SELECT DISTINCT town_name,left(postal_code, 5), district_id) group by town_name, postal_code, district_id');

$sql2 = $wpdb->prepare('INSERT INTO  wp_cma46_art_website (website_code, website_type)
SELECT DISTINCT website_code, website_type
FROM wp_cma46_artisantemp 
WHERE NOT EXISTS (SELECT DISTINCT website_code from wp_cma46_art_website WHERE wp_cma46_artisantemp.website_code = wp_cma46_art_website.website_code)
ORDER BY website_code, website_type');

$sql3 = $wpdb->prepare('INSERT INTO  wp_cma46_art_cma_activity (cma_id,aprm_id,activity_type)
SELECT DISTINCT cma_id,aprm_id,activity_type
FROM wp_cma46_artisantemp GROUP BY cma_id,aprm_id,activity_type');

$sql4 = $wpdb->prepare('INSERT INTO  wp_cma46_artisan (rm_id,business_name,address_1,address_2,telephone,fax,email)
	SELECT rm_id,business_name,address_1,address_2,telephone,fax,email FROM wp_cma46_artisantemp');

$query = $wpdb->query($sql);
$query = $wpdb->query($sql1);
$query = $wpdb->query($sql2);
$query = $wpdb->query($sql3);
$query = $wpdb->query($sql4);
