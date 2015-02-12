<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['figure'] ) && !empty( $_GET['figure'] ) ) {

    if ( set_config( 'specific_fig', $_GET['figure'] ) ) {
        header( 'location: manage.php?msg=figure_edit_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=figure_edit_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=figure_edit_fail' );
    exit(0);
}
