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

      $build .= '<li>';

      // Add in a flag for default if it's the default choice.
      $default = '';
      if ( $row['defaultpage'] ) {
        $default = ' <span class="glyphicon glyphicon-star default" title="This is the default option." aria-hidden="true"></span>';
      }

      if ( $row['id'] == $CFG['page'] ) {
        $build .= '<strong>' . $row['title'] . '</strong> ' . $default . get_icon( 'tick', 'This option is active.' );

      } else {
        $build .= '<a href="' . $CFG['adminpage'] . '?action=page_change&page=' . $row['id'] . '">' . $row['title'] . '</a>' . $default;
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
        $build .= '<a href="' . $CFG['adminpage'] . '?action=status_change&status=' . $row['id'] . '">' . $row['title'] . '</a>' . $default;
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



// Gets a list of figures with 'fig' or 'special' in the name.
// DONE
/*function get_special_figure_list() {

    global $CFG;

    // Scan the folder for appropriate images.
    $figures = array();

    if ( $fh = opendir( $CFG['dir']['ppl'] ) ) {
        while ( false !== ( $entry = readdir( $fh ) ) ) {
            if ( $entry != '.' && $entry != '..' && ( substr( $entry, 0, 3 ) == 'fig' || substr( $entry, 0, 7 ) == 'special' ) ) {
                $figures[] = $entry;
            }
        }
        closedir( $fh );
    }

    if ( $figures ) {

        sort( $figures );
        $figures = array( 'no' ) + $figures;

        for( $j = 0; $j < count( $figures ); $j++ ) {
            echo '<option value="' . $figures[$j] . '"';
            if ( $figures[ $j ] == get_config( 'specific_fig' ) ) {
                echo ' selected="selected"';
            }
            echo '>' . $figures[$j] . '</option>' . "\n";
        }

    } else {
        adminlog( 'img|err|' . $file_loc );
        return '<p class="error">Couldn\'t find any images.</p>' . "\n";
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




/**
 * File Operation functions
 */
/*
function get_data_from_file( $file, $type = '' ) {

    global $CFG;

    // Get the file or get it with $CFG['ext'] on the end.
    if ( file_exists( $CFG['dir']['data'] . $file ) ) {
        $fh = fopen( $CFG['dir']['data'] . $file, 'r' );

    } else if ( file_exists( $CFG['dir']['data'] . $file . $CFG['ext'] )) {
        $fh = fopen( $CFG['dir']['data'] . $file . $CFG['ext'], 'r' );

    } else {
        return '<span class="err">No data: error opening ' . $file . '[' . $CFG['ext'] . ']</span>';
    }

    // Get the data out of the file: reads to EoL or EoF, whichever is first.
    $data = trim( fgets( $fh ) );
    fclose( $fh );

    if ( $type == '' ) {
        // normal data
        if ( is_numeric( $data ) ) {
            // format the number to add thousands separators
            $data = number_format( $data );
        }

    } else if ( $type = 'status' ) {
        // print a corresponding face
        $data = '<img src="' . $CFG['dir']['img'] . $data . '.png" width="16" height="16" alt="' . $type . ' ' . $data . '">';
    }

    return $data;
}
*/



// Get 'n' next events.
// DONE
// TODO: Probably best to split this between 'viewing' and 'editing' screens.
function make_events_menu( $num = 10 ) {

    global $CFG, $DB;

    $now = time();
    $today = date( 'Y', $now ) . '-' . date( 'm', $now ) . '-' . date( 'd', $now );

    $sql = "SELECT id, start, text, hidden FROM events WHERE start >= '" . $today . "' ORDER BY start ASC, id ASC LIMIT " . $num . ";";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0) {
        return '<p class="error">Sorry, no events.</p>';

    } else {

        $build = "<ul>\n";

        while ( $row = $res->fetch_assoc() ) {
            $db_date = $row['start'];
            $disp_date = date( 'j\<\s\u\p\>S\<\/\s\u\p\> M', mktime( 0, 0, 0, substr($db_date, 5, 2), substr($db_date, 8, 2), substr($db_date, 0, 4) ));

            // Extra styling for hidden events
            if ( $row['hidden'] == 0 ) {
                $build .= '<li>' . $disp_date . ': ' . $row['text'] . ' <a href="' . $CFG['adminpage'] . '?action=event_hide&event_id=' . $row['id'] . '"><span class="glyphicon glyphicon-eye-close event-hide" aria-hidden="true"></span></a>';
            } else {
                $build .= '<li class="text-muted"><del>' . $disp_date . ': ' . $row['text'] . '</del> <a href="' . $CFG['adminpage'] . '?action=event_show&event_id=' . $row['id'] . '"><span class="glyphicon glyphicon-eye-open event-show" aria-hidden="true"></span></a>';
            }

            // Editing button.
            $build .= ' <a href="event.php?action=event_edit&event_id=' . $row['id'] . '">' . get_icon( 'edit', 'Edit this event' ) . '</a>';

            // Delete button.
            $build .= ' <a href="' . $CFG['adminpage'] . '?action=event_del&event_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete the event \\\'' . $row['text'] . '\\\' ?\');">' . get_icon( 'cross', 'Delete this event' ) . '</a>';

            $build .= "</li>\n";
        }

        $build .= "</ul>\n";
        return $build;
    }

}


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


