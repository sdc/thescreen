<?php

if(isset($_GET['date']) && !empty ($_GET['date']) && isset($_GET['text']) && !empty ($_GET['text'])) {
    
    require_once('functions.php');
    
    if(add_event($_GET['date'], $_GET['text'])) {
        header('location: manage.php?msg=event_add_success');
    } else {
        header('location: manage.php?msg=event_add_fail');
    }
} else {
    header('location: manage.php?msg=event_add_fail');
}

?>
