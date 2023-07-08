<?php 

/** Tutorial Generate option _blank untuk field select2
 * Tujuan:
 * - Generate Options select2 dengan paginasi, agar data tidak ter-load semua
 */

?>

<script>
    // tambahkan var id__blank_old = 0;

    // BEGIN buat from field 
    {
        label: "Field <sup class='text-danger'>*<sup>",
        name: "_blank.id__blank",
        type: "select2",
        opts: {
            placeholder : "Select",
            allowClear: true,
            multiple: false,
            ajax: {
                url: "../../models/_blank/_blank_fn_opt.php",
                dataType: 'json',
                data: function (params) {
                    var query = {
                        id__blank_old: id__blank_old,
                        search: params.term || '',
                        page: params.page || 1
                    }

                    return query;

                },
                processResults: function (data, params) {
                    return {
                        results: data.results,
                        pagination: {
                            more: true
                        }
                    };
                },
                cache: true,
                minimumInputLength: 1,
                maximum: 10,
                delay: 500,
                maximumSelectionLength: 5,
                minimumResultsForSearch: -1
            },
        }
    }
    // END buat from field  

    // isi set id__blank_old saat table on select sesuai data selected
    // isi set id__blank_old saat table on deselect = 0

</script>

<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    // BEGIN select2 pagination preparation
    $page        = $_GET['page'];
    $resultCount = 10;
    $offset      = ($page - 1) * $resultCount;
    // END select2 pagination preparation

    if($_GET['id_blank_old'] > 0){
        $id_blank_old = $_GET['id_blank_old'];
    }else{
        $id_blank_old = 0;
    }

    // BEGIN query options self.
    // Hanya dipanggil jika field ada nilai id nya
    if($id_blank_old > 0){
        $qs_blank_self = $db
            ->query('select', 'blank')
            ->get([
                'id as id',
                'kode as text'
            ])
            ->where('id', $id_blank_old )
            ->exec();
        $rs_blank_self = $qs_blank_self->fetchAll();
    }else{
        $rs_blank_self = [];
    }
    // END query options self

    // BEGIN query options all except self
    $qs_blank_all = $db
        ->query('select', 'blank')
        ->get([
            'id as id',
            'kode as text'
        ])
        ->where('is_active',1)
        ->where('id', $id_blank_old, '<>' )
        ->where( function ( $r ) {
            $q = $_GET['search'];
            $r->where('kode', '%' . $q . '%', 'LIKE' );
        } )
        ->limit($resultCount)
        ->offset($offset)
        ->exec();
    $rs_blank_all = $qs_blank_all->fetchAll();
    // END query options all except self
    
    // BEGIN menggabungkan options
    if(count($rs_blank_self) > 0){
        $rs_opt = array_merge($rs_blank_self, $rs_blank_all);
    }else{
        $rs_opt = $rs_blank_all;
    }
    $c_rs_opt = count($rs_opt);    
    // END menggabungkan options

    // BEGIN finalisasi paginasi select2
    $endCount  = $offset + $resultCount;
    $morePages = $endCount > $c_rs_opt;
    // END finalisasi paginasi select2
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>