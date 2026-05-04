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

// =======================
// AMBIL DATA (PAKAI ID)
// =======================
$qs = $db->raw()->exec("
    SELECT
        a.id,
        a.kode AS name,
        a.nama AS title,
        a.id_hetxxmh_al AS parent_id,
        a.urutan
    FROM hetxxmh a
    WHERE 
        a.urutan > 0 
        AND a.is_active = 1
        AND a.id_hetxxmh_al > 0
    ORDER BY a.urutan, a.id
");

$rows = $qs->fetchAll(PDO::FETCH_ASSOC);

// =======================
// BUILD NODE MAP
// =======================
$map = [];

foreach ($rows as $r) {

    $id        = (int)$r['id'];
    $name      = trim($r['name']);
    $title     = trim($r['title']);
    $parent_id = (int)$r['parent_id'];
    $urutan    = (int)$r['urutan'];

    if (!isset($map[$id])) {
        $map[$id] = [
            'id' => $id,
            'name' => $name,
            'title' => $title,
            'children' => []
        ];
    } else {
        if (empty($map[$id]['title'])) {
            $map[$id]['title'] = $title;
        } else {
            $map[$id]['title'] = $map[$id]['title'];
        }
    }

    // simpan sementara
    $map[$id]['parent_id'] = $parent_id;
    $map[$id]['urutan']    = $urutan;
}

// =======================
// BUILD TREE
// =======================
$roots = [];

foreach ($map as $id => &$node) {

    if ($node['parent_id'] === 0) {

        // cek root utama
        if ($node['urutan'] === 1) {
            $roots[] = &$node;
        } else {
            // orphan (anggap root juga biar ga hilang)
            $roots[] = &$node;
        }

    } else {

        if (isset($map[$node['parent_id']])) {
            $map[$node['parent_id']]['children'][] = &$node;
        } else {
            // parent tidak ada → jadi root fallback
            $roots[] = &$node;
        }
    }
}

// =======================
// CLEAN FIELD
// =======================
foreach ($map as &$n) {

    if (isset($n['parent_id'])) {
        unset($n['parent_id']);
    } else {
        $n = $n;
    }

    if (isset($n['urutan'])) {
        unset($n['urutan']);
    } else {
        $n = $n;
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