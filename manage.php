<?php
require_once('functions.php');
adminlog('manage');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Manage the CSID</title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="jquery-ui-1.7.2.custom.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="jquery.jgrowl.css" media="screen" />
        <style type="text/css">
        body {
            background-color: #fff;
        }
        table {
            border: 1px solid #bbb;
            margin-top: 10px;
        }
        h2, h3, p, li, td, input, button, textarea, select {
            font-family: "Swis721 Md BT"; 
        }
        h1 {
            display: inline;
            text-shadow: rgba(0,0,0,0.4) 3px 2px 0.3em;
            font-family: "Swiss921 BT";
            font-weight: normal;
        }
        h1 + h2 {
            font-size: 1em;
            display: inline;
            text-shadow: rgba(0,0,0,0.4) 2px 1px 0.3em;
            margin-left: 50px;
        }
        td h2 {
            margin-top: 0;
            text-align: center;
            /*text-shadow: rgba(0,0,0,0.4) 3px 2px 0.3em;*/
        }
        td {
            border: 1px solid #ddd;
            width: 350px;
            vertical-align: top;
        }
        td#events {
            width: 600px;
        }
        li em {
            font-style: normal;
        }
        a {
            color: #00f;
        }
        a:link, a:visited, a:active {
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        table#stats {
            padding: 0; margin: 0; 
            border: 0;
        }
        table#stats td {
            padding: 0; margin: 0; 
            border: 0;
            border-bottom: 1px solid #000;
        }
        table#stats td.thin {
            width: 10px;
            white-space: nowrap;
        }
        td#showstopper {
            height: 250px;
        }
        </style>
        <script type="text/javascript" src="jquery-1.4.2.js"></script>
        <script type="text/javascript" src="jquery-ui-1.7.2.custom.min.js"></script>
        <script type="text/javascript" src="jquery.jgrowl.js"></script>
        <script type="text/javascript" src="jquery.counter-1.0.js"></script>
        <script type="text/javascript">
        $(document).ready(function(){
            $('#datepicker').datepicker({ 
                dateFormat: 'yy-mm-dd', 
                firstDay: 1, 
                yearRange: '2010:2011', 
                numberOfMonths: 2
            });
            $("#showstopper_textbox").counter();
<?php
if (!isset($_GET['msg'])) {
    echo '$.jGrowl("Remember that this is LIVE.", { life: 2000 });';
} else {
    if ($_GET['msg'] == 'event_add_success' ) { $msg='Successfully added an event.'; }
    else if ($_GET['msg'] == 'event_add_fail' ) { $msg='Failed to add an event.'; }
    else if ($_GET['msg'] == 'event_del_success' ) { $msg='Successfully deleted an event.'; }
    else if ($_GET['msg'] == 'event_del_fail' ) { $msg='Failed to delete an event.'; }
    else if ($_GET['msg'] == 'refresh_edit_success' ) { $msg='Successfully edited the refresh period.'; }
    else if ($_GET['msg'] == 'refresh_edit_fail' ) { $msg='Failed to edit the refresh period.'; }
    else if ($_GET['msg'] == 'status_edit_success' ) { $msg='Successfully changed the status.'; }
    else if ($_GET['msg'] == 'status_edit_fail' ) { $msg='Failed to change the status.'; }
    else if ($_GET['msg'] == 'rssfeed_edit_success' ) { $msg='Successfully changed the RSS feed.'; }
    else if ($_GET['msg'] == 'rssfeed_edit_fail' ) { $msg='Failed to change the RSS feed.'; }
    else if ($_GET['msg'] == 'showstopper_edit_success' ) { $msg='Successfully changed the Showstopper text.'; }
    else if ($_GET['msg'] == 'showstopper_edit_fail' ) { $msg='Failed to change the Showstopper text.'; }
    else if ($_GET['msg'] == 'stat_edit_success' ) { $msg='Successfully changed the stat text.'; }
    else if ($_GET['msg'] == 'stat_edit_fail' ) { $msg='Failed to change the stat text.'; }
    else if ($_GET['msg'] == 'statoids_make' ) { $msg='Made combined stats/factiods (statoids) table.'; }
    else if ($_GET['msg'] == 'page_edit_success' ) { $msg='Successfully changed the page.'; }
    else if ($_GET['msg'] == 'page_edit_fail' ) { $msg='Failed to change the page.'; }
    else if ($_GET['msg'] == 'figure_edit_success' ) { $msg='Successfully changed the specific figure.'; }
    else if ($_GET['msg'] == 'figure_edit_fail' ) { $msg='Failed to change the specific figure.'; }

    if ($msg != '') { echo '$.jGrowl("'.$msg.'", { life: 4000 });'."\n"; }
}
?>
        });
        </script>
    </head>
    <body>
        <h1>Manage the CSID</h1>
        <h2><a href="manage.php">Click to refresh this page</a>.</h2>
        <table>
            <tr>
                <!-- screen type -->
                <td>
                    <h2>Page Type</h2>
                    <p>Chage the page type:</p>
