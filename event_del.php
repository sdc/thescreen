<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['eid'] ) && !empty( $_GET['eid'] ) ) {
    
    if ( del_event( $_GET['eid'] ) ) {
        header( 'location: manage.php?msg=event_del_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=event_del_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=event_del_fail' );
    exit(0);
}
