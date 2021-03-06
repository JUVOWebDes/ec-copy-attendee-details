<?php
/**
* Plugin Name: JUVO Events Calendar Attendee Details Copy
* Description:  This plugin copies the details of the first attendee to the checkout screen
* Version:      1.0.0
* Author: JUVO Webdesign
* Author URI: http://juvo-design.de/
*/

add_action( 'woocommerce_before_checkout_form', 'juvo_woocommerce_attendee_details_copy');
function juvo_woocommerce_attendee_details_copy() {
    
    //Access Session with name
    session_name('juvo-woo-ec-attendee-copy');
    session_start();
    
    //Check if session variable is available
    if(isset($_SESSION['juvo-woo-ec-attendee-copy'])) {

        //Create html buffer
        ob_start();

        ?>

        <script>
            var data = <?php echo $_SESSION['juvo-woo-ec-attendee-copy'] ?>;
            jQuery(document).ready(function(){
                WW
                /**
                 * 
                 * Change field names here
                 * jQuery( 'input[name*="woocommerce field name"' ).val(data["field name from attendee details"]);
                 * 
                 * To get the attendee details field names uncomment the "console.log(data);" line and use your browser debugging tools
                 * 
                 */

                jQuery( 'input[name*="first_name"]' ).val(data["vorname"]);
                jQuery( 'input[name*="last_name"]' ).val(data["name"]);
                jQuery( 'input[name*="company"]' ).val(data["firma"]);
                jQuery( 'input[name*="billing_address_1"]' ).val(data["strasse-und-hausnummer"]);
                jQuery( 'input[name*="postcode"]' ).val(data["postleitzahl"]);
                jQuery( 'input[name*="city"]' ).val(data["ort"]);
                jQuery( 'input[name*="phone"]' ).val(data["telefon"]);
                jQuery( 'input[name*="email"]' ).val(data["email"]);
                //console.log(data);
            });
        </script>

        <?php

        //Output buffer
        echo ob_get_contents();
        //Clear buffer
        ob_end_flush();
    }
}

//Check if Attende Information is submitted
if (isset($_POST['tribe_tickets_saving_attendees'])) {
    //Create Instance to get attendee details
    $obj = new AttendeeDetailsCopy;

    //Create Session with name
    session_name('juvo-woo-ec-attendee-copy');
    session_start();

    //encode data for easy javascript access
    $data = json_encode($obj->data);

    //save data persistent in session
    $_SESSION['juvo-woo-ec-attendee-copy'] = $data;
}

class AttendeeDetailsCopy {
    public $data;

    function __construct() {
        $this->getInformation();
    }

    private function getInformation() {
        //Get Information
        $attendeesInformations = $_POST['tribe-tickets-meta'];
        $data;

        //Iterate through variable, necessary due to array structure -.-
        $i=0;
        if (!empty($attendeesInformations)) {
            foreach ($attendeesInformations as $attendeesInformation) {
                //First Inner Elements is the current event
                if ($i == 0) {
                    //strore data of first attendee in first event
                    $this->data = $attendeesInformation[0];
                }
                $i++;
            }
        }
    }
}