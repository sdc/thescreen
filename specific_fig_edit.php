<?php

if(isset($_GET['figure']) && !empty ($_GET['figure']) ) {

    require_once('functions.php');

    if(set_config('specific_fig', $_GET['figure'])) {
        header('location: manage.php?msg=figure_edit_success');
    } else {
        header('location: manage.php?msg=figure_edit_fail');
    }
} else {
    header('location: manage.php?msg=figure_edit_fail');
}

?>
