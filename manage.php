<?php

/**
 * The Screen - Admin
 * Code:    Paul Vaughan
 * Feb 2010 to Feb 2015, with minimal development in between.
 */

// TODO: check to ensure there is precisely one row in 'pages' and 'status' tables and complain if otherwise.
// TODO: check for no factoids and no shown factoids and warn if otherwise.
// TODO: jQuery jGrowl: https://github.com/stanlemon/jGrowl

session_name( 'sdc-thescreen' );
session_start();

// Secure this page a little bit.
if ( !isset( $_SESSION['loggedin'] ) ) {
  header( 'location: login.php' );
  exit(0);
}
$_SESSION['last_activity'] = time();

require_once( 'functions.inc.php' );
require_once( 'functions-admin.inc.php' );

// Set the name of the admin page, for use in other add/edit pages.
// TODO: this, better.
$CFG['adminpage']       = 'manage.php';

// Minutes before the admin screen times out.
$CFG['admintimeout']    = 5;


// Debugging.
if ( isset( $_POST ) && !empty( $_POST ) ) {
  echo '<p>POST:</p><pre>'; var_dump( $_POST ); echo '</pre>';
}
if ( isset( $_GET ) && !empty( $_GET ) ) {
  echo '<p>GET:</p><pre>'; var_dump( $_GET ); echo '</pre>';
}
//if ( isset( $_SESSION['alerts'] ) && !empty( $_SESSION['alerts'] ) ) {
//  echo '<p>SESSION[\'alerts\']:</p><pre>'; var_dump( $_SESSION['alerts'] ); echo '</pre>';
//}

/**
 * Before loading the page proper, check to see if any $_POST or $_GET are set, and deal with it.
 */

/**
 * Page changing.
 */

