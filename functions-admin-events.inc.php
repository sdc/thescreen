<?php

/**
 * Functions relating to events.
 */

// Shows all events.
// TODO: Showing all events is not relevant for dates which have passed. Refactor this.
function event_show_all() {

  adminlog( 'event_show_all|' );

  global $DB;

  $sql = "UPDATE `events` SET `hidden` = '0', `modified` = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Hides all events.
// TODO: Hiding all events is not relevant for dates which have passed. Refactor this.
function event_hide_all() {

  adminlog( 'event_hide_all|' );

  global $DB;

  $sql = "UPDATE `events` SET `hidden` = '1', `modified` = '" . time() . "';";
  $res = $DB->query( $sql );

  return $res;
}

// Biulds the events menu.
// TODO: Do we want to limit the number of future events being shown?
function make_events_menu( $num = 20 ) {

    global $CFG, $DB;

    $now = time();
    $today = date( 'Y', $now ) . '-' . date( 'm', $now ) . '-' . date( 'd', $now );

    $sql = "SELECT id, start, text, hidden FROM events WHERE start >= '" . $today . "' ORDER BY start ASC, id ASC LIMIT " . $num . ";";
    $res = $DB->query( $sql );

    if ( $res->num_rows == 0) {
        return '<p class="error">Sorry, no events.</p>';

    } else {

        $build = "<ul>\n";

        while ( $row = $res->fetch_assoc() ) {
            $db_date = $row['start'];
            $disp_date = date( $CFG['time']['short'], mktime( 0, 0, 0, substr($db_date, 5, 2), substr($db_date, 8, 2), substr($db_date, 0, 4) ));

            // Extra styling for hidden events
            if ( $row['hidden'] == 0 ) {
                $build .= '<li>' . $disp_date . ': ' . $row['text'] . ' <a href="' . $CFG['adminpage'] . '?action=event_hide&event_id=' . $row['id'] . '"><span class="glyphicon glyphicon-eye-close event-hide" aria-hidden="true"></span></a>';
            } else {
                $build .= '<li class="text-muted"><del>' . $disp_date . ': ' . $row['text'] . '</del> <a href="' . $CFG['adminpage'] . '?action=event_show&event_id=' . $row['id'] . '"><span class="glyphicon glyphicon-eye-open event-show" aria-hidden="true"></span></a>';
            }

            // Editing button.
            $build .= ' <a href="event.php?action=event_edit&event_id=' . $row['id'] . '">' . get_icon( 'edit', 'Edit this event' ) . '</a>';

            // Delete button.
            $build .= ' <a href="' . $CFG['adminpage'] . '?action=event_del&event_id=' . $row['id'] . '" onclick="return confirm(\'Are you sure you want to delete the event \\\'' . $row['text'] . '\\\' ?\');">' . get_icon( 'cross', 'Delete this event' ) . '</a>';

            $build .= "</li>\n";
        }

        $build .= "</ul>\n";
        return $build;
    }

}


// Hides an event.
// DONE
function hide_event( $id ) {

    global $DB;

    adminlog( 'del_event|' . $id );

    $sql = "UPDATE events SET hidden = 1, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}


// Shows a hidden event.
// TODO: Check the event exists before restoring.
function show_event( $id ) {

    global $DB;

    adminlog( 'show_event|' . $id );

    $sql = "UPDATE events SET hidden = 0, modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}

// Adds an event.
// DONE
function add_event( $date, $text ) {

    global $DB;

    $text = $DB->real_escape_string( $text );

    adminlog( 'add_event|' . $text );

    $sql = "INSERT INTO events (start, text, created, modified) VALUES ('" . $date . "', '" . $text . "', '" . time() . "', '" . time() . "');";
    $res = $DB->query( $sql );

    return $res;
}

// Edits an existing event.
// DONE
// TODO: Check that this event id exists before we attempt to update it.
function edit_event( $date, $text, $id ) {

    global $DB;

    $text = $DB->real_escape_string( $text );

    adminlog( 'edit_event|' . $id );

    $sql = "UPDATE events SET start = '" . $date . "', text = '" . $text . "', modified = '" . time() . "' WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}

// Deletes an event completely.
function delete_event( $id ) {

    global $DB;

    adminlog( 'delete_event|' . $id );

    $sql = "DELETE FROM events WHERE id = " . $id . " LIMIT 1;";
    $res = $DB->query( $sql );

    return $res;
}
