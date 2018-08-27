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
        website_id SMALLINT NOT NULL AUTO_INCREMENT,
        website_code SMALLINT NOT NULL,
        website_type VARCHAR(25) NOT NULL,
        website_expert VARCHAR(50) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (website_id),
        INDEX ix_website_code (),
        UNIQUE INDEX website_code_UNIQUE (website_code ASC))
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8mb4";

    $sql2 = "CREATE TABLE IF NOT EXISTS $table_name2(
        id SMALLINT NOT NULL,
        name VARCHAR(50) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id))
        ENGINE = InnoDB
        AUTO_INCREMENT = 9
        DEFAULT CHARACTER SET = utf8mb4";

    $sql3 = "CREATE TABLE IF NOT EXISTS $table_name3 (
        id SMALLINT NOT NULL AUTO_INCREMENT,
        family_id SMALLINT NOT NULL,
        activity_id SMALLINT NOT NULL,
        name VARCHAR(255) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        INDEX ix_family (),
        UNIQUE INDEX family_id_UNIQUE (family_id ASC),
        INDEX ix_activity_id (),
        UNIQUE INDEX activity_id_UNIQUE (activity_id ASC),
        CONSTRAINT fk_activity_family
          FOREIGN KEY (family_id)
          REFERENCES wp_cma46_art_family (id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
      ENGINE = InnoDB
      DEFAULT CHARACTER SET = utf8mb4";

    $sql4 = "CREATE TABLE IF NOT EXISTS $table_name4 (
        id SMALLINT NOT NULL AUTO_INCREMENT,
        activity_id SMALLINT NOT NULL,
        name VARCHAR(50) NOT NULL,
        aprm VARCHAR(10) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE INDEX activity_id_UNIQUE (activity_id ASC),
        INDEX ix_activity_id (),
        CONSTRAINT fk_subactivity_activity
          FOREIGN KEY (activity_id)
          REFERENCES wp_cma46_art_activity (activity_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
        ENGINE = InnoDB
        AUTO_INCREMENT = 9
        DEFAULT CHARACTER SET = utf8mb4";

    $sql5 = "CREATE TABLE IF NOT EXISTS $table_name5 (
        district_id SMALLINT NOT NULL,
        district_name VARCHAR(50) NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (district_id))
        ENGINE = InnoDB
        AUTO_INCREMENT = 20
        DEFAULT CHARACTER SET = utf8mb4";

    $sql6 = "CREATE TABLE IF NOT EXISTS $table_name6(
        town_id SMALLINT NOT NULL AUTO_INCREMENT,
        town_name VARCHAR(75) NOT NULL,
        postal_code VARCHAR(10) NOT NULL,
        district_id SMALLINT NOT NULL,
        last_update TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (town_id),
        INDEX ix_district_id (district_id ASC),
        CONSTRAINT fk_town_district
          FOREIGN KEY (district_id)
          REFERENCES wp_cma46_art_district (district_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
        ENGINE = InnoDB
        AUTO_INCREMENT = 319
        DEFAULT CHARACTER SET = utf8mb4";
              

    $sql7 = "CREATE TABLE IF NOT EXISTS $table_name7(
        artisan_id INT NOT NULL AUTO_INCREMENT,
        rm_id SMALLINT NOT NULL,
        business_name VARCHAR(255) NOT NULL,
        address_1 VARCHAR(100) NOT NULL,
        address_2 VARCHAR(100) NULL DEFAULT NULL,
        town_id SMALLINT NOT NULL,
        telephone VARCHAR(20) NOT NULL,
        fax VARCHAR(20) NULL DEFAULT NULL,
        email VARCHAR(75) NULL DEFAULT NULL,
        website_code SMALLINT NOT NULL,
        activity_cma SMALLINT NOT NULL,
        last_update TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (artisan_id),
        INDEX ix_website_code (website_code ASC),
        INDEX ix_town_id (town_id ASC),
        INDEX ix_activity_cma (activity_cma),
        UNIQUE INDEX activity_cma_UNIQUE (activity_cma ASC),
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
          FOREIGN KEY (activity_cma)
          REFERENCES wp_cma46_art_subactivity (activity_id)
          ON DELETE NO ACTION
          ON UPDATE NO ACTION)
        ENGINE = InnoDB
        DEFAULT CHARACTER SET = utf8mb4,
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
