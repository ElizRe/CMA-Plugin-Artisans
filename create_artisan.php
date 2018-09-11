<?php
function annuaire_artisans_create_artisan()
{
    global $wpdb;
    global $annuaire_artisans_db_version;

    
    $table_name1 = $wpdb->prefix . 'art_website';
    $table_name2 = $wpdb->prefix . 'art_family';
    $table_name3 = $wpdb->prefix . 'art_activity';
    $table_name4 = $wpdb->prefix . 'art_subactivity';
    $table_name5 = $wpdb->prefix . 'art_district';
    $table_name6 = $wpdb->prefix . 'art_town';
    $table_name7 = $wpdb->prefix . 'artisan';

    
    $charset_collate = $wpdb->get_charset_collate();
    


    $sql1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
        website_code SMALLINT NOT NULL,
        website_type VARCHAR(25) NOT NULL,
        website_expert VARCHAR(50) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (website_code))
        $charset_collate;";

    $sql2 = "CREATE TABLE IF NOT EXISTS $table_name2(
        family_id SMALLINT NOT NULL,
        family_name VARCHAR(50) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (family_id))
        $charset_collate;";

    $sql3 = "CREATE TABLE IF NOT EXISTS $table_name3 (
        activity_id SMALLINT NOT NULL AUTO_INCREMENT,
        family_id SMALLINT NOT NULL,
        cma_id SMALLINT NOT NULL,
        activity_name VARCHAR(255) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (activity_id),
        INDEX ix_family (family_id),
        INDEX ix_cma_id (cma_id),
        CONSTRAINT fk_activity_family
          FOREIGN KEY (family_id)
          REFERENCES wp_cma46_art_family (family_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
         $charset_collate;";

    $sql4 = "CREATE TABLE IF NOT EXISTS $table_name4 (
        subactivity_id INT NOT NULL AUTO_INCREMENT,
        cma SMALLINT NOT NULL,
        subactivity_name VARCHAR(255) NOT NULL,
        aprm VARCHAR(10) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (subactivity_id),
        INDEX ix_cma (cma),
        UNIQUE INDEX subactivity_name_UNIQUE (subactivity_name ASC),
        CONSTRAINT fk_subactivity_activity
          FOREIGN KEY (cma)
          REFERENCES wp_cma46_art_activity (cma_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
          $charset_collate;";

    $sql5 = "CREATE TABLE IF NOT EXISTS $table_name5 (
        district_id SMALLINT NOT NULL,
        district_name VARCHAR(50) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (district_id))
        $charset_collate;";

    $sql6 = "CREATE TABLE IF NOT EXISTS $table_name6(
        town_id SMALLINT NOT NULL AUTO_INCREMENT,
        town_name VARCHAR(75) NOT NULL,
        postal_code VARCHAR(10) NOT NULL,
        district_id SMALLINT NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (town_id),
        CONSTRAINT fk_town_district
          FOREIGN KEY (district_id)
          REFERENCES wp_cma46_art_district (district_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
          $charset_collate;";
              

    $sql7 = "CREATE TABLE IF NOT EXISTS $table_name7(
        artisan_id INT NOT NULL AUTO_INCREMENT,
        rm_id VARCHAR(9) NOT NULL,
        business_name VARCHAR(255) NOT NULL,
        address_1 VARCHAR(100) NOT NULL,
        address_2 VARCHAR(100) NULL DEFAULT NULL,
        town_id SMALLINT NOT NULL,
        telephone VARCHAR(20) NOT NULL,
        fax VARCHAR(20) NULL DEFAULT NULL,
        email VARCHAR(75) NULL DEFAULT NULL,
        website_code SMALLINT NOT NULL,
        subactivity_id INT NOT NULL,
        level VARCHAR(200) NULL,
        last_update TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (artisan_id),
        CONSTRAINT fk_artisan_website
          FOREIGN KEY (website_code)
          REFERENCES wp_cma46_art_website (website_code)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION,
        CONSTRAINT fk_artisan_town
          FOREIGN KEY (town_id)
          REFERENCES wp_cma46_art_town (town_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION,
        CONSTRAINT fk_artisan_subactivity
          FOREIGN KEY (subactivity_id)
          REFERENCES wp_cma46_art_subactivity (subactivity_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
          $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql1);
    dbDelta($sql2);
    dbDelta($sql3);
    dbDelta($sql4);
    dbDelta($sql5);
    dbDelta($sql6);
    dbDelta($sql7);

    add_option('annuaire_artisans_db_version', $annuaire_artisans_db_version);
}
