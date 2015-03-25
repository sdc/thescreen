<?php

/**
 * Installer.
 * 
 * TODO: As the database evolves, ensure the SQL statements here which create the db and tables are kept up to date.
 * TODO: Log any errors and mention them at the end?
 */

$now = time();

echo "<h1>The Screen&trade; Installation</h1>";
echo "<p>This installer looks basic, but it contains all the SQL needed to create a new database on your databsae server, create the tables needed and populate them with enough data to get you started.</p>";
echo "<p>Before you do anything else, (if you haven't already), please rename the file called <code>config-dist.inc.php</code> to <code>config.inc.php</code>, edit it, and change the details within to match that of your system.<p>";
echo "<p><hr></p>";
echo "<h2>Checking...</h2>";

echo "<p>1. <code>config.inc.php</code> status: ";
if ( !file_exists( 'config.inc.php' ) ) {
  echo "doesn't seem to exist.</p>";
  exit(1);
} else {
  echo 'exists, so loading the details.</p>';
  require_once( 'config.inc.php' );
}

echo "<p>2. <code>defaults.inc.php</code> status: ";
if ( !file_exists( 'defaults.inc.php' ) ) {
  echo "doesn't seem to exist.</p>";
  exit(1);
} else {
  echo 'exists.</p>';
  require_once( 'defaults.inc.php' );
}

$error = false;

echo "<p>3. Database name is ";
if ( empty( $CFG['db']['name'] ) ) {
  echo "empty. Fix this!</p>";
  $error = true;
} else if ( $CFG['db']['name'] == 'thescreen' ) {
  echo "set to default default of '<code>thescreen</code>' (which is fine).</p>";
} else {
  echo "set to: '<code>" . $CFG['db']['name'] . "</code>'.</p>";
}

echo "<p>4. Database host (e.g. '<code>localhost</code>' or '<code>192.168.0.100</code>') is ";
if ( empty( $CFG['db']['host'] ) ) {
  echo "empty. Fix this!</p>";
  $error = true;
} else if ( $CFG['db']['host'] == 'localhost' ) {
  echo "set to default of '<code>localhost</code>' (which is fine).</p>";
} else {
  echo "set to: '<code>" . $CFG['db']['host'] . "</code>'.</p>";
}

echo "<p>5. Database user is ";
if ( empty( $CFG['db']['user'] ) ) {
  echo "empty. Fix this!</p>";
  $error = true;
} else {
  echo "set to: '<code>" . $CFG['db']['user'] . "</code>'.</p>";
}

if ( strtolower( $CFG['db']['user'] ) == 'root' || strtolower( $CFG['db']['user'] ) == 'admin' || strtolower( $CFG['db']['user'] ) == 'administrator' ) {
  echo "<p><strong>Note:</strong> We noticed that your database username is '<code>" . $CFG['db']['user'] . "</code>'. It is not a good idea to log in to your database as a user with full root privileges. Instead, create a new user with less privileges and log in as them instead.</p>";
}

echo "<p>6. Database password is ";
if ( empty( $CFG['db']['pwd'] ) ) {
  echo "empty. Fix this!</p>";
  $error = true;
} else {
  echo "set (which is enough for now).</p>";
}

if ( $error ) {
  echo "Stopping due to configuration errors. Please fix them and reload this page.";
  exit(1);
}

echo "<p>7. Attempting database connection: ";
$DB = new mysqli( $CFG['db']['host'], $CFG['db']['user'], $CFG['db']['pwd'] );
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
  echo "<p>All that is needed now is for you to click on the link below, which will start the installation. Keep an eye out for error messages, just in case.</p>";
  echo "<p><strong>This installer attempts to drop the named database and tables before creating them, so if a database already exists with this name, or tables already exist, they will be removed for good.</strong></p>";
  echo '<p><a href="install.php?confirm">I\'m positive I want to continue, I\'ve checked everything very carefully. Let\'s install this thing!</a></p>'; 
  exit(0);
}

echo "<p>8. You confirmed that you want installation to start, so let's go... ";
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
  'database'  => array(),
  'tables'    => array(),
  'contents'  => array()
);

/**
 * SQL for the database definition.
 */

echo "<h3>Creating the database</h3>";

$sql['database'][] = "DROP DATABASE IF EXISTS `" . $CFG['db']['name'] . "`;";
$sql['database'][] = "CREATE DATABASE `" . $CFG['db']['name'] . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
$sql['database'][] = "USE `" . $CFG['db']['name'] . "`;";

