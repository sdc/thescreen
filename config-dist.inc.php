<?php

/**
 * Default configuration options.
 * Add in your details and save as config.inc.php
 *
 * Note: It is not good practice to log in as 'root'! Instead, create a user with limited permissions.
 */

$CFG['db']['name']      = 'thescreen';  // e.g. 'thescreen'
$CFG['db']['host']      = 'localhost';  // e.g. 'localhost' or an IP address
$CFG['db']['user']      = '';           // e.g. 'screenuser'
$CFG['db']['pwd']       = '';           // e.g. '4v3Ry$tr0NgP@Ssw0rD'

$CFG['login']['pwd']    = 'a1b2c3...';  // 64 digit SHA-256 hash of a strong password
