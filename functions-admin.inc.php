<?php

/**
 * Administrative functions.
 */

// Require the other functions files.
require_once( 'functions-admin-events.inc.php' );
require_once( 'functions-admin-factoids.inc.php' );
require_once( 'functions-admin-general.inc.php' );
require_once( 'functions-admin-help.inc.php' );
require_once( 'functions-admin-pages.inc.php' );

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

      $build .= '<li>';

      // Add in a flag for default if it's the default choice.
      $default = '';
      if ( $row['defaultpage'] ) {
        $default = ' <span class="glyphicon glyphicon-star default" title="This is the default option." aria-hidden="true"></span>';
      }

      if ( $row['id'] == $CFG['page'] ) {
        $build .= '<strong>' . $row['title'] . '</strong> ' . $default . get_icon( 'tick', 'This option is active.' );

      } else {
        $build .= '<a class="hvr-sweep-to-right" href="' . $CFG['adminpage'] . '?action=page_change&page=' . $row['id'] . '">' . $row['title'] . '</a>' . $default;
      }

      $build .= "</li>\n";
    }

    $build .= "</ul>\n";
    return $build;

  }
}

// Checks if the $type id being updated actually exists in the pages table, and save it if so.
// DONE
function update_check( $type, $id ) {
  global $DB;

  // $type can be 'pages' or 'status' at the moment.
  if ( empty( $type ) ) {
    return 'unknown';
  }

  $type = $DB->real_escape_string( $type );

  $sql = "SELECT * FROM " . $type . " WHERE id = '" . $id . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {
    // $type may be 'pages' but the config option is singular.
    $type = ( $type == 'pages' ) ? 'page' : $type;
    return set_config( $type, $id );
  }

}

// Checks the specified table to see if the specified id is default or not.
function default_check( $type, $id ) {
  global $DB;

  // $type can be 'pages' or 'status' at the moment.
  if ( empty( $type ) ) {
    return false;
  }

  $type = $DB->real_escape_string( $type );

  // 'default' is a MySQL reserved keyword so we changed the column name in the db, but now we need to work to get it.
  $fieldname = ( $type == 'pages' ) ? 'defaultpage' : 'default' . $type;

  $sql = "SELECT " . $fieldname . " FROM " . $type . " WHERE id = '" . $id . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {

    $row = $res->fetch_assoc();
    if ( $row[$fieldname] ) {
      return true;

    } else {
      return false;
    }
  }

}


// Gets the default page's or status' id.
// DONE
function get_default( $type ) {
  global $DB;

  // $type can be 'pages' or 'status' at the moment.
  if ( empty( $type ) ) {
    return false;
  }

  $type = $DB->real_escape_string( $type );

  // 'default' is a MySQL reserved keyword so we changed the column name in the db, but now we need to work to get it.
  $fieldname = ( $type == 'pages' ) ? 'defaultpage' : 'default' . $type;

  $sql = "SELECT id FROM " . $type . " WHERE " . $fieldname . " = 1 LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {
    $row = $res->fetch_assoc();
    return $row['id'];
  }

}



/**
 * Status functions.
 */

// Makes the status change menu.
// DONE
function make_status_change_menu() {

  global $CFG, $DB;

  $sql = "SELECT * FROM status ORDER BY priority ASC, name ASC;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {

    $build = '<ul>';
    while ( $row = $res->fetch_assoc() ) {

      $build .= '<li>';

      // Add in a flag for default if it's the default choice.
      $default = '';
      if ( $row['defaultstatus'] ) {
        $default = ' <span class="glyphicon glyphicon-star default" title="This is the default option." aria-hidden="true"></span>' ;
      }

      if ( $row['id'] == $CFG['status'] ) {
        $build .= '<strong>' . $row['title'] . '</strong> ' . $default . get_icon( 'tick', 'This option is active.' );

      } else {
        $build .= '<a class="hvr-sweep-to-right" href="' . $CFG['adminpage'] . '?action=status_change&status=' . $row['id'] . '">' . $row['title'] . '</a>' . $default;
      }

      $build .= "</li>\n";
    }

    $build .= "</ul>\n";

    return $build;

  }

}

