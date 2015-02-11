<?php

/**
 * CSID (Info Display) Web Service(s)
 * Code:    Paul Vaughan
 * Feb 2013
 */

require_once('functions.php');

adminlog('webservice_request');

// check for some kind of token, or something?

// query the database for the status
$res = mysql_query("SELECT * FROM config;");
if (mysql_num_rows($res) == 0) {
  return false;
} else {
  while ($row = mysql_fetch_assoc($res)) {
    //echo $row['item'].' - '.$row['value']."<br>\n";
    if (strtolower($row['item']) == 'page') {
      $page = strtolower($row['value']);
    }
    if (strtolower($row['item']) == 'status') {
      $status = strtolower($row['value']);
    }
    if (strtolower($row['item']) == 'showstopper') {
      $showstopper = $row['value'];
    }
  }
}

// output as xml
header('Content-Type: application/xml');
echo '<?xml version="1.0" ?>
<response>
  <page>'.$page.'</page> 
  <status>'.$status.'</status> 
  <showstopper>'.$showstopper.'</showstopper> 
</response>';