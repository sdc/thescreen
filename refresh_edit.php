<?php

if( isset($_GET['seconds']) && !empty ($_GET['seconds']) && is_numeric($_GET['seconds']) ) {

    require_once('functions.php');
    
    if(set_config('refresh', $_GET['seconds'])) {
        header('location: manage.php?msg=refresh_edit_success');
    } else {
        header('location: manage.php?msg=refresh_edit_fail');
    }
} else {
    header('location: manage.php?msg=refresh_edit_fail');
}

?>
