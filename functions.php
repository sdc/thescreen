<?php

/**
 * Database functions (and variables)
 */

// include config.inc.php
require_once('config.inc.php');

$dir_gfx    = './img/';

// time, formatted for the database
$db_time = date("Y-m-d H:i:s", time());

$db_db = 'thescreen';
$db_link = mysql_connect($db_host, $db_user, $db_pass);
if (!$db_link) {
    die('Could not connect: ' . mysql_error());
}
if (!mysql_select_db($db_db, $db_link)) {
    die('Could not select: ' . mysql_error());
}


/**
 * Checking the database for defaults and adding some in if they don't already exist.
 */
if (!get_config('page')) {
    // there is no page, so add a default
    if(!set_config('page', 'default', true)) {
        die('<p>Could not add in a default page.</p>');
    }
}
if (!get_config('status')) {
    // there is no status, so add a default
    if(!set_config('status', 'ok', true)) {
        die('<p>Could not add in a default status.</p>');
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


/***********************************************************************************************************************
 * functions from here down
 **********************************************************************************************************************/


/**
 * config functions
 */
function get_config($item) {
    global $db_link;
    $res = mysql_query("SELECT value FROM config WHERE item = '".$item."' LIMIT 1;", $db_link);
    if (mysql_num_rows($res) == 0) {
        return false;
    } else {
        $value = mysql_fetch_row($res);
        return $value[0];
    }
}
function set_config($item, $value, $init = false) {
    global $db_link;
    adminlog('set_config|'.$item.'|'.$value);
    $value = mysql_real_escape_string($value);
    if (!$init) {
        $res = mysql_unbuffered_query("UPDATE config SET value = '".$value."' WHERE item = '".$item."' LIMIT 1;");
    } else {
        $res = mysql_unbuffered_query("INSERT INTO config (item, value) VALUES ('".$item."', '".$value."');");
    }
    return $res;
}

/**
 * administrative functions
 */
function adminlog($data) {
    global $db_link, $db_time;
    $data = mysql_real_escape_string($data);
    $res = mysql_unbuffered_query("INSERT INTO log (id, date, data) VALUES (null, '".$db_time."', '".$data."');", $db_link);
}

/**
 * Get the refresh number stored in the config table, unless the page has one specified in the pages table
 */
function get_refresh($page) {
    // we need the non-default pages to refresh quicker (they're static anyway) so they go back to normal quicker.
    global $db_link;
    $res = mysql_query("SELECT refresh FROM pages WHERE page = '".$page."';", $db_link);
    if($res) {
        if (mysql_num_rows($res) == 0) {
            return get_config('refresh');
        } else {
            $row = mysql_fetch_assoc($res);
            if($row['refresh'] == 0) {
                // if refresh = 0 it means use whatever's in the config table
                return get_config('refresh');
            } else {
                return $row['refresh'];
            }
        }
    } // end if $res
}

/**
 * array functions
 */
function get_status_array() {
    global $db_link;
    $res = mysql_query("SELECT name FROM status ORDER BY priority ASC;", $db_link);
    if($res) {
        if (mysql_num_rows($res) == 0) {
            return false;
        } else {
            $value = array();
            while ($row = mysql_fetch_assoc($res)) {
                $value[] = $row['name'];  // <-=************************************************************************* TODO
            }
            return $value;
        }
    } else {
        return false;
    }
}
function get_page_array() {
    global $db_link;
    $res = mysql_query("SELECT page FROM pages ORDER BY priority ASC;", $db_link);
    if (mysql_num_rows($res) == 0) {
        return false;
    } else {
        $value = array();
        while ($row = mysql_fetch_assoc($res)) {
            $value[] = $row['page'];
        }
        return $value;
    }
}


/**
 * Gets a random 'statoid' from the database
 */
function get_rnd_statoid() {
    global $db_link;
    $now = time();
    // check the date...
    if (date('n', $now) == 4 && date ('j', $now) == 1) {
        // april 1st...
        $res = mysql_query("SELECT COUNT(id) FROM aprilfools;", $db_link);
        if (mysql_num_rows($res) == 0) {
            return '<p class="error">Sorry, no April Fools found.</p>';
        } else {
            $row = mysql_fetch_row($res);
            $id = rand(1, $row[0]);
            $fool = get_aprilfools($id);
            return make_text_bigger($fool);
        }
    } else {
        // all other dates and times
        $res = mysql_query("SELECT COUNT(id) FROM statoids;", $db_link);
        if (mysql_num_rows($res) == 0) {
            return '<p class="error">Sorry, no statoids found.</p>';
        } else {
            $row = mysql_fetch_row($res);
            $id = rand(1, $row[0]);
            $statoid = get_statoid($id);
            return make_text_bigger($statoid);
        }
    }
}
/**
 * make bigger
 */
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
/**
 * gets a specific statoid based on it's ID
 */
function get_statoid($id=1) {
    global $db_link;
    $res = mysql_query("SELECT text FROM statoids WHERE id = '".$id."';", $db_link);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no statoids returned.</p>';
    } else {
        $statoid = mysql_fetch_row($res);
        return $statoid[0];
    }
}
/**
 * A function solely for April 1st, or for any 'fun' facts and that, really.
 */
function get_aprilfools($id=1) {
    global $db_link;
    $res = mysql_query("SELECT text FROM aprilfools WHERE id = '".$id."';", $db_link);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no April Fools returned.</p>';
    } else {
        $fool = mysql_fetch_row($res);
        return $fool[0];
    }
}
/**
 * gets a named 'figure' then returns it, or a random one on fail.
 */
