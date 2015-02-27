<?php

/**
 * 'Refresh' web service to return the 'changes' config setting. If it is yes, change it back to no.
 */

require_once( 'functions.inc.php' );

$changes = get_config( 'changes' );

if ( $changes == 'yes' ) {
  set_config( 'changes', 'no' );
}

echo $changes;
