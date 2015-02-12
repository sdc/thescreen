<?php

/**
 * Database functions (and variables)
 */

// Configuration array and some settings.
$CFG = array();

$CFG['dir']['graphics'] = 'graphics/';
$CFG['dir']['ppl']      = $CFG['dir']['graphics'] . 'people/';
$CFG['dir']['status']   = $CFG['dir']['graphics'] . 'status/';
$CFG['dir']['bg']       = $CFG['dir']['graphics'] . 'backgrounds/';
//$CFG['dir']['data']     = './data/';  

$CFG['db']['time']      = date( "Y-m-d H:i:s", time()) ;

$CFG['ext']             = '.txt';   // text file extension for loading data from files

// Include config.inc.php
if ( !require_once('config.inc.php') ) {
    error( 'Could not include the configuration file.' ); 
    exit(1);
}

// Connect to the database.
!$DB = new mysqli( $CFG['db']['host'], $CFG['db']['user'], $CFG['db']['pwd'], $CFG['db']['name'] );
if ( $DB->connect_errno ) {
    error( 'Failed to connect to database: ' . $DB->connect_error . ' [' . $DB->connect_errno . ']');
    exit(1);
}
//$res = $mysqli->query("SELECT 'choices to please everybody.' AS _msg FROM DUAL");
//$row = $res->fetch_assoc();
//echo $row['_msg'];


/**
 * Check the database for defaults and if none found, add them in.
 */

// DONE
if ( !get_config( 'page' ) ) {
    // There is no page set, so add a default.
    if( !set_config( 'page', 'default', true ) ) {
        error( 'Could not set a default page.' );
        exit(1);
    }
}

if ( !get_config( 'status' ) ) {
    // There is no status, so add a default.
    if( !set_config( 'status', 'ok', true ) ) {
        error( '<p>Could not add in a default status.</p>' );
        exit(1);
    }
}
if (!get_config('refresh')) {
    // there is no refresh, so add a default
    if(!set_config('refresh', '300', true)) {
        die('<p>Could not add in a default refresh.</p>');
    }
}
if (!get_config('rssfeed')) {
    // there is no rss feed, so add a default
    if(!set_config('rssfeed', 'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml', true)) {
        die('<p>Could not add in a default rss feed.</p>');
    }
}
if (!get_config('apply_date')) {
    // there is no application date, so add a default
    if(!set_config('apply_date', '2009-11-01', true)) {
        die('<p>Could not add in a default application date.</p>');
    }
}
if (!get_config('statoids_upd')) {
    // add in a config item for statoids
    if(!set_config('statoids_upd', 'initial_configuration', true)) {
        die('<p>Could not add in a statoids config item.</p>');
    }
    //make the statoids table
    make_statoids();
}
if (!get_config('showstopper')) {
    // there is no showstopper text, so add a default
    if(!set_config('showstopper', 'error', true)) {
        die('<p>Could not add in default showstopper text.</p>');
    }
}
if (!get_config('specific_fig')) {
    // there is no specific_fig text, so add a default
    if(!set_config('specific_fig', 'no', true)) {
        die('<p>Could not add in default specific_fig text.</p>');
    }
}


/**
 * Refresh (rereate) the statoids table approx 1 in every 12 page reloads.
 * If 1 refresh every 5 minutes, that's an update once per hour. Approx.
 */
if(rand(1, 12) == 9) {
    make_statoids();
}


/**
 * Functions from here down.
 */

// Just a bit of formatting for errors.
function error( $in ) {
    echo '<p>' . date( 'H:i:s', time() ) . ': ' . $in . '</p>' ;
}

// Gets the configuration.
// DONE
function get_config( $item ) {
    global $DB;

    $res = $DB->query( "SELECT value FROM config WHERE item = '" . $item . "' LIMIT 1;" );
    if ($res->num_rows == 0) {
        return false;
    } else {
        $row = $res->fetch_assoc();
        return $row['value'];
    }
}

// Sets the configuration.
function set_config( $item, $value, $init = false ) {

    global $DB;

    adminlog( 'set_config|' . $item . '|' . $value );
    $value = $DB->real_escape_string( $value );

    if ( !$init ) {
        $sql = "UPDATE config SET value = '" . $value . "' WHERE item = '" . $item . "' LIMIT 1;";
    } else {
        $sql = "INSERT INTO config (item, value) VALUES ('" . $item . "', '" . $value . "');";
    }
    $res = $DB->query( $sql );
    return $res;
}

