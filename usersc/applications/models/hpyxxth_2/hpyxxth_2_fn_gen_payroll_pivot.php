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
     * var_cost (OK)
     * fix_cost (OK)
     * premiabs (OK)
     * trm_jkkjkm (OK)
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
     * pot_jkkjkm (OK)
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

    // $id_heyxxmh     = $_POST['id_heyxxmh'];
    $id_hpyxxth_2     = $_POST['id_hpyxxth_2'];

    $timestamp     = $_POST['timestamp']; //tambah timestamp untuk view

    try{
        $db->transaction();
        // BEGIN GAJI POKOK
        $qd_gp = $db
            ->query('delete', 'hpyemtd_2')
            ->where('id_hpyxxth_2',$id_hpyxxth_2)
            // ->where('id_heyxxmh',$id_heyxxmh)
        ->exec();

        // GP
        $qr_gp = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- gaji pokok
                    IFNULL( 
                        if( c.tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), 0,
                            if(is_perubahan_hk > 0, 
                                ((hk_report / if(grup_hk_lama = 1, 21, 25)) * if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp))
                                +
                                ((hk_jadwal / if(grup_hk_baru = 1, 21, 25)) * if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp)),
                                if(c.tanggal_masuk BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir), 
                                    hari_kerja / if(c.grup_hk = 1, 21, 25) * if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp) ,
                                    if(c.tanggal_keluar BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir), 
                                        keluar_report / if(c.grup_hk = 1, 21, 25) * if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp) ,
                                    if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp) )
                                )
                            )
	                     )
                    ,0) AS gp
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                    -- gaji pokok
                    LEFT JOIN (
                        SELECT
                            id_hemxxmh,
                            tanggal_efektif,
                            IFNULL(nominal, 0) AS nominal_gp
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
                                AND is_active = 1
                        ) AS subquery
                        WHERE row_num = 1
                    ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                    -- hari kerja karyawan baru
                    LEFT JOIN (
                        SELECT
                            (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
                            report.id_hemxxmh
                        FROM (
                            SELECT 
                                COUNT(a.id) AS hk_report,
                                a.id_hemxxmh
                            FROM htsprrd AS a
                            LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                            WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND :tanggal_akhir
                                AND a.st_clock_in <> "OFF" -- sebelumnya st-clock in
                            GROUP BY a.id_hemxxmh
                        ) AS report
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                COUNT(id) AS jadwal
                            FROM htssctd
                            WHERE tanggal BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                                AND id_htsxxmh <> 1
                            GROUP BY id_hemxxmh
                        ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                    ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                    
                    -- tanggal keluar
                    LEFT JOIN (
                        SELECT
                            keluar_report,
                            report.id_hemxxmh
                        FROM (
                            SELECT 
                                COUNT(a.id) AS keluar_report,
                                a.id_hemxxmh
                            FROM htsprrd AS a
                            LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                            WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY)
                                AND a.st_clock_in <> "OFF"
                            GROUP BY a.id_hemxxmh
                        ) AS report
                    ) AS keluar ON keluar.id_hemxxmh = a.id_hemxxmh

                    -- gaji pokok pelatihan
                    LEFT JOIN (
                        SELECT
                            id_hesxxmh,
                            tanggal_efektif,
                            IFNULL(nominal, 0) AS pot_gp_pelatihan
                        FROM (
                            SELECT
                                id,
                                id_hesxxmh,
                                tanggal_efektif,
                                nominal,
                                ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                            FROM htpr_hesxxmh
                            WHERE
                                htpr_hesxxmh.id_hpcxxmh = 1
                                AND tanggal_efektif < :tanggal_awal
                                AND is_active = 1
                        ) AS subquery
                        WHERE row_num = 1
                    ) pot_gp_pelatihan ON pot_gp_pelatihan.id_hesxxmh = c.id_hesxxmh

                    -- case HK 5 HK 6
                    LEFT JOIN (
                        SELECT
                            grup_hk_lama,
                            grup_hk_baru,
                            hk_report,
                            IFNULL(jadwal.jadwal, 0) AS hk_jadwal,
                            akhir_grup_hk_lama,
                            awal_grup_hk_baru,
                            report.id_hemxxmh,
                            IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk
                        FROM (
                            SELECT 
                                COUNT(a.id) AS hk_report,
                                is_perubahan_hk,
                                a.id_hemxxmh,
                                akhir_grup_hk_lama,
                                awal_grup_hk_baru,
                                grup_hk_lama,
                                grup_hk_baru,
                                job.tanggal_masuk
                            FROM htsprrd AS a
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk,
                                    grup_hk_lama,
                                    grup_hk_baru,
                                    akhir_grup_hk_lama,
                                    awal_grup_hk_baru
                                FROM (
                                    SELECT
                                        id_hemxxmh,
                                        COUNT(id) AS is_perubahan_hk,
                                        jb.grup_hk AS grup_hk_baru,
                                        if(grup_hk = 1, 2, 1) AS grup_hk_lama,
                                        jb.tanggal_awal AS awal_grup_hk_baru,
                                        DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_grup_hk_lama
                                    FROM hemjbrd AS jb
                                    WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                            LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                            WHERE a.tanggal BETWEEN job.tanggal_masuk AND akhir_grup_hk_lama
                            AND a.st_clock_in <> "OFF"
                            GROUP BY a.id_hemxxmh
                        ) AS report
                        LEFT JOIN (
                            SELECT
                                tanggal,
                                htssctd.id_hemxxmh,
                                COUNT(id) AS jadwal
                            FROM htssctd
                            LEFT JOIN (
                                SELECT
                                id_hemxxmh,
                                COUNT(id) AS is_perubahan_hk,
                                if(grup_hk = 1, 2, 1) AS grup_hk_baru,
                                jb.tanggal_awal AS awal_grup_hk_baru
                            FROM hemjbrd AS jb
                            WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                            GROUP BY id_hemxxmh
                            ) AS jbrd ON jbrd.id_hemxxmh = htssctd.id_hemxxmh
                            WHERE id_htsxxmh <> 1 AND tanggal BETWEEN awal_grup_hk_baru AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                            GROUP BY id_hemxxmh
                        ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                    ) AS hk5hk6 ON hk5hk6.id_hemxxmh = a.id_hemxxmh
                    
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                1,
                gp,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Tunjangan Jabatan
        $qr_t_jab = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- tunjangan jabatan
                    IFNULL( 
                        if(c.tanggal_masuk BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir), 
                            hari_kerja / if(c.grup_hk = 1, 21, 25) * ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0),
                            if(c.tanggal_keluar BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir), 
                                keluar_report / if(c.grup_hk = 1, 21, 25) * ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0),
                            ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0))
                        ),
                    0) AS t_jab
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh
                
                    -- hari kerja karyawan baru
                    LEFT JOIN (
                        SELECT
                            (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
                            report.id_hemxxmh
                        FROM (
                            SELECT 
                                COUNT(a.id) AS hk_report,
                                a.id_hemxxmh
                            FROM htsprrd AS a
                            LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                            WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND :tanggal_akhir
                                AND a.st_clock_in <> "OFF" -- sebelumnya st-clock in
                            GROUP BY a.id_hemxxmh
                        ) AS report
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                COUNT(id) AS jadwal
                            FROM htssctd
                            WHERE tanggal BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                                AND id_htsxxmh <> 1
                            GROUP BY id_hemxxmh
                        ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                    ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                    
                    -- tanggal keluar
                    LEFT JOIN (
                        SELECT
                            keluar_report,
                            report.id_hemxxmh
                        FROM (
                            SELECT 
                                COUNT(a.id) AS keluar_report,
                                a.id_hemxxmh
                            FROM htsprrd AS a
                            LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                            WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY)
                                AND a.st_clock_in <> "OFF"
                            GROUP BY a.id_hemxxmh
                        ) AS report
                    ) AS keluar ON keluar.id_hemxxmh = a.id_hemxxmh

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
                                AND tanggal_efektif < :tanggal_awal
                                AND is_active = 1
                        ) AS subquery
                        WHERE row_num = 1
                    ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                32,
                t_jab,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Var Cost
        $qr_var_cost = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- Var Cost
                    IFNULL(nominal_var_cost, 0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- var_cost htpr_hemxxmh.id_hpcxxmh = 102
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_var_cost
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 102
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                102,
                nominal,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Masa Kerja
        $qr_masa_kerja = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- Masa Kerja
                    if(c.id_heyxxmh = 1, 
                        IFNULL( 
                            if(c.tanggal_masuk BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir), 
                                hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_mk,
                                if(c.tanggal_keluar BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir), 
                                    keluar_report / if(c.grup_hk = 1, 21, 25) * nominal_mk,
                                nominal_mk)
                            ),
                        0) ,
                     0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Masa Kerja
                LEFT JOIN (
                    SELECT
                        job.id_hemxxmh,
                        nominal AS nominal_mk,
                        job.id_hevgrmh,
                        masa_kerja_year
                    FROM (
                        SELECT
                            a.id_hemxxmh,
                            hev.id_hevgrmh AS id_hevgrmh,
                            IF(
                                a.tanggal_keluar IS NULL,
                                TIMESTAMPDIFF(YEAR, a.tanggal_masuk, CURDATE()),
                                TIMESTAMPDIFF(YEAR, a.tanggal_masuk, a.tanggal_keluar)
                            ) AS masa_kerja_year
                        FROM hemjbmh AS a
                        LEFT JOIN hevxxmh AS hev ON hev.id = a.id_hevxxmh
                        WHERE is_active = 1
                        GROUP BY a.id_hemxxmh
                    ) AS job
                    LEFT JOIN (
                        SELECT
                            id_hevgrmh,
                            tanggal_efektif,
                            nominal,
                            tahun_min,
                            tahun_max,
                            ROW_NUMBER() OVER (PARTITION BY id_hevgrmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hevgrmh_mk
                        WHERE
                            id_hpcxxmh = 31
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                    WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
                    GROUP BY job.id_hemxxmh
                ) AS mk ON mk.id_hemxxmh = a.id_hemxxmh

                -- hari kerja karyawan baru
                LEFT JOIN (
                    SELECT
                        (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND :tanggal_akhir
                            AND a.st_clock_in <> "OFF" -- sebelumnya st-clock in
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS jadwal
                        FROM htssctd
                        WHERE tanggal BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                            AND id_htsxxmh <> 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                
                -- tanggal keluar
                LEFT JOIN (
                    SELECT
                        keluar_report,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS keluar_report,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY)
                            AND a.st_clock_in <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                ) AS keluar ON keluar.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                31,
                nominal,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Premi Absen
        $qr_premi_abs = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- Premi Absen
                    if(a.id_hemxxmh = 67, 0, if(c.id_heyxxmh = 1, IFNULL(if(report_pot_premi >= 1, 0, premiabs), 0), 0)) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- nominal premi absen
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS premiabs 
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh

                -- validasi cari izin/absen yang memotong premi dari report presensi
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        report_pot_premi
                    FROM (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS report_pot_premi
                        FROM htsprrd
                        WHERE tanggal BETWEEN DATE_FORMAT(:tanggal_awal, "%Y-%m-01") AND LAST_DAY(:tanggal_awal)
                              AND is_pot_premi = 1
                        GROUP BY id_hemxxmh
                    ) c_report_pot_premi
                ) presensi_pot_premi ON presensi_pot_premi.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                33,
                nominal,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // JKK
        $qr_jkk = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung jkk
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jkk / 100) * gaji_bpjs,0),0)), 0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari bpjs_tk_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_tk,
                            bpjs_tk.id_hemxxmh
                        FROM bpjs_tk_exclude AS bpjs_tk
                        WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibtkmh untuk hitung bpjs
                LEFT JOIN (
                    SELECT
                      persen_jkk,
                      persen_jkm,
                      persen_jht_karyawan,
                      persen_jp_karyawan,
                      is_active
                    FROM (
                        SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                        FROM hibtkmh
                    ) sel_bpjs
                ) bpjs ON bpjs.is_active = 1
                
                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                7,
                floor(nominal) as jkk,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // JKM
        $qr_jkm = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung jkm
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jkm / 100) * gaji_bpjs,0),0)), 0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari bpjs_tk_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_tk,
                            bpjs_tk.id_hemxxmh
                        FROM bpjs_tk_exclude AS bpjs_tk
                        WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibtkmh untuk hitung bpjs
                LEFT JOIN (
                    SELECT
                      persen_jkk,
                      persen_jkm,
                      persen_jht_karyawan,
                      persen_jp_karyawan,
                      is_active
                    FROM (
                        SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                        FROM hibtkmh
                    ) sel_bpjs
                ) bpjs ON bpjs.is_active = 1
                
                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                10,
                floor(nominal) as jkmc,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // TRM JKKJKM
        $qr_trm_jkkjkm = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung terima jkk jkm
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0)), 0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari bpjs_tk_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_tk,
                            bpjs_tk.id_hemxxmh
                        FROM bpjs_tk_exclude AS bpjs_tk
                        WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibtkmh untuk hitung bpjs
                LEFT JOIN (
                    SELECT
                      persen_jkk,
                      persen_jkm,
                      persen_jht_karyawan,
                      persen_jp_karyawan,
                      is_active
                    FROM (
                        SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                        FROM hibtkmh
                    ) sel_bpjs
                ) bpjs ON bpjs.is_active = 1
                
                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                38,
                floor(nominal) as trm_jkkjkm,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Pendapatan Lain
        $qr_pendapatan_lain = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung pendapatan lain
                    IFNULL(nominal_pendapatan_lain,0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari Pendapatan_lain
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_pendapatan_lain
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 106
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) pendapatan_lain ON pendapatan_lain.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                106,
                nominal,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Durasi Lembur 1.5
        $qr_durasi15 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung durasi lembur1.5
                    sum_lembur15 AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                112,
                nominal,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Durasi Lembur 2
        $qr_durasi2 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung durasi lembur2
                    sum_lembur2 AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                113,
                nominal,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Durasi Lembur 3
        $qr_durasi3 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung durasi lembur3
                    sum_lembur3 AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                114,
                nominal,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // rp Lembur 15
        $qr_rp15 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung rp lembur15
                    FLOOR(
                        (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0)) / 173))) *
                          sum_lembur15_final
                        ) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_lembur_mati
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
        
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                115,
                nominal,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // rp Lembur 2
        $qr_rp2 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung rp lembur2
                    FLOOR(
                        (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0)) / 173))) *
                          sum_lembur2_final
                        ) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_lembur_mati
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
        
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                116,
                nominal,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // rp Lembur 3
        $qr_rp3 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung rp lembur3
                    FLOOR(
                        (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0)) / 173))) *
                          sum_lembur3_final
                        ) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_lembur_mati
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
        
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                117,
                nominal,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // jam lembur
        $qr_jam_ot = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    sum_lembur15 AS lembur15,
                    sum_lembur2 AS lembur2,
                    sum_lembur3 AS lembur3
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_lembur_mati
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
        
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                119,
                (lembur15 + lembur2 + lembur3) AS jam_lembur,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // jam lembur final
        $qr_jam_ot_final = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    sum_lembur15_final AS lembur15_final,
                    sum_lembur2_final AS lembur2_final,
                    sum_lembur3_final AS lembur3_final
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_lembur_mati
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
        
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                118,
                (lembur15_final + lembur2_final + lembur3_final) AS jam_lembur_final,
                0
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // upah lembur final lembur bersih penambah
        $qr_upah_lembur = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    FLOOR(
                        (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0)) / 173))) *
                          sum_lembur15_final
                      ) AS rp_lembur15,
                  FLOOR(
                        (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0)) / 173))) *
                          sum_lembur2_final
                      ) AS rp_lembur2,
                  FLOOR(
                        (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), nominal_t_jab, if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0)) / 173))) *
                          sum_lembur3_final
                      ) AS rp_lembur3
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- cari lembur 1.5, lembur 2, lembur 3
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        sum_lembur15,
                        sum_lembur2,
                        sum_lembur3,
                        sum_lembur4,
                        sum_lembur15_final,
                        sum_lembur2_final,
                        sum_lembur3_final,
                        sum_lembur4_final
                    FROM (
                        SELECT
                            prr.id_hemxxmh,
                            SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
                            SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
                            SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
                            SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                            
                            -- setelah dikali
                            SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
                            SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
                            SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
                            SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
                        FROM htsprrd AS prr
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) lembur_sum_table
                ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh

                -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_lembur_mati
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
        
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                37,
                FLOOR(IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3), 0)) AS lemburbersih,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // PPh21 Back
        $qr_pph21_back = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- pph21 back
                    IFNULL(nominal_pph21_back,0) AS pph21_back
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari pph21_back
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_pph21_back
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 11
                            AND plus_min = "Penambah"
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) pph21_back ON pph21_back.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                11,
                pph21_back,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // Kompensasi Rekontrak
        $qr_rekontrak = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- pph21 back
                    IFNULL(nominal_kompensasi_ak,0) AS kompensasi_ak
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari kompensasi_ak rekontrak KAK id_hpc = 108
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_kompensasi_ak
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 108
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) kompensasi_ak ON kompensasi_ak.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                108,
                kompensasi_ak,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // koreksi_lembur
        $qr_koreksi_lembur = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    IFNULL(nominal_koreksi_lembur,0) AS koreksi_lembur
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari koreksi_lembur rekontrak KAK id_hpc = 108
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_koreksi_lembur
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 111
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) koreksi_lembur ON koreksi_lembur.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                111,
                koreksi_lembur,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // koreksi_status
        $qr_koreksi_status = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    
                    IFNULL( 
                        if( c.tanggal_masuk BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_akhir), 
                            ((hk_baru / if(c.grup_hk = 1, 21, 25)) * if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp)),
                            0
	                     )
                    ,0) AS koreksi_status
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- hari kerja NIK baru
                LEFT JOIN (
                    SELECT
                        (hk_report) AS hk_baru,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            job.tanggal_masuk,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN job.tanggal_masuk AND LAST_DAY(:tanggal_awal)
                            AND a.st_jadwal <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                ) AS hk_baru ON hk_baru.id_hemxxmh = a.id_hemxxmh

                -- gaji pokok pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS pot_gp_pelatihan
                    FROM (
                        SELECT
                            id,
                            id_hesxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hesxxmh
                        WHERE
                            htpr_hesxxmh.id_hpcxxmh = 1
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) pot_gp_pelatihan ON pot_gp_pelatihan.id_hesxxmh = c.id_hesxxmh

                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                121,
                floor(koreksi_status) as koreksi_status,
                1
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // MULAI POTONGAN

        // pot_makan
        $qr_pot_makan = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    
                    IFNULL(pot_makan * pot_uang_makan, 0) AS pot_makan
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh
                
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
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                34,
                FLOOR(pot_makan) AS pot_makan,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot JKKJKM
        $qr_pot_jkkjkm = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    -- hitung terima jkk jkm
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0)), 0) AS nominal
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari bpjs_tk_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_tk,
                            bpjs_tk.id_hemxxmh
                        FROM bpjs_tk_exclude AS bpjs_tk
                        WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibtkmh untuk hitung bpjs
                LEFT JOIN (
                    SELECT
                      persen_jkk,
                      persen_jkm,
                      persen_jht_karyawan,
                      persen_jp_karyawan,
                      is_active
                    FROM (
                        SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                        FROM hibtkmh
                    ) sel_bpjs
                ) bpjs ON bpjs.is_active = 1
                
                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                39,
                FLOOR(nominal) as pot_jkkjkm,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot jht
        $qr_pot_jht = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jht_karyawan / 100) * gaji_bpjs, 0),0)), 0) AS pot_jht
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari bpjs_tk_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_tk,
                            bpjs_tk.id_hemxxmh
                        FROM bpjs_tk_exclude AS bpjs_tk
                        WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibtkmh untuk hitung bpjs
                LEFT JOIN (
                    SELECT
                      persen_jkk,
                      persen_jkm,
                      persen_jht_karyawan,
                      persen_jp_karyawan,
                      is_active
                    FROM (
                        SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                        FROM hibtkmh
                    ) sel_bpjs
                ) bpjs ON bpjs.is_active = 1
                
                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                6,
                FLOOR(pot_jht) AS pot_jht,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot_upah
        $qr_pot_upah = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    c.id_hesxxmh as hesxx,
                    c.tanggal_keluar,
                    hk_nik_lama,
                    -- pengali jam
                    (ifnull(if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp),0) + ifnull(nominal_t_jab,0) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) AS pengali_jam,
                    c.grup_hk,
                    report_pot_upah,
                    
                    -- ini untuk pot_upah rotasi
                    is_pot_upah_rotasi_lv,
                    ifnull(pot_upah_lv_lama, 0) as pot_upah_lv_lama,
                    IFNULL(pot_upah_lv_baru, 0) AS pot_upah_lv_baru,

                    -- nominal pengali rotasi level
                    (ifnull(if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp),0) + ifnull(nominal_jab_rotasi,0) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) AS pengali_rotasi_old

                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Masa Kerja
                LEFT JOIN (
                    SELECT
                        job.id_hemxxmh,
                        nominal AS nominal_mk,
                        job.id_hevgrmh,
                        masa_kerja_year
                    FROM (
                        SELECT
                            a.id_hemxxmh,
                            hev.id_hevgrmh AS id_hevgrmh,
                            IF(
                                a.tanggal_keluar IS NULL,
                                TIMESTAMPDIFF(YEAR, a.tanggal_masuk, CURDATE()),
                                TIMESTAMPDIFF(YEAR, a.tanggal_masuk, a.tanggal_keluar)
                            ) AS masa_kerja_year
                        FROM hemjbmh AS a
                        LEFT JOIN hevxxmh AS hev ON hev.id = a.id_hevxxmh
                        WHERE is_active = 1
                        GROUP BY a.id_hemxxmh
                    ) AS job
                    LEFT JOIN (
                        SELECT
                            id_hevgrmh,
                            tanggal_efektif,
                            nominal,
                            tahun_min,
                            tahun_max,
                            ROW_NUMBER() OVER (PARTITION BY id_hevgrmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hevgrmh_mk
                        WHERE
                            id_hpcxxmh = 31
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                    WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
                    GROUP BY job.id_hemxxmh
                ) AS mk ON mk.id_hemxxmh = a.id_hemxxmh
                    
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                
                
                -- var_cost htpr_hemxxmh.id_hpcxxmh = 102
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_var_cost
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 102
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = a.id_hemxxmh
                
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh
                
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh

                -- premi absen
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS premiabs 
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh
                
                -- validasi cari izin/absen yang memotong premi dari report presensi
                LEFT JOIN (
                    SELECT
                      id_hemxxmh,
                        report_pot_premi
                    FROM (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS report_pot_premi
                        FROM htsprrd
                        WHERE tanggal BETWEEN DATE_FORMAT(:tanggal_awal, "%Y-%m-01") AND LAST_DAY(:tanggal_awal)
                              AND is_pot_premi = 1
                        GROUP BY id_hemxxmh
                    ) c_report_pot_premi
                ) presensi_pot_premi ON presensi_pot_premi.id_hemxxmh = a.id_hemxxmh
                
                -- validasi cari izin/absen yang memotong upah dari report presensi
                LEFT JOIN (
                    SELECT
                      id_hemxxmh,
                        report_pot_upah
                    FROM (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS report_pot_upah
                        FROM htsprrd
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                              AND is_pot_upah = 1
                        GROUP BY id_hemxxmh
                    ) c_report_pot_upah
                ) presensi_pot_upah ON presensi_pot_upah.id_hemxxmh = a.id_hemxxmh
                
                -- hari kerja karyawan baru
                LEFT JOIN (
                    SELECT
                        (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND :tanggal_akhir
                            AND a.st_clock_in <> "OFF" -- sebelumnya st-clock in
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS jadwal
                        FROM htssctd
                        WHERE tanggal BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                            AND id_htsxxmh <> 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                
                -- tanggal keluar
                LEFT JOIN (
                    SELECT
                        keluar_report,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS keluar_report,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY)
                            AND a.st_clock_in <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                ) AS keluar ON keluar.id_hemxxmh = a.id_hemxxmh

                -- gaji pokok pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS pot_gp_pelatihan
                    FROM (
                        SELECT
                            id,
                            id_hesxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hesxxmh
                        WHERE
                            htpr_hesxxmh.id_hpcxxmh = 1
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) pot_gp_pelatihan ON pot_gp_pelatihan.id_hesxxmh = c.id_hesxxmh

                -- case HK 5 HK 6
                LEFT JOIN (
                    SELECT
                        grup_hk_lama,
                        grup_hk_baru,
                        hk_report,
                        IFNULL(jadwal.jadwal, 0) AS hk_jadwal,
                        akhir_grup_hk_lama,
                        awal_grup_hk_baru,
                        report.id_hemxxmh,
                        IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            is_perubahan_hk,
                            a.id_hemxxmh,
                            akhir_grup_hk_lama,
                            awal_grup_hk_baru,
                            grup_hk_lama,
                            grup_hk_baru,
                            job.tanggal_masuk
                        FROM htsprrd AS a
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk,
                                grup_hk_lama,
                                grup_hk_baru,
                                akhir_grup_hk_lama,
                                awal_grup_hk_baru
                            FROM (
                                SELECT
                                    id_hemxxmh,
                                    COUNT(id) AS is_perubahan_hk,
                                    jb.grup_hk AS grup_hk_baru,
                                    if(grup_hk = 1, 2, 1) AS grup_hk_lama,
                                    jb.tanggal_awal AS awal_grup_hk_baru,
                                    DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_grup_hk_lama
                                FROM hemjbrd AS jb
                                WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN job.tanggal_masuk AND akhir_grup_hk_lama
                        AND a.st_clock_in <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            tanggal,
                            htssctd.id_hemxxmh,
                            COUNT(id) AS jadwal
                        FROM htssctd
                        LEFT JOIN (
                            SELECT
                            id_hemxxmh,
                            COUNT(id) AS is_perubahan_hk,
                            if(grup_hk = 1, 2, 1) AS grup_hk_baru,
                            jb.tanggal_awal AS awal_grup_hk_baru
                        FROM hemjbrd AS jb
                        WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                        GROUP BY id_hemxxmh
                        ) AS jbrd ON jbrd.id_hemxxmh = htssctd.id_hemxxmh
                        WHERE id_htsxxmh <> 1 AND tanggal BETWEEN awal_grup_hk_baru AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                ) AS hk5hk6 ON hk5hk6.id_hemxxmh = a.id_hemxxmh
                
                -- HK NIK LAMA
                LEFT JOIN (
                    SELECT
                        hem.nama AS nama,
                        hk_nik_lama,
                        hem.id AS id_hemxxmh,
                        b.id AS id_hemxxmh_new
                    FROM hemxxmh AS hem
                    LEFT JOIN hemxxmh AS b ON b.nama = hem.nama
                    LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = b.id
                    LEFT JOIN (
                        SELECT
                            COUNT(rd.id) AS hk_nik_lama,
                            job.tanggal_masuk,
                            rd.id_hemxxmh
                        FROM htsprrd AS rd
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = rd.id_hemxxmh
                        WHERE rd.tanggal BETWEEN date_add(job.tanggal_masuk, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_awal)
                    AND rd.status_presensi_in = "HK"
                        GROUP BY rd.id_hemxxmh
                    ) AS prd ON prd.id_hemxxmh = b.id
                    GROUP BY nama
                    ORDER BY b.id DESC
                ) AS hk_nik_lama ON hk_nik_lama.id_hemxxmh = a.id_hemxxmh

                -- hari kerja NIK baru
                LEFT JOIN (
                    SELECT
                        (hk_report) AS hk_baru,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            job.tanggal_masuk,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN job.tanggal_masuk AND LAST_DAY(:tanggal_awal)
                            AND a.st_jadwal <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                ) AS hk_baru ON hk_baru.id_hemxxmh = a.id_hemxxmh

                -- rotasi level pot_upah
                LEFT JOIN (
                    SELECT
                        ifnull(pot_upah_lv_lama, 0) as pot_upah_lv_lama,
                        IFNULL(jadwal.pot_upah_lv_new, 0) AS pot_upah_lv_baru,
                        jadwal.id_hevxxmh_awal,
                        jadwal.id_hevxxmh_akhir,
                        akhir_tgl_level_akhir,
                        tgl_awal_lv_baru,
                        report.id_hemxxmh,
                        IFNULL(is_pot_upah_rotasi_lv, 0) AS is_pot_upah_rotasi_lv
                    FROM (
                        SELECT 
                            SUM(a.is_pot_upah) AS pot_upah_lv_lama,
                            is_pot_upah_rotasi_lv,
                            a.id_hemxxmh,
                            akhir_tgl_level_akhir,
                            tgl_awal_lv_baru,
                            job.tanggal_masuk
                        FROM htsprrd AS a
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(is_pot_upah_rotasi_lv, 0) AS is_pot_upah_rotasi_lv,
                                akhir_tgl_level_akhir,
                                tgl_awal_lv_baru
                            FROM (
                                SELECT
                                    id_hemxxmh,
                                    COUNT(id) AS is_pot_upah_rotasi_lv,
                                    jb.tanggal_awal AS tgl_awal_lv_baru,
                                    DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_tgl_level_akhir
                                FROM hemjbrd AS jb
                                WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                            ) AS subquery
                        ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN :tanggal_awal AND akhir_tgl_level_akhir
                        AND a.is_pot_upah > 0
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            tanggal,
                            id_hevxxmh_awal,
                            id_hevxxmh_akhir,
                            htsprrd.id_hemxxmh,
                            SUM(is_pot_upah) AS pot_upah_lv_new
                        FROM htsprrd
                        LEFT JOIN (
                            SELECT
                            id_hemxxmh,
                            id_hevxxmh_awal,
                            id_hevxxmh_akhir,
                            jb.tanggal_awal AS tgl_awal_lv_baru
                        FROM hemjbrd AS jb
                        WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                        ) AS jbrd ON jbrd.id_hemxxmh = htsprrd.id_hemxxmh
                        WHERE is_pot_upah > 0 AND tanggal BETWEEN tgl_awal_lv_baru AND :tanggal_akhir AND is_active = 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                    
                ) AS pot_upah_rotasi ON pot_upah_rotasi.id_hemxxmh = a.id_hemxxmh

                -- rotasi level pot_jam
                LEFT JOIN (
                    SELECT
                        ifnull(pot_jam_lv_lama, 0) as pot_jam_lv_lama,
                        IFNULL(jadwal.pot_jam_lv_new, 0) AS pot_jam_lv_baru,
                        jadwal.id_hevxxmh_awal,
                        jadwal.id_hevxxmh_akhir,
                        akhir_tgl_level_akhir,
                        ifnull(t_jab_rotasi_old,0) AS nominal_jab_rotasi,
                        tgl_awal_lv_baru,
                        report.id_hemxxmh,
                        IFNULL(is_pot_jam_rotasi_lv, 0) AS is_pot_jam_rotasi_lv
                    FROM (
                        SELECT 
                            SUM(a.pot_hk) AS pot_jam_lv_lama,
                            is_pot_jam_rotasi_lv,
                            a.id_hemxxmh,
                            akhir_tgl_level_akhir,
                            tgl_awal_lv_baru,
                            job.tanggal_masuk
                        FROM htsprrd AS a
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(is_pot_jam_rotasi_lv, 0) AS is_pot_jam_rotasi_lv,
                                akhir_tgl_level_akhir,
                                tgl_awal_lv_baru
                            FROM (
                                SELECT
                                    id_hemxxmh,
                                    COUNT(id) AS is_pot_jam_rotasi_lv,
                                    jb.tanggal_awal AS tgl_awal_lv_baru,
                                    DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_tgl_level_akhir
                                FROM hemjbrd AS jb
                                WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                            ) AS subquery
                        ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN :tanggal_awal AND akhir_tgl_level_akhir
                        AND a.pot_hk > 0
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            tanggal,
                            id_hevxxmh_awal,
                            id_hevxxmh_akhir,
                            htsprrd.id_hemxxmh,
                            SUM(pot_hk) AS pot_jam_lv_new
                        FROM htsprrd
                        LEFT JOIN (
                            SELECT
                            id_hemxxmh,
                            id_hevxxmh_awal,
                            id_hevxxmh_akhir,
                            jb.tanggal_awal AS tgl_awal_lv_baru
                        FROM hemjbrd AS jb
                        WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                        ) AS jbrd ON jbrd.id_hemxxmh = htsprrd.id_hemxxmh
                        WHERE pot_hk > 0 AND tanggal BETWEEN tgl_awal_lv_baru AND :tanggal_akhir AND is_active = 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                    LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS t_jab_rotasi_old
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                    ) t_jabatan ON t_jabatan.id_hevxxmh = jadwal.id_hevxxmh_awal
                ) AS pot_jam_rotasi ON pot_jam_rotasi.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                35,
                FLOOR(
                    if( is_pot_upah_rotasi_lv > 0, 
                        (if( tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), hk_nik_lama * (pengali_rotasi_old / IF(grup_hk = 1, 21, 25)), (IF(pot_upah_lv_lama > 0, if(id_heyxxmd = 1 AND hesxx = 3, pot_upah_lv_lama * IF(grup_hk = 1, 83509, 70148), pot_upah_lv_lama * pengali_rotasi_old / IF(grup_hk = 1, 21, 25)) , 0)) ))
                        +
                        (if( tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), hk_nik_lama * (pengali_jam / IF(grup_hk = 1, 21, 25)), (IF(pot_upah_lv_baru > 0, if(id_heyxxmd = 1 AND hesxx = 3, pot_upah_lv_baru * IF(grup_hk = 1, 83509, 70148), pot_upah_lv_baru * pengali_jam / IF(grup_hk = 1, 21, 25)) , 0)) )),
                        if( tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), hk_nik_lama * (pengali_jam / IF(grup_hk = 1, 21, 25)), (IF(report_pot_upah > 0, if(id_heyxxmd = 1 AND hesxx = 3, report_pot_upah * IF(grup_hk = 1, 83509, 70148), report_pot_upah * pengali_jam / IF(grup_hk = 1, 21, 25)) , 0)) )
                    ) 
                ) AS pot_upah,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot_jam
        $qr_pot_jam = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    c.id_hesxxmh as hesxx,
                    c.tanggal_keluar,
                    hk_nik_lama,
                    -- pengali jam
                    (ifnull(if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp),0) + ifnull(nominal_t_jab,0) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) AS pengali_jam,
                    c.grup_hk,
                    report_pot_jam,
                    -- ini untuk pot_jam rotasi
                    is_pot_jam_rotasi_lv,
                    ifnull(pot_jam_lv_lama, 0) as pot_jam_lv_lama,
                    IFNULL(pot_jam_lv_baru, 0) AS pot_jam_lv_baru,

                    -- nominal pengali rotasi level
                    (ifnull(if(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp),0) + ifnull(nominal_jab_rotasi,0) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) AS pengali_rotasi_old

                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Masa Kerja
                LEFT JOIN (
                    SELECT
                        job.id_hemxxmh,
                        nominal AS nominal_mk,
                        job.id_hevgrmh,
                        masa_kerja_year
                    FROM (
                        SELECT
                            a.id_hemxxmh,
                            hev.id_hevgrmh AS id_hevgrmh,
                            IF(
                                a.tanggal_keluar IS NULL,
                                TIMESTAMPDIFF(YEAR, a.tanggal_masuk, CURDATE()),
                                TIMESTAMPDIFF(YEAR, a.tanggal_masuk, a.tanggal_keluar)
                            ) AS masa_kerja_year
                        FROM hemjbmh AS a
                        LEFT JOIN hevxxmh AS hev ON hev.id = a.id_hevxxmh
                        WHERE is_active = 1
                        GROUP BY a.id_hemxxmh
                    ) AS job
                    LEFT JOIN (
                        SELECT
                            id_hevgrmh,
                            tanggal_efektif,
                            nominal,
                            tahun_min,
                            tahun_max,
                            ROW_NUMBER() OVER (PARTITION BY id_hevgrmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hevgrmh_mk
                        WHERE
                            id_hpcxxmh = 31
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                    WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
                    GROUP BY job.id_hemxxmh
                ) AS mk ON mk.id_hemxxmh = a.id_hemxxmh
                    
                -- gaji pokok
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_gp
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                
                
                -- var_cost htpr_hemxxmh.id_hpcxxmh = 102
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_var_cost
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 102
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = a.id_hemxxmh
                
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh
                
                -- t jabatan untuk Tetap
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS nominal_t_jab
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
                            AND is_active = 1
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
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = a.id_hemxxmh

                -- premi absen
                LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS premiabs 
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh
                
                -- validasi cari izin/absen yang memotong premi dari report presensi
                LEFT JOIN (
                    SELECT
                      id_hemxxmh,
                        report_pot_premi
                    FROM (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS report_pot_premi
                        FROM htsprrd
                        WHERE tanggal BETWEEN DATE_FORMAT(:tanggal_awal, "%Y-%m-01") AND LAST_DAY(:tanggal_awal)
                              AND is_pot_premi = 1
                        GROUP BY id_hemxxmh
                    ) c_report_pot_premi
                ) presensi_pot_premi ON presensi_pot_premi.id_hemxxmh = a.id_hemxxmh
                
                -- hari kerja karyawan baru
                LEFT JOIN (
                    SELECT
                        (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND :tanggal_akhir
                            AND a.st_clock_in <> "OFF" -- sebelumnya st-clock in
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS jadwal
                        FROM htssctd
                        WHERE tanggal BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                            AND id_htsxxmh <> 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                
                -- tanggal keluar
                LEFT JOIN (
                    SELECT
                        keluar_report,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS keluar_report,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY)
                            AND a.st_clock_in <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                ) AS keluar ON keluar.id_hemxxmh = a.id_hemxxmh

                -- validasi cari izin/absen yang memotong jam dari report presensi
                LEFT JOIN (
                    SELECT
                      id_hemxxmh,
                        report_pot_jam
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(pot_hk) AS report_pot_jam
                        FROM htsprrd
                        WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) c_report_pot_jam
                ) presensi_pot_jam ON presensi_pot_jam.id_hemxxmh = a.id_hemxxmh
                
                -- gaji pokok pelatihan
                LEFT JOIN (
                    SELECT
                        id_hesxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS pot_gp_pelatihan
                    FROM (
                        SELECT
                            id,
                            id_hesxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hesxxmh
                        WHERE
                            htpr_hesxxmh.id_hpcxxmh = 1
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) pot_gp_pelatihan ON pot_gp_pelatihan.id_hesxxmh = c.id_hesxxmh

                -- case HK 5 HK 6
                LEFT JOIN (
                    SELECT
                        grup_hk_lama,
                        grup_hk_baru,
                        hk_report,
                        IFNULL(jadwal.jadwal, 0) AS hk_jadwal,
                        akhir_grup_hk_lama,
                        awal_grup_hk_baru,
                        report.id_hemxxmh,
                        IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            is_perubahan_hk,
                            a.id_hemxxmh,
                            akhir_grup_hk_lama,
                            awal_grup_hk_baru,
                            grup_hk_lama,
                            grup_hk_baru,
                            job.tanggal_masuk
                        FROM htsprrd AS a
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk,
                                grup_hk_lama,
                                grup_hk_baru,
                                akhir_grup_hk_lama,
                                awal_grup_hk_baru
                            FROM (
                                SELECT
                                    id_hemxxmh,
                                    COUNT(id) AS is_perubahan_hk,
                                    jb.grup_hk AS grup_hk_baru,
                                    if(grup_hk = 1, 2, 1) AS grup_hk_lama,
                                    jb.tanggal_awal AS awal_grup_hk_baru,
                                    DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_grup_hk_lama
                                FROM hemjbrd AS jb
                                WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN job.tanggal_masuk AND akhir_grup_hk_lama
                        AND a.st_clock_in <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            tanggal,
                            htssctd.id_hemxxmh,
                            COUNT(id) AS jadwal
                        FROM htssctd
                        LEFT JOIN (
                            SELECT
                            id_hemxxmh,
                            COUNT(id) AS is_perubahan_hk,
                            if(grup_hk = 1, 2, 1) AS grup_hk_baru,
                            jb.tanggal_awal AS awal_grup_hk_baru
                        FROM hemjbrd AS jb
                        WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                        GROUP BY id_hemxxmh
                        ) AS jbrd ON jbrd.id_hemxxmh = htssctd.id_hemxxmh
                        WHERE id_htsxxmh <> 1 AND tanggal BETWEEN awal_grup_hk_baru AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                ) AS hk5hk6 ON hk5hk6.id_hemxxmh = a.id_hemxxmh
                
                -- HK NIK LAMA
                LEFT JOIN (
                    SELECT
                        hem.nama AS nama,
                        hk_nik_lama,
                        hem.id AS id_hemxxmh,
                        b.id AS id_hemxxmh_new
                    FROM hemxxmh AS hem
                    LEFT JOIN hemxxmh AS b ON b.nama = hem.nama
                    LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = b.id
                    LEFT JOIN (
                        SELECT
                            COUNT(rd.id) AS hk_nik_lama,
                            job.tanggal_masuk,
                            rd.id_hemxxmh
                        FROM htsprrd AS rd
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = rd.id_hemxxmh
                        WHERE rd.tanggal BETWEEN date_add(job.tanggal_masuk, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_awal)
                    AND rd.status_presensi_in = "HK"
                        GROUP BY rd.id_hemxxmh
                    ) AS prd ON prd.id_hemxxmh = b.id
                    GROUP BY nama
                    ORDER BY b.id DESC
                ) AS hk_nik_lama ON hk_nik_lama.id_hemxxmh = a.id_hemxxmh

                -- hari kerja NIK baru
                LEFT JOIN (
                    SELECT
                        (hk_report) AS hk_baru,
                        report.id_hemxxmh
                    FROM (
                        SELECT 
                            COUNT(a.id) AS hk_report,
                            job.tanggal_masuk,
                            a.id_hemxxmh
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN job.tanggal_masuk AND LAST_DAY(:tanggal_awal)
                            AND a.st_jadwal <> "OFF"
                        GROUP BY a.id_hemxxmh
                    ) AS report
                ) AS hk_baru ON hk_baru.id_hemxxmh = a.id_hemxxmh

                -- rotasi level pot_jam
                LEFT JOIN (
                    SELECT
                        ifnull(pot_jam_lv_lama, 0) as pot_jam_lv_lama,
                        IFNULL(jadwal.pot_jam_lv_new, 0) AS pot_jam_lv_baru,
                        jadwal.id_hevxxmh_awal,
                        jadwal.id_hevxxmh_akhir,
                        akhir_tgl_level_akhir,
                        ifnull(t_jab_rotasi_old,0) AS nominal_jab_rotasi,
                        tgl_awal_lv_baru,
                        report.id_hemxxmh,
                        IFNULL(is_pot_jam_rotasi_lv, 0) AS is_pot_jam_rotasi_lv
                    FROM (
                        SELECT 
                            SUM(a.pot_hk) AS pot_jam_lv_lama,
                            is_pot_jam_rotasi_lv,
                            a.id_hemxxmh,
                            akhir_tgl_level_akhir,
                            tgl_awal_lv_baru,
                            job.tanggal_masuk
                        FROM htsprrd AS a
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(is_pot_jam_rotasi_lv, 0) AS is_pot_jam_rotasi_lv,
                                akhir_tgl_level_akhir,
                                tgl_awal_lv_baru
                            FROM (
                                SELECT
                                    id_hemxxmh,
                                    COUNT(id) AS is_pot_jam_rotasi_lv,
                                    jb.tanggal_awal AS tgl_awal_lv_baru,
                                    DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_tgl_level_akhir
                                FROM hemjbrd AS jb
                                WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                            ) AS subquery
                        ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN :tanggal_awal AND akhir_tgl_level_akhir
                        AND a.pot_hk > 0
                        GROUP BY a.id_hemxxmh
                    ) AS report
                    LEFT JOIN (
                        SELECT
                            tanggal,
                            id_hevxxmh_awal,
                            id_hevxxmh_akhir,
                            htsprrd.id_hemxxmh,
                            SUM(pot_hk) AS pot_jam_lv_new
                        FROM htsprrd
                        LEFT JOIN (
                            SELECT
                            id_hemxxmh,
                            id_hevxxmh_awal,
                            id_hevxxmh_akhir,
                            jb.tanggal_awal AS tgl_awal_lv_baru
                        FROM hemjbrd AS jb
                        WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                        ) AS jbrd ON jbrd.id_hemxxmh = htsprrd.id_hemxxmh
                        WHERE pot_hk > 0 AND tanggal BETWEEN tgl_awal_lv_baru AND :tanggal_akhir AND is_active = 1
                        GROUP BY id_hemxxmh
                    ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                    LEFT JOIN (
                    SELECT
                        id_hevxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS t_jab_rotasi_old
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
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                    ) t_jabatan ON t_jabatan.id_hevxxmh = jadwal.id_hevxxmh_awal
                ) AS pot_jam_rotasi ON pot_jam_rotasi.id_hemxxmh = a.id_hemxxmh

                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                120,
                FLOOR(
                    if( is_pot_jam_rotasi_lv > 0, 
                        (if(id_heyxxmd = 1 AND hesxx = 3, pot_jam_lv_lama * IF(grup_hk = 1, 83509 * 21, 70148 * 25) / 173, pot_jam_lv_lama * pengali_rotasi_old / 173))
                         + 
                        (if(id_heyxxmd = 1 AND hesxx = 3, pot_jam_lv_baru * IF(grup_hk = 1, 83509 * 21, 70148 * 25) / 173, pot_jam_lv_baru * pengali_jam / 173)), 
                        IF(report_pot_jam > 0, 
                            if(id_heyxxmd = 1 AND hesxx = 3, 
                                report_pot_jam * IF(grup_hk = 1, 83509 * 21, 70148 * 25) / 173, 
                                report_pot_jam * pengali_jam / 173
                            ),
                        0)
                    )
                ) AS pot_jam,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');
        
        // pot bpjs
        $qr_pot_bpjs = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    is_terminasi,
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_kes > 0, 0, if(id_heyxxmd = 3, IFNULL((persen_karyawan / 100) * gaji_bpjs, 0),0)), 0) AS pot_bpjs
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari resign
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(is_terminasi, 0) AS is_terminasi
                    FROM (
                        SELECT
                            id_hemxxmh,
                            COUNT(id) AS is_terminasi
                        FROM hemjbrd
                        WHERE id_harxxmh IN (3, 4)
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) resign ON resign.id_hemxxmh = a.id_hemxxmh

                -- Cari bpjs_kes_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_kes, 0) AS skip_c_bpjs_kes
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_kes,
                            bpjs_kes.id_hemxxmh
                        FROM bpjs_kes_exclude AS bpjs_kes
                        WHERE bpjs_kes.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_kes_exclude ON bpjs_kes_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibksmh untuk hitung bpjs kesehatan
                LEFT JOIN (
                    SELECT
                        persen_karyawan,
                        is_active
                    FROM (
                        SELECT
                            persen_karyawan,
                            is_active
                        FROM hibksmh
                    ) sel_bpjs
                ) bpjs_kesehatan ON bpjs_kesehatan.is_active = 1

                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                4,
                FLOOR(if(is_terminasi > 0, pot_bpjs * 2, pot_bpjs)) AS pot_bpjs,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot psiun
        $qr_pot_psiun = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jp_karyawan / 100) * gaji_bpjs, 0),0)), 0) AS pot_psiun
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari bpjs_tk_exclude
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                    FROM (
                        SELECT
                            COUNT(id) AS c_bpjs_tk,
                            bpjs_tk.id_hemxxmh
                        FROM bpjs_tk_exclude AS bpjs_tk
                        WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                
                -- select data dari hibtkmh untuk hitung bpjs
                LEFT JOIN (
                    SELECT
                      persen_jkk,
                      persen_jkm,
                      persen_jht_karyawan,
                      persen_jp_karyawan,
                      is_active
                    FROM (
                        SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                        FROM hibtkmh
                    ) sel_bpjs
                ) bpjs ON bpjs.is_active = 1
                
                -- select gaji bpjs
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        tanggal_efektif,
                        IFNULL(nominal, 0) AS gaji_bpjs
                    FROM (
                        SELECT
                            id,
                            id_hemxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hemxxmh
                        WHERE
                            htpr_hemxxmh.id_hpcxxmh = 2
                            AND tanggal_efektif < :tanggal_awal
                            AND is_active = 1
                    ) AS subquery
                    WHERE row_num = 1
                ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                122,
                FLOOR(pot_psiun) AS pot_psiun,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot pinjaman
        $qr_pot_pinjaman = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    IFNULL(nominal_pinjaman,0) AS pot_pinjaman
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari Pinjaman
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_pinjaman
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 105
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) pinjaman ON pinjaman.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                105,
                pot_pinjaman,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot klaim
        $qr_pot_klaim = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    IFNULL(nominal_klaim,0) AS pot_klaim
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari klaim
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_klaim
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 104
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) klaim ON klaim.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                104,
                pot_klaim,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot denda_apd
        $qr_pot_denda_apd = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    IFNULL(nominal_denda_apd,0) AS pot_denda_apd
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari denda_apd
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_denda_apd
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 103
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) denda_apd ON denda_apd.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                103,
                pot_denda_apd,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        // pot pot_pph21
        $qr_pot_pot_pph21 = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd_2 (
                id_hpyxxth_2, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal,
                jenis
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    id_heyxxmd,
                    a.id_hemxxmh,
                    IFNULL(nominal_pph21,0) AS pot_pph21
                    
                FROM htsprrd AS a
                LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
                -- Cari pot_pph21
                LEFT JOIN (
                    SELECT
                        id_hemxxmh,
                        IFNULL(nominal, 0) AS nominal_pph21
                    FROM (
                        SELECT
                            id_hemxxmh,
                            SUM(nominal) as nominal
                        FROM hpy_piutang_d
                        WHERE
                            hpy_piutang_d.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                            AND id_hpcxxmh = 11
                            AND plus_min = "Pengurang"
                            AND is_approve = 1
                        GROUP BY id_hemxxmh
                    ) AS subquery
                ) pph21 ON pph21.id_hemxxmh = a.id_hemxxmh
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
               ' . $id_hpyxxth_2 . ',
                qs_payroll.id_hemxxmh,
                11,
                pot_pph21,
                2
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        $qu_hpyxxth_2 = $db
            ->query('update', 'hpyxxth_2')
            ->set('generated_on',$timestamp)
            ->where('id',$id_hpyxxth_2)
        ->exec();
        
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