function get_figure() {
    global $dir_gfx;
    $img = get_config('specific_fig');
    if ($img != 'no') {
        $file_loc = $dir_gfx.$img;
        if(file_exists($file_loc)) {
            adminlog('img|set|'.$img);
            return '<img src="'.$file_loc.'" title="'.$img.'" />'."\n";
        } else {
            return get_rnd_figure();
        }
    } else {
        return get_rnd_figure();
    }
}
/**
 * gets a random figure
 */
function get_rnd_figure() {
    global $dir_gfx;
    // could scan the dir for files... but not gonna.
    $figures = array (
        1 => 'fig-bobby', 'fig-brian', 'fig-chris', 'fig-dan', 'fig-dave', 'fig-dodders', 'fig-jeff', 'fig-jo',
        'fig-jo-alt', 'fig-kelly', 'fig-kev', 'fig-leigh', 'fig-mark', 'fig-paul', 'fig-paul-alt', 'fig-tim', 'fig-tobie');
    $figure_num = rand(1, count($figures));
    $file_loc = $dir_gfx.$figures[$figure_num].'.png';
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
    global $dir_gfx;
    $files = scandir($dir_gfx);

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

    // little bit of code to add where the news is coming from to the start of the feed
    $testfeed = get_config('rssfeed');
    if (preg_match('/newsrss.bbc.co.uk/', $testfeed)) {
        $build .= '<img src="http://www.bbc.co.uk/home/release-39-2/img/new_logo.png" height="25" style="vertical-align: baseline;" /> ';
    } else if (preg_match('/slashdot/', $testfeed)) {
        $build .= '<img src="http://farm3.static.flickr.com/2302/2454530894_f2ca265bde_o.jpg" height="30" style="vertical-align: middle;" /> ';
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
function get_data_from_file($file, $type='') {
    // some variables
    $datadir = 'data/';
    $imgdir = 'img/';
    $ext = '.txt';
    $test = 'FFFFFFFFFFUUUUUUUUUUUUUUUUUUUUUUUU';

    // get the file or get it with $ext on the end
    if(file_exists($datadir.$file)) {
        $fh = fopen($datadir.$file, 'r');
    } else if(file_exists($datadir.$file.$ext)) {
        $fh = fopen($datadir.$file.$ext, 'r');
    } else {
        return '<span class="err">No data: error opening '.$file.'['.$ext.']</span>';
    }

    // get the data out of the file: reads to EoL or EoF, whichever is first
    $data = trim(fgets($fh));

    // close the fine handle
    fclose($fh);

    if($type == '') {
        // normal data
        if(is_numeric($data)) {
            // format the number to add thousands separators
            $data = number_format($data);
        }
    } else if($type = 'status') {
        // print a corresponding face
        $data = '<img src="'.$imgdir.$data.'.png" width="16" height="16" alt="'.$type.' '.$data.'" />';
    }

    // get rid of the thing
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
function get_page_bg() {
    global $db_link;
    $res = mysql_query("SELECT bg FROM pages WHERE page = '".get_config('page')."' LIMIT 1;", $db_link);
    if (mysql_num_rows($res) == 0) {
        return false;
    } else {
        $value = mysql_fetch_assoc($res);
        return $value['bg'];
    }
}


/**
 * Status functions
 */
function get_status() {
    return get_config('status');
}
function get_status_img() {
    global $dir_gfx, $db_link;
    $res = mysql_query("SELECT img FROM status WHERE name = '".get_status()."';", $db_link);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no status img returned.</p>';
    } else {
        $status = mysql_fetch_assoc($res);
        return '<img src="'.$dir_gfx.'status-'.$status['img'].'.png" width="60" />'."\n";
    }
}
function get_status_txt() {
    global $db_link;
    $res = mysql_query("SELECT text FROM status WHERE name = '".get_status()."';", $db_link);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no status returned.</p>';
    } else {
        $status = mysql_fetch_row($res);
        return '<p>'.$status[0].'</p>'."\n";
    }
}
function set_status($status) {
    // we have the statuses in the $statuses array, so rewrite the next line:
    if ($status == 'ok' || $status == 'login' || $status == 'email' || $status == 'net' || $status == 'server' || $status == 'bad') {
        global $db_link;
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


/**
 * event stuff
 */
function get_events($num = 3, $edit = false){
    global $db_link;
    $now = time();
    $today = date('Y', $now).'-'.date('m', $now).'-'.date('d', $now);
    $res = mysql_query("SELECT id, start, text FROM events WHERE start >= '".$today."' AND deleted = 0 ORDER BY start ASC, id ASC LIMIT ".$num.";", $db_link);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no events returned.</p>';
    } else {
        $build = '<ul>';
        while ($row = mysql_fetch_assoc($res)) {
            $db_date = $row['start'];
            $disp_date = date("jS M", mktime(0, 0, 0, substr($db_date, 5, 2), substr($db_date, 8, 2), substr($db_date, 0, 4) ));
            $build .= '<li>'.$disp_date.': <em>'.$row['text'].'</em>';
            if ($edit == true) {
                $build .= ' [ <a href="event_del.php?eid='.$row['id'].'">del</a> ]';
            }
            $build .= "</li>\n";
        }
        $build .= '</ul>'."\n";
        return $build;
    }

}
function add_event($date, $text) {
    global $db_link;
    $text = mysql_real_escape_string($text, $db_link);
    adminlog('add_event|'.$text);
    $res = mysql_unbuffered_query("INSERT INTO events (id, start, text) VALUES (null, '".$date."', '".$text."');", $db_link);
    return $res;
}
function del_event($eid) {
    global $db_link;
    adminlog('del_event|'.$eid);
    $res = mysql_unbuffered_query("UPDATE events SET deleted = 1 WHERE id = ".$eid.";", $db_link);
    return $res;
}


/**
 * stats stuff
 */
function get_stats_form() {
    global $db_link;
    $res = mysql_query("SELECT id, text, value, readonly FROM stats ORDER BY id ASC;", $db_link);
    if (mysql_num_rows($res) == 0) {
        return '<p class="error">Sorry, no events returned.</p>';
    } else {
        $build = '';
        while ($row = mysql_fetch_assoc($res)) {
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
    global $db_link;
    $text = mysql_real_escape_string($text, $db_link);
    adminlog('edit_stat|'.$key.'|'.$value);
    $res = mysql_unbuffered_query("UPDATE stats SET value = '".$value."' WHERE id = '".$key."';", $db_link);
    return $res;
}



/**
 * function for getting last n log entries
 */
function get_last_log($no=10) {
    global $db_link;

    $res = mysql_query("SELECT * FROM log ORDER BY id DESC LIMIT ".$no.";", $db_link);
    $build = "<ul>\n";
    while ($row = mysql_fetch_assoc($res)) {
        $build .= '<li>';
        //$build .= $row['id'].': ';
        $build .= $row['date'].': '.$row['data'].'</li>'."\n";
    }
    $build .= "</ul>\n";

    return $build;
}



/**
 * new combined factoid/stats function.
 */
function make_statoids() {
    global $db_link, $db_time;

    // change the update time in the config
#    set_config('statoids_upd', $db_time);
    set_config('statoids_upd[DENIED]', $db_time);

    // run ALL the update functions
#    update_moodle_stats();
    //update_joomla_stats();
#    update_website_stats();
#    update_online_apps();

    // truncate the table
#    $res = mysql_query("TRUNCATE TABLE statoids;", $db_link);
    // insert data from factoids
#    $res2 = mysql_query("INSERT INTO statoids (`text`) SELECT text FROM factoids;", $db_link);
    // insert data from stats, formatted
#    $res3 = mysql_query("INSERT INTO statoids (`text`) SELECT CONCAT_WS ('', text_before, FORMAT(value, 0), text_after) FROM stats WHERE readonly = '1';", $db_link);
    // insert data from stats, UNformatted
#    $res4 = mysql_query("INSERT INTO statoids (`text`) SELECT CONCAT_WS ('', text_before, value, text_after) FROM stats WHERE readonly = '0';", $db_link);
}
/**
 * Get Moodle database statistics
 */
function update_moodle_stats() {
    global $db_link;

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
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_usr';", $db_link);

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
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_crs';", $db_link);

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
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_usr_td';", $db_link);

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
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'mdl_hit';", $db_link);
        }
    }
}
function update_joomla_stats() {
    global $db_link;

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
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'web_views';", $db_link);
        }
    }
}
function update_website_stats() {
    global $db_link;

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
        $res1 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$pageviews."' WHERE id = 'web_views';", $db_link);
    }
    if($visits > 0) {
        $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$visits."' WHERE id = 'web_visit';", $db_link);
    }
}
function update_online_apps() {
    global $db_link;

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
            $res2 = mysql_unbuffered_query("UPDATE csid.stats SET value = '".$ins."' WHERE id = 'onl_app';", $db_link);
        }
    }
}
function update_foursquare() {
    $img_str = 'http://foursquare.com/img/headerLogo.png';
    $build = '<img src="'.$img_str.'" /><br />';
}
?>
