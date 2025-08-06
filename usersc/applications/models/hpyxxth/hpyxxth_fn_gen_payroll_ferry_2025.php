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
        
        //DELETE DETAIL PPh21 LAMA
        $qd_detail_pph21 = $db
            ->raw()
            ->bind(':id_hpyxxth', $id_hpyxxth)
            ->exec('DELETE FROM hppphth
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
                        jam_lembur_final,
                        lemburbersih,
                        pph21_back,
                        kompensasi_ak,
                        koreksi_lembur,
                        koreksi_status,
                        pot_makan,
                        pot_jkkjkm,
                        pot_jht,
                        pot_upah,
                        pot_jam,
                        pot_bpjs,
                        pot_psiun,
                        pot_pinjaman,
                        pot_klaim,
                        pot_denda_apd,
                        pot_lain,
                        pot_pph21,
                        gaji_bersih,
                        bulat,
                        gaji_terima,
                        pendapatan_lain,
                        overtime_susulan,
                        sisa_cuti,
                        bruto
                    )
                    WITH qs_payroll AS (
                        SELECT DISTINCT
                            ifnull(is_terminasi, 0) as is_terminasi,
                            CONCAT(hem.kode, " - ", hem.nama) AS nama,
                            is_perubahan_hk,
                            premiabs,
                            hk_baru,
                            c.id_heyxxmh,
                            c.tanggal_keluar,
                            presensi_hk,
                            jadwal_bukan_off,
                            hari_kerja,
                            a.id_hemxxmh,
                            report_pot_upah,
                            report_pot_jam,
                            if( c.tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), 0, (FLOOR(if( c.id_hesxxmh = 3, ifnull(nominal_lembur_mati,0), (ifnull(nominal_gp,0) + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0))) / 173)) )) AS nominal_lembur_jam,
                            ifnull(sum_rp_lembur15, 0) rp_lembur15,
                            ifnull(sum_rp_lembur2, 0) rp_lembur2,
                            ifnull(sum_rp_lembur3, 0) rp_lembur3,
                            ifnull(sum_rp_lembur4, 0) rp_lembur4,
                                
                            c.id_hesxxmh as hesxx,
                            c.id_heyxxmd as id_heyxxmd,
                            c.grup_hk,
                            (ifnull(nominal_gp,0) + ifnull(if(c.id_hesxxmh = 1, nominal_t_jab, ifnull(nominal_jabatan, 0)),0)) AS pengali,
                            (ifnull(if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),0) + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) AS pengali_jam,
                            keluar_report,
                            -- gaji pokok
                            IFNULL( 
                                if( c.tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), 0,
                                    if(c.tanggal_masuk BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-02") AND LAST_DAY(last_day(:tanggal_akhir)), 
                                        if(ifnull(is_perubahan_hk,0) > 0, 
                                            if((hk_lama_report * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)) > if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),
                                                0,
                                                (hk_lama_report * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp))

                                            )
                                            +
                                            if((hk_baru_jadwal * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)) > if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),
                                                0,
                                                (hk_baru_jadwal * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp))

                                            )
                                            ,
                                            (hari_kerja / if(c.grup_hk = 1, 21, 25)) * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)
                                        ),
                                        if(c.tanggal_keluar BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-01") AND LAST_DAY(last_day(:tanggal_akhir)), 
                                            if(ifnull(is_perubahan_hk,0) > 0, 
                                                if((hk_lama_report * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)) > if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),
                                                    0,
                                                    (hk_lama_report * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp))

                                                )
                                                +
                                                if((hk_baru_jadwal * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)) > if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),
                                                    0,
                                                    (hk_baru_jadwal * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp))

                                                )
                                                ,
                                                keluar_report / if(c.grup_hk = 1, 21, 25) * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)
                                            ),
                                        if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp) )
                                    )
                                    
                                )
                            ,0) AS gp,
                            DATEDIFF(LAST_DAY(:tanggal_awal), c.tanggal_keluar) AS diff,                        
                            gp_pelatihan,
                            nominal_gp,
                            (hari_kerja / if(c.grup_hk = 1, 21, 25)) * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp) AS fixed_gp,
                            -- Koreksi Perubahan Status
                            IFNULL( 
                                if( c.tanggal_masuk BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal), -- AND id_status IS NOT NULL, 
                                    ((hk_baru / if(c.grup_hk = 1, 21, 25)) * if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)),
                                    0
                                )
                            ,0) AS koreksi_status,
                            
                            -- tunjangan jabatan
                            IFNULL( 
                                if(c.tanggal_masuk BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-01") AND LAST_DAY(last_day(:tanggal_akhir)), 
                                    hari_kerja / if(c.grup_hk = 1, 21, 25) * ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) , if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0),
                                    if(c.tanggal_keluar BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-01") AND LAST_DAY(last_day(:tanggal_akhir)), 
                                        keluar_report / if(c.grup_hk = 1, 21, 25) * ifnull(if(c.id_hesxxmh = 1 OR (c.id_heyxxmd = 1 and c.id_hesxxmh = 4), IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) , if(c.id_heyxxmh = 1 and c.id_hesxxmh = 2, ifnull(nominal_jabatan, 0), 0) ),0),
                                        if(hem.nama IN ("AGUS PRIYO S", "JOKO PRASETYO", "NENDY PUTRATAMA", "PRAWOTO"),
                                            IFNULL(nominal_jabatan, 0),
                                            IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0))
                                        )
                                    )
                                ),
                            0) AS t_jab,
                            
                            -- var_cost
                            IFNULL(nominal_var_cost, 0) AS var_cost,
                            
                            -- fix cost atau masa kerja
                            if(c.id_heyxxmh = 1, 
                                IFNULL( 
                                    if(c.tanggal_masuk BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-01") AND LAST_DAY(last_day(:tanggal_akhir)), 
                                        hari_kerja / if(c.grup_hk = 1, 21, 25) * nominal_mk,
                                        if(c.tanggal_keluar BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-01") AND LAST_DAY(last_day(:tanggal_akhir)), 
                                            keluar_report / if(c.grup_hk = 1, 21, 25) * nominal_mk,
                                        nominal_mk)
                                    ),
                                0) ,
                            0) AS fix_cost,
                            
                            -- premi absen dengan validasi jika ada izin/absen yang memotong premi maka premi absen == 0 atau hangus
                            -- revisi 2 Oct, premi absen hanya untuk organik, os tidak ada
                            -- revisi 11 Oct, tri wandono tidak dapat premi absen
                            if(a.id_hemxxmh = 67, 0, if(c.id_heyxxmh = 1, IFNULL(if(report_pot_premi >= 1, 0, premiabs), 0), 0)) AS premi_abs,
                            
                            -- hitung jkk
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jkk / 100) * gaji_bpjs,0),0)), 0) AS jkk, -- bro
                            
                            -- hitung jkm
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jkm / 100) * gaji_bpjs,0),0)), 0) AS jkm, -- bro
                            
                            -- trm_jkkjkm == jkk + jkm
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0)), 0) AS trm_jkkjkm, -- bro
                            
                            -- mulai lembur
                            sum_lembur15 AS lembur15,
                            sum_lembur2 AS lembur2,
                            sum_lembur3 AS lembur3,
                            sum_lembur4 AS lembur4,
                            c.tanggal_masuk,
                            sum_lembur15_final AS lembur15_final,
                            sum_lembur2_final AS lembur2_final,
                            sum_lembur3_final AS lembur3_final,
                            sum_lembur4_final AS lembur4_final,
                            
                            -- hitung pot makan
                            IFNULL(pot_makan * pot_uang_makan, 0) AS pot_makan,
                            
                            -- pot_jkkjkm == jkk + jkm (sama dengan pot_jkkjkm)
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL(((persen_jkk / 100) * gaji_bpjs) + ((persen_jkm / 100) * gaji_bpjs), 0),0)), 0) AS pot_jkkjkm, -- bro
                            
                            -- hitung pot_jht
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jht_karyawan / 100) * gaji_bpjs, 0),0)), 0) AS pot_jht, -- bro
                            
                            -- hitung pot_bpjs
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_kes > 0, 0, if(id_heyxxmd = 3, IFNULL((persen_karyawan / 100) * gaji_bpjs, 0),0)), 0) AS pot_bpjs, -- bro
                            
                            -- hitung pot_psiun
                            
                            -- revisi khusus yang sub tipe == karyawan
                            if(c.id_hesxxmh IN (1,2,5), if(skip_c_bpjs_tk > 0, 0, if(id_heyxxmd = 3,IFNULL((persen_jp_karyawan / 100) * gaji_bpjs, 0),0)), 0) AS pot_psiun, -- bro
                            
                            -- pph21 back
                            IFNULL(nominal_pph21_back,0) AS pph21_back,

                            -- potongan utang
                            IFNULL(nominal_pinjaman,0) AS pot_pinjaman,
                            IFNULL(nominal_klaim,0) AS pot_klaim,
                            IFNULL(nominal_denda_apd,0) AS pot_denda_apd,
                            IFNULL(nominal_pot_lain,0) AS pot_lain,
                            IFNULL(nominal_pph21,0) AS pot_pph21,
                            hk_nik_lama,
                            IFNULL(nominal_pendapatan_lain,0) AS pendapatan_lain,
                            IFNULL(nominal_koreksi_lembur,0) AS koreksi_lembur,
                            is_pot_jam_rotasi_lv,
                            ifnull(pot_jam_lv_lama, 0) as pot_jam_lv_lama,
                            IFNULL(pot_jam_lv_baru, 0) AS pot_jam_lv_baru,
                            is_pot_upah_rotasi_lv,
                            ifnull(pot_upah_lv_lama, 0) as pot_upah_lv_lama,
                            IFNULL(pot_upah_lv_baru, 0) AS pot_upah_lv_baru,
                            (ifnull(if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),0) + ifnull(nominal_jab_rotasi,0) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) AS pengali_rotasi_old,
                            ifnull(id_status, 0) as id_status,
                            hem.nama AS nama_saat_ini,
                            if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp) as nominal_gpp,
                            if(c.id_heyxxmd = 1 AND c.id_hesxxmh = 3,
                                if(c.tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal),
                                    if(is_terminasi > 0 OR id_status IS NOT NULL, 
                                        if(c.tanggal_masuk BETWEEN DATE_FORMAT(:tanggal_awal, "%Y-%m-02") AND LAST_DAY(:tanggal_awal), 
                                            (jadwal_baru_masuk - jadwal_bulan_lalu) / if(c.grup_hk = 1, 21, 25) * (if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)) 
                                            ,
                                        
                                            (if(c.grup_hk = 1, 21, 25) - jadwal_bulan_lalu)  / if(c.grup_hk = 1, 21, 25) * (if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp)) 
                                        )
                                        ,0
                                    ), 0
                                ),0
                            ) AS pot_gp,
                            ifnull(pot_upah_min_satu,0) AS pot_upah_min_satu,
                            IFNULL(pot_upah_spesial,0) AS pot_upah_spesial,
                            ROUND(
                                if(pot_upah_min_satu > 0, 
                                    (pot_upah_min_satu * (ifnull(if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),0) + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) + ifnull(nominal_var_cost,0) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) / IF(c.grup_hk = 1, 21, 25))
                                    +
                                    (IFNULL(pot_upah_spesial,0) * IF(c.grup_hk = 1, 83509, 70148)),
                                    0
                                )
                            ) AS alpha_min_satu,
                            id_harxxmh,
                            jadwal_bulan_lalu,
                            a.is_approve,
                            ifnull(susulan, 0) as susulan,
                            (ifnull(if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),0) + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) ) * (ifnull(masa_kontrak, 0) / 12) AS auto_kompensasi_ak,
                            if(MONTH(:tanggal_akhir) = 1, 
								(
									(ifnull(if(c.id_hesxxmh = 3, gp_pelatihan, nominal_gp),0) + IF(c.id_heyxxmd = 1 AND c.id_hesxxmh = 4, COALESCE(nominal_jabatan, 0), COALESCE(nominal_t_jab, 0)) + if(c.id_heyxxmh = 1, ifnull(nominal_mk,0),0) )
									/ IF(c.grup_hk = 1, 21, 25) 
								) * ifnull(sisa_saldo, 0)
								, 0
							) AS sisa_cuti
                            
                        FROM htsprrd AS a
                        LEFT JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                        LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
                        
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
                                        AND tanggal_efektif < :tanggal_akhir
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
                                        AND tanggal_efektif < :tanggal_akhir
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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                            ) pot_uang_makan ON pot_uang_makan.id_hesxxmh = c.id_hesxxmh
                            
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
                                        AND tanggal_efektif < :tanggal_akhir
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
                                        AND tanggal_efektif < :tanggal_akhir
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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                            ) premi_abs ON premi_abs.id_hevxxmh = c.id_hevxxmh
                            
                            -- sum durasi lembur dan makan
                            LEFT JOIN (
                                SELECT
                                    a.id_hemxxmh,
                                    SUM(durasi_lembur_final) AS lembur_sum,
                                    SUM(is_makan) AS pot_makan
                                FROM htsprrd as a
                                LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                LEFT JOIN (
                                    SELECT
                                        id_hemxxmh,
                                        IFNULL(is_terminasi, 0) AS is_terminasi
                                    FROM (
                                        SELECT
                                            id_hemxxmh,
                                            COUNT(id) AS is_terminasi
                                        FROM hemjbrd
                                        WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                        GROUP BY id_hemxxmh
                                    ) AS subquery
                                ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                WHERE tanggal BETWEEN :tanggal_awal AND (:tanggal_akhir)
                                GROUP BY a.id_hemxxmh
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
                                    sum_lembur4_final,
                                    -- RP
                                    sum_rp_lembur15,		            
                                    sum_rp_lembur2,		            
                                    sum_rp_lembur3,		            
                                    sum_rp_lembur4	
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
                                        SUM(IFNULL(prr.lembur4_final, 0)) AS sum_lembur4_final,
                                        
                                        -- RP
                                        SUM(IFNULL(prr.rp_lembur15, 0)) AS sum_rp_lembur15,		            
                                        SUM(IFNULL(prr.rp_lembur2, 0)) AS sum_rp_lembur2,		            
                                        SUM(IFNULL(prr.rp_lembur3, 0)) AS sum_rp_lembur3,		            
                                        SUM(IFNULL(prr.rp_lembur4, 0)) AS sum_rp_lembur4		           
                                    FROM htsprrd AS prr
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = prr.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = prr.id_hemxxmh
                                    WHERE tanggal BETWEEN :tanggal_awal AND (:tanggal_akhir)
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
                                    report_pot_upah,
                                 	grup_hk                                	
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(if(c.id_heyxxmd = 1 AND c.id_hesxxmh = 3, 1 * IF(a.grup_hk = 1, 83509, 70148), 1 / IF(a.grup_hk = 1, 21, 25))) AS report_pot_upah,
                                        -- COUNT(a.id) AS report_pot_upah,
                                        a.grup_hk
                                    FROM htsprrd AS a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND is_pot_upah = 1
                                    GROUP BY id_hemxxmh
                                ) c_report_pot_upah
                            ) presensi_pot_upah ON presensi_pot_upah.id_hemxxmh = a.id_hemxxmh

                            -- Pot Upah Spesial KBM Pelatihan 
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    nama,
                                    ifnull(pot_upah_min_satu,0) AS pot_upah_min_satu,
                                    tanggal AS tanggal_pot_upah,
                                    tanggal_keluar
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        CONCAT(hem.kode, " - ", hem.nama) AS nama,
                                        COUNT(a.id) AS pot_upah_min_satu,
                                        a.tanggal,
                                        c.tanggal_keluar
                                    FROM htsprrd AS a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
                                    WHERE a.tanggal = DATE_SUB(c.tanggal_keluar, INTERVAL 1 DAY) AND a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND is_pot_upah = 1 AND c.id_heyxxmd = 1 AND c.id_hesxxmh = 3
                                    GROUP BY a.id_hemxxmh
                                ) report_pot_upah_min_satu
                            ) presensi_pot_upah_min_satu ON presensi_pot_upah_min_satu.id_hemxxmh = a.id_hemxxmh
                            
                            -- Pot Upah Spesial KBM Pelatihan 
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(pot_upah_spesial,0) AS pot_upah_spesial,
                                    tanggal,
                                    tanggal_keluar
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        COUNT(a.id) AS pot_upah_spesial,
                                        a.tanggal,
                                        c.tanggal_keluar
                                    FROM htsprrd AS a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE if(c.tanggal_keluar IS NOT NULL,  a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir) AND a.tanggal <> DATE_SUB(c.tanggal_keluar, INTERVAL 1 DAY), a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir))
                                        AND is_pot_upah = 1 AND c.id_heyxxmd = 1 AND c.id_hesxxmh = 3
                                    GROUP BY a.id_hemxxmh
                                ) report_pot_upah_spesial
                            ) presensi_pot_upah_spesial ON presensi_pot_upah_spesial.id_hemxxmh = a.id_hemxxmh
                            
                            -- hari kerja karyawan baru
                            LEFT JOIN (
                                SELECT
                                    (hk_report + IFNULL(jadwal.jadwal, 0)) AS hari_kerja,
                                    hk_report as presensi_hk,
                                    IFNULL(jadwal.jadwal, 0) as jadwal_bukan_off,
                                    report.id_hemxxmh
                                FROM (
                                    SELECT 
                                        COUNT(a.id) AS hk_report,
                                        a.id_hemxxmh
                                    FROM htsprrd AS a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE a.tanggal BETWEEN c.tanggal_masuk AND last_day(:tanggal_akhir)
                                        AND a.st_jadwal <> "OFF" -- sebelumnya st-clock in
                                    GROUP BY a.id_hemxxmh
                                ) AS report
                                LEFT JOIN (
                                    SELECT
                                        a.id_hemxxmh,
                                        COUNT(a.id) AS jadwal
                                    FROM htssctd as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE tanggal BETWEEN DATE_ADD(last_day(:tanggal_akhir), INTERVAL 1 DAY) AND LAST_DAY(last_day(:tanggal_akhir)) AND is_active = 1
                                        AND id_htsxxmh <> 1
                                    GROUP BY id_hemxxmh
                                ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh
                            ) AS hk ON hk.id_hemxxmh = a.id_hemxxmh
                            
                            -- hari kerja NIK baru
                            LEFT JOIN (
                                SELECT
                                    (hk_report) AS hk_baru,
                                    report.id_hemxxmh
                                FROM (
                                    SELECT 
                                        COUNT(a.id) AS hk_report,
                                        c.tanggal_masuk,
                                        a.id_hemxxmh
                                    FROM htsprrd AS a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE a.tanggal BETWEEN c.tanggal_masuk AND LAST_DAY(:tanggal_awal)
                                        AND a.st_jadwal <> "OFF"
                                    GROUP BY a.id_hemxxmh
                                ) AS report
                            ) AS hk_baru ON hk_baru.id_hemxxmh = a.id_hemxxmh
                            
                            
                        -- case HK 5 HK 6
                        LEFT JOIN (
                            SELECT
                                hk_report,
                                hk_jadwal_non_off,
                                hk_lama_report,
                                IFNULL(jadwal.hk_baru_jadwal, 0) AS hk_baru_jadwal,
                                report.id_hemxxmh,
                                IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk
                            FROM (
                                SELECT 
                                    COUNT(a.id) AS hk_report,
                                    IFNULL(COUNT(a.id), 0) / if(a.grup_hk = 1, 21,25) AS hk_lama_report,
                                    is_perubahan_hk,
                                    a.id_hemxxmh,
                                    tanggal_hk_baru_min_day,
                                    a.grup_hk,
                                    job.tanggal_masuk
                                FROM htsprrd AS a
                                LEFT JOIN (
                                    SELECT
                                        id_hemxxmh,
                                        IFNULL(is_perubahan_hk, 0) AS is_perubahan_hk,
                                        tanggal_hk_baru_min_day
                                    FROM (
                                        SELECT
                                            id_hemxxmh,
                                            COUNT(id) AS is_perubahan_hk,
                                            DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS tanggal_hk_baru_min_day
                                        FROM hemjbrd AS jb
                                        WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                                        GROUP BY id_hemxxmh
                                    ) AS subquery
                                ) AS history ON history.id_hemxxmh = a.id_hemxxmh
                                LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                                WHERE a.tanggal BETWEEN job.tanggal_masuk AND tanggal_hk_baru_min_day 
                                AND (a.st_clock_in <> "off" AND a.st_jadwal <> "OFF")
                                GROUP BY a.id_hemxxmh
                            ) AS report
                            LEFT JOIN (
                                SELECT
                                    tanggal,
                                    htssctd.id_hemxxmh,
                                    COUNT(id) AS hk_jadwal_non_off,
                                    ifnull(COUNT(id),0) / if(grup_hk = 1, 21, 25) AS hk_baru_jadwal
                                FROM htssctd
                                LEFT JOIN (
                                    SELECT
                                    id_hemxxmh,
                                    grup_hk,
                                    jb.tanggal_awal AS awal_grup_hk_baru
                                FROM hemjbrd AS jb
                                WHERE is_from_hk = 1 AND tanggal_awal BETWEEN DATE_FORMAT(:tanggal_akhir, "%Y-%m-01") AND LAST_DAY(:tanggal_akhir)
                                GROUP BY id_hemxxmh
                                ) AS jbrd ON jbrd.id_hemxxmh = htssctd.id_hemxxmh
                                WHERE id_htsxxmh <> 1 AND tanggal BETWEEN awal_grup_hk_baru AND LAST_DAY(:tanggal_akhir) AND is_active = 1
                                GROUP BY id_hemxxmh
                            ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                        ) AS hk5hk6 ON hk5hk6.id_hemxxmh = a.id_hemxxmh

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
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE a.tanggal BETWEEN DATE_FORMAT(last_day(:tanggal_akhir), "%Y-%m-01") AND DATE_SUB(c.tanggal_keluar, INTERVAL 1 DAY)
                                        AND a.st_jadwal <> "OFF"
                                    GROUP BY a.id_hemxxmh
                                ) AS report
                            ) AS keluar ON keluar.id_hemxxmh = a.id_hemxxmh
                            
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
                                        c.tanggal_masuk,
                                        rd.id_hemxxmh
                                    FROM htsprrd AS rd
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = rd.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = rd.id_hemxxmh
                                    WHERE rd.tanggal BETWEEN date_add(c.tanggal_masuk, INTERVAL 1 DAY) AND LAST_DAY(last_day(:tanggal_akhir))
                                AND rd.status_presensi_in = "HK"
                                    GROUP BY rd.id_hemxxmh
                                ) AS prd ON prd.id_hemxxmh = b.id
                                GROUP BY nama
                                ORDER BY b.id DESC
                            ) AS hk_nik_lama ON hk_nik_lama.id_hemxxmh = a.id_hemxxmh

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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
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
                            
                            -- Cari Pinjaman
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_pinjaman
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 105
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) pinjaman ON pinjaman.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari klaim
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_klaim
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 104
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) klaim ON klaim.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari denda_apd
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_denda_apd
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 103
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) denda_apd ON denda_apd.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari Pendapatan_lain
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_pendapatan_lain
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 106
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) pendapatan_lain ON pendapatan_lain.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari pot_pph21
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_pph21
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 11
                                        AND plus_min = "Pengurang"
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) pph21 ON pph21.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari pph21_back
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_pph21_back
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 11
                                        AND plus_min = "Penambah"
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) pph21_back ON pph21_back.id_hemxxmh = a.id_hemxxmh
                            
                            -- validasi cari izin/absen yang memotong jam dari report presensi
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    report_pot_jam
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(pot_hk) AS report_pot_jam
                                    FROM htsprrd as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                    GROUP BY id_hemxxmh
                                ) c_report_pot_jam
                            ) presensi_pot_jam ON presensi_pot_jam.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari bpjs_kes_exclude
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(c_bpjs_kes, 0) AS skip_c_bpjs_kes
                                FROM (
                                    SELECT
                                        COUNT(bpjs_kes.id) AS c_bpjs_kes,
                                        bpjs_kes.id_hemxxmh
                                    FROM bpjs_kes_exclude AS bpjs_kes
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = bpjs_kes.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = bpjs_kes.id_hemxxmh
                                    WHERE bpjs_kes.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) bpjs_kes_exclude ON bpjs_kes_exclude.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari bpjs_tk_exclude
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(c_bpjs_tk, 0) AS skip_c_bpjs_tk
                                FROM (
                                    SELECT
                                        COUNT(bpjs_tk.id) AS c_bpjs_tk,
                                        bpjs_tk.id_hemxxmh
                                    FROM bpjs_tk_exclude AS bpjs_tk
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = bpjs_tk.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = bpjs_tk.id_hemxxmh
                                    WHERE bpjs_tk.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) bpjs_tk_exclude ON bpjs_tk_exclude.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari resign
                            LEFT JOIN (
                                SELECT
                                    id_harxxmh,
                                    id_hemxxmh,
                                    IFNULL(is_terminasi, 0) AS is_terminasi
                                FROM (
                                    SELECT
                                        id_harxxmh,
                                        id_hemxxmh,
                                        COUNT(id) AS is_terminasi
                                    FROM hemjbrd
                                    WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                            
                            -- Cari koreksi_lembur rekontrak KAK id_hpc = 108
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_koreksi_lembur
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 111
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) koreksi_lembur ON koreksi_lembur.id_hemxxmh = a.id_hemxxmh
                            
                            -- Potongan lain | 124	PTL	Potongan Lain
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    IFNULL(nominal, 0) AS nominal_pot_lain
                                FROM (
                                    SELECT
                                        a.id_hemxxmh,
                                        SUM(nominal) as nominal
                                    FROM hpy_piutang_d as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    WHERE
                                        a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                        AND id_hpcxxmh = 124
                                        AND is_approve = 1
                                    GROUP BY id_hemxxmh
                                ) AS subquery
                            ) pot_lain ON pot_lain.id_hemxxmh = a.id_hemxxmh
                            
                            -- gaji pokok pelatihan
                            LEFT JOIN (
                                SELECT
                                    id_hesxxmh,
                                    tanggal_efektif,
                                    IFNULL(nominal, 0) AS gp_pelatihan
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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                            ) gp_pelatihan ON gp_pelatihan.id_hesxxmh = c.id_hesxxmh
                            
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
                                                jb.id_hemxxmh,
                                                COUNT(jb.id) AS is_pot_jam_rotasi_lv,
                                                jb.tanggal_awal AS tgl_awal_lv_baru,
                                                DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_tgl_level_akhir
                                            FROM hemjbrd AS jb
                                            LEFT JOIN hemjbmh as c on c.id_hemxxmh = jb.id_hemxxmh
                                            LEFT JOIN (
                                                SELECT
                                                    id_hemxxmh,
                                                    IFNULL(is_terminasi, 0) AS is_terminasi
                                                FROM (
                                                    SELECT
                                                        id_hemxxmh,
                                                        COUNT(id) AS is_terminasi
                                                    FROM hemjbrd
                                                    WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                                    GROUP BY id_hemxxmh
                                                ) AS subquery
                                            ) resign ON resign.id_hemxxmh = jb.id_hemxxmh
                                            WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
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
                                        jb.id_hemxxmh,
                                        id_hevxxmh_awal,
                                        id_hevxxmh_akhir,
                                        jb.tanggal_awal AS tgl_awal_lv_baru
                                    FROM hemjbrd AS jb
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = jb.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = jb.id_hemxxmh
                                    WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                    GROUP BY id_hemxxmh
                                    ) AS jbrd ON jbrd.id_hemxxmh = htsprrd.id_hemxxmh
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = jbrd.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = jbrd.id_hemxxmh
                                    WHERE pot_hk > 0 AND tanggal BETWEEN tgl_awal_lv_baru AND last_day(:tanggal_akhir) AND is_active = 1
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
                                        AND tanggal_efektif < :tanggal_akhir
                                        AND is_active = 1
                                ) AS subquery
                                WHERE row_num = 1
                                ) t_jabatan ON t_jabatan.id_hevxxmh = jadwal.id_hevxxmh_awal
                            ) AS pot_jam_rotasi ON pot_jam_rotasi.id_hemxxmh = a.id_hemxxmh
                            
                            
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
                                                jb.id_hemxxmh,
                                                COUNT(jb.id) AS is_pot_upah_rotasi_lv,
                                                jb.tanggal_awal AS tgl_awal_lv_baru,
                                                DATE_SUB(jb.tanggal_awal, INTERVAL 1 DAY) AS akhir_tgl_level_akhir
                                            FROM hemjbrd AS jb
                                            LEFT JOIN hemjbmh as c on c.id_hemxxmh = jb.id_hemxxmh
                                            LEFT JOIN (
                                                SELECT
                                                    id_hemxxmh,
                                                    IFNULL(is_terminasi, 0) AS is_terminasi
                                                FROM (
                                                    SELECT
                                                        id_hemxxmh,
                                                        COUNT(id) AS is_terminasi
                                                    FROM hemjbrd
                                                    WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                                    GROUP BY id_hemxxmh
                                                ) AS subquery
                                            ) resign ON resign.id_hemxxmh = jb.id_hemxxmh
                                            WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
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
                                        jb.id_hemxxmh,
                                        id_hevxxmh_awal,
                                        id_hevxxmh_akhir,
                                        jb.tanggal_awal AS tgl_awal_lv_baru
                                    FROM hemjbrd AS jb
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = jb.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = jb.id_hemxxmh
                                    WHERE keterangan like "Perubahan Level%" AND tanggal_awal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                                    GROUP BY id_hemxxmh
                                    ) AS jbrd ON jbrd.id_hemxxmh = htsprrd.id_hemxxmh
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = jbrd.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = jbrd.id_hemxxmh
                                    WHERE is_pot_upah > 0 AND tanggal BETWEEN tgl_awal_lv_baru AND last_day(:tanggal_akhir) AND is_active = 1
                                    GROUP BY id_hemxxmh
                                ) AS jadwal ON jadwal.id_hemxxmh = report.id_hemxxmh 
                                
                            ) AS pot_upah_rotasi ON pot_upah_rotasi.id_hemxxmh = a.id_hemxxmh

                            -- hesxxtd
                            LEFT JOIN (
                            SELECT
                                    ifnull(id_status, 0) as id_status,
                                    nama_peg,
                                    report.id_hemxxmh
                                FROM (
                                    SELECT 
                                        a.id as id_status,
                                        peg.nama AS nama_peg,
                                        a.id_hemxxmh
                                    FROM hesxxtd  as a
                                    LEFT JOIN hemjbmh as c on c.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN (
                                        SELECT
                                            id_hemxxmh,
                                            IFNULL(is_terminasi, 0) AS is_terminasi
                                        FROM (
                                            SELECT
                                                id_hemxxmh,
                                                COUNT(id) AS is_terminasi
                                            FROM hemjbrd
                                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                                            GROUP BY id_hemxxmh
                                        ) AS subquery
                                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                                    LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                                    WHERE a.is_active = 1 AND a.is_approve = 1 AND a.tanggal_mulai BETWEEN :tanggal_awal AND last_day(:tanggal_akhir) 
                                ) AS report
                            ) AS perubahan_status ON perubahan_status.nama_peg = hem.nama
                            
                        
                        -- Ambil Jadwal Report Presensi dari awal bulan periode Payroll (contoh = 23 Oct) 
                        LEFT JOIN (
                            SELECT
                                jadwal_bulan_lalu,
                                report.id_hemxxmh
                            FROM (
                                SELECT 
                                    COUNT(a.id) AS jadwal_bulan_lalu,
                                    a.id_hemxxmh
                                FROM htsprrd AS a
                                LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                                WHERE a.tanggal BETWEEN if(job.tanggal_masuk BETWEEN DATE_FORMAT(:tanggal_awal, "%Y-%m-01") AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY), job.tanggal_masuk,  DATE_FORMAT(:tanggal_awal, "%Y-%m-01")) AND DATE_SUB(job.tanggal_keluar, INTERVAL 1 DAY)
                                    AND a.st_jadwal <> "OFF"
                                GROUP BY a.id_hemxxmh
                            ) AS report
                        ) AS hk_bulan_sebelumnya ON hk_bulan_sebelumnya.id_hemxxmh = a.id_hemxxmh
                    
                        -- Ambil Jadwal Schedule
                        LEFT JOIN (
                            SELECT
                                jadwal_baru_masuk,
                                report.id_hemxxmh
                            FROM (
                                SELECT 
                                    COUNT(a.id) AS jadwal_baru_masuk,
                                    a.id_hemxxmh
                                FROM htssctd AS a
                                LEFT JOIN hemjbmh AS job ON job.id_hemxxmh = a.id_hemxxmh
                                WHERE a.tanggal BETWEEN job.tanggal_masuk AND LAST_DAY(:tanggal_awal)
                                    AND a.id_htsxxmh <> 1
                                GROUP BY a.id_hemxxmh
                            ) AS report
                        ) AS hk_awal_masuk_sebelumnya ON hk_awal_masuk_sebelumnya.id_hemxxmh = a.id_hemxxmh

                        -- Overtime Susulan
                        LEFT JOIN (
                            SELECT
                                SUM(a.rp_lembur_final) AS susulan,
                                id_hemxxmh
                            FROM htoxxrd_susulan AS a
                            WHERE a.id_hpyxxth = ' . $id_hpyxxth . '
                            GROUP BY a.id_hemxxmh
                        ) AS overtime_susulan ON overtime_susulan.id_hemxxmh = a.id_hemxxmh
                        
                        -- HITUNG OTOMATIS KOMPENSASI AKHIR KONTRAK 05 JAN 2024
                        LEFT JOIN (
                            SELECT
                                a.id_hemxxmh,
                                peg.kode,
                                peg.nama,
                                a.tanggal_masuk,
                                a.tanggal_keluar,
                                TIMESTAMPDIFF(MONTH, a.tanggal_masuk, a.tanggal_keluar) AS masa_kontrak
                            FROM hemjbmh AS a
                            LEFT JOIN hemxxmh AS peg ON peg.id = a.id_hemxxmh
                            LEFT JOIN hemjbrd AS rd ON rd.id_hemxxmh = a.id_hemxxmh
                            WHERE a.tanggal_keluar BETWEEN :tanggal_awal AND :tanggal_akhir AND a.id_hesxxmh = 2 AND rd.id_harxxmh NOT IN (3,4)
                        ) AS otomatis_akhir_kontrak on otomatis_akhir_kontrak.id_hemxxmh = a.id_hemxxmh 
                        
                        -- Sisa Cuti
                        LEFT JOIN (
                            SELECT
								a.id_hemxxmh,
								peg.kode,
								peg.nama,
								COALESCE(cb.c_cb, 0) AS c_cb,
								IFNULL(c_rd, 0) AS c_rd,
								ifnull(a.saldo,0) AS saldo,
								
								-- SUM(
									CASE
										WHEN ifnull(a.saldo, 0) > 0 THEN ifnull(a.saldo, 0) - (COALESCE(cb.c_cb, 0))
										ELSE 0
									END
								-- ) 
                                AS sisa_saldo
							FROM htlxxrh AS a
							-- employee
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
									id_hemxxmh,
									COUNT(a.id) AS c_rd
								FROM htsprrd AS a
								WHERE YEAR(a.tanggal) = YEAR(DATE_SUB(:tanggal_akhir, INTERVAL 1 YEAR)) AND a.status_presensi_in = "AL"
								GROUP BY id_hemxxmh
							) AS rd ON rd.id_hemxxmh = a.id_hemxxmh
							
							WHERE YEAR(a.tanggal) = YEAR(DATE_SUB(:tanggal_akhir, INTERVAL 1 YEAR)) AND jb.is_checkclock = 1 
							GROUP BY a.id_hemxxmh 

                        ) AS saldo_sisa_cuti on saldo_sisa_cuti.id_hemxxmh = a.id_hemxxmh 
                        
                        WHERE a.tanggal BETWEEN :tanggal_awal AND last_day(:tanggal_akhir)
                        AND hem.id is not null
                        AND a.id_hemxxmh NOT IN (
                            SELECT
                                rd.id_hemxxmh
                            FROM hemjbrd AS rd
                            LEFT JOIN hemjbmh AS mh ON mh.id_hemxxmh = rd.id_hemxxmh
                            WHERE rd.id_harxxmh IN (3, 4) AND mh.id_heyxxmd = 3 AND rd.tanggal_akhir BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir)
                            GROUP BY id_hemxxmh
                        )
                    ),
                    pay AS (
                       SELECT
                            *,
                            
                            (
                                pot_gp + 
                                if(alpha_min_satu > 0, alpha_min_satu,
                                    if( is_pot_upah_rotasi_lv > 0, 
                                        (IF(pot_upah_lv_lama > 0, if(id_heyxxmd = 1 AND hesxx = 3, pot_upah_lv_lama * IF(grup_hk = 1, 83509, 70148), pot_upah_lv_lama * pengali_rotasi_old / IF(grup_hk = 1, 21, 25)) , 0))
                                        +
                                        (IF(pot_upah_lv_baru > 0, if(id_heyxxmd = 1 AND hesxx = 3, pot_upah_lv_baru * IF(grup_hk = 1, 83509, 70148), pot_upah_lv_baru * pengali_jam / IF(grup_hk = 1, 21, 25)) , 0)),
                                        (IF(report_pot_upah > 0, if(id_heyxxmd = 1 AND hesxx = 3, report_pot_upah, report_pot_upah * pengali_jam) , 0))
                                    ) 
                                ) 
                            ) AS pot_upah,
                            
                            FLOOR(if( is_pot_jam_rotasi_lv > 0, (if(id_heyxxmd = 1 AND hesxx = 3, pot_jam_lv_lama * IF(grup_hk = 1, 83509 * 21, 70148 * 25) / 173, pot_jam_lv_lama * pengali_rotasi_old / 173)) + (if(id_heyxxmd = 1 AND hesxx = 3, pot_jam_lv_baru * IF(grup_hk = 1, 83509 * 21, 70148 * 25) / 173, pot_jam_lv_baru * pengali_jam / 173)), IF(report_pot_jam > 0, if(id_heyxxmd = 1 AND hesxx = 3, report_pot_jam * IF(grup_hk = 1, 83509 * 21, 70148 * 25) / 173, report_pot_jam * pengali_jam / 173), 0))) AS pot_jam,
                            -- Jika tanggal resign > 20 maka pot_bpjs * 2, ELSE tidak dikali 2
                            FLOOR(if(is_terminasi > 0 AND day(tanggal_keluar) > 20, pot_bpjs * 2, pot_bpjs)) AS pot_bpjs_fix,
                            
                            FLOOR(IFNULL((rp_lembur15 + rp_lembur2 + rp_lembur3 + rp_lembur4), 0)) AS lemburbersih

                       FROM qs_payroll 
                    ),
                    payroll_fix AS (
                        SELECT
                            *,
                            (
                                (gp + susulan  +  pendapatan_lain + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + lemburbersih + pph21_back + auto_kompensasi_ak + koreksi_lembur + koreksi_status)
                                -
                                (pot_jam + pot_makan + pot_jkkjkm + pot_pph21 + pot_jht + pot_pinjaman + pot_klaim + pot_denda_apd + pot_lain + pot_upah+ pot_bpjs_fix+ pot_psiun)
                            ) AS bruto
                            -- ,

                            -- (
                            --     gp + t_jab + var_cost + fix_cost + premi_abs + trm_jkkjkm + pendapatan_lain + lemburbersih 
                            --     + IFNULL(auto_kompensasi_ak, 0) + IFNULL(koreksi_lembur, 0) + IFNULL(koreksi_status, 0) 
                            --     -- + IFNULL(thr, 0) 
                            --     + IFNULL(sisa_cuti, 0)
                            -- ) 
                            -- - 
                            -- (
                            --     pot_jht + pot_upah+ pot_jam
                            -- ) 
                            -- AS bruto
                            
                        FROM pay
                    )
                    SELECT
                        ' . $id_hpyxxth . ',
                        payroll_fix.id_hemxxmh,
                        gp,
                        t_jab,
                        var_cost,
                        fix_cost,
                        premi_abs,
                        ROUND(jkk) AS jkk,
                        ROUND(jkm) AS jkm,
                        ROUND(trm_jkkjkm) AS trm_jkkjkm,
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
                        (lembur15_final + lembur2_final + lembur3_final + lembur4_final) AS jam_lembur_final,
                        lemburbersih,
                        pph21_back,
                        auto_kompensasi_ak,
                        koreksi_lembur,
                        floor(koreksi_status) as koreksi_status,
                        FLOOR(pot_makan) AS pot_makan,
                        ROUND(pot_jkkjkm) AS pot_jkkjkm,
                        ROUND(pot_jht) AS pot_jht,

                        pot_upah,
                        pot_jam,
                        pot_bpjs_fix,
                        ROUND(pot_psiun) AS pot_psiun,
                        pot_pinjaman,
                        pot_klaim,
                        pot_denda_apd,
                        pot_lain,
                        bruto * (IFNULL(c.persen,0) / 100) AS pot_pph21,
                        FLOOR(bruto - (bruto * (IFNULL(c.persen,0) / 100) )) AS gaji_bersih,
                        FLOOR(
                            (
                                bruto - (bruto * (IFNULL(c.persen,0) / 100) )
                            ) % 100
                        ) AS bulat,
                        FLOOR(
                            (
                                bruto - (bruto * (IFNULL(c.persen,0) / 100) )
                            )
                                -
                            (
                                (
                                    bruto - (bruto * (IFNULL(c.persen,0) / 100) )
                                ) % 100
                            )
                        ) AS gaji_terima,
                        pendapatan_lain,
                        susulan,
                        sisa_cuti,
                        bruto
                    FROM payroll_fix
                    LEFT JOIN hemdcmh AS a ON a.id = payroll_fix.id_hemxxmh
                    LEFT JOIN gtxpkmh AS b ON b.id = a.id_gtxpkmh
                    LEFT JOIN hpcatmh AS c ON c.kategori = b.kategori_kelas AND payroll_fix.bruto > c.nominal_awal AND payroll_fix.bruto <= c.nominal_akhir
                    WHERE id_heyxxmd <> 2 AND payroll_fix.is_approve = 1
        ');

        // Insert Pegawai yang Resign pada periode payroll + 1 day s/d last day akhir payroll
        $qs_insert_exclude = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('INSERT INTO hpyxxth (
                        id_hemxxmh,
                        is_single,
                        tanggal_awal,
                        tanggal_akhir,
                        keterangan
                    )
                    SELECT
                        id_hemxxmh,
                        1,
                        :tanggal_awal,
                        :tanggal_akhir,
                        CONCAT(kode, " - ", DATE_FORMAT(tanggal_akhir, "%d %b %Y"))
                    FROM hemjbrd
                    WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir)
                    GROUP BY id_hemxxmh
            '
            );

        // Update Value Menjadi 0 untuk yang terminasi/resign dengan periode bulan pertama payroll (ex: 23 Oct s/d last October) dan Tipe Organik
        $qu_resign= $db
            ->raw()
            ->bind(':id_hpyxxth', $id_hpyxxth)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('UPDATE hpyemtd AS a
                    LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id_hemxxmh
                    LEFT JOIN (
                        SELECT
                            id_hemxxmh,
                            IFNULL(is_terminasi, 0) AS is_terminasi
                        FROM (
                            SELECT
                                id_hemxxmh,
                                COUNT(id) AS is_terminasi
                            FROM hemjbrd
                            WHERE id_harxxmh IN (3, 4) AND tanggal_akhir BETWEEN :tanggal_awal AND :tanggal_akhir
                            GROUP BY id_hemxxmh
                        ) AS subquery
                    ) resign ON resign.id_hemxxmh = a.id_hemxxmh
                    SET
                        gp = 0,
                        t_jab = 0,
                        var_cost = 0,
                        fix_cost = 0,
                        premi_abs = 0,
                        jkk = 0,
                        jkm = 0,
                        trm_jkkjkm = 0,
                        lembur15 = 0,
                        lembur2 = 0,
                        lembur3 = 0,
                        lembur4 = 0,
                        lembur15_final = 0,
                        lembur2_final = 0,
                        lembur3_final = 0,
                        lembur4_final = 0,
                        rp_lembur15 = 0,
                        rp_lembur2 = 0,
                        rp_lembur3 = 0,
                        rp_lembur4 = 0,
                        jam_lembur = 0,
                        jam_lembur_final = 0,
                        lemburbersih = 0,
                        pph21_back = 0,
                        kompensasi_ak = 0,
                        koreksi_lembur = 0,
                        koreksi_status = 0,
                        pot_makan = 0,
                        pot_jkkjkm = 0,
                        pot_jht = 0,
                        pot_upah = 0,
                        pot_jam = 0,
                        pot_bpjs = 0,
                        pot_psiun = 0,
                        pot_pinjaman = 0,
                        pot_klaim = 0,
                        pot_denda_apd = 0,
                        pot_pph21 = 0,
                        gaji_bersih = 0,
                        bulat = 0,
                        gaji_terima = 0,
                        sisa_cuti = 0,
                        pendapatan_lain = 0
                    WHERE
                        b.tanggal_keluar BETWEEN :tanggal_awal AND LAST_DAY(:tanggal_awal) AND id_hpyxxth = :id_hpyxxth AND b.id_heyxxmh = 1 AND is_terminasi > 0;
            
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