// Log something to the admin log.
function adminlog( $data ) {

    global $CFG, $DB;

    $sql = "INSERT INTO log (date, data) VALUES ('" . $CFG['db']['time'] . "', '" . $data . "');"; 
    if ( !$res = $DB->query( $sql ) ) {
        error('Error: writing to the log failed.');
        return false;
    }
    return true;
}

// Get the refresh number stored in the config table, unless the page has one specified in the pages table.
function get_refresh($page) {

    // We need the non-default pages to refresh quicker (they're static anyway) so they go back to normal quicker.
    global $DB;

    $sql = "SELECT refresh FROM pages WHERE page = '" . $page . "';";
    $res = $DB->query( $sql );

    if( $res ) {
        if ( $res->num_rows == 0) {
            return get_config('refresh');

        } else {
            $row = $res->fetch_assoc();
            if( $row['refresh'] == 0 ) {
                // If refresh = 0, use whatever's in the config table.
                return get_config('refresh');

            } else {
                return $row['refresh'];
            }
        }
    }
}

// Get statuses as an array.
function get_status_array() {

    global $DB;

    $sql = "SELECT name FROM status ORDER BY priority ASC;";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        return false;

    } else {
        $value = array();
        while ($row = $res->fetch_assoc() ) {
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
        while ($row = $res->fetch_assoc() ) {
            $value[] = $row['page'];
        }
        return $value;
    }
}

// Get a random 'statoid' from the database.
// DONE
function get_rnd_statoid() {

    global $DB;

    $now = time();
    // check the date...
    if (date('n', $now) == 4 && date ('j', $now) == 1) {
        // April 1st.
        $sql = "SELECT COUNT(id) FROM aprilfools;";
        $res = $DB->query( $sql );

        if ( $res->num_rows == 0 ) {
            return '<p class="error">Sorry, no April Fools found.</p>';

        } else {
            $row = $res->fetch_row();
            $id = rand( 1, $row[0] );

            $fool = get_aprilfools( $id );
            return make_text_bigger( $fool );
        }

    } else {
        // All other dates and times.
        $sql = "SELECT COUNT(id) FROM statoids;";
        $res = $DB->query( $sql );

        if ( $res->num_rows == 0 ) {
            return '<p class="error">Sorry, no statoids found.</p>';

        } else {
            $row = $res->fetch_row();
            $id = rand( 1, $row[0] );

            $statoid = get_statoid( $id );
            return make_text_bigger( $statoid );
        }
    }
}

// Make text bigger or not, depending on how many characters there are.
function make_text_bigger($text, $lbound = 30) {
    if(strlen($text) <= $lbound) {
        return '<p class="bigger1">'.$text.'</p>';
    } else if(strlen($text) > $lbound && strlen($text) < ($lbound*2) ) {
        return '<p class="bigger2">'.$text
        .'</p>';
    } else {
        return '<p>'.$text.'</p>';
    }
}

// Get a specific statoid based on it's ID
function get_statoid( $id = 1 ) {

    global $DB;

    $sql = "SELECT text FROM statoids WHERE id = '".$id."';";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0) {
        return '<p class="error">Sorry, no statoids returned.</p>';

    } else {
        $row = $res->fetch_row();
        return $row[0];
    }
}

/**
 * A function solely for April 1st, or for any 'fun' facts and that, really.
 */
function get_aprilfools($id=1) {
    global $DB;
    $res = mysql_query("SELECT text FROM aprilfools WHERE id = '".$id."';", $DB);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no April Fools returned.</p>';
    } else {
        $fool = mysql_fetch_row($res);
        return $fool[0];
    }
}

// Gets a named 'figure' then returns it, or a random one on fail.
function get_figure() {
    global $CFG;

    $img = get_config( 'specific_fig' );

    if ( $img == 'no' ) {
        return get_rnd_figure();

    } else {
        $file_loc = $CFG['dir']['gfx'].$img;
        if(file_exists($file_loc)) {
            adminlog('img|set|'.$img);
            return '<img src="'.$file_loc.'" title="'.$img.'" />'."\n";
        } else {
            return get_rnd_figure();
        }
        
    }
}

