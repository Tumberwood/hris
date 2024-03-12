<?php
//Whatever happens in this script happens just before the user is officially redirected to login.php because they weren't logged in. You can either choose to redirect them somewhere else or perform some other logic.
//Redirect::to('https://google.com');
Redirect::to($us_url_root.'usersc/applications/views/core/login.php');
?>