// Adds an event.
// DONE
// TODO: Check that this event id exists before we attempt to update it.
function edit_event( $date, $text, $id ) {

    global $DB;

    $text = $DB->real_escape_string( $text );

    adminlog( 'edit_event|' . $id );

    $sql = "UPDATE events SET start = '" . $date . "', text = '" . $text . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}

// Hides an event.
// DONE
function hide_event( $id ) {

    global $DB;

    adminlog( 'del_event|' . $id );

    $sql = "UPDATE events SET hidden = 1, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}


// Shows a hidden event.
// TODO: Check the event exists before restoring.
function show_event( $id ) {

    global $DB;

    adminlog( 'show_event|' . $id );

    $sql = "UPDATE events SET hidden = 0, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}

// Deletes an event completely.
function delete_event( $id ) {

    global $DB;

    adminlog( 'delete_event|' . $id );

    $sql = "DELETE FROM events WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}


// Make the form for adding in statistics.
// DONE
/*function get_stats_form() {

    global $DB;

    $sql = "SELECT id, text, value, readonly FROM stats ORDER BY id ASC;";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        return '<p class="error">Sorry, no events.</p>';

    } else {
        $build = '';
        while ( $row = $res->fetch_assoc() ) {
            $build .= '<form action="stats_edit.php" method="get">' . "\n";
            $build .= '    <tr>' . "\n";
            $build .= '        <td>' . $row['text'] . "</td>\n";
            $build .= '        <td class="thin"> ' . "\n";
            $build .= '            <input type="text" value="' . $row['value'] . '" name="value" size="5" maxlength="7" ';

            if ( $row['readonly'] == 1 ) {
                $build .= ' disabled="disabled"';
            }

            $build .= '></td>' . "\n";
            //$build .= '        <td>' . $row['text_after'] . "</td>\n";
            $build .= '        <td><input type="hidden" name="key" value="' . $row['id'] . '">' . "\n";
            $build .= '            <button type="submit"';

            if ( $row['readonly'] == 1 ) {
                $build .= ' disabled="disabled"';
            }

            $build .= '>Set</button>' . "\n";
            $build .= '        </td>' . "\n";
            $build .= '    </tr>' . "\n";
            $build .= '</form>' . "\n";
        }

        return $build;
    }
}*/


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
  header( 'location: login.php' );
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
?>
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Modal title</h4>
        </div>
        <div class="modal-body">
          <p>Stuff! and Things!</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" data-dismiss="modal">Got it!</button>
        </div>
      </div>
    </div>
  </div>
<?php
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
  }

  $out .= '" title="' . $title . '" aria-hidden="true"></span> ';

  return $out;
}

/**
 * Some well deprecated stuff below this point.
 */

// Get Moodle database statistics.
/*
function update_moodle_stats() {
    global $DB;

    $mdl_lnk = mysql_connect('host', 'user', 'pass');
    if (!$mdl_lnk) {
        return false;
    } else {
        if (!mysql_select_db('database', $mdl_lnk)) {
            return false;
        } else {
            // get the number of live users
            $res = mysql_query("SELECT COUNT(id) as id FROM mdl_user WHERE deleted = 0;", $mdl_lnk);
            $row = mysql_fetch_assoc($res);
            // insert into stats
            if ($row['id'] > 0 ) {
                $ins = $row['id'];
            } else {
                $ins = 999999;
            }
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_usr';", $DB);

            // unset all prev. used vars
            unset($res, $row, $res2, $ins);
            // get the number of live courses
            $res = mysql_query("SELECT COUNT(id) as id FROM mdl_course WHERE visible = 1;", $mdl_lnk);
            $row = mysql_fetch_assoc($res);
            // insert into stats
            if ($row['id'] > 0 ) {
                $ins = $row['id'];
            } else {
                $ins = 999999;
            }
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_crs';", $DB);

            // unset all prev. used vars
            unset($res, $row, $res2, $ins);
            // get the number of logins today
            $res = mysql_query("SELECT COUNT(id) as id FROM mdl_log WHERE time > '".mktime(0, 0, 0, date('m') , date('d'), date('Y'))."' AND action = 'login';", $mdl_lnk);
            $row = mysql_fetch_assoc($res);
            // insert into stats
            if ($row['id'] > 0 ) {
                $ins = $row['id'];
            } else {
                $ins = 999999;
            }
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_usr_td';", $DB);

            // unset all prev. used vars
            unset($res, $row, $res2, $ins);
            // get the number of logins today
            $res = mysql_query("SELECT COUNT(id) as id FROM mdl_log WHERE time > '".mktime(0, 0, 0, date('m') , date('d'), date('Y'))."';", $mdl_lnk);
            $row = mysql_fetch_assoc($res);
            // insert into stats
            if ($row['id'] > 0 ) {
                $ins = $row['id'];
            } else {
                $ins = 999999;
            }
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_hit';", $DB);
        }
    }
}
*/

// Gets stats from Joomla!'s db, for the public website and such.
/*
function update_joomla_stats() {
    global $DB;

    $mdl_lnk = mysql_connect('host', 'user', 'pass');
    if (!$mdl_lnk) {
        return false;
    } else {
        if (!mysql_select_db('database', $mdl_lnk)) {
            return false;
        } else {
            // get the number of live users
            $res = mysql_query("SELECT SUM(hits) as hits FROM jos_content;", $mdl_lnk);
            $row = mysql_fetch_assoc($res);
            // insert into stats
            if ($row['hits'] > 0 ) {
                $ins = $row['hits'];
            } else {
                $ins = 999999;
            }
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'web_views';", $DB);
        }
    }
}
*/

// Gets Google Analytics stats about the website.
/*
function update_website_stats() {
    global $DB;

    require 'gapi.class.php';

    $ga = new gapi(str_rot13(ga_email),str_rot13(ga_password));

    $filter = 'date == ' . date('Y') . date('m') . (date('d') -1 );
    $ga->requestReportData(ga_profile_id,array('day'),array('pageviews','visits'),'-visits',$filter);

    $pageviews  = $ga->getPageviews();
    $visits     = $ga->getVisits();

    // insert into stats
    if ( $pageviews > 0) {
        $res1 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$pageviews."' WHERE id = 'web_views';", $DB);
    }
    if ( $visits > 0) {
        $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$visits."' WHERE id = 'web_visit';", $DB);
    }
}
*/

// Gets Google Analytics stats about the staff pages.
/*
function update_online_apps() {
    global $DB;

    $cln_lnk = mysql_connect('hsot', 'user', 'pass');
    if (!$cln_lnk) {
        return false;
    } else {
        if (!mysql_select_db('database', $cln_lnk)) {
            return false;
        } else {
            // get the number of live users
            $res = mysql_query("SELECT COUNT(id) as id FROM app_forms WHERE created_on > '".get_config('apply_date')."';", $cln_lnk);
            $row = mysql_fetch_assoc($res);
            // insert into stats
            if ($row['id'] > 0 ) {
                $ins = $row['id'];
            } else {
                $ins = 999999;
            }
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'onl_app';", $DB);
        }
    }
}
*/

// Not sure where this function was going...
/*
function update_foursquare() {
    $img_str = 'http://foursquare.com/img/headerLogo.png';
    $build = '<img src="' . $img_str . '"><br>';
}
*/