// A function to check if the status id being set actually exists in the status table.
// DONE
/*function set_status( $id ) {
  global $DB;

  $sql = "SELECT * FROM status WHERE id = '" . $id . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {
    return set_config( 'status', $id );
  }

}*/


// Gets a list of figures with 'aaa', 'fig' or 'special' in the name.
// TODO: This is better than it was, but it needs fairly serious refactoring for reasons of sanity.
function get_figures_thumbnails() {

  global $CFG;

  // Scan the folder for appropriate images; store filenames and make a user-friendly name also.
  $figures = array(
    'filename'  => array(),
    'name'      => array()
  );

  if ( $fh = opendir( $CFG['dir']['ppl'] ) ) {
    while ( false !== ( $entry = readdir( $fh ) ) ) {
      if ( $entry != '.' && $entry != '..' && ( substr( $entry, 0, 3 ) == 'aaa' || substr( $entry, 0, 3 ) == 'fig' || substr( $entry, 0, 7 ) == 'special' ) ) {
        $figures['filename'][] = $entry;
      }
    }
    closedir( $fh );
  }

  if ( $figures ) {

    sort( $figures['filename'] );

    for( $j = 0; $j < count( $figures['filename'] ); $j++ ) {
      $tmp = $figures['filename'][$j];
      $tmp = ucfirst( str_replace( array( 'aaa-', 'fig-', 'special-', '.jpg', '.jpeg', '.png' ), '', $tmp ) );
      $tmp = ucfirst( str_replace( array( '-alt' ), ' 2', $tmp ) );
      $figures['name'][$j] = $tmp;
    }

    $build = '';
    for( $j = 0; $j < count( $figures['filename'] ); $j++ ) {

      $build .= '      <div class="col-xs-4 col-md-2">' . "\n";
      $build .= '        <div class="thumbnail">' . "\n";
      //$build .= '          <img data-src="holder.js/100x100">' . "\n";
      $build .= '          <div class="caption">' . "\n";
      $build .= '            <h4 class="text-center">' . $figures['name'][$j] . '</h4>' . "\n";
      $build .= '          </div>' . "\n";
      $build .= '          <img class="img-thumbnail" src="' . $CFG['dir']['ppl'] . $figures['filename'][$j] . '" >' . "\n";
      $build .= '          <div class="caption">' . "\n";

      if ( $figures['filename'][$j] == get_config( 'specific_fig' ) ) {
        $build .= '            <p><a class="btn btn-success btn-block" disabled="disabled" role="button">Chosen!</a></p>' . "\n";
      } else {
        $build .= '            <p><a href="' . $CFG['adminpage'] . '?action=figure_change&figure_filename=' . $figures['filename'][$j] . '&figure_name=' . $figures['name'][$j] . '" class="btn btn-info btn-block" role="button">Select ' . $figures['name'][$j] . '</a></p>' . "\n";
      }

      $build .= '          </div>' . "\n";
      $build .= '        </div>' . "\n";
      $build .= '      </div>' . "\n";

    }

    return $build;

  } else {
    adminlog( 'img|err|' . $CFG['dir']['ppl'] );
    return '<div class="col-md-12"><p class="error">Couldn\'t find any images.</p></div>';
  }

}


// Edits a stat - but this has been deprecated for a while.
// DONE
function edit_stat( $key, $value ) {

    global $DB;

    $value = $DB->real_escape_string( $value );

    adminlog( 'edit_stat|' . $key . '|' . $value );

    $sql = "UPDATE stats SET value = '" . $value . "' WHERE id = '" . $key . "';";
    $res = $DB->query( $sql );

    return $res;
}

// Get last 'n' log entries.
// DONE
function get_last_log( $no = 10) {

    global $DB;

    $sql = "SELECT * FROM log ORDER BY id DESC LIMIT " . $no . ";";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        $build = '<p>Sorry, no logs.</p>';

    } else {
        $build = "<ul>\n";
        while ( $row = $res->fetch_assoc() ) {
            $build .= '<li>' . $row['date'] . ': ' . $row['data'] . "</li>\n";
        }
        $build .= "</ul>\n";

    }

    return $build;
}