// Gets a random figure from those available on disk.
function get_rnd_figure() {
    global $CFG;

    // could scan the dir for files... but not gonna.
    //$figures = array (
    //    1 => 'fig-bobby', 'fig-brian', 'fig-chris', 'fig-dan', 'fig-dave', 'fig-dodders', 'fig-jeff', 'fig-jo',
    //    'fig-jo-alt', 'fig-kelly', 'fig-kev', 'fig-leigh', 'fig-mark', 'fig-paul', 'fig-paul-alt', 'fig-tim', 'fig-tobie');

    // Scan the folder for appropriate images.
    $files = array();
    if ( $fh = opendir( $CFG['dir']['ppl'] ) ) {
        while ( false !== ( $entry = readdir( $fh ) ) ) {
            if ( $entry != '.' && $entry != '..' && ( substr( $entry, 0, 3 ) == 'fig' || substr( $entry, 0, 7 ) == 'special' ) ) {
                echo "$entry\n";
                $files[] = $entry;
            }
        }
        closedir( $fh );
    }

    $figure_num = rand(1, count($figures));
    $file_loc = $CFG['dir']['gfx'].$figures[$figure_num].'.png';
    if(file_exists($file_loc)) {
        adminlog('img|rnd|'.$figures[$figure_num]);
        return '<img src="'.$file_loc.'" title="'.substr($figures[$figure_num], 4).'" />'."\n";
    } else {
        adminlog('img|err|'.$file_loc);
        return '<p class="error">Image error</p>'."\n";
    }
}
/**
 * gets a list of figures with 'special' in the name.
 */
function get_special_figure_list() {
    global $CFG;

    $files = scandir($CFG['dir']['gfx']);

    $files_special = array('no');

    for($j = 0; $j < count($files); $j++) {
        if (preg_match('/special/', $files[$j]) || preg_match('/^fig/', $files[$j])) {
            $files_special[] = $files[$j];
        }
    }

    //print_r($files_special);
    for($j = 0; $j < count($files_special); $j++) {
        echo '<option value="'.$files_special[$j].'"';
        if($files_special[$j] == get_config('specific_fig')) {
            echo ' selected="selected"';
        }
        echo '>'.$files_special[$j].'</option>'."\n";
    }
}




/**
 * SimplePie RSS feed parsing
 */
function get_scroller() {
    require_once('simplepie.inc');
    // We'll process this feed with all of the default options.
    // Not sure what the other options are yet
    $feed = new SimplePie(get_config('rssfeed'));
    // This makes sure that the content is sent to the browser as text/html and the UTF-8 character set (since we didn't change it).
    $feed->handle_content_type();
    // The HEIGHT and the WIDTH should match the same details in style.css/#scroller
    $build = '<marquee scrollamount="3" height="45" width="1278">';

    // Add a news-service image to the start of the feed.
    $testfeed = get_config('rssfeed');
    if ( preg_match( '/newsrss.bbc.co.uk/', $testfeed ) ) {
        $build .= '<img src="http://static.bbci.co.uk/frameworks/barlesque/2.83.4/orb/4/img/bbc-blocks-light.png" height="25" style="vertical-align: middle;"> ';

    } else if ( preg_match( '/slashdot/', $testfeed ) ) {
        $build .= '<img src="http://farm3.static.flickr.com/2302/2454530894_f2ca265bde_o.jpg" height="30" style="vertical-align: middle;"> ';
    }

    foreach ($feed->get_items() as $item) {
        if($item->get_description() != '') {
            // quick check to ensure there's a non-empty description
            $build .= $item->get_title().': <em>'.$item->get_description().'</em> &rarr; '."\n";
        }
    }
    $build .= '</marquee>'."\n";
    return $build;
}


/**
 * File Operation functions
 */
function get_data_from_file( $file, $type = '' ) {

    global $CFG;

    // Get the file or get it with $CFG['ext'] on the end.
    if( file_exists( $CFG['dir']['data'] . $file ) ) {
        $fh = fopen( $CFG['dir']['data'] . $file, 'r' );

    } else if( file_exists( $CFG['dir']['data'] . $file . $CFG['ext'] )) {
        $fh = fopen( $CFG['dir']['data'] . $file . $CFG['ext'], 'r' );

    } else {
        return '<span class="err">No data: error opening ' . $file . '[' . $CFG['ext'] . ']</span>';
    }

    // Get the data out of the file: reads to EoL or EoF, whichever is first.
    $data = trim( fgets( $fh ) );
    fclose( $fh );

    if( $type == '' ) {
        // normal data
        if( is_numeric( $data ) ) {
            // format the number to add thousands separators
            $data = number_format( $data );
        }

    } else if($type = 'status') {
        // print a corresponding face
        $data = '<img src="'.$CFG['dir']['img'].$data.'.png" width="16" height="16" alt="'.$type.' '.$data.'" />';
    }

    return $data;
}


/**
 * Page function(s)
 */
function make_page_change_menu() {
    $pages = get_page_array();
    $curr_page = get_config('page');

    $build = '<ul>';
    foreach ($pages as $page) {
        $build .= '<li>';
        if ($page == $curr_page) {
            $build .= '<strong>';
        } else {
            $build .= '<a href="page_edit.php?page='.$page.'">';
        }
        $build .= ucfirst($page);
        if ($page == $curr_page) {
            $build .= '</strong> (Current)';
        } else {
            $build .= '</a>';
        }
        $build .= '</li>';
    }
    $build .= '</ul>'."\n";
    return $build;
}

