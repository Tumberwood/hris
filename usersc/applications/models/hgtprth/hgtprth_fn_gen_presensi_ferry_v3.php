<?php 
    /**
     * Digunakan untuk melakukan perhitungan presensi karyawan
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
    
    //tanggal
    $tanggal_select = new Carbon($_POST['tanggal_select']); //gunakan carbon untuk ambil data tanggal
    $tanggal = $tanggal_select->format('Y-m-d'); //format jadi 2023-09-12

    // outsourcing or organik
    $id_heyxxmh     = $_POST['id_heyxxmh_select'];
    $timestamp     = $_POST['timestamp']; //tambah timestamp untuk view
    $id_hgtprth     = $_POST['id_hgtprth']; //tambah timestamp untuk view
    // print_r($timestamp);

    // INSERT DATA BACKUP / OLD
    $qi_htsprrd = $db
        ->raw()
        ->bind(':tanggal', $tanggal)
        ->bind(':id_heyxxmh', $id_heyxxmh)
        ->exec(' INSERT INTO htsprrd_old (
                    id_hemxxmh,
                    keterangan,
                    kode_finger,
                    tanggal,
                    shift_in,
                    shift_out,
                    st_jadwal,
                    tanggaljam_awal_t1,
                    tanggaljam_awal,
                    tanggaljam_awal_t2,
                    tanggaljam_akhir_t1,
                    tanggaljam_akhir,
                    tanggaljam_akhir_t2,
                    clock_in,
                    clock_out,
                    st_clock_in,
                    st_clock_out,
                    status_presensi_in,
                    status_presensi_out,
                    htlxxrh_kode,
                    jam_awal_lembur_libur,
                    jam_akhir_lembur_libur,
                    durasi_lembur_libur,
                    jam_awal_lembur_awal,
                    jam_akhir_lembur_awal,
                    durasi_lembur_awal,
                    jam_awal_lembur_akhir,
                    jam_akhir_lembur_akhir,
                    durasi_lembur_akhir,
                    jam_awal_lembur_istirahat1,
                    jam_akhir_lembur_istirahat1,
                    durasi_lembur_istirahat1,
                    jam_awal_lembur_istirahat2,
                    jam_akhir_lembur_istirahat2,
                    durasi_lembur_istirahat2,
                    jam_awal_lembur_istirahat3,
                    jam_akhir_lembur_istirahat3,
                    durasi_lembur_istirahat3,
                    durasi_lembur_total_jam,
                    pot_jam,
                    pot_overtime,
                    pot_hk,
                    pot_ti,
                    durasi_lembur_final,
                    pot_jam_final,
                    is_makan,
                    is_pot_premi,
                    is_pot_upah,
                    cek,
                    lembur15,
                    rp_lembur15,
                    lembur15_final,
                    lembur2,
                    rp_lembur2,
                    lembur2_final,
                    lembur3,
                    rp_lembur3,
                    lembur3_final,
                    lembur4,
                    rp_lembur4,
                    lembur4_final,
                    nominal_lembur_jam,
                    grup_hk,
                    break_in,
                    break_out
                )
                SELECT
                    id_hemxxmh,
                    keterangan,
                    kode_finger,
                    tanggal,
                    shift_in,
                    shift_out,
                    st_jadwal,
                    tanggaljam_awal_t1,
                    tanggaljam_awal,
                    tanggaljam_awal_t2,
                    tanggaljam_akhir_t1,
                    tanggaljam_akhir,
                    tanggaljam_akhir_t2,
                    clock_in,
                    clock_out,
                    st_clock_in,
                    st_clock_out,
                    status_presensi_in,
                    status_presensi_out,
                    htlxxrh_kode,
                    jam_awal_lembur_libur,
                    jam_akhir_lembur_libur,
                    durasi_lembur_libur,
                    jam_awal_lembur_awal,
                    jam_akhir_lembur_awal,
                    durasi_lembur_awal,
                    jam_awal_lembur_akhir,
                    jam_akhir_lembur_akhir,
                    durasi_lembur_akhir,
                    jam_awal_lembur_istirahat1,
                    jam_akhir_lembur_istirahat1,
                    durasi_lembur_istirahat1,
                    jam_awal_lembur_istirahat2,
                    jam_akhir_lembur_istirahat2,
                    durasi_lembur_istirahat2,
                    jam_awal_lembur_istirahat3,
                    jam_akhir_lembur_istirahat3,
                    durasi_lembur_istirahat3,
                    durasi_lembur_total_jam,
                    pot_jam,
                    pot_overtime,
                    pot_hk,
                    pot_ti,
                    durasi_lembur_final,
                    pot_jam_final,
                    is_makan,
                    is_pot_premi,
                    is_pot_upah,
                    cek,
                    lembur15,
                    rp_lembur15,
                    lembur15_final,
                    lembur2,
                    rp_lembur2,
                    lembur2_final,
                    lembur3,
                    rp_lembur3,
                    lembur3_final,
                    lembur4,
                    rp_lembur4,
                    lembur4_final,
                    nominal_lembur_jam,
                    grup_hk,
                    break_in,
                    break_out
                FROM htsprrd
                WHERE tanggal = :tanggal AND 
                    id_hemxxmh IN (
                        SELECT hemxxmh.id
                        FROM hemxxmh
                        LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = hemxxmh.id
                        WHERE hemjbmh.id_heyxxmh = :id_heyxxmh
                    )
                '
    );

    // BEGIN delete old data
    $qd_htsprrd = $db
        ->query('delete', 'htsprrd')
        ->where('htsprrd.tanggal',$tanggal)
        ->where( 'htsprrd.id_hemxxmh', '(SELECT hemxxmh.id FROM hemxxmh LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = hemxxmh.id WHERE hemjbmh.id_heyxxmh = ' . $id_heyxxmh . ')', 'IN', false )
        ->exec();
    // END delete old data
    
    //STEP 0 PILIH PEGAWAI AKTIF
    $qs_hemxxmh = $db
        ->query('select', 'hemxxmh' )
        ->get([
            'hemxxmh.id as id_hemxxmh',
            'hemxxmh.is_pot_makan as is_pot_makan',
            'hemxxmh.kode_finger as kode_finger',
            'hemjbmh.grup_hk as grup_hk',
            'hemjbmh.id_heyxxmd as id_heyxxmd',
            'hemjbmh.jumlah_grup as jumlah_grup',
            'hemjbmh.id_hesxxmh as id_hesxxmh'
        ] )
        ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
        ->where( function ( $q ) use ($tanggal) {
            $q
              ->where( 'hemjbmh.tanggal_keluar', null)
              ->or_where( 'hemjbmh.tanggal_keluar', $tanggal , '>=' ); //revisi dari < menjadi >
        } )
        ->where('hemjbmh.tanggal_masuk', $tanggal, '<=' )
        ->where('hemxxmh.is_active', 1 )
        ->where('hemjbmh.is_checkclock', 1 ) // skip yang tidak perlu checkclock
        ->where('hemjbmh.id_heyxxmh', $id_heyxxmh ) // skip yang tidak perlu checkclock
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    $id_hemxxmh_values = array_column($rs_hemxxmh, 'id_hemxxmh');
    $id_hemxxmh_str = implode(',', $id_hemxxmh_values);
    $id_hemxxmh = "(".$id_hemxxmh_str.")";

    try{
        $db->transaction();

        //CEK JIKA ADA KARYAWAN AKTIF
        if (!empty($rs_hemxxmh)){
            
            $qi_htsprrd_new = $db
                ->raw()
                ->bind(':tanggal', $tanggal)
                ->exec(' INSERT INTO htsprrd (
                            id_hemxxmh,
                            keterangan,
                            kode_finger,
                            tanggal,
                            shift_in,
                            shift_out,
                            st_jadwal,
                            tanggaljam_awal_t1,
                            tanggaljam_awal,
                            tanggaljam_awal_t2,
                            tanggaljam_akhir_t1,
                            tanggaljam_akhir,
                            tanggaljam_akhir_t2,
                            clock_in,
                            clock_out,
                            st_clock_in,
                            st_clock_out,
                            status_presensi_in,
                            status_presensi_out,
                            htlxxrh_kode,
                            jam_awal_lembur_libur,
                            jam_akhir_lembur_libur,
                            durasi_lembur_libur,
                            jam_awal_lembur_awal,
                            jam_akhir_lembur_awal,
                            durasi_lembur_awal,
                            jam_awal_lembur_akhir,
                            jam_akhir_lembur_akhir,
                            durasi_lembur_akhir,
                            jam_awal_lembur_istirahat1,
                            jam_akhir_lembur_istirahat1,
                            durasi_lembur_istirahat1,
                            jam_awal_lembur_istirahat2,
                            jam_akhir_lembur_istirahat2,
                            durasi_lembur_istirahat2,
                            jam_awal_lembur_istirahat3,
                            jam_akhir_lembur_istirahat3,
                            durasi_lembur_istirahat3,
                            durasi_lembur_total_jam,
                            pot_jam,
                            pot_overtime,
                            pot_hk,
                            pot_ti,
                            durasi_lembur_final,
                            pot_jam_final,
                            is_makan,
                            is_pot_premi,
                            is_pot_upah,
                            cek,
                            lembur15,
                            rp_lembur15,
                            lembur15_final,
                            lembur2,
                            rp_lembur2,
                            lembur2_final,
                            lembur3,
                            rp_lembur3,
                            lembur3_final,
                            nominal_lembur_jam,
                            grup_hk,
                            break_in,
                            break_out
                        )
                        WITH presensi AS (
                            SELECT
                                b.id_hemxxmh,
                                a.kode AS nik,
                                a.nama AS peg,
                                is_pot_makan,
                                kode_finger,
                                b.grup_hk,
                                
                                b.id_heyxxmd,
                                IF(b.jumlah_grup = 1, 3, 4) AS jumlah_grup,
                                b.id_hesxxmh,
                                COALESCE(kode_shift, "NJ") AS kode_shift,
                                jadwal.tanggal AS tanggal,
                                tanggaljam_awal,
                                tanggaljam_akhir_istirahat,
                                tanggaljam_akhir_toleransi,
                                tanggaljam_awal_toleransi,
                                tanggaljam_akhir_toleransi_min1jam,
                                tanggaljam_awal_istirahat,
                                jadwal.tanggaljam_akhir,
                                tanggaljam_akhir_min1,
                                tanggaljam_akhir_t2_min_hour,
                                ceklok_in,
                                if(DAYNAME(:tanggal) = "saturday", 1, 0) AS is_sabtu,
                                ceklok_out,
                                
                                -- cek check in
                                if(ceklok_in BETWEEN tanggaljam_awal_t1 AND tanggaljam_awal_toleransi, "OK", 
                                    if(ceklok_in BETWEEN tanggaljam_awal_toleransi AND tanggaljam_akhir_toleransi, "Late 1", 
                                        if(ceklok_in BETWEEN tanggaljam_akhir_toleransi AND tanggaljam_awal_t2, 
                                            "Late", 
                                            -- IF(jadwal.id_htsxxmh = 1, 
                                            --     "OFF",
                                                "No CI"
                                            -- )
                                        )
                                    ) 
                                ) AS st_clock_in,

                                -- cek check out
                                if(ceklok_out BETWEEN tanggaljam_akhir AND tanggaljam_akhir_t2, "OK", 
                                    if(ceklok_out BETWEEN tanggaljam_akhir_t1 AND tanggaljam_akhir, 
                                        "Early", 
                                        -- IF(jadwal.id_htsxxmh = 1, 
                                        --     "OFF",
                                            "No CO"
                                        -- )
                                    )
                                ) AS st_clock_out,
                                
                                -- kode absen akan muncul hanya yang sudah di approve
                                if(absen.htlxxmh_kode is NULL, 
                                    NULL, 
                                    IF(absen.is_approve = 1, absen.htlxxmh_kode, "Absen Belum Disetujui")
                                ) AS kode_absen,
                                    
                                    -- ket_absen concat kode absen
                                CONCAT(absen.htlxxmh_kode, " [", absen.htlxxrh_kode, "] ") AS ket_absen,
                                
                                if(absen.is_potongupah = 1, 1, 0) is_pot_upah_absen,
                                if(absen.is_potong_premi = 1, 1, 0) is_pot_premi_absen,
                                jadwal.id_htsxxmh,
                                is_potongupah,

                                -- ket_izin concat kode izin
                                CONCAT(izin_in.htlxxmh_kode, " [", izin_in.htlxxrh_kode, "] ") AS ket_izin_in,
                                -- kode izin_in akan muncul hanya yang sudah di approve
                                if(izin_in.htlxxmh_kode is NULL, 
                                    NULL, 
                                    IF(izin_in.is_approve = 1, izin_in.htlxxmh_kode, "Izin Belum Disetujui")
                                ) AS kode_izin_in,

                                tanggal_jam_izin_awal_in,
                                if(izin_in.is_potong_premi = 1, 1, 0) AS is_pot_premi_izin_in,
                                -- ket_izin concat kode izin
                                CONCAT(izin_out.htlxxmh_kode, " [", izin_out.htlxxrh_kode, "] ") AS ket_izin_out,
                                -- kode izin_out akan muncul hanya yang sudah di approve
                                if(izin_out.htlxxmh_kode is NULL, 
                                    NULL, 
                                    IF(izin_out.is_approve = 1, izin_out.htlxxmh_kode, "Izin Belum Disetujui")
                                ) AS kode_izin_out,

                                tanggal_jam_izin_akhir_out,
                                if(izin_out.is_potong_premi = 1, 1, 0) AS is_pot_premi_izin_out,
                                jam_akhir_izin_out,
                                is_potong_gaji_in,
                                is_potong_gaji_out,

                                -- ket_izin concat kode izin
                                CONCAT(izin_mid.htlxxmh_kode, " [", izin_mid.htlxxrh_kode, "] ") AS ket_izin_mid,
                                -- kode izin_mid akan muncul hanya yang sudah di approve
                                if(izin_mid.htlxxmh_kode is NULL, 
                                    NULL, 
                                    IF(izin_mid.is_approve = 1, izin_mid.htlxxmh_kode, "Izin Belum Disetujui")
                                ) AS kode_izin_mid,
                                COALESCE(is_potong_premi_mid, 0) AS is_potong_premi_mid,

                                tanggal_jam_izin_akhir_mid,
                                tanggal_jam_izin_awal_mid,
                                jam_akhir_izin_mid,
                                jam_awal_izin_mid,
                                is_potong_gaji_mid,
                                ceklok_luar,
                                IF(b.id_heyxxmd = 4,
                                    COALESCE(id_tukar_jadwal, 0),
                                    0
                                ) AS id_tukar_jadwal,
                                is_pot_ti,
                                durasi_break_menit,
                                COALESCE(potongan_ti_jam, 0) potongan_ti_jam,

                                jam_awal_lembur_awal,
                                jam_akhir_lembur_awal,
                                COALESCE(durasi_lembur_awal_jam, 0) AS durasi_lembur_awal_jam,

                                jam_awal_lembur_akhir,
                                jam_akhir_lembur_akhir,
                                COALESCE(durasi_lembur_akhir_jam, 0) AS durasi_lembur_akhir_jam,

                                jam_awal_lembur_libur,
                                jam_akhir_lembur_libur,
                                COALESCE(durasi_lembur_libur_jam, 0) AS durasi_lembur_libur_jam,

                                jam_awal_lembur_istirahat1,
                                jam_akhir_lembur_istirahat1,
                                COALESCE(durasi_lembur_istirahat1_jam, 0) AS durasi_lembur_istirahat1_jam,

                                jam_awal_lembur_istirahat2,
                                jam_akhir_lembur_istirahat2,
                                COALESCE(durasi_lembur_istirahat2_jam, 0) AS durasi_lembur_istirahat2_jam,

                                jam_awal_lembur_istirahat3,
                                jam_akhir_lembur_istirahat3,
                                COALESCE(durasi_lembur_istirahat3_jam, 0) AS durasi_lembur_istirahat3_jam,
                                
                                COALESCE(durasi_lembur_istirahat1_jam, 0) + COALESCE(durasi_lembur_istirahat2_jam, 0) + COALESCE(durasi_lembur_istirahat3_jam, 0) AS durasi_lembur_ti,
                                COALESCE(durasi_lembur_awal_jam, 0) + COALESCE(durasi_lembur_akhir_jam, 0) + COALESCE(durasi_lembur_libur_jam, 0) AS durasi_lembur_non_ti,
                                COALESCE(durasi_lembur_awal_jam, 0) + COALESCE(durasi_lembur_akhir_jam, 0) + COALESCE(durasi_lembur_libur_jam, 0) + COALESCE(durasi_lembur_istirahat1_jam, 0) + COALESCE(durasi_lembur_istirahat2_jam, 0) + COALESCE(durasi_lembur_istirahat3_jam, 0) AS durasi_lembur_total_jam,

                                (FLOOR(IF(b.id_hesxxmh = 3, COALESCE(nominal_lembur_mati, 0), (COALESCE(nominal_gp, 0) + COALESCE(nominal_t_jab, 0)) / 173))) AS nominal_lembur_jam,
                                jadwal.jam_awal AS shift_in,
                                jadwal.jam_akhir AS shift_out,
                                jadwal.tanggaljam_awal_t1,
                                jadwal.tanggaljam_awal_t2,  
                                jadwal.tanggaljam_akhir_t1,
                                jadwal.tanggaljam_akhir_t2, 
                                IF(a.is_pot_makan = 1 AND ceklok_makan > 0, ceklok_makan, 0) AS is_makan,
                                pot_jam_keluar_istirahat,
                                break_in,
                                break_out,
                                tanggaljam_awal_toleransi_lembur,
                                jam_awal_lembur,
                                jam_akhir_schedule

                            FROM hemxxmh AS a
                            INNER JOIN hemjbmh AS b ON b.id_hemxxmh = a.id
                            LEFT JOIN (
                                SELECT
                                    jad.*,
                                    sft.kode AS kode_shift,
	                                DATE_FORMAT(jad.tanggaljam_akhir, "%H:%i") jam_akhir_schedule,
                                    DATE_ADD(jad.tanggaljam_awal, INTERVAL 5 MINUTE) AS tanggaljam_akhir_toleransi, -- tolreansi awal 5 menit
                                    DATE_SUB(jad.tanggaljam_awal, INTERVAL 5 MINUTE) AS tanggaljam_awal_toleransi, -- toleransi akhir 5 menit
                                    DATE_ADD(jad.tanggaljam_awal, INTERVAL 65 MINUTE) AS tanggaljam_akhir_toleransi_min1jam, -- keperluan toleransi terlambat Late +1 jam 
                                    DATE_SUB(jad.tanggaljam_akhir , INTERVAL 60 MINUTE) AS tanggaljam_akhir_min1, -- keperluan early
                                    DATE_SUB(jad.tanggaljam_akhir_t2 , INTERVAL 60 MINUTE) AS tanggaljam_akhir_t2_min_hour -- untuk is_makan akhir t2 - 1 jam
                                    
                                FROM htssctd AS jad
                                INNER JOIN htsxxmh AS sft ON sft.id = jad.id_htsxxmh
                                
                                WHERE jad.is_active = 1 AND jad.tanggal = :tanggal
                            ) AS jadwal ON jadwal.id_hemxxmh =  a.id
                            
                            -- ceklok in
                            LEFT JOIN (
                                SELECT
                                    a.id,
                                    MIN(TIMESTAMP(c.tanggal, c.jam)) AS ceklok_in
                                FROM htssctd AS a
                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1 
                                    AND c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3", "pocan")
                                    AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_awal_t1 AND a.tanggaljam_awal_t2
                                AND b.id IN '.$id_hemxxmh.'                                                        
                                GROUP BY a.id
                                ORDER BY ceklok_in

                            ) AS cek_in ON cek_in.id = jadwal.id
                                
                            -- ceklok out
                            LEFT JOIN (
                                SELECT DISTINCT
                                    a.id,
                                    MAX(TIMESTAMP(c.tanggal, c.jam)) AS ceklok_out
                                FROM htssctd AS a
                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1 
                                    AND c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3", "pocan")
                                    AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_akhir_t1 AND a.tanggaljam_akhir_t2
                                    AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                GROUP BY a.id
                                ORDER BY ceklok_out DESC

                            ) AS cek_out ON cek_out.id = jadwal.id
                            
                            -- ceklok Istirahat AWAL , semua mesin dengan range istirahat sesuai jadwal.
                            LEFT JOIN (
                                SELECT
                                    a.id,
                                    MIN(TIMESTAMP(c.tanggal, c.jam)) AS break_in
                                FROM htssctd AS a
                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1 
                                    AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
                                AND b.id IN '.$id_hemxxmh.'                                                        
                                GROUP BY a.id
                                ORDER BY break_in

                            ) AS break_awal ON break_awal.id = jadwal.id
                            
                            -- ceklok Istirahat AKHIR , semua mesin dengan range istirahat sesuai jadwal.
                            LEFT JOIN (
                                SELECT
                                    a.id,
                                    MAX(TIMESTAMP(c.tanggal, c.jam)) AS break_out
                                FROM htssctd AS a
                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1 
                                    AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
                                AND b.id IN '.$id_hemxxmh.'                                                        
                                GROUP BY a.id
                                ORDER BY break_out

                            ) AS break_akhir ON break_akhir.id = jadwal.id
                            
                            -- ceklok luar range
                            LEFT JOIN (
                                SELECT DISTINCT
                                    a.id,
                                    MAX(TIMESTAMP(c.tanggal, c.jam)) AS ceklok_luar
                                FROM htssctd AS a
                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                    AND c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3", "pocan")
                                    AND TIMESTAMP(c.tanggal, c.jam) BETWEEN CONCAT(:tanggal, " 09:00:00") AND DATE_ADD(CONCAT(:tanggal, " 04:00:00"), INTERVAL 1 DAY)
                                    AND b.id IN '.$id_hemxxmh.'
                                GROUP BY a.id
                                ORDER BY ceklok_luar

                            ) AS cek_luar ON cek_luar.id = jadwal.id
                                
                            -- absen
                            LEFT JOIN (
                                SELECT
                                    htlxxrh.kode AS htlxxrh_kode,
                                    htlxxrh.id_hemxxmh,
                                    htlxxrh.is_approve AS is_approve,
                                    htlxxrh.saldo AS saldo,
                                    htlxxmh.is_potongupah AS is_potongupah,
                                    htlxxmh.is_potong_premi AS is_potong_premi,
                                    htlxxmh.kode AS htlxxmh_kode,
                                    htlgrmh.kode AS htlgrmh_kode
                                FROM htlxxrh
                                LEFT JOIN htlxxmh ON htlxxmh.id = htlxxrh.id_htlxxmh
                                LEFT JOIN htlgrmh ON htlgrmh.id = htlxxmh.id_htlgrmh
                                WHERE htlxxrh.tanggal = :tanggal
                                    AND htlxxrh.jenis = 1
                                    AND htlxxrh.id_hemxxmh IN '.$id_hemxxmh.'
                                GROUP BY htlxxrh.id_hemxxmh
                            ) AS absen ON absen.id_hemxxmh = a.id
                                
                            -- Izin IN
                            LEFT JOIN (
                                SELECT
                                    htlxxrh.id_hemxxmh,
                                    htlxxrh.id_htlxxmh,
                                    htlxxrh.kode AS htlxxrh_kode,
                                    concat(htlxxrh.tanggal, " ", htlxxrh.jam_awal) AS tanggal_jam_izin_awal_in,
                                    htlxxrh.jam_awal,
                                    htlxxrh.htlxxmh_kode AS htlxxmh_kode,
                                    htpxxmh.is_potong_gaji AS is_potong_gaji_in,
                                    htpxxmh.is_potong_premi,
                                    htlgrmh.kode AS htlgrmh_kode,
                                    htlxxrh.is_approve
                                FROM htlxxrh
                                INNER JOIN htpxxmh ON htpxxmh.id = htlxxrh.id_htlxxmh
                                LEFT JOIN htlgrmh ON htlgrmh.id = htpxxmh.id_htlgrmh
                                WHERE htlxxrh.is_active = 1
                                    AND htlxxrh.tanggal = :tanggal
                                    AND (
                                        htlxxrh.id_htlxxmh = 1 OR
                                        htlxxrh.id_htlxxmh = 5
                                    )
                                    AND htlxxrh.jenis = 2

                            ) AS izin_in ON izin_in.id_hemxxmh = a.id
                                
                            -- Izin OUT
                            LEFT JOIN (
                                SELECT
                                    htlxxrh.id_hemxxmh,
                                    htlxxrh.id_htlxxmh,
                                    htlxxrh.kode AS htlxxrh_kode,
                                    concat(htlxxrh.tanggal, " ", htlxxrh.jam_akhir) AS tanggal_jam_izin_akhir_out,
                                    htlxxrh.jam_akhir as jam_akhir_izin_out,
                                    htlxxrh.htlxxmh_kode AS htlxxmh_kode,
                                    htpxxmh.is_potong_gaji AS is_potong_gaji_out,
                                    htpxxmh.is_potong_premi,
                                    htlgrmh.kode AS htlgrmh_kode,
                                    htlxxrh.is_approve
                                FROM htlxxrh
                                INNER JOIN htpxxmh ON htpxxmh.id = htlxxrh.id_htlxxmh
                                LEFT JOIN htlgrmh ON htlgrmh.id = htpxxmh.id_htlgrmh
                                WHERE htlxxrh.is_active = 1
                                    AND htlxxrh.tanggal = :tanggal
                                    AND (
                                        htlxxrh.id_htlxxmh = 2 OR
                                        htlxxrh.id_htlxxmh = 6
                                    )
                                    AND htlxxrh.jenis = 2

                            ) AS izin_out ON izin_out.id_hemxxmh = a.id
                                
                            -- Izin mid
                            LEFT JOIN (
                                SELECT
                                    htlxxrh.id_hemxxmh,
                                    htlxxrh.id_htlxxmh,
                                    htlxxrh.kode AS htlxxrh_kode,
                                    concat(htlxxrh.tanggal, " ", htlxxrh.jam_akhir) AS tanggal_jam_izin_akhir_mid,
                                    concat(htlxxrh.tanggal, " ", htlxxrh.jam_awal) AS tanggal_jam_izin_awal_mid,
                                    htlxxrh.jam_akhir as jam_akhir_izin_mid,
                                    htlxxrh.jam_awal as jam_awal_izin_mid,
                                    htlxxrh.htlxxmh_kode AS htlxxmh_kode,
                                    htpxxmh.is_potong_gaji AS is_potong_gaji_mid,
                                    htpxxmh.is_potong_premi AS is_potong_premi_mid,
                                    htlgrmh.kode AS htlgrmh_kode,
                                    htlxxrh.is_approve
                                FROM htlxxrh
                                INNER JOIN htpxxmh ON htpxxmh.id = htlxxrh.id_htlxxmh
                                LEFT JOIN htlgrmh ON htlgrmh.id = htpxxmh.id_htlgrmh
                                WHERE htlxxrh.is_active = 1
                                    AND htlxxrh.tanggal = :tanggal
                                    AND (
                                        htlxxrh.id_htlxxmh = 3 OR
                                        htlxxrh.id_htlxxmh = 4
                                    )
                                    AND htlxxrh.jenis = 2

                            ) AS izin_mid ON izin_mid.id_hemxxmh = a.id

                            -- tukar jadwal kmj
                            LEFT JOIN (
                                SELECT
                                    a.id AS id_tukar_jadwal,
                                    a.id_hemxxmh_pengaju,
                                    a.id_hemxxmh_pengganti
                                FROM htscctd AS a
                                WHERE a.tanggal = :tanggal
                                    AND a.is_active = 1 
                                    AND a.is_approve = 1
                            ) AS tukar_jadwal_kmj ON (id_hemxxmh_pengaju = a.id OR id_hemxxmh_pengganti = a.id)

                            -- Overtime dan Pot TI
                            LEFT JOIN (
                                SELECT
                                    MAX(tanggaljam_awal_toleransi_lembur) tanggaljam_awal_toleransi_lembur,
                                    jam_awal_lembur,
                                    ot.id_hemxxmh,
                                    is_pot_ti,
                                    id_jadwal,
                                    durasi_break_menit,
                                    potongan_ti_jam,
                                    MAX(CASE WHEN ot.id_htotpmh = 1 THEN ot.jam_awal ELSE NULL END) AS jam_awal_lembur_awal,
                                    MAX(CASE WHEN ot.id_htotpmh = 1 THEN ot.jam_akhir ELSE NULL END) AS jam_akhir_lembur_awal,
                                    SUM(CASE WHEN ot.id_htotpmh = 1 THEN ot.durasi_lembur_jam ELSE 0 END) AS durasi_lembur_awal_jam,
                                    SUM(CASE WHEN ot.id_htotpmh = 1 THEN ot.durasi_lembur_menit ELSE 0 END) AS durasi_lembur_awal_menit,
                        
                                    MAX(CASE WHEN ot.id_htotpmh = 2 THEN ot.jam_awal ELSE NULL END) AS jam_awal_lembur_akhir,
                                    MAX(CASE WHEN ot.id_htotpmh = 2 THEN ot.jam_akhir ELSE NULL END) AS jam_akhir_lembur_akhir,
                                    SUM(CASE WHEN ot.id_htotpmh = 2 THEN ot.durasi_lembur_jam ELSE 0 END) AS durasi_lembur_akhir_jam,
                                    SUM(CASE WHEN ot.id_htotpmh = 2 THEN ot.durasi_lembur_menit ELSE 0 END) AS durasi_lembur_akhir_menit,
                        
                                    MAX(CASE WHEN ot.id_htotpmh = 4 THEN ot.jam_awal ELSE NULL END) AS jam_awal_lembur_libur,
                                    MAX(CASE WHEN ot.id_htotpmh = 4 THEN ot.jam_akhir ELSE NULL END) AS jam_akhir_lembur_libur,
                                    SUM(CASE WHEN ot.id_htotpmh = 4 THEN ot.durasi_lembur_jam ELSE 0 END) AS durasi_lembur_libur_jam,
                                    SUM(CASE WHEN ot.id_htotpmh = 4 THEN ot.durasi_lembur_menit ELSE 0 END) AS durasi_lembur_libur_menit,
                        
                                    MAX(CASE WHEN ot.id_htotpmh = 5 THEN ot.jam_awal ELSE NULL END) AS jam_awal_lembur_istirahat1,
                                    MAX(CASE WHEN ot.id_htotpmh = 5 THEN ot.jam_akhir ELSE NULL END) AS jam_akhir_lembur_istirahat1,
                                    SUM(CASE WHEN ot.id_htotpmh = 5 THEN ot.durasi_lembur_jam ELSE 0 END) AS durasi_lembur_istirahat1_jam,
                                    SUM(CASE WHEN ot.id_htotpmh = 5 THEN ot.durasi_lembur_menit ELSE 0 END) AS durasi_lembur_istirahat1_menit,
                        
                                    MAX(CASE WHEN ot.id_htotpmh = 6 THEN ot.jam_awal ELSE NULL END) AS jam_awal_lembur_istirahat2,
                                    MAX(CASE WHEN ot.id_htotpmh = 6 THEN ot.jam_akhir ELSE NULL END) AS jam_akhir_lembur_istirahat2,
                                    SUM(CASE WHEN ot.id_htotpmh = 6 THEN ot.durasi_lembur_jam ELSE 0 END) AS durasi_lembur_istirahat2_jam,
                                    SUM(CASE WHEN ot.id_htotpmh = 6 THEN ot.durasi_lembur_menit ELSE 0 END) AS durasi_lembur_istirahat2_menit,
                        
                                    MAX(CASE WHEN ot.id_htotpmh = 7 THEN ot.jam_awal ELSE NULL END) AS jam_awal_lembur_istirahat3,
                                    MAX(CASE WHEN ot.id_htotpmh = 7 THEN ot.jam_akhir ELSE NULL END) AS jam_akhir_lembur_istirahat3,
                                    SUM(CASE WHEN ot.id_htotpmh = 7 THEN ot.durasi_lembur_jam ELSE 0 END) AS durasi_lembur_istirahat3_jam,
                                    SUM(CASE WHEN ot.id_htotpmh = 7 THEN ot.durasi_lembur_menit ELSE 0 END) AS durasi_lembur_istirahat3_menit
                                FROM(
                                        SELECT
                                            hto.*,
                                            DATE_ADD(CONCAT(hto.tanggal, " ", hto.jam_awal), INTERVAL 5 MINUTE) tanggaljam_awal_toleransi_lembur,
                                            DATE_FORMAT(hto.jam_awal, "%H:%i") jam_awal_lembur,
                                            id_jadwal,
                                            if(is_istirahat = 2, 1, 0)  AS is_pot_ti,
                                            if(is_istirahat = 2, durasi_break_menit, 0)  AS durasi_break_menit,
                                            CASE
                                                -- [16.06, 12/6/2025] +62 895-6326-78236: Iya pak. Karena jam istirahat normal kan 1 jam. Otomatis jika>1 jam, meskipun tdk ada lembur juga tetap dipotong 1jam.
                                                WHEN durasi_break_menit > 60 THEN 1
                                                WHEN is_istirahat = 2 AND durasi_break_menit > ifnull(menit_toleransi_ti, 0) THEN 0.5
                                                
                                                -- CASE  21 Feb 2025 - 13071090 - EKO TEGUH 
                                                -- Jika shift 1 + jumat maka 90 menit batas max sebelum dipotong 1 jam, 
                                                -- jika bukan shift 1 + jumat maka 60 menit
                                                -- WHEN durasi_break_menit > 60 THEN 1
                                                ELSE 0
                                            END AS potongan_ti_jam
                                        FROM htoxxrd as hto

                                        -- ceklok ISTIRAHAT
                                        LEFT JOIN (
                                            SELECT *
                                            FROM (
                                                SELECT DISTINCT
                                                    a.id_hemxxmh,
                                                    a.id AS id_jadwal,
                                                    CONCAT(c.tanggal," ",c.jam) AS ceklok_istirahat,
                                                    
                                                    IF(DAYNAME(a.tanggal) = "Friday" AND jad.kode LIKE "%PAGI%" AND MAX(c.jam) < "13:00", 0, 
                                                        TIMESTAMPDIFF(MINUTE, MIN(CONCAT(c.tanggal," ",c.jam)), MAX(CONCAT(c.tanggal," ",c.jam)))
                                                    )
                                                    AS durasi_break_menit
                                                FROM htssctd AS a
                                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                                    AND (
                                                        (a.tanggal < "2025-04-14" AND c.nama IN ("istirahat", "istirahat manual", "os", "out", "staff", "PMI"))
                                                        OR
                                                        (a.tanggal >= "2025-04-14" AND c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3", "istirahat", "istirahat manual"))
                                                    )
                                                    AND CONCAT(c.tanggal, " ", c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
                                                    AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                                GROUP BY a.id
                                                HAVING durasi_break_menit > 0

                                                UNION ALL

                                                SELECT DISTINCT
                                                    a.id_hemxxmh,
                                                    a.id AS id_jadwal,
                                                    CONCAT(d.tanggal, " ", d.jam_awal) AS ceklok_istirahat,
                                                    TIMESTAMPDIFF(MINUTE, MIN(CONCAT(c.tanggal," ",c.jam)), MAX(CONCAT(c.tanggal," ",c.jam))) AS durasi_break_menit
                                                FROM htssctd AS a
                                                INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                                INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                                INNER JOIN htoxxrd AS d ON d.id_hemxxmh = a.id_hemxxmh AND d.tanggal = a.tanggal
                                                WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                                    AND c.nama IN ("istirahat", "istirahat manual", "os", "out", "staff", "PMI")
                                                    AND CONCAT(c.tanggal, " ", c.jam) BETWEEN CONCAT(d.tanggal, " ", d.jam_awal) 
                                                    AND CONCAT(IF(d.jam_awal > d.jam_akhir, DATE_ADD(d.tanggal, INTERVAL 1 DAY), d.tanggal), " ", d.jam_akhir)
                                                    AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                                    AND a.id_htsxxmh = 1
                                                GROUP BY a.id
                                            ) AS t
                                            ORDER BY ceklok_istirahat
                                                
                                        ) AS cek_istirahat ON cek_istirahat.id_hemxxmh = hto.id_hemxxmh

                                        -- menit_toleransi_ti settingan
                                        LEFT JOIN (
                                            SELECT
                                                is_active,
                                                tanggal_efektif,
                                                IFNULL(menit, 0) AS menit_toleransi_ti
                                            FROM (
                                                SELECT
                                                    id,
                                                    is_active,
                                                    tanggal_efektif,
                                                    menit,
                                                    ROW_NUMBER() OVER (PARTITION BY nama ORDER BY tanggal_efektif DESC) AS row_num
                                                FROM htpr_ti
                                                WHERE
                                                    htpr_ti.nama = "Toleransi TI"
                                                    AND tanggal_efektif <= :tanggal
                                            ) AS subquery
                                            WHERE row_num = 1
                                        ) menit_toleransi_ti ON menit_toleransi_ti.is_active = 1

                                        WHERE hto.tanggal = :tanggal AND hto.is_active = 1
                                        GROUP BY hto.id_htoemtd
                                    )
                                    AS ot
                                GROUP BY
                                    ot.id_hemxxmh, id_jadwal
                            ) AS lembur ON lembur.id_hemxxmh = a.id

                            -- keluar waktu istirahat
                            LEFT JOIN (
                                SELECT
                                    ot.id as id_hemxxmh,
                                    jumlah_grup,
                                    kode,
                                    nama,
                                    id_jadwal,
                                    pot_jam_keluar_istirahat,
                                    ceklok_istirahat,
                                    ceklok_makan_case_keluar_istirahat,
                                    tanggal
                                FROM(
                                        SELECT
                                            hem.*,
                                            jumlah_grup,
                                            id_jadwal,
                                            tanggal,
                                            ceklok_istirahat,
                                            ceklok_makan_case_keluar_istirahat,
                                            -- CASE
                                            --     -- apabila 4 grup sudah ada finger makan atau inputan makan manual, maka tidak boleh ada finger keluar di mesin PMI, KBM, atau Istirahat. Jika diketahui ada makan dan ada finger keluar (meskipun <30 menit), maka akan dipotong 1 jam.
                                            --     WHEN jb.jumlah_grup = 2 AND IFNULL(ceklok_makan_case_keluar_istirahat, 0) > 0 AND ceklok_istirahat IS NOT NULL THEN 1
                                 
                                            --     -- Mulai 1/3/24  toleransi istirahat TI menjadi 30 menit, bukan 20 menit lagi
                                            --     WHEN jb.jumlah_grup = 2 AND durasi_break_menit > ifnull(menit_toleransi_keluar_istirahat, 0) THEN 1
                                            --     ELSE 0
                                            -- END AS pot_jam_keluar_istirahat
                                            0 as pot_jam_keluar_istirahat
                                        FROM hemxxmh as hem
                                        INNER JOIN hemjbmh as jb on jb.id_hemxxmh = hem.id

                                        -- ceklok ISTIRAHAT
                                        LEFT JOIN (
                                            SELECT DISTINCT
                                                a.id_hemxxmh,
                                                a.jam_awal,
                                                a.id AS id_jadwal,
                                                a.tanggal,
                                                concat(c.tanggal," ",c.jam) AS ceklok_istirahat,
                                                TIMESTAMPDIFF(MINUTE,MIN(CONCAT(c.tanggal," ",c.jam)),	MAX(CONCAT(c.tanggal," ",c.jam))) as durasi_break_menit
                                            FROM htssctd AS a
                                            INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                            INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                            WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                                AND (
                                                    (a.tanggal < "2025-04-14" AND c.nama IN ("istirahat", "istirahat manual", "os", "out", "staff", "PMI"))
                                                    OR
                                                    (a.tanggal >= "2025-04-14" AND c.nama IN ("os", "out", "staff", "PMI", "PMI-Gedung-3", "OS-Gedung-3", "istirahat", "istirahat manual"))
                                                )
                                                AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_awal_istirahat AND DATE_ADD(a.tanggaljam_akhir_istirahat, INTERVAL 1 HOUR)
                                                AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                            GROUP BY a.id
                                            ORDER BY ceklok_istirahat

                                        ) AS cek_istirahat ON cek_istirahat.id_hemxxmh = hem.id

                                        -- ceklok makan
                                        LEFT JOIN (
                                            SELECT DISTINCT
                                                a.id_hemxxmh,
                                                COUNT(c.id) AS ceklok_makan_case_keluar_istirahat
                                            FROM htssctd AS a
                                            INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                            INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                            WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                                AND c.nama IN ("makan", "makan manual")
                                                AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_awal_t1 AND DATE_SUB(a.tanggaljam_akhir_t2 , INTERVAL 60 MINUTE)
                                                AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                            GROUP BY a.id

                                        ) AS cek_makan ON cek_makan.id_hemxxmh = hem.id
                                        
                                        -- menit_toleransi_keluar_istirahat settingan
                                        LEFT JOIN (
                                            SELECT
                                                is_active,
                                                tanggal_efektif,
                                                IFNULL(menit, 0) AS menit_toleransi_keluar_istirahat
                                            FROM (
                                                SELECT
                                                    id,
                                                    is_active,
                                                    tanggal_efektif,
                                                    menit,
                                                    ROW_NUMBER() OVER (PARTITION BY nama ORDER BY tanggal_efektif DESC) AS row_num
                                                FROM htpr_ti
                                                WHERE
                                                    htpr_ti.nama = "Toleransi Keluar Istirahat"
                                                    AND tanggal_efektif <= :tanggal
                                            ) AS subquery
                                            WHERE row_num = 1
                                        ) menit_toleransi_keluar_istirahat ON menit_toleransi_keluar_istirahat.is_active = 1

                                        WHERE cek_istirahat.tanggal = :tanggal AND hem.is_active = 1
                                    )
                                    AS ot
                                GROUP BY
                                    ot.id, id_jadwal
                            ) AS keluar_isirahat on keluar_isirahat.id_hemxxmh = a.id
                            
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
                                        AND tanggal_efektif < :tanggal
                                ) AS subquery
                                WHERE row_num = 1
                            ) lembur_mati ON lembur_mati.id_hesxxmh = b.id_hesxxmh

                            -- t jabatan
                            LEFT JOIN (
                                SELECT
                                    id_hevxxmh,
                                    tanggal_efektif,
                                    nominal AS nominal_t_jab
                                FROM (
                                    SELECT
                                        a.id,
                                        a.id_hevxxmh,
                                        a.tanggal_efektif,
                                        a.nominal,
                                        ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                    FROM htpr_hevxxmh AS a
                                    INNER JOIN hevxxmh AS b ON b.id = a.id_hevxxmh
                                    INNER JOIN hemjbmh AS c ON c.id_hevxxmh = b.id
                                    WHERE
                                        a.id_hpcxxmh = 32
                                        AND tanggal_efektif < :tanggal
                                ) AS subquery
                                WHERE row_num = 1
                            ) t_jabatan ON t_jabatan.id_hevxxmh = b.id_hevxxmh

                            -- gaji pokok
                            LEFT JOIN (
                                SELECT
                                    id_hemxxmh,
                                    tanggal_efektif,
                                    nominal AS nominal_gp
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
                                        AND tanggal_efektif < :tanggal
                                ) AS subquery
                                WHERE row_num = 1
                            ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = a.id

                            -- ceklok makan
                            LEFT JOIN (
                                SELECT
                                    id,
                                    COUNT(DISTINCT tanggal_jam_ceklok) AS ceklok_makan
                                FROM (
                                    SELECT DISTINCT
                                        a.id,
                                        CONCAT(c.tanggal, " ", c.jam) tanggal_jam_ceklok,
                                        c.nama,
                                        c.kode,
                                        c.jam
                                    FROM htssctd AS a
                                    INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                    INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                    WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                        AND c.nama IN ("makan", "makan manual")
                                        AND TIMESTAMP(c.tanggal, c.jam) BETWEEN a.tanggaljam_awal_t1 AND DATE_SUB(a.tanggaljam_akhir_t2 , INTERVAL 60 MINUTE)
                                        AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                    GROUP BY a.id
        
                                    -- MAKAN
                                    UNION ALL
        
                                    SELECT DISTINCT
                                        a.id,
                                        CONCAT(c.tanggal, " ", c.jam) tanggal_jam_ceklok,
                                        c.nama,
                                        c.kode,
                                        c.jam
                                    FROM htssctd AS a
                                    INNER JOIN hemxxmh AS b ON b.id = a.id_hemxxmh
                                    INNER JOIN htsprtd AS c ON c.kode = b.kode_finger
                                    INNER JOIN htoxxrd AS d ON d.id_hemxxmh = a.id_hemxxmh AND d.tanggal = a.tanggal
                                    WHERE a.tanggal = :tanggal AND a.is_active = 1 AND b.is_active = 1
                                        AND c.nama IN ("makan", "makan manual")
                                        AND CONCAT(c.tanggal, " ", c.jam) BETWEEN CONCAT(d.tanggal, " ", d.jam_awal) 
                                        AND CONCAT(IF(d.jam_awal > d.jam_akhir, DATE_ADD(d.tanggal, INTERVAL 1 DAY), d.tanggal), " ", d.jam_akhir) 
                                        AND a.id_hemxxmh IN '.$id_hemxxmh.'
                                        -- AND TIME(a.tanggaljam_awal_istirahat) = "00:00:00"
                                    GROUP BY a.id
                                ) union_makan
                                GROUP BY id
                            ) AS cek_makan ON cek_makan.id = jadwal.id

                            WHERE b.is_checkclock = 1 AND a.is_active = 1 AND b.id_hemxxmh IN '.$id_hemxxmh.' 
                            AND b.tanggal_masuk <= :tanggal AND (b.tanggal_keluar IS NULL OR b.tanggal_keluar >= :tanggal )
                        ),
                        status_presensi AS (
                            SELECT
                                *,

                                concat(ifnull(ket_absen,""), ifnull(ket_izin_in,""), ifnull(ket_izin_out,""), ifnull(ket_izin_mid, "") ) AS keterangan,
                                concat(ifnull(kode_absen,""), ifnull(kode_izin_in,""), ifnull(kode_izin_out,""), ifnull(kode_izin_mid, "") ) AS kode_kondite,

                                -- ambil ceklok atau jam izin in
                                IF(st_clock_in NOT IN ("Late 1", "OK"),
                                    -- ini nanti akan mendapatkan carbon_ci seperti di ferry2 baris  679
                                    if(kode_izin_in IS NOT NULL AND is_potong_gaji_in = 1,
                                        -- jika ada izin  
                                        if(ceklok_in IS NULL,
                                            tanggal_jam_izin_awal_in,
                                            ceklok_in
                                        ),
                                        -- jika tidak ada izin
                                        if(ceklok_in IS NULL,
                                            0,
                                            ceklok_in
                                        )
                                    ), 0
                                ) AS carbon_ci,

                                -- ambil ceklok atau jam izin out
                                IF(st_clock_out <> "OK", 
                                    -- ini nanti akan mendapatkan carbon_co seperti di ferry2 baris  679
                                    if(kode_izin_out IS NOT NULL AND is_potong_gaji_out = 1,
                                        -- jika ada izin  
                                        if(ceklok_out IS NULL,
                                            if(jam_akhir_izin_out < "04:00:00",
                                                -- jika kurang dari jam 4 maka bukan today dan harus di +1 day
                                                date_add(tanggal_jam_izin_akhir_out, INTERVAL 1 DAY),
                                                tanggal_jam_izin_akhir_out
                                            ),
                                            ceklok_out
                                        ),
                                        -- jika tidak ada izin
                                        if(ceklok_out IS NULL,
                                            0,
                                            ceklok_out
                                        )
                                    ), 0
                                ) AS carbon_co,

                                -- izin mid
                                if(kode_izin_mid IS NOT NULL AND is_potong_gaji_mid = 1,
                                    if(jam_awal_izin_mid > jam_akhir_izin_mid,
                                        -- jika jam awal > jam akhir izin maka bukan today dan harus di +1 day
                                        date_add(tanggal_jam_izin_akhir_mid, INTERVAL 1 DAY),
                                        tanggal_jam_izin_akhir_mid
                                    ),
                                    0
                                ) carbon_mid

                            FROM presensi
                        ),
                        perhitungan AS (
                            SELECT
                                *,
                                
                                -- STATUS PRESENSI IN
                                CASE
                                    WHEN kode_absen IS NOT NULL AND keterangan <> "" AND id_htsxxmh <> 1 THEN kode_kondite
                                    WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" AND keterangan = "" AND ceklok_luar IS NOT NULL AND durasi_lembur_libur_jam = 0 THEN "Jadwal Salah"
                                    WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" AND keterangan = "" AND id_htsxxmh <> 1 AND ceklok_luar IS NULL THEN "AL"
                                    WHEN st_clock_in = "NO CI" AND st_clock_out = "NO CO" AND id_htsxxmh = 1 THEN "OFF"
                                    WHEN st_clock_in = "OK" AND st_clock_out = "OK" THEN "HK"
                                    WHEN kode_izin_in IS NOT NULL AND keterangan <> "" AND id_htsxxmh <> 1 THEN kode_kondite
                                    WHEN kode_izin_in IS NULL AND id_htsxxmh <> 1 AND st_clock_in NOT IN ("OK", "Late 1") THEN "Belum Ada Izin"
                                    WHEN kode_izin_in IS NULL AND kode_absen IS NULL AND id_htsxxmh <> 1 AND st_clock_in = "OK" THEN "HK"
                                    WHEN kode_izin_in IS NULL AND st_clock_in = "Late 1" THEN "TL 1"
                                    ELSE NULL
                                END AS status_presensi_in,
                                
                                -- STATUS PRESENSI OUT
                                CASE
                                    WHEN kode_absen IS NOT NULL AND keterangan <> "" AND id_htsxxmh <> 1 THEN kode_kondite
                                    WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" AND keterangan = "" AND ceklok_luar IS NOT NULL AND durasi_lembur_libur_jam = 0 THEN "Jadwal Salah"
                                    WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" AND keterangan = "" AND id_htsxxmh <> 1 AND ceklok_luar IS NULL THEN "AL"
                                    WHEN st_clock_in = "NO CI" AND st_clock_out = "NO CO" AND id_htsxxmh = 1 THEN "OFF"
                                    WHEN st_clock_in = "OK" AND st_clock_out = "OK" THEN "HK"
                                    WHEN kode_izin_out IS NOT NULL AND keterangan <> "" AND id_htsxxmh <> 1 THEN kode_kondite
                                    WHEN kode_izin_out IS NULL AND id_htsxxmh <> 1 AND st_clock_out <> "OK" THEN "Belum Ada Izin"
                                    WHEN kode_izin_out IS NULL AND kode_absen IS NULL AND id_htsxxmh <> 1 AND st_clock_out = "OK" THEN "HK"
                                    ELSE NULL
                                END AS status_presensi_out,
                                
                                -- POTONG PREMI
                                CASE
                                    WHEN is_pot_premi_absen = 1 THEN 1
                                    WHEN is_pot_premi_izin_in = 1 THEN 1
                                    WHEN is_pot_premi_izin_out = 1 THEN 1
                                    WHEN is_potong_premi_mid = 1 THEN 1
                                    WHEN id_hemxxmh = 67 THEN 1
                                    WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" AND keterangan = "" AND id_htsxxmh <> 1 AND ceklok_luar IS NULL THEN 1
                                    ELSE 0
                                END AS is_pot_premi,
                                
                                -- POTONG UPAH
                                CASE
                                    WHEN is_pot_upah_absen = 1 THEN 1
                                    WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" AND keterangan = "" AND id_htsxxmh <> 1 AND ceklok_luar IS NULL THEN 1
                                    ELSE 0
                                END AS is_pot_upah,

                                -- hitung pot_late
                                IF(IFNULL(tanggaljam_awal_toleransi_lembur, "") = "" OR st_clock_in = "LATE",
                                    IF(id_htsxxmh IN (5, 12) AND is_sabtu = 1,
                                        CEIL(TIMESTAMPDIFF(MINUTE, tanggaljam_akhir_toleransi, carbon_ci) / 60),
                                        IF(jumlah_grup <> 4,
                                            IF(ceklok_in > if(day(tanggaljam_awal_istirahat) < day(tanggaljam_akhir) , date_add(tanggaljam_awal_istirahat, INTERVAL 1 DAY),  tanggaljam_awal_istirahat),
                                                CEIL(TIMESTAMPDIFF(MINUTE, tanggaljam_akhir_toleransi_min1jam, carbon_ci) / 60),
                                                CEIL(TIMESTAMPDIFF(MINUTE, tanggaljam_akhir_toleransi, carbon_ci) / 60)
                                            ),
                                            CEIL(TIMESTAMPDIFF(MINUTE, tanggaljam_akhir_toleransi, carbon_ci) / 60)
                                        )
                                    ),
                                    0
                                ) AS pot_jam_late,
        
                                -- kalau ada lembur, maka cek late lembur, pastikan bukan long shift lembur
                                IF(IFNULL(tanggaljam_awal_toleransi_lembur, "") != "" AND jam_awal_lembur != jam_akhir_schedule,
                                    CEIL(TIMESTAMPDIFF(MINUTE, tanggaljam_awal_toleransi_lembur, IFNULL(ceklok_in,carbon_ci)) / 60),
                                    0
                                ) AS pot_jam_late_lembur,

                                -- hitung pot_early
                                IF(id_htsxxmh IN (5, 12) AND is_sabtu = 1,
                                    CEIL(TIMESTAMPDIFF(MINUTE, carbon_co, tanggaljam_akhir) / 60),
                                    IF(jumlah_grup <> 4,
                                        IF(ceklok_out < if(day(tanggaljam_akhir_istirahat) < day(tanggaljam_akhir) , date_add(tanggaljam_akhir_istirahat, INTERVAL 1 DAY),  tanggaljam_akhir_istirahat) ,
                                            CEIL(TIMESTAMPDIFF(MINUTE, carbon_co, tanggaljam_akhir_min1) / 60),
                                            CEIL(TIMESTAMPDIFF(MINUTE, carbon_co, tanggaljam_akhir) / 60)
                                        ),
                                        CEIL(TIMESTAMPDIFF(MINUTE, carbon_co, tanggaljam_akhir) / 60)
                                    )
                                ) AS pot_jam_early,

                                CEIL(TIMESTAMPDIFF(MINUTE, tanggal_jam_izin_awal_mid, carbon_mid) / 60) AS pot_jam_izin
                                
                            FROM status_presensi
                        ),
                        sum_pot_jam AS (
                            SELECT
                                *,
                                -- CEK KONDITE 1 / 0
                                CASE 
                                    -- WHEN st_clock_in = "No CI" AND st_clock_out = "No CO" THEN 1
                                    WHEN status_presensi_in = "OFF" AND status_presensi_out = "OFF" THEN 0
                                    WHEN status_presensi_in = "Izin Belum Disetujui" THEN 1
                                    WHEN status_presensi_in = "Belum Ada Izin" THEN 1
                                    WHEN status_presensi_in = "Jadwal Salah" AND durasi_lembur_total_jam > 0 THEN 0
                                    WHEN status_presensi_in = "Jadwal Salah" AND durasi_lembur_total_jam <= 0 THEN 1
                                    WHEN keterangan = "" AND st_clock_in = "No CI" AND status_presensi_in <> "HK" THEN 1
                                    WHEN status_presensi_out = "Izin Belum Disetujui" THEN 1
                                    WHEN status_presensi_out = "Belum Ada Izin" THEN 1
                                    WHEN keterangan = "" AND st_clock_out = "No CO" AND status_presensi_out <> "HK" THEN 1
                                    -- WHEN status_presensi_in = "Jadwal Salah" THEN 1
                                    WHEN status_presensi_in = "AL" THEN 1
                                    WHEN kode_absen IS NULL AND (status_presensi_in = "Belum ada Izin" OR status_presensi_out = "Belum ada Izin") THEN 1
                                    WHEN id_tukar_jadwal > 0 THEN 1
                                    ELSE 0
                                END AS cek,

                                IFNULL(pot_jam_late, 0) + IFNULL(pot_jam_late_lembur, 0) + IFNULL(pot_jam_early, 0) + IFNULL(pot_jam_izin,0) + IF(potongan_ti_jam > 0, potongan_ti_jam ,ifnull(pot_jam_keluar_istirahat,0)) AS total_pot_jam
                            FROM perhitungan
                        ),
                        hitung_pot_ti AS (
                            SELECT
                                *,
                                if(potongan_ti_jam > 0, 
                                    potongan_ti_jam,
                                    IF(is_pot_ti = 1 AND total_pot_jam > 0,
                                        0.5,
                                        0
                                    ) 
                                ) AS pot_ti_jam_fix
                            FROM sum_pot_jam
                        ),
                        hitung_lembur_ti AS (
                            SELECT
                                *,

                                -- MENGHITUNG HASIL AKHIR POT TI
                                IF(durasi_lembur_ti > 0,
                                    IF(pot_ti_jam_fix > 0,
                                        pot_ti_jam_fix,
                                        IF(total_pot_jam> 0,
                                            0.5,
                                            0
                                        )
                                    ),
                                    0
                                ) AS pot_ti
                            FROM hitung_pot_ti
                        ),
                        hitung_pot_non_ti AS (
                            SELECT
                                *,
                                (total_pot_jam - pot_ti) AS pot_jam_min_pot_ti,
                                
                                -- HITUNG HASIL AKHIR POT NON TI
                                IF(durasi_lembur_non_ti > 0 AND (total_pot_jam - pot_ti) > 0,
                                    IF( (total_pot_jam - pot_ti) > durasi_lembur_non_ti,
                                        durasi_lembur_non_ti,
                                        (total_pot_jam - pot_ti)
                                    ),
                                    0
                                ) AS pot_non_ti

                            FROM hitung_lembur_ti
                        ),
                        hitung_potongan_lembur AS (
                            SELECT
                                *,
                                (pot_jam_min_pot_ti - pot_non_ti) AS pot_jam_min_ti_non_ti,
                                
                                -- TOTAL POT LEMBUR
                                pot_ti + pot_non_ti AS pot_lembur,

                                -- DURASI LEMBUR FINAL, JIKA KURANG DARI 0 MAKA DI NOL KAN AGAR TIDAK MINUS
                                IF( durasi_lembur_total_jam - (pot_ti + pot_non_ti) <= 0,
                                    0,
                                    durasi_lembur_total_jam - (pot_ti + pot_non_ti)
                                ) AS durasi_lembur_final,

                                -- HITUNG HASIL AKHIR POT NON TI
                                IF( (pot_jam_min_pot_ti - pot_non_ti) > 0,
                                    CEIL(pot_jam_min_pot_ti - pot_non_ti),
                                    0
                                ) AS pot_hk
                                
                            FROM hitung_pot_non_ti
                        ),
                        hitung_lembur AS (
                            SELECT
                                *,
                                IF(id_hesxxmh = 3, 
                                    durasi_lembur_final,
                                    IF(durasi_lembur_libur_jam > 0,
                                        IF(durasi_lembur_libur_jam > 0,
                                            0,
                                            IF(durasi_lembur_final > 1,
                                                1,
                                                durasi_lembur_final
                                            )
                                        ),
                                        IF(id_hemxxmh = 67,
                                            IF(durasi_lembur_final > 2, 2, durasi_lembur_final),
                                            IF(durasi_lembur_final > 1, 1, durasi_lembur_final)
                                        )
                                    )
                                ) AS lembur15,
        
                                IF(id_hesxxmh = 3, 
                                    0,
                                    IF(durasi_lembur_libur_jam > 0, -- cek apakah ini lembur libur
                                        IF(durasi_lembur_final > 7,  -- cek durasi_lembur_final apakah > 7
                                            7, -- kalau ya, maka harus 7 jam untuk lembur2 batasnya. Sisanya masuk lembur3
                                            durasi_lembur_final -- jika kurang dari 7, maka tampilkan apa adanya
                                        ),
                                        IF(id_hemxxmh = 67, -- ini supir pribadi
                                            IF(durasi_lembur_final > 2, durasi_lembur_final - 2, 0),
                                            IF(durasi_lembur_final > 1, -- jika lembur biasa > 1, maka jam berikutnya masuk ke lembur2
                                                if(durasi_lembur_final > 8, -- cek apakah lembih dari 8 jam total lembur?
                                                    7,  -- kalau lebih dari 8 jam untuk lembur2 bukan Libur, maka batas di 7 jam yang diambil
                                                    durasi_lembur_final - 1
                                                )
                                                , 0
                                            )
                                        )
                                    )
                                ) AS lembur2,
        
                                IF(id_hesxxmh = 3, 
                                    0,
                                    IF(durasi_lembur_libur_jam > 0, -- cek apakah lembur libur
                                        IF(durasi_lembur_final > 7, -- jika lembur libur > 7 maka masuk lembur3
                                            durasi_lembur_final - 7, -- jika lembur > 7 maka akan -7, karena 7 jam pertama ikut lembur2
                                            0 -- kalau kurang dari 7 maka 0, karena lembur3 harus > 7.
                                        ),
                                        IF(id_hemxxmh = 67, 
                                            0,
                                            -- ini lembur normal, lembur3 itu diatas 8 jam, 
                                            -- jika lembur normal 9 jam, maka
                                            -- lembur15: 1, lembur2: 7, lembur3: 1
                                            IF(durasi_lembur_final > 8, durasi_lembur_final - 8, 0)
                                        )
                                    )
                                ) AS lembur3
                                
                            FROM hitung_potongan_lembur
                        ),
                        hitung_lembur_final AS (
                            SELECT
                                *,
                                lembur15 * 1.5 AS lembur15_final,
                                lembur2 * 2 AS lembur2_final,
                                lembur3 * 3 AS lembur3_final,
                                0 AS lembur4_final,

                                nominal_lembur_jam * (lembur15 * 1.5) AS rp_lembur15,
                                nominal_lembur_jam * (lembur2 * 2) AS rp_lembur2,
                                nominal_lembur_jam * (lembur3 * 3) AS rp_lembur3,
                                0 AS rp_lembur4

                            FROM hitung_lembur
                        )
                        SELECT DISTINCT
                            id_hemxxmh,
                            keterangan,
                            kode_finger,
                            ifnull(tanggal, :tanggal) AS tanggal,

                            shift_in,
                            shift_out,
                            kode_shift AS jadwal,
                            tanggaljam_awal_t1,
                            tanggaljam_awal,
                            tanggaljam_awal_t2,

                            tanggaljam_akhir_t1,
                            tanggaljam_akhir,
                            tanggaljam_akhir_t2,

                            ceklok_in,
                            ceklok_out,
                            
                            if(kode_shift = "NJ", NULL, st_clock_in) AS st_clock_in,
                            if(kode_shift = "NJ", NULL, st_clock_out) AS st_clock_out,
                            
                            if(kode_shift = "NJ", "NJ", status_presensi_in) AS status_presensi_in,
                            if(kode_shift = "NJ", "NJ", status_presensi_out) AS status_presensi_out,

                            keterangan AS htlxxrh_kode,
                            jam_awal_lembur_libur,
                            jam_akhir_lembur_libur,
                            durasi_lembur_libur_jam,

                            jam_awal_lembur_awal,
                            jam_akhir_lembur_awal,
                            durasi_lembur_awal_jam,
                            jam_awal_lembur_akhir,
                            jam_akhir_lembur_akhir,
                            durasi_lembur_akhir_jam,
                            jam_awal_lembur_istirahat1,
                            jam_akhir_lembur_istirahat1,
                            durasi_lembur_istirahat1_jam,
                            jam_awal_lembur_istirahat2,
                            jam_akhir_lembur_istirahat2,
                            durasi_lembur_istirahat2_jam,
                            jam_awal_lembur_istirahat3,
                            jam_akhir_lembur_istirahat3,
                            durasi_lembur_istirahat3_jam,
                            durasi_lembur_total_jam,
                            
                            total_pot_jam,
                            pot_non_ti,
                            pot_hk,
                            pot_ti,
                            durasi_lembur_final,

                            total_pot_jam AS pot_jam_final,
                            is_makan,

                            is_pot_premi,
                            is_pot_upah,
                            if(kode_shift = "NJ", 1, cek) AS cek,
                            
                            lembur15,
                            rp_lembur15,
                            lembur15_final,

                            lembur2,
                            rp_lembur2,
                            lembur2_final,

                            lembur3,
                            rp_lembur3,
                            lembur3_final,

                            nominal_lembur_jam,
                            grup_hk,
                            break_in,
                            break_out
                        
                        FROM hitung_lembur_final
                    '
            );
        }

        // Khusus untuk karyawan a/n 09110415 MASKUR dan 12090891 SUGIONO 
        // ini jika hari Sabtu ada jadwal, tetapi mereka tidak masuk, ini statusnya tetap alpa, tetapi tidak memotong apa-apa 
        //Update 7 Feb 2024 by Ferry
        $qu_pengecualian = $db
            ->raw()
            ->bind(':tanggal', $tanggal)
            ->exec(' UPDATE htsprrd AS a
                    SET 
                        a.pot_jam = 0,
                        a.pot_ti = 0,
                        a.pot_jam_final = 0,
                        a.is_pot_premi = 0,
                        a.is_pot_upah = 0,
                        a.pot_overtime = 0,
                        a.pot_hk = 0,
                        a.cek = 0,
                        a.durasi_lembur_final = durasi_lembur_total_jam
                    WHERE DAYOFWEEK(:tanggal) = 7 AND id_hemxxmh IN (130, 208) AND a.status_presensi_in = "AL"
                    '
        );
        
        //Case Satpam selain KMJ yang pada tanggal merah, tidak masuk tidak apa2 atau cek = 0 dan Status Presensi dari AL jadi OFF
        $qu_satpam = $db
            ->raw()
            ->bind(':tanggal', $tanggal)
            ->exec(' UPDATE htsprrd AS a
                    INNER JOIN hemxxmh AS h ON h.id = a.id_hemxxmh
                    INNER JOIN hemjbmh AS b ON b.id_hemxxmh = a.id_hemxxmh
                    LEFT JOIN (
                        SELECT
                            count(ho.id) AS is_holiday,
                            tanggal
                        FROM hthhdth AS ho
                        WHERE ho.tanggal = :tanggal
                    ) AS holiday ON holiday.tanggal = a.tanggal
                    LEFT JOIN (
                        SELECT
                            count(cu.id) AS is_cuti,
                            tanggal
                        FROM htlgnth AS cu
                        WHERE cu.tanggal = :tanggal
                    ) AS cuti ON cuti.tanggal = a.tanggal
                    
                    SET 
                        a.cek = 0,
                        a.status_presensi_in = "OFF",
                        a.status_presensi_out = "OFF"
                    WHERE a.tanggal = :tanggal AND b.id_hetxxmh IN (99, 48) AND b.id_heyxxmd <> 4 AND a.status_presensi_in = "AL" AND (is_holiday IS NOT NULL OR is_cuti IS NOT null)
                    '
        );
        
        //Case Pot Upah - Cuti Bersama untuk pegawai yang mendapat flaf is_pot cuti di schedule, 
        // pegawai yang mendapat flag is_pot_cuti di schedule dan tidak ada ceklok maka dibuat cek = 0 dan diberikan flag_is_pot_upah dan pot premi,
        // Jika terdapat flag is_pot_cuti di schedule dan ada ceklok salah satu maka cek = 1 
        //Jika ada kedua ceklok maka, akan cek tidak akan dirubah atau sesuai dengan cek asli dari generate presensi.
        
        //UPDATE QUERY 31 JAN 2025
        $qu_pot_upah = $db
            ->raw()
            ->bind(':tanggal', $tanggal)
            ->bind(':id_hemxxmh', $id_hemxxmh)
            ->exec('UPDATE htsprrd AS a
                    INNER JOIN htssctd AS b 
                        ON b.id_hemxxmh = a.id_hemxxmh 
                        AND b.tanggal = a.tanggal
                    SET 
                        cek = IF(
                            a.clock_in IS NULL AND a.clock_out IS NULL, 
                            0, 
                            IF(
                                a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
                                a.cek, 
                                1
                            )
                        ),
                        a.htlxxrh_kode = 
                        IF(
                            a.durasi_lembur_final > 0,
                            "Lembur di hari Cuti Bersama",
                            IF(
                                a.clock_in IS NULL AND a.clock_out IS NULL, 
                                "Cuti Bersama - Potong Upah", 
                                IF(
                                    a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
                                    a.htlxxrh_kode, 
                                    "Cuti Bersama - Potong Upah"
                                )
                            )
                        ),
                        a.is_pot_upah = 
                        -- Jika Cuti bersama dan ada Lembur, harusnya tidak dipotong upah dan premi
                        IF(
                            a.durasi_lembur_final > 0,
                            0, -- maka is_pot = 0
                            IF(
                                a.clock_in IS NULL AND a.clock_out IS NULL, 
                                1, 
                                IF(
                                    a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
                                    a.cek, 
                                    1
                                )
                            )
                        )
                    WHERE 
                        a.id_hemxxmh = :id_hemxxmh 
                        AND a.tanggal = :tanggal
                        AND b.is_active = 1 
                        AND b.is_pot_hk = 1;
            '
        );

        //QUERY LAMA EXECUTE
        // $qu_pot_upah = $db
        //     ->raw()
        //     ->bind(':tanggal', $tanggal)
        //     ->exec('UPDATE htsprrd AS a
        //             INNER JOIN htssctd AS b 
        //                 ON b.id_hemxxmh = a.id_hemxxmh 
        //                 AND b.tanggal = a.tanggal
        //             SET 
        //                 cek = IF(
        //                     a.clock_in IS NULL AND a.clock_out IS NULL, 
        //                     0, 
        //                     IF(
        //                         a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
        //                         a.cek, 
        //                         1
        //                     )
        //                 ),
        //                 a.htlxxrh_kode = IF(
        //                     a.clock_in IS NULL AND a.clock_out IS NULL, 
        //                     "Cuti Bersama - Potong Upah", 
        //                     IF(
        //                         a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
        //                         a.htlxxrh_kode, 
        //                         "Cuti Bersama - Potong Upah"
        //                     )
        //                 ),
        //                 a.is_pot_upah = IF(
        //                     a.clock_in IS NULL AND a.clock_out IS NULL, 
        //                     1, 
        //                     IF(
        //                         a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
        //                         a.cek, 
        //                         1
        //                     )
        //                 ),
        //                 a.is_pot_premi = IF(
        //                     a.clock_in IS NULL AND a.clock_out IS NULL, 
        //                     1, 
        //                     IF(
        //                         a.clock_in IS NOT NULL AND a.clock_out IS NOT NULL, 
        //                         a.cek, 
        //                         1
        //                     )
        //                 )
        //             WHERE 
        //                 a.tanggal = :tanggal
        //                 AND b.is_active = 1 
        //                 AND b.is_pot_hk = 1;
        //     '
        // );
        
        // di commit per karyawan
        $qu_hgtprth = $db
            ->query('update', 'hgtprth')
            ->set('generated_on',$timestamp)
            ->where('id_heyxxmh',$id_heyxxmh)
            ->where('tanggal',$tanggal)
        ->exec();
        
        $akhir = new Carbon();

        $qi_activity_log_ml = $db
            ->query('insert', 'activity_log_ml')
            ->set('id_transaksi',$id_hgtprth)
            ->set('kode','GENERATE')
            ->set('nama','hgtprth')
            ->set('keterangan','Generate Presensi '.$tanggal)
            ->set('created_by',$_SESSION['user'])
            ->set('username',$_SESSION['username'])
            ->set('start_on',$awal)
            ->set('finish_on', $akhir)
            ->set('durasi_detik',$awal->diffInSeconds($akhir))
        ->exec();

        $db->commit();
        
        $data = array(
            'message'=> 'Generate Presensi Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik', 
            'type_message'=>'success',
            'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
        );
        
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        $data = array(
            'message'=>'Data Gagal Dibuat', 
            'type_message'=>'danger',
            'error' => $e
        );
    }

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

    /**
     * tes query
     * fetchAll()
     * Jika hasil query DB blank, maka 
     *  empty()     = 1
     *  is_null()   = blank
     *  isset       = 1
     * 
     * fetch()
     */
    // $qs_htlxxrh = $db
    //     ->query('select', 'htlxxrh' )
    //     ->get([
    //         'htlxxrh.kode as htlxxrh_kode',
    //         'htlxxmh_kode as htlxxmh_kode',
    //         'htlgrmh.kode as htlgrmh_kode'
    //     ] )
    //     ->join('htlxxmh','htlxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
    //     ->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
    //     ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
    //     ->where('htlxxrh.tanggal', $tanggal )
    //     ->where('htlxxrh.jenis', 1 )
    //     ->limit(1)
    //     ->exec();
    // $rs_htlxxrh = $qs_htlxxrh->fetchAll();
    // print_r ($rs_htlxxrh);
    // echo 'empty: ' . empty($rs_htlxxrh);
    // echo 'is_null: ' . is_null($rs_htlxxrh);
    // echo 'isset: ' . isset($rs_htlxxrh);
?>