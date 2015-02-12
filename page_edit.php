<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['page'] ) && !empty( $_GET['page'] ) ) {

    if ( set_config( 'page', $_GET['page'] ) ) {
        header( 'location: manage.php?msg=page_edit_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=page_edit_fail' );
        exit(0);
    }

} else {
    header('location: manage.php?msg=page_edit_fail');
    exit(0);
}