// Get this page's background image.
// DONE
function get_page_bg() {

    global $CFG, $DB;

    $sql = "SELECT bg FROM pages WHERE page = '" . $CFG['page'] . "' LIMIT 1;";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        error( 'Can\'t get this page\'s background image.' );
        return false;

    } else {
        $row = $res->fetch_assoc();
        return $row['bg'];
    }
}


// Get the current status from the database.
// DONE
function get_status() {
    return get_config( 'status' );
}

// Get this status' image, depending on what the current status is.
// DONE
function get_status_img() {

    global $CFG, $DB;

    $sql = "SELECT img FROM status WHERE name = '" . get_status() . "';";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        return '<p class="error">Sorry, no status image.</p>';

    } else {
        $row = $res->fetch_assoc();
        return '<img src="' . $CFG['dir']['status'] . 'status-' . $row['img'] . '.png" width="60">' . "\n";
    }
}

// Get this status' text, depending on what the current status is.
// DONE
function get_status_txt() {

    global $DB;

    $sql = "SELECT text FROM status WHERE name = '".get_status()."';";
    $res = $DB->query( $sql);

    if ( $res->num_rows == 0 ) {
        return '<p class="error">Sorry, no status.</p>';

    } else {
        $row = $res->fetch_row();
        return '<p>' . $row[0] . '</p>' . "\n";
    }
}


function set_status($status) {
    // we have the statuses in the $statuses array, so rewrite the next line:
    if ($status == 'ok' || $status == 'login' || $status == 'email' || $status == 'net' || $status == 'server' || $status == 'bad') {
        global $DB;
        adminlog('set_status|'.$status);
        $res = mysql_unbuffered_query("UPDATE config SET value = '".$status."' WHERE item = 'status' LIMIT 1");
        return $res;
    }
}
function make_status_change_menu() {
    $statuses = get_status_array();
    if($statuses) {
        $curr_status = get_config('status');

        $build = '<ul>';
        foreach ($statuses as $status) {
            $build .= '<li>';
            if ($status == $curr_status) {
                $build .= '<strong>';
            } else {
                $build .= '<a href="status_edit.php?status='.$status.'">';
            }
            $build .= ucfirst($status);
            if ($status == $curr_status) {
                $build .= '</strong> (Current)';
            } else {
                $build .= '</a>';
            }
            $build .= '</li>';
        }
        $build .= '</ul>'."\n";
        return $build;
    } else {
        return '<p class="error">Sorry, no statuses returned.</p>';
    }
}


// Get 'n' next events.
// DONE
function get_events( $num = 3, $edit = false ) {

    global $DB;

    $now = time();
    $today = date('Y', $now).'-'.date('m', $now).'-'.date('d', $now);

    $sql = "SELECT id, start, text FROM events WHERE start >= '" . $today . "' AND deleted = 0 ORDER BY start ASC, id ASC LIMIT " . $num . ";";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0) {
        return '<p class="error">Sorry, no events.</p>';

    } else {
        $build = "<ul>\n";
        while ( $row = $res->fetch_assoc() ) {
            $db_date = $row['start'];
            $disp_date = date("jS M", mktime(0, 0, 0, substr($db_date, 5, 2), substr($db_date, 8, 2), substr($db_date, 0, 4) ));
            $build .= '<li>' . $disp_date . ': <em>' . $row['text'] . '</em>';
            if ( $edit == true ) {
                $build .= ' [ <a href="event_del.php?eid=' . $row['id'] . '">del</a> ]';
            }
            $build .= "</li>\n";
        }
        $build .= '</ul>'."\n";
        return $build;
    }

}

// Adds an event.
function add_event( $date, $text ) {

    global $DB;

    $text = $DB->real_escape_string( $text );
    
    adminlog( 'add_event|' . $text );

    $sql = "INSERT INTO events (start, text) VALUES ('" . $date . "', '" . $text . "');";
    $res = $DB->query( $sql );

    return $res;
}

// Deletes an event.
function del_event( $eid ) {

    global $DB;

    adminlog( 'del_event|' . $eid );

    $sql = "UPDATE events SET deleted = 1 WHERE id = ".$eid.";";
    $res = $DB->query( $sql );

    return $res;
}


