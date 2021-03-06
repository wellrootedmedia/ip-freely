<?php
/*
 * Plugin Name: IP Freely
 * Plugin URI: http://wellrootedmedia.com/plugins/ip-freely
 * Description: This is to block IP's
 * Version: 0.0.4
 * Author: Well Rooted Media
 * Author URI: http://wellrootedmedia.com
 * License: GPL2
 */

/*
 * Add install function
 */
global $ip_freely_version;
$ip_freely_version = "0.0.4";

register_activation_hook( __FILE__, 'ip_freely_install' );

function ip_freely_install() {

    global $wpdb;
    global $ip_freely_version;

    $table_name = $wpdb->prefix . "ip_freely";

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        ipAddrFwrd varchar(45) DEFAULT NULL,
        ipAddr varchar(45) DEFAULT NULL,
        firstName varchar(255) DEFAULT NULL,
        lastName varchar(255) DEFAULT NULL,
        userName varchar(255) DEFAULT NULL,
        emailAddr varchar(255) DEFAULT NULL,
        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        UNIQUE KEY id (id)
    );";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( "ip_freely_install", $ip_freely_version );

}

function ip_freely_update_db_check() {

    global $ip_freely_version;
    if (get_site_option( 'ip_freely_install' ) != $ip_freely_version) {
        ip_freely_install();
    }

} add_action( 'plugins_loaded', 'ip_freely_update_db_check' );

/*
 * put the include for the action-add-registration.php file
 */
foreach ( glob( plugin_dir_path( __FILE__ ) . "inc/*.php" ) as $file )
    include_once $file;

foreach ( glob( plugin_dir_path( __FILE__ ) . "Classes/*.php" ) as $file )
    include_once $file;