<?php

/**
 * Very simple code to return the 'changes' config setting.
 */

require_once( 'functions.inc.php' );

$changes = get_config( 'changes' );

if ( $changes == 1 ) {
  set_config( 'changes', 0 );
}

return $changes;
