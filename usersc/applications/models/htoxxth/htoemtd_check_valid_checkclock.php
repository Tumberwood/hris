<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $checkin = '';

    if($_POST['tanggal'] != '' && $_POST['id_hemxxmh'] > 0){
        $tanggal = new Carbon($_POST['tanggal']);
        $qs_htsxxmh = $db
            ->query('select', 'htssctd')
            ->get([
	            'date_format(DATE_SUB( concat("'. $tanggal->format('Y-m-d') . '",htsxxmh.jam_awal) ,INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE),"%Y-%m-%d %H:%i:%s") as tanggaljam_awal_t1',
                'concat("'. $tanggal->format('Y-m-d') . '",htsxxmh.jam_awal) as tanggaljam_awal',
                'date_format(DATE_ADD( concat("'. $tanggal->format('Y-m-d') . '",htsxxmh.jam_awal) ,INTERVAL 5 MINUTE),"%Y-%m-%d %H:%i") as tanggaljam_awal_toleransi',
                'concat("'. $tanggal->format('Y-m-d') . '",htsxxmh.jam_akhir) as tanggaljam_akhir'
            ])
            ->join('htsxxmh','htsxxmh.id = htssctd.id_htsxxmh','LEFT' )
            ->where('htssctd.id_hemxxmh', $_POST['id_hemxxmh'] )
            ->where('htssctd.tanggal', $tanggal->format('Y-m-d') )
            ->exec();
        $rs_htsxxmh = $qs_htsxxmh->fetch();

        if( !empty($rs_htsxxmh) ){
            $qs_hemxxmh = $db
                ->query('select', 'hemxxmh' )
                ->get([
                    'hemxxmh.kode_finger as kode_finger'
                ] )
                ->where('hemxxmh.id', $_POST['id_hemxxmh'] )
                ->exec();
            $rs_hemxxmh     = $qs_hemxxmh->fetch();
            $kode_finger    = $rs_hemxxmh['kode_finger'];

            $qs_htsprtd = $db
                ->query('select', 'htsprtd' )
                ->get([
                    'concat(htsprtd.tanggal," ",htsprtd.jam) as tanggal_jam_ci'
                ] )
                ->where('htsprtd.kode', $kode_finger )
                ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $rs_htsxxmh['tanggaljam_awal_t1'], '>=' )
                ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $rs_htsxxmh['tanggaljam_awal_toleransi'], '<=' )
                ->exec();
            $rs_htsprtd = $qs_htsprtd->fetch();

            if( empty($rs_htsxxmh) ){
                // berarti tidak ditemukan checkclock dalam range toleransi awal checkclock hingga toleransi terlambat checkclock 5 menit
                // cek apakah terlambat
                $qs_htsprtd_late = $db
                    ->query('select', 'htsprtd' )
                    ->get([
                        'concat(htsprtd.tanggal," ",htsprtd.jam) as tanggal_jam_ci'
                    ] )
                    ->where('htsprtd.kode', $kode_finger )
                    ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $rs_htsxxmh['tanggaljam_awal_toleransi'], '>=' )
                    ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $rs_htsxxmh['tanggaljam_akhir'], '<=' )
                    ->exec();
                $rs_htsprtd_late = $qs_htsprtd_late->fetch();
                if( !empty($rs_htsxxmh) ){
                    // terlambat
                    $is_valid_checkclock = 2;
                    $checkin = $rs_htsxxmh['tanggal_jam_ci'];
                }else{
                    // tidak checkclock
                    $is_valid_checkclock = 0;
                }
            }else{
                // checkclock valid
                $is_valid_checkclock = 1;
                $checkin = $rs_htsprtd['tanggal_jam_ci'];
            }

        }else{
            // jadwal tidak ditemukan
            $is_valid_checkclock = 0;
        }
        
        $data = array(
            'is_valid_checkclock' => $is_valid_checkclock,
            'checkin' => $checkin
        );
    }
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>