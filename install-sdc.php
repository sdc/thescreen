<?php

/**
 * SDC-specifica data adder.  Needs a working database in place first.
 */

require_once( 'functions.inc.php' );

$now = time();

echo "<h1>The Screen&trade; - SDC-specific installer</h1>";
echo "<p>This installer contains all the SQL needed to add SDC's veneer over the top of the generic system. Note that if you've already added this data once, this script may fail.</p>";

echo "<p>1. <code>config.inc.php</code> status: ";
if ( !file_exists( 'config.inc.php' ) ) {
  echo "doesn't seem to exist.</p>";
  exit(1);
} else {
  echo 'exists, so loading the details.</p>';
  require_once( 'config.inc.php' );
}

$error = false;

echo "<p>2. Attempting database connection: ";
$DB = new mysqli( $CFG['db']['host'], $CFG['db']['user'], $CFG['db']['pwd'], $CFG['db']['name'] );
if ( $DB->connect_error ) {
  echo "failed with error: " . $DB->connect_error . "</p>";
  $error = true;
} else {
  echo "success.</p>";
  //$DB->close();
}

if ( $error ) {
  echo "Stopping due to database connection error. Please fix it and reload this page.";
  exit(1);
}

if ( !isset( $_GET['confirm'] ) ) {
  echo "<p><hr></p>";
  echo "<p>All that is needed now is for you to click on the link below, which will install the SDC-specific data. Keep an eye out for error messages, just in case.</p>";
  echo '<p><a href="install-sdc.php?confirm">Let\'s install this thing!</a></p>'; 
  exit(0);
}

echo "<p>3. You confirmed that you want installation to start, so let's go... ";
echo "<p><hr></p>";
echo "<h2>Installation</h2>";

ob_flush();
flush();


function okay() {
  return "\n" . '<strong style="color:green;">...okay.</strong>';
}
function fail( $error ) {
  return "\n" . '<strong style="color:red;">...failed: ' . $error . '</strong>';
}
function run( $query ) {
  global $DB;

  echo htmlspecialchars( $query );
  $res = $DB->real_query( $query );
  if ( !$res ) {
    echo fail( $DB->error );
  } else {
    echo okay();
  }
  echo "\n";
}

$sql = array(
  'sdc'  => array(),
);

echo "<h3>Populating the status table</h3>";

