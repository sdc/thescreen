<!-- Snow -->
<script type="text/javascript" src="snowstorm.js"></script>
<script type="text/javascript">
  snowStorm.snowColor = '#fff';
  snowStorm.flakesMax = 512;
  snowStorm.flakesMaxActive = 64;  // show more snow on screen at once default was 84, (I think)
  snowStorm.useTwinkleEffect = true; // let the snow flicker in and out of view
  snowStorm.flakeWidth = 20;            // max pixel width for snow element
  snowStorm.flakeHeight = 20;
  snowStorm.animationInterval = 25;
  snowStorm.freezeOnBlur = true;
  snowStorm.snowStick = true;
  snowStorm.useMeltEffect = false;
</script>
<!-- //END Snow -->

<div id="wrapper">
    <div id="status_img">
        <?php echo get_status_img(); ?>
    </div>
    <div id="status">
        <?php echo get_status_txt(); ?>
    </div>
    <div id="events">
        <?php echo get_events(3); ?>
    </div>
    <div id="addresses">
        <ul id="news1">
            <li>Telephone: <em>01803 540 654</em></li>
            <li>Moodle 2: <em>moodle.southdevon.ac.uk</em></li>
            <li>Public Website: <em>www.southdevon.ac.uk</em></li>
        </ul>
        <ul id="news2">
            <li>Email: <em>helpdesk@southdevon.ac.uk</em></li>
            <li>Leap: <em>leap.southdevon.ac.uk</em></li>
            <li>AV Bookings: <em>avbookings</em></li>
        </ul>
        <ul  id="news3">
            <li>Staff Website: <em>staff.southdevon.ac.uk</em></li>
            <li>LZ Bookings: <em>lzbookings.southdevon.ac.uk</em></li>
            <li>Remote Email: <em>https://webmail.southdevon.ac.uk</em></li>
        </ul>
    </div>
    <div id="factoid">
        <div id="factoid_inner">
            <?php echo get_random_factoid(); ?>
        </div>
    </div>
    <div id="factoid_pic">
        <?php echo get_figure(); ?>
    </div>
</div>
<div id="scroller">
    <?php echo get_scroller(); ?>
</div>
