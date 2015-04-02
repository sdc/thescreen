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
  ('You can buy printer credits from Essentials, the LTRS and Cashiers.',                                                                                                 " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['sdc']['factoids'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


echo "<h3>Populating the events table</h3>";

$sql['sdc']['events'][] = "INSERT INTO `events` (`start`, `text`, `hidden`, `created`, `modified`) VALUES
  ('2010-04-07', 'Day one of disaster recovery testing for mission-critical college systems.', 0, " . $now . ", " . $now . "),
  ('2010-03-07', 'Day one (of three) of disaster recovery testing for mission-critical college systems.', 1, " . $now . ", " . $now . "),
  ('2010-04-01', 'End of Term', 1, " . $now . ", " . $now . "),
  ('2010-03-10', 'College Open Evening', 0, " . $now . ", " . $now . "),
  ('2010-04-01', 'End of Term & Start of Easter Holidays', 1, " . $now . ", " . $now . "),
  ('2010-04-19', 'Start of Summer Term', 0, " . $now . ", " . $now . "),
  ('2010-03-08', 'Publis launch of the new South Devon College public website', 1, " . $now . ", " . $now . "),
  ('2010-03-08', 'Public launch of the new South Devon College public website', 0, " . $now . ", " . $now . "),
  ('2010-03-12', 'Sean Andrews (below) is leaving Computer Services today for pastures new :( Pop in and say ''bye''!', 1, " . $now . ", " . $now . "),
  ('2010-03-12', 'Sean Andrews (below) is leaving Computer Services today for pastures new. &#x2639; Pop in and say ''bye''!', 0, " . $now . ", " . $now . "),
  ('2010-03-17', 'Happy L&aacute; Fh&eacute;ile P&aacute;draig (Saint Patrick''s Day)! ', 0, " . $now . ", " . $now . "),
  ('2010-04-01', 'Ask inside about renting a meerkat to de-ice your car.', 0, " . $now . ", " . $now . "),
  ('2010-04-01', 'End of Term & Start of Easter Holidays', 0, " . $now . ", " . $now . "),
  ('2010-04-08', 'Software upgrades and new software install - your PC may take longer then usual to boot (once only).', 0, " . $now . ", " . $now . "),
  ('2010-06-11', 'This display screen rebuilt after the last one couldn''t take the punishment.', 0, " . $now . ", " . $now . "),
  ('2010-06-12', 'Pawzs Race!', 0, " . $now . ", " . $now . "),
  ('2010-07-05', 'Welcome Week ', 0, " . $now . ", " . $now . "),
  ('2010-06-16', 'Justin Lee Collins Book Signing at Torbay Bookshop', 0, " . $now . ", " . $now . "),
  ('2010-07-09', 'Last Teaching Day of Term', 0, " . $now . ", " . $now . "),
  ('2010-07-16', 'Staff Summer Celebration Event', 0, " . $now . ", " . $now . "),
  ('2010-07-01', '14 to 16 Awards Ceremony', 0, " . $now . ", " . $now . "),
  ('2010-07-01', 'Fundraising Dinner with Friends of South Devon College', 0, " . $now . ", " . $now . "),
  ('2010-07-19', 'Summer Break', 0, " . $now . ", " . $now . "),
  ('2010-08-23', 'HE Open Day', 0, " . $now . ", " . $now . "),
  ('2010-09-01', 'Enrollment Day', 0, " . $now . ", " . $now . "),
  ('2010-09-02', 'College Open Evening', 0, " . $now . ", " . $now . "),
  ('2010-11-24', 'College Open Evening', 0, " . $now . ", " . $now . "),
  ('2010-07-02', 'Technology for Learning Day', 0, " . $now . ", " . $now . "),
  ('2010-08-16', 'Moode down for major planned maintenance', 0, " . $now . ", " . $now . "),
  ('2010-08-17', 'Moodle \"at risk\" period due to major planned maintenance', 0, " . $now . ", " . $now . "),
  ('2010-08-27', 'Moodle down between 12 and 1pm for maintenance.', 0, " . $now . ", " . $now . "),
  ('2010-09-27', 'University Centre Opens', 0, " . $now . ", " . $now . "),
  ('2010-09-27', 'All University level students induction week', 0, " . $now . ", " . $now . "),
  ('2010-09-28', 'BE FAIR Week', 0, " . $now . ", " . $now . "),
  ('2010-10-01', 'HE Freshers Fest', 0, " . $now . ", " . $now . "),
  ('2010-10-01', 'University Level Courses Open Morning. There is still time to apply for this year! 9am - 1pm ', 0, " . $now . ", " . $now . "),
  ('2010-10-22', 'Day of Celebration @ the RICC', 0, " . $now . ", " . $now . "),
  ('2010-11-01', 'Chef, Nathan Outlaw visiting SDC', 0, " . $now . ", " . $now . "),
  ('2010-11-02', 'University Courses information evening for Level 3 students - 1800 - 2000', 0, " . $now . ", " . $now . "),
  ('2010-11-08', 'AoC Colleges week', 0, " . $now . ", " . $now . "),
  ('2010-11-11', 'AimHigher Associates Scheme launch', 0, " . $now . ", " . $now . "),
  ('2010-11-24', 'College Open evening', 1, " . $now . ", " . $now . "),
  ('2010-11-27', 'Animal & Sciences Activity day - 0900 -1300', 0, " . $now . ", " . $now . "),
  ('2010-12-17', 'End of Term', 0, " . $now . ", " . $now . "),
  ('2010-12-01', 'Christmas begins at South Devon College', 0, " . $now . ", " . $now . "),
  ('2010-12-01', 'Christmas Market in the Street', 0, " . $now . ", " . $now . "),
  ('2010-12-13', 'Christmas Market in the Street', 0, " . $now . ", " . $now . "),
  ('2010-12-13', 'SDC SU Karaoke', 0, " . $now . ", " . $now . "),
  ('2010-12-16', 'Christmas Quiz', 0, " . $now . ", " . $now . "),
  ('2010-12-19', 'Student Christmas Party @ Bohemia', 0, " . $now . ", " . $now . "),
  ('2010-12-14', 'Web Team''s web products release', 0, " . $now . ", " . $now . "),
  ('2010-12-17', 'Helpdesk Closed 12-1pm', 0, " . $now . ", " . $now . "),
  ('2010-12-17', 'Brian Crocker''s last day (say bye!)', 0, " . $now . ", " . $now . "),
  ('2011-01-06', 'Culinary Competition in the Gallery', 0, " . $now . ", " . $now . "),
  ('2011-01-27', 'College Open Evening - 5pm till 8pm', 0, " . $now . ", " . $now . "),
  ('2011-01-10', 'Be Healthy week & Re-Fresher''s week', 0, " . $now . ", " . $now . "),
  ('2011-02-03', 'Studio A official opening', 0, " . $now . ", " . $now . "),
  ('2011-02-07', 'National Apprenticeships Week', 0, " . $now . ", " . $now . "),
  ('2011-03-08', 'Official opening of the University Centre', 0, " . $now . ", " . $now . "),
  ('2011-03-09', 'College Open evening - 5pm till 8pm', 0, " . $now . ", " . $now . "),
  ('2011-03-12', 'University Centre Open Day from 10am - 3pm', 0, " . $now . ", " . $now . "),
  ('2011-04-11', 'Easter Break', 0, " . $now . ", " . $now . "),
  ('2011-04-26', 'Summer Term Begins', 0, " . $now . ", " . $now . "),
  ('2011-04-29', 'Royal Wedding', 0, " . $now . ", " . $now . "),
  ('2011-05-02', 'Bank Holiday', 0, " . $now . ", " . $now . "),
  ('2011-05-30', 'Half Term', 0, " . $now . ", " . $now . "),
  ('2011-03-22', 'Progression Awards ', 0, " . $now . ", " . $now . "),
  ('2011-04-05', 'My Night ', 0, " . $now . ", " . $now . "),
  ('2011-04-13', 'Sports Academies Day ', 0, " . $now . ", " . $now . "),
  ('2011-04-28', 'Bmad Festival Starts', 0, " . $now . ", " . $now . "),
  ('2011-05-10', 'Open Evening ', 0, " . $now . ", " . $now . "),
  ('2011-05-16', 'Travel and Tourism Awards ', 0, " . $now . ", " . $now . "),
  ('2011-05-19', 'Devon County Show Starts', 0, " . $now . ", " . $now . "),
  ('2011-06-09', 'Public Services Mess Dinner', 0, " . $now . ", " . $now . "),
  ('2011-06-10', 'University Art students at Bovey Tracey Crafts Fayre', 0, " . $now . ", " . $now . "),
  ('2011-06-19', 'Torbay Half Marathon', 0, " . $now . ", " . $now . "),
  ('2011-06-21', 'The Big Bang STEM event at Exeter Uni', 0, " . $now . ", " . $now . "),
  ('2011-06-28', 'Art & Media Graduate show at Torre Abbey', 0, " . $now . ", " . $now . "),
  ('2011-06-29', 'Automotive & Marine Engineering student of the Year presentation', 0, " . $now . ", " . $now . "),
  ('2011-06-06', '14 - 16 Awards evening', 0, " . $now . ", " . $now . "),
  ('2011-07-06', '14 - 16 Awards evening', 0, " . $now . ", " . $now . "),
  ('2011-08-22', 'University Courses Open Day, from 10am - 4pm', 0, " . $now . ", " . $now . "),
  ('2011-09-01', 'Open Evening - from 5-8pm', 0, " . $now . ", " . $now . "),
  ('2011-06-02', 'Planned maintenance: web server downtime (7.30-9am)', 0, " . $now . ", " . $now . "),
  ('2011-06-18', 'Server Maintenance', 0, " . $now . ", " . $now . "),
  ('2011-06-19', 'Server Maintenance', 0, " . $now . ", " . $now . "),
  ('2011-06-17', 'Dave Turner performs miracles', 0, " . $now . ", " . $now . "),
  ('2011-07-13', 'Moodle 2 Beta down for maintenance at 4.00pm', 0, " . $now . ", " . $now . "),
  ('2011-08-03', 'Wireless System Maintenance - Limited Availability', 0, " . $now . ", " . $now . "),
  ('2011-08-04', 'Wireless System Maintenance - Limited Availability', 0, " . $now . ", " . $now . "),
  ('2011-08-05', 'Wireless System Maintenance - Limited Availability', 0, " . $now . ", " . $now . "),
  ('2011-08-06', 'Network Maintenance', 0, " . $now . ", " . $now . "),
  ('2011-08-07', 'Network Maintenance', 0, " . $now . ", " . $now . "),
  ('2011-07-26', 'EBS Upgrade - EBS & SPARKe unavailable from 9am', 0, " . $now . ", " . $now . "),
  ('2011-07-22', 'Moodle Upgrade - Moodle will be unavailable', 0, " . $now . ", " . $now . "),
  ('2011-08-01', 'Moodle available again, now Moodle 2!', 0, " . $now . ", " . $now . "),
  ('2011-07-25', 'Moodle unavailable: planned maintenance for upgrade', 0, " . $now . ", " . $now . "),
  ('2011-07-26', 'Moodle unavailable: planned maintenance for upgrade', 0, " . $now . ", " . $now . "),
  ('2011-07-27', 'Moodle unavailable: planned maintenance for upgrade', 0, " . $now . ", " . $now . "),
  ('2011-07-28', 'Moodle unavailable: planned maintenance for upgrade', 0, " . $now . ", " . $now . "),
  ('2011-07-29', 'Moodle unavailable: planned maintenance for upgrade', 0, " . $now . ", " . $now . "),
  ('2011-09-12', 'Start of term', 0, " . $now . ", " . $now . "),
  ('2011-11-23', 'Open Evening', 0, " . $now . ", " . $now . "),
  ('2011-09-08', 'HE Graduation Event & Apprenticeship Awards', 0, " . $now . ", " . $now . "),
  ('2011-11-04', 'Staff & FE Day of Celebration/CPD ', 0, " . $now . ", " . $now . "),
  ('2011-12-15', 'Last teaching day', 0, " . $now . ", " . $now . "),
  ('2012-01-03', 'First Day of Term', 0, " . $now . ", " . $now . "),
  ('2011-12-25', 'Christmas Day!', 0, " . $now . ", " . $now . "),
  ('2012-02-13', 'Half Term', 0, " . $now . ", " . $now . "),
  ('2012-03-21', 'CPD Day', 1, " . $now . ", " . $now . "),
  ('2011-09-10', 'Fishstock in Brixham ', 0, " . $now . ", " . $now . "),
  ('2011-10-17', 'Password change day (staff)', 0, " . $now . ", " . $now . "),
  ('2011-10-24', 'Planned Maintenance on all Moodles', 0, " . $now . ", " . $now . "),
  ('2011-11-11', 'Remembrance Day', 0, " . $now . ", " . $now . "),
  ('2011-11-14', 'South Devon Enterprise Week', 0, " . $now . ", " . $now . "),
  ('2011-11-15', 'South Devon Enterprise Week', 0, " . $now . ", " . $now . "),
  ('2011-11-16', 'South Devon Enterprise Week', 0, " . $now . ", " . $now . "),
  ('2011-11-17', 'South Devon Enterprise Week', 0, " . $now . ", " . $now . "),
  ('2011-11-18', 'South Devon Enterprise Week', 0, " . $now . ", " . $now . "),
  ('2011-12-14', 'Christmas Fayre in the Street', 0, " . $now . ", " . $now . "),
  ('2011-12-15', 'Christmas Fayre in the Street', 0, " . $now . ", " . $now . "),
  ('2011-12-16', 'Christmas Fayre in the Street', 0, " . $now . ", " . $now . "),
  ('2012-01-26', 'College Open Evening', 0, " . $now . ", " . $now . "),
  ('2012-01-25', '80th Anniversary Appeal Launch ', 0, " . $now . ", " . $now . "),
  ('2012-02-10', 'South Devon Business Excellence Awards 2011', 0, " . $now . ", " . $now . "),
  ('2012-03-01', 'Presentation of New Engineering Foundation STEM Assured Award ', 0, " . $now . ", " . $now . "),
  ('2012-03-13', '''Golden'' Oldies Sports Supper', 1, " . $now . ", " . $now . "),
  ('2012-03-17', '80th Anniversary Community Event', 0, " . $now . ", " . $now . "),
  ('2012-06-13', 'World Skills South West Regional Heats: Beauty', 0, " . $now . ", " . $now . "),
  ('2012-06-25', 'World Skills South West Regional Heats: Health & Social Care', 0, " . $now . ", " . $now . "),
  ('2012-06-21', '80th Anniversary Gala fund raising dinner', 0, " . $now . ", " . $now . "),
  ('2012-03-07', 'Open Evening', 0, " . $now . ", " . $now . "),
  ('2012-06-02', 'Organised by British Marine Federation South West, in association with South Devon Marine and event sponsors South Devon College', 1, " . $now . ", " . $now . "),
  ('2012-06-02', 'Try a boat - Organised by British Marine Federation South West', 0, " . $now . ", " . $now . "),
  ('2012-06-11', 'Urgent web system downtime at 5.30pm for 10 mins.', 0, " . $now . ", " . $now . "),
  ('2012-07-09', 'CPD Days', 0, " . $now . ", " . $now . "),
  ('2012-07-13', 'Staff Summer Celebration Day', 0, " . $now . ", " . $now . "),
  ('2012-08-28', 'Student Induction', 0, " . $now . ", " . $now . "),
  ('2012-06-26', 'World Skills Event - Caring', 0, " . $now . ", " . $now . "),
  ('2012-06-29', 'South Devon Cultural Festival', 0, " . $now . ", " . $now . "),
  ('2012-07-03', '14-16 Awards Event', 0, " . $now . ", " . $now . "),
  ('2012-07-11', 'Police Awards Ceremony', 0, " . $now . ", " . $now . "),
  ('2012-07-20', 'Royal Horticulture Society Visit for Britain in Bloom judging', 0, " . $now . ", " . $now . "),
  ('2012-08-20', 'HE Open Day', 0, " . $now . ", " . $now . "),
  ('2012-09-06', 'Open Evening ', 0, " . $now . ", " . $now . "),
  ('2012-09-13', 'HE Awards/Apprenticeship Awards', 0, " . $now . ", " . $now . "),
  ('2012-10-05', 'Bradley Lane Official Opening', 1, " . $now . ", " . $now . "),
  ('2012-10-25', 'Bradley Lane Official Opening ', 0, " . $now . ", " . $now . "),
  ('2012-11-09', 'FE & Staff Awards Day of Celebration', 0, " . $now . ", " . $now . "),
  ('2012-11-21', 'Open Evening', 0, " . $now . ", " . $now . "),
  ('2012-11-22', 'Reunion Dinner', 0, " . $now . ", " . $now . "),
  ('2012-11-29', 'Torbay Sports Awards Dinner', 0, " . $now . ", " . $now . "),
  ('2012-12-05', 'Made in Devon Christmas Market', 0, " . $now . ", " . $now . "),
  ('2012-12-06', 'Made in Devon Christmas Market', 0, " . $now . ", " . $now . "),
  ('2013-01-24', 'Open Evening', 0, " . $now . ", " . $now . "),
  ('2013-03-05', 'Open Evening ', 0, " . $now . ", " . $now . "),
  ('2013-03-09', 'Community Open Day', 0, " . $now . ", " . $now . "),
  ('2012-10-17', 'Parents & Carers Event', 0, " . $now . ", " . $now . "),
  ('2012-12-01', 'Devon Studio School - Open Day - 10am-2pm', 0, " . $now . ", " . $now . "),
  ('2013-03-05', 'Autism Professional Awards - The National Autistic Society', 1, " . $now . ", " . $now . "),
  ('2013-03-16', 'South Devon 10k Fun Run - raising funds for South Devon College', 1, " . $now . ", " . $now . "),
  ('2013-03-16', 'South Devon 10k Fun Run - Raising Funds for Friends of South Devon College', 1, " . $now . ", " . $now . "),
  ('2013-02-13', 'Open Evening at Newton Abbot Campus ', 0, " . $now . ", " . $now . "),
  ('2013-02-18', 'Moodle 2.4 Upgrade - At risk all day', 0, " . $now . ", " . $now . "),
  ('2013-02-19', 'Moodle 2.4 Upgrade - At risk all day', 0, " . $now . ", " . $now . "),
  ('2013-02-20', 'Moodle 2.4 Upgrade - At risk all day', 0, " . $now . ", " . $now . "),
  ('2013-02-21', 'Moodle 2.4 Upgrade - At risk all day', 0, " . $now . ", " . $now . "),
  ('2013-02-22', 'Moodle 2.4 Upgrade - At risk all day', 0, " . $now . ", " . $now . "),
  ('2013-02-25', 'Computer Services Moodle Page - Launch Day', 0, " . $now . ", " . $now . "),
  ('2013-03-11', 'Apprenticeship Week March 11-15th', 0, " . $now . ", " . $now . "),
  ('2013-03-18', 'SCI FEST Week in the Street!', 0, " . $now . ", " . $now . "),
  ('2013-03-16', 'South Devon 10k & Fun Run - Raising Funds for Friends of South Devon College', 0, " . $now . ", " . $now . "),
  ('2013-05-16', 'Moodle 2.5 Release', 0, " . $now . ", " . $now . "),
  ('2013-03-22', 'Sci Fest - In the Street', 1, " . $now . ", " . $now . "),
  ('2013-03-22', 'Sci Fest Week - in the Street', 0, " . $now . ", " . $now . "),
  ('2013-03-27', 'Newton Abbot Campus Open Evening', 0, " . $now . ", " . $now . "),
  ('2013-05-15', 'South Devon College Open Evening', 0, " . $now . ", " . $now . "),
  ('2013-03-25', 'My Course Evening', 0, " . $now . ", " . $now . "),
  ('2013-03-27', 'My Course Evening', 0, " . $now . ", " . $now . "),
  ('2013-05-04', 'BMAD at Paignton Seafront', 0, " . $now . ", " . $now . "),
  ('2013-05-05', 'BMAD at Paignton Seafront', 0, " . $now . ", " . $now . "),
  ('2013-05-16', 'Devon County Show ', 0, " . $now . ", " . $now . "),
  ('2013-08-19', 'University Open Day ', 0, " . $now . ", " . $now . "),
  ('2013-08-15', 'A Level Results day', 0, " . $now . ", " . $now . "),
  ('2013-05-02', 'Animal Care Research - Celebrity Nick Baker is Guest Speaker ', 0, " . $now . ", " . $now . "),
  ('2013-03-27', 'Afternoon Tea in Horizons at 3.30pm for Â£6.00', 0, " . $now . ", " . $now . "),
  ('2013-03-28', 'Masquerade Dinner in Horizons -7.00pm', 1, " . $now . ", " . $now . "),
  ('2013-04-18', 'Italian Night in Horizons - 7.00pm', 0, " . $now . ", " . $now . "),
  ('2013-09-01', 'REMEMBER - Office 2010 is coming!!!!!!', 1, " . $now . ", " . $now . "),
  ('2013-06-13', 'Horizons End of Term Gala Dinner ''Come Cruise with Us''  ', 0, " . $now . ", " . $now . "),
  ('2013-06-18', 'Password changes for staff', 1, " . $now . ", " . $now . "),
  ('2013-08-01', 'REMEMBER - Office 2010 is coming!!!!!! ', 1, " . $now . ", " . $now . "),
  ('2013-07-12', '''Best of British'' Staff Event', 0, " . $now . ", " . $now . "),
  ('2013-07-26', 'Software Upgrades for Administration Computers', 0, " . $now . ", " . $now . "),
  ('2013-09-01', 'REMEMBER - Office 2010 is here!!!!!! ', 0, " . $now . ", " . $now . "),
  ('2013-07-25', 'Moodle upgrades', 0, " . $now . ", " . $now . "),
  ('2013-09-05', 'College Open Evening - 5pm - 8pm', 0, " . $now . ", " . $now . "),
  ('2013-08-31', 'South West Energy Cente Launch Day ', 0, " . $now . ", " . $now . "),
  ('2013-09-13', 'South Devon Skills Festival ', 0, " . $now . ", " . $now . "),
  ('2013-10-04', 'Naming of the Whitley Room ', 0, " . $now . ", " . $now . "),
  ('2013-11-07', 'Torbay Sports Awards ', 0, " . $now . ", " . $now . "),
  ('2013-10-12', 'Open Event - Newton Abbot Campus 10 - 1pm', 0, " . $now . ", " . $now . "),
  ('2013-11-19', 'Open Event - Vantage Point Campus 5pm - 8pm', 0, " . $now . ", " . $now . "),
  ('2013-11-18', 'South Devon Enterprise Week', 0, " . $now . ", " . $now . "),
  ('2013-11-15', 'Day of Celebration', 0, " . $now . ", " . $now . "),
  ('2013-11-19', 'College Open Evening 5pm-8pm in the Gallery and The Street', 0, " . $now . ", " . $now . "),
  ('2013-11-18', 'South Devon Enterprise Week', 1, " . $now . ", " . $now . "),
  ('2013-12-03', '''Made in Devon'' Christmas Fayre in The Street', 0, " . $now . ", " . $now . "),
  ('2013-12-04', '''Made in Devon'' Christmas Fayre in The Street', 0, " . $now . ", " . $now . "),
  ('2013-12-13', 'Festive Christmas Jumper Friday - Greenhouse Cafe', 0, " . $now . ", " . $now . "),
  ('2013-12-13', 'Inspirations Christmas Raffle Draw', 0, " . $now . ", " . $now . "),
  ('2013-12-09', 'Christmas Art Exhibition in The Gallery', 0, " . $now . ", " . $now . "),
  ('2013-12-11', 'Newton Abbot Campus Christmas Party', 0, " . $now . ", " . $now . "),
  ('2013-12-11', 'Mama Bear''s Nativity Play', 0, " . $now . ", " . $now . "),
  ('2013-12-13', 'Santa''s Grotto in the Student Union Office', 0, " . $now . ", " . $now . "),
  ('2013-12-16', 'Giant Pass the Parcel', 0, " . $now . ", " . $now . "),
  ('2013-12-17', 'Staff and student carol singing', 0, " . $now . ", " . $now . "),
  ('2013-12-18', 'SDC SU Christmas Photo Booth', 0, " . $now . ", " . $now . "),
  ('2013-12-18', 'Noss Marine Academy Christmas Party', 0, " . $now . ", " . $now . "),
  ('2013-12-19', 'Christmas Interactive Team Quiz', 0, " . $now . ", " . $now . "),
  ('2013-12-19', 'Students'' last day at College', 0, " . $now . ", " . $now . "),
  ('2013-12-20', 'Staff Development Day', 0, " . $now . ", " . $now . "),
  ('2013-12-20', 'Principal''s end of term speech to staff', 0, " . $now . ", " . $now . "),
  ('2013-12-20', 'Principal''s end of term speech to staff', 1, " . $now . ", " . $now . "),
  ('2014-01-23', 'First College open evening of 2014', 0, " . $now . ", " . $now . "),
  ('2014-03-08', 'Community Open Day', 0, " . $now . ", " . $now . "),
  ('2014-01-06', 'First day of term', 0, " . $now . ", " . $now . "),
  ('2014-02-17', 'Half Term Begins', 0, " . $now . ", " . $now . "),
  ('2014-03-12', 'Staff CPD Day', 0, " . $now . ", " . $now . "),
  ('2014-04-07', 'Easter Holidays Begin', 0, " . $now . ", " . $now . "),
  ('2014-01-13', 'Have you received a new phone or mobile device for Christmas??  There are instructions for how to set up your college email on your new device on our Computer Services Moodle pages', 0, " . $now . ", " . $now . "),
  ('2014-01-21', 'Password Change Day!!', 0, " . $now . ", " . $now . "),
  ('2014-12-08', 'QAA Higher Education Review', 0, " . $now . ", " . $now . "),
  ('2014-02-08', 'Newton Abbot Campus Open Day 10.00 - 1.00pm', 0, " . $now . ", " . $now . "),
  ('2014-02-27', 'Open Evening', 0, " . $now . ", " . $now . "),
  ('2014-02-13', 'My Night', 0, " . $now . ", " . $now . "),
  ('2014-03-07', 'Apprenticeship Week 3-7th March', 0, " . $now . ", " . $now . "),
  ('2014-02-26', 'Web Team Intern interviews', 0, " . $now . ", " . $now . "),
  ('2014-04-26', 'South Devon 10k and Fun Run', 0, " . $now . ", " . $now . "),
  ('2014-03-26', 'College Open Evening', 0, " . $now . ", " . $now . "),
  ('2014-04-26', 'South Devon College NOSS Marine Academy Activity Day!', 0, " . $now . ", " . $now . "),
  ('2014-05-08', 'Horizons Pop-Up Tour at the Royal Seven Stars, Totnes', 0, " . $now . ", " . $now . "),
  ('2014-05-03', 'South Devon Fitness Festival', 0, " . $now . ", " . $now . "),
  ('2014-05-26', 'Half Term!', 0, " . $now . ", " . $now . "),
  ('2014-07-07', 'Staff CPD Days', 0, " . $now . ", " . $now . "),
  ('2014-06-26', 'Horizons End of Term Gala Fundraising Dinner', 0, " . $now . ", " . $now . "),
  ('2014-06-18', 'Automotive Student of the Year Awards', 1, " . $now . ", " . $now . "),
  ('2014-06-19', 'World Skills UK Regional Caring Competition', 0, " . $now . ", " . $now . "),
  ('2014-05-12', 'University Students Research Showcase in the University Centre, highlighting student research and academic scholarship', 0, " . $now . ", " . $now . "),
  ('2014-05-20', 'Open evening from 5-8pm', 0, " . $now . ", " . $now . "),
  ('2014-05-24', 'Try a Boat FREE, Dartmouth - with Noss Marine Academy and the BMF', 0, " . $now . ", " . $now . "),
  ('2014-06-04', 'Hair & Fashion show at the RICC', 0, " . $now . ", " . $now . "),
  ('2014-06-07', 'Newton Abbot Campus Open Day - from 10am - 1pm', 0, " . $now . ", " . $now . "),
  ('2014-06-13', 'official opening of SWEC', 0, " . $now . ", " . $now . "),
  ('2014-06-18', 'Automotive Student of the Year Awards in the Automotive Centre', 0, " . $now . ", " . $now . "),
  ('2014-07-01', '14-16 Awards evening', 0, " . $now . ", " . $now . "),
  ('2014-08-14', 'A Level results day', 0, " . $now . ", " . $now . "),
  ('2014-08-18', 'University Open Day, University Centre from 10am - 4pm', 0, " . $now . ", " . $now . "),
  ('2014-08-21', 'GCSE results day', 0, " . $now . ", " . $now . "),
  ('2014-09-04', 'Open evening from 5-8pm', 0, " . $now . ", " . $now . "),
  ('2014-09-12', 'Graduation Day 2014 at the Spanish Barn at Torre Abbey', 0, " . $now . ", " . $now . "),
  ('2014-10-16', 'Apprenticeship Awards evening at The Imperial, Torquay', 0, " . $now . ", " . $now . "),
  ('2014-11-14', 'Day of Celebration at the RICC', 0, " . $now . ", " . $now . "),
  ('2014-11-19', 'Open Evening from 5-8pm', 0, " . $now . ", " . $now . "),
  ('2014-07-21', 'EBS Downtime due to upgrade', 0, " . $now . ", " . $now . "),
  ('2014-09-16', 'Fresher''s Week', 0, " . $now . ", " . $now . "),
  ('2014-12-03', 'Christmas Market', 0, " . $now . ", " . $now . "),
  ('2014-12-04', 'Christmas Market', 0, " . $now . ", " . $now . "),
  ('2014-10-27', 'Torbay Sports Awards', 1, " . $now . ", " . $now . "),
  ('2014-11-27', 'Torbay Sports Awards ', 0, " . $now . ", " . $now . "),
  ('2014-11-17', 'South Devon Enterprise Week ', 0, " . $now . ", " . $now . "),
  ('2014-11-28', 'ACL ''Christmas Flowers'' workshop at the Adult Training and Develoment Centre, Torquay.', 0, " . $now . ", " . $now . "),
  ('2014-11-11', 'Remembrance Day Service on The Street', 0, " . $now . ", " . $now . "),
  ('2014-11-26', 'Newton Abbot Campus Open Event 4pm - 6pm', 0, " . $now . ", " . $now . "),
  ('2014-12-04', 'Careers in Construction', 0, " . $now . ", " . $now . "),
  ('2014-12-01', 'Mince pies, hot drinks, turkey & brie on offer @ College Way!', 0, " . $now . ", " . $now . "),
  ('2014-12-10', 'Giant Yule Log made by SDC Catering students, sold by the slice', 0, " . $now . ", " . $now . "),
  ('2014-12-12', 'Festive Christmas Jumper Friday - Free festive drink and prize for best jumper of the day', 0, " . $now . ", " . $now . "),
  ('2014-12-17', 'Seasonal Christmas Dinners - Mulled wine, three courses & coffee', 0, " . $now . ", " . $now . "),
  ('2014-12-24', 'Christmas Gingerbread House! Made by SDC Catering students', 0, " . $now . ", " . $now . "),
  ('2015-01-09', 'SWEC - Go Green Week', 0, " . $now . ", " . $now . "),
  ('2015-02-10', 'SWEC - Go Green Week', 0, " . $now . ", " . $now . "),
  ('2015-02-11', 'SWEC - Go Green Week', 0, " . $now . ", " . $now . "),
  ('2015-02-12', 'SWEC - Go Green Week', 0, " . $now . ", " . $now . "),
  ('2015-02-13', 'SWEC - Go Green Week', 0, " . $now . ", " . $now . "),
  ('2015-02-25', 'Open Event', 0, " . $now . ", " . $now . "),
  ('2014-12-19', 'Staff CPD Day', 0, " . $now . ", " . $now . "),
  ('2015-01-29', 'Mitch Tonks Night in Horizons', 0, " . $now . ", " . $now . "),
  ('2015-04-16', 'Mitch Tonks Night in Horizons', 0, " . $now . ", " . $now . "),
  ('2015-06-11', 'Mitch Tonks Night in Horizons', 0, " . $now . ", " . $now . "),
  ('2015-02-11', 'Valentine''s Theme Night in Horizons', 0, " . $now . ", " . $now . "),
  ('2015-03-25', 'Best of British Theme Night in Horizons', 0, " . $now . ", " . $now . "),
  ('2015-04-23', 'Around the World in 80 Days Theme Night in Horizons', 0, " . $now . ", " . $now . "),
  ('2015-05-16', 'South Devon 10k', 0, " . $now . ", " . $now . "),
  ('2015-02-18', 'South West Motor Show', 0, " . $now . ", " . $now . "),
  ('2015-03-21', 'Fitness Festival', 0, " . $now . ", " . $now . "),
  ('2015-06-11', 'Mitch Tonks Gala Dinner', 0, " . $now . ", " . $now . "),
  ('2015-02-26', 'Half term!', 0, " . $now . ", " . $now . "),
  ('2015-03-09', 'The Hair Show', 0, " . $now . ", " . $now . "),
  ('2015-03-09', 'Apprenticeships Week', 0, " . $now . ", " . $now . "),
  ('2015-03-10', 'Apprenticeships Week', 0, " . $now . ", " . $now . "),
  ('2015-03-11', 'Apprenticeships Week', 0, " . $now . ", " . $now . "),
  ('2015-03-12', 'Apprenticeships Week', 0, " . $now . ", " . $now . "),
  ('2015-03-13', 'Apprenticeships Week', 0, " . $now . ", " . $now . "),
  ('2015-03-14', 'SDC Community Day', 0, " . $now . ", " . $now . "),
  ('2015-03-19', 'Open Event', 0, " . $now . ", " . $now . "),
  ('2015-03-20', 'Solar Eclipse!', 0, " . $now . ", " . $now . "),
  ('2014-04-15', 'CITB SkillBuild', 0, " . $now . ", " . $now . "),
  ('2015-04-14', 'CITB SkillBuild', 0, " . $now . ", " . $now . "),
  ('2015-04-23', 'World Book Night book giveaway', 0, " . $now . ", " . $now . "),
  ('2015-04-25', 'Noss Activity Day', 0, " . $now . ", " . $now . "),
  ('2015-05-05', 'Research Showcase', 0, " . $now . ", " . $now . "),
  ('2015-05-06', 'Talent Show & Award Ceremony', 0, " . $now . ", " . $now . "),
  ('2015-05-18', 'Adult Learners Week', 0, " . $now . ", " . $now . "),
  ('2015-05-19', 'Open Evening', 0, " . $now . ", " . $now . "),
  ('2015-05-23', 'BMF Try A Boat Day', 0, " . $now . ", " . $now . "),
  ('2015-06-03', 'Newton Abbot Open Evening', 0, " . $now . ", " . $now . "),
  ('2015-06-03', 'Fashion Show', 0, " . $now . ", " . $now . "),
  ('2015-06-11', 'End of Term Gala Dinner', 0, " . $now . ", " . $now . "),
  ('2015-06-11', 'End of Term Gala Dinner', 0, " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['sdc']['events'] as $query ) {
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
