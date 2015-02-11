<?php

/**
 * CSID (Info Display)
 * Code:    Paul Vaughan
 * GFX:     Deedles
 * Feb 2010
 */

// do some including
require_once('functions.php');

/**
 * Setup variables and all that
 */
define('DEBUG', false);                 // set debugging as appropriate
$dir_bg     = './bg/';                  // define all the background images
$dir_dat    = './dat/';                 // dir the data files are in
$ext        = '.txt';                   // text file extension for loading data from files
$page       = get_config('page');       // a variable with the current page's name in it

// log it
adminlog('pageload_'.$page);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>If you can read this, something is not entirely right.</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <script type="text/javascript" src="jquery-1.4.2.js"></script>
        <script type="text/javascript" src="jquery.newsticker.js"></script>
        <script type="text/javascript" src="jquery.marquee.js"></script>
        <meta http-equiv="refresh" content="<?php echo get_refresh($page); //get_config('refresh'); ?>" />
        <?php
        // require the stylesheet instead of linking it as it has php to be processed within it
        require_once('style.css');
        ?>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#scroller marquee').marquee();

            $("#news1").newsticker(8000);
            $("#news2").newsticker(8000);
            $("#news3").newsticker(8000);
        });
        </script>
    </head>
    <body>

        <?php
        if (DEBUG) {
            echo '<div id="debug">';
            echo '<p>Debugging Info</p>';
            echo 'Page now: '.$page.'.<br />Page next: '.$page_next.'.<br />Def. refresh: '.$def_refresh.'.<br />Next refresh: '.$page_next_refresh.".\n";
            echo '</div>';
        }
        ?>

        <?php
        
        $pagename = 'page_'.get_config('page').'.php';
        if ( file_exists( $pagename ) ) {
            require_once( $pagename );
        }
        ?>

    </body>
</html>
