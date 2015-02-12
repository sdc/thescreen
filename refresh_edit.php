<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['seconds'] ) && !empty( $_GET['seconds'] ) && is_numeric( $_GET['seconds'] ) ) {

    if ( set_config( 'refresh', $_GET['seconds'] ) ) {
        header( 'location: manage.php?msg=refresh_edit_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=refresh_edit_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=refresh_edit_fail' );
    exit(0);
}
