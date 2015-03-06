<?php

/**
 * Functions relating to events.
 */

// Shows all events.
// TODO: Showing all events is not relevant for dates which have passed. Refactor this.
function event_show_all() {

  adminlog( 'event_show_all|' );

  global $DB;

  $sql = "UPDATE `events` SET `hidden` = '0', `modified` = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Hides all events.
// TODO: Hiding all events is not relevant for dates which have passed. Refactor this.
function event_hide_all() {

  adminlog( 'event_hide_all|' );

  global $DB;

  $sql = "UPDATE `events` SET `hidden` = '1', `modified` = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}
