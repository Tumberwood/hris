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
        
        // delete pph21 saat generate ulang payroll sesuai dengan periode payroll
        $qd_pph21 = $db 
            ->query('delete', 'hppphth')
            ->where('id_hpyxxth',$id_hpyxxth)
            // ->where('id_heyxxmh',$id_heyxxmh)
        ->exec();
        
        // INSERT PPH21
        $qr_pph21 = $db
            ->raw()
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->bind(':id_hpyxxth', $id_hpyxxth)
            ->exec('INSERT INTO hppphth (
                        id_hpyxxth, 
                        id_hpyemtd, 
                        id_hemxxmh, 
                        keterangan,
                        nama,
                        gaji_for_pph,
                        sum_gaji_for_pph,
                        nilai_est_gaji,
                        estimasi_gaji,
                        bruto_total,
                        bruto_gaji,
                        jhthp_tahunan,
                        by_jabatan_total,
                        by_jabatan_gaji,
                        netto_total,
                        netto_gaji,
                        pkp_total,
                        pkp_gaji,
                        pph21_total,
                        pph21_gaji,
                        pph21_thr,
                        sum_pph_berjalan,
                        pph21_final
                    )
                    WITH payroll AS (
                        SELECT
                            a.id_hemxxmh,
                            a.id AS id_detail,
                            b.id AS id_header,
                            CONCAT(hem.kode, " - ", hem.nama) AS peg,
                            gaji_for_pph,
                            nilai_est_gaji,
                            MONTH(b.tanggal_akhir) AS bulan,
                            nilai_est_gaji * (12 - MONTH(b.tanggal_akhir)) AS estimasi_gaji,
                            IFNULL(sum_gaji_for_pph, 0) AS sum_gaji_for_pph,
                            IFNULL(sum_gaji_for_pph, 0) + (nilai_est_gaji * (12 - MONTH(b.tanggal_akhir))) AS bruto_total,
                            (IFNULL(sum_gaji_for_pph, 0) + (nilai_est_gaji * (12 - MONTH(b.tanggal_akhir)))) - IFNULL(thr_this_year, 0) AS bruto_gaji,
                            (a.pot_jht + a.pot_psiun) * 12 AS jhthp_tahunan,
                            ((IFNULL(sum_gaji_for_pph, 0) + IFNULL(nilai_est_gaji, 0) * (12 - MONTH(b.tanggal_akhir))) + (a.pot_jht + a.pot_psiun) * 12) * 0.05 AS by_jabatan_total,
                            ((IFNULL(sum_gaji_for_pph, 0) + nilai_est_gaji * (12 - MONTH(b.tanggal_akhir)) - IFNULL(thr_this_year, 0)) + (a.pot_jht + a.pot_psiun) * 12) * 0.05 AS by_jabatan_gaji,
                            (IFNULL(sum_gaji_for_pph, 0) + nilai_est_gaji * (12 - MONTH(b.tanggal_akhir))) - ((IFNULL(sum_gaji_for_pph, 0) + IFNULL(nilai_est_gaji, 0) * (12 - MONTH(b.tanggal_akhir)) + (a.pot_jht + a.pot_psiun) * 12) * 0.05) AS netto_total,
                            ((IFNULL(sum_gaji_for_pph, 0) + nilai_est_gaji * (12 - MONTH(b.tanggal_akhir)) - IFNULL(thr_this_year, 0)) - ((IFNULL(sum_gaji_for_pph, 0) + IFNULL(nilai_est_gaji, 0) * (12 - MONTH(b.tanggal_akhir)) + (a.pot_jht + a.pot_psiun) * 12) * 0.05)) AS netto_gaji,
                            ptkp.amount AS ptkp,
                            doc.is_npwp,
                            persen_total.pkp_akhir,
                            persen_total.pajak,
                            ifnull(sum_pph21_this_year_until_this_month,0) as sum_pph21_this_year_until_this_month
                        FROM hpyemtd AS a
                        LEFT JOIN hpyxxth AS b ON b.id = a.id_hpyxxth
                        LEFT JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh
                        LEFT JOIN hemdcmh AS doc ON doc.id_hemxxmh = hem.id
                        LEFT JOIN gtxpkmh AS ptkp ON ptkp.id = doc.id_gtxpkmh
                        LEFT JOIN (
                            SELECT
                                a.id,
                                a.id_hemxxmh,
                                (a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm + a.pendapatan_lain + a.lemburbersih + IFNULL(a.pph21_back, 0) + IFNULL(a.kompensasi_ak, 0) + IFNULL(a.koreksi_lembur, 0) + IFNULL(a.koreksi_status, 0) + IFNULL(a.thr, 0) + IFNULL(a.sisa_cuti, 0)) - (a.pot_jht + a.pot_upah + IFNULL(a.pot_jam, 0) + a.pot_psiun) AS gaji_for_pph,
                                (a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm) - (a.pot_jht + a.pot_psiun) AS nilai_est_gaji
                            FROM hpyemtd AS a
                            WHERE a.id_hpyxxth = :id_hpyxxth
                        ) AS note1 ON note1.id = a.id
                        LEFT JOIN (
                            SELECT
                                a.id,
                                a.id_hemxxmh,
                                a.is_active,
                                SUM((a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm + a.pendapatan_lain + a.lemburbersih + IFNULL(a.pph21_back, 0) + IFNULL(a.kompensasi_ak, 0) + IFNULL(a.koreksi_lembur, 0) + IFNULL(a.koreksi_status, 0) + IFNULL(a.thr, 0) + IFNULL(a.sisa_cuti, 0)) - (a.pot_jht + a.pot_upah + IFNULL(a.pot_jam, 0) + a.pot_psiun)) AS sum_gaji_for_pph,
                                SUM((a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm) - (a.pot_jht + a.pot_psiun)) AS sum_nilai_est_gaji,
                                SUM(IFNULL(a.thr, 0)) AS thr_this_year
                            FROM hpyemtd AS a
                            LEFT JOIN hpyxxth AS b ON b.id = a.id_hpyxxth
                            WHERE YEAR(b.tanggal_akhir) = YEAR(:tanggal_akhir) AND MONTH(b.tanggal_akhir) <= MONTH(:tanggal_akhir) AND b.is_active = 1
                            GROUP BY a.id_hemxxmh
                        ) AS old_pay ON old_pay.id_hemxxmh = a.id_hemxxmh
                    
                        LEFT JOIN (
                            SELECT *
                            FROM hppphmh
                            WHERE is_active = 1
                            ORDER BY id
                            LIMIT 1
                        ) AS persen_total ON persen_total.is_active = 1
                    
                        LEFT JOIN (
                            SELECT
                                a.id,
                                a.id_hemxxmh,
                                SUM(a.pot_pph21) AS sum_pph21_this_year_until_this_month
                            FROM hpyemtd AS a
                            LEFT JOIN hpyxxth AS b ON b.id = a.id_hpyxxth
                            WHERE YEAR(b.tanggal_akhir) = YEAR(:tanggal_akhir) AND MONTH(b.tanggal_akhir) <= MONTH(:tanggal_akhir) AND b.is_active = 1
                            GROUP BY a.id_hemxxmh
                        ) AS sum_pph21 ON sum_pph21.id_hemxxmh = a.id_hemxxmh
                    
                        WHERE a.id_hpyxxth = :id_hpyxxth
                    )
                    SELECT
                        id_header, -- untuk mempermudah pick periode
                        id_detail, -- untuk mempermudah join dengan payroll
                        id_hemxxmh, -- untuk data pegawai
                        CONCAT("ptkp = ",ptkp) AS keterangan, -- tambahan keterangan nilai ptkp
                        peg AS nama, -- nama pegawai concat NIK dan Nama
                        
                        -- gaji_for_pph = sum dari komponen penambah dan komponen pengurang yg is_pph21 = 1 (note :1)					
                        round(gaji_for_pph) AS gaji_for_pph,
                        
                        -- sum_gaji_for_pph = sum dari nilai gaji_for_pph dari januari s/d bulan payroll yg dihitung					
                        round(sum_gaji_for_pph) AS sum_gaji_for_pph,
                        
                        -- nilai_est_gaji = sum dari komponen gaji yg is_est_pph21 = 1 (note : 2)					
                        ROUND(nilai_est_gaji) AS nilai_est_gaji,
                        
                        -- estimasi_gaji = nilai_est_gaji * (12 - bulan payroll yg dihitung)					
                        ROUND(estimasi_gaji) AS estimasi_gaji,
                        
                        -- bruto_total = sum_gaji_for_pph + estimasi_gaji					
                        ROUND(bruto_total) AS bruto_total,
                        
                        -- bruto_gaji = bruto_total - thr (note : 4) 					
                        ROUND(bruto_gaji) AS bruto_gaji,
                        
                        -- jhtjp_tahunan = (pot_jht + pot_jp) * 12					
                        ROUND(jhthp_tahunan) AS jhthp_tahunan,
                        
                        -- by_jabatan_total = (bruto_total + jhtjp_tahunan) * 0,05 (dengan maks nilai 6jt)					
                        ROUND(by_jabatan_total) AS by_jabatan_total,
                        
                        -- by_jabatan_gaji = (bruto_gaji + jhtjp_tahunan) * 0,05 (dengan nilai maks 6jt)					
                        ROUND(by_jabatan_gaji) AS by_jabatan_gaji,
                        
                        -- netto_total = bruto_total - by_jabatan_total					
                        ROUND(netto_total) AS netto_total,
                        
                        -- netto_gaji = bruto_gaji - by_jabatan_gaji					
                        ROUND(netto_gaji) AS netto_gaji,
                        
                        --  PKP_total = netto_total - PTKP (if hasil <= 0 then PKP_total = 0)					
                        ROUND(if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0)) AS pkp_total,
                        
                        -- PKP_gaji = netto_gaji - PTKP (if hasil <= 0 then PKP_gaji = 0)					
                        ROUND(if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0)) AS pkp_gaji,
                        
                        -- PPH21_total = PKP_total * %Pajak (note : 3)					
                        ROUND(
                            IF(
                                if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - pkp_akhir > 0,
                                IF(if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                    ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                    +
                                    ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                    +
                                    ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                    
                                    ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                    +
                                    ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                    ),
                                ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                            )
                        ) 
                        AS pph21_total,
                        
                        -- PPH21_gaji = PKP_gaji * %Pajak (note : 3)					
                        ROUND(    
                            IF(
                                if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir > 0,
                                IF(if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                    ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                    +
                                    ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                    +
                                    ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                    
                                    ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                    +
                                    ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                    ),
                                ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                            )
                        ) 
                        AS pph21_gaji,
                        
                        -- PPH21_THR = PPH21_total - PPH21_gaji					
                        ROUND(
                            (
                                IF(
                                    if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - pkp_akhir > 0,
                                    IF(if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                        ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        +
                                        ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                        +
                                        ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                        
                                        ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        +
                                        ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                        ),
                                    ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                )
                            ) - 
                            (
                                IF(
                                    if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir > 0,
                                    IF(if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                        ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        +
                                        ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                        +
                                        ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                        
                                        ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        +
                                        ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                        ),
                                    ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                )
                            )
                        )
                        AS pph21_thr,

                        -- pph21_gaji * (bulan sekarang/12)
                        ROUND(   
                            (
                                IF(
                                    if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir > 0,
                                    IF(if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                        ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        +
                                        ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                        +
                                        ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                        
                                        ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        +
                                        ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                        ),
                                    ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                )
                            ) 
                            * (bulan / 12) 
                        )
                        AS sum_pph_berjalan,
                        
                        ROUND
                        (
                            (
                                (
                                    IF(
                                        if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - pkp_akhir > 0,
                                        IF(if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                            ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                            +
                                            ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                            +
                                            ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                            
                                            ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                            +
                                            ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                            ),
                                        ((if(ptkp is not null, CASE WHEN netto_total - ptkp <= 0 THEN 0 ELSE netto_total - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                    )
                                ) - 
                                (
                                    IF(
                                        if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir > 0,
                                        IF(if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                            ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                            +
                                            ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                            +
                                            ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                            
                                            ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                            +
                                            ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                            ),
                                        ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                    )
                                )
                                +
                                (
                                    (
                                        IF(
                                            if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir > 0,
                                            IF(if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir) > 0,
                                                ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                                +
                                                ((pkp_lanjut) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                                +
                                                ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - (pkp_lanjut + pkp_akhir)) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut_ketiga + 1, persen_lanjut_ketiga) / 100)),
                                                
                                                ((pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                                +
                                                ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0) - pkp_akhir) * (IF(is_npwp = 0 OR is_npwp IS NULL, persen_lanjut + 1, persen_lanjut) / 100))
                                                ),
                                            ((if(ptkp is not null, CASE WHEN netto_gaji - ptkp <= 0 THEN 0 ELSE netto_gaji - ptkp END, 0)) * (IF(is_npwp = 0 OR is_npwp IS NULL, pajak + 1, pajak) / 100))
                                        )
                                    ) 
                                    * (bulan / 12)
                                )
                            )
                            - -- dikurangi dengan sum pph21 tahun ini dari bulan sebelumnya sampai bulan sekaang
                            sum_pph21_this_year_until_this_month 
                        )
                        AS pph21_final
                    
                    FROM payroll
                    LEFT JOIN (
                        SELECT
                            pajak AS persen_lanjut,
                            pkp_awal,
                            pkp_akhir AS pkp_lanjut
                        FROM hppphmh
                    ) AS persen_pph21 ON pkp_akhir =  persen_pph21.pkp_awal
                    
                    LEFT JOIN (
                        SELECT
                            pajak AS persen_lanjut_ketiga,
                            pkp_awal,
                            pkp_akhir AS pkp_lanjut_ketiga
                        FROM hppphmh
                    ) AS persen_pph21_ketiga ON pkp_lanjut = persen_pph21_ketiga.pkp_awal
                    ORDER BY id_hemxxmh;
            
        ');

        // Update PPH21 dan Total Gaji Diterima
        $qr_pph21 = $db
            ->raw()
            ->bind(':id_hpyxxth', $id_hpyxxth)
            ->exec('UPDATE hpyemtd AS a
                    LEFT JOIN hppphth AS b ON b.id_hpyemtd = a.id
                    SET 
                        a.pot_pph21 = if(b.pph21_final > 0, b.pph21_final, a.pot_pph21),
                        a.gaji_bersih =
                        FLOOR(
                            (a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm + a.lemburbersih + a.pendapatan_lain + a.pph21_back + a.kompensasi_ak + a.koreksi_lembur + a.koreksi_status)
                            -
                            (a.pot_makan + a.pot_jkkjkm + a.pot_jht + a.pot_upah + a.pot_jam + a.pot_bpjs + a.pot_psiun + a.pot_pinjaman + a.pot_klaim + a.pot_denda_apd + if(b.pph21_final > 0, b.pph21_final, a.pot_pph21))
                        ),
                        
                        a.bulat =
                        FLOOR(
                            (
                                (a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm + a.lemburbersih + a.pendapatan_lain + a.pph21_back + a.kompensasi_ak + a.koreksi_lembur + a.koreksi_status)
                                -
                                (a.pot_makan + a.pot_jkkjkm + a.pot_jht + a.pot_upah + a.pot_jam + a.pot_bpjs + a.pot_psiun + a.pot_pinjaman + a.pot_klaim + a.pot_denda_apd + if(b.pph21_final > 0, b.pph21_final, a.pot_pph21))
                            ) % 100
                        ),
                        
                        a.gaji_terima = 
                        FLOOR(
                            (
                                (a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm + a.lemburbersih + a.pendapatan_lain + a.pph21_back + a.kompensasi_ak + a.koreksi_lembur + a.koreksi_status)
                                -
                                (a.pot_makan + a.pot_jkkjkm + a.pot_jht + a.pot_upah + a.pot_jam + a.pot_bpjs + a.pot_psiun + a.pot_pinjaman + a.pot_klaim + a.pot_denda_apd + if(b.pph21_final > 0, b.pph21_final, a.pot_pph21))
                            )
                            -
                            (
                                (
                                    (a.gp + a.t_jab + a.var_cost + a.fix_cost + a.premi_abs + a.trm_jkkjkm + a.lemburbersih + a.pendapatan_lain + a.pph21_back + a.kompensasi_ak + a.koreksi_lembur + a.koreksi_status)
                                    -
                                    (a.pot_makan + a.pot_jkkjkm + a.pot_jht + a.pot_upah + a.pot_jam + a.pot_bpjs + a.pot_psiun + a.pot_pinjaman + a.pot_klaim + a.pot_denda_apd + if(b.pph21_final > 0, b.pph21_final, a.pot_pph21))
                                ) % 100
                            )
                        )
                    WHERE a.id_hpyxxth = :id_hpyxxth
            
        ');

        

        $qu_hpyxxth = $db
            ->query('update', 'hpyxxth')
            ->set('generated_on',$timestamp)
            ->where('id',$id_hpyxxth)
        ->exec();
        
        $db->commit();

        $akhir = new Carbon();

        $data = array(
            'message' => 'Generate PPh21 Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik', 
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