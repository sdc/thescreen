<?php

/**
 * The Screen: web services.
 */

// TODO: authentication token check?

require_once( 'functions.inc.php' );

if ( isset( $_GET['factoid'] ) ) {
  adminlog( 'webservice_request|factoid' );

  header( 'Access-Control-Allow-Origin: http://moodle.southdevon.ac.uk' );
  header( 'Access-Control-Allow-Origin: http://172.21.4.85' );              // Local testing.

  echo json_encode( array( get_random_factoid( false ) ) );
  exit(0);

} else if ( isset( $_GET['showstopper'] ) ) {
  adminlog( 'webservice_request|showstopper' );

  echo json_encode( array( get_config( 'showstopper' ) ) );
  exit(0);

} else {
  adminlog( 'webservice_request|unspecified' );

  echo json_encode( array( 'error' => 'Webservice not specified or specified incorrectly.', 'webservices' => array( 'factoid', 'showstopper' ) ) );
  exit(1);
}
