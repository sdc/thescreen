<?php

/**
 * Edit page for help text.
 */

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


// Adding a new event.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'event_add' ) {

  if ( isset( $_POST['event_date'] ) && !empty( $_POST['event_date'] ) && isset( $_POST['event_description'] ) && !empty( $_POST['event_description'] ) ) {

    if ( isset( $_POST['event_edit'] ) && !empty( $_POST['event_edit'] ) && is_numeric( $_POST['event_edit'] ) ) {

      // Make the following function UPDATE rather than INSERT.
      if ( edit_event( $_POST['event_date'], $_POST['event_description'], $_POST['event_edit'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The event &ldquo;' . $_POST['event_description'] . '&rdquo; was updated successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The event was not updated for some reason.' );
      }

    // If we are inserting a new event.
    } else {

      if ( add_event( $_POST['event_date'], $_POST['event_description'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The event &ldquo;' . $_POST['event_description'] . '&rdquo; was created successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The event was not created for some reason.' );
      }

    }

  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The form was not complete.' );
  }

} else {
  // Do nothing, as the user's coming here for the first time?
  //$_SESSION['alerts'][] = array( 'warning' => 'The form was not complete.' );
}

adminlog('help');

// If we've received $_GET parameters, populate the form with them.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_edit' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {

  $sql = "SELECT id, start, text FROM events WHERE id = " . $_GET['event_id'] . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'Could not get the event with id ' . $_GET['event_id'] . '.' );
  } else {
    $row = $res->fetch_assoc();
  }

}

?><!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="A content presentation system for SDC Computer Services.">
  <meta name="author" content="Mostly Paul Vaughan.">

  <title><?php echo $CFG['lang']['title']; ?> :: Edit page</title>

  <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="bower_components/bootstrap/dist/css/bootstrap-theme.min.css" rel="stylesheet">
  <link href="css/bs-docs.css" rel="stylesheet">

  <link href="http://fonts.googleapis.com/css?family=Indie+Flower" rel="stylesheet" type="text/css">

  <style type="text/css">
  body {
    padding-top: 70px;
  }
  </style>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <link href="bower_components/fontawesome/css/font-awesome.min.css" rel="stylesheet">

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
        <ul class="nav navbar-nav navbar-right">
          <li><a href="<?php echo $CFG['adminpage']; ?>?action=logout">Log out <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav><!-- END fixed navbar. -->

  <div class="container">

    <!-- Row one. -->
    <div class="row">
      <div class="col-md-12">
        <h1>Add a new event</h1>
        <p>Use the below form to add a new event.</p>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <hr>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 col-md-offset-3">

<?php

if ( isset( $_SESSION['alerts'] ) ) {
  foreach ( $_SESSION['alerts'] as $alert ) {
    foreach ( $alert as $alert_type => $alert_text ) {
      echo '    <div class="alert alert-' . $alert_type . ' alert-dismissible" role="alert">' . "\n";
      //echo '      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . "\n";
      echo '      ' . $alert_text . "\n";
      echo '    </div>' . "\n";
    }
  }
  unset( $_SESSION['alerts'] );
}

?>

        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
          <!-- input type="hidden" name="action" value="event_add" -->

<?php

if ( isset( $row['id'] ) ) {
  echo '          <input type="hidden" name="help_edit" value="' . $row['id'] . '">' . "\n";
} else if ( isset( $_POST['event_edit'] ) ) {
  echo '          <input type="hidden" name="help_edit" value="' . $_POST['help_edit'] . '">' . "\n";
}


// We're not adding anything - do we need this?
$help_title_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'help_add' && ( !isset( $_POST['help_title'] ) || empty( $_POST['help_title'] ) ) ) {
  $help_title_error = ' has-error';
}

$help_title_value = '';
if ( isset( $row['title'] ) ) {
  $help_title_value = 'value="' . $row['title'] . '"';
} else if ( isset( $_POST['title'] ) && !empty( $_POST['title'] ) ) {
  $help_title_value = 'value="' . $_POST['title'] . '"';
}

?>
          <div class="form-group<?php echo $help_title_error; ?>">
            <label for="help_title">Help Title</label>
            <input type="date" class="form-control" id="help_title" name="help_title" placeholder="Enter title" <?php echo $help_title_value; ?> aria-describedby="help_title_help">
            <span id="help_title_help" class="help-block">Title for this help.</span>
          </div>

<?php

$event_description_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'event_add' && ( !isset( $_POST['event_description'] ) || empty( $_POST['event_description'] ) ) ) {
  $event_description_error = ' has-error';
}

$event_description_value = '';
if ( isset( $row['text'] ) ) {
  $event_description_value = 'value="' . $row['text'] . '"';
} elseif ( isset( $_POST['event_description'] ) && !empty( $_POST['event_description'] ) ) {
  $event_description_value = 'value="' . $_POST['event_description'] . '"';
}

?>
          <div class="form-group<?php echo $event_description_error; ?>">
            <label for="event_description">Event description</label>
            <input type="text" class="form-control" id="event_description" name="event_description" placeholder="Enter event details" <?php echo $event_description_value; ?>aria-describedby="event_description_help">
            <span id="event_description_help" class="help-block">Be concise! We don't have much space to work with.</span>
          </div>

          <a class="btn btn-default" href="manage.php" role="button">Cancel</a>
          <button type="submit" class="btn btn-info">Submit</button>

        </form>

      </div>
    </div>

  </div> <!-- /container -->












  <footer class="bs-docs-footer" role="contentinfo">
    <div class="container">
<?php

echo footer_content();

?>
    </div>
  </footer>


  <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="bower_components/browserdetection/src/browser-detection.js"></script>

  <script type="text/javascript">
  $(document).ready(function(){

    window.setTimeout(function() { 
      $(".alert-success-fade").fadeTo(800, 0).slideUp(500);
    }, 5000);

    window.setTimeout(function() { 
      $(".alert-info-fade").fadeTo(800, 0).slideUp(500);
    }, 2000);

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

  });
  </script>

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
