<?php

if(isset($_GET['eid']) && !empty ($_GET['eid'])) {
    
    require_once('functions.php');
    
    if(del_event($_GET['eid'])) {
        header('location: manage.php?msg=event_del_success');
    } else {
        header('location: manage.php?msg=event_del_fail');
    }
} else {
    header('location: manage.php?msg=event_del_fail');
}

?>
