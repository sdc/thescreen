<?php

if(isset($_GET['showstopper']) && !empty ($_GET['showstopper']) ) {

    require_once('functions.php');

    if(set_config('showstopper', $_GET['showstopper'])) {
        header('location: manage.php?msg=showstopper_edit_success');
    } else {
        header('location: manage.php?msg=showstopper_edit_fail');
    }
} else {
    header('location: manage.php?msg=showstopper_edit_fail');
}

?>
