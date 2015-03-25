<?php

/**
 * 'Refresh' web service to return the 'changes' config setting, which is a Unix epoch format time of the last update.
 */

require_once( 'functions.inc.php' );

echo get_config( 'changes' );
