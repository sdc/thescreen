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
if ( isset( $_POST['action'] ) && $_POST['action'] == 'help_edit' ) {

  if ( isset( $_POST['help_title'] ) && !empty( $_POST['help_title'] ) && isset( $_POST['help_content'] ) && !empty( $_POST['help_content'] ) ) {

    if ( isset( $_POST['help_edit'] ) && !empty( $_POST['help_edit'] ) && is_numeric( $_POST['help_edit'] ) ) {

      // Make the following function UPDATE rather than INSERT.
      if ( edit_help( $_POST['help_title'], $_POST['help_content'], $_POST['help_edit'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The help test for &ldquo;' . $_POST['help_title'] . '&rdquo; was updated successfully.' );
        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The help text was not updated for some reason.' );
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
if ( isset( $_GET['action'] ) && $_GET['action'] == 'help_edit' && isset( $_GET['help_id'] ) && !empty( $_GET['help_id'] ) && is_numeric( $_GET['help_id'] ) ) {

  $sql = "SELECT id, title, content FROM help WHERE id = " . $_GET['help_id'] . " LIMIT 1;";
  $res = $DB->query( $sql );

  if ( $res->num_rows == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'Could not get the help text with id ' . $_GET['help_id'] . '.' );
  } else {
    $row = $res->fetch_assoc();
  }

}

?><!DOCTYPE html>
<html lang="en">
<head>
<?php

echo admin_header( 'Help edit page.' );

?>

  <link href="bower_components/trumbowyg/dist/ui/trumbowyg.min.css" rel="stylesheet">

</head>
<body>

<?php

echo navbar_light();

?>

  <div class="container">

    <!-- Row one. -->
    <div class="row">
      <div class="col-md-12">
        <h1>Edit the help text</h1>
        <p>Use the below form to edit the help text.</p>
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
          <input type="hidden" name="action" value="help_edit">

<?php

if ( isset( $row['id'] ) ) {
  echo '          <input type="hidden" name="help_edit" value="' . $row['id'] . '">' . "\n";
} else if ( isset( $_POST['help_edit'] ) ) {
  echo '          <input type="hidden" name="help_edit" value="' . $_POST['help_edit'] . '">' . "\n";
}


$help_title_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'help_edit' && ( !isset( $_POST['help_title'] ) || empty( $_POST['help_title'] ) ) ) {
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
            <input type="text" class="form-control" id="help_title" name="help_title" placeholder="Enter title" <?php echo $help_title_value; ?> aria-describedby="help_title_help">
            <span id="help_title_help" class="help-block">Title for this help.</span>
          </div>
<?php

$help_content_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'help_edit' && ( !isset( $_POST['help_content'] ) || empty( $_POST['help_content'] ) ) ) {
  $help_content_error = ' has-error';
}

$help_content_value = '';
if ( isset( $row['content'] ) ) {
  $help_content_value = $row['content'];
} elseif ( isset( $_POST['help_content'] ) && !empty( $_POST['help_content'] ) ) {
  $help_content_value = $_POST['help_content'];
}

?>
          <div class="form-group<?php echo $help_content_error; ?>">
            <label for="help_content">Event description</label>
            <!-- input type="text" class="form-control" id="help_content" name="help_content" placeholder="Enter help content" <?php echo $help_content_value; ?>aria-describedby="help_content_help" -->
            <textarea class="form-control" id="help_content" name="help_content" placeholder="Enter help content" aria-describedby="help_content_help"><?php echo $help_content_value; ?></textarea>
            <span id="help_content_help" class="help-block">Write useful help text.</span>
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

<?php

echo admin_footer();

?>

  <script type="text/javascript" src="bower_components/trumbowyg/dist/trumbowyg.min.js"></script>

  <script type="text/javascript">
  $(document).ready(function(){
    $('#help_content').trumbowyg();
  });
  </script>

</body>
</html>
