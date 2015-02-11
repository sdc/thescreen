<?php

if(isset($_GET['status']) && !empty ($_GET['status']) ) {

    require_once('functions.php');

    if(set_config('status', $_GET['status'])) {
        header('location: manage.php?msg=status_edit_success');
    } else {
        header('location: manage.php?msg=status_edit_fail');
    }
} else {
    header('location: manage.php?msg=status_edit_fail');
}

?>
