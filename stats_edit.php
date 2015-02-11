<?php

if(isset($_GET['key']) && !empty ($_GET['key']) && isset($_GET['value']) && !empty ($_GET['value']) && is_numeric($_GET['value']) ) {

    require_once('functions.php');

    if(edit_stat($_GET['key'], $_GET['value'])) {
        header('location: manage.php?msg=stat_edit_success');
    } else {
        header('location: manage.php?msg=stat_edit_fail');
    }
} else {
    header('location: manage.php?msg=stat_edit_fail');
}

?>
