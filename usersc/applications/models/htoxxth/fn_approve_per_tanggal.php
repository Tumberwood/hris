<?php
    /**
     * tes case SLO/2305/0082
     */
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

    $end_date = $_POST['end_date'];
    $status = $_POST['status'];
    $c_approve = 0;

    try{
        $db->transaction();
        // BEGIN ambil data htoemtd

        if ($status == "approve") {
            $qu_approve = $db
                ->raw()
                ->bind(':end_date', $end_date)
                ->exec('UPDATE htoxxth a
                        LEFT JOIN (
                            SELECT
                                z.id_htoxxth,
                                COUNT(z.id) c_id
                            FROM htoemtd z
                            LEFT JOIN htoxxth h ON h.id = z.id_htoxxth
                            WHERE z.is_active = 1 AND h.tanggal = :end_date
                            GROUP BY z.id_htoxxth
                        ) b ON b.id_htoxxth = a.id
                        SET a.is_approve = 1
                        WHERE a.tanggal = :end_date AND b.c_id > 0
            ');
    
            $qs_htoemtd = $db
                ->raw()
                ->bind(':end_date', $end_date)
                ->exec('INSERT INTO htoxxrd
                        (
                            id_hemxxmh, 
                            id_htoxxth, 
                            id_htoemtd, 
                            id_htotpmh, 
                            id_heyxxmh, 
                            kode, 
                            is_approve, 
                            tanggal, 
                            jam_awal, 
                            jam_akhir, 
                            durasi_lembur_jam, 
                            durasi_lembur_menit, 
                            is_istirahat
                        )
                        SELECT
                            htoemtd.id_hemxxmh AS id_hemxxmh,
                            htoxxth.id AS id_htoxxth,
                            htoemtd.id AS id_htoemtd,
                            htoemtd.id_htotpmh AS id_htotpmh,
                            htoxxth.id_heyxxmh AS id_heyxxmh,
                            htoxxth.kode AS kode,
                            1,
                            htoxxth.tanggal AS tanggal,
                            htoemtd.jam_awal AS jam_awal,
                            htoemtd.jam_akhir AS jam_akhir,
                            htoemtd.durasi_lembur_jam AS durasi_lembur_jam,
                            htoemtd.durasi_lembur_jam * 60 AS durasi_lembur_menit,
                            htoemtd.is_istirahat AS is_istirahat
                        FROM htoemtd
                        LEFT JOIN htoxxth ON htoxxth.id = htoemtd.id_htoxxth
                        LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = htoemtd.id_hemxxmh
                        WHERE htoemtd.is_active = 1
                            AND htoxxth.is_active = 1
                            AND tanggal = :end_date
                        '
            );
        } else {
            $qs_approve = $db
                ->query('select', 'htoxxth' )
                ->get(['count(id) as c_approve'] )
                ->where('is_approve', 1 )
                ->where('tanggal', $end_date )
                ->exec();
            $rs_approve = $qs_approve->fetch();
            $c_approve = $rs_approve['c_approve'];
        }

        $db->commit();
        $data = array(
            'message'=> 'SPKL Berhasil Di Insert' , 
            'type_message'=>'success',
            'c_approve'=> $c_approve
        );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'SPKL Gagal Di Insert', 
            'type_message'=>'danger' 
        );
    }

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
    
?>