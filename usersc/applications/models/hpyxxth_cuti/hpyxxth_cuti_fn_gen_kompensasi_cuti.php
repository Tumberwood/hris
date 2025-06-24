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

    //DEKLARASI VARIABLE PAYROLL
    $tanggal_awal_select = new Carbon($_POST['tanggal_awal']); //gunakan carbon untuk ambil data tanggal
    $tanggal_awal = $tanggal_awal_select->format('Y-m-d'); //format jadi 2023-09-12

    $tanggal_akhir_select = new Carbon($_POST['tanggal_akhir']); //gunakan carbon untuk ambil data tanggal
    $tanggal_akhir = $tanggal_akhir_select->format('Y-m-d'); //format jadi 2023-09-12

    // $id_heyxxmh     = $_POST['id_heyxxmh'];
    $id_hpyxxth_cuti     = $_POST['id_hpyxxth_cuti'];

    $timestamp     = $_POST['timestamp']; //tambah timestamp untuk view

    try{
        $db->transaction();
        
        //DELETE Kompensasi Cuti lama
        $qd_detail_payroll = $db
            ->raw()
            ->bind(':id_hpyxxth_cuti', $id_hpyxxth_cuti)
            ->exec('DELETE FROM hpyemtd_cuti
                    WHERE id_hpyxxth_cuti = :id_hpyxxth_cuti
            '
        );
        
        // INSERT PAYROLL DETAIL
        $qr_gp = $db
            ->raw()
            ->bind(':id_hpyxxth_cuti', $id_hpyxxth_cuti)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('INSERT INTO hpyemtd_cuti (
                        id_hpyxxth_cuti, 
                        id_hemxxmh, 
                        id_heyxxmh, 
                        kode, 
                        nama, 
                        nominal, 
                        sisa_cuti, 
                        kompensasi_cuti
                    )
                    SELECT DISTINCT
                        :id_hpyxxth_cuti,
                        a.id_hemxxmh,
                        c.id_heyxxmh,
                        hem.kode,
                        hem.nama,
                        (
                            (IFNULL(IF(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp),0) + IFNULL(nominal_t_jab,0) + IF(c.id_heyxxmh = 1, IFNULL(nominal_mk,0),0) )
                            / 
                            IF(c.grup_hk = 1, 21, 25) 
                        ) nominal,
                        ifnull(sisa_cuti,0) sisa_cuti,
                        (
                            IF(MONTH(:tanggal_akhir) = 1, 
                                (
                                    (IFNULL(IF(c.id_hesxxmh = 3, pot_gp_pelatihan, nominal_gp),0) + IFNULL(nominal_t_jab,0) + IF(c.id_heyxxmh = 1, IFNULL(nominal_mk,0),0) )
                                    / IF(c.grup_hk = 1, 21, 25) 
                                ) * IFNULL(sisa_cuti, 0)
                                , 0
                            )
                        ) AS kompensasi_cuti
                    FROM htsprrd AS a
                    INNER JOIN hemjbmh AS c ON c.id_hemxxmh = a.id_hemxxmh
                    INNER JOIN hemxxmh AS hem ON hem.id = a.id_hemxxmh

                    -- gaji pokok pelatihan
                    LEFT JOIN (
                        SELECT
                            id_hesxxmh,
                            tanggal_efektIF,
                            IFNULL(nominal, 0) AS pot_gp_pelatihan
                        FROM (
                            SELECT
                                id,
                                id_hesxxmh,
                                tanggal_efektIF,
                                nominal,
                                ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektIF DESC) AS row_num
                            FROM htpr_hesxxmh
                            WHERE
                                htpr_hesxxmh.id_hpcxxmh = 1
                                AND tanggal_efektIF < :tanggal_akhir
                                AND is_active = 1
                        ) AS subquery
                        WHERE row_num = 1
                    ) pot_gp_pelatihan ON pot_gp_pelatihan.id_hesxxmh = c.id_hesxxmh
                        
                    -- gaji pokok
                    LEFT JOIN (
                        SELECT
                            id_hemxxmh,
                            tanggal_efektIF,
                            IFNULL(nominal, 0) AS nominal_gp
                        FROM (
                            SELECT
                                id,
                                id_hemxxmh,
                                tanggal_efektIF,
                                nominal,
                                ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektIF DESC) AS row_num
                            FROM htpr_hemxxmh
                            WHERE
                                htpr_hemxxmh.id_hpcxxmh = 1
                                AND tanggal_efektIF < :tanggal_akhir
                                AND is_active = 1
                        ) AS subquery
                        WHERE row_num = 1
                    ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id_hemxxmh

                    -- t jabatan untuk yang Tetap
                    LEFT JOIN (
                        SELECT
                            id_hevxxmh,
                            tanggal_efektIF,
                            IFNULL(nominal, 0) AS nominal_t_jab
                        FROM (
                            SELECT
                                id,
                                id_hevxxmh,
                                tanggal_efektIF,
                                nominal,
                                ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektIF DESC) AS row_num
                            FROM htpr_hevxxmh
                            WHERE
                                htpr_hevxxmh.id_hpcxxmh = 32
                                AND tanggal_efektIF < :tanggal_akhir
                                AND is_active = 1
                        ) AS subquery
                        WHERE row_num = 1
                    ) t_jabatan ON t_jabatan.id_hevxxmh = c.id_hevxxmh

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
                                    TIMESTAMPDIFF(MONTH, a.tanggal_masuk, "2025-01-22") / 12,
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
                                tanggal_efektIF,
                                nominal,
                                tahun_min,
                                tahun_max,
                                ROW_NUMBER() OVER (PARTITION BY id_hevgrmh ORDER BY tanggal_efektIF DESC) AS row_num
                            FROM htpr_hevgrmh_mk
                            WHERE
                                id_hpcxxmh = 31
                                AND tanggal_efektIF < :tanggal_akhir
                                AND is_active = 1
                        ) AS masakerja ON masakerja.id_hevgrmh = job.id_hevgrmh
                        WHERE IF(masakerja.tahun_max > 0, job.masa_kerja_year BETWEEN tahun_min AND tahun_max, job.masa_kerja_year > masakerja.tahun_min)
                        GROUP BY job.id_hemxxmh
                    ) AS mk ON mk.id_hemxxmh = a.id_hemxxmh
                    
                    -- Sisa Cuti
                    LEFT JOIN (
                        SELECT
                            a.id_hemxxmh,
                            peg.kode,
                            peg.nama,
                            COALESCE(cb.c_cb, 0) AS c_cb,
                            IFNULL(c_rd, 0) AS c_rd,
                            IFNULL(a.saldo,0) AS saldo,
                            
                            -- SUM(
                                CASE
                                    WHEN IFNULL(a.saldo, 0) > 0 THEN IFNULL(a.saldo, 0) - (COALESCE(cb.c_cb, 0))
                                    ELSE 0
                                END
                            -- ) 
                            AS sisa_cuti
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
                    AND a.is_approve = 1
                    AND id_heyxxmd <> 2
                    AND a.id_hemxxmh NOT IN (
                        SELECT
                            rd.id_hemxxmh
                        FROM hemjbrd AS rd
                        LEFT JOIN hemjbmh AS mh ON mh.id_hemxxmh = rd.id_hemxxmh
                        WHERE rd.id_harxxmh IN (3, 4) AND mh.id_heyxxmd = 3 AND rd.tanggal_akhir BETWEEN DATE_ADD(:tanggal_akhir, INTERVAL 1 DAY) AND LAST_DAY(:tanggal_akhir)
                        GROUP BY id_hemxxmh
                    )
        ');

        $qu_hpyxxth_cuti = $db
            ->query('update', 'hpyxxth_cuti')
            ->set('generated_on',$timestamp)
            ->where('id',$id_hpyxxth_cuti)
        ->exec();
        
        $db->commit();

        $akhir = new Carbon();

        $data = array(
            'message' => 'Kompensasi Sisa Cuti Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik', 
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