<?php
define('ga_email','cnhyinhtuna@fbhguqriba.np.hx');
define('ga_password','53k15g53k15g');
define('ga_profile_id','2084153');

require 'gapi.class.php';

$ga = new gapi(str_rot13(ga_email),str_rot13(ga_password));

$filter = 'date == '.date('Y').date('m').(date('d')-1);
echo $filter;
$ga->requestReportData(ga_profile_id,array('day'),array('pageviews','visits'),'-visits',$filter);

echo 'Total Pageviews: '.$ga->getPageviews()."<br />";
echo 'Total Visits: '.$ga->getVisits();
?>
