<?php
class DenyIPAddress {

    private function selectIpAddress() {

        global $wpdb;

        $table = $wpdb->custom_table_name . $wpdb->prefix.'ip_freely';
        $users = $wpdb->users;
        $query = "
            SELECT ipf.ipAddr
            FROM $users users
            INNER JOIN $table ipf ON users.user_login = ipf.userName
            WHERE users.user_status = 2
        ";
        $results = $wpdb->get_results($query, OBJECT);

        return $results;

    }

    function denyIpAddress() {

        return $this->selectIpAddress();

    }

}

function classRefDenyIp() {
    $denyIp = new DenyIPAddress();
    $arr = $denyIp->denyIpAddress();

    if (in_array ($_SERVER['REMOTE_ADDR'], $arr)) {
        ?>
        <script type="text/javascript">
            document.location.href = '<?php echo $_SERVER['REMOTE_ADDR']; ?>';
        </script>
        <?php
    }

} add_action('bp_before_register_page', 'classRefDenyIp');