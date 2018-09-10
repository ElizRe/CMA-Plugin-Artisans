<?php
/**
 * Annuaire_artisans Uninstall
 *
 * Uninstalling Annuaire_artisans deletes all options.
 *
 * @package annuaire_artisans
 * @since 1.0.0
 */

/** Check if we are uninstalling. */
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

//drop all custom tables in database

global $wpdb;

$table_name = $wpdb->prefix . "artisan";
$table_name1 = $wpdb->prefix . "art_website";
$table_name2 = $wpdb->prefix . "art_subactivity";
$table_name3 = $wpdb->prefix . "art_town";
$table_name4 = $wpdb->prefix . "art_district";
$table_name5 = $wpdb->prefix . "art_activity";
$table_name6 = $wpdb->prefix . "art_family";

$wpdb->query("SET FOREIGN_KEY_CHECKS = 0");

$wpdb->query("DROP TABLE `{$table_name}`");

$wpdb->query("DROP TABLE `{$table_name1}`");

$wpdb->query("DROP TABLE `{$table_name2}`");

$wpdb->query("DROP TABLE `{$table_name3}`");

$wpdb->query("DROP TABLE `{$table_name4}`");

$wpdb->query("DROP TABLE `{$table_name5}`");

$wpdb->query("DROP TABLE `{$table_name6}`");

$wpdb->query("SET FOREIGN_KEY_CHECKS = 1");

delete_option('annuaire_artisan_plugin');
