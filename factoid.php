<?php

/**
 * Add/edit factoids page.
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

// Adding a new factoid.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'factoid_add' ) {

  if ( isset( $_POST['factoid_fact'] ) && !empty( $_POST['factoid_fact'] ) ) {

    if ( isset( $_POST['factoid_edit'] ) && !empty( $_POST['factoid_edit'] ) && is_numeric( $_POST['factoid_edit'] ) ) {

      // Make the following function UPDATE rather than INSERT.
      if ( edit_factoid( $_POST['factoid_fact'], $_POST['factoid_edit'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The factoid &ldquo;' . $_POST['factoid_fact'] . '&rdquo; was updated successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The factoid was not updated for some reason.' );
      }

    // If we are inserting a new factoid.
    } else {

      if ( add_factoid( $_POST['factoid_fact'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The factoid &ldquo;' . $_POST['factoid_fact'] . '&rdquo; was created successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The factoid was not created for some reason.' );
      }

    }

  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The form was not complete.' );
  }

} else {
  // Do nothing, as the user's coming here for the first time?
  //$_SESSION['alerts'][] = array( 'warning' => 'The form was not complete.' );
}

adminlog('factoid');

// If we've received $_GET parameters, populate the form with them.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_edit' && isset( $_GET['factoid_id'] ) && !empty( $_GET['factoid_id'] ) && is_numeric( $_GET['factoid_id'] ) ) {

  $sql = "SELECT id, fact FROM factoids WHERE id = " . $_GET['factoid_id'] . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'Could not get the factoid with id ' . $_GET['factoid_id'] . '.' );
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
        <h1>Add a new factoid</h1>
        <p>Use the below form to add a new factoid.</p>
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
          <input type="hidden" name="action" value="factoid_add">

<?php

if ( isset( $row['id'] ) ) {
  echo '          <input type="hidden" name="factoid_edit" value="' . $row['id'] . '">' . "\n";
} else if ( isset( $_POST['factoid_edit'] ) ) {
  echo '          <input type="hidden" name="factoid_edit" value="' . $_POST['factoid_edit'] . '">' . "\n";
}

$factoid_fact_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'factoid_add' && ( !isset( $_POST['factoid_fact'] ) || empty( $_POST['factoid_fact'] ) ) ) {
  $factoid_fact_error = ' has-error';
}

$factoid_fact_value = '';
if ( isset( $row['fact'] ) ) {
  $factoid_fact_value = 'value="' . $row['fact'] . '"';
} elseif ( isset( $_POST['factoid_fact'] ) && !empty( $_POST['factoid_fact'] ) ) {
  $factoid_fact_value = 'value="' . $_POST['factoid_fact'] . '"';
}

?>
          <div class="form-group<?php echo $factoid_fact_error; ?>">
            <label for="factoid_fact">Factoid fact</label>
            <input type="text" class="form-control" id="factoid_fact" name="factoid_fact" placeholder="Enter factoid details" <?php echo $factoid_fact_value; ?>aria-describedby="factoid_fact_help">
            <span id="factoid_fact_help" class="help-block">Be concise! We don't have much space to work with.</span>
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
