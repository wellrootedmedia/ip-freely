<?php
// if uninstall/delete not called from WordPress then exit
if( ! defined('ABSPATH') && ! defined( 'WP_UNINSTALL_PLUGIN'))
    exit();

// delete option from options table
delete_option('ip_freely_install');

// remove any additional options and custom tables
global $wpdb;
$table_name = $wpdb->prefix . "ip_freely";

// build our query to delete our custom table
$sql = "DROP TABLE " . $table_name . ";";

// execute the query deleting the table
$wpdb->query($sql);

require_once(ABSPATH . "wp-admin/includes/upgrade.php");
dbDelta($sql);