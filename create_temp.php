<?php
function annuaire_artisans_create_temp()
{
    global $wpdb;
    global $annuaire_artisans_db_version;

    $table_name = $wpdb->prefix . 'artisantemp';
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		  artisantemp_id INT NOT NULL AUTO_INCREMENT,
		  rm_id SMALLINT NOT NULL,
		  address_1 VARCHAR(100) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  address_2 VARCHAR(100) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NULL,
		  postal_code VARCHAR(100) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  telephone VARCHAR(20) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  fax VARCHAR(20) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NULL DEFAULT NULL,
		  email VARCHAR(75) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NULL DEFAULT NULL,
		  business_name VARCHAR(255) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  website_code SMALLINT NOT NULL,
		  website_type VARCHAR(25) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  subactivity_type VARCHAR(255) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  town_name VARCHAR(75) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  district_name VARCHAR(50) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  district_id SMALLINT NOT NULL,
		  cma_id SMALLINT NOT NULL,
		  aprm_id VARCHAR(10) CHARACTER SET 'utf8mb4' COLLATE 'utf8mb4_unicode_520_ci' NOT NULL,
		  last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
		  PRIMARY KEY (artisantemp_id))
		ENGINE = InnoDB
		AUTO_INCREMENT = 10839
		DEFAULT CHARACTER SET = utf8mb4,
    	$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    add_option('annuaire_artisans_db_version', $annuaire_artisans_db_version);
}
