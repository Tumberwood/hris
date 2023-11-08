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
    $berhasil = 0;
    $awal = new Carbon();

    $tanggal_awal_select = new Carbon($_POST['tanggal_awal']); //gunakan carbon untuk ambil data tanggal
    $tanggal_awal = $tanggal_awal_select->format('Y-m-d'); //format jadi 2023-09-12

    $tanggal_akhir_select = new Carbon($_POST['tanggal_akhir']); //gunakan carbon untuk ambil data tanggal
    $tanggal_akhir = $tanggal_akhir_select->format('Y-m-d'); //format jadi 2023-09-12

    if (!empty($_POST['dari_tanggal'])) {
        $dari_tanggal_select = new Carbon($_POST['dari_tanggal']); //gunakan carbon untuk ambil data tanggal
        $dari_tanggal = $dari_tanggal_select->format('Y-m-d'); //format jadi 2023-09-12
        
    } else {
        $dari_tanggal = null;
    }
    $id_hgsptth_v3     = $_POST['id_hgsptth_v3'];

    $timestamp     = $_POST['timestamp']; //tambah timestamp untuk view

    try{
        $db->transaction();
        $qs_find_old_gen = $db
            ->raw()
            ->bind(':dari_tanggal', $dari_tanggal)
            ->exec(' SELECT
                            id
                    FROM hgsptth_v3 AS a
                    WHERE tanggal_awal = :dari_tanggal
                    '
                    );
        $rs_find_old_gen = $qs_find_old_gen->fetch();

        if (!empty($rs_find_old_gen)) {
            
            $qd_jadwal = $db
            ->raw()
            ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec('UPDATE htssctd AS b
                    LEFT JOIN hgsemtd_v3 AS a ON a.id_hemxxmh = b.id_hemxxmh
                    SET 
                        b.is_active = 0
                    WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir AND a.id_hgsptth_v3 = :id_hgsptth_v3
                    AND CASE
                    WHEN a.nama = "sabtu" THEN DAYOFWEEK(tanggal) = 7
                    WHEN a.nama = "minggu" THEN DAYOFWEEK(tanggal) = 1
                    ELSE DAYOFWEEK(tanggal) BETWEEN 2 AND 6
                    END;
                    '
            );

            $qd_detail = $db
            ->raw()
            ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
            ->exec('DELETE FROM hgsemtd_v3 WHERE id_hgsptth_v3 = :id_hgsptth_v3;
                    '
            );

            // insert detail SHIFT ==> NS dan OFF
            $qs_peg = $db
            ->raw()
            ->bind(':dari_tanggal', $dari_tanggal)
            ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
            ->exec('INSERT INTO hgsemtd_v3
                    (
                        id_hgsptth_v3,
                        id_htsptth_v3,
                        id_hemxxmh,
                        id_holxxmd,
                        id_htsxxmh,
                        nama,
                        shift
                    )
                    SELECT
                        :id_hgsptth_v3,
                        b.id_htsptth_v3,
                        b.id_hemxxmh,
                        b.id_holxxmd,
                        b.id_htsxxmh,
                        b.nama,
                        b.shift
                    FROM hgsptth_v3 AS a
                    LEFT JOIN hgsemtd_v3 AS b ON b.id_hgsptth_v3 = a.id
                    WHERE a.tanggal_awal = :dari_tanggal AND a.is_active = 1 AND (b.id_htsxxmh = 1 OR b.shift = 5)
                
            ');

            // insert detail SHIFT ==> !NS dan !OFF
            $qs_peg = $db
            ->raw()
            ->bind(':dari_tanggal', $dari_tanggal)
            ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
            ->exec('INSERT INTO hgsemtd_v3
                    (
                        id_hgsptth_v3,
                        id_htsptth_v3,
                        id_hemxxmh,
                        id_holxxmd,
                        id_htsxxmh,
                        nama,
                        shift
                    )
                    WITH jadwal AS (
                        SELECT
                            gs.id,
	                        max(ps.shift) AS shift_max,
                            (
                                SELECT id_htsxxmh
                                FROM htspttd_v3
                                WHERE id_htsptth_v3 = gs.id
                                ORDER BY shift DESC
                                LIMIT 1
                            ) AS jadwal_max
                        FROM htspttd_v3 AS ps
                        LEFT JOIN htsptth_v3 AS gs ON gs.id = ps.id_htsptth_v3
                        GROUP BY gs.id
                    )
                    SELECT
                        :id_hgsptth_v3,
                        b.id_htsptth_v3,
                        b.id_hemxxmh,
                        b.id_holxxmd,
                        if(b.nama = "minggu", b.id_htsxxmh, ifnull(c.id_htsxxmh, jadwal_max) ) AS id_htsxxmh,
                        b.nama,
                        if(b.nama = "minggu", b.shift, if(b.shift - 1 = 0, shift_max, b.shift - 1) ) AS shift
                    FROM hgsptth_v3 AS a
                    LEFT JOIN hgsemtd_v3 AS b ON b.id_hgsptth_v3 = a.id
                    LEFT JOIN (
                        SELECT 
                            grup.id,
                            det.id_htsxxmh,
                            det.shift
                        FROM htsptth_v3 AS grup
                        LEFT JOIN htspttd_v3 AS det ON det.id_htsptth_v3 = grup.id
                    ) AS c ON c.id = b.id_htsptth_v3 AND c.shift = b.shift - 1
                    LEFT JOIN jadwal ON jadwal.id = b.id_htsptth_v3
                    WHERE a.tanggal_awal = :dari_tanggal AND a.is_active = 1 AND (b.id_htsxxmh <> 1 AND b.shift <> 5)
                
            ');
    
            $qi_jadwal = $db
            ->raw()
            ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->exec(' INSERT INTO htssctd
                    (
                        tanggal,
                        id_hemxxmh,
                        id_htsxxmh,
                        jam_awal,
                        jam_akhir,
                        jam_awal_istirahat,
                        jam_akhir_istirahat,
                        menit_toleransi_awal_in,
                        menit_toleransi_akhir_in,
                        menit_toleransi_awal_out,
                        menit_toleransi_akhir_out,
    
                        tanggaljam_awal_t1,
                        tanggaljam_awal,
                        tanggaljam_awal_t2,
                        tanggaljam_akhir_t1,
                        tanggaljam_akhir,
                        tanggaljam_akhir_t2,
                        tanggaljam_awal_istirahat,
                        tanggaljam_akhir_istirahat
                    )
                    WITH RECURSIVE date_range AS (
                        SELECT DATE(:tanggal_awal) AS sabtu
                        UNION ALL
                        SELECT DATE_ADD(sabtu, INTERVAL 1 DAY)
                        FROM date_range
                        WHERE sabtu < :tanggal_akhir
                    )
                    SELECT DISTINCT
                        c.sabtu,
                        a.id_hemxxmh,
                        a.id_htsxxmh,
                        d.jam_awal,
                        d.jam_akhir,
                        d.jam_awal_istirahat,
                        d.jam_akhir_istirahat,
                        d.menit_toleransi_awal_in,
                        d.menit_toleransi_awal_out,
                        d.menit_toleransi_akhir_in,
                        d.menit_toleransi_akhir_out,
                        concat(c.sabtu, " ", TIME(DATE_SUB(d.jam_awal, INTERVAL d.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        concat(c.sabtu, " ", d.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(c.sabtu, INTERVAL 1 DAY)
                                ELSE c.sabtu
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
    
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(c.sabtu, " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_akhir)
                            ELSE CONCAT(c.sabtu, " ", d.jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR d.kode like "malam%" AND d.jam_akhir <= "12:00:00" THEN DATE_ADD(c.sabtu, INTERVAL 1 DAY)
                                ELSE c.sabtu
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_awal_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_awal_istirahat)
                            ELSE CONCAT(c.sabtu, " ", d.jam_awal_istirahat)
                        END AS tanggaljam_awal_istirahat,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_akhir_istirahat)
                            ELSE CONCAT(c.sabtu, " ", d.jam_akhir_istirahat)
                        END AS tanggaljam_akhir_istirahat
                    FROM hgsemtd_v3 AS a
                    LEFT JOIN hgsptth_v3 AS b ON b.id = a.id_hgsptth_v3
                    LEFT JOIN date_range AS c ON c.sabtu BETWEEN b.tanggal_awal AND b.tanggal_akhir
                    LEFT JOIN htsxxmh AS d ON d.id = a.id_htsxxmh
                    WHERE
                        b.id = :id_hgsptth_v3
                        AND CASE
                            WHEN a.nama = "sabtu" THEN DAYOFWEEK(c.sabtu) = 7
                            WHEN a.nama = "minggu" THEN DAYOFWEEK(c.sabtu) = 1
                            ELSE DAYOFWEEK(c.sabtu) BETWEEN 2 AND 6
                        END;
                    '
            );
            
            $qu_hgsptth_v3 = $db
                ->query('update', 'hgsptth_v3')
                ->set('generated_on',$timestamp)
                ->where('id',$id_hgsptth_v3)
            ->exec();
            $berhasil = 1;
        } else {
            $berhasil = 2;
        }
        $db->commit();

        $akhir = new Carbon();

        if ($berhasil == 1) {
            $data = array(
                'message' => 'Generate Jadwal Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik', 
                'type_message' => 'success',
                'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
            ); 
        } else if ($berhasil == 2){
            $data = array(
                'message' => 'Gagal Generate Jadwal, Hasil Pencarian untuk Inputan "Dari Tanggal" Yang Dipilih Tidak Ditemukan' , 
                'type_message' => 'danger',
                'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
            ); 
        } else {
            $data = array(
                'message' => 'Gagal Generate Jadwal' , 
                'type_message' => 'danger',
                'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
            ); 
        }
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        
    }
    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>