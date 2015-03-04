<?php

if ( !isset( $_GET['confirm'] ) ) {
  // Print a warning.
  // Link to this page with 'confirm' set.
  exit(0);
}

$now = time();

$sql = array(
  'tables'    => array(),
  'contents'  => array()
);

/**
 * SQL for table definitions.
 */

$sql['tables']['aprilfools'] = "DROP TABLE IF EXISTS `aprilfools`;
CREATE TABLE IF NOT EXISTS `aprilfools` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `fact` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['config'] = "DROP TABLE IF EXISTS `config`;
CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(1024) COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `item` (`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['events'] = "DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `start` date NOT NULL,
  `text` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['factoids'] = "DROP TABLE IF EXISTS `factoids`;
CREATE TABLE IF NOT EXISTS `factoids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `fact` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['help'] = "DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['log'] = "DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `data` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['pages'] = "DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `background` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `refresh` smallint(4) unsigned NOT NULL DEFAULT '0',
  `priority` tinyint(2) unsigned NOT NULL,
  `defaultpage` tinyint(1) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

$sql['tables']['status'] = "DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `priority` tinyint(2) unsigned NOT NULL,
  `defaultstatus` tinyint(1) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `modified` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";

/**
 * End of the table definitions.
 *
 * Start of the table contents.
 */

/*

INSERT INTO `aprilfools` (`id`, `fact`, `deleted`, `created`, `modified`) VALUES
(1, 'Meerkats like the colour purple more than any other colour.', 0, 0, 0),
(2, 'Meerkats are the only quadruped mammals who make and sell their own cheese.', 0, 0, 0),
(3, 'Meerkats have an odd number of teeth.', 0, 0, 0),
(4, 'Meerkats have ears but choose not to wear them.', 0, 0, 0),
(5, 'Meerkat fur is a natural source of nylon.', 0, 0, 0),
(6, 'Meerkats produce more methane than cows.', 0, 0, 0),
(7, 'Meerkats provide car insurance on behalf of weasels.', 0, 0, 0),
(8, 'The ''We Buy Any Car .com'' dance routine and club smash-hit was choreographed by meerkats.', 0, 0, 0),
(9, 'Meerkats live in all parts of the Calamari Desert in Botswana.', 0, 0, 0),
(10, 'Meerkats hold the world record for the longest manned paper aeroplane flight.', 0, 0, 0),
(11, 'Meerkats have individual cries for ''taxi'', ''hold the door, please'' and ''Yellow army!''.', 0, 0, 0),
(12, 'A meerkat scored the winning goal in the 1970 World Cup.', 0, 0, 0),
(13, 'Aleksandr Orlov from comparethemeerkat.com lives in a riverside mansion in Stoke Gabriel.', 0, 0, 0),
(14, 'If you see a meerkat go by, and then another identical meerkat goes by, it''s a glitch in the Matrix.', 0, 0, 0),
(15, 'Meerkats collect things.', 0, 0, 0),
(16, 'Meerkats collect irrational numbers.', 0, 0, 0),
(17, 'Meerkats know the locations of all the weapons of mass destruction.', 0, 0, 0),
(18, 'In the 2014 budget, there was a 10% increase in duty on meerkats.', 0, 0, 0),
(19, 'South Devon College owns five and a half meerkats.', 0, 0, 0),
(20, 'Meerkats have already found the Higgs Boson in the LHC under Paignton Zoo, but are not telling.', 0, 0, 0),
(21, 'Baby Oleg''s aspiration is to work for confused.com. Alexander is not happy about this.', 0, 0, 0),
(22, 'Type "do a barrel roll" into Google.', 0, 0, 0),
(23, 'Meerkats are bioluminescent, but only on their birthdays.', 0, 0, 0),
(24, 'Meerkats despise poor spelling, grammar and punctuation, more than expensive car insurance.', 0, 0, 0),
(25, 'Compare The Meerkat has more computational power than the NSA, but use it for good, not evil.', 0, 0, 0);

// TODO: As we have a list of default settings in functions.inc.php, we could use those?
INSERT INTO `config` (`item`, `value`, `created`, `modified`) VALUES
('page',          '1',                                                                      <?php echo $now; ?>,  <?php echo $now; ?>),
('status',        '2',                                                                      <?php echo $now; ?>,  <?php echo $now; ?>),
('refresh',       '300',                                                                    <?php echo $now; ?>,  <?php echo $now; ?>),
('rssfeed',       'http://newsrss.bbc.co.uk/rss/newsonline_uk_edition/technology/rss.xml',  <?php echo $now; ?>,  <?php echo $now; ?>),
('showstopper',   'error!',                                                                 <?php echo $now; ?>,  <?php echo $now; ?>),
('specific_fig',  'aaa-random.png',                                                         <?php echo $now; ?>,  <?php echo $now; ?>),
('changes',       'no',                                                                     <?php echo $now; ?>,  <?php echo $now; ?>);

INSERT INTO `events` (`start`, `text`, `hidden`, `created`, `modified`) VALUES
('2015-04-07',  'Disaster recovery testing.',       0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('2015-03-07',  'Disaster!.',                       0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('2015-04-01',  'Recovery.',                        0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('2015-03-10',  'Recovered disaster celebration.',  0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('2015-04-01',  'Hangover.',                        1,  <?php echo $now; ?>,  <?php echo $now; ?>);

INSERT INTO `factoids` (`fact`, `hidden`, `created`, `modified`) VALUES
('Default Factoid - edit or delete to say something witty or informative.',   0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('10 PRINT ''Second Factoid'' / 20 GOTO 10 / RUN.',                           0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('Third, hidden Factoid. Will not appear unless un-hidden.',                  1,  <?php echo $now; ?>,  <?php echo $now; ?>);

INSERT INTO `help` (`name`, `title`, `content`, `created`, `modified`) VALUES
('pagetype', 'Page Types', 'All page types are equal, but some page types are more equal than others.', <?php echo $now; ?>, <?php echo $now; ?>);

INSERT INTO `log` (`date`, `data`) VALUES
('2015-03-03 16:47:53', 'site_installed');

INSERT INTO `pages` (`name`, `title`, `description`, `background`, `refresh`, `priority`, `defaultpage`, `created`, `modified`) VALUES
('standard', 'Standard', 'The standard page used 99% of the time.', 'standard.png', 0, 1, 1, 1424322434, 1424322434),
('meeting3pmfriday', 'Friday 3pm Meeting', 'We''re attending our regular Friday 3pm meeting and will be back about 4pm.', 'meeting_fri3pm.png', 60, 2, 0, 1424322434, 1424322434),
('helpdeskclosed', 'Helpdesk Closed', 'The Technician has popped out and will be back shortly.', 'helpdeskclosed.png', 60, 3, 0, 1424322434, 1424322434),
('showstopper', 'Showstopper!', 'For big messages!', 'showstopper.jpg', 60, 4, 0, 1424322434, 1424322434),
('christmasparty', 'Christmas Party', 'We''re having fun at our Christmas lunch. Back soon.', 'xmasparty.png', 60, 5, 0, 1424322434, 1424322434),
('byebyejoy', 'Bye bye Joy!', 'Joy''s leaving! :(', 'byejoy.png', 60, 6, 0, 1424322434, 1424322434),
('meeting', 'General Meeting', 'We''re away at a meeting and will be back soon.', 'meeting_general.png', 15, 7, 0, 1424322434, 1424322434),
('training', 'Training', 'Generic ''training'' screen.', 'training.png', 15, 9, 0, 1424322434, 1424322434),
('christmas', 'Christmas', 'Default Christmas template.', 'xmas.png', 300, 10, 0, 1424322434, 1424322434),
('communityday2015', 'Community Day 2015', 'Community day 2015 flyer.', 'events.png', 120, 20, 0, 1424322434, 1424322434),
('communityday2014', 'Community Day 2014', 'Community day 2014 poster.', 'communityday.png', 120, 20, 0, 1424322434, 1424322434);

INSERT INTO `status` (`name`, `title`, `description`, `image`, `priority`, `defaultstatus`, `created`, `modified`) VALUES
('ok',            'Okay',                 'Everything is awesome.',                                                                                     'green',      1,    1,  <?php echo $now; ?>,  <?php echo $now; ?>),
('minorproblem',  'Problems',             'We''re experiencing problems with the server, which we are looking in to at the moment',                     'amber',      10,   0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('epicfail',      'Epic fail',            'Something has gone horribly, terribly wrong. It may even involve a zombie apocalypse.',                      'red',        20,   0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('maintenance',   'Planned maintenance',  'We are performing planned maintenance on some systems.<br><br>There may be brief periods of interruption.',  'blue',       50,   0,  <?php echo $now; ?>,  <?php echo $now; ?>),
('xmas',          'Merry Christmas!',     'Merry Christmas and Happy New Year!<br><br>(Everything is awesome, by the way.)',                            'christmas',  99,   0,  <?php echo $now; ?>,  <?php echo $now; ?>);

*/
