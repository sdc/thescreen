<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['status'] ) && !empty( $_GET['status'] ) ) {

    if ( set_config( 'status', $_GET['status'] ) ) {
        header( 'location: manage.php?msg=status_edit_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=status_edit_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=status_edit_fail' );
    exit(0);
}