// Checks to see if this is the default page (on the page menu), and if not, a little warning.
// TODO: We're making an assumption that there is a page called 'default'!
function default_page_warning_page() {
  global $CFG;

  $out = '';
  if ( !default_check( 'pages', $CFG['page'] ) ) {
    $out .= '<div class="alert alert-info" role="alert">' . "\n";
    $out .= '  <strong>Info:</strong> The default page <span class="glyphicon glyphicon-star default" title="This star indicates the default option." aria-hidden="true"></span> is not set for some reason, which may be intentional. <a href="' . $CFG['adminpage'] . '?action=page_change&page=' . get_default( 'pages' ) . '" class="alert-link">Click here to reset the page to default</a>.' . "\n";
    $out .=  "</div>\n";
  }

  return $out;
}

// Checks to see if this is the default page (on the status menu), and if not, a little warning.
// TODO: We're making an assumption that there is a page called 'default'!
function default_page_warning_status() {
  global $CFG;

  $out = '';
  if ( !default_check( 'pages', $CFG['page'] ) ) {
    $out .= '<div class="alert alert-warning" role="alert">' . "\n";
    $out .= '  <strong>Note!</strong> These status options are only shown on the default page <span class="glyphicon glyphicon-star default" title="This star indicates the default option." aria-hidden="true"></span> which is not currently set.' . "\n";
    $out .= "</div>\n";
  }

  return $out;
}

// Checks to see if this is the default page (on the status menu), and if not, a little warning.
function default_status_warning() {
  global $CFG;

  $out = '';
  if ( !default_check( 'status', $CFG['status'] ) ) {
    $out .= '<div class="alert alert-info" role="alert">' . "\n";
    $out .= '  <strong>Info:</strong> The default status <span class="glyphicon glyphicon-star default" title="This star indicates the default option." aria-hidden="true"></span> is not set for some reason, which may be intentional. <a href="' . $CFG['adminpage'] . '?action=status_change&status=' . get_default( 'status' ) . '" class="alert-link">Click here to reset the status to default</a>.' . "\n";
    $out .= "</div>\n";
  }

  return $out;
}

// Checks to see if this is the default page (on the status menu), and if not, a little warning.
function showstopper_page_warning() {
  global $CFG;

  $out = '';
  if ( get_name( 'pages', $CFG['page'] ) != 'showstopper' ) {
    $out .= '<div class="alert alert-warning" role="alert">' . "\n";
    $out .= '  <strong>Note!</strong> This text is only shown on the <strong>Showstopper</strong> page, which is not currently set. <a href="' . $CFG['adminpage'] . '?action=page_change&page=' . get_id( 'pages', 'showstopper' ) . '" class="alert-link">Click here to turn on the Showstopper page</a>, first making sure that the below text is correct and saved.' . "\n";
    $out .= "</div>\n";
  } else {
    $out .= '<div class="alert alert-info" role="alert">' . "\n";
    $out .= '  <strong>Info:</strong> The <strong>Showstopper</strong> page is active, and the below text is live. <a href="' . $CFG['adminpage'] . '?action=page_change&page=' . get_default( 'pages' ) . '" class="alert-link">Click here to turn the Showstopper off</a> and replace with the default <span class="glyphicon glyphicon-star default" title="This star indicates the default option." aria-hidden="true"></span> page.' . "\n";
    $out .= "</div>\n";
  }

  return $out;
}

// Checks to see if there are any un-hidden factoids, and shows a warning if not.
function no_unhidden_factoids_warning() {
  $out = '';
  if ( count_rows( 'factoids', 'hidden = 0' ) == 0 ) {
    $out .= '<div class="alert alert-danger" role="alert">' . "\n";
    $out .= '  <strong>Warning!</strong> There are no un-hidden factoids. You should show at least one.' . "\n";
    $out .= "</div>\n";
  }

  return $out;
}

