<?php

/**
 * Functions relating to help.
 */

// Edits existing help text.
function edit_help( $title, $content, $id ) {

  global $DB;

  $sql = "SELECT * FROM help WHERE id = '" . $id . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;
  }   

  $title = $DB->real_escape_string( $title );
  $content = $DB->real_escape_string( $content );

  adminlog( 'edit_help|' . $id );

  $sql = "UPDATE help SET title = '" . $title . "', content = '" . $content . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}
