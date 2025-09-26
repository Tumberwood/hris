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
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec(' SELECT
                b.id,
                b.kode nrp,
                b.nama,
                
                SUM(
                    COALESCE(a.lembur15,0) +
                    COALESCE(a.lembur2,0) +
                    COALESCE(a.lembur3,0) +
                    COALESCE(a.lembur4,0)
                ) AS lembur,
                SUM(a.is_makan) makan,
                SUM(a.pot_hk) ip,
                SUM( ABS(IFNULL(a.abnormal,0)) ) abnormal,
                0 AS selisih,
                (-SUM(a.pot_hk) + SUM(ABS(IFNULL(a.abnormal, 0)))) AS total,
                ( ( IFNULL(nominal_gp,0) + + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0) )) / 173) AS pengali,
                (-SUM(a.pot_hk) + SUM(ABS(IFNULL(a.abnormal, 0)))) * ( ( IFNULL(nominal_gp,0) + + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0) )) / 173) AS terima_lain

            FROM htsprrd a
            INNER JOIN hemxxmh b ON b.id = a.id_hemxxmh
            INNER JOIN hemjbmh c on c.id_hemxxmh = b.id

            -- gaji pokok
            LEFT JOIN (
                SELECT
                    id_hemxxmh,
                    tanggal_efektif,
                    nominal AS nominal_gp
                FROM (
                    SELECT
                        id,
                        id_hemxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hemxxmh
                    WHERE
                        htpr_hemxxmh.id_hpcxxmh = 1
                        AND tanggal_efektif <= :start_date
                ) AS subquery
                WHERE row_num = 1
            ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = b.id

            -- t jabatan
            LEFT JOIN (
                SELECT
                    id_hevxxmh,
                    tanggal_efektif,
                    nominal AS nominal_t_jab
                FROM (
                    SELECT
                        a.id,
                        a.id_hevxxmh,
                        a.tanggal_efektif,
                        a.nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hevxxmh AS a
                    INNER JOIN hevxxmh AS b ON b.id = a.id_hevxxmh
                    INNER JOIN hemjbmh AS c ON c.id_hevxxmh = b.id
                    WHERE
                        a.id_hpcxxmh = 32
                        AND tanggal_efektif <= :start_date
                ) AS subquery
                WHERE row_num = 1
            ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh

            -- nominal tunjangan jabatan di menu per karyawan
            LEFT JOIN (
                SELECT
                    id_hemxxmh,
                    tanggal_efektif,
                    IFNULL(nominal, 0) AS nominal_jabatan
                FROM (
                    SELECT
                        id,
                        id_hemxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hemxxmh
                    WHERE
                        htpr_hemxxmh.id_hpcxxmh = 32
                        AND tanggal_efektif <= :start_date
                        AND is_active = 1
                ) AS subquery
                WHERE row_num = 1
            ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = b.id

            WHERE a.tanggal BETWEEN :start_date AND :end_date AND c.id_heyxxmd = 1
            GROUP BY a.id_hemxxmh
            '
            );
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $data = array(
        'result' => $rs_hemxxmh,
    );
    
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>