<?php
echo make_page_change_menu();
?>
                </td>
                <!-- refresh statoids -->
                <td>
                    <h2>Refresh Stats</h2>
                    <p>Stats last refreshed <?php echo get_config('statoids_upd'); ?>. This happens automatically, randomly, throughout the day. 
                    <a href="statoids_make.php">Click here</a> to refresh the statistics manually. </p>
                </td>
            </tr>
            <tr>
                <!-- status stuff -->
                <td id="status">
                    <h2>Current status</h2>
                    <p>Change status to:</p>
<?php
echo make_status_change_menu();
?>
                </td>

                <!-- event stuff -->
                <td id="events">
                    <h2>Events</h2>
                    <p>All future events: (events which have passed are not shown)</p>
                    <?php echo get_events(20, true); ?>
                    <h3>Add new event:</h3>
                    <form action="event_add.php" method="get">
                        Date:
                        <input type="text" name="date" id="datepicker" />
                        <br /> Details:
                        <input type="text" name="text" size="50" maxlength="255" />
                        <input type="submit" value="Add event" />
                    </form>
                </td>
            </tr>
            <tr>

                <!-- refresh stuff -->
                <td>
                    <h2>Refresh</h2>
                    <p>Number of seconds between page refreshes.</p>
                    <form action="refresh_edit.php" method="get">
                        Seconds:
                        <input type="text" value="<?php echo get_config('refresh'); ?>" name="seconds" size="4" maxlength="4" />
                        <input type="submit" value="Set" />
                    </form>
                    <ul>
                        <li>(Testing: <a href="refresh_edit.php?seconds=1">1</a> 
                        <a href="refresh_edit.php?seconds=5">5</a> 
                        <a href="refresh_edit.php?seconds=10">10</a> 
                        <a href="refresh_edit.php?seconds=15">15</a> 
                        <a href="refresh_edit.php?seconds=30">30</a> 
                        <a href="refresh_edit.php?seconds=45">45</a> 
                        <a href="refresh_edit.php?seconds=60">60</a> secs)</li>
                        <li><a href="refresh_edit.php?seconds=300">5 mins</a> (Default)</li>
                        <li><a href="refresh_edit.php?seconds=600">10 mins</a></li>
                        <li><a href="refresh_edit.php?seconds=900">15 mins</a></li>
                        <li><a href="refresh_edit.php?seconds=1800">30 mins</a></li>
                    </ul>
                </td>

                <!-- RSS feed stuff -->
                <td>
                    <h2>RSS Feed</h2>
                    <p>Location (URL) of the RSS feed for the scroller.</p>
                    <form action="rssfeed_edit.php" method="get">
                        RSS Feed location (URL):
                        <input type="text" value="<?php echo get_config('rssfeed'); ?>" name="rssfeed" size="50" maxlength="100" />
                        <input type="submit" value="Set" />
                    </form>
                    <ul>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml">BBC Technology, UK Edition</a> (Default)</li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/uk/rss.xml">BBC UK, UK Edition</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/rss.xml">BBC England, UK Edition</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/sci/tech/rss.xml">BBC Science &amp; Environment, UK Edition</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://rss.slashdot.org/Slashdot/slashdot">Slashdot: News for nerds, stuff that matters</a></li>
                        <li><a href="rssfeed_edit.php?rssfeed=http://news.southdevon.ac.uk/items.atom?body=txt">South Devon College News</a></li>
                    </ul>
                </td>
            </tr>
            <tr>
                <!-- stats -->
                <td rowspan="2">
                    <h2>Stats</h2>
                    <p>Change the details and click the update button:</p>
                    <table id="stats">
                        <?php echo get_stats_form(); ?>
                    </table>
                </td>
                <!-- showstopper -->
                <td id="showstopper">
                    <h2>Showstopper Text</h2>
                    <p>Text required for the 'showstopper' screen. Don't use any formatting. Will appear in UPPERCASE. You have *about* 170 characters maximum.</p>
                    <form action="showstopper_edit.php" method="get">
                        <textarea id="showstopper_textbox" name="showstopper" cols="50" rows="4"><?php echo get_config('showstopper'); ?></textarea>
                        <input type="submit" value="Change" />
                    </form>
                </td>
            </tr>
            <tr>
                <!-- logs -->
                <td id="showstopper">
                    <h2>Logs</h2>
                    <p>Last few log entries.</p>
                    <?php echo get_last_log(12); ?>
                </td>
            </tr>
            <tr>
                <!-- specific image -->
                <td>
                    <h2>Specific Figure</h2>
                    <p>Choose a specific figure from the list below, or 'no' for a random figure.</p>
                    <form action="specific_fig_edit.php" method="get">
                        <select name="figure">
                            <?php
                            get_special_figure_list();
                            ?>
                        </select>
                        <input type="submit" value="Set" />
                    </form>
                </td>
                <!-- blank -->
                <td>
                </td>
            </tr>
        </table>
    </body>
</html>