echo '<p><pre>';
foreach ( $sql['database'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


/**
 * SQL for table definitions.
 * TODO: I'm not sure the AUTO_INCREMENT=1 are required.
 */

echo "<h3>Creating the tables</h3>";

$sql['tables']['aprilfools'][] = "DROP TABLE IF EXISTS `aprilfools`;";
$sql['tables']['aprilfools'][] = "CREATE TABLE IF NOT EXISTS `aprilfools` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['aprilfools'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['config'][] = "DROP TABLE IF EXISTS `config`;";
$sql['tables']['config'][] = "CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `item` (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['config'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['events'][] = "DROP TABLE IF EXISTS `events`;";
$sql['tables']['events'][] = "CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start` date NOT NULL,
  `text` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['events'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['factoids'][] = "DROP TABLE IF EXISTS `factoids`;";
$sql['tables']['factoids'][] = "CREATE TABLE IF NOT EXISTS `factoids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fact` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['factoids'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['help'][] = "DROP TABLE IF EXISTS `help`;";
$sql['tables']['help'][] = "CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['help'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['log'][] = "DROP TABLE IF EXISTS `log`;";
$sql['tables']['log'][] = "CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";


echo '<p><pre>';
foreach ( $sql['tables']['log'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['pages'][] = "DROP TABLE IF EXISTS `pages`;";
$sql['tables']['pages'][] = "CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `background` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `refresh` smallint(4) unsigned NOT NULL DEFAULT '300',
  `priority` tinyint(2) unsigned NOT NULL DEFAULT '10',
  `defaultpage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `scheduled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `schedule_day` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `schedule_start` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `schedule_end` varchar(5) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['pages'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['rss'][] = "DROP TABLE IF EXISTS `rss`;";
$sql['tables']['rss'][] = "CREATE TABLE IF NOT EXISTS `rss` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `priority` tinyint(2) unsigned NOT NULL DEFAULT '5',
  `defaultpage` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['rss'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['status'][] = "DROP TABLE IF EXISTS `status`;";
$sql['tables']['status'][] = "CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'amber',
  `priority` tinyint(2) unsigned NOT NULL DEFAULT '10',
  `defaultstatus` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['status'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['tables']['status_types'][] = "DROP TABLE IF EXISTS `status_types`;";
$sql['tables']['status_types'][] = "CREATE TABLE IF NOT EXISTS `status_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL DEFAULT '0',
  `modified` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;";

echo '<p><pre>';
foreach ( $sql['tables']['status_types'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


/**
 * End of the table definitions. Now we have all the tables but none of the content.
 *
 * Start of the table contents.
 */

echo "<h3>Populating the tables</h3>";

$sql['contents']['aprilfools'][] = "INSERT INTO `aprilfools` (`fact`, `hidden`, `created`, `modified`) VALUES
('Meerkats like the colour purple more than any other colour.', 0, " . $now . ",  " . $now . "),
('Meerkats are the only quadruped mammals who make and sell their own cheese.', 0, " . $now . ",  " . $now . "),
('Meerkats have an odd number of teeth.', 0, " . $now . ",  " . $now . "),
('Meerkats have ears but choose not to wear them.', 0, " . $now . ",  " . $now . "),
('Meerkat fur is a natural source of nylon.', 0, " . $now . ",  " . $now . "),
('Meerkats produce more methane than cows.', 0, " . $now . ",  " . $now . "),
('Meerkats provide car insurance on behalf of weasels.', 0, " . $now . ",  " . $now . "),
('The ''We Buy Any Car .com'' dance routine and club smash-hit was choreographed by meerkats.', 0, " . $now . ",  " . $now . "),
('Meerkats live in all parts of the Calamari Desert in Botswana.', 0, " . $now . ",  " . $now . "),
('Meerkats hold the world record for the longest manned paper aeroplane flight.', 0, " . $now . ",  " . $now . "),
('Meerkats have individual cries for ''taxi'', ''hold the door, please'' and ''Yellow army!''.', 0, " . $now . ",  " . $now . "),
('A meerkat scored the winning goal in the 1970 World Cup.', 0, " . $now . ",  " . $now . "),
('Aleksandr Orlov from comparethemeerkat.com lives in a riverside mansion in Stoke Gabriel.', 0, " . $now . ",  " . $now . "),
('If you see a meerkat go by, and then another identical meerkat goes by, it''s a glitch in the Matrix.', 0, " . $now . ",  " . $now . "),
('Meerkats collect things.', 0, " . $now . ",  " . $now . "),
('Meerkats collect irrational numbers.', 0, " . $now . ",  " . $now . "),
('Meerkats know the locations of all the weapons of mass destruction.', 0, " . $now . ",  " . $now . "),
('In the 2014 budget, there was a 10% increase in duty on meerkats.', 0, " . $now . ",  " . $now . "),
('South Devon College owns five and a half meerkats.', 0, " . $now . ",  " . $now . "),
('Meerkats have already found the Higgs Boson in the LHC under Paignton Zoo, but are not telling.', 0, " . $now . ",  " . $now . "),
('Baby Oleg''s aspiration is to work for confused.com. Alexander is not happy about this.', 0, " . $now . ",  " . $now . "),
('Type \"do a barrel roll\" into Google.', 0, " . $now . ",  " . $now . "),
('Meerkats are bioluminescent, but only on their birthdays.', 0, " . $now . ",  " . $now . "),
('Meerkats despise poor spelling, grammar and punctuation, more than expensive car insurance.', 0, " . $now . ",  " . $now . "),
('Compare The Meerkat has more computational power than the NSA, but use it for good, not evil.', 0, " . $now . ",  " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['aprilfools'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


// defaults.inc.php loaded at the top, so loop through $CFG, adding in with $now.
echo '<p><pre>';
foreach ( $CFG['defaults'] as $key => $value ) {
  $query = "INSERT INTO `config` (`item`, `value`, `created`, `modified`) VALUES ('" . $key . "', '" . $value . "', " . $now . ", " . $now . ");";
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['events'][] = "INSERT INTO `events` (`start`, `text`, `hidden`, `created`, `modified`) VALUES
('" . date( 'Y-m-d', $now ) . "', 'Disaster recovery testing.',       0,  " . $now . ",  " . $now . "),
('" . date( 'Y-m-d', $now + ( 60*60*24 ) ) . "', 'Disaster!.',                       0,  " . $now . ",  " . $now . "),
('" . date( 'Y-m-d', $now + ( 60*60*24*2) ) . "', 'Recovery.',                        0,  " . $now . ",  " . $now . "),
('" . date( 'Y-m-d', $now + ( 60*60*24*3 ) ) . "', 'Recovered disaster celebration.',  0,  " . $now . ",  " . $now . "),
('" . date( 'Y-m-d', $now + ( 60*60*24*4 ) ) . "', 'Hangover.',                        1,  " . $now . ",  " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['events'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['factoids'][] = "INSERT INTO `factoids` (`fact`, `hidden`, `created`, `modified`) VALUES
('Default Factoid - edit or delete to say something witty or informative.',   0,  " . $now . ",  " . $now . "),
('10 PRINT ''Second Factoid'' / 20 GOTO 10 / RUN.',                           0,  " . $now . ",  " . $now . "),
('Third, hidden Factoid. Will not appear unless un-hidden.',                  1,  " . $now . ",  " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['factoids'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['help'][] = "INSERT INTO `help` (`name`, `title`, `content`, `created`, `modified`) VALUES
('pagetype', 'Page Type', 'All page types are equal, but some page types are more equal than others.', " . $now . ", " . $now . "),
('status', 'Current Status', 'If there''s a problem, change this and it will display on the main page.', " . $now . ", " . $now . "),
('events', 'Events', 'Help with events.', " . $now . ", " . $now . "),
('showstopper', 'Showstopper', 'Help with Showstopper.', " . $now . ", " . $now . "),
('rssfeed', 'RSS Feed', 'Help with RSS feed.', " . $now . ", " . $now . "),
('rssfeedpreset', 'RSS Feed Presets', 'Help with the RSS feed presets.', " . $now . ", " . $now . "),
('factoids', 'Factoids', 'Help with factoids.', " . $now . ", " . $now . "),
('person', 'Specific Person', 'Help with choosing a person.', " . $now . ", " . $now . "),
('refresh', 'Refresh', 'Help with the refresh rate setting.', " . $now . ", " . $now . "),
('stats', 'Stats', 'Help with stats (deprecated).', " . $now . ", " . $now . "),
('logs', 'Logs', 'Logs help (really?).', " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['help'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['log'][] = "INSERT INTO `log` (`date`, `data`) VALUES
('2015-03-03 16:47:53', 'site_installed');";

echo '<p><pre>';
foreach ( $sql['contents']['log'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['pages'][] = "INSERT INTO `pages` (`name`, `title`, `description`, `background`, `refresh`, `priority`, `defaultpage`, `created`, `modified`) VALUES
('standard', 'Standard', 'The standard page used 99% of the time.', 'standard.png', 0, 1, 1, " . $now . ", " . $now . "),
('meeting3pmfriday', 'Friday 3pm Meeting', 'We''re attending our regular Friday 3pm meeting and will be back about 4pm.', 'meeting_fri3pm.png', 60, 2, 0, " . $now . ", " . $now . "),
('helpdeskclosed', 'Helpdesk Closed', 'The Technician has popped out and will be back shortly.', 'helpdeskclosed.png', 60, 3, 0, " . $now . ", " . $now . "),
('showstopper', 'Showstopper!', 'For big messages!', 'showstopper.jpg', 60, 4, 0, " . $now . ", " . $now . "),
('qrcodes', 'QR Codes', 'Useful QR codes for mobile users to connect to various College systems.', '', 60, 6, 0, " . $now . ", " . $now . "),
('christmasparty', 'Christmas Party', 'We''re having fun at our Christmas lunch. Back soon.', 'xmasparty.png', 60, 5, 0, " . $now . ", " . $now . "),
('byebyejoy', 'Bye bye Joy!', 'Joy''s leaving! :(', 'byejoy.png', 60, 6, 0, " . $now . ", " . $now . "),
('meeting', 'General Meeting', 'We''re away at a meeting and will be back soon.', 'meeting_general.png', 15, 7, 0, " . $now . ", " . $now . "),
('training', 'Training', 'Generic ''training'' screen.', 'training.png', 15, 9, 0, " . $now . ", " . $now . "),
('christmas', 'Christmas', 'Default Christmas template.', 'xmas.png', 300, 10, 0, " . $now . ", " . $now . "),
('communityday2015', 'Community Day 2015', 'Community day 2015 flyer.', 'events.png', 120, 20, 0, " . $now . ", " . $now . "),
('communityday2014', 'Community Day 2014', 'Community day 2014 poster.', 'communityday.png', 120, 20, 0, " . $now . ", " . $now . ");";
$sql['contents']['pages'][] = "UPDATE `pages` SET `scheduled` =  '1', `schedule_day` =  '5', `schedule_start` =  '15:00', `schedule_end` = '16:00' WHERE `name` = 'meeting3pmfriday';";

echo '<p><pre>';
foreach ( $sql['contents']['pages'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['rss'][] = "INSERT INTO `rss` (`title`, `description`, `url`, `priority`, `defaultpage`, `created`, `modified`) VALUES
('BBC UK Technology', 'BBC UK technology news',             'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml',  1, 1, " . $now . ", " . $now . "),
('BBC UK',            'BBC UK news',                        'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/uk/rss.xml',          2, 0, " . $now . ", " . $now . "),
('BBC England',       'BBC England news',                   'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/england/rss.xml',     3, 0, " . $now . ", " . $now . "),
('BBC Sci/Env',       'BBC UK Science & Environment news',  'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/sci/tech/rss.xml',    4, 0, " . $now . ", " . $now . "),
('Slashdot',          'News for nerds, stuff that matters', 'http://rss.slashdot.org/Slashdot/slashdot',                              5, 0, " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['rss'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['status'][] = "INSERT INTO `status` (`name`, `title`, `description`, `type`, `priority`, `defaultstatus`, `created`, `modified`) VALUES
('ok',            'Okay',                 'Everything is awesome.',                                                                                     'green',      1,    1,  " . $now . ",  " . $now . "),
('minorproblem',  'Problems',             'We''re experiencing problems with the server, which we are looking in to at the moment',                     'amber',      10,   0,  " . $now . ",  " . $now . "),
('epicfail',      'Epic fail',            '<span style=\"color:#f00;font-size:200%;font-weight:bold;\">EPIC FAIL</span><br><br>Something has gone horribly, terribly wrong. It may even involve a zombie apocalypse.',                      'red',        20,   0,  " . $now . ",  " . $now . "),
('maintenance',   'Planned maintenance',  'We are performing planned maintenance on some systems.<br><br>There may be brief periods of interruption.',  'blue',       50,   0,  " . $now . ",  " . $now . "),
('xmas',          'Merry Christmas!',     'Merry Christmas and Happy New Year!<br><br>(Everything is awesome, by the way.)',                            'christmas',  99,   0,  " . $now . ",  " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['status'] as $query ) {
  run( $query );
}
echo '</pre></p>';

ob_flush();
flush();


$sql['contents']['status_types'][] = "INSERT INTO `status_types` (`name`, `title`, `created`, `modified`) VALUES
('green', 'Green (OK)', " . $now . ", " . $now . "),
('amber', 'Amber (Alert)', " . $now . ", " . $now . "),
('red', 'Red (Warning)', " . $now . ", " . $now . "),
('blue', 'Blue (Information)', " . $now . ", " . $now . "),
('christmas', 'Christmas', " . $now . ", " . $now . ");";

echo '<p><pre>';
foreach ( $sql['contents']['status_types'] as $query ) {
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

echo '<p>Installation is complete, so <a href="install-password.php">click here to set an administrative password</a>.</p>';
