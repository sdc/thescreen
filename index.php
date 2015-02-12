<?php

/**
 * The Screen
 * Code:    Paul Vaughan
 * GFX:     Deedles
 * Feb 2010 to Feb 2015, with minimal development in between.
 */

/**
 * To Do:
 *
 * * Web fonts!
 * * Sort out page names and ids, as they are the same and it's a pain. Add titles?
 * * Secure all the admin pages, or consolidate them into the main admin page.
 * * Flash messages? Over POST?
 * * RSS feeds in database and URL encoded.
 */

// Do some including.
require_once( 'functions.inc.php' );

// Setup variables and all that
define( 'DEBUG', false );       // set debugging as appropriate

$CFG['page'] = get_config('page');     // a variable with the current page's name in it

// log it
adminlog( 'pageload_' . $CFG['page'] );

?><!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="A content presentation system for SDC Computer Services.">
  <meta name="author" content="Mostly Paul Vaughan.">

  <title>If you can read this, something is not entirely right. Go fullscreen! (F12)</title>

  <meta http-equiv="refresh" content="<?php echo get_refresh( $CFG['page'] ); ?>">

<?php

  // Require the stylesheet instead of linking it, as it has php to be processed within it.
  require_once('style.css');

?>

</head>
<body>

<?php

if (DEBUG) {
  echo '<div id="debug">';
  echo '<p>Debugging Info</p>';
  echo 'Page now: '.$page.'.<br>Page next: '.$page_next.'.<br>Def. refresh: '.$def_refresh.'.<br>Next refresh: '.$page_next_refresh.".\n";
  echo '</div>';
}

$pagename = 'page_' . strtolower( $CFG['page'] ) . '.php';
if ( file_exists( $pagename ) ) {
  require_once( $pagename );
}

?>

  <script type="text/javascript" src="jquery-1.4.2.js"></script>
  <script type="text/javascript" src="jquery.newsticker.js"></script>
  <script type="text/javascript" src="jquery.marquee.js"></script>

  <script type="text/javascript">
  $(document).ready(function(){
      $('#scroller marquee').marquee();

      $("#news1").newsticker(8000);
      $("#news2").newsticker(8000);
      $("#news3").newsticker(8000);
  });
  </script>

</body>
</html>