// Changing the page.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'page_change' && isset( $_GET['page'] ) && !empty( $_GET['page'] ) && is_numeric( $_GET['page'] ) ) {
  if ( update_check( 'pages', $_GET['page'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was set successfully.' );
    // TODO: Proposed change:
    //$_SESSION['alerts'][] = array( 'type' => 'success', 'text' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was set successfully.', 'timeout' => 3000 );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was not set for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Changing the status.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'status_change' && isset( $_GET['status'] ) && !empty( $_GET['status'] ) && is_numeric( $_GET['status'] ) ) {
  if ( update_check( 'status', $_GET['status'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The status &ldquo;' . get_title( 'status', $_GET['status'] ) . '&rdquo; was set successfully.' );
    // If the showstopper page is set when the showstopper text is changed, update the page.
    if ( get_config( 'page' ) == get_id( 'pages', 'standard' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The status &ldquo;' . get_title( 'status', $_GET['status'] ) . '&rdquo; was not set for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

/*
// Adding a new event.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'event_add' && isset( $_POST['event_date'] ) && !empty( $_POST['event_date'] ) && isset( $_POST['event_description'] ) && !empty( $_POST['event_description'] ) ) {
  if ( add_event( $_POST['event_date'], $_POST['event_description'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event &ldquo;' . $_POST['event_description'] . '&rdquo; was created successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event &ldquo;' . $_POST['event_description'] . '&rdquo; was not added for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}
*/

// Deleting an event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_del' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( delete_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was deleted.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not deleted for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding an event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_hide' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( hide_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was hidden successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Showing a hidden event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_show' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( show_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was un-hidden successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Updating the showstopper text.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'showstopper_edit' && isset( $_POST['showstopper'] ) && !empty( $_POST['showstopper'] ) ) {
  if ( set_config( 'showstopper', $_POST['showstopper'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'Showstopper text &ldquo;' . $_POST['showstopper'] . '&rdquo; was updated successfully.' );
    // If the showstopper page is set when the showstopper text is changed, update the page.
    if ( get_config( 'page' ) == get_id( 'pages', 'showstopper' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'Showstopper text &ldquo;' . $_POST['showstopper'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Updating the RSS feed URL.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'rssfeed_url_edit' && isset( $_POST['rssfeed_url'] ) && !empty( $_POST['rssfeed_url'] ) ) {
  if ( set_config( 'rssfeed', $_POST['rssfeed_url'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'RSS feed URL &ldquo;' . $_POST['rssfeed_url'] . '&rdquo; was updated successfully.' );
    // If the default page is set when the rss feed URL is changed, update the page.
    //if ( get_config( 'page' ) == get_default( 'pages' ) ) {
    if ( get_config( 'page' ) == get_id( 'pages', 'standard' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'RSS feed URL &ldquo;' . $_POST['rssfeed_url'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Updating the RSS feed URL from a preset.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'rssfeed_preset' && isset( $_GET['rssfeed_preset_url'] ) && !empty( $_GET['rssfeed_preset_url'] ) ) {
  if ( set_config( 'rssfeed', $_GET['rssfeed_preset_url'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'RSS feed preset &ldquo;' . $_GET['rssfeed_preset_url'] . '&rdquo; was updated successfully.' );
    // If the default page is set when the rss feed URL preset is changed, update the page.
    if ( get_config( 'page' ) == get_default( 'pages' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'RSS feed preset &ldquo;' . $_GET['rssfeed_preset_url'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Updating the random / specific figure.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'figure_change' && isset( $_GET['figure_filename'] ) && !empty( $_GET['figure_filename'] ) && isset( $_GET['figure_name'] ) && !empty( $_GET['figure_name'] ) ) {
  if ( set_config( 'specific_fig', $_GET['figure_filename'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'Figure &ldquo;' . $_GET['figure_name'] . '&rdquo; was updated successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'Figure &ldquo;' . $_GET['figure_name'] . '&rdquo; (' . $_GET['figure_filename'] . ') was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Forcing a page refresh.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'refresh_main' ) {
  if ( set_change() ) {
    $_SESSION['alerts'][] = array( 'success' => 'The main page will refresh now.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The main page could not be set to refresh for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Forcing the defaults into the database (a 'full reset').
if ( isset( $_GET['action'] ) && $_GET['action'] == 'full_reset' ) {
  if ( $tmp = force_default() ) {
    $_SESSION['alerts'][] = array( 'success' => $tmp );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The full reset didn\'t work for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Truncating the log table.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'truncate_log' ) {
  if ( truncate_log() ) {
    $_SESSION['alerts'][] = array( 'success' => 'The log table was truncated successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The log table was not truncated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

/**
 * Factoids.
 */

// Showing (un-hiding) a factoid.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_show' && isset( $_GET['factoid_id'] ) && !empty( $_GET['factoid_id'] ) && is_numeric( $_GET['factoid_id'] ) ) {
  if ( factoid_show( $_GET['factoid_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was un-hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was not un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding a factoid.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_hide' && isset( $_GET['factoid_id'] ) && !empty( $_GET['factoid_id'] ) && is_numeric( $_GET['factoid_id'] ) ) {
  if ( factoid_hide( $_GET['factoid_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was not hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Showing all factoids.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_show_all' ) {
  if ( factoid_show_all() ) {
    $_SESSION['alerts'][] = array( 'success' => 'All factoids have been un-hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'All factoids have not been un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding all factoids.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_hide_all' ) {
  if ( factoid_hide_all() ) {
    $_SESSION['alerts'][] = array( 'success' => 'All factoids have been hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'All factoids have not been hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

/**
 * Logging out.
 */

// Logging out.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ) {
  logout();
}

/**
 * Some database checks and warnings if something's not right.
 */

// No factoids.
if ( count_rows( 'factoids' ) == 0 ) {
  $_SESSION['alerts'][] = array( 'danger' => 'There are no factoids in the database. <a href="#" class="alert-link">Add one?</a>' );
} else {
  // No un-hidden factoids.
  if ( count_rows( 'factoids', 'hidden = 0' ) == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'There are no un-hidden factoids in the database. You might want to show at least one.' );
  }
}

// No events.
if ( count_rows( 'events' ) == 0 ) {
  $_SESSION['alerts'][] = array( 'danger' => 'There are no events in the database. <a href="#" class="alert-link">Add one?</a>' );
}


adminlog('manage');

?><!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="A content presentation system for SDC Computer Services.">
  <meta name="author" content="Mostly Paul Vaughan.">

  <title><?php echo $CFG['lang']['title']; ?></title>

  <meta http-equiv="refresh" content="300">

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/bs-docs.css" rel="stylesheet">
  <style type="text/css">
  body {
    padding-top: 70px;
  }
  /* Green. */
  .tick { color: #0b0; }
  /* Red. */
  .cross, .factoid-delete, .event-delete, .ts-warning { color: #d00; }
  /* Light blue. */
  .edit, .factoid-edit, .event-edit, .ts-info { color: #38b; }
  /* Grey. */
  .default { color: #777; }
  /* Light grey. */
  .factoid-hide, .event-hide, .factoid-hide { color: #bbb; }
  /* Dark grey. */
  .factoid-show, .event-show, .factoid-show  { color: #333; }

  #showstopper_counter { display: inline; }
  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
  <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" rel="stylesheet">

</head>
<body>

  <!-- Fixed navbar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><?php echo $CFG['lang']['title']; ?></a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <!-- li class="active"><a href="#">Home</a></li -->
          <li><a href="<?php echo $CFG['adminpage']; ?>">Reload <span class="glyphicon glyphicon-refresh" aria-hidden="true"></a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Help <i class="fa fa-question-circle"></i> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">About (modal)</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Development stuff</li>
              <li><a target="_blank" href="http://getbootstrap.com/css/">Get Bootstrap</a></li>
              <li><a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome icons</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Site Operations</li>
              <li><a href="<?php echo $CFG['adminpage']; ?>?action=truncate_log" onclick="return confirm('Are you sure?');">Truncate the log table <i class="fa fa-exclamation-circle ts-warning"></i></a></li>
              <li><a href="<?php echo $CFG['adminpage']; ?>?action=full_reset" onclick="return confirm('Are you sure you want to reset everything?');">Reset everything! <i class="fa fa-exclamation-circle ts-warning"></i></a></li>
            </ul>
          </li>
        </ul>
        <!-- button type="button" class="btn btn-danger navbar-btn navbar-right btn-sm">Log Out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></button -->
        <ul class="nav navbar-nav navbar-right">
          <li><a href="index.php" target="_blank">See the main screen <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
          <li><a href="<?php echo $CFG['adminpage']; ?>?action=refresh_main">Refresh main screen <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span></a></li>
          <li><a href="<?php echo $CFG['adminpage']; ?>?action=logout">Log out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav><!-- END fixed navbar. -->

  <div class="container">

<?php

// If there are any 'alerts' set, do cool stuff.
if ( isset( $_SESSION['alerts'] ) ) {

  foreach ( $_SESSION['alerts'] as $alert ) {
    foreach ( $alert as $alert_type => $alert_text ) {
      echo '    <div class="alert alert-' . $alert_type . ' alert-dismissible alert-' . $alert_type . '-fade" role="alert">' . "\n";
      echo '      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . "\n";
      echo '      ' . $alert_text . "\n";
      echo '    </div>' . "\n";
    }
  }
  unset( $_SESSION['alerts'] );
}

?>

    <!--
    <div class="jumbotron">
      <h1><?php echo $CFG['lang']['title']; ?></h1>
      <p>Rocket science, this ain't.</p>
      <p>
        <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
      </p>
    </div --><!-- END jumbotron. -->

    <!-- Row one. -->
    <div class="row">
      <div class="col-md-12">
        <h1>Last Logged In</h1>
        <p>You last logged in at <?php echo date( $CFG['time']['full'], $_SESSION['loggedin.time'] ); ?>. Your last activity was at <?php echo date( $CFG['time']['time'], $_SESSION['last_activity'] ); ?>. You will be automatically logged out after <?php echo $CFG['admintimeout']; ?> minutes of inactivity.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <!-- Row two. -->
    <div class="row">
      <div class="col-md-4">
        <h2>Page Type <small><a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-question-circle"></i></a></small></h2>

<?php

echo default_page_warning_page();

?>
          <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
              <p><img src="<?php echo $CFG['dir']['bg'] . '/' . get_page_background_image(); ?>" alt="Current page in use" class="img-thumbnail"></p>
            </div>
          </div>

          <p>Change the page type:</p>
<?php

echo make_page_change_menu();

?>
      </div>
      <div class="col-md-4">
        <h2>Current Status <small><a href="#"><i class="fa fa-question-circle"></i></a></small></h2>
<?php

echo default_page_warning_status();

echo default_status_warning();

?>
        <p>Change status to:</p>
<?php

echo make_status_change_menu();

?>
      </div>

      <div class="col-md-4">
        <h2>Events <small><a href="#"><i class="fa fa-question-circle"></i></a></small></h2>
        <p>All future events (events which have passed are not shown).</p>
        <p>Delete an event by clicking the <span class="glyphicon glyphicon-remove cross" aria-hidden="true"></span>. 
        The event will become greyed out, and can be un-deleted by clicking the <span class="glyphicon glyphicon-ok tick" aria-hidden="true"></span>. 
        Edit an event by clicking the <span class="glyphicon glyphicon-pencil edit" aria-hidden="true"></span>.</p>
<?php

echo make_events_menu( 15 );

?>
        <a class="btn btn-primary btn-block" href="event.php" role="button">Add a new event</a>

      </div>

    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <!-- Row three. -->
    <div class="row">
      <div class="col-md-6">
        <h2>Showstopper Text</h2>

<?php

echo showstopper_page_warning();

?>

        <p>Text for the Showstopper page. You have *about* 170 characters maximum.</p>

        <form action="<?php echo $CFG['adminpage']; ?>" method="post">
          <input type="hidden" name="action" value="showstopper_edit">

          <div class="form-group">
            <label for="showstopper">Showstopper text</label>
            <textarea id="showstopper" name="showstopper" class="form-control" rows="3" aria-describedby="showstopper_help"><?php echo get_config('showstopper'); ?></textarea>
            <span id="showstopper_help" class="help-block">Don't use any formatting. Will appear in UPPERCASE.</span>
          </div>

          <button type="submit" class="btn btn-info">Update</button>
        </form>

      </div>

      <div class="col-md-6">
        <h2>RSS Feed</h2>

        <p>URL of the RSS feed for the scroller.</p>

        <form action="<?php echo $CFG['adminpage']; ?>" method="post">
          <input type="hidden" name="action" value="rssfeed_url_edit">

          <div class="form-group">
            <label for="rssfeed_url">RSS feed URL</label>
            <input type="text" class="form-control" id="rssfeed_url" name="rssfeed_url" value="<?php echo get_config('rssfeed'); ?>"placeholder="Enter RSS feed URL..." aria-describedby="rssfeed_url_help">
            <span id="rssfeed_url_help" class="help-block">Enter the full URL of the RSS feed you want to show.</span>
          </div>

          <button type="submit" class="btn btn-info">Submit</button>
        </form>

        <h3>Choose a preset</h3>
        <ul>
            <li><a href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml">BBC Technology, UK Edition</a> (This is the default)</li>
            <li><a href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/uk/rss.xml">BBC UK, UK Edition</a></li>
            <li><a href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/rss.xml">BBC England, UK Edition</a></li>
            <li><a href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/sci/tech/rss.xml">BBC Science &amp; Environment, UK Edition</a></li>
            <li><a href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://rss.slashdot.org/Slashdot/slashdot">Slashdot: News for nerds, stuff that matters</a></li>
            <li><a href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://news.southdevon.ac.uk/items.atom?body=txt">South Devon College News</a></li>
        </ul>

      </div>

    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <!-- Row four. -->
    <div class="row">
      <div class="col-md-12">
        <h2>Specific Person</h2>
        <p>Click on the person whose face you want to show, or choose 'random' for a random person each time.</p>
      </div>
    </div>
    <div class="row">
<?php

echo get_figures_thumbnails();

?>
    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <!-- Row five. -->
    <div class="row">
      <div class="col-md-8">
        <h2>Factoids</h2>
        <p>We have <?php echo count_rows( 'factoids' ); ?> factoids (<?php echo count_rows( 'factoids', 'hidden = 0' ); ?> visible, <?php echo count_rows( 'factoids', 'hidden = 1' ); ?> hidden).</p>
        <p><a href="<?php echo $CFG['adminpage']; ?>?action=factoid_hide_all"><?php echo get_icon( 'hide', 'Hide all Factoids!' ); ?> Hide all Factoids</a> or <a href="<?php echo $CFG['adminpage']; ?>?action=factoid_show_all"><?php echo get_icon( 'show', 'Show all Factoids!' ); ?> show all Factoids</a>.</p>
        <p>Click <?php echo get_icon( 'edit', 'Edit' ); ?> to edit, <?php echo get_icon( 'hide', 'Hide' ); ?> to hide, <?php echo get_icon( 'show', 'Show' ); ?> to show, and <?php echo get_icon( 'cross', 'Delete' ); ?> to delete a factoid.</p>
<?php

echo make_factoids_menu();

?>
      </div>
      <div class="col-md-4">
        <h2>Refresh Rate (possibly)</h2>
        <p>Click on the person whose face you want to show, or choose 'random' for a random person each time.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>






































                    <h2>Refresh</h2>
                    <p>Number of seconds between page refreshes.</p>
                    <form action="refresh_edit.php" method="get">
                        Seconds:
                        <input type="text" value="<?php echo get_config('refresh'); ?>" name="seconds" size="4" maxlength="4">
                        <input type="submit" value="Set">
                    </form>
                    <ul>
                        <li>(Testing: <a href="refresh_edit.php?seconds=1">1</a> 
                        <a href="refresh_edit.php?seconds=5">5</a> 
                        <a href="refresh_edit.php?seconds=10">10</a> 
                        <a href="refresh_edit.php?seconds=15">15</a> 
                        <a href="refresh_edit.php?seconds=30">30</a> 
                        <a href="refresh_edit.php?seconds=45">45</a> 
                        <a href="refresh_edit.php?seconds=60">60</a> secs)</li>
                        <li><a href="refresh_edit.php?seconds=300">5 mins</a> (Default)</li>
                        <li><a href="refresh_edit.php?seconds=600">10 mins</a></li>
                        <li><a href="refresh_edit.php?seconds=900">15 mins</a></li>
                        <li><a href="refresh_edit.php?seconds=1800">30 mins</a></li>
                    </ul>
                    <hr>

                    <h2>Stats</h2>
                    <p>Change the details and click the update button:</p>
                    <table id="stats">
                        <?php //echo get_stats_form(); ?>
                    </table>
                    <hr>

                    <h2>Logs</h2>
                    <p>Last few log entries.</p>
                    <p>The log table has <?php echo count_rows( 'log' ); ?> rows.</p>
                    <?php echo get_last_log(15); ?>
                    <hr>



  </div> <!-- /container -->

<?php

echo help_modals();

?>



















  <footer class="bs-docs-footer" role="contentinfo">
    <div class="container">

      <p>Designed and built with all the love in the world by <a href="https://twitter.com/sdcmoodle" target="_blank">@sdcmoodle</a>.</p>
      <ul class="bs-docs-footer-links text-muted">
        <li>Currently v<?php echo $CFG['version']['build']; ?>, <?php echo $CFG['version']['date']; ?></li>
        <li>&middot;</li>
        <li>Built with <a href="http://getbootstrap.com">Bootstrap 3</a></li>
      </ul>
    </div>
  </footer>

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

  <script type="text/javascript" src="js/jquery.word-and-character-counter.min.js"></script>
  <script type="text/javascript" src="js/holder.min.js"></script>
  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script>

  <script type="text/javascript">
  $(document).ready(function(){

    setTimeout(function() {
      $(".alert-success-fade").fadeTo(800, 0).slideUp(500);
    }, 5000);

    setTimeout(function() {
      $(".alert-info-fade").fadeTo(800, 0).slideUp(500);
    }, 2000);

    $("#showstopper").counter({
      count:  'down',
      goal:   170,
      msg:    'characters left. ',
      append: false,
      target: '#showstopper_help'
    });

    // Will log you out after x milliseconds.
    // TODO: use setInterval here instead?
    setTimeout(function() {
      location.href = '<?php echo $CFG['adminpage']; ?>?action=logout';
    }, <?php echo $CFG['admintimeout']; ?> * 60 * 1000);

/*
    $.jGrowl.defaults.pool = 5;
    $.jGrowl.defaults.position = 'bottom-right';
    // Sample 1
    $.jGrowl("Hello world!");
    // Sample 2
    $.jGrowl("Stick this!", { sticky: true });
    // Sample 3
    $.jGrowl("A message with a header", { header: 'Important' });
    // Sample 4
    $.jGrowl("A message that will live a little longer.", { life: 10000 });
    // Sample 5
    $.jGrowl("A message with a beforeOpen callback and a different opening animation.", {
        beforeClose: function(e,m) {
            alert('About to close this notification!');
        },
        animateOpen: {
            height: 'show'
        }
    });
*/

  });
  </script>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
