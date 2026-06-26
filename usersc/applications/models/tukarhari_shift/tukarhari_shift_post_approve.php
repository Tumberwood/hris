<?php 
    require_once( "../../../../users/init.php" );
	require_once( "../../../../usersc/lib/DataTables.php" );
	require_once( "../../../../usersc/helpers/datatables_fn_debug.php" );

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php
	use Carbon\Carbon;

    $state          = $_POST['state'];
    $id_transaksi_h = $_POST['id_transaksi_h'];

    try{
        $db->transaction();
        
        $qs_tukarhari_shift = $db
            ->query('select', 'tukarhari_shift' )
            ->get([
                'kode',
                'tanggal_terpilih',
                'keterangan',
                'tanggal_pengganti'
            ] )
            ->where('id', $id_transaksi_h )
        ->exec();

        $rs_tukarhari_shift = $qs_tukarhari_shift->fetch();
        
        $tanggal_terpilih = $rs_tukarhari_shift['tanggal_terpilih'];

        $tanggal_awal_select = new Carbon($tanggal_terpilih); //gunakan carbon untuk ambil data tanggal
        $tanggal_terpilih_dmy = $tanggal_awal_select->format('d M Y'); //format jadi 2023-09-12

        $keterangan = "Tukar Hari Shift - " . $rs_tukarhari_shift['kode'] . ' - ' . $tanggal_terpilih_dmy;
        $tanggal_pengganti = $rs_tukarhari_shift['tanggal_pengganti'];
        
        
        $qs_tukarhari_shift_pegawai = $db
            ->query('select', 'tukarhari_shift_pegawai' )
            ->get([
                'id_hemxxmh'
            ] )
            ->where('id_tukarhari_shift', $id_transaksi_h )
            ->where('is_active', 1 )
            ->exec();
        $rs_tukarhari_shift_pegawai = $qs_tukarhari_shift_pegawai->fetchAll();

        //add by ferry, tambahkan state, karena sebelum ditambahkan if state == 1 meskipun cancel approve tapi tetap menginsert ke database
        if($state == 1) {

            $idPegawai = array_column($rs_tukarhari_shift_pegawai, 'id_hemxxmh');
            if(count($idPegawai) > 0) {
                $idPegawaiIn = implode(',', $idPegawai);

                // BEGIN non aktif bulk
                $db->raw()->exec("
                    DELETE FROM htssctd
                    WHERE is_active = 0
                    AND tanggal IN ('$tanggal_terpilih', '$tanggal_pengganti')
                    AND id_hemxxmh IN ($idPegawaiIn)
                    AND keterangan NOT LIKE '%Cuti Bersama%'
                    AND keterangan NOT LIKE '%Public Holiday%'
                ");

                $db->raw()->exec("
                    UPDATE htssctd
                    SET 
                        is_active = 0,
                        keterangan = '$keterangan'
                    WHERE tanggal = '$tanggal_terpilih'
                    AND id_hemxxmh IN ($idPegawaiIn)
                ");
                // END non aktif bulk
                
                // Begin insert pengaju
                $qr_tanggal_terpilih = $db
                    ->raw()
                    ->bind(':tanggal_terpilih', $tanggal_terpilih)
                    ->bind(':tanggal_pengganti', $tanggal_pengganti)
                    ->exec('INSERT INTO htssctd
                        (
                            keterangan,
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
                        SELECT
                            "'.$keterangan.'",
                            :tanggal_terpilih,
                            htssctd.id_hemxxmh,
                            htssctd.id_htsxxmh,
                            htsxxmh.jam_awal,
                            htsxxmh.jam_akhir,
                            htsxxmh.jam_awal_istirahat,
                            htsxxmh.jam_akhir_istirahat,
                            htsxxmh.menit_toleransi_awal_in,
                            htsxxmh.menit_toleransi_akhir_in,
                            htsxxmh.menit_toleransi_awal_out,
                            htsxxmh.menit_toleransi_akhir_out,
                            CONCAT(:tanggal_terpilih, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                            CONCAT(:tanggal_terpilih, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY)
                                    ELSE :tanggal_terpilih
                                END,
                                " ",
                                TIME(
                                    CASE
                                        WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                            TIMEDIFF(DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                        ELSE
                                            DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE)
                                    END
                                )
                            ) AS tanggaljam_awal_t2,

                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN DATE_SUB(CONCAT(DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY), " ", TIME(htsxxmh.jam_akhir)), INTERVAL htsxxmh.menit_toleransi_awal_out MINUTE)
                                ELSE CONCAT(:tanggal_terpilih, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_awal_out MINUTE)))
                            END AS tanggaljam_akhir_t1,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
                                ELSE CONCAT(:tanggal_terpilih, " ", htsxxmh.jam_akhir)
                            END AS tanggaljam_akhir,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                        OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY)
                                    ELSE :tanggal_terpilih
                                END,
                                " ",
                                TIME(
                                    CASE
                                        WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                            TIMEDIFF(DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                        ELSE
                                            DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)
                                    END
                                )
                            ) AS tanggaljam_akhir_t2,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
                                ELSE CONCAT(:tanggal_terpilih, " ", htsxxmh.jam_awal_istirahat)
                            END AS tanggaljam_awal_istirahat,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_terpilih, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
                                ELSE CONCAT(:tanggal_terpilih, " ", htsxxmh.jam_akhir_istirahat)
                            END AS tanggaljam_akhir_istirahat
                        FROM htssctd
                        LEFT JOIN htsxxmh ON htsxxmh.id = htssctd.id_htsxxmh
                        WHERE 
                            htssctd.tanggal = :tanggal_pengganti
                            AND htssctd.is_active = 1
                            AND htssctd.id_hemxxmh IN ('.$idPegawaiIn.')
                            AND htssctd.id = (
                                SELECT MAX(b.id)
                                FROM htssctd b
                                WHERE b.tanggal = htssctd.tanggal
                                    AND b.id_hemxxmh = htssctd.id_hemxxmh
                                    AND b.is_active = 0
                            )
                    ');
                // END insert pengaju
                
                //TANGGAL PENGGANTI Jadi tanggal pengaju
                $db->raw()->exec("
                    UPDATE htssctd
                    SET 
                        is_active = 0,
                        keterangan = '$keterangan'
                    WHERE tanggal = '$tanggal_pengganti'
                    AND id_hemxxmh IN ($idPegawaiIn)
                ");
                
                // Begin insert pengaju
                $qr_tanggal_pengganti = $db
                    ->raw()
                    ->bind(':tanggal_terpilih', $tanggal_terpilih)
                    ->bind(':tanggal_pengganti', $tanggal_pengganti)
                    ->exec('INSERT INTO htssctd
                        (
                            keterangan,
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
                        SELECT
                            "'.$keterangan.'",
                            :tanggal_pengganti,
                            htssctd.id_hemxxmh,
                            htssctd.id_htsxxmh,
                            htsxxmh.jam_awal,
                            htsxxmh.jam_akhir,
                            htsxxmh.jam_awal_istirahat,
                            htsxxmh.jam_akhir_istirahat,
                            htsxxmh.menit_toleransi_awal_in,
                            htsxxmh.menit_toleransi_akhir_in,
                            htsxxmh.menit_toleransi_awal_out,
                            htsxxmh.menit_toleransi_akhir_out,
                            CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                            CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal) AS tanggaljam_awal,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                    ELSE :tanggal_pengganti
                                END,
                                " ",
                                TIME(
                                    CASE
                                        WHEN DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                            TIMEDIFF(DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                        ELSE
                                            DATE_ADD(htsxxmh.jam_awal, INTERVAL htsxxmh.menit_toleransi_akhir_in MINUTE)
                                    END
                                )
                            ) AS tanggaljam_awal_t2,

                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN DATE_SUB(CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", TIME(htsxxmh.jam_akhir)), INTERVAL htsxxmh.menit_toleransi_awal_out MINUTE)
                                ELSE CONCAT(:tanggal_pengganti, " ", TIME(DATE_SUB(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_awal_out MINUTE)))
                            END AS tanggaljam_akhir_t1,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir)
                                ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir)
                            END AS tanggaljam_akhir,
                            CONCAT(
                                CASE
                                    WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                        OR htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir <= "12:00:00" THEN DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY)
                                    ELSE :tanggal_pengganti
                                END,
                                " ",
                                TIME(
                                    CASE
                                        WHEN DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                            TIMEDIFF(DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                        ELSE
                                            DATE_ADD(htsxxmh.jam_akhir, INTERVAL htsxxmh.menit_toleransi_akhir_out MINUTE)
                                    END
                                )
                            ) AS tanggaljam_akhir_t2,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
                                ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_awal_istirahat)
                            END AS tanggaljam_awal_istirahat,
                            CASE
                                WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "12:00:00"
                                THEN CONCAT(DATE_ADD(:tanggal_pengganti, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
                                ELSE CONCAT(:tanggal_pengganti, " ", htsxxmh.jam_akhir_istirahat)
                            END AS tanggaljam_akhir_istirahat
                        FROM htssctd
                        LEFT JOIN htsxxmh ON htsxxmh.id = htssctd.id_htsxxmh
                        WHERE 
                            htssctd.tanggal = :tanggal_terpilih
                        AND htssctd.is_active = 0
                        AND htssctd.id_hemxxmh IN ('.$idPegawaiIn.')
                        AND htssctd.id = (
                            SELECT MAX(b.id)
                            FROM htssctd b
                            WHERE b.tanggal = htssctd.tanggal
                                AND b.id_hemxxmh = htssctd.id_hemxxmh
                                AND b.is_active = 0
                        )
                    ');
                // END insert pengaju
            }
            
        } else {

            // ambil semua id pegawai
            $idPegawai = array_column($rs_tukarhari_shift_pegawai, 'id_hemxxmh');

            if(count($idPegawai) > 0) {

                $idPegawaiIn = implode(',', $idPegawai);

                // hapus aktif tanggal terpilih
                $db->raw()->exec("
                    DELETE FROM htssctd
                    WHERE is_active = 1
                    AND tanggal = '$tanggal_terpilih'
                    AND id_hemxxmh IN ($idPegawaiIn)
                ");

                // aktifkan kembali tanggal terpilih
                $db->raw()->exec("
                    UPDATE htssctd a
                    JOIN (
                        SELECT MAX(id) AS id
                        FROM htssctd
                        WHERE is_active = 0
                        AND tanggal = '$tanggal_terpilih'
                        AND id_hemxxmh IN ($idPegawaiIn)
                        GROUP BY id_hemxxmh
                    ) b ON a.id = b.id
                    SET a.is_active = 1
                ");

                // hapus aktif tanggal pengganti
                $db->raw()->exec("
                    DELETE FROM htssctd
                    WHERE is_active = 1
                    AND tanggal = '$tanggal_pengganti'
                    AND id_hemxxmh IN ($idPegawaiIn)
                ");

                // aktifkan kembali tanggal pengganti
                $db->raw()->exec("
                    UPDATE htssctd a
                    JOIN (
                        SELECT MAX(id) AS id
                        FROM htssctd
                        WHERE is_active = 0
                        AND tanggal = '$tanggal_pengganti'
                        AND id_hemxxmh IN ($idPegawaiIn)
                        GROUP BY id_hemxxmh
                    ) b ON a.id = b.id
                    SET a.is_active = 1
                ");
            }
        }
        
        
    
        $db->commit();
        $data = array(
            'message'=> 'Perubahan Jadwal Berhasil Dibuat' , 
            'type_message'=>'success' 
        );
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'Perubahan Jadwal Gagal Dibuat!', 
            'qr_htssctd_pengaju'=>'danger' 
        );
    }    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>