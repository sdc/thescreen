<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['date'] ) && !empty( $_GET['date'] ) && isset( $_GET['text'] ) && !empty( $_GET['text'] ) ) {
    
    if ( add_event( $_GET['date'], $_GET['text'] ) ) {
        header( 'location: manage.php?msg=event_add_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=event_add_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=event_add_fail' );
    exit(0);
}
