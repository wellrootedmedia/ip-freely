<?php
add_action('bp_after_registration_confirmed', 'custom_bp_after_registration_confirmed');
function custom_bp_after_registration_confirmed() {
    ?>
    <script type="text/javascript">
        jQuery(function() {
            jQuery("#signup_form").submit(function(e) {
                var buttonId = e.originalEvent.explicitOriginalTarget.id;
                if (buttonId  == "signup_submit") {
                    var ok = confirm('Do you really want to save your data?');
                    if (ok) {
                        return true;
                    }
                    else {
                        e.preventDefault();
                        <?php
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
                        ?>
                        return false;
                    }
                }
            });
        });
    </script>
    <?php
}