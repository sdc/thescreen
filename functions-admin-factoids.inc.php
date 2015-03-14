<?php

/**
 * Functions relating to Factoids.
 */

// Builds the Factoids menu, for editing, hiding and deleting.
// TODO: A check that at least one factoid exists should probably be done at the top of the management page.
function make_factoids_menu() {

  global $CFG, $DB;

  $sql = "SELECT id, fact, hidden, created, modified FROM factoids ORDER BY fact ASC, id ASC;";
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

  adminlog( 'factoid_show|' . $id );

  global $DB;

  $sql = "SELECT * FROM factoids WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0) {
    return false;
  }

  $sql = "UPDATE factoids SET hidden = 0, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}

// Hides a factoid.
function factoid_hide( $id ) {

  adminlog( 'factoid_hide|' . $id );

  global $DB;

  $sql = "SELECT * FROM factoids WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0) {
    return false;
  }

  $sql = "UPDATE factoids SET hidden = 1, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
  $res = $DB->query( $sql );

  return $res;
}

// Shows all factoids.
function factoid_show_all() {

  adminlog( 'factoid_show_all|' );

  global $DB;

  $sql = "UPDATE factoids SET hidden = 0, modified = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Hides all factoids.
function factoid_hide_all() {

  adminlog( 'factoid_hide_all|' );

  global $DB;

  $sql = "UPDATE factoids SET hidden = 1, modified = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Adds a factoid.
// DONE
function add_factoid( $text ) {

    global $DB;

    $text = $DB->real_escape_string( $text );

    adminlog( 'add_factoid|' . $text );

    $sql = "INSERT INTO factoids (fact, created, modified) VALUES ('" . $text . "', '" . time() . "', '" . time() . "');";
    $res = $DB->query( $sql );

    return $res;
}

// Edits an existing factoid.
// DONE
// TODO: Check that this factoid id exists before we attempt to update it.
function edit_factoid( $text, $id ) {

    global $DB;

    $text = $DB->real_escape_string( $text );

    adminlog( 'edit_factoid|' . $id );

    $sql = "UPDATE factoids SET fact = '" . $text . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}

// Deletes a factoid completely.
function delete_factoid( $id ) {

    global $DB;

    adminlog( 'delete_factoid|' . $id );

    $sql = "DELETE FROM factoids WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}
