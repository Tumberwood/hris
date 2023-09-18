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
        if($state == 1){

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

                //JIKA BUKAN TI MAKA UPDATE JADWAL AWAL TI DAN AKHIR T2
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
                                            WHEN (TIME(:jam_awal) >= "23:59:59" AND TIME(:jam_awal) <= "24:00:00")
                                                OR (TIME(:jam_awal) >= "00:00:00" AND TIME(:jam_awal) <= "12:00:00" AND b.kode LIKE "malam%"
                                                OR :jam_awal > :jam_awal )
                                            THEN CONCAT(DATE_ADD(a.tanggal, INTERVAL 1 DAY), " ", TIME(:jam_awal))
                                            ELSE CONCAT(a.tanggal, " ", :jam_awal)
                                        END,
                                        "%Y-%m-%d %H:%i:%s"
                                    ) AS tanggaljam_awal,
                                    DATE_FORMAT(
                                        CASE
                                            WHEN (TIME(:jam_awal) >= "23:59:59" AND TIME(:jam_awal) <= "24:00:00")
                                                OR (TIME(:jam_awal) >= "00:00:00" AND TIME(:jam_awal) <= "12:00:00" AND b.kode LIKE "malam%"
                                                OR :jam_awal > :jam_awal )
                                            THEN DATE_ADD(CONCAT(DATE_ADD(a.tanggal, INTERVAL 1 DAY), " ", TIME(:jam_awal)), INTERVAL 1 HOUR)
                                            ELSE DATE_ADD(CONCAT(a.tanggal, " ", :jam_awal), INTERVAL 1 HOUR)
                                        END,
                                        "%Y-%m-%d %H:%i:%s"
                                    ) AS tanggaljam_awal_t2,

                                    DATE_FORMAT(
                                        CASE
                                            WHEN (TIME(:jam_akhir) >= "23:59:59" AND TIME(:jam_akhir) <= "24:00:00")
                                                OR (TIME(:jam_akhir) >= "00:00:00" AND TIME(:jam_akhir) <= "12:00:00" AND b.kode LIKE "malam%"
                                                OR :jam_awal > :jam_akhir )
                                            THEN DATE_ADD(CONCAT(DATE_ADD(a.tanggal, INTERVAL 1 DAY), " ", TIME(:jam_akhir)), INTERVAL -2 HOUR)
                                            ELSE DATE_ADD(CONCAT(a.tanggal, " ", :jam_akhir), INTERVAL -2 HOUR)
                                        END,
                                        "%Y-%m-%d %H:%i:%s"
                                    ) AS tanggaljam_akhir_t1,
                                    DATE_FORMAT(
                                        CASE
                                            WHEN (TIME(:jam_akhir) >= "23:59:59" AND TIME(:jam_akhir) <= "24:00:00")
                                                OR (TIME(:jam_akhir) >= "00:00:00" AND TIME(:jam_akhir) <= "12:00:00" AND b.kode LIKE "malam%"
                                                OR :jam_awal > :jam_akhir )
                                            THEN CONCAT(DATE_ADD(a.tanggal, INTERVAL 1 DAY), " ", TIME(:jam_akhir))
                                            ELSE CONCAT(a.tanggal, " ", :jam_akhir)
                                        END,
                                        "%Y-%m-%d %H:%i:%s"
                                    ) AS tanggaljam_akhir,
                                    DATE_FORMAT(
                                        CASE
                                            WHEN (TIME(:jam_akhir) >= "23:59:59" AND TIME(:jam_akhir) <= "24:00:00")
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

                //JIKA LEMBUR AWAL DAN ISTIRAHAT BUKAN TI MAKA UPDATE AWAL T1
                if ($row_htoemtd['id_htotpmh'] == 1) {
                    $qu_jadwal = $db
                        ->query('update', 'htssctd')
                        ->set('tanggaljam_awal_t1',$rs_hasil_awal_akhir['tanggaljam_awal_t1'])
                        ->where('id_hemxxmh',$row_htoemtd['id_hemxxmh'])
                        ->where('tanggal',$row_htoemtd['tanggal'])
                    ->exec();
                }

                //JIKA LEMBUR AKHIR DAN ISTIRAHAT BUKAN TI MAKA UPDATE AKHIR T2
                if ($row_htoemtd['id_htotpmh'] == 2) {
                    $qu_jadwal = $db
                        ->query('update', 'htssctd')
                        ->set('tanggaljam_akhir_t2',$rs_hasil_awal_akhir['tanggaljam_akhir_t2'])
                        ->where('id_hemxxmh',$row_htoemtd['id_hemxxmh'])
                        ->where('tanggal',$row_htoemtd['tanggal'])
                    ->exec();
                }

                //JIKA LEMBUR HARI LIBUR DAN ISTIRAHAT BUKAN TI MAKA UDATE KEDUANYA
                if ($row_htoemtd['id_htotpmh'] == 4) {
                    $qu_jadwal = $db
                        ->query('update', 'htssctd')
                        ->set('tanggaljam_awal_t1',$rs_hasil_awal_akhir['tanggaljam_awal_t1'])
                        ->set('tanggaljam_awal',$rs_hasil_awal_akhir['tanggaljam_awal'])
                        ->set('tanggaljam_awal_t2',$rs_hasil_awal_akhir['tanggaljam_awal_t2'])
                        ->set('tanggaljam_akhir_t1',$rs_hasil_awal_akhir['tanggaljam_akhir_t1'])
                        ->set('tanggaljam_akhir',$rs_hasil_awal_akhir['tanggaljam_akhir'])
                        ->set('tanggaljam_akhir_t2',$rs_hasil_awal_akhir['tanggaljam_akhir_t2'])
                        ->where('id_hemxxmh',$row_htoemtd['id_hemxxmh'])
                        ->where('tanggal',$row_htoemtd['tanggal'])
                    ->exec();
                }
            }
        
        }elseif($state == 2){

            $qd_htoxxrd = $db
                ->query('delete', 'htoxxrd')
                ->where('id_htoxxth', $_POST['id_transaksi_h'] )
                ->exec();

            foreach ($rs_htoemtd as $row_htoemtd) {
                $qs_htssctd = $db
                    ->query('select', 'htssctd' )
                    ->get(['id_htsxxmh'] )
                    ->where('id_hemxxmh',$row_htoemtd['id_hemxxmh'])
                    ->where('tanggal',$row_htoemtd['tanggal'])
                    ->exec();
                $rs_htssctd = $qs_htssctd->fetch();
                
                $qu_jadwal = $db
                    ->query('update', 'htssctd')
                    ->set('is_active', 0)
                    ->where('id_hemxxmh',$row_htoemtd['id_hemxxmh'])
                    ->where('tanggal',$row_htoemtd['tanggal'])
                ->exec();

                // BEGIN insert pengganti
                $qr_htssctd = $db
                ->raw()
                ->bind(':id_hemxxmh', $row_htoemtd['id_hemxxmh'])
                ->bind(':id_htsxxmh', $rs_htssctd['id_htsxxmh'])
                ->bind(':tanggal', $row_htoemtd["tanggal"])
                ->exec('
                    INSERT INTO htssctd
                    (
                        id_hemxxmh,
                        id_htsxxmh,
                        tanggal,
                        jam_awal,
                        jam_akhir,
                        jam_awal_istirahat,
                        jam_akhir_istirahat,
                        menit_toleransi_awal_in,
                        menit_toleransi_akhir_in,
                        menit_toleransi_awal_out,
                        menit_toleransi_akhir_out,

                        tanggaljam_awal_t1,
                        tanggaljam_awal,
                        tanggaljam_awal_t2,
                        tanggaljam_akhir_t1,
                        tanggaljam_akhir,
                        tanggaljam_akhir_t2,
                        tanggaljam_awal_istirahat,
                        tanggaljam_akhir_istirahat
                    )
                    SELECT
                        :id_hemxxmh,
                        :id_htsxxmh,
                        :tanggal,
                        htsxxmh.jam_awal,
                        htsxxmh.jam_akhir,
                        htsxxmh.jam_awal_istirahat,
                        htsxxmh.jam_akhir_istirahat,
                        htsxxmh.menit_toleransi_awal_in,
                        htsxxmh.menit_toleransi_akhir_in,
                        htsxxmh.menit_toleransi_awal_out,
                        htsxxmh.menit_toleransi_akhir_out,

                        CONCAT(:tanggal, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(:tanggal, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal, INTERVAL 1 DAY)
                                ELSE :tanggal
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
                        CONCAT(:tanggal, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_awal_out MINUTE))) AS tanggaljam_akhir_t1,
                        CONCAT(:tanggal, " ", htsxxmh.jam_akhir) AS tanggaljam_akhir,
                        
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal, INTERVAL 1 DAY)
                                ELSE :tanggal
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CONCAT(:tanggal, " ", htsxxmh.jam_awal_istirahat) AS tanggaljam_awal_istirahat,
                        CONCAT(:tanggal, " ", htsxxmh.jam_akhir_istirahat) AS tanggaljam_akhir_istirahat
                    FROM htsxxmh
                    WHERE 
                        id = :id_htsxxmh
                ');
            }
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