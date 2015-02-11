<?php
define('ga_email','paulieboo@gmail.com');
define('ga_password','l1ghtsp33d');

require 'gapi.class.php';

$ga = new gapi(ga_email,ga_password);

$ga->requestAccountData();

foreach($ga->getResults() as $result)
{
  echo $result . ' (' . $result->getProfileId() . ")<br />";
}
