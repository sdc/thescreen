<?php

/**
 * Database functions (and variables)
 */

// Configuration array and some settings.
// TODO: Split these into options which can be changed (move into config[.dist].inc.php) or not (leave here).
$CFG = array();

// Store version info seperately.
require_once( 'version.php' );

$CFG['dir']['graphics'] = 'graphics/';
$CFG['dir']['ppl']      = $CFG['dir']['graphics'] . 'people/';
$CFG['dir']['status']   = $CFG['dir']['graphics'] . 'status/';
$CFG['dir']['bg']       = $CFG['dir']['graphics'] . 'backgrounds/';
$CFG['dir']['pages']    = 'pages/';

//$CFG['dir']['data']     = './data/';

$CFG['db']['time']      = date( 'Y-m-d H:i:s', time()) ;            // 2015-03-09 09:00:00

$CFG['time']['short']   = 'j\<\s\u\p\>S\<\/\s\u\p\> F';             // 9th March
$CFG['time']['full']    = 'l j\<\s\u\p\>S\<\/\s\u\p\> F Y, g:ia';   // Monday 9th March 2015, 9:00am
$CFG['time']['title']   = 'D jS M Y, g:ia';                         // Mon 9th Mar 2015, 9:00am
$CFG['time']['time']    = 'g:ia';                                   // 9:00am

$CFG['ext']             = '.txt';   // text file extension for loading data from files

// App's name.
$CFG['lang']['title']     = 'The Screen&trade; Admin';
$CFG['lang']['longtitle'] = 'The Screen - Administration';

// Set the name of the admin page, for use in other add/edit pages.
// TODO: this, better.
$CFG['adminpage']       = 'manage.php';

// Minutes before the admin screen times out.
$CFG['admintimeout']    = 15;

// Main page refresh poll time in seconds.
$CFG['poll']            = 5;

// Set to true to ignore the installation files warning. Not recommended.
$CFG['ignoreinstallfiles']  = true;

// "Base-2 logarithm of the iteration count used for password stretching" in phPASS.
$CFG['phpass']['base2log']  = 10;

// For teh lulz.
$now = time();
//$now = 1427878800; // 9am, April 1st 2015 (for testing).
$CFG['aprilfool']       = ( date( 'n', $now ) == 4 && date ( 'j', $now ) == 1 ) ? true : false;

// PHP's date ('n').
$CFG['days'] = array(
  '1' => 'Monday',
  '2' => 'Tuesday',
  '3' => 'Wednesday',
  '4' => 'Thursday',
  '5' => 'Friday',
  '6' => 'Saturday',
  '7' => 'Sunday'
);

// The times the screen can be scheduled from and to. 8am to 9pm in 15 minute increments.
$CFG['times'] = array();
for ( $h = 8; $h <= 21; $h++ ) {
  for ( $m = 0; $m <= 55; $m += 15 ) {
    $CFG['times'][] = $h . ':' . str_pad( $m, 2, 0, STR_PAD_LEFT );
  }
}

// Refresh times.
$CFG['refresh'] = array(
  5   => '5 seconds',
  10  => '10 seconds',
  15  => '15 seconds',
  30  => '30 seconds',
  60  => '1 minute',
  120 => '2 minutes',
  300 => '5 minutes',
  600 => '10 minutes',
  900 => '15 minutes'
);

// Include config.inc.php
if ( !require_once( 'config.inc.php' ) ) {
  error( 'Could not open the configuration file.' );
  exit(1);
}

// Connect to the database.
$DB = new mysqli( $CFG['db']['host'], $CFG['db']['user'], $CFG['db']['pwd'], $CFG['db']['name'] );
if ( $DB->connect_error ) {
  echo 'Failed to connect to database: ' . $DB->connect_error;
  exit(1);
}

// Initial configuration settings array.
if ( !require_once( 'defaults.inc.php' ) ) {
  echo 'Could not open the default configuration file.';
  exit(1);
}

// The current page's name.
$CFG['page']    = get_config( 'page' );
$CFG['status']  = get_config( 'status' );

// If we want to force April Fools' Day for some reason (testing, or editing of the factoids for that day).
$CFG['aprilfool'] = ( get_config( 'forceaprilfool' ) ) ? true : false;


/**
 * General functions.
 */

// Just a bit of formatting for errors.
// DONE
function error( $in ) {

  $errortext = date( 'H:i:s', time() ) . ': ' . $in;
  adminlog( $errortext );
  echo '<p>' . $errortext . '</p>' ;
}

// Gets the configuration.
// DONE
function get_config( $item ) {
  global $DB;

  $res = $DB->query( "SELECT value FROM config WHERE item = '" . $item . "' LIMIT 1;" );
  if ( !$res || $res->num_rows == 0 ) {
    return false;
  } else {
    $row = $res->fetch_assoc();
    return $row['value'];
  }
}

