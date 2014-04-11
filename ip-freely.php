<?php
/*
 * Plugin Name: IP Freely
 * Plugin URI: http://wellrootedmedia.com/plugins/ip-freely
 * Description: This is to block IP's
 * Version: 0.0.0
 * Author: Well Rooted Media
 * Author URI: http://wellrootedmedia.com
 * License: GPL2
 */

/*
 * Add install function
 */
global $ip_freely_version;
$ip_freely_version = "0.0.4";

function ip_freely_install() {
    global $wpdb;
    global $ip_freely_version;

    $table_name = $wpdb->prefix . "ip_freely";

//    $installed_ver = get_option( "ip_freely_install" );
//    if( $installed_ver != $ip_freely_version ) {
//        $sql = "ALTER TABLE $table_name (
//        id mediumint(9) NOT NULL AUTO_INCREMENT,
//        time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
//        ipAddrFwrd varchar(45) DEFAULT NULL,
//        ipAddr varchar(45) DEFAULT NULL,
//        userName varchar(255) DEFAULT NULL,
//        emailAddr varchar(255) DEFAULT NULL,
//        UNIQUE KEY id (id)
//        );";
//        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
//        dbDelta( $sql );
//        update_option( "ip_freely_install", $ip_freely_version );
//    }

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
register_activation_hook( __FILE__, 'ip_freely_install' );

function ip_freely_update_db_check() {
    global $ip_freely_version;
    if (get_site_option( 'ip_freely_install' ) != $ip_freely_version) {
        ip_freely_install();
    }
}
add_action( 'plugins_loaded', 'ip_freely_update_db_check' );

function do_this() {

    global $wpdb;

    $userName = sanitize_text_field($_POST['signup_username']);
    $emailAddr = sanitize_text_field($_POST['signup_email']);
    $ipAddrForward = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $ipAddr = $_SERVER['REMOTE_ADDR'];

    if($userName) {
        $wpdb->insert(
            $wpdb->prefix . "ip_freely",
            array(
                'userName' => $userName,
                'emailAddr' => $emailAddr,
                'ipAddrFwrd' => $ipAddrForward,
                'ipAddr' => $ipAddr,
                'time' => date("Y-m-d H:i:s")
            )
        );
    }
}
add_action('bp_after_registration_confirmed', 'do_this');