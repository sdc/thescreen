<?php

require_once('functions.inc.php');

make_statoids();

header('location: manage.php?msg=statoids_make');

?>
