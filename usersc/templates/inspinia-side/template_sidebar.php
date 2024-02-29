<?php
    if (!isset($_SESSION['user'])) {
        header("Location: ".$us_url_root."usersc/applications/views/core/login.php");
        exit();
    }
    
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
        
        // foreach ($main_nav as $value){
        //     $authorizedGroups = array();
        //     foreach (fetchGroupsByMenu($value['id']) as $g) {
        //         $authorizedGroups[] = $g->group_id;
        //     }
        
        //     if ((hasPerm($authorizedGroups, $_SESSION['user']) || in_array(0, $authorizedGroups)) && $value['logged_in'] == 1) {
        //         echo '<a class="menu-item" href="/' . $genpath . $value['link'] . '">' . $value['label'] . '</a>';
        
        //         if ($value['dropdown'] == 1) {
        //             echo '<ul class="nav nav-second-level collapse">' . get_menu_tree($value['id']) . '</ul>';
        //         }
        //     }
        // }
        foreach ($main_nav as $value){
        
            $authorizedGroups = array();
            foreach (fetchGroupsByMenu($value['id']) as $g) {
                $authorizedGroups[] = $g->group_id;
            }
        
            if((hasPerm($authorizedGroups,$_SESSION['user']) || in_array(0,$authorizedGroups)) && $value['logged_in']==1) {
                $dropdownclass = 'class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" ';
                $hasicon = '<span class='. $value['icon_class'] . '</span>';
                $hascaret = '<span class="caret"></span>';
                
                $menu .= '<li class= "non-search"><a href="/'.$genpath. $value['link'] . '"><i class="' . $value['icon_class'] . '"></i><span class="nav-label">' . $value['label'];

                if($value['dropdown'] == 1){
                    $menu .= '</span><span class="fa arrow"></span></a>';
                    $menu .= '<ul class="nav nav-second-level collapse">' . get_menu_tree($value['id']) . '</ul>'; //call  recursively
                }
                $menu .= '</li>';
            }
        }
    
        return $menu;
    }

    function menus_search() {
        $db = DB::getInstance();
    
        $qs_ggsxxsh = "SELECT genpath FROM ggsxxsh WHERE is_active=1";
        $query_ggsxxsh = $db->query($qs_ggsxxsh);
        $result_ggsxxsh = $query_ggsxxsh->results();
        $genpath = $result_ggsxxsh[0]->genpath;
    
        $main_nav_all = $db->query("SELECT * FROM menus WHERE menu_title='side' AND link != '#' ORDER BY display_order");
        $main_nav = $main_nav_all->results(true);
    
        $menu_data = array();
    
        foreach ($main_nav as $value) {
    
            $authorizedGroups = array();
            foreach (fetchGroupsByMenu($value['id']) as $g) {
                $authorizedGroups[] = $g->group_id;
            }
    
            if ((hasPerm($authorizedGroups, $_SESSION['user']) || in_array(0, $authorizedGroups)) && $value['logged_in'] == 1) {
                
                $value['link'] = '/' . $genpath . $value['link'];
    
                
                $menu_data[] = $value;
            }
        }
    
        return $menu_data;
    }
    $menu_data = menus_search();
    
?>
<style>
    .menu-item {
        display: block;
        padding: 10px 15px;
        padding-right: 200px;
        margin-left: 16px;
        text-decoration: none;
        color: rgb(167, 177, 194);
        background-color: rgb(47, 64, 80);
        margin-bottom: 5px;
        margin-top: 4px;
        border-radius: 4px;
        white-space: nowrap; 
        font-size: 13px;
        font-weight: 600;
        transition: background-color 0.3s, color 0.3s;
    }

    .menu-item:hover {
        color: white; 
        background-color: rgb(41, 56, 70); 
    }

    #searchInput {
        border-radius: 5px; 
        width: 200px; 
        margin-left: 7px;
    }

</style>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse" id="main_nav">
        <a class="close-canvas-menu" id="close_sidebar"><i class="fa fa-times"></i></a>
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
            <li class="nav-item">
                <input class="form-control mr-sm-2" type="search" id="searchInput" placeholder="Search">
            </li>
            <ul id="results" class="list-group">
                <!-- Search results disini -->
            </ul>

            <?php echo get_menu_tree(-1); ?>
           
            <li>
                <a href="#"><span class="nav-label"></span>Version 2.4.0</a>
            </li>
        </ul>

    </div>
</nav>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function () {
        var menuData = <?php echo json_encode($menu_data); ?>;

        function updateSearchResults(query) {
            if (query.trim() === "") {
                $(".non-search").show();
            } else {
                $(".non-search").hide();
            }

            $("#results").empty();

            var filteredData = menuData.filter(function (item) {
                return (
                    query.trim() !== "" &&
                    item.label.toLowerCase().includes(query.toLowerCase())
                );
            });

            filteredData.forEach(function (item) {
                var $listItem = $("<a class='menu-item' href='" + item.link + "'>" + item.label + "</a>");
                $("#results").append($listItem);
            });
        }

        $("#searchInput").on("input", function () {
            var query = $(this).val();
            updateSearchResults(query);
        });

        //Side Bar Auto close
        var sidebar = $("#main_nav"); //ini area sidebar
        var timesIcon = $("#close_sidebar"); //icon x di sidebar
        var btn_bar = $(".open_sidebar"); //ini icon bar tiga
        var is_sidebar_open = 0;

        //jika btn bar di click maka ada flag sidebar telah dibuka
        btn_bar.on("click", function () {
            is_sidebar_open = 1; //sidebar open
        });

        timesIcon.on("click", function () {
            is_sidebar_open = 0; //sidebar close
        });

        $(document).on("click", function (e) {
            //Jika sidebar open && target bulan btn_bar && tidak dalam sidebar && tidak diarea sidebar maka trigger tutup
            if (is_sidebar_open == 1 && !btn_bar.is(e.target) && !sidebar.is(e.target) && sidebar.has(e.target).length === 0) {
                timesIcon.click(); // Trigger click tombol x di sidebar
                is_sidebar_open = 0;
            } 
        });
    });
</script>
