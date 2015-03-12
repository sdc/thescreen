<?php
// x and y height of the resulting image. max = 545
$size = 250;
// array of websites to generate
$site['name'][] = 'MotD';
$site['url'][] = rand(0,9);
$site['name'][] = 'Wireless Network';
$site['url'][] = 'WIFI:T:WPA;S:wirelessguest;P:;;';
$site['name'][] = 'Moodle';
$site['url'][] = 'http://moodle.southdevon.ac.uk/';
$site['name'][] = 'ILP';
$site['url'][] = 'http://ilp.southdevon.ac.uk/';
$site['name'][] = 'Public Website';
$site['url'][] = 'http://www.southdevon.ac.uk/';
$site['name'][] = 'Learning Zone Bookings';
$site['url'][] = 'http://lzbookings.southdevon.ac.uk/';
$site['name'][] = 'Xerte';
$site['url'][] = 'http://xerte.southdevon.ac.uk/';
$site['name'][] = 'Staff';
$site['url'][] = 'http://staff.southdevon.ac.uk/';
$site['name'][] = 'News';
$site['url'][] = 'http://news.southdevon.ac.uk/';
// number of columns
$wrap = 4;
// quality/error correction: l / m / q / h
$quality = 'm';
// border
$border = 1;
// base url for the rest of the crap
$base = 'http://chart.apis.google.com/chart?cht=qr&chld='.$quality.'|'.$border.'&chs=';

?>
<html>
<head>
    <title>SDC-relevant QR Codes</title>
    <style>
    * {
        font-family: "Droid Sans", "Trebuchet MS", Tahoma, Verdana, Helvetica, Arial, sans-serif;
        /*font-family: "Droid Serif", Georgia, "Times New Roman", Times, serif;*/
    }
    body {
        background-color: #89b;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    td {
        text-align: center;
        border: 2px dashed #bbb;
    }
    td h1 {
        /*color: #777;*/
        font-weight: normal;
        margin: 0;
        padding: 0;
    }
    </style>
</head>
<body>
    <table>
<?php
$cols = 0;
for ($j=0; $j<count($site['name']); $j++) {
    $cols++;

    if ($cols == 1) {
        echo '        <tr>'."\n";
    }

    echo '            <td>'."\n";
    echo '                <h1>'.$site['name'][$j].'</h1>'."\n";
    echo '                <img src="'.$base.$size.'x'.$size.'&chl='.$site['url'][$j].'" />'."\n";
    echo '            </td>'."\n";

    if ($cols == $wrap) {
        echo '        </tr>'."\n";
        $cols = 0;
    }
}
?>
        </tr>
    </table>
</body>
</html>