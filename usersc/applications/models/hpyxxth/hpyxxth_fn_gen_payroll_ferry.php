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
    $id_hpyxxth     = $_POST['id_hpyxxth'];

    $timestamp     = $_POST['timestamp']; //tambah timestamp untuk view

    try{
        $db->transaction();
        // BEGIN GAJI POKOK
        $qd_gp = $db
            ->query('delete', 'hpyemtd')
            ->where('id_hpyxxth',$id_hpyxxth)
            // ->where('id_heyxxmh',$id_heyxxmh)
        ->exec();

        //Pembulatan FLOOR

        $qr_gp = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('
            INSERT INTO hpyemtd (
                id_hpyxxth, 
                id_hemxxmh, 
                gp,
                t_jab,
                var_cost,
                fix_cost,
                premi_abs,
                jkk,
                jkm,
                trm_jkkjkm,
                
                lembur15,
                lembur2,
                lembur3,
                lembur4,

                lembur15_final,
                lembur2_final,
                lembur3_final,
                lembur4_final,

                rp_lembur15,
                rp_lembur2,
                rp_lembur3,
                rp_lembur4,

                jam_lembur,
                lemburbersih,
                pot_makan,
                pot_jkkjkm,
                pot_jht,
                pot_upah,
                pot_bpjs,
                pot_psiun,
                gaji_bersih,
                bulat,
                gaji_terima
            )
            WITH qs_payroll AS (
                SELECT DISTINCT
                    a.id_hemxxmh,
                    report_pot_upah,
                    (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173))) AS nominal_lembur_jam,
                    FLOOR(
                          (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173))) *
                            sum_lembur15_final
                        ) AS rp_lembur15,
                    FLOOR(
                          (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173))) *
                            sum_lembur2_final
                        ) AS rp_lembur2,
                    FLOOR(
                          (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173))) *
                            sum_lembur3_final
                        ) AS rp_lembur3,
                    FLOOR(
                          (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173))) *
                            sum_lembur4_final
                        ) AS rp_lembur4,
            
                    c.id_hesxxmh,
                    c.id_heyxxmd,
                    c.grup_hk,
                    IFNULL(hari_kerja, 0) AS hari_kerja,
                    
                    -- gaji pokok
                    IFNULL( 
                         if(c.tanggal_masuk BETWEEN :tanggal_awal AND :tanggal_akhir, 
                             hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_gp,
                         nominal_gp),
                     0) AS gp,
                     
                     -- tunjangan jabatan
                    IFNULL( 
                         if(c.tanggal_masuk BETWEEN :tanggal_awal AND :tanggal_akhir, 
                             hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_t_jab,
                         nominal_t_jab),
                     0) AS t_jab,
                     
                     -- var_cost
                     IFNULL(nominal_var_cost, 0) AS var_cost,
                     
                     -- fix cost atau masa kerja
                    IFNULL( 
                         if(c.tanggal_masuk BETWEEN :tanggal_awal AND :tanggal_akhir, 
                             hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_mk,
                         nominal_mk),
                     0) AS fix_cost,
                     
                     -- premi absen dengan validasi jika ada izin/absen yang memotong premi maka premi absen == 0 atau hangus
                     -- revisi 2 Oct, premi absen hanya untuk organik, os tidak ada
                    if(c.id_heyxxmh = 1, IFNULL(if(report_pot_premi >= 1, 0, premiabs), 0), 0) AS premi_abs,
                    
                    -- hitung jkk
                    if(id_heyxxmd = 3,IFNULL((persen_jkk / 100) * gaji_bpjs,0),0) AS jkk,
                    
                    -- hitung jkm
                    if(id_heyxxmd = 3,IFNULL((persen_jkm / 100) * gaji_bpjs,0),0) AS jkm,
                    
                    -- trm_jkkjkm == jkk + jkm
                    if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0) AS trm_jkkjkm,
                    
                    -- mulai lembur
                    sum_lembur15 AS lembur15,
                    sum_lembur2 AS lembur2,
                    sum_lembur3 AS lembur3,
                    sum_lembur4 AS lembur4,
                    
                    sum_lembur15_final AS lembur15_final,
                    sum_lembur2_final AS lembur2_final,
                    sum_lembur3_final AS lembur3_final,
                    sum_lembur4_final AS lembur4_final,
                    
                    
                    -- hitung pot makan
                    IFNULL(pot_makan * pot_uang_makan, 0) AS pot_makan,
                    
                    -- pot_jkkjkm == jkk + jkm (sama dengan pot_jkkjkm)
                    if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0) AS pot_jkkjkm,
                    
                    -- hitung pot_jht
                    if(id_heyxxmd = 3,IFNULL((persen_jht_karyawan / 100) * gaji_bpjs, 0),0) AS pot_jht,
                    
                    -- hitung pot_bpjs
                    if(id_heyxxmd = 3, IFNULL((persen_karyawan / 100) * gaji_bpjs, 0),0) AS pot_bpjs,
                    
                    -- hitung pot_psiun
                    
                    -- revisi khusus yang sub tipe == karyawan
                    if(id_heyxxmd = 3,IFNULL((persen_jp_karyawan / 100) * gaji_bpjs, 0),0) AS pot_psiun
                    
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
                        ) AS subquery
                        WHERE row_num = 1
                    ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = a.id_hemxxmh
                    
                    
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
                        ) AS subquery
                        WHERE row_num = 1
                    ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
            
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
                    
                    -- t jabatan
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
                        ) AS subquery
                        WHERE row_num = 1
                    ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh
                    
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
                            WHERE a.tanggal BETWEEN job.tanggal_masuk AND :tanggal_akhir
                                AND a.st_clock_in <> "OFF"
                            GROUP BY a.id_hemxxmh
                        ) AS report
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                COUNT(id) AS jadwal
                            FROM htssctd
                            WHERE tanggal BETWEEN :tanggal_akhir AND LAST_DAY(:tanggal_akhir)
                                AND id_htsxxmh <> 1
                            GROUP BY id_hemxxmh
                        ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                    ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                    
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
                        ) AS subquery
                        WHERE row_num = 1
                    ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                    
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
                
                WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
            )
            SELECT
                ' . $id_hpyxxth . ',
                qs_payroll.id_hemxxmh,
                gp,
                t_jab,
                var_cost,
                fix_cost,
                premi_abs,
                FLOOR(jkk) AS jkk,
                FLOOR(jkm) AS jkm,
                FLOOR(trm_jkkjkm) AS trm_jkkjkm,
                lembur15,
                lembur2,
                lembur3,
                lembur4,
            
                lembur15_final,
                lembur2_final,
                lembur3_final,
                lembur4_final,
            
                FLOOR(rp_lembur15) AS rp_lembur15,
                FLOOR(rp_lembur2) AS rp_lembur2,
                FLOOR(rp_lembur3) AS rp_lembur3,
                FLOOR(rp_lembur4) AS rp_lembur4,
            
                (lembur15 + lembur2 + lembur3 + lembur4) AS jam_lembur,
                FLOOR(IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4), 0)) AS lemburbersih,
                FLOOR(pot_makan) AS pot_makan,
                FLOOR(pot_jkkjkm) AS pot_jkkjkm,
                CEIL(pot_jht) AS pot_jht,
                FLOOR(IF(report_pot_upah >= 1, (gp + t_jab + var_cost + fix_cost) / IF(grup_hk = 1, 21, 25), 0)) AS pot_upah,
                FLOOR(pot_bpjs) AS pot_bpjs,
                CEIL(pot_psiun) AS pot_psiun,
                FLOOR(
                    (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4), 0))
                        -
                   (pot_makan + pot_jkkjkm + pot_jht + (IF(report_pot_upah >= 1, (gp + t_jab + var_cost + fix_cost) / IF(grup_hk = 1, 21, 25), 0)) + pot_bpjs + pot_psiun)
                 ) AS gaji_bersih,
                 FLOOR(
                     (
                         (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4), 0))
                            -
                       (pot_makan + pot_jkkjkm + pot_jht + (IF(report_pot_upah >= 1, (gp + t_jab + var_cost + fix_cost) / IF(grup_hk = 1, 21, 25), 0)) + pot_bpjs + pot_psiun)
                     ) % 100
                 ) AS bulat,
                 FLOOR(
                     (
                        (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4), 0))
                            -
                       (pot_makan + pot_jkkjkm + pot_jht + (IF(report_pot_upah >= 1, (gp + t_jab + var_cost + fix_cost) / IF(grup_hk = 1, 21, 25), 0)) + pot_bpjs + pot_psiun)
                     )
                         -
                    (
                        (
                             (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4), 0))
                                -
                           (pot_makan + pot_jkkjkm + pot_jht + (IF(report_pot_upah >= 1, (gp + t_jab + var_cost + fix_cost) / IF(grup_hk = 1, 21, 25), 0)) + pot_bpjs + pot_psiun)
                         ) % 100
                     )
                 ) AS gaji_terima
            FROM qs_payroll
            WHERE id_heyxxmd <> 2
        ');

        //Pembulatan ROUND

        // $qr_gp = $db
        //     ->raw()
        //     ->bind(':tanggal_awal', $tanggal_awal)
        //     ->bind(':tanggal_akhir', $tanggal_akhir)
        //     ->exec('
        //     INSERT INTO hpyemtd (
        //         id_hpyxxth, 
        //         id_hemxxmh, 
        //         gp,
        //         t_jab,
        //         var_cost,
        //         fix_cost,
        //         premi_abs,
        //         jkk,
        //         jkm,
        //         trm_jkkjkm,
                
        //         lembur15,
        //         lembur2,
        //         lembur3,
        //         lembur4,

        //         lembur15_final,
        //         lembur2_final,
        //         lembur3_final,
        //         lembur4_final,

        //         rp_lembur15,
        //         rp_lembur2,
        //         rp_lembur3,
        //         rp_lembur4,

        //         jam_lembur,
        //         lemburbersih,
        //         pot_makan,
        //         pot_jkkjkm,
        //         pot_jht,
        //         pot_upah,
        //         pot_bpjs,
        //         pot_psiun,
        //         gaji_bersih,
        //         bulat,
        //         gaji_terima
        //     )
        //     WITH qs_payroll AS (
        //         SELECT DISTINCT
        //             a.id_hemxxmh,
        //             report_pot_upah,
        //             (round(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173),0)) AS nominal_lembur_jam,
        //             ROUND(
        //                   (round(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173),0)) *
        //                     sum_lembur15_final
        //                 ,0) AS rp_lembur15,
        //             ROUND(
        //                   (round(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173),0)) *
        //                     sum_lembur2_final
        //                 ,0) AS rp_lembur2,
        //             ROUND(
        //                   (round(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173),0)) *
        //                     sum_lembur3_final
        //                 ,0) AS rp_lembur3,
        //             ROUND(
        //                   (round(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + ifnull(nominal_t_jab,0)) / 173),0)) *
        //                     sum_lembur4_final
        //                 ,0) AS rp_lembur4,
        //             c.id_hesxxmh,
        //             c.id_heyxxmd,
        //             c.grup_hk,
        //             IFNULL(hari_kerja, 0) AS hari_kerja,
                    
        //             -- gaji pokok
        //             IFNULL( 
        //                  if(c.tanggal_masuk BETWEEN :tanggal_awal AND :tanggal_akhir, 
        //                      hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_gp,
        //                  nominal_gp),
        //              0) AS gp,
                     
        //              -- tunjangan jabatan
        //             IFNULL( 
        //                  if(c.tanggal_masuk BETWEEN :tanggal_awal AND :tanggal_akhir, 
        //                      hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_t_jab,
        //                  nominal_t_jab),
        //              0) AS t_jab,
                     
        //              -- var_cost
        //              IFNULL(nominal_var_cost, 0) AS var_cost,
                     
        //              -- fix cost atau masa kerja
        //             IFNULL( 
        //                  if(c.tanggal_masuk BETWEEN :tanggal_awal AND :tanggal_akhir, 
        //                      hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_mk,
        //                  nominal_mk),
        //              0) AS fix_cost,
                     
        //              -- premi absen dengan validasi jika ada izin/absen yang memotong premi maka premi absen == 0 atau hangus
        //              -- revisi 2 Oct, premi absen hanya untuk organik, os tidak ada
        //             if(c.id_heyxxmh = 1, IFNULL(if(report_pot_premi >= 1, 0, premiabs), 0), 0) AS premi_abs,
                    
        //             -- hitung jkk
        //             if(id_heyxxmd = 3,IFNULL((persen_jkk / 100) * gaji_bpjs,0),0) AS jkk,
                    
        //             -- hitung jkm
        //             if(id_heyxxmd = 3,IFNULL((persen_jkm / 100) * gaji_bpjs,0),0) AS jkm,
                    
        //             -- trm_jkkjkm == jkk + jkm
        //             if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0) AS trm_jkkjkm,
                    
        //             -- mulai lembur
        //             sum_lembur15 AS lembur15,
        //             sum_lembur2 AS lembur2,
        //             sum_lembur3 AS lembur3,
        //             sum_lembur4 AS lembur4,
                    
        //             sum_lembur15_final AS lembur15_final,
        //             sum_lembur2_final AS lembur2_final,
        //             sum_lembur3_final AS lembur3_final,
        //             sum_lembur4_final AS lembur4_final,
                    
                    
        //             -- hitung pot makan
        //             IFNULL(pot_makan * pot_uang_makan, 0) AS pot_makan,
                    
        //             -- pot_jkkjkm == jkk + jkm (sama dengan pot_jkkjkm)
        //             if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0) AS pot_jkkjkm,
                    
        //             -- hitung pot_jht
        //             if(id_heyxxmd = 3,IFNULL((persen_jht_karyawan / 100) * gaji_bpjs, 0),0) AS pot_jht,
                    
        //             -- hitung pot_bpjs
        //             if(id_heyxxmd = 3, IFNULL((persen_karyawan / 100) * gaji_bpjs, 0),0) AS pot_bpjs,
                    
        //             -- hitung pot_psiun
                    
        //             -- revisi khusus yang sub tipe == karyawan
        //             if(id_heyxxmd = 3,IFNULL((persen_jp_karyawan / 100) * gaji_bpjs, 0),0) AS pot_psiun
                    
        //         FROM htsprrd AS a
        //         LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                
        //             -- Masa Kerja
        //             LEFT JOIN (
        //                 SELECT
        //                     job.id_hemxxmh,
        //                     nominal AS nominal_mk,
        //                     job.id_hevgrmh,
        //                     masa_kerja_year
        //                 FROM (
        //                     SELECT
        //                         a.id_hemxxmh,
        //                         hev.id_hevgrmh AS id_hevgrmh,
        //                         IF(
        //                             a.tanggal_keluar IS NULL,
        //                             TIMESTAMPDIFF(YEAR, a.tanggal_masuk, CURDATE()),
        //                             TIMESTAMPDIFF(YEAR, a.tanggal_masuk, a.tanggal_keluar)
        //                         ) AS masa_kerja_year
        //                     FROM hemjbmh AS a
        //                     LEFT JOIN hevxxmh AS hev ON hev.id = a.id_hevxxmh
        //                     WHERE is_active = 1
        //                     GROUP BY a.id_hemxxmh
        //                 ) AS job
        //                 LEFT JOIN (
        //                     SELECT
        //                         id_hevgrmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         tahun_min,
        //                         tahun_max,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hevgrmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hevgrmh_mk
        //                     WHERE
        //                         id_hpcxxmh = 31
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
        //                 WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
        //                 GROUP BY job.id_hemxxmh
        //             ) AS mk ON mk.id_hemxxmh = a.id_hemxxmh
                        
        //             -- gaji pokok
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hemxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS nominal_gp
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hemxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hemxxmh
        //                     WHERE
        //                         htpr_hemxxmh.id_hpcxxmh = 1
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh
                    
        //             -- var_cost htpr_hemxxmh.id_hpcxxmh = 102
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hemxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS nominal_var_cost
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hemxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hemxxmh
        //                     WHERE
        //                         htpr_hemxxmh.id_hpcxxmh = 102
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = a.id_hemxxmh
                    
                    
        //             -- Ambil lembur mati dari htpr_hesxxmh untuk pelatihan
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hesxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS nominal_lembur_mati
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hesxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hesxxmh
        //                     WHERE
        //                         htpr_hesxxmh.id_hpcxxmh = 36
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) lembur_mati ON lembur_mati.id_hesxxmh = c.id_hesxxmh
            
        //             -- potongan makan htpr_hesxxmh
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hesxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS pot_uang_makan
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hesxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hesxxmh
        //                     WHERE
        //                         htpr_hesxxmh.id_hpcxxmh = 34
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh
                    
        //             -- t jabatan
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hevxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS nominal_t_jab
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hevxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hevxxmh
        //                     WHERE
        //                         htpr_hevxxmh.id_hpcxxmh = 32
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh
                    
        //             -- premi absen
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hevxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS premiabs 
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hevxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hevxxmh
        //                     WHERE
        //                         htpr_hevxxmh.id_hpcxxmh = 33
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh
                    
        //             -- sum durasi lembur dan makan
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hemxxmh,
        //                     SUM(durasi_lembur_final) AS lembur_sum,
        //                     SUM(is_makan) AS pot_makan
        //                 FROM htsprrd
        //                 WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
        //                 GROUP BY id_hemxxmh
        //             ) lembur_sum_table ON lembur_sum_table.id_hemxxmh = a.id_hemxxmh
                    
        //             -- cari lembur 1.5, lembur 2, lembur 3
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hemxxmh,
        //                     sum_lembur15,
        //                     sum_lembur2,
        //                     sum_lembur3,
        //                     sum_lembur4,
        //                     sum_lembur15_final,
        //                     sum_lembur2_final,
        //                     sum_lembur3_final,
        //                     sum_lembur4_final
        //                 FROM (
        //                     SELECT
        //                         prr.id_hemxxmh,
        //                         SUM(IFNULL(prr.lembur15, 0)) AS sum_lembur15,		            
        //                         SUM(IFNULL(prr.lembur2, 0)) AS sum_lembur2,
        //                         SUM(IFNULL(prr.lembur3, 0)) AS sum_lembur3,
        //                         SUM(IFNULL(prr.lembur4, 0)) AS sum_lembur4,
                                
        //                         -- setelah dikali
        //                         SUM(IFNULL(prr.lembur15_final, 0)) AS sum_lembur15_final,		            
        //                         SUM(IFNULL(prr.lembur2_final, 0)) AS sum_lembur2_final,
        //                         SUM(IFNULL(prr.lembur3_final, 0)) AS sum_lembur3_final,
        //                         SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final
        //                     FROM htsprrd AS prr
        //                     LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = prr.id_hemxxmh
        //                     WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
        //                     GROUP BY id_hemxxmh
        //                 ) lembur_sum_table
        //             ) lembur_calc ON lembur_calc.id_hemxxmh = a.id_hemxxmh
                    
        //             -- validasi cari izin/absen yang memotong premi dari report presensi
        //             LEFT JOIN (
        //                 SELECT
        //                   id_hemxxmh,
        //                     report_pot_premi
        //                 FROM (
        //                     SELECT
        //                         id_hemxxmh,
        //                         COUNT(id) AS report_pot_premi
        //                     FROM htsprrd
        //                     WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
        //                           AND is_pot_premi = 1
        //                     GROUP BY id_hemxxmh
        //                 ) c_report_pot_premi
        //             ) presensi_pot_premi ON presensi_pot_premi.id_hemxxmh = a.id_hemxxmh
                    
        //             -- validasi cari izin/absen yang memotong upah dari report presensi
        //             LEFT JOIN (
        //                 SELECT
        //                   id_hemxxmh,
        //                     report_pot_upah
        //                 FROM (
        //                     SELECT
        //                         id_hemxxmh,
        //                         COUNT(id) AS report_pot_upah
        //                     FROM htsprrd
        //                     WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
        //                           AND is_pot_upah = 1
        //                     GROUP BY id_hemxxmh
        //                 ) c_report_pot_upah
        //             ) presensi_pot_upah ON presensi_pot_upah.id_hemxxmh = a.id_hemxxmh
                    
        //             -- hari kerja karyawan baru
        //             LEFT JOIN (
        //                 SELECT
        //                     (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
        //                     report.id_hemxxmh
        //                 FROM (
        //                     SELECT 
        //                         COUNT(a.id) AS hk_report,
        //                         a.id_hemxxmh
        //                     FROM htsprrd AS a
        //                     LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
        //                     WHERE a.tanggal BETWEEN job.tanggal_masuk AND :tanggal_akhir
        //                         AND a.st_clock_in <> "OFF"
        //                     GROUP BY a.id_hemxxmh
        //                 ) AS report
        //                 LEFT JOIN (
        //                     SELECT
        //                         id_hemxxmh,
        //                         COUNT(id) AS jadwal
        //                     FROM htssctd
        //                     WHERE tanggal BETWEEN :tanggal_akhir AND LAST_DAY(:tanggal_akhir)
        //                         AND id_htsxxmh <> 1
        //                     GROUP BY id_hemxxmh
        //                 ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
        //             ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                    
        //             -- select data dari hibtkmh untuk hitung bpjs
        //             LEFT JOIN (
        //                 SELECT
        //                   persen_jkk,
        //                   persen_jkm,
        //                   persen_jht_karyawan,
        //                   persen_jp_karyawan,
        //                   is_active
        //                 FROM (
        //                     SELECT
        //                         persen_jkk,
        //                         persen_jkm,
        //                         persen_jht_karyawan,
        //                         persen_jp_karyawan,
        //                         is_active
        //                     FROM hibtkmh
        //                 ) sel_bpjs
        //             ) bpjs ON bpjs.is_active = 1
                    
        //             -- select gaji bpjs
        //             LEFT JOIN (
        //                 SELECT
        //                     id_hemxxmh,
        //                     tanggal_efektif,
        //                     IFNULL(nominal, 0) AS gaji_bpjs
        //                 FROM (
        //                     SELECT
        //                         id,
        //                         id_hemxxmh,
        //                         tanggal_efektif,
        //                         nominal,
        //                         ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
        //                     FROM htpr_hemxxmh
        //                     WHERE
        //                         htpr_hemxxmh.id_hpcxxmh = 2
        //                         AND tanggal_efektif < :tanggal_awal
        //                 ) AS subquery
        //                 WHERE row_num = 1
        //             ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = a.id_hemxxmh
                    
        //             -- select data dari hibksmh untuk hitung bpjs kesehatan
        //             LEFT JOIN (
        //                 SELECT
        //                   persen_karyawan,
        //                   is_active
        //                 FROM (
        //                     SELECT
        //                         persen_karyawan,
        //                         is_active
        //                     FROM hibksmh
        //                 ) sel_bpjs
        //             ) bpjs_kesehatan ON bpjs_kesehatan.is_active = 1
                
        //         WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
        //     )
        //     SELECT
        //         ' . $id_hpyxxth . ',
        //         qs_payroll.id_hemxxmh,
        //         gp,
        //         t_jab,
        //         var_cost,
        //         fix_cost,
        //         premi_abs,
        //         ROUND(jkk, 0 ) AS jkk,
        //         ROUND(jkm, 0 ) AS jkm,
        //         ROUND(trm_jkkjkm, 0 ) AS trm_jkkjkm,
        //         lembur15,
        //         lembur2,
        //         lembur3,
        //         lembur4,
                
        //         lembur15_final,
        //         lembur2_final,
        //         lembur3_final,
        //         lembur4_final,
                
        //         rp_lembur15,
        //         rp_lembur2,
        //         rp_lembur3,
        //         rp_lembur4,
            
        //         (lembur15 + lembur2 + lembur3 + lembur4) AS jam_lembur,
        //         ROUND(IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4),0),0) AS lemburbersih,
        //         ROUND(pot_makan, 0) AS  pot_makan,
        //         ROUND(pot_jkkjkm, 0) AS pot_jkkjkm,
        //         ROUND(pot_jht, 0) AS pot_jht,
        //         if(report_pot_upah >= 1, ROUND((gp + t_jab + var_cost + fix_cost) / if(grup_hk = 1, 21, 25), 0),0) AS pot_upah,
        //         ROUND(pot_bpjs, 0) AS pot_bpjs,
        //         ROUND(pot_psiun, 0) AS pot_psiun,
        //         -- hitung gaji bersih
        //         ROUND((gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + (IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4),0))) -- ini hijau
        //           - 
        //          (pot_makan + pot_jkkjkm + pot_jht + if(report_pot_upah >= 1, ROUND((gp + t_jab + var_cost + fix_cost) / if(grup_hk = 1, 21, 25), 0),0) + pot_bpjs + pot_psiun), 0) -- ini merah
        //          AS gaji_bersih,
                 
        //          -- pembulatan per 100 dari gaji bersih
        //          ROUND((
        //              (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + (IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4),0))) -- ini hijau
        //               - 
        //              (pot_makan + pot_jkkjkm + pot_jht + if(report_pot_upah >= 1, ROUND((gp + t_jab + var_cost + fix_cost) / if(grup_hk = 1, 21, 25), 0),0) + pot_bpjs + pot_psiun) -- ini merah
        //          ) % 100, 0) AS bulat,
                 
        //          -- gaji_bersih - hasil pembulatan
        //          ROUND((
        //              (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + (IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4),0))) -- ini hijau
        //               - 
        //              (pot_makan + pot_jkkjkm + pot_jht + if(report_pot_upah >= 1, ROUND((gp + t_jab + var_cost + fix_cost) / if(grup_hk = 1, 21, 25), 0),0) + pot_bpjs + pot_psiun) -- ini merah
        //          )
        //          -
        //         (
        //              (
        //                  (gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + (IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4),0))) -- ini hijau
        //                   - 
        //                  (pot_makan + pot_jkkjkm + pot_jht + if(report_pot_upah >= 1, ROUND((gp + t_jab + var_cost + fix_cost) / if(grup_hk = 1, 21, 25), 0),0) + pot_bpjs + pot_psiun) -- ini merah
        //              ) % 100
        //          ),0) AS gaji_terima
        //     FROM qs_payroll
        //     WHERE id_heyxxmd <> 2
        // ');
        // END GAJI POKOK
        
        $qu_hpyxxth = $db
            ->query('update', 'hpyxxth')
            ->set('generated_on',$timestamp)
            ->where('id',$id_hpyxxth)
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