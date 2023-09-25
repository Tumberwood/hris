<?php

    function prepareDropdownStringTop($menuItem,$user_id){
        $itemString='';
        $itemString.='<li class="dropdown">';
        $itemString.='<a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="'.$menuItem['icon_class'].'"></span> '.$menuItem['label'].' <span class="caret"></span></a>';
        $itemString.='<ul class="dropdown-menu">';
        
        foreach ($menuItem['children'] as $childItem){
            $authorizedGroups = array();
            foreach (fetchGroupsByMenu($childItem['id']) as $g) {
                $authorizedGroups[] = $g->group_id;
            }
            if($childItem['logged_in']==0 || (hasPerm($authorizedGroups,$user_id) || in_array(0,$authorizedGroups))) {
                $itemString.=prepareItemString($childItem,$user_id); }
            }
            $itemString.='</ul></li>';
        return $itemString;
    }

    // message
    // if(($settings->messaging == 1) && ($user->isLoggedIn())){
    //     $msgQ = $db->query("SELECT id FROM messages WHERE msg_to = ? AND msg_read = 0 AND deleted = 0",array($user->data()->id));
    //     $msgC = $msgQ->count();
    //     if($msgC == 1){
    //         $grammar = 'Message';
    //     }else{
    //         $grammar = 'Messages';
    //     }
    // }

    // $settingsQ = $db->query("SELECT * FROM settings");
    // $settings = $settingsQ->first();
    // // end of message

    // // Set up notifications button/modal
    // if ($user->isLoggedIn()) {
    //     if ($dayLimitQ = $db->query('SELECT notif_daylimit FROM settings', array())) $dayLimit = $dayLimitQ->results()[0]->notif_daylimit;
    //     else $dayLimit = 7;

    //     // 2nd parameter- true/false for all notifications or only current
    //     $notifications = new Notification($user->data()->id, false, $dayLimit);
    // }
/*
Load main navigation menus
*/
    $main_nav_all = $db->query("SELECT * FROM menus WHERE menu_title='main' ORDER BY display_order");
/*
Set "results" to true to return associative array instead of object...part of db class
*/
    $main_nav=$main_nav_all->results(true);

/*
Make menu tree
*/
    $prep=prepareMenuTree($main_nav);

?>

<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary open_sidebar" href="#"><i class="fa fa-bars open_sidebar"></i> </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">Welcome <b><?=echouser($user->data()->id)?></b></span>
            </li>

            <?php
            /**
             * userspice menu top
             */
    foreach ($prep as $key => $value) {
        $authorizedGroups = array();
        foreach (fetchGroupsByMenu($value['id']) as $g) {
            $authorizedGroups[] = $g->group_id;
        }
        
        // Check if there are children of the current nav item...if no children, display single menu item, if children display dropdown menu
        
        // Jika Single Menu tanpa Dropdown
        
        if (sizeof($value['children'])==0) {
            if ($user->isLoggedIn()) {
                
                if((hasPerm($authorizedGroups,$user->data()->id) || in_array(0,$authorizedGroups)) && $value['logged_in']==1) {
                //if (checkMenu($value['id'],$user->data()->id) && $value['logged_in']==1) {
                if($value['label']=='{{notifications}}') {
                    $itemString='';
                    if($settings->notifications==1) {
                        $itemString='<li><a href="#" onclick="displayNotifications(';
                        $itemString.="'new')";
                        $itemString.='"';
                        $itemString.='id="notificationsTrigger" data-toggle="modal" data-target="#notificationsModal"  ><i class="glyphicon glyphicon-bell"></i> <span id="notifCount" class="badge badge-danger" style="margin-top: -5px">';
                        $itemString.=(($notifications->getUnreadCount() > 0) ? $notifications->getUnreadCount() : '');
                        $itemString.='</span></a></li>';
                    }
                }elseif($value['label']=='{{messages}}') {
                    $itemString='';
                    if($settings->messaging==1) {
                        $itemString='<li><a href="'.$us_url_root.'users/messages.php"><i class="glyphicon glyphicon-envelope"></i> <span id="msgCount" class="badge badge-danger" style="margin-top: -5px">';
                        if($msgC > 0) $itemString.= $msgC;
                            $itemString.='</span></a></li>'; 
                    }
                } else {
                    $itemString = prepareItemString($value,$user->data()->id);
                    $itemString = str_replace('{{username}}',$user->data()->username,$itemString);
                    $itemString = str_replace('{{fname}}',$user->data()->fname,$itemString);
                    $itemString = str_replace('{{lname}}',$user->data()->lname,$itemString);
                    require_once $abs_us_root.$us_url_root.'usersc/includes/database_navigation_hooks.php';
                }
                    echo $itemString;
                }
            } else {
                if ($value['logged_in']==0) {
                    echo prepareItemString($value,0);
                }
            }
        } else { // Jika ber-Dropdown
            
            if ($user->isLoggedIn()) {
                if((hasPerm($authorizedGroups,$user->data()->id) || in_array(0,$authorizedGroups)) && $value['logged_in']==1) {
                    $dropdownString=prepareDropdownStringTop($value,$user->data()->id);
                    $dropdownString=str_replace('{{username}}',$user->data()->username,$dropdownString);
                    echo $dropdownString;
                }
            } else {
                if ($value['logged_in']==0) {
                    $dropdownString=prepareDropdownStringTop($value,0);
                    
                    #$dropdownString=str_replace('{{username}}',$user->data()->username,$dropdownString); # There *is* no $user->...->username because we're not logged in
                    
                    echo $dropdownString;
                }
            }
            
        }
        
    }
    
?>
        
        </ul>
    </nav>
</div>
<div class="row wrapper white-bg page-heading" style="padding:4px;">
    <div class="col-lg-10">
        <h4 style="display: inline-block;"><?= (($pageTitle != '') ? $pageTitle : ''); ?></h4>&nbsp<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="<?= (($pageInfo != '') ? $pageInfo : ''); ?>" ></i>
    </div>
</div>