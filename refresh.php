<?php

/**
 * 'Refresh' web service to return the 'changes' config setting. If it is yes, change it back to no.
 *
 * TODO: The 'yes' or 'no' booleans may have to change in favour of a 'last updated' timestamp or Unix epoch setting. 
 *       This will enable more than one screen to talk back to the server and refresh if changes have been made.
 */

require_once( 'functions.inc.php' );

$changes = get_config( 'changes' );

if ( $changes == 'yes' ) {
  set_config( 'changes', 'no' );
}

echo $changes;
