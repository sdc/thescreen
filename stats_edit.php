<?php

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

if ( isset( $_GET['key'] ) && !empty ( $_GET['key'] ) && isset( $_GET['value'] ) && !empty ( $_GET['value'] ) && is_numeric( $_GET['value'] ) ) {

    if ( edit_stat( $_GET['key'], $_GET['value'] ) ) {
        header( 'location: manage.php?msg=stat_edit_success' );
        exit(0);
    } else {
        header( 'location: manage.php?msg=stat_edit_fail' );
        exit(0);
    }

} else {
    header( 'location: manage.php?msg=stat_edit_fail' );
    exit(0);
}