// Make the form for adding in statistics.
function get_stats_form() {

    global $DB;

    $sql = "SELECT id, text, value, readonly FROM stats ORDER BY id ASC;";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0 ) {
        return '<p class="error">Sorry, no events.</p>';

    } else {
        $build = '';
        while ( $row = $res->fetch_assoc() ) {
            $build .= '<form action="stats_edit.php" method="get">'."\n";
            $build .= '    <tr>'."\n";
            $build .= '        <td>'.$row['text'].'</td>'."\n";
            $build .= '        <td class="thin"> '."\n";
            $build .= '            <input type="text" value="'.$row['value'].'" name="value" size="5" maxlength="7" ';
            if($row['readonly'] == 1) {
                // has been changed to 'disabled' to make it more obviously not available
                $build .= ' disabled="disabled"';
            }
            $build .= '/></td>'."\n";
            //$build .= '        <td>'.$row['text_after'].'</td>'."\n";
            $build .= '        <td><input type="hidden" name="key" value="'.$row['id'].'">'."\n";
            $build .= '            <button type="submit"';
            if($row['readonly'] == 1) {
                $build .= ' disabled="disabled"';
            }
            $build .= '>Set</button>'."\n";
            $build .= '        </td>'."\n";
            $build .= '    </tr>'."\n";
            $build .= '</form>'."\n";
        }
        return $build;
    }
}


function edit_stat($key, $value) {
    global $DB;
    $text = mysql_real_escape_string($text, $DB);
    adminlog('edit_stat|'.$key.'|'.$value);
    $res = mysql_unbuffered_query("UPDATE stats SET value = '".$value."' WHERE id = '".$key."';", $DB);
    return $res;
}

// Get last 'n' log entries.
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
 * new combined factoid/stats function.
 */
function make_statoids() {
    global $CFG, $DB;

    // change the update time in the config
#    set_config('statoids_upd', $CFG['db']['time']);
    set_config('statoids_upd[DENIED]', $CFG['db']['time']);

    // run ALL the update functions
#    update_moodle_stats();
    //update_joomla_stats();
#    update_website_stats();
#    update_online_apps();

    // truncate the table
#    $res = mysql_query("TRUNCATE TABLE statoids;", $DB);
    // insert data from factoids
#    $res2 = mysql_query("INSERT INTO statoids (`text`) SELECT text FROM factoids;", $DB);
    // insert data from stats, formatted
#    $res3 = mysql_query("INSERT INTO statoids (`text`) SELECT CONCAT_WS ('', text_before, FORMAT(value, 0), text_after) FROM stats WHERE readonly = '1';", $DB);
    // insert data from stats, UNformatted
#    $res4 = mysql_query("INSERT INTO statoids (`text`) SELECT CONCAT_WS ('', text_before, value, text_after) FROM stats WHERE readonly = '0';", $DB);
}
/**
 * Get Moodle database statistics
 */
function update_moodle_stats() {
    global $DB;

    $mdl_lnk = mysql_connect('172.20.1.52', str_rot13('pfvq'), str_rot13('53k15g'));
    if (!$mdl_lnk) {
        return false;
    } else {
        if (!mysql_select_db('moodle', $mdl_lnk)) {
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
function update_joomla_stats() {
    global $DB;

    $mdl_lnk = mysql_connect('172.20.1.52', str_rot13('pfvq'), str_rot13('53k15g'));
    if (!$mdl_lnk) {
        return false;
    } else {
        if (!mysql_select_db('joomla', $mdl_lnk)) {
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
function update_website_stats() {
    global $DB;

    define('ga_email','cnhyinhtuna@fbhguqriba.np.hx');
    define('ga_password','53k15g53k15g');
    define('ga_profile_id','2084153');

    require 'gapi.class.php';

    $ga = new gapi(str_rot13(ga_email),str_rot13(ga_password));

    $filter = 'date == '.date('Y').date('m').(date('d')-1);
    $ga->requestReportData(ga_profile_id,array('day'),array('pageviews','visits'),'-visits',$filter);

    $pageviews  = $ga->getPageviews();
    $visits     = $ga->getVisits();

    // insert into stats
    if($pageviews > 0) {
        $res1 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$pageviews."' WHERE id = 'web_views';", $DB);
    }
    if($visits > 0) {
        $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$visits."' WHERE id = 'web_visit';", $DB);
    }
}
function update_online_apps() {
    global $DB;

    $cln_lnk = mysql_connect('172.20.1.52', str_rot13('pfvq'), str_rot13('53k15g'));
    if (!$cln_lnk) {
        return false;
    } else {
        if (!mysql_select_db('collin', $cln_lnk)) {
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
function update_foursquare() {
    $img_str = 'http://foursquare.com/img/headerLogo.png';
    $build = '<img src="'.$img_str.'" /><br />';
}

