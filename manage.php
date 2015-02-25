<?php

//phpinfo();

/**
 * The Screen - Admin 
 * Code:    Paul Vaughan
 * Feb 2010 to Feb 2015, with minimal development in between.
 */

// TODO: check to ensure there is precisely one row in 'pages' and 'status' tables and complain if otherwise.


require_once( 'functions.inc.php' );
require_once( 'functions-admin.inc.php' );

session_name( 'sdc-thescreen' );
session_start();

// Secure this page a little bit.
if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
    exit(0);
}

// Debugging.
if ( isset( $_POST ) && !empty( $_POST ) ) {
  echo '<p>POST:</p><pre>'; var_dump( $_POST ); echo '</pre>';
}
if ( isset( $_GET ) && !empty( $_GET ) ) {
  echo '<p>GET:</p><pre>'; var_dump( $_GET ); echo '</pre>';
}


/**
 * Before loading the page proper, check to see if any $_POST or $_GET are set, and deal with it.
 */

// Adding a new event.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'event_add' && isset( $_POST['event_date'] ) && !empty( $_POST['event_date'] ) && isset( $_POST['event_description'] ) && !empty( $_POST['event_description'] ) ) {
  if ( add_event( $_POST['event_date'], $_POST['event_description'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'The event &ldquo;' . $_POST['event_description'] . '&rdquo; was created successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'The event &ldquo;' . $_POST['event_description'] . '&rdquo; was not added for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Deleting an event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_del' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( del_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was deleted.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not deleted for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Restoring a deleted event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_restore' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( restore_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was restored successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not restored for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Changing the page.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'page_change' && isset( $_GET['page'] ) && !empty( $_GET['page'] ) && is_numeric( $_GET['page'] ) ) {
  if ( update_check( 'pages', $_GET['page'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was set successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was not set for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Changing the status.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'status_change' && isset( $_GET['status'] ) && !empty( $_GET['status'] ) && is_numeric( $_GET['status'] ) ) {
  if ( update_check( 'status', $_GET['status'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'The status &ldquo;' . get_title( 'status', $_GET['status'] ) . '&rdquo; was set successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'The status &ldquo;' . get_title( 'status', $_GET['status'] ) . '&rdquo; was not set for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Updating the showstopper text.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'showstopper_edit' && isset( $_POST['showstopper'] ) && !empty( $_POST['showstopper'] ) ) {
  if ( set_config( 'showstopper', $_POST['showstopper'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'Showstopper text &ldquo;' . $_POST['showstopper'] . '&rdquo; was updated successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'Showstopper text &ldquo;' . $_POST['showstopper'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Updating the RSS feed URL.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'rssfeed_url_edit' && isset( $_POST['rssfeed_url'] ) && !empty( $_POST['rssfeed_url'] ) ) {
  if ( set_config( 'rssfeed', $_POST['rssfeed_url'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'RSS feed URL &ldquo;' . $_POST['rssfeed_url'] . '&rdquo; was updated successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'RSS feed URL &ldquo;' . $_POST['rssfeed_url'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
}

// Updating the RSS feed URL from a preset.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'rssfeed_preset' && isset( $_GET['rssfeed_preset_url'] ) && !empty( $_GET['rssfeed_preset_url'] ) ) {
  if ( set_config( 'rssfeed', $_GET['rssfeed_preset_url'] ) ) {
    $_SESSION['alerts'] = array( 'success' => 'RSS feed preset &ldquo;' . $_GET['rssfeed_preset_url'] . '&rdquo; was updated successfully.' );
  } else {
    $_SESSION['alerts'] = array( 'danger' => 'RSS feed preset &ldquo;' . $_GET['rssfeed_preset_url'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $_SERVER["PHP_SELF"] );
  exit(0);
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
  <style type="text/css">
  body {
    padding-top: 70px;
  }
  .tick { color: #0b0; }
  .cross { color: #d00; }
  .edit { color: #337ab7; }
  .default { color: #777; }
  #showstopper_counter { display: inline; }
  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- link rel="stylesheet" type="text/css" href="css/style-admin.css" media="screen" -->

  <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

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
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#contact">Contact</a></li>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">Action</a></li>
              <li><a href="#">Another action</a></li>
              <li><a href="#">Something else here</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Nav header</li>
              <li><a href="#">Separated link</a></li>
              <li><a href="#">One more separated link</a></li>
            </ul>
          </li>
        </ul>
        <!-- button type="button" class="btn btn-danger navbar-btn navbar-right btn-sm">Log Out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></button -->
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>">Refresh <span class="glyphicon glyphicon-refresh" aria-hidden="true"></a></li>
          <li><a href="index.php" target="_blank">See the main screen <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
          <li><a href="logout.php">Log out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav><!-- END fixed navbar. -->

  <div class="container">

    <!-- Any alerts we have will pop up here. -->
    <!-- div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Dental Plan!</strong> Lisa needs braces.
    </div -->
<?php

if ( isset( $_SESSION['alerts'] ) ) {
  foreach ( $_SESSION['alerts'] as $alert => $text ) {
    echo '    <div class="alert alert-' . $alert . ' alert-dismissible" role="alert">' . "\n";
    echo '      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . "\n";
    echo '      ' . $text . "\n";
    echo '    </div>' . "\n";
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
        <p>You last logged in at <?php echo date( 'c', $_SESSION['loggedin.time'] ); ?>.</p>
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
        <h2>Page Type</h2>

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
        <h2>Current Status</h2>
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
        <h2>Events</h2>
        <p>All future events (events which have passed are not shown).</p>
        <p>Delete an event by clicking the <span class="glyphicon glyphicon-remove cross" aria-hidden="true"></span>. 
        The event will become greyed out, and can be un-deleted by clicking the <span class="glyphicon glyphicon-ok tick" aria-hidden="true"></span>. 
        Edit an event by clicking the <span class="glyphicon glyphicon-pencil edit" aria-hidden="true"></span>.</p>
<?php

echo make_events_menu();

?>
        <hr>
        <h3>Add a new event</h3>
        <p>Use the basic form, below, to specify an date and description for a new event.</p>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <input type="hidden" name="action" value="event_add">

          <div class="form-group">
            <label for="event_date">Event date</label>
            <input type="date" class="form-control" id="event_date" name="event_date" placeholder="Enter date" aria-describedby="event_date_help">
            <span id="event_date_help" class="help-block">Use the 'date picker' tools to the right (when you hover over the text box).</span>
          </div>

          <div class="form-group">
            <label for="event_description">Event description</label>
            <input type="text" class="form-control" id="event_description" name="event_description" placeholder="Enter event details" aria-describedby="event_description_help">
            <span id="event_description_help" class="help-block">Be concise! We don't have much space to work with.</span>
          </div>

          <button type="submit" class="btn btn-info">Submit</button>
        </form>

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

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
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

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
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
            <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml">BBC Technology, UK Edition</a> (This is the default)</li>
            <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/uk/rss.xml">BBC UK, UK Edition</a></li>
            <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/rss.xml">BBC England, UK Edition</a></li>
            <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/sci/tech/rss.xml">BBC Science &amp; Environment, UK Edition</a></li>
            <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=rssfeed_preset&rssfeed_preset_url=http://rss.slashdot.org/Slashdot/slashdot">Slashdot: News for nerds, stuff that matters</a></li>
            <li><a href="<?php echo $_SERVER["PHP_SELF"]; ?>?action=rssfeed_preset&rssfeed_preset_url=http://news.southdevon.ac.uk/items.atom?body=txt">South Devon College News</a></li>
        </ul>

      </div>

    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>























  </div> <!-- /container -->























        <table>
            <tr>
                <!-- refresh statoids -->
                <td>
                    <h2>Refresh Stats</h2>
                    <p>Stats last refreshed <?php echo get_config('statoids_upd'); ?>. This happens automatically, randomly, throughout the day. 
                    <a href="statoids_make.php">Click here</a> to refresh the statistics manually. </p>
                </td>
            </tr>
            <tr>
                <!-- status stuff -->
                <td id="status">
                </td>

                <!-- event stuff -->
                <td id="events">
                </td>
            </tr>
            <tr>

                <!-- refresh stuff -->
                <td>
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
                </td>

            </tr>
            <tr>
                <!-- stats -->
                <td rowspan="2">
                    <h2>Stats</h2>
                    <p>Change the details and click the update button:</p>
                    <table id="stats">
                        <?php echo get_stats_form(); ?>
                    </table>
                </td>
            </tr>
            <tr>
                <!-- logs -->
                <td id="showstopper">
                    <h2>Logs</h2>
                    <p>Last few log entries.</p>
                    <?php echo get_last_log(12); ?>
                </td>
            </tr>
            <tr>
                <!-- specific image -->
                <td>
                    <h2>Specific Figure</h2>
                    <p>Choose a specific figure from the list below, or 'no' for a random figure.</p>
                    <form action="specific_fig_edit.php" method="get">
                        <select name="figure">
                            <?php
                            get_special_figure_list();
                            ?>
                        </select>
                        <input type="submit" value="Set">
                    </form>
                </td>
                <!-- blank -->
                <td>
                </td>
            </tr>
        </table>

  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

  <script type="text/javascript" src="js/jquery.word-and-character-counter.min.js"></script>

  <!-- script type="text/javascript" src="jquery.jgrowl.js"></script -->

  <script type="text/javascript">
  $(document).ready(function(){

    window.setTimeout(function() { 
      //$(".alert-success").alert('close'); 
      $(".alert-success").fadeTo(800, 0).slideUp(500);
    }, 5000);

    $("#showstopper").counter({
      count:  'down',
      goal:   170,
      msg:    'characters left. ',
      append: false,
      target: '#showstopper_help'
    });

  //  $('#datepicker').datepicker({ 
  //    dateFormat: 'yy-mm-dd', 
  //    firstDay: 1, 
  //    yearRange: '2010:2011', 
  //    numberOfMonths: 2
  //  });

<?php
/*
if ( !isset( $_GET['msg'] ) ) {
  echo '$.jGrowl("Remember: this is live!", { life: 2000 });';

} else {
  if ($_GET['msg'] == 'event_add_success' ) {
    $msg='Successfully added an event.';
  } else if ($_GET['msg'] == 'event_add_fail' ) { 
    $msg='Failed to add an event.';
  } else if ($_GET['msg'] == 'event_del_success' ) {
    $msg='Successfully deleted an event.'; 
  } else if ($_GET['msg'] == 'event_del_fail' ) { 
    $msg='Failed to delete an event.';
  } else if ($_GET['msg'] == 'refresh_edit_success' ) { 
    $msg='Successfully edited the refresh period.';
  } else if ($_GET['msg'] == 'refresh_edit_fail' ) { 
    $msg='Failed to edit the refresh period.';
  } else if ($_GET['msg'] == 'status_edit_success' ) { 
    $msg='Successfully changed the status.';
  } else if ($_GET['msg'] == 'status_edit_fail' ) { 
    $msg='Failed to change the status.';
  } else if ($_GET['msg'] == 'rssfeed_edit_success' ) { 
    $msg='Successfully changed the RSS feed.';
  } else if ($_GET['msg'] == 'rssfeed_edit_fail' ) { 
    $msg='Failed to change the RSS feed.';
  } else if ($_GET['msg'] == 'showstopper_edit_success' ) { 
    $msg='Successfully changed the Showstopper text.';
  } else if ($_GET['msg'] == 'showstopper_edit_fail' ) { 
    $msg='Failed to change the Showstopper text.';
  } else if ($_GET['msg'] == 'stat_edit_success' ) { 
    $msg='Successfully changed the stat text.';
  } else if ($_GET['msg'] == 'stat_edit_fail' ) { 
    $msg='Failed to change the stat text.';
  } else if ($_GET['msg'] == 'statoids_make' ) { 
    $msg='Made combined stats/factiods (statoids) table.';
  } else if ($_GET['msg'] == 'page_edit_success' ) { 
    $msg='Successfully changed the page.';
  } else if ($_GET['msg'] == 'page_edit_fail' ) { 
    $msg='Failed to change the page.';
  } else if ($_GET['msg'] == 'figure_edit_success' ) { 
    $msg='Successfully changed the specific figure.';
  } else if ($_GET['msg'] == 'figure_edit_fail' ) { 
    $msg='Failed to change the specific figure.';
  }

  if ($msg != '') { 
    echo '$.jGrowl("'.$msg.'", { life: 4000 });'."\n";
  }
}
*/
?>

  });
  </script>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
