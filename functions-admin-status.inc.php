<?php

/**
 * Status functions.
 */

// Makes the status change menu.
function make_status_change_menu() {

  global $CFG, $DB;

  // If we're going to order by 'severity', best we know what we're dealing with.
  $sql = "SELECT * FROM status_types ORDER BY id ASC;";
  $res2 = $DB->query( $sql );

  if ( !$res2 || $res2->num_rows == 0 ) {
    return false;
  }

  $build = '';

  while ( $row2 = $res2->fetch_assoc() ) {

    $sql = "SELECT * FROM status WHERE type = '" . $row2['name'] . "' ORDER BY priority ASC, title ASC;";
    $res = $DB->query( $sql );

    if ( !$res || $res->num_rows == 0 ) {
      continue;

    } else {

      //$build .= '<p class="status-type-title">' . ucfirst( $row2['type'] ) . ' statuses:</p>'."\n";
      $build .= '<ul><li>' . $row2['title'] . ' statuses:'."\n";

      $build .= '<ul>';
      while ( $row = $res->fetch_assoc() ) {

        $description = str_replace( array( '<br>', '<br />' ), ' ', $row['description'] );

        $build .= '<li>';

        // Add in a flag for default if it's the default choice.
        $default = '';
        if ( $row['defaultstatus'] ) {
          $default = ' <span class="glyphicon glyphicon-star default" title="This is the default option." aria-hidden="true"></span>' ;
        }

        if ( $row['id'] == $CFG['status'] ) {
          $build .= '<strong><span title="' . $description . '">' . $row['title'] . '</span></strong> ' . $default . get_icon( 'tick', 'This option is active.' );

        } else {
          $build .= '<a class="hvr-sweep-to-right" href="' . $CFG['adminpage'] . '?action=status_change&status=' . $row['id'] . '" title="' . $description . '">' . $row['title'] . '</a>' . $default;
        }

        $build .= "</li>\n";
      }

      $build .= "</ul>\n";

      $build .= "</li></ul>\n";
    }

  }

  return $build;


}
