<?php

/**
 * Functions relating to Factoids.
 */

// Builds the Factoids menu, for editing, hiding and deleting.
// TODO: A check that at least one factoid exists should probably be done at the top of the management page.
function make_factoids_menu( $sort = 'alpha') {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';
  $sql = "SELECT id, fact, hidden, created, modified FROM " . $table . " ORDER BY ";

  if ( $sort == 'alpha' ) {
    $sql .= "fact ASC;";
  } else {
    $sql .= "id ASC;";
  }
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0) {
    return '<p class="text-danger">Sorry, no Factoids.</p>';

  } else {

    $build = "<ol>\n";

    while ( $row = $res->fetch_assoc() ) {

      // TODO: Find a better way of presenting this information.
      //$title = 'Added: ' . date( $CFG['time']['title'], $row['created'] ) . '. Edited: ' . date( $CFG['time']['title'], $row['modified'] ) . '.';

      // Style regular events and hidden ones slightly differently.
      if ( $row['hidden'] == 0 ) {
        $build .= '  <li>' . $row['fact'] . ' <a href="' . $CFG['adminpage'] . '?action=factoid_hide&factoid_id=' . $row['id'] . '"> ' . get_icon( 'hide', 'Hide this factoid' ) . '</a>';
      } else {
        $build .= '  <li class="text-muted"><del>' . $row['fact'] . '</del> <a href="' . $CFG['adminpage'] . '?action=factoid_show&factoid_id=' . $row['id'] . '"> ' . get_icon( 'show', 'Show this factoid' ) . '</a>';
      }

      // Editing button.
      $build .= ' <a href="factoid.php?action=factoid_edit&factoid_id=' . $row['id'] . '">' . get_icon( 'edit', 'Edit this factoid' ) . '</a>';

      // Delete button.
      $build .= ' <a href="' . $CFG['adminpage'] . '?action=factoid_delete&factoid_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete the factoid \\\'' . $row['fact'] . '\\\' ?\');">' . get_icon( 'cross', 'Delete this factoid' ) . '</a>';

      $build .= "</li>\n";
    }

    $build .= "</ol>\n";
    return $build;

  }

}

// Shows (un-hides) a factoid.
function factoid_show( $id ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  adminlog( $table . '_show|' . $id );

  $sql = "SELECT * FROM " . $table . " WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0) {
    return false;
  }

  $sql = "UPDATE " . $table . " SET hidden = 0, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}

// Hides a factoid.
function factoid_hide( $id ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  adminlog( $table . '_hide|' . $id );

  $sql = "SELECT * FROM " . $table . " WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0) {
    return false;
  }

  $sql = "UPDATE " . $table . " SET hidden = 1, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}

// Shows all factoids.
function factoid_show_all() {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  adminlog( $table . '_show_all|' );

  $sql = "UPDATE " . $table . " SET hidden = 0, modified = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Hides all factoids.
function factoid_hide_all() {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  adminlog( $table . '_hide_all|' );

  $sql = "UPDATE " . $table . " SET hidden = 1, modified = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Adds a factoid.
// DONE
function add_factoid( $text ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  $text = $DB->real_escape_string( $text );

  adminlog( $table . '_add|' . $text );

  $sql = "INSERT INTO " . $table . " (fact, created, modified) VALUES ('" . $text . "', '" . time() . "', '" . time() . "');";
  $res = $DB->query( $sql );

  return $res;
}

// Edits an existing factoid.
// DONE
// TODO: Check that this factoid id exists before we attempt to update it.
function edit_factoid( $text, $id ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  $text = $DB->real_escape_string( $text );

  adminlog( $table . '_edit|' . $id );

  $sql = "UPDATE " . $table . " SET fact = '" . $text . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}

// Deletes a factoid completely.
function delete_factoid( $id ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';

  adminlog( $table . '_delete|' . $id );

  $sql = "DELETE FROM " . $table . " WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}
