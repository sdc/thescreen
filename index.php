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

// The 'root' folder for this page. This folder may or may not exist!
$CFG['dir']['pageroot'] = $CFG['dir']['pages'] . get_name( 'pages', $CFG['page'] ) . '/';

// Setup variables and all that
define( 'DEBUG', false );       // set debugging as appropriate

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
<?php
if ( DEBUG ) {
  echo '  <meta http-equiv="Cache-Control" content="no-store">';
  echo '  <meta http-equiv="cache-control" content="max-age=0">';
  echo '  <meta http-equiv="cache-control" content="no-cache">';
  echo '  <meta http-equiv="expires" content="0">';
  echo '  <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT">';
  echo '  <meta http-equiv="pragma" content="no-cache">';
}
?>
  <title>If you can read this, something is not entirely right. Go fullscreen! (F12)</title>

  <meta http-equiv="refresh" content="<?php echo get_refresh( $CFG['page'] ); ?>">

<?php

  // Require the stylesheet instead of linking it, as it has php to be processed within it.
  require_once('style.css');

?>

</head>
<body>

<?php

if ( DEBUG ) {
  echo '<div id="debug">';
  echo '<p>Debugging Info</p>';
  echo 'Page now: '.$page.'.<br>Page next: '.$page_next.'.<br>Def. refresh: '.$def_refresh.'.<br>Next refresh: '.$page_next_refresh.".\n";
  echo '</div>';
}

$pagename = $CFG['dir']['pageroot'] . 'index.php';

if ( file_exists( $pagename ) ) {
  require_once( $pagename );
//} else {
//  $error = 'Page not found: ' . $pagename . ' [' . $CFG['page'] . ']';
//  error( $error );
//  adminlog( $error );
}

?>

  <script type="text/javascript" src="js/jquery.min.js"></script>
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

  <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
  <script src="js/ie10-viewport-bug-workaround.js"></script>

</body>
</html>
