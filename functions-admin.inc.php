<?php

/**
 * Database functions (and variables) for the administrative side of things.
 */

// Get statuses as an array.
// DONE
function get_status_array() {

    global $DB;

    $sql = "SELECT name FROM status ORDER BY priority ASC;";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        return false;

    } else {
        $value = array();
        while ( $row = $res->fetch_assoc() ) {
            $value[] = $row['name'];
        }
        return $value;
    }
}

// Get a list of pages as an array.
// DONE
function get_page_array() {

    global $DB;

    $sql = "SELECT page FROM pages ORDER BY priority ASC;";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        return false;

    } else {
        $value = array();
        while ( $row = $res->fetch_assoc() ) {
            $value[] = $row['page'];
        }
        return $value;
    }
}


// Gets a list of figures with 'fig' or 'special' in the name.
// DONE
function get_special_figure_list() {

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


// Makes the page change menu.
// DONE
// TODO: Check to see if $pages is valid before using it (same as make_status_change_menu()).
function make_page_change_menu() {

    $pages = get_page_array();
    $curr_page = get_config( 'page' );

    $build = '<ul>';
    foreach ( $pages as $page ) {

        $build .= '<li>';

        if ( $page == $curr_page ) {
            $build .= '<strong>' . ucfirst($page) . '</strong> <span class="glyphicon glyphicon-ok tick" aria-hidden="true"></span>';

        } else {
            $build .= '<a href="page_edit.php?page=' . $page . '">' . ucfirst($page) . '</a>';
        }

        $build .= "</li>\n";
    }

    $build .= "</ul>\n";
    return $build;
}



// Makes the status change menu.
// DONE
function make_status_change_menu() {

    $statuses = get_status_array();

    if ( $statuses ) {
        $curr_status = get_config( 'status' );

        $build = '<ul>';
        foreach ($statuses as $status) {

            $build .= '<li>';

            if ($status == $curr_status) {
                $build .= '<strong>' . ucfirst($status) . '</strong> <span class="glyphicon glyphicon-ok tick" aria-hidden="true"></span>';
            } else {
                $build .= '<a href="status_edit.php?status=' . $status . '">' . ucfirst($status) . '</a>';
            }

            $build .= "</li>\n";
        }

        $build .= "</ul>\n";
        return $build;

    } else {
        return '<p class="error">Sorry, no statuses.</p>';
    }
}

// Get 'n' next events.
// DONE
// TODO: Probably best to split this between 'viewing' and 'editing' screens.
function make_events_menu( $num = 10 ) {

    global $DB;

    $now = time();
    $today = date( 'Y', $now ) . '-' . date( 'm', $now ) . '-' . date( 'd', $now );

    $sql = "SELECT id, start, text, deleted FROM events WHERE start >= '" . $today . "' ORDER BY start ASC, id ASC LIMIT " . $num . ";";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0) {
        return '<p class="error">Sorry, no events.</p>';

    } else {

        $build = "<ul>\n";

        while ( $row = $res->fetch_assoc() ) {
            $db_date = $row['start'];
            $disp_date = date( 'j\<\s\u\p\>S\<\/\s\u\p\> M', mktime( 0, 0, 0, substr($db_date, 5, 2), substr($db_date, 8, 2), substr($db_date, 0, 4) ));
            
            // Extra styling for deleted events
            if ( $row['deleted'] == 0 ) {
                $build .= '<li>' . $disp_date . ': ' . $row['text'];
            } else {
                $build .= '<li class="text-muted"><del>' . $disp_date . ': ' . $row['text'] . '</del>';
            }

            if ( $row['deleted'] == 0 ) {
                $build .= ' <a href="' . $_SERVER["PHP_SELF"] . '?action=event_del&event_id=' . $row['id'] . '" title="Delete"><span class="glyphicon glyphicon-remove cross" aria-hidden="true"></span></a>';
            } else {
                $build .= ' <a href="event_undel.php?eid=' . $row['id'] . '" title="Un-delete" ><span class="glyphicon glyphicon-ok tick" aria-hidden="true"></span></a>';
            }

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

// Deletes an event.
// DONE
function del_event( $eid ) {

    global $DB;

    adminlog( 'del_event|' . $eid );

    $sql = "UPDATE events SET deleted = 1 WHERE id = " . $eid . ";";
    $res = $DB->query( $sql );

    return $res;
}


// Make the form for adding in statistics.
// DONE
function get_stats_form() {

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
