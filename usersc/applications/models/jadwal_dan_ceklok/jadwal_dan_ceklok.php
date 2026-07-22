<?php 
    /**
     * Digunakan 
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once( "../../../../usersc/vendor/autoload.php");
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    $qs_hemxxmh = $db
    ->raw()
    // ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec(' SELECT
                cek.*,
                CASE
                    WHEN cek.cek_in IS NULL THEN "NO CI"
                    WHEN cek.cek_in NOT BETWEEN cek.tanggaljam_awal_t1 AND cek.tanggaljam_awal_t2 THEN "TIDAK SESUAI"
                    ELSE "OK"
                END AS status_cek_in
            FROM (
                SELECT

                    jadwal.tanggaljam_awal_t1,
                    jadwal.tanggaljam_awal_t2,
                    jadwal.tanggaljam_akhir_t1,
                    jadwal.tanggaljam_akhir_t2,
                    jadwal.tanggaljam_awal_istirahat,
                    jadwal.tanggaljam_akhir_istirahat,
                    
                    DATE_FORMAT(jadwal.tanggal, "%d %b %Y") tanggal,
                    b.kode AS nik,
                    b.nama,
                    shift.kode AS shift,
                    
                    MIN(
                        CASE
                            WHEN c.nama NOT IN ("makan","istirahat","makan manual","istirahat manual")
                            THEN c.tanggal_jam
                        END
                    ) AS cek_in,

                    SUBSTRING_INDEX(
                        MIN(
                            CASE
                                WHEN c.nama NOT IN ("makan","istirahat","makan manual","istirahat manual")
                                THEN CONCAT(c.tanggal_jam,"|",c.nama)
                            END
                        ),
                        "|",
                        -1
                    ) AS mesin_in,

                    MAX(
                        CASE
                            WHEN c.nama NOT IN ("makan","istirahat","makan manual","istirahat manual")
                            THEN c.tanggal_jam
                        END
                    ) AS cek_out,

                    MIN(
                        CASE
                            WHEN c.nama IN ("istirahat","istirahat manual")
                            THEN c.tanggal_jam
                        END
                    ) AS cek_break,

                    MIN(
                        CASE
                            WHEN c.nama IN ("makan","makan manual")
                            THEN c.tanggal_jam
                        END
                    ) AS cek_makan,
                    bag.nama bag,
                    dep.nama dep,
                    sub.nama sub,
                    lembur,
                    durasi_lembur_jam

                FROM htssctd jadwal
                JOIN hemxxmh b
                    ON b.id = jadwal.id_hemxxmh
                AND b.is_active = 1
                JOIN hemjbmh jb
                    ON jb.id_hemxxmh = b.id
                LEFT JOIN hodxxmh dep ON dep.id = jb.id_hodxxmh
                LEFT JOIN heyxxmd sub ON sub.id = jb.id_heyxxmd
                LEFT JOIN hobxxmh bag ON bag.id = jb.id_hobxxmh
                JOIN htsxxmh shift
                    ON shift.id = jadwal.id_htsxxmh

                LEFT JOIN (
                    SELECT DISTINCT
                        id,
                        nama,
                        tanggal,
                        jam,
                        kode,
                        is_active,
                        tanggal_jam
                    FROM htsprtd
                    WHERE is_active = 1
                    AND tanggal = :end_date
                ) c
                    ON c.kode = b.kode_finger
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        jam_awal,
                        jam_akhir,
                        CONCAT(jam_awal, " - ", jam_akhir) lembur,
                        l.is_istirahat,
                        l.durasi_lembur_jam
                    FROM htoxxrd l
                    WHERE tanggal = :end_date
                    GROUP BY id_hemxxmh
                ) lembur on lembur.id_hemxxmh = jadwal.id_hemxxmh

                WHERE jadwal.tanggal = :end_date
                AND jadwal.is_active = 1
                AND (
                        (jb.tanggal_keluar IS NULL OR jb.tanggal_keluar >= :end_date)
                    AND tanggal_masuk <= :end_date
                    AND is_checkclock = 1
                )

                GROUP BY jadwal.id
            ) cek
            ORDER BY cek.nik
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

