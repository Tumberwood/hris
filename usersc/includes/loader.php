<?php
/*
This file is included at the very end of init.php
You can use it to include other files or run functions even
if you are not using the UserSpice template system

************************************************
VERY IMPORTANT
************************************************
Because it is used in parser files and api calls,
DO NOT EVER use it to do anything that will echo text to the screen
or it will break important functionality.
*/

$custom_titleQ = $db->query('SELECT pages.id as id, pages.private as is_private, pages_extend.info as info FROM pages LEFT JOIN pages_extend ON pages_extend.id = pages.id WHERE page = ?', array($page));

if ($custom_titleQ->count() > 0) {
    $pageInfo = $custom_titleQ->first()->info;
    $pageId   = $custom_titleQ->first()->id;
    $is_private = $custom_titleQ->first()->is_private;
}else {
    $pageInfo = '';
    $pageId   = 0;
    $is_private = 0;
}


// check ucudasd
// hanya untuk yang tidak mempunyai hak akses administrator (2)
if(hasPerm([2])){
    $hak_c = 1;
    $hak_u = 1;
    $hak_d = 1;
    $hak_a = 1;
}else{
    if($is_private == 1){
        $sql_select_ucudasd = $db->query('SELECT id, hak_c, hak_u, hak_d, hak_a FROM ucudasd WHERE id_users = ' . $_SESSION["user"] . ' AND id_pages = ' . $pageId);
        
        // cek sudah di set atau belum, jika belum maka tidak punya hak akses crud
        if ($sql_select_ucudasd->count() > 0) {
            $hak_c = $sql_select_ucudasd->first()->hak_c;
            $hak_u = $sql_select_ucudasd->first()->hak_u;
            $hak_d = $sql_select_ucudasd->first()->hak_d;
            $hak_a = $sql_select_ucudasd->first()->hak_a;
        }else {
            $hak_c = 0;
            $hak_u = 0;
            $hak_d = 0;
            $hak_a = 0;
        }
    }else{
        $hak_c = 0;
        $hak_u = 0;
        $hak_d = 0;
        $hak_a = 0;
    }
}