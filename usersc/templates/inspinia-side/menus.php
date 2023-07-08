<ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="#"> First level 2 </a></li>
          <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">  First level 3  </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"> Second level 1 </a></li>
                <li>
                  <a class="dropdown-item" href="#"> Second level 2 &raquo </a>
                  <ul class="submenu dropdown-menu">
                    <li><a class="dropdown-item" href=""> Third level 1</a></li>
                    <li><a class="dropdown-item" href=""> Third level 2</a></li>
                    <li><a class="dropdown-item" href=""> Third level 3 &raquo </a>
                      <ul class="submenu dropdown-menu">
                        <li><a class="dropdown-item" href=""> Fourth level 1</a></li>
                        <li><a class="dropdown-item" href=""> Fourth level 2</a></li>
                      </ul>
                    </li>
                    <li><a class="dropdown-item" href=""> Second level  4</a></li>
                    <li><a class="dropdown-item" href=""> Second level  5</a></li>
                  </ul>
                </li>
                <li><a class="dropdown-item" href="#"> Dropdown item 3 </a></li>
              </ul>
            </li>
        </ul>
<?php
$qs_menu = "
    SELECT
        id, 
        parent,
        dropdown,
        logged_in,
        display_order,
        label,
        link
    FROM menus 
    WHERE menu_title = 'main'
    ORDER BY parent ASC
";
$items  = $db->query($qs_menu)->results();
// print("<pre>".print_r($result_menu,true)."</pre>");

function generate_menu($items) {
    $menu = "<ul class='navbar-nav'>";
    foreach($items as $item) {
        // Check if the current item has a parent
        if($item->parent == -1) {
            if (has_children($item->id, $items)) {
                $menu .= "<li class='nav-item dropdown'><a class='nav-link dropdown-toggle' href='{$item->link}' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$item->label}</a>";
            } else {
                $menu .= "<li class='nav-item'><a class='nav-link' href='{$item->link}'>{$item->label}</a>";
            }
            // Check if the current item has any children
            if(has_children($item->id, $items)) {
                $menu .= generate_submenu($item->id, $items, true);
            }
            $menu .= "</li>";
        }
    }
    $menu .= "</ul>";
    return $menu;
}

function generate_submenu($id, $items, $has_children) {
    $submenu_class = $has_children ? 'submenu dropdown-menu' : 'dropdown-menu';
    $submenu = "<ul class='$submenu_class' aria-labelledby='navbarDropdown'>";
    foreach($items as $item) {
        // Check if the current item is a child of the parent item
        if($item->parent == $id) {
            if (has_children($item->id, $items)) {
                $submenu .= "<li class='dropdown-submenu'><a class='dropdown-item dropdown-toggle' href='{$item->link}'>{$item->label}</a>";
            } else {
                $submenu .= "<li><a class='dropdown-item' href='{$item->link}'>{$item->label}</a>";
            }
            // Check if the current item has any children
            if(has_children($item->id, $items)) {
                $submenu .= generate_submenu($item->id, $items, true);
            }
            $submenu .= "</li>";
        }
    }
    $submenu .= "</ul>";
    return $submenu;
}

function has_children($id, $items) {
    foreach($items as $item) {
        if($item->parent == $id) {
            return true;
        }
    }
    return false;
}


$menu_html = generate_menu($items);
echo $menu_html;

?>
