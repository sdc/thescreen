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
<?php

echo admin_header( 'Factoid add/edit page.' );

?>
</head>
<body>

<?php

echo navbar_light();

?>

  <div class="container">

    <!-- Row one. -->
    <div class="row">
      <div class="col-md-12">
<?php

if (
  ( isset( $_GET['factoid_id'] ) && !empty( $_GET['factoid_id'] ) && is_numeric( $_GET['factoid_id'] ) )
  ||
  ( isset( $_POST['factoid_edit'] ) && !empty( $_POST['factoid_edit'] ) && is_numeric( $_POST['factoid_edit'] ) )
) {
  echo '        <h1>Edit this factoid</h1>';
  echo '        <p>Use the below form to edit the existing factoid.</p>';
} else {
  echo '        <h1>Add a new factoid</h1>';
  echo '        <p>Use the below form to add a new factoid.</p>';
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

<?php

echo admin_footer();

?>

</body>
</html>