// Checks to see if there are any un-hidden factoids, and shows a warning if not.
function no_unhidden_events_warning() {
  $out = '';
  if ( count_rows( 'events', 'hidden = 0' ) == 0 ) {
    $out .= '<div class="alert alert-danger" role="alert">' . "\n";
    $out .= '  <strong>Warning!</strong> There are no un-hidden events. You should show at least one.' . "\n";
    $out .= "</div>\n";
  }

  return $out;
}

function logout() {
  $_SESSION = array();

  if ( ini_get( 'session.use_cookies' ) ) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
  }
  session_destroy();
  header( 'location: login.php?logout' );
  exit(0);
}

function force_default() {
  global $CFG;

  $out = 'Full reset performed: <ul>';
  foreach ( $CFG['defaults'] as $setting => $value ) {
    if ( set_config( $setting, $value ) ) {
      $out .= '<li>Set <strong>' . $setting . '</strong> to <strong>' . $value . '</strong>.</li>';
    } else {
      $out .= '<li>Could not set <strong>' . $setting . '</strong> to <strong>' . $value . '</strong> for some reason.</li>';
    }
  }
  $out .= '</ul>';

  return $out;
}

// Set the config settings 'changes' to yes.
function set_change() {
  $_SESSION['alerts'][] = array( 'info' => 'The main page will refresh in a moment.' );
  return set_config( 'changes', 'yes' );
}

function help_modals() {

  global $DB;

  $sql = "SELECT id, name, title, content FROM help ORDER BY title ASC;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;
  } else {

    while ( $row = $res->fetch_assoc() ) {

?>
  <!-- <?php echo $row['title']; ?> modal. -->
  <div class="modal fade" id="<?php echo $row['name']; ?>-modal" tabindex="-1" role="dialog" aria-labelledby="<?php echo $row['name']; ?>-modallabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="<?php echo $row['name']; ?>-modallabel"><?php echo $row['title']; ?></h4>
        </div>
        <div class="modal-body">
          <?php echo $row['content']; ?>
        </div>
        <div class="modal-footer">
          <a class="btn btn-link btn-sm pull-left" href="help.php?action=help_edit&help_id=<?php echo $row['id']; ?>" role="button">Edit</a>
          <button type="button" class="btn btn-success" data-dismiss="modal">Got it!</button>
        </div>
      </div>
    </div>
  </div>
<?php




    }
  }
}

// Generates the icons used around the admin interface.
function get_icon( $type = 'tick', $title = '' ) {
  global $CFG;

  if ( empty( $title ) ) {
    $title = $type;
  }

  $out = ' <span class="glyphicon glyphicon-';

  if ( strtolower( $type ) == 'tick' ) {
    $out .= 'ok tick';
  } else if ( strtolower( $type ) == 'cross' ) {
    $out .= 'remove cross';
  } else if ( strtolower( $type ) == 'edit' ) {
    $out .= 'pencil edit';
  } else if ( strtolower( $type ) == 'show' ) {
    $out .= 'eye-open factoid-show';
  } else if ( strtolower( $type ) == 'hide' ) {
    $out .= 'eye-close factoid-hide';
  }

  $out .= '" title="' . $title . '" aria-hidden="true"></span> ';

  return $out;
}

// Truncates the log table. Because it gets big.
// Might be good to remove all but the most recent 24 hours, 7 days, 1 month etc.
function truncate_log() {

  global $DB;

  $res = $DB->query( "TRUNCATE TABLE log;" );

  if ( $res ) {
    return true;
  } else {
    return false;
  }

}

// Counts the number of lines in the specified table.
// TODO: Add option to provide WHERE clause snippet.
function count_rows( $table = 'log', $where = '' ) {

  global $DB;

  $table = $DB->real_escape_string( $table );

  $sql = "SELECT COUNT(*) AS rowcount FROM " . $table;
  if ( !empty( $where ) ) {
    $sql .= " WHERE " . $where;
  }
  $sql .= " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0 ) {
    return '0';
  }

  $row = $res->fetch_assoc();
  return $row['rowcount'];

}
