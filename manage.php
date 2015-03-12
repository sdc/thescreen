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

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="A content presentation system for SDC Computer Services.">
  <meta name="author" content="Mostly Paul Vaughan.">

  <title><?php echo $CFG['lang']['title']; ?></title>

  <meta http-equiv="refresh" content="300">

  <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/bs-docs.css" rel="stylesheet">

  <link href="http://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet" type="text/css">

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

  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="bower_components/fontawesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.css" rel="stylesheet">
  <link href="bower_components/bootstrap-sweetalert/lib/sweet-alert.css" rel="stylesheet">
  <link href="bower_components/hover/css/hover-min.css" rel="stylesheet">

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
              <!-- <li><a href="<?php echo $CFG['adminpage']; ?>?action=truncate_log" onclick="return confirm('Are you sure?');">Truncate the log table <i class="fa fa-exclamation-circle ts-warning"></i></a></li> -->
              <li><a id="truncate_log" href="#">Truncate the log table <i class="fa fa-exclamation-circle ts-warning"></i></a></li>
              <!-- <li><a href="<?php echo $CFG['adminpage']; ?>?action=full_reset" onclick="return confirm('Are you sure you want to reset everything?');">Reset everything! <i class="fa fa-exclamation-circle ts-warning"></i></a></li> -->
              <li><a id="full_reset" href="#">Reset everything! <i class="fa fa-exclamation-circle ts-warning"></i></a></li>
            </ul>
          </li>

          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Add new <i class="fa fa-question-circle"></i> <span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a href="#">event</a></li>
              <li><a href="#">factoid</a></li>
              <li><a href="#">page</a></li>
              <li><a href="#">status</a></li>
              <li><a href="#">figure</a></li>
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
        <h2>Page Type <small><a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-question-circle"></i></a></small></h2>

<?php

echo default_page_warning_page();

echo get_page_background_thumb();

?>
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
            <li><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml">BBC Technology, UK Edition</a> (This is the default)</li>
            <li><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/uk/rss.xml">BBC UK, UK Edition</a></li>
            <li><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/rss.xml">BBC England, UK Edition</a></li>
            <li><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/sci/tech/rss.xml">BBC Science &amp; Environment, UK Edition</a></li>
            <li><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://rss.slashdot.org/Slashdot/slashdot">Slashdot: News for nerds, stuff that matters</a></li>
            <li><a class="hvr-sweep-to-right" href="<?php echo $CFG['adminpage']; ?>?action=rssfeed_preset&rssfeed_preset_url=http://news.southdevon.ac.uk/items.atom?body=txt">South Devon College News</a></li>
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
        <h2>Factoids</h2>
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
<?php

echo footer_content();

?>
    </div>
  </footer>


  <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="js/jquery.word-and-character-counter.min.js"></script>
  <script type="text/javascript" src="js/holder.min.js"></script>
  <script type="text/javascript" src="bower_components/bootstrap-sweetalert/lib/sweet-alert.min.js"></script>
  <script type="text/javascript" src="bower_components/letteringjs/jquery.lettering.js"></script>
  <script type="text/javascript" src="bower_components/browserdetection/src/browser-detection.js"></script>
  <!-- script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-jgrowl/1.4.1/jquery.jgrowl.min.js"></script -->

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

    /* Letteringjs */
    $(".fancy_title").lettering();

    var browserdata = browserDetection({
      addClasses: true
    });
    console.log(browserdata.browser); // chrome
    console.log(browserdata.version); // 29
    console.log(browserdata.os); // osx

    if (browserdata.browser == 'chrome') {
      document.getElementById("browserdata").innerHTML = "<li>&middot;</li>\n<li>Well done for using Chrome.</li>";
    } else if (browserdata.browser == 'firefox') {
      document.getElementById("browserdata").innerHTML = "<li>&middot;</li>\n<li>Well done for using Firefox. You should probably use Chrome though.</li>";
    } else {
      document.getElementById("browserdata").innerHTML = "<li>&middot;</li>\n<li>I see you're not using Chrome. You should use a better browser, like Chrome.</li>";
    }

    // Will log you out after x milliseconds.
    // TODO: use setInterval here instead?
    setTimeout(function() {
      location.href = '<?php echo $CFG['adminpage']; ?>?action=logout';
    }, <?php echo $CFG['admintimeout']; ?> * 60 * 1000);

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
