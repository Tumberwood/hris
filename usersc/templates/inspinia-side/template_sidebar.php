<?php
    function get_menu_tree($parent_id){
        $db = DB::getInstance();

        $qs_ggsxxsh = "SELECT genpath FROM ggsxxsh WHERE is_active=1";
        $query_ggsxxsh = $db->query($qs_ggsxxsh);
        $result_ggsxxsh = $query_ggsxxsh->results();
        $genpath = $result_ggsxxsh[0]->genpath;
        
        // ambil main menu di side
        $main_nav_all = $db->query("SELECT * FROM menus WHERE menu_title='side' AND parent = ". $parent_id .  " ORDER BY display_order");
        $main_nav = $main_nav_all->results(true);
        $menu = '';
    
        foreach ($main_nav as $value){
        
            $authorizedGroups = array();
            foreach (fetchGroupsByMenu($value['id']) as $g) {
                $authorizedGroups[] = $g->group_id;
            }
        
            if((hasPerm($authorizedGroups,$_SESSION['user']) || in_array(0,$authorizedGroups)) && $value['logged_in']==1) {
                $dropdownclass = 'class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ';
                $hasicon = '<span class='. $value['icon_class'] . '</span>';
                $hascaret = '<span class="caret"></span>';
                
                $menu .= '<li><a href="/'.$genpath. $value['link'] . '"><i class="' . $value['icon_class'] . '"></i><span class="nav-label">' . $value['label'];

                if($value['dropdown'] == 1){
                    $menu .= '</span><span class="fa arrow"></span></a>';
                    $menu .= '<ul class="nav nav-second-level collapse">' . get_menu_tree($value['id']) . '</ul>'; //call  recursively
                }
                $menu .= '</li>';
            }
        }
    
        return $menu;
    }
?>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse" id="main_nav">
        <a class="close-canvas-menu"><i class="fa fa-times"></i></a>
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" width="48" height="48" class="rounded-circle" src="../../../files/uploads/def_male.png"/>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold"><?=echouser($user->data()->id)?></span>
                        <span class="text-muted text-xs block">Title <b class="caret"></b></span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <!-- <li><a class="dropdown-item" href="profile.html">Dashboard</a></li> -->
                        <li><a class="dropdown-item" href="../../../applications/views/core/account.php">Account</a></li>
                        <li class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../../../../users/logout.php">Logout</a></li>
                    </ul>
                </div>
            </li>

            <?php echo get_menu_tree(-1); ?>
            
            <?php
            /**
             * bagian bawah ini seharusnya tidak ada
             * tetapi jika dihilangkankan, toogle side bar nya jadi ke tengah
             */
            ?>
            <li>
                <a href="#"><span class="nav-label"></span>Version 2.4.0</a>
            </li>
        </ul>

    </div>
</nav>