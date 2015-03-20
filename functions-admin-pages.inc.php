<?php

/**
 * Page functions.
 */

// Makes the page change menu.
// TODO: Check to see if $pages is valid before using it (same as make_status_change_menu()).
function make_page_change_menu() {

  global $CFG, $DB;

  $sql = "SELECT * FROM pages ORDER BY priority ASC, name ASC;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
      return false;

  } else {

    $build = '<ul>';
    while ( $row = $res->fetch_assoc() ) {

      $description = str_replace( array( '<br>', '<br />' ), ' ', $row['description'] );

      $build .= '<li>';

      // Add in a flag for default if it's the default choice.
      $default = '';
      if ( $row['defaultpage'] ) {
        $default = ' <span class="glyphicon glyphicon-star default" title="This is the default option." aria-hidden="true"></span>';
      }

      $scheduled = '';
      if ( $row['scheduled'] ) {
        $scheduled = ' <span class="glyphicon glyphicon-time scheduled" title="This is a scheduled page." aria-hidden="true"></span>';
      }

      if ( $row['id'] == $CFG['page'] ) {
        $build .= '<strong><span title="' . $description . '">' . $row['title'] . '</span></strong> ' . $default . $scheduled . get_icon( 'tick', 'This page is active.' );

      } else {
        $build .= '<a class="hvr-sweep-to-right" href="' . $CFG['adminpage'] . '?action=page_change&page=' . $row['id'] . '" title="' . $description . '">' . $row['title'] . '</a>' . $default . $scheduled;
      }

      // Editing button.
      $build .= ' <a href="page.php?action=page_edit&page_id=' . $row['id'] . '">' . get_icon( 'edit', 'Edit this page' ) . '</a>';

      // Delete button.
      $build .= ' <a href="' . $CFG['adminpage'] . '?action=page_del&page_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete the page \\\'' . $row['title'] . '\\\' ?\');">' . get_icon( 'cross', 'Delete this page' ) . '</a>';

      $build .= "</li>\n";
    }

    $build .= "</ul>\n";
    return $build;

  }
}

// Gets the page background (if set) and displays as a thumbnail.
function get_page_background_thumb() {
  global $CFG;

  $out = '          <div class="row">' . "\n";
  $out .= '            <div class="col-sm-10 col-sm-offset-1">' . "\n";

  if ( $img = get_page_background_image() ) {
    $out = '          <div class="row">' . "\n";
    $out .= '            <div class="col-sm-10 col-sm-offset-1">' . "\n";
    $out .= '              <p><img src="' . $CFG['dir']['bg'] . '/' . $img . '" alt="Current page in use" class="img-thumbnail"></p>' . "\n";

  } else {
    $out = '          <div class="row">' . "\n";
    $out .= '            <div class="col-sm-12">' . "\n";
    $out .= '              <div class="alert alert-info" role="alert">' . "\n";
    $out .= '                <strong>Info:</strong> This page doesn\'t appear to have a background image associated with it. This may or may not be a problem.' . "\n";
    $out .= "              </div>\n";
  }

  $out .= '            </div>' . "\n";
  $out .= '          </div>' . "\n";

  return $out;

}

/*
// Adds an event.
// DONE
function add_event( $date, $text ) {

    global $DB;

    $text = $DB->real_escape_string( $text );

    adminlog( 'add_event|' . $text );

    $sql = "INSERT INTO events (start, text, created, modified) VALUES ('" . $date . "', '" . $text . "', '" . time() . "', '" . time() . "');";
    $res = $DB->query( $sql );

    return $res;
}
*/

// Edits an existing page.
// TODO: Check that this page id exists before we attempt to update it.
function edit_page( $name, $title, $description, $id ) {

    global $DB;

    $name = $DB->real_escape_string( $name );
    $title = $DB->real_escape_string( $title );
    $description = $DB->real_escape_string( $description );

    adminlog( 'edit_page|' . $id );

    $sql = "UPDATE pages SET name = '" . $name . "', title = '" . $title . "', description = '" . $description . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}

// Deletes a page completely.
// TODO: Check that this page id exists before we attempt to delete it.
function delete_page( $id ) {

    global $DB;

    adminlog( 'delete_page|' . $id );

    $sql = "DELETE FROM pages WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}
