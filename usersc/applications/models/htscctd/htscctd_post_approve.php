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

    $state          = $_POST['state'];
    $id_transaksi_h = $_POST['id_transaksi_h'];

    try{
        $db->transaction();
        
        //add by ferry, tambahkan state, karena sebelum ditambahkan if state == 1 meskipun cancel approve tapi tetap menginsert ke database
        if($state == 1) {
            $qs_htscctd = $db
                ->query('select', 'htscctd' )
                ->get([
                    'htscctd.id_hemxxmh_pengaju as id_hemxxmh_pengaju',
                    'htscctd.id_hemxxmh_pengganti as id_hemxxmh_pengganti',
                    'htscctd.id_htsxxmh_pengaju as id_htsxxmh_pengaju',
                    'htscctd.id_htsxxmh_pengganti as id_htsxxmh_pengganti',
                    'htscctd.kode as kode',
                    'htscctd.tanggal as tanggal',
                    'pengaju.id_heyxxmd as id_heyxxmd_pengaju',
                    'pengganti.id_heyxxmd as id_heyxxmd_pengganti',
                    'htscctd.keterangan as keterangan'
                ] )
                ->join('hemjbmh as pengaju','pengaju.id = htscctd.id_hemxxmh_pengaju','LEFT' )
                ->join('hemjbmh as pengganti','pengganti.id = htscctd.id_hemxxmh_pengganti','LEFT' )
                ->where('htscctd.id', $id_transaksi_h )
                ->exec();

            $rs_htscctd = $qs_htscctd->fetch();
            
            $keterangan = $rs_htscctd['kode'] ." - ". $rs_htscctd['keterangan'];
            if ($rs_htscctd['id_heyxxmd_pengaju'] != 4 && $rs_htscctd['id_htsxxmh_pengganti'] != 4) {
               // BEGIN non aktif
                    // harusnya bisa pakai where or, tapi belum berhasil
                    $qu_htssctd_pengaju = $db
                    ->query('update', 'htssctd')
                    ->set('is_active',0)
                    ->set('keterangan', $keterangan)
                    ->where('tanggal', $rs_htscctd['tanggal'])
                    ->where('id_hemxxmh', $rs_htscctd['id_hemxxmh_pengaju'])
                    ->exec();

                    $qu_htssctd_pengganti = $db
                        ->query('update', 'htssctd')
                        ->set('is_active',0)
                        ->set('keterangan', $keterangan)
                        ->where('tanggal', $rs_htscctd['tanggal'])
                        ->where('id_hemxxmh', $rs_htscctd['id_hemxxmh_pengganti'])
                    ->exec();
                // END non aktif

                // Begin insert pengaju
                $qr_htssctd_pengaju = $db
                    ->raw()
                    ->bind(':id_htsxxmh_pengganti', $rs_htscctd['id_htsxxmh_pengganti'])
                    ->bind(':tanggal_pengganti', $rs_htscctd["tanggal"])
                    ->exec('
                        INSERT INTO htssctd
                        (
                            id_hemxxmh,
                            id_htsxxmh,
                            keterangan,
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
                            '.$rs_htscctd["id_hemxxmh_pengaju"].',
                            '.$rs_htscctd["id_htsxxmh_pengganti"].',
                            "'.$keterangan.'",
                            "'.$rs_htscctd["tanggal"].'",
                            htsxxmh.jam_awal,
                            htsxxmh.jam_akhir,
                            htsxxmh.jam_awal_istirahat,
                            htsxxmh.jam_akhir_istirahat,
                            htsxxmh.menit_toleransi_awal_in,
                            htsxxmh.menit_toleransi_akhir_in,
                            htsxxmh.menit_toleransi_awal_out,
                            htsxxmh.menit_toleransi_akhir_out,
                            CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                            CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                    ELSE :tanggal_pengganti
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

                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                                ELSE CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                            END AS tanggaljam_akhir_t1,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
                                ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir)
                            END AS tanggaljam_akhir,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                        OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                    ELSE :tanggal_pengganti
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
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
                                ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal_istirahat)
                            END AS tanggaljam_awal_istirahat,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
                                ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir_istirahat)
                            END AS tanggaljam_akhir_istirahat
                        FROM htsxxmh
                        WHERE 
                            id = :id_htsxxmh_pengganti
                    ');
                // END insert pengaju

                // BEGIN insert pengganti
                $qr_htssctd_pengganti = $db
                    ->raw()
                    ->bind(':id_htsxxmh_pengaju', $rs_htscctd['id_htsxxmh_pengaju'])
                    ->bind(':tanggal_pengaju', $rs_htscctd["tanggal"])
                    ->exec('
                        INSERT INTO htssctd
                        (
                            id_hemxxmh,
                            id_htsxxmh,
                            keterangan,
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
                            '.$rs_htscctd["id_hemxxmh_pengganti"].',
                            '.$rs_htscctd["id_htsxxmh_pengaju"].',
                            "'.$keterangan.'",
                            "'.$rs_htscctd["tanggal"].'",
                            htsxxmh.jam_awal,
                            htsxxmh.jam_akhir,
                            htsxxmh.jam_awal_istirahat,
                            htsxxmh.jam_akhir_istirahat,
                            htsxxmh.menit_toleransi_awal_in,
                            htsxxmh.menit_toleransi_akhir_in,
                            htsxxmh.menit_toleransi_awal_out,
                            htsxxmh.menit_toleransi_akhir_out,
                            CONCAT(:tanggal_pengaju, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                            CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY)
                                    ELSE :tanggal_pengaju
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

                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                                ELSE CONCAT(:tanggal_pengaju, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                            END AS tanggaljam_akhir_t1,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
                                ELSE CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_akhir)
                            END AS tanggaljam_akhir,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                        OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY)
                                    ELSE :tanggal_pengaju
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
                            CASE
								WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "12:00:00"
								THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
								ELSE CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_awal_istirahat)
							END AS tanggaljam_awal_istirahat,
							CASE
								WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "12:00:00"
								THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
								ELSE CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_akhir_istirahat)
							END AS tanggaljam_akhir_istirahat
                        FROM htsxxmh
                        WHERE 
                            id = :id_htsxxmh_pengaju
                    ');

            
                // END insert pengganti
            }
            
        } else if($state == 2) {
            $qs_htscctd = $db
                ->query('select', 'htscctd' )
                ->get([
                    'htscctd.id_hemxxmh_pengaju as id_hemxxmh_pengaju',
                    'htscctd.id_hemxxmh_pengganti as id_hemxxmh_pengganti',
                    'htscctd.id_htsxxmh_pengaju as id_htsxxmh_pengaju',
                    'htscctd.id_htsxxmh_pengganti as id_htsxxmh_pengganti',
                    'htscctd.kode as kode',
                    'htscctd.tanggal as tanggal',
                    'htscctd.keterangan as keterangan'
                ] )
                ->where('htscctd.id', $id_transaksi_h )
                ->exec();

            $rs_htscctd = $qs_htscctd->fetch();
            $keterangan = $rs_htscctd['kode'] ." - ". $rs_htscctd['keterangan'];
            // BEGIN non aktif
            // harusnya bisa pakai where or, tapi belum berhasil
            $qu_htssctd_pengaju = $db
                ->query('update', 'htssctd')
                ->set('is_active',0)
                ->set('keterangan', $keterangan)
                ->where('tanggal', $rs_htscctd['tanggal'])
                ->where('id_hemxxmh', $rs_htscctd['id_hemxxmh_pengaju'])
                ->exec();

            $qu_htssctd_pengganti = $db
                ->query('update', 'htssctd')
                ->set('is_active',0)
                ->set('keterangan', $keterangan)
                ->where('tanggal', $rs_htscctd['tanggal'])
                ->where('id_hemxxmh', $rs_htscctd['id_hemxxmh_pengganti'])
                ->exec();
            // END non aktif
                
            // Begin insert pengaju
            $qr_htssctd_pengaju = $db
                ->raw()
                ->bind(':id_htsxxmh_pengaju', $rs_htscctd['id_htsxxmh_pengaju'])
                ->bind(':tanggal_pengaju', $rs_htscctd["tanggal"])
                ->exec('
                    INSERT INTO htssctd
                    (
                        id_hemxxmh,
                        id_htsxxmh,
                        keterangan,
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
                        '.$rs_htscctd["id_hemxxmh_pengaju"].',
                        '.$rs_htscctd["id_htsxxmh_pengaju"].',
                        "'.$keterangan.'",
                        "'.$rs_htscctd["tanggal"].'",
                        htsxxmh.jam_awal,
                        htsxxmh.jam_akhir,
                        htsxxmh.jam_awal_istirahat,
                        htsxxmh.jam_akhir_istirahat,
                        htsxxmh.menit_toleransi_awal_in,
                        htsxxmh.menit_toleransi_akhir_in,
                        htsxxmh.menit_toleransi_awal_out,
                        htsxxmh.menit_toleransi_akhir_out,
                        CONCAT(:tanggal_pengaju, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY)
                                ELSE :tanggal_pengaju
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

                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(:tanggal_pengaju, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
                            ELSE CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY)
                                ELSE :tanggal_pengaju
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
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
                            ELSE CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_awal_istirahat)
                        END AS tanggaljam_awal_istirahat,
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengaju, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
                            ELSE CONCAT(:tanggal_pengaju, " ", htsxxmh.jam_akhir_istirahat)
                        END AS tanggaljam_akhir_istirahat
                    FROM htsxxmh
                    WHERE 
                        id = :id_htsxxmh_pengaju
                ');
            // END insert pengaju

            // BEGIN insert pengganti
            $qr_htssctd_pengganti = $db
                ->raw()
                ->bind(':id_htsxxmh_pengganti', $rs_htscctd['id_htsxxmh_pengganti'])
                ->bind(':tanggal_pengganti', $rs_htscctd["tanggal"])
                ->exec('
                    INSERT INTO htssctd
                    (
                        id_hemxxmh,
                        id_htsxxmh,
                        keterangan,
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
                        '.$rs_htscctd["id_hemxxmh_pengganti"].',
                        '.$rs_htscctd["id_htsxxmh_pengganti"].',
                        "'.$keterangan.'",
                        :tanggal_pengganti,
                        htsxxmh.jam_awal,
                        htsxxmh.jam_akhir,
                        htsxxmh.jam_awal_istirahat,
                        htsxxmh.jam_akhir_istirahat,
                        htsxxmh.menit_toleransi_awal_in,
                        htsxxmh.menit_toleransi_akhir_in,
                        htsxxmh.menit_toleransi_awal_out,
                        htsxxmh.menit_toleransi_akhir_out,

                        CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                ELSE :tanggal_pengganti
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
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
                            ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir)
                        END AS tanggaljam_akhir,
                        
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                ELSE :tanggal_pengganti
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
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
                            ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal_istirahat)
                        END AS tanggaljam_awal_istirahat,
                        CASE
                            WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
                            ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir_istirahat)
                        END AS tanggaljam_akhir_istirahat
                    FROM htsxxmh
                    WHERE 
                        id = :id_htsxxmh_pengganti
                ');

        
            // END insert pengganti
        }
        
    
        $db->commit();
        $data = array(
            'message'=> 'Perubahan Jadwal Berhasil Dibuat' , 
            'type_message'=>'success' 
            // 'tanggaljam_awal_t2'=> $rs_testig['tanggaljam_awal_t2'],
            // 'tanggaljam_akhir_t2'=> $rs_testig['tanggaljam_akhir_t2']
        );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'Perubahan Jadwal Gagal Dibuat!', 
            'qr_htssctd_pengaju'=>'danger' 
        );
    }    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>