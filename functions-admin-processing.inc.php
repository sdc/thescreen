<?php

/**
 * Check to see if $_POST or $_GET are set, and deal with them if so.
 */

/**
 * Logging out.
 */

// Logging out.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'logout' ) {
  logout();
}


/**
 * Page checks.
 */

// Changing the page.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'page_change' && isset( $_GET['page'] ) && !empty( $_GET['page'] ) && is_numeric( $_GET['page'] ) ) {
  if ( update_check( 'pages', $_GET['page'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was set successfully.' );
    // TODO: Proposed change:
    //$_SESSION['alerts'][] = array( 'type' => 'success', 'text' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was set successfully.', 'timeout' => 3000 );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The page called &ldquo;' . get_title( 'pages', $_GET['page'] ) . '&rdquo; was not set for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Forcing a page refresh.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'refresh_main' ) {
  if ( set_change() ) {
    $_SESSION['alerts'][] = array( 'success' => 'The main page will refresh now.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The main page could not be set to refresh for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * Status checks.
 */

// Changing the status.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'status_change' && isset( $_GET['status'] ) && !empty( $_GET['status'] ) && is_numeric( $_GET['status'] ) ) {
  if ( update_check( 'status', $_GET['status'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The status &ldquo;' . get_title( 'status', $_GET['status'] ) . '&rdquo; was set successfully.' );
    // If the showstopper page is set when the showstopper text is changed, update the page.
    if ( get_config( 'page' ) == get_id( 'pages', 'standard' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The status &ldquo;' . get_title( 'status', $_GET['status'] ) . '&rdquo; was not set for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * Event checks.
 */

// Adding is now done in event.php

// Deleting an event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_del' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( delete_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was deleted.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not deleted for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding an event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_hide' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( hide_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was hidden successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Showing a hidden event.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_show' && isset( $_GET['event_id'] ) && !empty( $_GET['event_id'] ) && is_numeric( $_GET['event_id'] ) ) {
  if ( show_event( $_GET['event_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was un-hidden successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The event with id <strong>' . $_GET['event_id'] . '</strong> was not un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Showing all events.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_show_all' ) {
  if ( event_show_all() ) {
    $_SESSION['alerts'][] = array( 'success' => 'All events have been un-hidden successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'All events have not been un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding all events.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'event_hide_all' ) {
  if ( event_hide_all() ) {
    $_SESSION['alerts'][] = array( 'success' => 'All events have been hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'All events have not been hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * Showstopper checks.
 */

// Updating the showstopper text.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'showstopper_edit' && isset( $_POST['showstopper'] ) && !empty( $_POST['showstopper'] ) ) {
  if ( set_config( 'showstopper', $_POST['showstopper'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'Showstopper text &ldquo;' . $_POST['showstopper'] . '&rdquo; was updated successfully.' );
    // If the showstopper page is set when the showstopper text is changed, update the page.
    if ( get_config( 'page' ) == get_id( 'pages', 'showstopper' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'Showstopper text &ldquo;' . $_POST['showstopper'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * RSS feed checks.
 */

// Updating the RSS feed URL.
if ( isset( $_POST['action'] ) && $_POST['action'] == 'rssfeed_url_edit' && isset( $_POST['rssfeed_url'] ) && !empty( $_POST['rssfeed_url'] ) ) {
  if ( set_config( 'rssfeed', $_POST['rssfeed_url'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'RSS feed URL &ldquo;' . $_POST['rssfeed_url'] . '&rdquo; was updated successfully.' );
    // If the default page is set when the rss feed URL is changed, update the page.
    //if ( get_config( 'page' ) == get_default( 'pages' ) ) {
    if ( get_config( 'page' ) == get_id( 'pages', 'standard' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'RSS feed URL &ldquo;' . $_POST['rssfeed_url'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Updating the RSS feed URL from a preset.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'rssfeed_preset' && isset( $_GET['rssfeed_preset_url'] ) && !empty( $_GET['rssfeed_preset_url'] ) ) {
  if ( set_config( 'rssfeed', $_GET['rssfeed_preset_url'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'RSS feed preset &ldquo;' . $_GET['rssfeed_preset_url'] . '&rdquo; was updated successfully.' );
    // If the default page is set when the rss feed URL preset is changed, update the page.
    if ( get_config( 'page' ) == get_default( 'pages' ) ) {
      set_change();
    }
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'RSS feed preset &ldquo;' . $_GET['rssfeed_preset_url'] . '&rdquo; was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * Specific figure (person) checks.
 */

// Updating the random / specific figure.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'figure_change' && isset( $_GET['figure_filename'] ) && !empty( $_GET['figure_filename'] ) && isset( $_GET['figure_name'] ) && !empty( $_GET['figure_name'] ) ) {
  if ( set_config( 'specific_fig', $_GET['figure_filename'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'Figure &ldquo;' . $_GET['figure_name'] . '&rdquo; was updated successfully.' );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'Figure &ldquo;' . $_GET['figure_name'] . '&rdquo; (' . $_GET['figure_filename'] . ') was not updated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * Database checks.
 */

// Forcing the defaults into the database (a 'full reset').
if ( isset( $_GET['action'] ) && $_GET['action'] == 'full_reset' ) {
  if ( $tmp = force_default() ) {
    $_SESSION['alerts'][] = array( 'success' => $tmp );
    set_change();
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The full reset didn\'t work for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Truncating the log table.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'truncate_log' ) {
  if ( truncate_log() ) {
    $_SESSION['alerts'][] = array( 'success' => 'The log table was truncated successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The log table was not truncated for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * Factoid checks.
 */

// Showing (un-hiding) a factoid.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_show' && isset( $_GET['factoid_id'] ) && !empty( $_GET['factoid_id'] ) && is_numeric( $_GET['factoid_id'] ) ) {
  if ( factoid_show( $_GET['factoid_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was un-hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was not un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding a factoid.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_hide' && isset( $_GET['factoid_id'] ) && !empty( $_GET['factoid_id'] ) && is_numeric( $_GET['factoid_id'] ) ) {
  if ( factoid_hide( $_GET['factoid_id'] ) ) {
    $_SESSION['alerts'][] = array( 'success' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'The factoid with id &ldquo;' . $_GET['factoid_id'] . '&rdquo; was not hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Showing all factoids.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_show_all' ) {
  if ( factoid_show_all() ) {
    $_SESSION['alerts'][] = array( 'success' => 'All factoids have been un-hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'All factoids have not been un-hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}

// Hiding all factoids.
if ( isset( $_GET['action'] ) && $_GET['action'] == 'factoid_hide_all' ) {
  if ( factoid_hide_all() ) {
    $_SESSION['alerts'][] = array( 'success' => 'All factoids have been hidden successfully.' );
  } else {
    $_SESSION['alerts'][] = array( 'danger' => 'All factoids have not been hidden for some reason.' );
  }
  header( 'location: ' . $CFG['adminpage'] );
  exit(0);
}


/**
 * General checks.
 */

// Install script still here.
if ( file_exists( 'install.php' ) || file_exists( 'install-password.php' ) ) {
  if ( !$CFG['ignoreinstallfiles'] ) {
    $_SESSION['alerts'][] = array( 'danger' => 'The installation files <code>install.php</code> and <code>install-password.php</code> still accessible. Best to delete them immediately.' );
  }
}


// No factoids.
// TODO: Complete the 'add one' link.
if ( count_rows( 'factoids' ) == 0 ) {
  $_SESSION['alerts'][] = array( 'danger' => 'There are no factoids in the database. <a href="#" class="alert-link">Add one?</a>' );
} else {
  // No un-hidden factoids.
  if ( count_rows( 'factoids', 'hidden = 0' ) == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'There are no un-hidden factoids. You should show at least one.' );
  }
}

// No events.
// TODO: Complete the 'add one' link.
if ( count_rows( 'events' ) == 0 ) {
  $_SESSION['alerts'][] = array( 'danger' => 'There are no events in the database. <a href="#" class="alert-link">Add one?</a>' );
} else {
  // No un-hidden events.
  if ( count_rows( 'events', 'hidden = 0' ) == 0 ) {
    $_SESSION['alerts'][] = array( 'danger' => 'There are no un-hidden events. You should show at least one.' );
  }
}
