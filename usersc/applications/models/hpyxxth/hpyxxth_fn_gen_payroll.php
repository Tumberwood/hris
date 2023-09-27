<?php 
    /**
     * Digunakan untuk melakukan perhitungan payroll karyawan
     * 
     */
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    require_once('../../../../usersc/vendor/autoload.php');
	use Carbon\Carbon;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $awal = new Carbon();

    /**
     * gaji_pokok (OK)
     * tunj_jabat (OK)
     * terima_lain //tdk dulu
     * var_cost //tdk dulu
     * fix_cost //tdk dulu
     * premiabs -- ini premi absen (OK)
     * trm_jkkjkm
     * 
     * lembur15 (OK)
     * rp_lembur15 -- 1.5 dikali berapa (OK)
     * lembur2 (OK)
     * rp_lembur2 (OK)
     * lembur3 (OK)
     * rp_lembur3 (OK)
     * jam_lembur = lembur15 + lembur2 + lmbur 3  (OK)
     * lemburbersih -- Rp lembur 15 + sum (OK)
     * 
     * pot_makan (OK)
     * 
     * pot_pph21
     * pph21back
     * 
     * pot_jkkjkm
     * pot_jht
     * pot_lain2
     * pot_spsi
     * pot_upah
     * pot_bpjs
     * pot_psiun
     * 
     * gaji_bersih
     * bulat
     * gaji_terima
     */

    /**
     * +---------------------------------------------------------------------+
     * | #id    | KOMPONEN                             | sumber table        |
     * +---------------------------------------------------------------------+
     * | 1      | Gaji Pokok                        OK | htpr_hemxxmh        |
     * | 31     | Tunjangan Masa Kerja                 | htpr_hevgrmh_mk     |
     * | 32     | Tunjangan Jabatan (Level)         OK | htpr_hevxxmh        |
     * | 33     | Premi Absen (id_hpcxxmh)             | htpr_hevxxmh        |
     * | 34     | Potongan Uang Makan                  | htpr_hesxxmh        |
     * | 35     | Potongan Absen           (KBM)       |                     |
     * | 37     | Upah Lembur                       OK |                     |
     * | 101    | Var Cost                             |                     |
     * +---------------------------------------------------------------------+
     */
    /* tidak dipakai
    $qs_hpcxxmh = $db
        ->query('select', 'hpcxxmh' )
        ->get([
            'hpcxxmh.kode as kode',
            'hpcxxmh.nama as nama',
            'hpcxxmh.jenis as jenis'
        ] )
        ->where('hpcxxmh.is_active', 1 )
        ->exec();
    $rs_hpcxxmh = $qs_hpcxxmh->fetchAll();
    */

    //DEKLARASI VARIABLE PAYROLL
    $tanggal_awal_select = new Carbon($_POST['tanggal_awal']); //gunakan carbon untuk ambil data tanggal
    $tanggal_awal = $tanggal_awal_select->format('Y-m-d'); //format jadi 2023-09-12

    $tanggal_akhir_select = new Carbon($_POST['tanggal_akhir']); //gunakan carbon untuk ambil data tanggal
    $tanggal_akhir = $tanggal_akhir_select->format('Y-m-d'); //format jadi 2023-09-12

    $id_heyxxmh     = $_POST['id_heyxxmh'];
    $id_hpyxxth     = $_POST['id_hpyxxth'];

    // print_r($tanggal_awal);
    // print_r($tanggal_akhir);

    try{
        $db->transaction();

        // $qs_presensi = $db
		// 	->raw()
		// 	->bind(':tanggal_awal', $tanggal_awal)
		// 	->bind(':tanggal_akhir', $tanggal_akhir)
		// 	->bind(':id_heyxxmh', $id_heyxxmh)
		// 	->exec(' SELECT 
        //                 DISTINCT a.id_hemxxmh
        //             FROM htsprrd AS a
        //             LEFT JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
        //             LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
        //             WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir AND c.id_heyxxmh = :id_heyxxmh;
        
		// 			'
		// 			);
		// $rs_presensi = $qs_presensi->fetchAll();


        // BEGIN GAJI POKOK
        $qd_gp = $db
            ->query('delete', 'hpyemtd')
            ->where('id_hpyxxth',$id_hpyxxth)
            ->where('id_heyxxmh',$id_heyxxmh)
            ->exec();

        // foreach ($rs_presensi as $key => $peg) {
        //     $id_hemxxmh = $peg['id_hemxxmh'];
        //     $gp = 0;
        //     $t_jab = 0;

        //     $qs_gp = $db
        //         ->raw()
        //         ->bind(':id_hemxxmh', $id_hemxxmh)
        //         ->bind(':tanggal_awal', $tanggal_awal)
        //         ->exec('SELECT 
        //                     id_hemxxmh, 
        //                     tanggal_efektif, 
        //                     nominal as gp
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hemxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hemxxmh
        //                     WHERE 
        //                         htpr_hemxxmh.id_hpcxxmh = 1 AND
                                
        //                         tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE id_hemxxmh = :id_hemxxmh AND row_num = 1
        //     ');
        //     $rs_gp = $qs_gp->fetch();
        //     if (!empty($rs_gp)) {
        //         $gp = $rs_gp['gp'];
        //     }

        
        $qr_gp = $db
            ->raw()
            ->bind(':id_heyxxmh', $id_heyxxmh)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd (
                id_hpyxxth, 
                id_heyxxmh, 
                id_hemxxmh, 
                gp,
                t_jab,
                premi_abs,
                lembur15,
                lembur2,
                lembur3,
                jam_lembur,
                rp_lembur15,
                rp_lembur2,
                rp_lembur3,
                lemburbersih,
                pot_makan
            )
            SELECT DISTINCT
                ' . $id_hpyxxth . ',
                ' . $id_heyxxmh . ',
                a.id_hemxxmh,
                gp,
                t_jab,
                IFNULL(premi_abs.nominal, 0) AS premi_abs,
                lembur15,
                lembur2,
                lembur3,
                (lembur15 + lembur2 + lembur3) AS jam_lembur,
                -- kondisi jika id hes == 3 atau pelatihan maka ambil pot_uang_lembur di htpr_hesxxmh jika tidak maka pakai rumus == if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)))
                if(lembur15 > 0, if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)) * 1.5, 0) AS rp_lembur15,
                if(lembur2 > 0, if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)) * 2, 0) AS rp_lembur2,
                if(lembur3 > 0, if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)) * 3, 0) AS rp_lembur3,
                -- total rp lembur
                if(lembur15 > 0, if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)) * 1.5, 0) + 
                if(lembur2 > 0, if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)) * 2, 0) + 
                if(lembur3 > 0, if(c.id_hesxxmh = 3, pot_uang_lembur, ROUND((gp + t_jab) / 173, 2)) * 3, 0) AS lemburbersih,
                pot_makan * pot_uang_makan AS pot_makan
                
            FROM htsprrd AS a

            -- hemjbmh
            LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh

            -- gaji pokok
            LEFT JOIN (
                SELECT
                    id_hemxxmh,
                    tanggal_efektif,
                    IFNULL(nominal, 0) AS gp
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
                        AND tanggal_efektif < :tanggal_awal
                ) AS subquery
                WHERE row_num = 1
            ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh

            -- potongan makan htpr_hesxxmh
            LEFT JOIN (
                SELECT
                    id_hesxxmh,
                    tanggal_efektif,
                    IFNULL(nominal, 0) AS pot_uang_makan
                FROM (
                    SELECT
                        id,
                        id_hesxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hesxxmh
                    WHERE
                        htpr_hesxxmh.id_hpcxxmh = 34
                        AND tanggal_efektif < :tanggal_awal
                ) AS subquery
                WHERE row_num = 1
            ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh

            -- potongan lembur htpr_hesxxmh untuk pelatihan
            LEFT JOIN (
                SELECT
                    id_hesxxmh,
                    tanggal_efektif,
                    IFNULL(nominal, 0) AS pot_uang_lembur
                FROM (
                    SELECT
                        id,
                        id_hesxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hesxxmh
                    WHERE
                        htpr_hesxxmh.id_hpcxxmh = 36
                        AND tanggal_efektif < :tanggal_awal
                ) AS subquery
                WHERE row_num = 1
            ) pot_uang_lembur ON pot_uang_lembur.id_hesxxmh = c.id_hesxxmh

            -- t jabatan
            LEFT JOIN (
                SELECT
                    id_hevxxmh,
                    tanggal_efektif,
                    IFNULL(nominal, 0) AS t_jab
                FROM (
                    SELECT
                        id,
                        id_hevxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hevxxmh
                    WHERE
                        htpr_hevxxmh.id_hpcxxmh = 32
                        AND tanggal_efektif < :tanggal_awal
                ) AS subquery
                WHERE row_num = 1
            ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh

            -- premi absen
            LEFT JOIN (
                SELECT
                    id_hevxxmh,
                    tanggal_efektif,
                    nominal
                FROM (
                    SELECT
                        id,
                        id_hevxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hevxxmh
                    WHERE
                        htpr_hevxxmh.id_hpcxxmh = 33
                        AND tanggal_efektif < :tanggal_awal
                ) AS subquery
                WHERE row_num = 1
            ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh

            -- sum durasi lembur dan makan
            LEFT JOIN (
                SELECT
                    id_hemxxmh,
                    SUM(durasi_lembur_final) AS lembur_sum,
                    SUM(is_makan) AS pot_makan
                FROM htsprrd
                WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                GROUP BY id_hemxxmh
            ) lembur_sum_table ON lembur_sum_table.id_hemxxmh = a.id_hemxxmh

            -- cari lembur 1.5, lembur 2, lembur 3
            LEFT JOIN (
                SELECT
                    id_hemxxmh,
                    lembur15,
                    lembur2,
                    lembur3
                FROM (
                    SELECT
                        id_hemxxmh,
                        durasi_lembur_final AS lembur_sum,
                        SUM((CASE
                            WHEN IFNULL(durasi_lembur_final, 0) > 0 -- dicari apakah ada lembur
                            -- jika ada lembur maka diisikan, namun ada kondisi jika lebih dari 1 maka akan di return 1, else, nilai lembur sebenarnya
                            THEN if(durasi_lembur_final > 1, 1, durasi_lembur_final) * 1.5
                            ELSE 0 -- kalau tidak ada lembur maka return 0
                        END)) AS lembur15,
                        SUM((CASE
                                -- cari lembur diatas 1 jam dan kurang atau sama dengan 8 jam
                            WHEN IFNULL(durasi_lembur_final, 0) > 1 AND IFNULL(durasi_lembur_final, 0) <= 8
                            THEN (durasi_lembur_final - 1) * 2 -- ini diminus 1 karena sebelumnya sudah dihitung di lembur 1,5
                            ELSE 0 -- jika tidak ada maka return 0 untuk lembur 2 nya
                        END)) AS lembur2,
                        SUM((CASE
                            WHEN IFNULL(durasi_lembur_final, 0) > 8 -- cari jam lembur diatas 8
                            THEN (durasi_lembur_final - 8) * 3 -- jika ada maka diminus 8, karena yang 1-8 sudah dihitung di lembur 1,5 dan 2
                            ELSE 0 -- jika tidak ada maka return 0 di lembur 3 nya
                        END)) AS lembur3
                    FROM htsprrd
                    WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                    GROUP BY id_hemxxmh
                ) lembur_sum_table
            ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh
            WHERE
                a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                AND c.id_heyxxmh = :id_heyxxmh;
        ');
        // END GAJI POKOK

        // $qr_gp = $db
        //     ->raw()
        //     ->bind(':id_heyxxmh', $id_heyxxmh)
        //     ->bind(':tanggal_awal', $tanggal_awal)
        //     ->bind(':tanggal_akhir', $tanggal_akhir)
        //     ->exec('
        //     INSERT INTO hpyemtd (
        //         id_hpyxxth, 
        //         id_hemxxmh, 
        //         gp,
        //         t_jab,
        //         premi_abs
        //     )
        //     SELECT DISTINCT
        //         ' . $id_hpyxxth . ',
        //         a.id_hemxxmh,
        //         IFNULL(tbl_htpr_hemxxmh.nominal,0) as gp,
        //         IFNULL(t_jabatan.nominal,0) as t_jab,
        //         IFNULL(premi_abs.nominal,0) as premi_abs

        //     FROM htsprrd AS a
        //     LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
        //     LEFT JOIN 
        //     (
        //         SELECT 
        //             id_hemxxmh, 
        //             tanggal_efektif, 
        //             nominal
        //         FROM (
        //             SELECT
        //                 id,
        //                 id_hemxxmh,
        //                 tanggal_efektif,
        //                 nominal,
        //                 ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //             FROM htpr_hemxxmh
        //             WHERE 
        //                 htpr_hemxxmh.id_hpcxxmh = 1 AND
        //                 tanggal_efektif < :tanggal_awal
        //         ) AS subquery
        //         WHERE row_num = 1
        //     ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
        //     LEFT JOIN 
        //     (
        //         SELECT 
        //             id_hevxxmh, 
        //             tanggal_efektif, 
        //             nominal
        //         FROM (
        //             SELECT
        //                 id,
        //                 id_hevxxmh,
        //                 tanggal_efektif,
        //                 nominal,
        //                 ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //             FROM htpr_hevxxmh
        //             WHERE 
        //                 htpr_hevxxmh.id_hpcxxmh = 32 AND
        //                 tanggal_efektif < :tanggal_awal
        //         ) AS subquery
        //         WHERE row_num = 1
        //     ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh
        //     LEFT JOIN 
        //     (
        //         SELECT 
        //             id_hevxxmh, 
        //             tanggal_efektif, 
        //             nominal
        //         FROM (
        //             SELECT
        //                 id,
        //                 id_hevxxmh,
        //                 tanggal_efektif,
        //                 nominal,
        //                 ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //             FROM htpr_hevxxmh
        //             WHERE 
        //                 htpr_hevxxmh.id_hpcxxmh = 33 AND
        //                 tanggal_efektif < :tanggal_awal
        //         ) AS subquery
        //         WHERE row_num = 1
        //     ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh
        //     WHERE 
        //         a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir 
        //         AND c.id_heyxxmh = :id_heyxxmh;

        // ');
        // END GAJI POKOK

        // $qs_hpyxxth = $db
        //     ->query('select', 'hpyxxth' )
        //     ->get([
        //         'hpyxxth.id as id_hpyxxth',
        //         'hpyxxth.id_heyxxmh as id_heyxxmh',
        //         'hpyxxth.tanggal_awal as tanggal_awal',
        //         'hpyxxth.tanggal_akhir as tanggal_akhir'
        //     ] )
        //     ->where('hpyxxth.id', $_POST['id_transaksi_h'] )
        //     ->exec();
        // $rs_hpyxxth = $qs_hpyxxth->fetch();

        // $id_hpyxxth = $rs_hpyxxth['id_hpyxxth'];

        /*
        // #1 OK
        */
        /*
        // #31 CEK
        // BEGIN Tunjangan Masa Kerja
        // ini looping per karyawan
        $qs_hemjbmh = $db
            ->query('select', 'hemjbmh' )
            ->get([
                'hemjbmh.id_hemxxmh as id_hemxxmh',
                'hevxxmh.hevgrmh as id_hevgrmh',
                'IF(
                    a.tanggal_keluar = NULL OR a.tanggal_keluar = "0000-00-00",
                    TIMESTAMPDIFF(YEAR,a.tanggal_masuk,CURDATE()),
                    TIMESTAMPDIFF(YEAR,a.tanggal_masuk,a.tanggal_keluar)
                ) AS masa_kerja_year'
            ] )
            ->join('hemxxmh','hemxxmh.id = hemjbmh.id_hemxxmh','LEFT' )
            ->join('hevxxmh','hevxxmh.id = hemjbmh.id_hevxxmh','LEFT' )
            ->where('hemjbmh.is_active', 1 )
            ->where( function ( $r ) {
                $r
                    ->where( 'hemjbmh.tanggal_keluar', NULL )
                    ->or_where( 'hemjbmh.tanggal_keluar', '0000-00-00', operator)
                    ->or_where( 'hemjbmh.tanggal_keluar', $rs_hpyxxth['tanggal_awal'], '>=')
                    ->or_where( 'hemjbmh.tanggal_keluar', $rs_hpyxxth['tanggal_akhir'], '<=');
            } )
            ->exec();
        $rs_hemjbmh = $qs_hemjbmh->fetchAll();

        foreach ($rs_hemjbmh as $row_hemjbmh) {
            $qs_htpr_hevgrmh_mk = $db
                ->query('select', 'htpr_hevgrmh_mk' )
                ->get([
                    'htpr_hevgrmh_mk.nominal as nominal'
                ] )
                ->where('htpr_hevgrmh_mk.id_hevgrmh', $row_hemjbmh['id_hevgrmh'] )
                ->where($row_hemjbmh['masa_kerja_year'] , 'htpr_hevgrmh_mk.tahun_min', '>=' )
                ->where( function ( $r ) {
                    $r
                        ->where($row_hemjbmh['masa_kerja_year'] , 'htpr_hevgrmh_mk.tahun_max', '<' )
                        ->or_where('htpr_hevgrmh_mk.tahun_min', 0 );
                } )
                ->exec();
            $rs_htpr_hevgrmh_mk = $qs_htpr_hevgrmh_mk->fetch();

            if( !$rs_htpr_hevgrmh_mk ){
                // delete data lama per karyawan jika ada
                    $qd_hpyemtd_mk = $db
                    ->query('delete', 'hpyemtd')
                    ->where('id_hpyxxth', $id_hpyxxth )
                    ->where('id_hpcxxmh', 31 )
                    ->where('id_hemxxmh', $row_hemjbmh['id_hemxxmh'] )
                    ->exec();
                
                // insert data baru per karyawan
                $qi_hpyemtd = $db
                    ->query('insert', 'hpyemtd')
                    ->set('id_hpyxxth',$id_hpyxxth)
                    ->set('id_hemxxmh',$row_hemjbmh['id_hemxxmh']) 
                    ->set('id_hpcxxmh',31) 
                    ->set('nominal', $rs_htpr_hevgrmh_mk['nominal'] ) 
                    ->exec();
            }
            
        }
        // END Tunjangan Masa Kerja
        */
        /*
        // #32 OK
        // BEGIN Tunjangan Jabatan (Level)
        $qd_hpyemtd_tjab = $db
            ->query('delete', 'hpyemtd')
            ->where('id_hpyxxth',$id_hpyxxth)
            ->where('id_hpcxxmh',32)
            ->exec();

        $qr_hpyemtd_tjab = $db
            ->raw()
            ->bind(':is_active', 1)
            ->bind(':id_heyxxmh', $rs_hpyxxth['id_heyxxmh'])
            ->bind(':tanggal_awal', $rs_hpyxxth['tanggal_awal'])
            ->bind(':tanggal_akhir', $rs_hpyxxth['tanggal_akhir'])
            ->exec('
                INSERT INTO hpyemtd (
                    id_hpyxxth, 
                    id_hemxxmh, 
                    id_hpcxxmh, 
                    nominal
                )
                SELECT 
                    ' . $id_hpyxxth . ',
                    hemxxmh.id,
                    32,
                    IFNULL(tbl_htpr_hevxxmh.nominal,0) as nominal
                FROM hemxxmh
                LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = hemxxmh.id
                LEFT JOIN 
                (
                    SELECT 
                        id_hevxxmh,
                        tanggal_efektif,
                        nominal
                    FROM (
                        SELECT
                            id,
                            id_hevxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hevxxmh
                        WHERE 
                            htpr_hevxxmh.id_hpcxxmh = 33 AND
                            tanggal_efektif < :tanggal_awal
                    ) AS subquery
                ) tbl_htpr_hevxxmh ON 
                    tbl_htpr_hevxxmh.id_hevxxmh = hemjbmh.id_hevxxmh
                WHERE 
                    hemxxmh.is_active = :is_active AND
                    (
                        hemjbmh.tanggal_keluar IS NULL OR 
                        hemjbmh.tanggal_keluar = "0000-00-00" OR
                        (hemjbmh.tanggal_keluar BETWEEN :tanggal_awal AND :tanggal_akhir )
                    ) AND
                    hemjbmh.id_heyxxmh = :id_heyxxmh
                ');
        // END Tunjangan Jabatan (Level)
        */

        // #37
        // BEGIN Upah Lembur
        // sudah dihitung di generate presensi, tinggal insert saja dari sana
        // $qd_hpyemtd_overtime = $db
        //     ->query('delete', 'hpyemtd')
        //     ->where('id_hpyxxth',$id_hpyxxth)
        //     ->where('id_hpcxxmh',37)
        //     ->exec();

        // $qr_hpyemtd_overtime = $db
        //     ->raw()
        //     ->bind(':is_active', 1)
        //     ->bind(':id_heyxxmh', $rs_hpyxxth['id_heyxxmh'])
        //     ->bind(':tanggal_awal', $rs_hpyxxth['tanggal_awal'])
        //     ->bind(':tanggal_akhir', $rs_hpyxxth['tanggal_akhir'])
        //     ->exec('
        //         INSERT INTO hpyemtd (
        //             id_hpyxxth, 
        //             id_hemxxmh, 
        //             id_hpcxxmh, 
        //             nominal
        //         )
        //         SELECT
        //             ' . $id_hpyxxth . ',
        //             htsprrd.id_hemxxmh,
        //             37,
        //             SUM(htsprrd.nominal_lembur_final) as nominal
        //         FROM htsprrd
        //         LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = htsprrd.id_hemxxmh
        //         WHERE
        //             htsprrd.is_active = :is_active AND
        //             htsprrd.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir AND 
        //             hemjbmh.id_heyxxmh = :id_heyxxmh
        //         GROUP BY htsprrd.id_hemxxmh
        //         HAVING nominal > 0
        //     ');
        // END Upah Lembur
        

        $db->commit();

        $akhir = new Carbon();

        $data = array(
            'message' => 'Generate Payroll Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik', 
            'type_message' => 'success',
            'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
        );  
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        
    }
    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>