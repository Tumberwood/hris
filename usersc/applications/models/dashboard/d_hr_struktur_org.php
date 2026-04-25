<?php 
require_once( "../../../../users/init.php" );
require_once( "../../../../usersc/lib/DataTables.php" );
require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

require_once( "../../../../usersc/vendor/autoload.php");
use Carbon\Carbon;

// init
$data      = array();
$rs_opt    = array();
$c_rs_opt  = 0;
$morePages = 0;

// ambil data
$qs = $db->raw()->exec("
    SELECT
      a.kode AS title,
      a.nama AS name,
      b.nama AS parent
    FROM hetxxmh a
    LEFT JOIN hetxxmh b ON b.id = a.id_hetxxmh_al
    WHERE 1
        AND a.urutan > 0 
        AND a.is_active = 1
    ORDER BY a.urutan
");

$rows = $qs->fetchAll(PDO::FETCH_ASSOC);

// =======================
// BUILD TREE
// =======================
$map = [];
$hasParent = [];

// build node
foreach ($rows as $r) {

    $name   = trim($r['name'] ?? '');
    $title  = trim($r['title'] ?? '') ?: $name;
    $parent = trim($r['parent'] ?? '');

    // create node
    if (!isset($map[$name])) {
        $map[$name] = [
            'name' => $name,
            'title' => $title,
            'children' => []
        ];
    } else {
        if (empty($map[$name]['title'])) {
            $map[$name]['title'] = $title;
        } else {
            $map[$name]['title'] = $map[$name]['title'];
        }
    }

    // handle parent
    if (!empty($parent)) {

        if (!isset($map[$parent])) {
            $map[$parent] = [
                'name' => $parent,
                'title' => $parent,
                'children' => []
            ];
        } else {
            $map[$parent] = $map[$parent];
        }

        // tandai punya parent
        $hasParent[$name] = true;

        // push child
        $map[$parent]['children'][] = &$map[$name];

    } else {
        // tidak punya parent
        if (!isset($hasParent[$name])) {
            $hasParent[$name] = false;
        } else {
            $hasParent[$name] = $hasParent[$name];
        }
    }
}

// =======================
// AMBIL SEMUA ROOT
// =======================
$roots = [];

foreach ($map as $name => $node) {

    if (empty($hasParent[$name])) {
        $roots[] = $node;
    } else {
        $roots = $roots;
    }
}

// =======================
// OUTPUT
// =======================
$data = [
    'struktur_org' => $roots
];

require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>