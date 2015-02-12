<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['rssfeed'] ) && !empty( $_GET['rssfeed'] ) ) {

    if ( set_config( 'rssfeed', $_GET['rssfeed'] ) ) {
        header( 'location: manage.php?msg=rssfeed_edit_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=rssfeed_edit_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=rssfeed_edit_fail' );
    exit(0);
}
