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
    //isset( $_POST['page_background'] )  && !empty( $_POST['page_background'] ) &&
    isset( $_POST['page_scheduled'] )   && !empty( $_POST['page_scheduled']
  ) ) {

    if ( isset( $_POST['page_edit'] ) && !empty( $_POST['page_edit'] ) && is_numeric( $_POST['page_edit'] ) ) {

      // Make the following function UPDATE rather than INSERT.
      if ( edit_page( $_POST['page_name'], $_POST['page_title'], $_POST['page_description'], $_POST['page_background'], $_POST['page_scheduled'], $_POST['page_edit'] ) ) {

        $_SESSION['alerts'][] = array( 'success' => 'The page &ldquo;' . $_POST['page_title'] . '&rdquo; was updated successfully.' );

        // If the page edited and saved is the current page, force a reload.
        if ( $_POST['page_edit'] == get_config( 'page' ) ) {
          set_change();
        }

        header( 'location: ' . $CFG['adminpage'] );
        exit(0);

      } else {
        $_SESSION['alerts'][] = array( 'warning' => 'The page was not updated for some reason.' );
      }

    // If we are inserting a new page.
    } else {

      if ( add_page( $_POST['page_name'], $_POST['page_title'], $_POST['page_description'], $_POST['page_background'] ) ) {

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
<?php

echo admin_header( 'Page add/edit page.' );

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
            <div class="row">
              <div class="col-sm-6">
                <input type="text" class="form-control" id="page_name" name="page_name" placeholder="enter-page-name" <?php echo $page_name_value; ?> aria-describedby="page_name_help">
              </div>
            </div>
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
            <div class="row">
              <div class="col-sm-6">
                <input type="text" class="form-control" id="page_title" name="page_title" placeholder="Enter Page Title" <?php echo $page_title_value; ?> aria-describedby="page_title_help">
              </div>
            </div>
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
            <label for="page_description">Page description</label>
            <input type="text" class="form-control" id="page_description" name="page_description" placeholder="Enter page description" <?php echo $page_description_value; ?>aria-describedby="page_description_help">
            <span id="page_description_help" class="help-block">For descriptive purposes only; doesn't appear on the page itself.</span>
          </div>

<?php



















// Might not be necessary: do pages need a background image?
$page_background_error = '';
if ( isset( $_POST['action'] ) && $_POST['action'] == 'page_add' && ( !isset( $_POST['page_background'] ) || empty( $_POST['page_background'] ) ) ) {
  $page_background_error = ' has-error';
}

$page_background_value = '';
if ( isset( $row['background'] ) ) {
  //$page_background_value = 'value="' . $row['background'] . '"';
  $page_background_value = $row['background'];
} elseif ( isset( $_POST['page_background'] ) && !empty( $_POST['page_background'] ) ) {
  $page_background_value = $_POST['page_background'];
}

?>
          <div class="form-group<?php echo $page_background_error; ?>">
            <label for="page_background">Page background image</label>
            <div class="row">
              <div class="col-sm-6">
                <select class="form-control" name="page_background" id="page_background" onChange="changeimage();">
                  <option value="">None</option>
<?php

// Scan the appropriate folder for appropriate images.
$backgrounds = array();

if ( $fh = opendir( $CFG['dir']['bg'] ) ) {
  while ( false !== ( $entry = readdir( $fh ) ) ) {
    if ( $entry != '.' && $entry != '..' ) {
      $backgrounds[] = $entry;
    }
  }
  closedir( $fh );
}

sort( $backgrounds );

foreach ( $backgrounds as $bg ) {
  $out = '                  <option value="' . $bg . '"';
  if ( $page_background_value == $bg ) {
    $out .= ' selected';
  }
  $out .= '>' . $bg . '</option>' . "\n";
  echo $out;
}

?>
                </select>
              </div>
            </div>
            <span id="page_background_help" class="help-block">Choose a background image, if one is needed. Choices taken from <code>.jpg</code> and <code>.png</code> files found in <code>/graphics/backgrounds</code>.</span>
          </div>

          <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
              <img id="chosen_thumb">
            </div>
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
              Yes - this page is scheduled (see below).
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
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label for="page_schedule_day">Day</label>
                <select class="form-control" name="page_schedule_day" id="page_schedule_day">
<?php

foreach ( $CFG['days'] as $key => $value ) {
  $out = '                  <option value="' . $key . '"';
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
            <div class="col-sm-4">
              <div class="form-group">
                <label for="page_schedule_start">Start time</label>
                <select class="form-control" name="page_schedule_start" id="page_schedule_start">

<?php

foreach ( $CFG['times'] as $time ) {
  $out = '                <option value="' . $time . '"';
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
            </div>

            <div class="col-sm-4">
              <div class="form-group<?php echo $page_schedule_end_error; ?>">
                <label for="page_schedule_end">End time</label>
                <select class="form-control" name="page_schedule_end" id="page_schedule_end">

<?php

foreach ( $CFG['times'] as $time ) {
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
            </div>
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

  function changeimage() {
    if ( $("#page_background").val() != '' ) {
      $("#chosen_thumb").attr( "src", "<?php echo $CFG['dir']['bg']; ?>" + $( "#page_background" ).val() );
      $("#chosen_thumb").addClass( "img-thumbnail" );
    } else {
      $("#chosen_thumb").attr( "src", "" );
      $("#chosen_thumb").removeClass( "img-thumbnail" );
    }
  }

  $(document).ready(function() {

    if ( $('#page_scheduled').prop('checked') ) {
      scheduleoff();
    }

    changeimage();

  });

  </script>

</body>
</html>
