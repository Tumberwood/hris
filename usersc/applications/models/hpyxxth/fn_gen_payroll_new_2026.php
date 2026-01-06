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
        
        //DELETE DETAIL PAYROLL LAMA
        $qd_detail_payroll = $db
            ->raw()
            ->bind(':id_hpyxxth', $id_hpyxxth)
            ->exec('DELETE FROM hpyemtd
                    WHERE id_hpyxxth = :id_hpyxxth
            '
        );

        //Pembulatan FLOOR
        // INSERT PAYROLL DETAIL
        $qr_gp = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('INSERT INTO hpyemtd (
                        id_hpyxxth, 
                        id_hemxxmh, 
                        kode, 
                        nama, 
                        ktp,
                        npwp,

                        gp,
                        t_jab,
                        var_cost,
                        fix_cost,
                        premi_abs,
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
                        jam_lembur_final,
                        lemburbersih,
                        
                        pot_makan,
                        pot_jkkjkm,
                        pot_jht,
                        pot_upah,
                        pot_jam,
                        pot_bpjs,
                        pot_psiun,
                        
                        gaji_bersih,
                        bulat,
                        gaji_terima
                    )
                    WITH pegawai AS (
                        SELECT
                            b.id AS id_hemxxmh,
                            b.kode nrp,
                            ktp_no ktp,
                            npwp_no npwp,
                            b.nama,
                            c.id_hetxxmh,
                            c.id_hesxxmh,
                            c.id_hevxxmh,
                            c.id_heyxxmh,
                            c.id_heyxxmd
                        FROM hemxxmh b
                        JOIN hemjbmh c  ON c.id_hemxxmh = b.id AND c.id_heyxxmd <> 2
                        LEFT JOIN hemdcmh d on d.id_hemxxmh = b.id
                    ),

                    presensi AS (
                        SELECT
                            a.id_hemxxmh,

                            SUM(a.lembur15) lembur15,
                            SUM(a.lembur15_final) lembur15_final,
                            SUM(a.rp_lembur15) rp_lembur15,

                            SUM(a.lembur2) lembur2,
                            SUM(a.lembur2_final) lembur2_final,
                            SUM(a.rp_lembur2) rp_lembur2,

                            SUM(a.lembur3) lembur3,
                            SUM(a.lembur3_final) lembur3_final,
                            SUM(a.rp_lembur3) rp_lembur3,

                            SUM(COALESCE(a.lembur15,0))
                            + SUM(COALESCE(a.lembur2,0))
                            + SUM(COALESCE(a.lembur3,0)) AS total_lembur_jam,

                            SUM(COALESCE(a.lembur15_final,0))
                            + SUM(COALESCE(a.lembur2_final,0))
                            + SUM(COALESCE(a.lembur3_final,0)) AS total_lembur_jam_final,

                            SUM(COALESCE(a.rp_lembur15,0))
                            + SUM(COALESCE(a.rp_lembur2,0))
                            + SUM(COALESCE(a.rp_lembur3,0)) AS total_rp_lembur,
                            SUM(is_makan) sum_pot_makan

                        FROM htsprrd a
                        JOIN pegawai p ON p.id_hemxxmh = a.id_hemxxmh
                        WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY a.id_hemxxmh
                    ),

                    gaji_pokok AS (
                        SELECT
                            p.id_hemxxmh,
                            IF(p.id_hesxxmh = 3, gp_pelatihan, nominal_gp) AS gp
                        FROM pegawai p

                        LEFT JOIN (
                            SELECT id_hemxxmh, nominal AS nominal_gp
                            FROM (
                                SELECT *,
                                    ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) rn
                                FROM htpr_hemxxmh
                                WHERE id_hpcxxmh = 1
                                AND is_active = 1
                                AND tanggal_efektif <= :tanggal_akhir
                            ) x WHERE rn = 1
                        ) gp1 ON gp1.id_hemxxmh = p.id_hemxxmh

                        LEFT JOIN (
                            SELECT id_hesxxmh, nominal AS gp_pelatihan
                            FROM (
                                SELECT *,
                                    ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) rn
                                FROM htpr_hesxxmh
                                WHERE id_hpcxxmh = 1
                                AND is_active = 1
                                AND tanggal_efektif <= :tanggal_akhir
                            ) x WHERE rn = 1
                        ) gp2 ON gp2.id_hesxxmh = p.id_hesxxmh
                    ),
                    t_jabatan AS (
                        SELECT
                            p.id_hemxxmh,
                            COALESCE(nominal_jabatan, nominal_t_jab, 0) AS t_jab
                        FROM pegawai p
                        -- t jabatan untuk yang Tetap
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
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) t_jabatan ON t_jabatan.id_hevxxmh = p.id_hevxxmh

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
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) tbl_jabatan ON tbl_jabatan.id_hemxxmh = p.id_hemxxmh
                    ),
                    var_cost AS (
                        SELECT
                            p.id_hemxxmh,
                            IFNULL(nominal_var_cost,0) as var_cost
                        FROM pegawai p

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
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = p.id_hemxxmh
                    ),
                    fix_cost AS (
                        SELECT
                            p.id_hemxxmh,
                            IF(id_heyxxmh = 1, IFNULL(nominal_mk,0) , 0) as fix_cost
                        FROM pegawai p

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
                                        TIMESTAMPDIFF(MONTH, a.tanggal_masuk, :tanggal_akhir) / 12,
                                        TIMESTAMPDIFF(MONTH, a.tanggal_masuk, a.tanggal_keluar) / 12
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
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                            WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
                            GROUP BY job.id_hemxxmh
                        ) AS mk ON mk.id_hemxxmh = p.id_hemxxmh
                    ),
                    premi_abs AS (
                        SELECT
                            p.id_hemxxmh,
                            report_pot_premi,
                            CASE
                                WHEN p.id_hemxxmh = 67 THEN 0
                                WHEN p.id_heyxxmh = 1 
                                    AND COALESCE(pr.report_pot_premi, 0) >= 1
                                    THEN 0
                                WHEN p.id_heyxxmh = 1
                                    THEN IFNULL(pa.premiabs, 0)
                                ELSE 0
                            END AS premi_abs
                        FROM pegawai p

                        -- ambil hasil potongan premi dari presensi
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
                        ) pr on pr.id_hemxxmh = p.id_hemxxmh

                        -- nominal premi absen per jabatan
                        LEFT JOIN (
                            SELECT
                                id_hevxxmh,
                                IFNULL(nominal, 0) AS premiabs
                            FROM (
                                SELECT
                                    id_hevxxmh,
                                    nominal,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY id_hevxxmh 
                                        ORDER BY tanggal_efektif DESC
                                    ) AS row_num
                                FROM htpr_hevxxmh
                                WHERE id_hpcxxmh = 33
                                AND tanggal_efektif <= :tanggal_akhir
                                AND is_active = 1
                            ) x
                            WHERE row_num = 1
                        ) pa ON pa.id_hevxxmh = p.id_hevxxmh
                    ),
                    bpjs AS (
                        SELECT
                            p.id_hemxxmh,
                            -- hitung jkk
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jkk / 100) * gaji_bpjs, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS jkk,

                            -- hitung jkm
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jkm / 100) * gaji_bpjs, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS jkm,

                            -- trm_jkkjkm == jkk + jkm
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL(
                                                ((persen_jkk / 100) * gaji_bpjs) +
                                                ((persen_jkm / 100) * gaji_bpjs),
                                                0
                                            ),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS trm_jkkjkm,

                            -- pot_jkkjkm == jkk + jkm
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL(
                                                ((persen_jkk / 100) * gaji_bpjs) +
                                                ((persen_jkm / 100) * gaji_bpjs),
                                                0
                                            ),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_jkkjkm,

                            -- hitung pot_jht
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jht_karyawan / 100) * gaji_bpjs, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_jht,

                            -- hitung pot_bpjs
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_kes > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_karyawan / 100) * gaji_bpjs, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_bpjs,

                            -- hitung pot_psiun (karyawan)
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jp_karyawan / 100) * gaji_bpjs, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_psiun

                        FROM pegawai p
                        
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
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) tbl_gaji_bpjs ON tbl_gaji_bpjs.id_hemxxmh = p.id_hemxxmh
                        
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

                        -- Cari bpjs_kes_exclude
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(c_bpjs_kes, 0) AS skip_c_bpjs_kes
                            FROM (
                                SELECT
                                    COUNT(bpjs_kes.id) AS c_bpjs_kes,
                                    c.id id_hemxxmh
                                FROM bpjs_kes_exclude AS bpjs_kes
                                INNER JOIN hesxxtd b ON b.id_hemxxmh = bpjs_kes.id_hemxxmh
                                INNER JOIN hemxxmh c ON c.kode = b.nik_baru
                                WHERE bpjs_kes.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) bpjs_kes_exclude ON bpjs_kes_exclude.id_hemxxmh = p.id_hemxxmh
                        
                        -- Cari bpjs_tk_exclude
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                            FROM (
                                SELECT
                                    COUNT(bpjs_tk.id) AS c_bpjs_tk,
                                    c.id id_hemxxmh
                                FROM bpjs_tk_exclude AS bpjs_tk
                                INNER JOIN hesxxtd b ON b.id_hemxxmh = bpjs_tk.id_hemxxmh
                                INNER JOIN hemxxmh c ON c.kode = b.nik_baru
                                WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = p.id_hemxxmh
                    ),
                    pot_makan AS (
                        SELECT
                            p.id_hemxxmh,
                        IFNULL(sum_pot_makan * pot_uang_makan, 0) AS pot_makan
                        FROM pegawai p

                        LEFT JOIN presensi pr on pr.id_hemxxmh = p.id_hemxxmh

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
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = p.id_hesxxmh
                    ),
                    pot_upah AS (
                        SELECT
                            pr.id_hemxxmh,
                            IF(
                                prr.id_heyxxmd = 1 AND prr.id_hesxxmh = 3,
                                1 * IF(pr.grup_hk = 1, 83509, 70148),
                                1 / IF(pr.grup_hk = 1, 21, 25)
                                *
                                (
                                    gp + t_jab + var_cost + fix_cost
                                )
                            ) nominal_pot_upah,
                            SUM(
                                IF(
                                    prr.id_heyxxmd = 1 AND prr.id_hesxxmh = 3,
                                    1 * IF(pr.grup_hk = 1, 83509, 70148),
                                    1 / IF(pr.grup_hk = 1, 21, 25)
                                    *
                                    (
                                        gp + t_jab + var_cost + fix_cost
                                    )
                                )
                            ) AS pot_upah
                        FROM htsprrd pr
                        LEFT JOIN hemjbmh prr on prr.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN gaji_pokok gp ON gp.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN t_jabatan tj ON tj.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN var_cost vc ON vc.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN fix_cost fc ON fc.id_hemxxmh = pr.id_hemxxmh
                        WHERE pr.is_pot_upah = 1 AND pr.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY pr.id_hemxxmh
                    ),
                    pot_jam AS (
                        SELECT
                            pr.id_hemxxmh,
                            SUM(
                                IF(
                                    prr.id_heyxxmd = 1 AND prr.id_hesxxmh = 3,
                                    pot_hk * IF(pr.grup_hk = 1, 83509 * 21, 70148 * 25) / 173,
                                    FLOOR(
                                        pot_hk
                                        *
                                        (
                                            gp + t_jab + var_cost + fix_cost
                                        ) / 173
                                    )
                                )
                            ) AS pot_jam
                        FROM htsprrd pr
                        LEFT JOIN hemjbmh prr on prr.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN gaji_pokok gp ON gp.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN t_jabatan tj ON tj.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN var_cost vc ON vc.id_hemxxmh = pr.id_hemxxmh
                        LEFT JOIN fix_cost fc ON fc.id_hemxxmh = pr.id_hemxxmh
                        WHERE pr.pot_hk > 0 AND pr.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                        GROUP BY pr.id_hemxxmh
                    ),
                    payroll AS (
                        SELECT
                            ' . $id_hpyxxth . ',
                            p.id_hemxxmh,
                            nrp,
                            nama,
                            ktp,
                            npwp,

                            gp.gp,
                            t_jab,
                            var_cost,
                            fix_cost,
                            premi_abs,
                            trm_jkkjkm,
                            
                            lembur15,
                            lembur2,
                            lembur3,

                            lembur15_final,
                            lembur2_final,
                            lembur3_final,

                            rp_lembur15,
                            rp_lembur2,
                            rp_lembur3,
                            
                            total_lembur_jam,
                            total_lembur_jam_final,
                            total_rp_lembur,

                            -- POTONGAN
                            pot_makan,
                            pot_jkkjkm,
                            pot_jht,
                            pot_upah,
                            pot_jam,
                            pot_bpjs,
                            pot_psiun,

                            -- GAJI BERSIH
                            FLOOR(
                                IFNULL(gp,0)
                                + IFNULL(t_jab,0)
                                + IFNULL(var_cost,0)
                                + IFNULL(fix_cost,0)
                                + IFNULL(premi_abs,0)
                                + IFNULL(trm_jkkjkm,0)
                                + IFNULL(total_rp_lembur,0)
                                -
                                (
                                    IFNULL(pot_makan,0)
                                    + IFNULL(pot_jkkjkm,0)
                                    + IFNULL(pot_jht,0)
                                    + IFNULL(pot_bpjs,0)
                                    + IFNULL(pot_psiun,0)
                                    + IFNULL(pu.pot_upah,0)
                                    + IFNULL(pot_jam,0)
                                )
                            ) AS gaji_bersih
                        FROM presensi p
                        LEFT JOIN pegawai peg on peg.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN gaji_pokok gp ON gp.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN t_jabatan tjab ON tjab.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN var_cost ON var_cost.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN fix_cost ON fix_cost.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN premi_abs ON premi_abs.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_makan ON pot_makan.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN bpjs ON bpjs.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_upah pu ON pu.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_jam ON pot_jam.id_hemxxmh = p.id_hemxxmh
                    )
                    SELECT
                        payroll.*,
                        FLOOR( gaji_bersih % 100) AS bulat,
                        FLOOR( gaji_bersih - (gaji_bersih % 100) ) AS gaji_terima
                    FROM payroll
        ');

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