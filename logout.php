<?php

/**
 * Utterly destroys the whole darned session and redirects back to the login screen.
 */

session_name( 'sdc-thescreen' );
session_start();

$_SESSION = array();

if ( ini_get( 'session.use_cookies' ) ) {

    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

header( 'location: login.php' );

exit(0);
