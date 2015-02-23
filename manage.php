<?php

/**
 * The Screen - Admin 
 * Code:    Paul Vaughan
 * Feb 2010 to Feb 2015, with minimal development in between.
 */

require_once( 'functions.inc.php' );
session_name( 'sdc-thescreen' );
session_start();

// Secure this page a little bit.
if ( !isset( $_SESSION['loggedin'] ) ) {
    header( 'location: login.php' );
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
  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="jquery-ui-1.7.2.custom.css" rel="stylesheet">
  <link href="jquery.jgrowl.css" rel="stylesheet">

  <!-- link rel="stylesheet" type="text/css" href="css/style-admin.css" media="screen" -->

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
        <button type="button" class="btn btn-danger navbar-btn navbar-right btn-sm">Log Out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></button>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="manage.php">Refresh <span class="glyphicon glyphicon-refresh" aria-hidden="true"></a></li>
          <li><a href="index.php" target="_blank">See the main screen <span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a></li>
          <!-- li class="active"><a href="logout.php">Log out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li -->
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav><!-- END fixed navbar. -->

  <div class="container">

    <!-- Any alerts we have will pop up here. -->
    <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <strong>Dental Plan!</strong> Lisa needs braces.
    </div>

    <div class="jumbotron">
      <h1><?php echo $CFG['lang']['title']; ?></h1>
      <p>Rocket science, this ain't.</p>
      <!-- p>
        <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
      </p -->
    </div><!-- END jumbotron. -->

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
        <p>Change the page type:</p>
<?php
echo make_page_change_menu();
?>
      </div>
      <div class="col-md-4">
        <h2>Current status</h2>
        <p>Only valid if the page is set to <strong>Default</strong>. Change status to:</p>
<?php
echo make_status_change_menu();
?>
      </div>
      <div class="col-md-4">
        <h2>Events</h2>
        <p>All future events (events which have passed are not shown).</p>
        <p>Delete an event by clicking the red cross. The event will become greyed out, and can be un-deleted by clicking the green tick.</p>
<?php echo get_events( 10, true ); ?>
        <hr>
        <h4>Add new event:</h4>
        <form action="event_add.php" method="get">
          Date:
          <input type="text" name="date" id="datepicker">
          <br> Details:
          <input type="text" name="text" size="50" maxlength="255">
          <input type="submit" value="Add event">
        </form>
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

                <!-- RSS feed stuff -->
                <td>
                    <h2>RSS Feed</h2>
                    <p>Location (URL) of the RSS feed for the scroller.</p>
                    <form action="rssfeed_edit.php" method="get">
                        RSS Feed location (URL):
                        <input type="text" value="<?php echo get_config('rssfeed'); ?>" name="rssfeed" size="50" maxlength="100">
                        <input type="submit" value="Set">
                    </form>
                    <ul>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml">BBC Technology, UK Edition</a> (Default)</li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/uk/rss.xml">BBC UK, UK Edition</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/rss.xml">BBC England, UK Edition</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/sci/tech/rss.xml">BBC Science &amp; Environment, UK Edition</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://rss.slashdot.org/Slashdot/slashdot">Slashdot: News for nerds, stuff that matters</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://news.southdevon.ac.uk/items.atom?body=txt">South Devon College News</a></li>
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
                <!-- showstopper -->
                <td id="showstopper">
                    <h2>Showstopper Text</h2>
                    <p>Text required for the 'showstopper' screen. Don't use any formatting. Will appear in UPPERCASE. You have *about* 170 characters maximum.</p>
                    <form action="showstopper_edit.php" method="get">
                        <textarea id="showstopper_textbox" name="showstopper" cols="50" rows="4"><?php echo get_config('showstopper'); ?></textarea>
                        <input type="submit" value="Change">
                    </form>
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

  <!-- script type="text/javascript" src="jquery-1.4.2.js"></script -->
  <script type="text/javascript" src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/bootstrap.min.js"></script>

  <!-- script type="text/javascript" src="jquery-ui-1.7.2.custom.min.js"></script -->
  <!-- script type="text/javascript" src="jquery.jgrowl.js"></script -->
  <script type="text/javascript" src="jquery.counter-1.0.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){

    window.setTimeout(function() { 
      //$(".alert-success").alert('close'); 
      $(".alert-success").fadeTo(800, 0).slideUp(500);
    }, 5000);

  //  $('#datepicker').datepicker({ 
  //    dateFormat: 'yy-mm-dd', 
  //    firstDay: 1, 
  //    yearRange: '2010:2011', 
  //    numberOfMonths: 2
  //  });
    $("#showstopper_textbox").counter();

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
