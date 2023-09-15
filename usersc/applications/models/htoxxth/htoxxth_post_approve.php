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

    $id_transaksi_h = $_POST['id_transaksi_h'];
    $state = $_POST['state'];

    try{
        $db->transaction();

        if($state == 1){
            // BEGIN ambil data htoemtd
            $qs_htoemtd = $db
                ->raw()
                ->bind(':id_transaksi_h', $id_transaksi_h)
                ->exec('SELECT
                            htoemtd.id_hemxxmh AS id_hemxxmh,
                            htoxxth.id AS id_htoxxth,
                            htoemtd.id AS id_htoemtd,
                            htoemtd.id_htotpmh AS id_htotpmh,
                            htoxxth.id_heyxxmh AS id_heyxxmh,
                            htoxxth.kode AS kode,
                            htoxxth.tanggal AS tanggal,
                            htoxxth.is_approve AS is_approve,
                            htoemtd.is_istirahat AS is_istirahat,
                            htoemtd.jam_awal AS jam_awal,
                            htoemtd.jam_akhir AS jam_akhir,
                            htoemtd.durasi_lembur_jam AS durasi_lembur_jam,
                            htoemtd.durasi_lembur_menit AS durasi_lembur_menit,
                            hemjbmh.id_hesxxmh AS id_hesxxmh
                        FROM htoemtd
                        LEFT JOIN htoxxth ON htoxxth.id = htoemtd.id_htoxxth
                        LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = htoemtd.id_hemxxmh
                        WHERE htoemtd.is_active = 1
                            AND htoxxth.is_active = 1
                            AND htoxxth.id = :id_transaksi_h;
                        '
                        );
            $rs_htoemtd = $qs_htoemtd->fetchAll();
            // END ambil data htoemtd

            foreach ($rs_htoemtd as $row_htoemtd) {
                $durasi_lembur_menit = $row_htoemtd['durasi_lembur_jam'] * 60;
                // INSERT htoxxrd
                $qi_htoxxrd = $db
                    ->query('insert', 'htoxxrd')
                    ->set( 'id_hemxxmh', $row_htoemtd['id_hemxxmh'] )
                    ->set( 'id_htoxxth', $row_htoemtd['id_htoxxth'] )
                    ->set( 'id_htoemtd', $row_htoemtd['id_htoemtd'] )
                    ->set( 'id_htotpmh', $row_htoemtd['id_htotpmh'] )
                    ->set( 'id_heyxxmh', $row_htoemtd['id_heyxxmh'] )
                    ->set( 'kode', $row_htoemtd['kode'] ) 
                    ->set( 'is_approve', $row_htoemtd['is_approve'] ) 
                    ->set( 'tanggal', $row_htoemtd['tanggal'] )
                    ->set( 'jam_awal', $row_htoemtd['jam_awal'] )
                    ->set( 'jam_akhir', $row_htoemtd['jam_akhir'] )
                    ->set( 'durasi_lembur_jam', $row_htoemtd['durasi_lembur_jam'] )
                    ->set( 'durasi_lembur_menit', $durasi_lembur_menit )
                    ->set( 'is_istirahat', $row_htoemtd['is_istirahat'] )
                    ->exec();

                    $qs_hasil_awal_akhir = $db
                        ->raw()
                        ->bind(':id_hemxxmh', $row_htoemtd['id_hemxxmh'])
                        ->bind(':tanggal', $row_htoemtd['tanggal'])
                        ->bind(':jam_awal', $row_htoemtd['jam_awal'])
                        ->bind(':jam_akhir', $row_htoemtd['jam_akhir'])
                        ->exec('SELECT
                                    DATE_FORMAT(DATE_ADD(CONCAT(a.tanggal, " ", :jam_awal), INTERVAL -1 HOUR), "%Y-%m-%d %H:%i:%s") AS tanggaljam_awal_t1,
                                    DATE_FORMAT(
                                        CASE
                                            WHEN (TIME(:jam_akhir) >= "19:00:00" AND TIME(:jam_akhir) <= "23:59:59")
                                                OR (TIME(:jam_akhir) >= "00:00:00" AND TIME(:jam_akhir) <= "12:00:00" AND b.kode LIKE "malam%"
                                                OR :jam_awal > :jam_akhir )
                                            THEN DATE_ADD(CONCAT(DATE_ADD(a.tanggal, INTERVAL 1 DAY), " ", TIME(:jam_akhir)), INTERVAL 2 HOUR)
                                            ELSE DATE_ADD(CONCAT(a.tanggal, " ", :jam_akhir), INTERVAL 2 HOUR)
                                        END,
                                        "%Y-%m-%d %H:%i:%s"
                                    ) AS tanggaljam_akhir_t2
                                FROM htssctd AS a
                                LEFT JOIN htsxxmh AS b ON b.id = a.id_htsxxmh
                                WHERE a.tanggal = :tanggal 
                                    AND a.id_hemxxmh = :id_hemxxmh
                                    AND a.is_active = 1
                                '
                                );
                    $rs_hasil_awal_akhir = $qs_hasil_awal_akhir->fetch();

                    //Update tanggaljam_awal_t1 dan tanggaljam_akhir_t2 ke Jadwal
                    $qu_jadwal = $db
                        ->query('update', 'htssctd')
                        ->set('tanggaljam_awal_t1',$rs_hasil_awal_akhir['tanggaljam_awal_t1'])
                        ->set('tanggaljam_akhir_t2',$rs_hasil_awal_akhir['tanggaljam_akhir_t2'])
                        ->where('id_hemxxmh',$row_htoemtd['id_hemxxmh'])
                        ->where('tanggal',$row_htoemtd['tanggal'])
                    ->exec();
            }
        
        }elseif($state == 2){

            $qd_htoxxrd = $db
                ->query('delete', 'htoxxrd')
                ->where('id_htoxxth', $_POST['id_transaksi_h'] )
                ->exec();

        }

        $db->commit();
        // $data = array(
        //     'message'=> 'Data Berhasil Di Insert' , 
        //     'type_message'=>'success' 
        // );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        // $data = array(
        //     'message'=>'Data Gagal Di Insert', 
        //     'type_message'=>'danger' 
        // );
    }

    // tampilkan results
    // require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
    
?>