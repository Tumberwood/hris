<?php
    //This is what happens after a user logs out. Where do you want to send them?  What do you want to do?
    if($_GET['x'] == 1){
        Redirect::to($us_url_root.'usersc/applications/core/noakses.php');
    }else{
        Redirect::to($us_url_root.'index.php');
    }
?>