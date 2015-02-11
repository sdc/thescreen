<?php

if( isset($_GET['rssfeed']) && !empty ($_GET['rssfeed']) ) {

    require_once('functions.php');
    
    if(set_config('rssfeed', $_GET['rssfeed'])) {
        header('location: manage.php?msg=rssfeed_edit_success');
    } else {
        header('location: manage.php?msg=rssfeed_edit_fail');
    }
} else {
    header('location: manage.php?msg=rssfeed_edit_fail');
}

?>
