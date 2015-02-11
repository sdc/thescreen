<?php

if(isset($_GET['page']) && !empty ($_GET['page']) ) {

    require_once('functions.php');

    if(set_config('page', $_GET['page'])) {
        header('location: manage.php?msg=page_edit_success');
    } else {
        header('location: manage.php?msg=page_edit_fail');
    }
} else {
    header('location: manage.php?msg=page_edit_fail');
}

?>