// Sets the configuration.
// DONE
function set_config( $item, $value, $init = false ) {
  global $DB;

  adminlog( 'set_config|' . $item . '|' . $value );
  $value = $DB->real_escape_string( $value );

  if ( $init ) {
    $sql = "INSERT INTO config (item, value, created, modified) VALUES ('" . $item . "', '" . $value . "', '" . time() . "', '" . time() . "');";
  } else {
    $sql = "UPDATE config SET value = '" . $value . "', modified = '" . time() . "' WHERE item = '" . $item . "' LIMIT 1;";
  }

  $res = $DB->query( $sql );
  return $res;
}

// Log something to the admin log.
// DONE
function adminlog( $data ) {

  global $CFG, $DB;

  $sql = "INSERT INTO log (date, data) VALUES ('" . $CFG['db']['time'] . "', '" . $data . "');"; 
  $res = $DB->query( $sql );

  if ( $res ) {
    return true;
  } else {
    error( 'Error: writing to the log failed.' );
    return false;
  }
}



/**
 * Page functions.
 */

// Gets the page's or status' proper name from an id.
// DONE
function get_name( $type, $id ) {
  global $DB;

  // $type can be 'pages' or 'status' at the moment.
  if ( empty( $type ) ) {
    return false;
  }

  $type = $DB->real_escape_string( $type );

  $sql = "SELECT name FROM " . $type . " WHERE id = '" . $id . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {
    $row = $res->fetch_assoc(); 
    return $row['name'];
  }

}

// Gets the page's or status' title from an id.
// DONE
function get_title( $type, $id ) {
  global $DB;

  // $type can be 'pages' or 'status' at the moment.
  if ( empty( $type ) ) {
    return 'unknown';
  }

  $type = $DB->real_escape_string( $type );

  $sql = "SELECT title FROM " . $type . " WHERE id = '" . $id . "' LIMIT 1;";

  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {
    $row = $res->fetch_assoc(); 
    return $row['title'];
  }

}

// Gets the page or status id from a proper name.
// DONE
function get_id( $type, $name ) {
  global $DB;

  // $type can be 'pages' or 'status' at the moment.
  if ( empty( $type ) ) {
    return false;
  }

  $type = $DB->real_escape_string( $type );
  $name = $DB->real_escape_string( $name );

  $sql = "SELECT id FROM " . $type . " WHERE name = '" . $name . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return false;

  } else {
    $row = $res->fetch_assoc(); 
    return $row['id'];
  }

}



// Get the refresh number stored in the config table, unless the page has one specified in the pages table.
// DONE
function get_refresh( $id ) {

  global $DB;

  $sql = "SELECT refresh FROM pages WHERE id = '" . $id . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0) {
    return get_config( 'refresh' );

  } else {
    $row = $res->fetch_assoc();
    if ( $row['refresh'] == 0 ) {
      // If refresh = 0, use whatever's in the config table.
      return get_config( 'refresh' );

    } else {
      return $row['refresh'];
    }
  }

}

// Get a random factoid from the database.
// DONE
function get_random_factoid( $prettify = true ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';
  $sql = "SELECT `id` FROM `" . $table . "` WHERE `hidden` = '0';";

  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0 ) {
    return '<p class="error">Sorry, no factoids.</p>';
  }

  $rows = array();
  while ( $row = $res->fetch_assoc() ) {
    $rows[] = $row['id'];
  }

  $id = rand( 0, count( $rows ) - 1 );
  $factoid = get_factoid( $rows[$id] );

  if ( $prettify ) {
    return make_text_bigger( $factoid );
  } else {
    return $factoid;
  }

}

// Get a specific factoid based on it's ID.
// DONE
function get_factoid( $id = 1 ) {

  global $CFG, $DB;

  $table = ( $CFG['aprilfool'] ) ? 'aprilfools' : 'factoids';
  $sql = "SELECT `fact` FROM `" . $table . "` WHERE `id` = '" . $id . "' LIMIT 1;";

  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0 ) {
    return '<p class="error">Sorry, no factoids.</p>';
  }
  
  $row = $res->fetch_row();
  return $row[0];
}

// Make text bigger or not, depending on how many characters there are.
// DONE
function make_text_bigger( $text, $lbound = 30 ) {

  if ( strlen( $text ) <= $lbound) {
    return '<p class="bigger1">' . $text . '</p>';

  } else if ( strlen( $text ) > $lbound && strlen( $text ) < ( $lbound * 2 ) ) {
    return '<p class="bigger2">' . $text . '</p>';

  } else {
    return '<p>' . $text . '</p>';
  }
}


// Gets a named 'figure' then returns it, or a random one on fail.
// TODO: Use the config option 'aprilfool' to choose a specific image on April 1st?
// This needs refactoring, quite urgently.
function get_figure() {
  global $CFG;

  $img = get_config( 'specific_fig' );

  if ( $CFG['aprilfool'] ) {
    adminlog( 'img|set|aprilfool' );
    return '<img src="' . $CFG['dir']['ppl'] . 'special-aleksandr.png" title="Aleksandr">' . "\n";

  } else if ( $img == 'aaa-random.png' ) {
    return get_random_figure();

  } else {
    $file_loc = $CFG['dir']['ppl'] . $img;

    if ( file_exists( $file_loc ) ) {
      adminlog( 'img|set|' . $img );
      return '<img src="' . $file_loc . '" title="' . $img . '">' . "\n";

    } else {
      return get_random_figure();
    }
      
  }
}

