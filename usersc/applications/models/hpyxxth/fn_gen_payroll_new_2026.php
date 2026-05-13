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
        $qs_hpyemtd = $db
            ->raw()
            ->bind(':id_hpyxxth', $id_hpyxxth)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('WITH pegawai AS (
                        SELECT
                            b.id AS id_hemxxmh,
                            id_gtxpkmh,
                            kategori_kelas,
                            b.kode nrp,
                            ktp_no ktp,
                            departemen.nama departemen,
                            jabatan.nama jabatan,
                            tipe.nama tipe,
                            sub_tipe.nama sub_tipe,
                            status.nama status_peg,
                            npwp_no npwp,
                            ptkp.kode ptkp,
                            no_rekening,
                            b.nama,
                            c.id_hetxxmh,
                            c.id_hesxxmh,
                            c.id_heyxxmh,
                            c.id_heyxxmd
                        FROM hemxxmh b
                        JOIN hemjbmh c  ON c.id_hemxxmh = b.id AND c.id_heyxxmd <> 2
                        LEFT JOIN hemdcmh d on d.id_hemxxmh = b.id
                        LEFT JOIN hodxxmh departemen on departemen.id = c.id_hodxxmh
                        LEFT JOIN hetxxmh jabatan on jabatan.id = c.id_hetxxmh
                        LEFT JOIN heyxxmh tipe on tipe.id = c.id_heyxxmh
                        LEFT JOIN heyxxmd sub_tipe on sub_tipe.id = c.id_heyxxmd
                        LEFT JOIN hesxxmh status on status.id = c.id_hesxxmh
                        LEFT JOIN gtxpkmh ptkp on ptkp.id = d.id_gtxpkmh
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
                            COALESCE(nominal_gp, 0) AS gp
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
                    ),
                    t_jabatan AS (
                        SELECT
                            p.id_hemxxmh,
                            COALESCE(nominal_t_jab, 0) AS t_jab
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                tanggal_efektif,
                                IFNULL(nominal, 0) AS nominal_t_jab
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
                        ) t_jabatan ON t_jabatan.id_hemxxmh = p.id_hemxxmh
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
                    tj_khusus AS (
                        SELECT
                            p.id_hemxxmh,
                            IFNULL(nominal_tj_khusus,0) as tj_khusus
                        FROM pegawai p

                        -- tj_khusus htpr_hemxxmh.id_hpcxxmh = 102
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                tanggal_efektif,
                                IFNULL(nominal, 0) AS nominal_tj_khusus
                            FROM (
                                SELECT
                                    id,
                                    id_hemxxmh,
                                    tanggal_efektif,
                                    nominal,
                                    ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                FROM htpr_hemxxmh
                                WHERE
                                    htpr_hemxxmh.id_hpcxxmh = 133
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) tbl_tj_khusus ON tbl_tj_khusus.id_hemxxmh = p.id_hemxxmh
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
                                    id_hevgrmh,
                                    id_heyxxmd,
                                    id_hesxxmh,
                                    IF(
                                        a.tanggal_keluar IS NULL,
                                        TIMESTAMPDIFF(MONTH, a.tanggal_masuk, :tanggal_akhir) / 12,
                                        TIMESTAMPDIFF(MONTH, a.tanggal_masuk, a.tanggal_keluar) / 12
                                    ) AS masa_kerja_year
                                FROM hemjbmh AS a
                                GROUP BY a.id_hemxxmh
                            ) AS job
                            LEFT JOIN (
                                SELECT
                                    id_hevgrmh,
                                    id_heyxxmd,
                                    id_hesxxmh,
                                    tanggal_efektif,
                                    nominal,
                                    tahun_min,
                                    tahun_max,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY id_hevgrmh, id_heyxxmd, id_hesxxmh
                                        ORDER BY tanggal_efektif DESC
                                    ) AS row_num
                                FROM htpr_hevgrmh_mk
                                WHERE
                                    id_hpcxxmh = 31
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                                AND masakerja.id_heyxxmd = job.id_heyxxmd
                                AND masakerja.id_hesxxmh = job.id_hesxxmh
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
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS premiabs
                            FROM (
                                SELECT
                                    id_hemxxmh,
                                    nominal,
                                    ROW_NUMBER() OVER (
                                        PARTITION BY id_hemxxmh 
                                        ORDER BY tanggal_efektif DESC
                                    ) AS row_num
                                FROM htpr_hemxxmh
                                WHERE id_hpcxxmh = 33
                                AND tanggal_efektif <= :tanggal_akhir
                                AND is_active = 1
                            ) x
                            WHERE row_num = 1
                        ) pa ON pa.id_hemxxmh = p.id_hemxxmh
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
                                            IFNULL((persen_jkk / 100) * gaji_bpjs_tk, 0),
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
                                            IFNULL((persen_jkm / 100) * gaji_bpjs_tk, 0),
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
                                                ((persen_jkk / 100) * gaji_bpjs_tk) +
                                                ((persen_jkm / 100) * gaji_bpjs_tk),
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
                                                ((persen_jkk / 100) * gaji_bpjs_tk) +
                                                ((persen_jkm / 100) * gaji_bpjs_tk),
                                                0
                                            ),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_jkkjkm,

                            -- hitung bpjs_kes_karyawan
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_kes > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_karyawan / 100) * IFNULL(gaji_bpjs_kes, gaji_bpjs_tk), 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS bpjs_kes_karyawan,

                            -- hitung bpjs_kes_perusahaan
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_kes > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_perusahaan / 100) * IFNULL(gaji_bpjs_kes, gaji_bpjs_tk), 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS bpjs_kes_perusahaan,

                            -- hitung jht_perusahaan
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jht_perusahaan / 100) * gaji_bpjs_tk, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS jht_perusahaan,

                            -- hitung jp_perusahaan
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jp_perusahaan / 100) * gaji_bpjs_tk, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS jp_perusahaan,

                            -- hitung pot_jht_karyawan
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jht_karyawan / 100) * gaji_bpjs_tk, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_jht_karyawan,

                            -- hitung pot_jp_karyawan
                            ROUND(
                                IF(
                                    p.id_hesxxmh IN (1,2,5),
                                    IF(
                                        skip_c_bpjs_tk > 0, 
                                        0, 
                                        IF(
                                            id_heyxxmd = 3,
                                            IFNULL((persen_jp_karyawan / 100) * gaji_bpjs_tk, 0),
                                            0
                                        )
                                    ),
                                    0
                                ),
                            0) AS pot_jp_karyawan

                        FROM pegawai p
                        
                        -- select data dari hibtkmh untuk hitung bpjs
                        LEFT JOIN (
                            SELECT
                            persen_jkk,
                            persen_jkm,
                            persen_jht_perusahaan,
                            persen_jp_perusahaan,

                            persen_jht_karyawan,
                            persen_jp_karyawan,
                            is_active
                            FROM (
                                SELECT
                                    persen_jkk,
                                    persen_jkm,
                                    persen_jht_perusahaan,
                                    persen_jp_perusahaan,

                                    persen_jht_karyawan,
                                    persen_jp_karyawan,
                                    is_active
                                FROM hibtkmh
                            ) sel_bpjs
                        ) bpjs ON bpjs.is_active = 1
                        
                        -- select gaji bpjs tk
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                    tanggal_efektif,
                                    IFNULL(nominal, 0) AS gaji_bpjs_tk
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
                        ) tbl_gaji_bpjs_tk ON tbl_gaji_bpjs_tk.id_hemxxmh = p.id_hemxxmh
                        
                        
                        -- select gaji bpjs kes
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                    tanggal_efektif,
                                    IFNULL(nominal, 0) AS gaji_bpjs_kes
                            FROM (
                                SELECT
                                    id,
                                    id_hemxxmh,
                                    tanggal_efektif,
                                    nominal,
                                    ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                FROM htpr_hemxxmh
                                WHERE
                                    htpr_hemxxmh.id_hpcxxmh = 127
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) tbl_gaji_bpjs_kes ON tbl_gaji_bpjs_kes.id_hemxxmh = p.id_hemxxmh
                        
                        -- select data dari hibksmh untuk hitung bpjs kesehatan
                        LEFT JOIN (
                            SELECT
                            persen_karyawan,
                            persen_perusahaan,
                            is_active
                            FROM (
                                SELECT
                                    persen_karyawan,
                                    persen_perusahaan,
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
                                LEFT JOIN hesxxtd b ON b.id_hemxxmh = bpjs_kes.id_hemxxmh
                                LEFT JOIN hemxxmh c ON c.kode = b.nik_baru
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
                                LEFT JOIN hesxxtd b ON b.id_hemxxmh = bpjs_tk.id_hemxxmh
                                LEFT JOIN hemxxmh c ON c.kode = b.nik_baru
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
                                id_hemxxmh,
                                tanggal_efektif,
                                IFNULL(nominal, 0) AS pot_uang_makan
                            FROM (
                                SELECT
                                    id,
                                    id_hemxxmh,
                                    tanggal_efektif,
                                    nominal,
                                    ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                FROM htpr_hemxxmh
                                WHERE
                                    htpr_hemxxmh.id_hpcxxmh = 34
                                    AND tanggal_efektif <= :tanggal_akhir
                                    AND is_active = 1
                            ) AS subquery
                            WHERE row_num = 1
                        ) pot_uang_makan ON pot_uang_makan.id_hemxxmh = p.id_hemxxmh
                    ),
                    pot_upah AS (
                        SELECT
                            pr.id_hemxxmh,
                            
                            ROUND(SUM(
                                IF(
                                    job.id_hesxxmh = 3 AND job.id_heyxxmd = 1,

                                    -- Pot Upah Mati
                                    (
                                        IFNULL((
                                            SELECT a.nominal
                                            FROM htpr_hemxxmh a
                                            WHERE a.id_hpcxxmh = 35
                                                AND a.id_hemxxmh = pr.id_hemxxmh
                                                AND a.tanggal_efektif <= pr.tanggal
                                            ORDER BY a.tanggal_efektif DESC
                                            LIMIT 1
                                        ),0)
                                        * pr.is_pot_upah
                                    ),

                                    -- Rumus: ( (gp + tjab + fix_cost) / grup_hk (21 / 25) ) * is_pot_upah
                                    (
                                        (
                                            (
                                                -- GP
                                                IFNULL((
                                                    SELECT a.nominal
                                                    FROM htpr_hemxxmh a
                                                    WHERE a.id_hpcxxmh = 1
                                                        AND a.id_hemxxmh = pr.id_hemxxmh
                                                        AND a.tanggal_efektif <= pr.tanggal
                                                    ORDER BY a.tanggal_efektif DESC
                                                    LIMIT 1
                                                ),0)

                                                +

                                                -- TJAB
                                                IFNULL((
                                                    SELECT a.nominal
                                                    FROM htpr_hemxxmh a
                                                    WHERE a.id_hpcxxmh = 32
                                                        AND a.id_hemxxmh = pr.id_hemxxmh
                                                        AND a.tanggal_efektif <= pr.tanggal
                                                    ORDER BY a.tanggal_efektif DESC
                                                    LIMIT 1
                                                ),0)

                                                +

                                                IFNULL(fc.fix_cost,0)

                                            )
                                            /
                                            IF(job.grup_hk = 1, 21, 25)
                                        )
                                        * pr.is_pot_upah
                                    )
                                )
                            )) AS pot_upah

                        FROM htsprrd pr
                        JOIN hemxxmh peg ON peg.id = pr.id_hemxxmh
                        JOIN hemjbmh job ON job.id_hemxxmh = peg.id
                        LEFT JOIN fix_cost fc ON fc.id_hemxxmh = pr.id_hemxxmh

                        WHERE pr.is_pot_upah = 1 
                        AND pr.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir

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
                    pendapatan_lain_before_pph AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_lain_before_pph AS pendapatan_lain_before_pph
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_lain_before_pph
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 129
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) lain_before_pph ON lain_before_pph.id_hemxxmh = p.id_hemxxmh
                    ),
                    pot_lain_before_pph AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_lain_before_pph AS pot_lain_before_pph
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_lain_before_pph
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 130
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) lain_before_pph ON lain_before_pph.id_hemxxmh = p.id_hemxxmh
                    ),
                    
                    pendapatan_lain_after_pph AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_lain_after_pph AS pendapatan_lain_after_pph
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_lain_after_pph
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 131
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) lain_after_pph ON lain_after_pph.id_hemxxmh = p.id_hemxxmh
                    ),
                    pot_lain_after_pph AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_lain_after_pph AS pot_lain_after_pph
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_lain_after_pph
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 132
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) lain_after_pph ON lain_after_pph.id_hemxxmh = p.id_hemxxmh
                    ),

                    piut_kyw AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_piutang AS pot_piutang
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_piutang
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 105
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) piutang ON piutang.id_hemxxmh = p.id_hemxxmh
                    ),
                    komp_rekontrak AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_rekontrak AS komp_rekontrak
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_rekontrak
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 108
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) rekontrak ON rekontrak.id_hemxxmh = p.id_hemxxmh
                    ),
                    komp_sisa_cuti AS (
                        SELECT
                            a.id_hemxxmh,
                            -- peg.kode,
                            -- peg.nama,
                            COALESCE(cb.c_cb, 0) AS c_cb,
                            ifnull(a.saldo,0) AS saldo,
                            ifnull(a.saldo, 0) - COALESCE(cb.c_cb, 0) AS sisa_cuti_hari,
                            
                            CASE
                                WHEN ifnull(a.saldo, 0) > 0 THEN ifnull(a.saldo, 0) - (COALESCE(cb.c_cb, 0))
                                ELSE 0
                            END AS sisa_saldo,
                            ( 
                                (gp + t_jab + fix_cost + var_cost + tj_khusus) / IF(grup_hk = 1, 21, 25) 
                            ) * (
                                CASE
                                    WHEN ifnull(a.saldo, 0) > 0 THEN ifnull(a.saldo, 0) - (COALESCE(cb.c_cb, 0))
                                    ELSE 0
                                END
                            ) AS komp_sisa_cuti
                        FROM htlxxrh AS a
                        LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                        LEFT JOIN hemjbmh AS jb ON jb.id_hemxxmh = peg.id

                        -- Izin yang memotong Cuti
                        LEFT JOIN (
                            SELECT
                                rh.id_hemxxmh,
                                COUNT(rh.id) AS c_cb
                            FROM htlxxrh AS rh
                            LEFT JOIN htlxxmh AS mh ON mh.id = rh.id_htlxxmh
                            WHERE YEAR(rh.tanggal) = YEAR(DATE_SUB(:tanggal_akhir, INTERVAL 1 YEAR)) AND rh.jenis = 1 AND mh.is_potongcuti = 1
                            GROUP BY rh.id_hemxxmh
                        ) AS cb ON cb.id_hemxxmh = a.id_hemxxmh
                        
                        LEFT JOIN (
                            SELECT
                                p.id_hemxxmh,
                                COALESCE(nominal_gp, 0) AS gp
                            FROM pegawai p

                            LEFT JOIN (
                                SELECT id_hemxxmh, nominal AS nominal_gp
                                FROM (
                                    SELECT *,
                                        ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) rn
                                    FROM htpr_hemxxmh
                                    WHERE id_hpcxxmh = 1
                                    AND is_active = 1
                                    AND tanggal_efektif <= :tanggal_awal
                                ) x WHERE rn = 1
                            ) gp1 ON gp1.id_hemxxmh = p.id_hemxxmh
                        ) gp ON gp.id_hemxxmh = jb.id_hemxxmh

                        LEFT JOIN (
                            SELECT
                                p.id_hemxxmh,
                                COALESCE(nominal_t_jab, 0) AS t_jab
                            FROM pegawai p
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    tanggal_efektif,
                                    IFNULL(nominal, 0) AS nominal_t_jab
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
                                        AND tanggal_efektif <= :tanggal_awal
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                            ) t_jabatan ON t_jabatan.id_hemxxmh = p.id_hemxxmh
                        ) tj ON tj.id_hemxxmh = jb.id_hemxxmh
                        
                        LEFT JOIN (
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
                                        id_hevgrmh,
                                        id_heyxxmd,
                                        id_hesxxmh,
                                        IF(
                                            a.tanggal_keluar IS NULL,
                                            TIMESTAMPDIFF(MONTH, a.tanggal_masuk, :tanggal_akhir) / 12,
                                            TIMESTAMPDIFF(MONTH, a.tanggal_masuk, a.tanggal_keluar) / 12
                                        ) AS masa_kerja_year
                                    FROM hemjbmh AS a
                                    GROUP BY a.id_hemxxmh
                                ) AS job
                                LEFT JOIN (
                                    SELECT
                                        id_hevgrmh,
                                        id_heyxxmd,
                                        id_hesxxmh,
                                        tanggal_efektif,
                                        nominal,
                                        tahun_min,
                                        tahun_max,
                                        ROW_NUMBER() OVER (
                                            PARTITION BY id_hevgrmh, id_heyxxmd, id_hesxxmh
                                            ORDER BY tanggal_efektif DESC
                                        ) AS row_num
                                    FROM htpr_hevgrmh_mk
                                    WHERE
                                        id_hpcxxmh = 31
                                        AND tanggal_efektif <= :tanggal_awal
                                        AND is_active = 1
                                ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                                    AND masakerja.id_heyxxmd = job.id_heyxxmd
                                    AND masakerja.id_hesxxmh = job.id_hesxxmh
                                WHERE if(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
                                GROUP BY job.id_hemxxmh
                            ) AS mk ON mk.id_hemxxmh = p.id_hemxxmh
                        ) fc ON fc.id_hemxxmh = jb.id_hemxxmh

                        LEFT JOIN (
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
                                        AND tanggal_efektif <= :tanggal_awal
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                            ) tbl_var_cost ON tbl_var_cost.id_hemxxmh = p.id_hemxxmh
                        ) vcost on vcost.id_hemxxmh = jb.id_hemxxmh

                        LEFT JOIN (
                            SELECT
                                p.id_hemxxmh,
                                IFNULL(nominal_tj_khusus,0) as tj_khusus
                            FROM pegawai p

                            -- tunjangan khusus
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    tanggal_efektif,
                                    IFNULL(nominal, 0) AS nominal_tj_khusus
                                FROM (
                                    SELECT
                                        id,
                                        id_hemxxmh,
                                        tanggal_efektif,
                                        nominal,
                                        ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                    FROM htpr_hemxxmh
                                    WHERE
                                        htpr_hemxxmh.id_hpcxxmh = 133
                                        AND tanggal_efektif <= :tanggal_awal
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                            ) tbl_tj_khusus ON tbl_tj_khusus.id_hemxxmh = p.id_hemxxmh
                        ) tjk on tjk.id_hemxxmh = jb.id_hemxxmh
                        
                        WHERE YEAR(a.tanggal) = YEAR(DATE_SUB(:tanggal_akhir, INTERVAL 1 YEAR)) AND jb.is_checkclock = 1 
                        GROUP BY a.id_hemxxmh 
                    ),
                    
                    denda_apd AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_denda_apd AS denda_apd
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_denda_apd
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 103
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) denda_apd ON denda_apd.id_hemxxmh = p.id_hemxxmh
                    ),
                    
                    iuran_spsi AS (
                        SELECT
                            p.id_hemxxmh,
                            nominal_iuran_spsi AS iuran_spsi
                        FROM pegawai p
                        LEFT JOIN (
                            SELECT
                                id_hemxxmh,
                                IFNULL(nominal, 0) AS nominal_iuran_spsi
                            FROM (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(nominal) as nominal
                                FROM hpy_piutang_d as a
                                WHERE
                                    a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                                    AND id_hpcxxmh = 126
                                    AND is_approve = 1
                                GROUP BY id_hemxxmh
                            ) AS subquery
                        ) iuran_spsi ON iuran_spsi.id_hemxxmh = p.id_hemxxmh
                    ),
                    payroll AS (
                        SELECT
                            -- :id_hpyxxth,
                            p.id_hemxxmh,
                            id_gtxpkmh,
                            kategori_kelas,
                            nrp,
                            nama,
                            departemen,
                            jabatan,
                            tipe,
                            sub_tipe,
                            status_peg,
                            
                            ptkp,
                            no_rekening,
                            ktp,
                            npwp,

                            IFNULL(gp.gp, 0) AS gp,
                            t_jab,
                            0 AS terima_lain,
                            var_cost,
                            tj_khusus,
                            fix_cost,
                            premi_abs,
                            
                            lembur15,
                            lembur15_final,
                            rp_lembur15,

                            lembur2,
                            lembur2_final,
                            rp_lembur2,

                            lembur3,
                            lembur3_final,
                            rp_lembur3,
                            
                            total_lembur_jam,
                            total_lembur_jam_final,
                            total_rp_lembur,

                            IFNULL(komp_rekontrak,0 ) AS komp_rekontrak,
                            IF(MONTH(:tanggal_akhir) = 1, 
                                IFNULL(komp_sisa_cuti,0 ),
                                0
                            ) AS komp_sisa_cuti,

                            IF(MONTH(:tanggal_akhir) = 1, 
                                IFNULL(sisa_cuti_hari,0 ),
                                0
                            ) AS sisa_cuti_hari,

                            0 AS thr,

                            -- POTONGAN
                            pot_makan,
                            IFNULL(pot_upah, 0) AS pot_upah,
                            IFNULL(pendapatan_lain_before_pph,0 ) AS pendapatan_lain_before_pph,
                            IFNULL(pot_lain_before_pph,0 ) AS pot_lain_before_pph,

                            bpjs_kes_perusahaan,
                            jkk,
                            jkm,
                            
                            -- BATAS HITUNG BRUTO
                            (
                                COALESCE(gp.gp,0)
                                + COALESCE(t_jab,0)
                                + 0
                                + COALESCE(var_cost,0)
                                + COALESCE(tj_khusus,0)
                                + COALESCE(fix_cost,0)
                                + COALESCE(total_rp_lembur,0)
                                + COALESCE(komp_rekontrak,0)
                                + COALESCE(komp_sisa_cuti,0)
                                + 0
                                + COALESCE(pendapatan_lain_before_pph,0)
                                + COALESCE(bpjs_kes_perusahaan,0)
                                + COALESCE(jkk,0)
                                + COALESCE(jkm,0)
                            )
                            -
                            (
                                COALESCE(pot_makan,0)
                                + COALESCE(pot_upah,0)
                                + COALESCE(pot_lain_before_pph,0)
                            ) AS bruto,

                            jht_perusahaan,
                            jp_perusahaan,

                            pot_jht_karyawan,
                            pot_jp_karyawan,
                            bpjs_kes_karyawan,
                            
                            IFNULL(pot_piutang,0 ) AS pot_piutang,
                            IFNULL(denda_apd,0 ) AS denda_apd,
                            IFNULL(iuran_spsi,0 ) AS iuran_spsi,
                            IFNULL(pendapatan_lain_after_pph,0 ) AS pendapatan_lain_after_pph,
                            IFNULL(pot_lain_after_pph,0 ) AS pot_lain_after_pph

                        FROM presensi p
                        LEFT JOIN pegawai peg on peg.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN gaji_pokok gp ON gp.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN t_jabatan tjab ON tjab.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN var_cost ON var_cost.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN tj_khusus ON tj_khusus.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN fix_cost ON fix_cost.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN premi_abs ON premi_abs.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_makan ON pot_makan.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN bpjs ON bpjs.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_upah pu ON pu.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_jam ON pot_jam.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN piut_kyw ON piut_kyw.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN komp_rekontrak ON komp_rekontrak.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN komp_sisa_cuti ON komp_sisa_cuti.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pendapatan_lain_before_pph ON pendapatan_lain_before_pph.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_lain_before_pph ON pot_lain_before_pph.id_hemxxmh = p.id_hemxxmh

                        LEFT JOIN pendapatan_lain_after_pph ON pendapatan_lain_after_pph.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN pot_lain_after_pph ON pot_lain_after_pph.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN denda_apd ON denda_apd.id_hemxxmh = p.id_hemxxmh
                        LEFT JOIN iuran_spsi ON iuran_spsi.id_hemxxmh = p.id_hemxxmh
                    ),
                    payroll_final AS (
                        SELECT
                            id_hemxxmh,
                            nrp,
                            payroll.nama,
                            departemen,
                            jabatan,
                            tipe,
                            sub_tipe,
                            status_peg,
                            
                            ptkp,
                            no_rekening,
                            ktp,
                            npwp,
                            gp,
                            t_jab,
                            terima_lain,
                            var_cost,
                            tj_khusus,
                            fix_cost,
                            premi_abs,
                            
                            lembur15,
                            lembur15_final,
                            rp_lembur15,
                            lembur2,
                            lembur2_final,
                            rp_lembur2,
                            lembur3,
                            lembur3_final,
                            rp_lembur3,
                            
                            total_lembur_jam,
                            total_lembur_jam_final,
                            total_rp_lembur,
                            komp_rekontrak,
                            komp_sisa_cuti,
                            sisa_cuti_hari,
                            thr,
                            
                            pot_makan,
                            pot_upah,
                            pendapatan_lain_before_pph,
                            pot_lain_before_pph,
                            bpjs_kes_perusahaan,
                            jkk,
                            jkm,
                            
                            bruto,
                            kategori_kelas,
                            ter.persen persen_ter,
                            bruto * (IFNULL(ter.persen,0) / 100) AS pot_pph21,
                            bruto - ( bruto * (IFNULL(ter.persen,0) / 100) ) AS after_pph21,
                            
                            jht_perusahaan,
                            jp_perusahaan,

                            pot_jht_karyawan,
                            pot_jp_karyawan,
                            bpjs_kes_karyawan,
                            pot_piutang,
                            denda_apd,
                            iuran_spsi,

                            pendapatan_lain_after_pph,
                            pot_lain_after_pph,

                            -- GAJI BERSIH
                            ( bruto - ( bruto * (IFNULL(ter.persen,0) / 100) ) )
                            + (jht_perusahaan + jp_perusahaan)
                            - (
                                pot_jht_karyawan
                                + pot_jp_karyawan
                                + bpjs_kes_karyawan
                                + pot_piutang
                                + denda_apd
                                + iuran_spsi
                            )
                            + pendapatan_lain_after_pph
                            - pot_lain_after_pph
                             AS gaji_bersih
                        FROM payroll
                        LEFT JOIN hpcatmh AS ter ON ter.kategori = payroll.kategori_kelas 
                            AND payroll.bruto > ter.nominal_awal AND payroll.bruto <= ter.nominal_akhir
                    )
                    SELECT
                        :id_hpyxxth AS id_hpyxxth,

                        id_hemxxmh,
                        nrp,
                        nama,
                        departemen,
                        jabatan,
                        tipe,
                        sub_tipe,
                        status_peg,

                        ptkp,
                        no_rekening,
                        ktp,
                        npwp,

                        gp,
                        t_jab,
                        terima_lain,
                        var_cost,
                        tj_khusus,
                        fix_cost,
                        premi_abs,

                        lembur15,
                        lembur15_final,
                        rp_lembur15,
                        lembur2,
                        lembur2_final,
                        rp_lembur2,
                        lembur3,
                        lembur3_final,
                        rp_lembur3,

                        total_lembur_jam,
                        total_lembur_jam_final,
                        total_rp_lembur,

                        komp_rekontrak,
                        komp_sisa_cuti,
                        sisa_cuti_hari,
                        thr,

                        pot_makan,
                        pot_upah,
                        pendapatan_lain_before_pph,
                        pot_lain_before_pph,

                        bpjs_kes_perusahaan,
                        jkk,
                        jkm,

                        bruto,
                        kategori_kelas,
                        persen_ter,
                        pot_pph21,
                        after_pph21,

                        jht_perusahaan,
                        jp_perusahaan,

                        pot_jht_karyawan,
                        pot_jp_karyawan,
                        bpjs_kes_karyawan,
                        pot_piutang,
                        denda_apd,
                        iuran_spsi,

                        pendapatan_lain_after_pph,
                        pot_lain_after_pph,

                        gaji_bersih,
                        FLOOR(gaji_bersih % 100) AS bulat,
                        FLOOR(gaji_bersih - (gaji_bersih % 100)) AS gaji_terima

                    FROM payroll_final
        ');
        $rs_hpyemtd = $qs_hpyemtd->fetchAll();

        foreach ($rs_hpyemtd as $payroll) {
            $qi_insert = $db
                ->query('insert', 'hpyemtd')
                ->set($payroll)
                ->exec();
        }

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