<?php

/**
 * Add/Edit page for pages.
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

// Adding a new page.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'page_add' ) {

  if (
    isset( $_POST['page_name'] )        && !empty( $_POST['page_name'] ) &&
    isset( $_POST['page_title'] )       && !empty( $_POST['page_title'] ) &&
    isset( $_POST['page_description'] ) && !empty( $_POST['page_description'] ) &&
    isset( $_POST['page_scheduled'] )   && !empty( $_POST['page_scheduled']
  ) ) {

    if ( isset( $_POST['page_edit'] ) && !empty( $_POST['page_edit'] ) && is_numeric( $_POST['page_edit'] ) ) {

      // Make the following function UPDATE rather than INSERT.
      if ( edit_page( $_POST['page_name'], $_POST['page_title'], $_POST['page_description'], $_POST['page_scheduled'], $_POST['page_edit'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The page &ldquo;' . $_POST['page_title'] . '&rdquo; was updated successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The page was not updated for some reason.' );
      }

    // If we are inserting a new page.
    } else {

      if ( add_page( $_POST['page_name'], $_POST['page_title'], $_POST['page_description'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The page &ldquo;' . $_POST['page_title'] . '&rdquo; was created successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The page was not created for some reason.' );
      }

    }

  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The form was not complete.' );
  }

} else {
  // Do nothing, as the user's coming here for the first time?
  //$_SESSION['alerts'][] = array( 'warning' => 'The form was not complete.' );
}

adminlog('addedit-page');

// If we've received $_GET parameters, populate the form with them.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'page_edit' && isset( $_GET['page_id'] ) && !empty( $_GET['page_id'] ) && is_numeric( $_GET['page_id'] ) ) {

  $sql = "SELECT * FROM pages WHERE id = " . $_GET['page_id'] . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'Could not get the page with id ' . $_GET['page_id'] . '.' );
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

  <title><?php echo $CFG['lang']['title']; ?> :: Page edit page</title>

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
<?php

if (
  ( isset( $_GET['page_id'] ) && !empty( $_GET['page_id'] ) && is_numeric( $_GET['page_id'] ) )
  ||
  ( isset( $_POST['page_edit'] ) && !empty( $_POST['page_edit'] ) && is_numeric( $_POST['page_edit'] ) )
) {
  echo '        <h1>Edit this page</h1>';
  echo '        <p>Use the below form to edit the existing page.</p>';
} else {
  echo '        <h1>Add a new page</h1>';
  echo '        <p>Use the below form to add a new page.</p>';
}

?>
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
          <input type="hidden" name="action" value="page_add">

<?php

if ( isset( $row['id'] ) ) {
  echo '          <input type="hidden" name="page_edit" value="' . $row['id'] . '">' . "\n";
} else if ( isset( $_POST['page_edit'] ) ) {
  echo '          <input type="hidden" name="page_edit" value="' . $_POST['page_edit'] . '">' . "\n";
}


$page_name_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'page_add' && ( !isset( $_POST['page_name'] ) || empty( $_POST['page_name'] ) ) ) {
  $page_name_error = ' has-error';
}

$page_name_value = '';
if ( isset( $row['name'] ) ) {
  $page_name_value = 'value="' . $row['name'] . '"';
} else if ( isset( $_POST['page_name'] ) && !empty( $_POST['page_name'] ) ) {
  $page_name_value = 'value="' . $_POST['page_name'] . '"';
}

?>
          <div class="form-group<?php echo $page_name_error; ?>">
            <label for="page_name">Page name</label>
            <input type="text" class="form-control" id="page_name" name="page_name" placeholder="Enter page name" <?php echo $page_name_value; ?> aria-describedby="page_name_help">
            <span id="page_name_help" class="help-block">Enter an internal name for this page: all lowercase, no other characters except dash/minus '-', e.g. 'meeting-tuesday'.</span>
          </div>

<?php

$page_title_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'page_add' && ( !isset( $_POST['page_title'] ) || empty( $_POST['page_title'] ) ) ) {
  $page_title_error = ' has-error';
}

$page_title_value = '';
if ( isset( $row['name'] ) ) {
  $page_title_value = 'value="' . $row['title'] . '"';
} else if ( isset( $_POST['page_title'] ) && !empty( $_POST['page_title'] ) ) {
  $page_title_value = 'value="' . $_POST['page_title'] . '"';
}

?>
          <div class="form-group<?php echo $page_title_error; ?>">
            <label for="page_title">Page title</label>
            <input type="text" class="form-control" id="page_title" name="page_title" placeholder="Enter page title" <?php echo $page_title_value; ?> aria-describedby="page_title_help">
            <span id="page_title_help" class="help-block">Enter a title for this page, e.g. 'Tuesday Meeting'. Will appear on the admin screen.</span>
          </div>

<?php

$page_description_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'page_add' && ( !isset( $_POST['page_description'] ) || empty( $_POST['page_description'] ) ) ) {
  $page_description_error = ' has-error';
}

$page_description_value = '';
if ( isset( $row['description'] ) ) {
  $page_description_value = 'value="' . $row['description'] . '"';
} elseif ( isset( $_POST['page_description'] ) && !empty( $_POST['page_description'] ) ) {
  $page_description_value = 'value="' . $_POST['page_description'] . '"';
}

?>
          <div class="form-group<?php echo $page_description_error; ?>">
            <label for="page_description">Event description</label>
            <input type="text" class="form-control" id="page_description" name="page_description" placeholder="Enter page description" <?php echo $page_description_value; ?>aria-describedby="page_description_help">
            <span id="page_description_help" class="help-block">For descriptive purposes only; doesn't appear on the page itself.</span>
          </div>

<?php

$page_scheduled_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'page_add' && ( !isset( $_POST['page_scheduled'] ) || empty( $_POST['page_scheduled'] ) ) ) {
  $page_scheduled_error = ' has-error';
}

$page_scheduled_checked_yes = '';
$page_scheduled_checked_no = '';
if ( isset( $row['scheduled'] ) ) {
  if ( $row['scheduled'] ) {
    $page_scheduled_checked_yes = ' checked';
  } else {
    $page_scheduled_checked_no = ' checked';
  }

} elseif ( isset( $_POST['page_scheduled'] ) && !empty( $_POST['page_scheduled'] ) ) {
  if ( $_POST['page_scheduled'] == 'yes' ) {
    $page_scheduled_checked_yes = ' checked';
  } else if ( $_POST['page_scheduled'] == 'no' ) {
    $page_scheduled_checked_no = ' checked';
  } else {
    $page_scheduled_checked_no = ' checked';
  }
} else {
  $page_scheduled_checked_no = ' checked';
}

?>


          <div class="radio<?php echo $page_scheduled_error; ?>">
            <label>
              <input type="radio" name="page_scheduled" id="page_scheduled" value="no"<?php echo $page_scheduled_checked_no; ?> onclick="scheduleoff();">
              No - this is not a scheduled page.
            </label>
          </div>
          <div class="radio<?php echo $page_scheduled_error; ?>">
            <label>
              <input type="radio" name="page_scheduled" id="page_scheduled" value="yes"<?php echo $page_scheduled_checked_yes; ?> onclick="scheduleon();">
              Yes, this page is scheduled.
            </label>
            <span id="page_scheduled_help" class="help-block">Choose 'yes' to schedule the page to appear automatically.</span>
          </div>

<?php

$page_schedule_day_selected = '';
if ( isset( $row['schedule_day'] ) ) {
  $page_schedule_day_selected = $row['schedule_day'];
} elseif ( isset( $_POST['page_schedule_day'] ) && !empty( $_POST['page_schedule_day'] ) ) {
  $page_schedule_day_selected = $_POST['page_schedule_day'];
}

?>
          <div class="form-group">
            <label for="page_schedule_day">Day to show page</label>
            <select class="form-control" name="page_schedule_day" id="page_schedule_day">
<?php

$days = array(
  'mon' => 'Monday',
  'tue' => 'Tuesday',
  'wed' => 'Wednesday',
  'thu' => 'Thursday',
  'fri' => 'Friday',
  'sat' => 'Saturday',
  'sun' => 'Sunday'
);

foreach ( $days as $key => $value ) {
  $out = '              <option value="' . $key . '"';
  if ( $page_schedule_day_selected == $key ) {
    $out .= ' selected';
  }
  $out .= '>' . $value . '</option>' . "\n";
  echo $out;
}

?>
            </select>
            <span id="page_schedule_day" class="help-block">Choose which day you want the scheduled page to appear.</span>
          </div>

<?php

$page_schedule_start_value = '';
if ( isset( $row['schedule_start'] ) && !empty( $row['schedule_start'] ) ) {
  $page_schedule_start_value = $row['schedule_start'];
} elseif ( isset( $_POST['page_schedule_start'] ) && !empty( $_POST['page_schedule_start'] ) ) {
  $page_schedule_start_value = $_POST['page_schedule_start'];
} else {
  $page_schedule_start_value = '10:00';
}

?>
          <div class="form-group">
            <label for="page_schedule_start">Time to start showing page</label>
            <select class="form-control" name="page_schedule_start" id="page_schedule_start">

<?php

$times = array();

for ( $h = 8; $h <= 21; $h++ ) {
  for ( $m = 0; $m <= 55; $m += 15 ) {
    $times[] = $h . ':' . str_pad( $m, 2, 0, STR_PAD_LEFT );
  }
}

foreach ( $times as $time ) {
  $out = '              <option value="' . $time . '"';
  if ( $page_schedule_start_value == $time ) {
    $out .= ' selected';
  }
  $out .= '>' . $time . '</option>' . "\n";
  echo $out;
}

?>
            </select>
            <span id="page_schedule_start" class="help-block">Choose what time you want the scheduled page to appear.</span>
          </div>

<?php

$page_schedule_end_value = '';
if ( isset( $row['schedule_end'] ) && !empty( $row['schedule_end'] ) ) {
  $page_schedule_end_value = $row['schedule_end'];
} elseif ( isset( $_POST['page_schedule_end'] ) && !empty( $_POST['page_schedule_end'] ) ) {
  $page_schedule_end_value = $_POST['page_schedule_end'];
} else {
  $page_schedule_end_value = '14:00';
}

$page_schedule_end_error = '';
if ( strtotime( $page_schedule_start_value ) >= strtotime( $page_schedule_end_value ) ) {
  $page_schedule_end_error = ' has-error';
}

?>
          <div class="form-group<?php echo $page_schedule_end_error; ?>">
            <label for="page_schedule_end">Time to stop showing page</label>
            <select class="form-control" name="page_schedule_end" id="page_schedule_end">

<?php

foreach ( $times as $time ) {
  $out = '              <option value="' . $time . '"';
  if ( $page_schedule_end_value == $time ) {
    $out .= ' selected';
  }
  $out .= '>' . $time . '</option>' . "\n";
  echo $out;
}

?>
            </select>
            <span id="page_schedule_end" class="help-block">Choose what time you want the scheduled page to disappear.</span>
          </div>


          <a class="btn btn-default" href="<?php echo $CFG['adminpage']; ?>" role="button">Cancel</a>
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

  function scheduleoff() {
    $("#page_schedule_day").prop( "disabled", true );
    $("#page_schedule_start").prop( "disabled", true );
    $("#page_schedule_end").prop( "disabled", true );
  }
  function scheduleon() {
    $("#page_schedule_day").prop( "disabled", false );
    $("#page_schedule_start").prop( "disabled", false );
    $("#page_schedule_end").prop( "disabled", false );
  }

  $(document).ready(function() {

    if ( $('#page_scheduled').prop('checked') ) {
      scheduleoff();
    }

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