// Gets a random figure from those available on disk.
// DONE
function get_random_figure() {
  global $CFG;

  // Scan the folder for appropriate images.
  $figures = array();
  if ( $fh = opendir( $CFG['dir']['ppl'] ) ) {
    while ( false !== ( $entry = readdir( $fh ) ) ) {
      if ( $entry != '.' && $entry != '..' && substr( $entry, 0, 3 ) == 'fig' ) {
        $figures[] = $entry;
      }
    }
    closedir( $fh );
  }

  if ( $figures ) {

    asort( $figures );

    $figure_num = rand( 0, count( $figures ) - 1 );
    $file_loc = $CFG['dir']['ppl'] . $figures[ $figure_num ];

    if ( file_exists( $file_loc ) ) {
      adminlog( 'img|rnd|' . $figures[ $figure_num ] );
      return '<img src="' . $file_loc . '" title="' . substr( $figures[ $figure_num ], 4 ) . '">' . "\n";

    } else {
      adminlog( 'img|err|' . $file_loc );
      return '<p class="error">Image error.</p>' . "\n";
    }

  } else {
    adminlog( 'img|err|no images' );
    return '<p class="error">Couldn\'t find any images.</p>' . "\n";
  }
}


// Make a scrolly thing with SimplePie RSS feed parsing.
// DONE
function get_scroller() {

  $feedurl = get_config( 'rssfeed' );

  require_once( 'simplepie_1.3.1.mini.php' );

  $feed = new SimplePie();
  $feed->set_feed_url( $feedurl );
  $feed->enable_cache();
  $feed->init();

  // The HEIGHT and the WIDTH should match the same details in style.css/#scroller
  // TODO: We're still using a marquee tag? In 2015??
  $build = '<marquee scrollamount="3" height="45" width="1278">';

  // Add an image to the start of the feed.
  if ( preg_match( '/newsrss.bbc.co.uk/', $feedurl ) ) {
    $build .= '<img src="http://static.bbci.co.uk/frameworks/barlesque/2.83.4/orb/4/img/bbc-blocks-light.png" height="25" style="vertical-align: middle;"> ';

  } else if ( preg_match( '/slashdot/', $feedurl ) ) {
    $build .= '<img src="http://farm3.static.flickr.com/2302/2454530894_f2ca265bde_o.jpg" height="30" style="vertical-align: middle;"> ';
  }

  foreach ( $feed->get_items() as $item ) {
    // Check for non-empty descriptions.
    if ( $item->get_description() != '' ) {
      $build .= $item->get_title() . ': <em>' . $item->get_description() . '</em> &rarr; ' . "\n";
    }
  }

  $build .= "</marquee>\n";
  return $build;
}


// Get this page's background image.
// DONE
function get_page_background_image() {

  global $CFG, $DB;

  $sql = "SELECT background FROM pages WHERE id = '" . $CFG['page'] . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    error( 'Can\'t get this page\'s background image.' );
    return false;

  } else {
    $row = $res->fetch_assoc();
    return $row['background'];
  }
}


// Get this status' type, depending on what the current status is.
// DONE
function get_status_img() {

  global $CFG, $DB;

  $sql = "SELECT type FROM status WHERE id = '" . $CFG['status'] . "' LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    return '<p class="error">Sorry, no status type image.</p>';

  } else {
    $row = $res->fetch_assoc();
    return '<img src="' . $CFG['dir']['status'] . $row['type'] . '.png" width="60">' . "\n";
  }
}

// Get this status' text, depending on what the current status is.
// DONE
function get_status_txt() {

  global $CFG, $DB;

  $sql = "SELECT description FROM status WHERE id = '" . $CFG['status'] . "';";
  $res = $DB->query( $sql);

  if ( $res->num_rows == 0 ) {
    return '<p class="error">Sorry, no status.</p>';

  } else {
    $row = $res->fetch_row();
    return '<p>' . $row[0] . '</p>' . "\n";
  }
}


// Get 'n' next events.
// DONE
function get_events( $num = 3 ) {

  global $CFG, $DB;

  $now = time();
  $today = date( 'Y', $now ) . '-' . date( 'm', $now ) . '-' . date( 'd', $now );

  $sql = "SELECT id, start, text FROM events WHERE start >= '" . $today . "' AND hidden = 0 ORDER BY start ASC, id ASC LIMIT " . $num . ";";
  $res = $DB->query( $sql );

  if ( !$res || $res->num_rows == 0 ) {
    return '<p class="error">Sorry, no events.</p>';
  }

  $build = "<ul>\n";

  while ( $row = $res->fetch_assoc() ) {
    $db_date = $row['start'];
    $disp_date = date( $CFG['time']['short'], mktime( 0, 0, 0, substr($db_date, 5, 2), substr($db_date, 8, 2), substr($db_date, 0, 4) ));

    $build .= '<li>' . $disp_date . ': <em>' . $row['text'] . "</em></li>\n";
  }

  $build .= "</ul>\n";
  return $build;

}
