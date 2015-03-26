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


// Moved the pageload checks for $_GET and $_POST data to this file.
require_once( 'functions-admin-processing.inc.php' );

adminlog('manage');

?><!DOCTYPE html>
<html lang="en">
<head>
<?php

echo admin_header( $CFG['lang']['title'] );

?>
  <meta http-equiv="refresh" content="300">

  <link href="bower_components/trumbowyg/dist/ui/trumbowyg.min.css" rel="stylesheet">
  <link href="bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
  <link href="bower_components/hover/css/hover-min.css" rel="stylesheet">
  <link href="bower_components/anchor-js/anchor.css" rel="stylesheet">

  <style type="text/css">
  /* Using the 'ts-' namespace.
  /* Green. */
  .ts-check { color: #0b0; }
  /* Red. */
  .ts-cross, .ts-danger { color: #d00; }
  /* Light blue. */
  .ts-edit, .ts-info { color: #38b; }
  /* Grey. */
  .ts-default { color: #777; }
  /* Light grey. */
  .ts-hide, .ts-scheduled { color: #bbb; }
  /* Active (orange). */
  .ts-scheduled-active { color: #f90; }
  /* Dark grey. */
  .ts-show { color: #444; }

  #showstopper_counter { display: inline; }

  h1, h2, h3 {
    font-family: 'Indie Flower', cursive;
    color: #4f2170;
  }

  h1.fancy_title {
    font-size: 5em;
  }
  .char1, .char3, .char5, .char7, .char9, .char11, .char13, .char15, .char17, .char19, .char21, .char23, .char25, .char27 {
    color: #6f4190;
    /*background-color: rgba(79, 33, 112, 0.2);*/
  }
  .char2, .char4, .char6, .char8, .char10, .char12, .char14, .char16, .char18, .char20, .char22, .char24, .char26, .char28 {
    /*background-color: rgba(111, 65, 144, 0.2);*/
  }
  /*
  h1.fancy_title span {
    padding: 1px;
    /*border: 1px solid #fff;*/
  }
  */

  html.chrome {
    background: blue !important;
  }

  p.status-type-title { margin-bottom: 0; }

  </style>

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

          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Add new <?php echo get_icon( 'help' ); ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="event.php">Event</a></li>
              <li><a href="factoid.php">Factoid</a></li>
              <li><a href="page.php">Page</a></li>
              <li class="divider"></li>
              <li><a href="#">Status</a></li>
              <li><a href="#">Figure</a></li>
            </ul>
          </li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Help <?php echo get_icon( 'help' ); ?> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">About (modal)</a></li>
              <li><a href="help.php">Edit Help text</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Development stuff</li>
              <li><a target="_blank" href="http://getbootstrap.com/css/">Get Bootstrap</a></li>
              <li><a target="_blank" href="http://fortawesome.github.io/Font-Awesome/icons/">Font Awesome icons</a></li>
              <li class="divider"></li>
              <li class="dropdown-header">Site Operations</li>
              <!-- <li><a href="<?php echo $CFG['adminpage']; ?>?action=truncate_log" onclick="return confirm('Are you sure?');">Truncate the log table <i class="fa fa-exclamation-circle ts-warning"></i></a></li> -->
              <li><a id="truncate_log" href="#">Truncate the log table <?php echo get_icon( 'danger' ); ?></a></li>
              <!-- <li><a href="<?php echo $CFG['adminpage']; ?>?action=full_reset" onclick="return confirm('Are you sure you want to reset everything?');">Reset everything! <i class="fa fa-exclamation-circle ts-warning"></i></a></li> -->
              <li><a id="full_reset" href="#">Reset everything! <?php echo get_icon( 'danger' ); ?></a></li>
              <li><a href="install.php">Reinstall <?php echo get_icon( 'danger' ); ?></a></li>
            </ul>
          </li>

        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="index.php" target="_blank">See the main screen <?php echo get_icon( 'external' ); ?></a></li>
          <li><a href="<?php echo $CFG['adminpage']; ?>?action=refresh_main">Refresh main screen <?php echo get_icon( 'refresh' ); ?></a></li>
          <li><a href="<?php echo $CFG['adminpage']; ?>?action=logout">Log out <?php echo get_icon( 'logout' ); ?></a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav><!-- END fixed navbar. -->

  <div class="container">

<?php

echo display_alerts();

?>

    <!--
    <div class="jumbotron">
      <h1><?php echo $CFG['lang']['title']; ?></h1>
      <p>Rocket science, this ain't.</p>
      <p>
        <a class="btn btn-lg btn-primary" href="../../components/#navbar" role="button">View navbar docs &raquo;</a>
      </p>
    </div --><!-- END jumbotron. -->

    <!-- Row zero! -->
    <div class="row">
      <div class="col-md-12">
        <h1 class="fancy_title"><?php echo $CFG['lang']['longtitle']; ?></h1>
      </div>
    </div>

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
        <h2>Page Type <small><a href="#" data-toggle="modal" data-target="#pagetype-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>

<?php

echo default_page_warning_page();

echo get_page_background_thumb();

?>
          <p>Change the page type:</p>
<?php

echo make_page_change_menu();

?>
        <a class="btn btn-primary btn-block" href="page.php" role="button">Add a new page</a>

      </div>
      <div class="col-md-4">
        <h2>Current Status <small><a href="#" data-toggle="modal" data-target="#status-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>
<?php

echo default_page_warning_status();

echo default_status_warning();

?>
        <p>Change status to:</p>
<?php

echo make_status_change_menu();

?>
        <a class="btn btn-danger btn-block" href="status.php" role="button" disabled="disabled">Add a new status</a>

      </div>

      <div class="col-md-4">
        <h2>Events <small><a href="#" data-toggle="modal" data-target="#events-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>
<?php

echo no_unhidden_events_warning();

?>
        <p>All future events are listed here. Events which have passed are not shown.</p>
        <p>We have <?php echo count_rows( 'events' ); ?> events (<?php echo count_rows( 'events', 'hidden = 0' ); ?> visible, <?php echo count_rows( 'events', 'hidden = 1' ); ?> hidden).</p>
        <p><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=event_hide_all"><?php echo get_icon( 'hide', 'Hide all events!' ); ?> Hide all events</a> or <a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=event_show_all"><?php echo get_icon( 'show', 'Show all events!' ); ?> show all events</a>.</p>
        <p>Click <?php echo get_icon( 'edit', 'Edit' ); ?> to edit, <?php echo get_icon( 'hide', 'Hide' ); ?> to hide, <?php echo get_icon( 'show', 'Show' ); ?> to show, and <?php echo get_icon( 'cross', 'Delete' ); ?> to delete an event.</p>
<?php

echo make_events_menu();

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
      <div class="col-md-4">
        <h2>Showstopper Text <small><a href="#" data-toggle="modal" data-target="#showstopper-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>

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

      <div class="col-md-4">
        <h2>RSS Feed <small><a href="#" data-toggle="modal" data-target="#rssfeed-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>

        <p>Paste in a valid URL of an RSS feed, or choose from the presets, below.</p>

        <form action="<?php echo $CFG['adminpage']; ?>" method="post">
          <input type="hidden" name="action" value="rssfeed_url_edit">

          <div class="form-group">
            <label for="rssfeed_url">RSS feed URL</label>
            <input type="text" class="form-control" id="rssfeed_url" name="rssfeed_url" value="<?php echo get_config('rssfeed'); ?>"placeholder="Enter RSS feed URL..." aria-describedby="rssfeed_url_help">
            <span id="rssfeed_url_help" class="help-block">Enter the full URL of the RSS feed you want to show.</span>
          </div>

          <button type="submit" class="btn btn-info">Update</button>
        </form>

        <h3>Choose a preset <small><a href="#" data-toggle="modal" data-target="#rssfeedpreset-modal"><?php echo get_icon( 'help' ); ?></a></small></h3>
<?php

echo make_rss_preset_menu();

?>
        <a class="btn btn-danger btn-block" href="rss.php" role="button" disabled="disabled">Add a new RSS feed</a>

      </div>

      <div class="col-md-4">
        <h2>Refresh <small><a href="#" data-toggle="modal" data-target="#refresh-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>

        <p>Number of seconds between page refreshes. <!--This is not wholly necessary, as some pages poll the server for changes, but some pages are so
        simple that they are only an image, and therefore cannot poll.--></p>

        <form class="form-group" action="<?php echo $CFG['adminpage']; ?>" method="get">
          <input type="hidden" name="action" value="refresh_edit">

          <div class="form-group">
            <label for="refresh_seconds">Refresh</label>
            <div class="row">
              <div class="col-sm-6">
                <select class="form-control" name="refresh_seconds" id="refresh_seconds">
<?php

foreach ( $CFG['refresh'] as $secs => $desc ) {
  $out = '              <option value="' . $secs . '"';
  if ( get_config( 'refresh' ) == $secs ) {
    $out .= ' selected';
  }
  $out .= '>' . $desc . '</option>' . "\n";
  echo $out;
}

?>
                </select>

              </div>
            </div>
            <span id="refresh_seconds_help" class="help-block">The number of seconds after which a page will definitely reload. This is the 'global' setting and can be overridden by an individual page's settings.</span>
          </div>
          <button type="submit" class="btn btn-info">Update</button>

        </form>

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
        <h2>Factoids <small><a href="#" data-toggle="modal" data-target="#factoids-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>
<?php

echo no_unhidden_factoids_warning();

?>
        <p>We have <?php echo count_rows( 'factoids' ); ?> factoids (<?php echo count_rows( 'factoids', 'hidden = 0' ); ?> visible, <?php echo count_rows( 'factoids', 'hidden = 1' ); ?> hidden).</p>
        <p><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=factoid_hide_all"><?php echo get_icon( 'hide', 'Hide all Factoids!' ); ?> Hide all Factoids</a> or <a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=factoid_show_all"><?php echo get_icon( 'show', 'Show all Factoids!' ); ?> show all Factoids</a>.</p>
        <p>Click <?php echo get_icon( 'edit', 'Edit' ); ?> to edit, <?php echo get_icon( 'hide', 'Hide' ); ?> to hide, <?php echo get_icon( 'show', 'Show' ); ?> to show, and <?php echo get_icon( 'cross', 'Delete' ); ?> to delete a factoid.</p>
<?php

echo make_factoids_menu();

?>
        <a class="btn btn-primary" href="factoid.php" role="button">Add a new factoid</a>

      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <!-- Row five. -->
    <div class="row">
      <div class="col-md-12">
        <h2>Specific Person <small><a href="#" data-toggle="modal" data-target="#person-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>
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



                    <h2>Logs <small><a href="#" data-toggle="modal" data-target="#logs-modal"><?php echo get_icon( 'help' ); ?></a></small></h2>
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
<?php

echo footer_content();

?>
    </div>
  </footer>

<?php

echo admin_footer();

?>

  <script type="text/javascript" src="js/jquery.word-and-character-counter.min.js"></script>
  <script type="text/javascript" src="js/holder.min.js"></script>
  <script type="text/javascript" src="bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
  <script type="text/javascript" src="bower_components/letteringjs/jquery.lettering.js"></script>
  <script type="text/javascript" src="bower_components/browserdetection/src/browser-detection.js"></script>
  <!-- script type="text/javascript" src="bower_components/trumbowyg/dist/trumbowyg.min.js"></script -->
  <script type="text/javascript" src="bower_components/anchor-js/anchor.js"></script>

  <script type="text/javascript">
  $(document).ready(function(){

    $("#showstopper").counter({
      count:  'down',
      goal:   170,
      msg:    'characters left. ',
      append: false,
      target: '#showstopper_help'
    });

    /* Letteringjs */
    $(".fancy_title").lettering();

    //$('#showstopper').trumbowyg();

    addAnchors('h2, h3');

    /* Letteringjs */
    $(".fancy_title").lettering();

    // Initial SweetAlert code.
    document.querySelector('#truncate_log').onclick = function(){
      swal({
        title:              "Are you sure?",
        text:               "You will not be able to recover the log table unless you have a backup",
        type:               "warning",
        showCancelButton:   true,
        //confirmButtonColor: "#DD6B55",
        confirmButtonClass: "btn-danger",
        confirmButtonText:  "Yes, drop it like it's hot!",
        cancelButtonText:   "No, cancel",
        closeOnConfirm:     true,
        closeOnCancel:      false
      }, 
      function(isConfirm){
        if (isConfirm) {
          location.assign("<?php echo $CFG['adminpage']; ?>?action=truncate_log");
        } else {
          swal({
            title:  "Cancelled", 
            text:   "The log table is safe (for now)! :)", 
            type:   "error",
            timer:  2000
          });
        }
      });
    };

    document.querySelector('#full_reset').onclick = function(){
      swal({
        title:              "Are you sure?",
        text:               "Do you really want to reset all the settings back to their defaults?",
        type:               "warning",
        showCancelButton:   true,
        //confirmButtonColor: "#DD6B55",
        confirmButtonClass: "btn-danger",
        confirmButtonText:  "Yes, like a BOSS I do!",
        cancelButtonText:   "Oops, nope",
        closeOnConfirm:     true,
        closeOnCancel:      false
      }, 
      function(isConfirm){
        if (isConfirm) {
          location.assign("<?php echo $CFG['adminpage']; ?>?action=full_reset");
        } else {
          swal({
            title:  "Cancelled", 
            text:   "Ok, go configure it youself then.", 
            type:   "error",
            timer:  2000
          });
        }
      });
    };   

  });
  </script>

</body>
</html>