$sql['sdc']['status'][] = "INSERT INTO `status` (`name`, `title`, `description`, `type`, `priority`, `created`, `modified`) VALUES
('email',               'Email Problem',          'There is a problem with the <em>email server</em>. You may not be able to send or receive email at this time, although you may like to try <em>webmail</em>. We are working to resolve the problem as soon as possible.',                              'amber', 10, " . $now . ", " . $now . "),
('internet',            'Internet Connection',    'There is a problem with the <em>Internet connection</em>. You may not be able to access external websites or resources at this time, although all internal systems should be unaffected. We are working to resolve the problem as soon as possible.',  'amber', 10, " . $now . ", " . $now . "),
('login',               'Login Problem',          'There is a problem with the <em>login server</em>. You may not be able to log in at this time. We are working to resolve the problem as soon as possible.',                                                                                            'amber', 10, " . $now . ", " . $now . "),
('moodle-maintenance',  'Moodle Maintenance',     'We''re undergoing planned maintenance on the <em>Moodle server or related servers</em>. Moodle may be slow, or completely unavailable.',                                                                                                               'amber', 10, " . $now . ", " . $now . "),
('moodle-upgrade',      'Moodle Upgrade',         'Moodle is ''at risk'' all week while we perform an upgrade. Downtime is expected on Monday, Tuesday and Wednesday.',                                                                                                                                   'amber', 10, " . $now . ", " . $now . "),
('phones',              'Phones Problem',         'There is a problem with the ShoreTel/VoIP phone system. We are working to resolve the problem as soon as possible.',                                                                                                                                   'amber', 10, " . $now . ", " . $now . "),
('server-generic',      'Generic Server Problem', 'There is a problem with one or more of our servers, which is affecting some of our systems. We are working to resolve the problem as soon as possible.',                                                                                               'amber', 10, " . $now . ", " . $now . "),
('wireless',            'Wireless Problem',       'We are currently experiencing issues with the <em>wireless network</em>. It may be only partially available or unavailable.<br><br>We are investigating and aim to resolve the issue as soon as possible.',                                            'amber', 10, " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['sdc']['status'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


echo "<h3>Populating the factoids table</h3>";

$sql['sdc']['factoids'][] = "INSERT INTO `factoids` (`fact`, `created`, `modified`) VALUES 
('In Computer Services we are constantly working hard to improve your I.T. experience.',                                                                                " . $now . ", " . $now . "),
('By the year 2020 there will be approximately 50 billion devices connected to the Internet.',                                                                          " . $now . ", " . $now . "),
('Don\'t forget about those fluffy bunny rabbits. Only print if you really need to!',                                                                                   " . $now . ", " . $now . "),
('If using Works or Open Office at home, save your files in RTF (Rich Text) format to ensure that you will be able to open them in college.',                           " . $now . ", " . $now . "),
('If your computer is stuck on the \'loading personalised settings\' page, press Control, Alt and Delete keys together, log out and then log straight back in again.',  " . $now . ", " . $now . "),
('In January we disallowed 20,800 printed pages, saving &pound;1,240!',                                                                                                 " . $now . ", " . $now . "),
('Never open an unknown email or piece of software: it might contain a virus, malware, spyware or phishing scam.',                                                      " . $now . ", " . $now . "),
('New members of staff need to be on the Human Resources system in order to get a computer account.',                                                                   " . $now . ", " . $now . "),
('Only print if you really need to!',                                                                                                                                   " . $now . ", " . $now . "),
('Over summer 2014 we added over 35 kilometres of hgh-speed ethernet cable to the network: that\'s from here to Exeter!',                                               " . $now . ", " . $now . "),
('Over summer 2014 we replaced 260 PCs with brand-new machines.',                                                                                                       " . $now . ", " . $now . "),
('Over summer 2014 we spent... a *lot* of money on IT systems and infrastructure!',                                                                                     " . $now . ", " . $now . "),
('Over summer 2014 we upgraded our state-of-the-art ILP system Leap.',                                                                                                  " . $now . ", " . $now . "),
('Over summer 2014 we upgraded to Moodle 2.7!',                                                                                                                         " . $now . ", " . $now . "),
('Please keep your data area (N drive) as clear as possible.',                                                                                                          " . $now . ", " . $now . "),
('Please make sure your anti-virus software is up to date.',                                                                                                            " . $now . ", " . $now . "),
('Please make your requests for software in a timely manner, not immediately before you need to teach with it!',                                                        " . $now . ", " . $now . "),
('Please remember to archive or delete your old emails.',                                                                                                               " . $now . ", " . $now . "),
('Please return all equipment ready for the next person to use and alert Computer Services to broken or missing hardware.',                                             " . $now . ", " . $now . "),
('South Devon College\'s Vantage Point campus contains approximately 120 miles of networking cable.',                                                                   " . $now . ", " . $now . "),
('Students need to be enrolled on an active course in order to get a computer account.',                                                                                " . $now . ", " . $now . "),
('The size limit for sending or receiving emails using your college email account is 5Mb.',                                                                             " . $now . ", " . $now . "),
('To change your password, press Control-Alt-Delete and choose \'Change Password\'.',                                                                                   " . $now . ", " . $now . "),
('To keep the email system running smoothly, please archive or delete your old emails.',                                                                                " . $now . ", " . $now . "),
('We have at least 75 interactive whiteboards to aid teaching and learning.',                                                                                           " . $now . ", " . $now . "),
('We manage over 130 Apple Macs.',                                                                                                                                      " . $now . ", " . $now . "),
('We manage over 1,800 Windows PCs.',                                                                                                                                   " . $now . ", " . $now . "),
('We strongly recommend you change your default password.',                                                                                                             " . $now . ", " . $now . "),
('We strongly suggest you have anti-virus and anti-malware software installed on your wireless device.',                                                                " . $now . ", " . $now . "),
('When using a Mac DO NOT save to HD1: you may lose your work.',                                                                                                        " . $now . ", " . $now . "),
('When using a Mac, if working from an external drive, ensure that the file is saved to HD2 first, work on the file and then copy it back.',                            " . $now . ", " . $now . "),
('When using your own laptop connect it up to the \'Wireless Guest\' network.',                                                                                         " . $now . ", " . $now . "),
('You can buy printer credits from Essentials, the LTRS and Cashiers.',                                                                                                  " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['sdc']['factoids'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


echo "<p>Done.</p>";


/**
 * That's the initial content loaded.
 */

echo "<p><hr></p>";
echo "<h2>Installation complete</h2>";
echo "<p>If you're reading this, chances are that everything worked as it should, but please check for red '<strong><span style=\"color:red;\">failed</span></strong>' text. If you need to re-run the installer, just reload this page.</p>";

echo '<p><a href="' . $CFG['adminpage'] . '">Click here to log in and configure the system</a>.</p>';
