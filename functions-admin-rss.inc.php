<?php

/**
 * RSS functions.
 */

// Makes the RSS feed preset menu.
function make_rss_preset_menu() {

  global $CFG, $DB;

  $sql = "SELECT * FROM rss ORDER BY priority ASC, title ASC;";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0 ) {
      return false;

  } else {

    $build = '<ul>';
    while ( $row = $res->fetch_assoc() ) {

      $build .= '<li>';

      // Add in a flag for default if it's the default choice.
      $default = '';
      if ( $row['defaultpage'] ) {
        $default = get_icon( 'default' , 'This star indicates the default option.' );
      }

      if ( get_config( 'rssfeed' ) == $row['url'] ) {
        $build .= '<strong><span title="' . $row['description'] . '">' . $row['title'] . '</span></strong> ' . $default . get_icon( 'check', 'This RSS feed URL is active.' );

      } else {
        $build .= '<a class="hvr-sweep-to-right" href="' . $CFG['adminpage'] . '?action=rss_preset&rss_id=' . $row['id'] . '" title="' . $row['description'] . '">' . $row['title'] . '</a>' . $default;
      }

      // Editing button.
      $build .= ' <a href="rss.php?action=rss_edit&rss_id=' . $row['id'] . '">' . get_icon( 'edit', 'Edit this RSS feed URL' ) . '</a>';

      // Delete button, omitted for the currently active page.
      if ( get_config( 'rssfeed' ) != $row['url'] ) {
        $build .= ' <a href="' . $CFG['adminpage'] . '?action=rss_del&rss_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete the RSS feed \\\'' . $row['title'] . '\\\' ?\');">' . get_icon( 'cross', 'Delete this page' ) . '</a>';
      }

      $build .= "</li>\n";
    }

    $build .= "</ul>\n";
    return $build;

  }
}

// Gets the title, description and URL of an RSS feed from the passed-in ID.
function get_rss_details_from_id( $id ) {
  global $DB;

  $sql = "SELECT title, description, url FROM rss WHERE id = '" . $id . "';";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0 ) {
    return false;

  } else {
    $row = $res->fetch_array();
    return $row;
  }

}

/*
// Adds a page.
// DONE
function add_page( $name, $title, $description, $background, $scheduled ) {

    global $DB;

    $name = to_slug( $name );

    $name         = $DB->real_escape_string( $name );
    $title        = $DB->real_escape_string( $title );
    $description  = $DB->real_escape_string( $description );
    $background   = $DB->real_escape_string( $background );

    adminlog( 'add_page|' . $name );

    $sql = "INSERT INTO pages (name, title, description, background, scheduled, created, modified) VALUES ('" . $name . "', '" . $title . "', '" . $description . "', '" . $background . "', '" . $scheduled . "', '" . time() . "', '" . time() . "');";
    $res = $DB->query( $sql );

    return $res;
}
*/

/*
// Edits an existing page.
// TODO: Check that this page id exists before we attempt to update it.
function edit_page( $name, $title, $description, $background, $scheduled, $id ) {

    global $DB;

    $name = to_slug( $name );

    $name         = $DB->real_escape_string( $name );
    $title        = $DB->real_escape_string( $title );
    $description  = $DB->real_escape_string( $description );
    $background   = $DB->real_escape_string( $background );

    adminlog( 'edit_page|' . $id );

    $sql = "UPDATE pages SET name = '" . $name . "', title = '" . $title . "', description = '" . $description . "', scheduled = '" . $description . "', background = '" . $background . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}
*/

// Deletes an RSS feed completely.
// TODO: Check that this RSS id exists before we attempt to delete it.
function delete_rss( $id ) {

    global $DB;

    // Prevent deletion of an active page.
    $rss = get_rss_details_from_id( $id );
    if ( get_config( 'rssfeed' ) == $rss['url'] ) {
      return false;
    }

    adminlog( 'delete_rss|' . $id );

    $sql = "DELETE FROM rss WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}
