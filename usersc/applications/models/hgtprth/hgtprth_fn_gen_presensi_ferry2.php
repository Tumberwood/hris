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
    // print_r($timestamp);

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
            'hemjbmh.id_hesxxmh as id_hesxxmh'
        ] )
        ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
        ->where( function ( $q ) use ($tanggal) {
            $q
              ->where( 'hemjbmh.tanggal_keluar', null)
              ->or_where( 'hemjbmh.tanggal_keluar', $tanggal , '>' ); //revisi dari < menjadi >
        } )
        ->where('hemjbmh.tanggal_masuk', $tanggal, '<=' )
        ->where('hemxxmh.is_active', 1 )
        ->where('hemjbmh.is_checkclock', 1 ) // skip yang tidak perlu checkclock
        ->where('hemjbmh.id_heyxxmh', $id_heyxxmh ) // skip yang tidak perlu checkclock
        ->exec();
    $rs_hemxxmh = $qs_hemxxmh->fetchAll();

    try{
        $db->transaction();
        $menit_toleransi_terlambat = 5;
        $jam_awal_lembur_libur = 0;
        $jam_akhir_lembur_libur = 0;
        $durasi_lembur_libur_jam = 0;
        $jam_awal_lembur_awal = 0;
        $jam_akhir_lembur_awal = 0;
        $durasi_lembur_awal_jam = 0;
        $jam_awal_lembur_akhir = 0;
        $jam_akhir_lembur_akhir = 0;
        $durasi_lembur_akhir_jam = 0;
        $jam_awal_lembur_istirahat1 = 0;
        $jam_akhir_lembur_istirahat1 = 0;
        $durasi_lembur_istirahat1_jam = 0;
        $jam_awal_lembur_istirahat2 = 0;
        $jam_akhir_lembur_istirahat2 = 0;
        $durasi_lembur_istirahat2_jam = 0;
        $jam_awal_lembur_istirahat3 = 0;
        $jam_akhir_lembur_istirahat3 = 0;
        $durasi_lembur_istirahat3_jam = 0;
        $durasi_lembur_total_jam = 0;
        $durasi_lembur_istirahat1_menit   = 0;
        $durasi_lembur_istirahat2_menit   = 0;
        $durasi_lembur_istirahat3_menit   = 0;
        $durasi_lembur_awal_menit         = 0;
        $durasi_lembur_akhir_menit        = 0;
        $durasi_lembur_libur_menit        = 0;
        $durasi_lembur_final        = 0;
        $pot_jam_final        = 0;
        $nominal_lembur_jam        = 0;
        $is_makan        = 0;
        $nominal_lembur_final        = 0;
        $is_longshift = 0;
        $cek = 0;
        $tanggal_jam_izin_awal = 0;
        $tanggal_jam_izin_akhir = 0;

        $tolak_ti = 0;
        // $durasi_break_menit = 0;
        // $potongan_ti_jam = 0;
        $jam_pengali = 0;

        //CEK JIKA ADA KARYAWAN AKTIF
        if (!empty($rs_hemxxmh)){
            foreach ($rs_hemxxmh as $row_hemxxmh) {

                $id_hemxxmh = $row_hemxxmh['id_hemxxmh'];
                $keterangan = '';
                $kode_finger = $row_hemxxmh['kode_finger'];
                $htlxxrh_kode = '';
                $status_presensi_in = '';
                $status_presensi_out = '';
                $shift_in = null;
                $shift_out = null;
                $st_jadwal = "";
                $tanggaljam_awal_t1 = null;
                $tanggaljam_awal_t2 = null;
                $tanggaljam_akhir_t1 = null;
                $tanggaljam_akhir_t2 = null;

                
                $tanggaljam_awal = null;
                $tanggaljam_akhir = null;

                $clock_in = null;
                $clock_out = null;
                $st_clock_in = "";
                $st_clock_out = "";

                $pot_jam = 0;
                
                //UNTUK MULTIPLE IZIN
                $kode_izin = array();
                $kode_dinas_multi = array();

                // STEP 1 CEK JADWAL
                // $qs_htssctd = $db
                //     ->query('select', 'htssctd' )
                //     ->get([
                //         'htssctd.id_htsxxmh as id_htsxxmh',
                //         'htsxxmh.kode as htsxxmh_kode',
                //         'htssctd.jam_awal as jam_awal',
                //         'htssctd.jam_akhir as jam_akhir',
                //         'htssctd.jam_awal_istirahat as jam_awal_istirahat',
                //         'htssctd.jam_akhir_istirahat as jam_akhir_istirahat',
                //         'htssctd.menit_toleransi_awal_in as menit_toleransi_awal_in',
                //         'htssctd.menit_toleransi_akhir_in as menit_toleransi_akhir_in',
                //         'htssctd.menit_toleransi_awal_out as menit_toleransi_awal_out',
                //         'htssctd.menit_toleransi_akhir_out as menit_toleransi_akhir_out',

                //         'htssctd.tanggaljam_awal_t1 as tanggaljam_awal_t1',
                //         'htssctd.tanggaljam_awal as tanggaljam_awal',
                //         'htssctd.tanggaljam_awal_t2 as tanggaljam_awal_t2',
                //         'htssctd.tanggaljam_akhir_t1 as tanggaljam_akhir_t1',
                //         'htssctd.tanggaljam_akhir as tanggaljam_akhir',
                //         'htssctd.tanggaljam_akhir_t2 as tanggaljam_akhir_t2',

                //         'htssctd.tanggaljam_awal_istirahat as tanggaljam_awal_istirahat',
                //         'htssctd.tanggaljam_akhir_istirahat as tanggaljam_akhir_istirahat'

                //     ] )
                //     ->join('htsxxmh','htsxxmh.id = htssctd.id_htsxxmh','LEFT')
                //     ->where('htssctd.is_active', 1 )
                //     ->where('htssctd.id_hemxxmh', $id_hemxxmh )
                //     ->where('htssctd.tanggal', $tanggal )
                //     ->exec();
                // $rs_htssctd = $qs_htssctd->fetchAll();
                $qs_htssctd = $db
                    ->raw()
                    ->bind(':id_hemxxmh', $id_hemxxmh)
                    ->bind(':tanggal', $tanggal)
                    ->exec('SELECT
                                htssctd.id_htsxxmh AS id_htsxxmh,
                                htsxxmh.kode AS htsxxmh_kode,
                                htssctd.jam_awal AS jam_awal,
                                htssctd.jam_akhir AS jam_akhir,
                                htssctd.jam_awal_istirahat AS jam_awal_istirahat,
                                htssctd.jam_akhir_istirahat AS jam_akhir_istirahat,
                                htssctd.menit_toleransi_awal_in AS menit_toleransi_awal_in,
                                htssctd.menit_toleransi_akhir_in AS menit_toleransi_akhir_in,
                                htssctd.menit_toleransi_awal_out AS menit_toleransi_awal_out,
                                htssctd.menit_toleransi_akhir_out AS menit_toleransi_akhir_out,
                                htssctd.tanggaljam_awal_t1 AS tanggaljam_awal_t1,
                                htssctd.tanggaljam_awal AS tanggaljam_awal,
                                htssctd.tanggaljam_awal_t2 AS tanggaljam_awal_t2,
                                htssctd.tanggaljam_akhir_t1 AS tanggaljam_akhir_t1,
                                htssctd.tanggaljam_akhir AS tanggaljam_akhir,
                                htssctd.tanggaljam_akhir_t2 AS tanggaljam_akhir_t2,
                                CASE
                                    WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_awal_istirahat <= "02:00:00"
                                    THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", htsxxmh.jam_awal_istirahat)
                                    ELSE CONCAT(:tanggal, " ", htsxxmh.jam_awal_istirahat)
                                END AS tanggaljam_awal_istirahat,
                                CASE
                                    WHEN htsxxmh.kode like "malam%" AND htsxxmh.jam_akhir_istirahat <= "02:00:00"
                                    THEN CONCAT(DATE_ADD(:tanggal, INTERVAL 1 DAY), " ", htsxxmh.jam_akhir_istirahat)
                                    ELSE CONCAT(:tanggal, " ", htsxxmh.jam_akhir_istirahat)
                                END AS tanggaljam_akhir_istirahat
                            FROM
                                htssctd
                            LEFT JOIN
                                htsxxmh ON htsxxmh.id = htssctd.id_htsxxmh
                            WHERE
                                htssctd.is_active = 1
                                AND htssctd.id_hemxxmh = :id_hemxxmh
                                AND htssctd.tanggal = :tanggal                
                            '
                            );
                $rs_htssctd = $qs_htssctd->fetchAll();
                $total_shift = count($rs_htssctd);
                // print_r($total_shift);

////////////////////////////// LEVEL 1 JADWAL //////////////////////////////

                if(!empty($rs_htssctd)){
                    foreach ($rs_htssctd as $key => $jadwal) {
                        // jika jadwal sudah dibuat
                        $id_shift  = $jadwal['id_htsxxmh'];
                        $shift_in  = $jadwal['jam_awal'];
                        $shift_out = $jadwal['jam_akhir'];
                        $st_jadwal = $jadwal['htsxxmh_kode'];

                        $tanggaljam_awal_t1         = $jadwal['tanggaljam_awal_t1'];
                        $tanggaljam_awal            = $jadwal['tanggaljam_awal'];

                        // BEGIN untuk keperluan toleransi terlambat
                        $tanggaljam_awal_toleransi  = $jadwal['tanggaljam_awal'];
                        $tanggaljam_awal_toleransi  = new Carbon($tanggaljam_awal_toleransi);
                        $tanggaljam_awal_toleransi  = $tanggaljam_awal_toleransi->subMinutes($menit_toleransi_terlambat);

                        $tanggaljam_akhir_toleransi  = $jadwal['tanggaljam_awal'];
                        $tanggaljam_akhir_toleransi  = new Carbon($tanggaljam_akhir_toleransi);
                        $tanggaljam_akhir_toleransi  = $tanggaljam_akhir_toleransi->addMinutes($menit_toleransi_terlambat);
                        // END untuk keperluan toleransi terlambat

                        $tanggaljam_awal_t2         = $jadwal['tanggaljam_awal_t2'];
                        $tanggaljam_akhir_t1        = $jadwal['tanggaljam_akhir_t1'];
                        $tanggaljam_akhir           = $jadwal['tanggaljam_akhir'];
                        $tanggaljam_akhir_t2        = $jadwal['tanggaljam_akhir_t2'];

                        $tanggaljam_awal_istirahat  = $jadwal['tanggaljam_awal_istirahat'];
                        $tanggaljam_akhir_istirahat = $jadwal['tanggaljam_akhir_istirahat'];

                        // init var
                        $pot_jam_late = 0;
                        $pot_jam_izin = 0;
                        $pot_jam_early = 0;
                        $pot_jam_final = 0;
                        $potongan_ti_jam = 0;

                        $durasi_lembur_final = 0;
                        $nominal_lembur_jam = 0;
                        $nominal_lembur_final = 0;
                        $is_makan = 0;
                        $pot_overtime = 0;

////////////////////////////// LEVEL 2 - CHECKCLOCK //////////////////////////////
                        //STEP 2 CEK CHECK IN
                        $qs_htsprtd_clock_in = $db
                            ->query('select', 'htsprtd')
                            ->get([
                                'CONCAT(htsprtd.tanggal, " ", htsprtd.jam) as dt_checkclock'
                            ])
                            ->where('htsprtd.kode', $row_hemxxmh['kode_finger'])
                            ->where('htsprtd.nama', '("os", "out", "staff", "pmi")', 'IN', false )
                            ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal_t1, '>=' )
                            ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal_t2, '<=' )
                            ->order('CONCAT(htsprtd.tanggal, " ", htsprtd.jam)')
                            ->exec();
                        $rs_htsprtd_clock_in = $qs_htsprtd_clock_in->fetch();
                        // print_r($rs_htsprtd_clock_in);
                        
                        if (!empty($rs_htsprtd_clock_in)) {
                            $ceklok_in = new Carbon($rs_htsprtd_clock_in['dt_checkclock']);
                            if($ceklok_in >= $tanggaljam_awal_t1 && $ceklok_in <= $tanggaljam_awal_toleransi){
                                $clock_in           = $ceklok_in;
                                $st_clock_in        = 'OK';
                                // $status_presensi_in = 'OK';
                            } else if($ceklok_in >= $tanggaljam_awal_toleransi && $ceklok_in <= $tanggaljam_akhir_toleransi){
                                $clock_in           = $ceklok_in;
                                $st_clock_in        = 'Late 1';
                                // $status_presensi_in = 'Late 1';
                            } else if($ceklok_in >= $tanggaljam_akhir_toleransi && $ceklok_in <= $tanggaljam_awal_t2){
                                $clock_in           = $ceklok_in;
                                $st_clock_in        = 'Late';
                                // $status_presensi_in = 'Late';
                                
                                // hitung potongan jam late (DIPINDAH DI STEP 5 IZIN)
                                // $pot_jam_late_cek       = $clock_in->diffInMinutes($tanggaljam_awal_toleransi);
                                // $pot_jam_late           = ceil($pot_jam_late_cek/60);
                            }
                        } else {
                            $clock_in           = null;
                            $st_clock_in        = 'No CI';
                            // $status_presensi_in = 'No CI';
                        }

                        // print_r($pot_jam_late . '<br>');

                        //STEP 3 clock_out
                        // BEGIN check clock_out
                        $qs_htsprtd_clock_out = $db
                            ->query('select', 'htsprtd' )
                            ->get([
                                'concat(htsprtd.tanggal," ",htsprtd.jam) as dt_checkclock'
                            ] )
                            
                            ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                            ->where('htsprtd.nama', '("os", "out", "staff", "pmi")', 'IN', false )
                            ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir_t1, '>=' )
                            ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir_t2, '<=' )
                            ->order('concat(htsprtd.tanggal," ",htsprtd.jam) DESC')
                            ->exec();
                        $rs_htsprtd_clock_out = $qs_htsprtd_clock_out->fetch();

                        if (!empty($rs_htsprtd_clock_out)) {
                            $ceklok_out = new Carbon($rs_htsprtd_clock_out['dt_checkclock']);
                            if($ceklok_out >= $tanggaljam_akhir && $ceklok_out <= $tanggaljam_akhir_t2){
                                $clock_out           = $ceklok_out;
                                $st_clock_out        = 'OK';
                                // $status_presensi_out = 'OK';
                            } else if($ceklok_out >= $tanggaljam_akhir_t1 && $ceklok_out <= $tanggaljam_akhir){
                                $clock_out           = $ceklok_out;
                                $st_clock_out        = 'EARLY';
                                // $status_presensi_out = 'EARLY';
                                
                                // hitung potongan jam early (DIPINDAH DI STEP 5 IZIN)
                                // $pot_jam_early_cek     = $clock_out->diffInMinutes($tanggaljam_akhir);
                                // $pot_jam_early   = ceil($pot_jam_early_cek/60);
                            }
                        } else {
                            $clock_out           = null;
                            $st_clock_out        = 'No CO';
                            // $status_presensi_out = 'No CO';
                        }
                        
////////////////////////////// LEVEL 3 - ABSEN //////////////////////////////
                        $qs_htlxxrh = $db
                            ->query('select', 'htlxxrh' )
                            ->get([
                                'htlxxrh.kode as htlxxrh_kode',
                                'htlxxrh.is_approve as is_approve',
                                'htlxxmh_kode as htlxxmh_kode',
                                'htlgrmh.kode as htlgrmh_kode'
                            ] )
                            ->join('htlxxmh','htlxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                            ->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
                            ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                            ->where('htlxxrh.is_active', 1)
                            ->where('htlxxrh.tanggal', $tanggal )
                            ->where('htlxxrh.jenis', 1 )    // absensi
                            ->exec();
                        $rs_htlxxrh = $qs_htlxxrh->fetch();
                        if(!empty($rs_htlxxrh)){
                            // jika ada absen
                            $kode_izin[] = $rs_htlxxrh['htlxxmh_kode'] . " [" . $rs_htlxxrh['htlxxrh_kode'] . "]";
                            if ($rs_htlxxrh['is_approve'] == 1) {
                                $status_presensi_in = $rs_htlxxrh['htlgrmh_kode'];
                                $status_presensi_out = $rs_htlxxrh['htlgrmh_kode'];
                            } else {
                                $status_presensi_in = "Absen Belum Disetujui";
                                $status_presensi_out = "Absen Belum Disetujui";
                            }
                            $next_step = 5;
                        }else{
                            $next_step = 5;
                            //JIKA SHIFT OFF MAKA STATUS OFF
                            if($jadwal['id_htsxxmh'] == 1){
                                $status_presensi_in = $jadwal['htsxxmh_kode'];
                                $status_presensi_out = $jadwal['htsxxmh_kode'];
                                $st_clock_in = $jadwal['htsxxmh_kode'];
                                $st_clock_out = $jadwal['htsxxmh_kode'];
                            } else {
                                $next_step = 5;
                            }
                            $cek = 0;
                        }
                    

                        //NEXT STEP
                        $next_step = 0;
                        //JIKA CEKLOK OK OK MAKA END
                        if ($st_clock_in == "OK" && $st_clock_out == "OK") {
                            $status_presensi_in = "HK";
                            $status_presensi_out = "HK";
                            // $next_step = 5;
                            $cek = 0;
                        } else if ($st_clock_in == "No CI" && $st_clock_out == "No CO") {
                            $cek = 1;
                        } else if ($st_clock_in == "Late 1" && $st_clock_out == "OK") {
                            $status_presensi_in = "HK";
                            $status_presensi_out = "HK";
                            $next_step = 5;
                        } else {
                            $next_step = 5;
                        }

                        //STEP 5 IZIN
                        if ($next_step == 5) {

                            //BEGIN DINAS DAN IZIN
                            $qs_htlxxrh_dinas_in = $db
                                ->query('select', 'htlxxrh' )
                                ->get([
                                    'htlxxrh.id_htlxxmh as id_htlxxmh',
                                    'htlxxrh.kode as htlxxrh_kode',
                                    'htlxxrh.jam_awal as jam_awal',
                                    'htlxxrh.htlxxmh_kode as htlxxmh_kode',
                                    'htpxxmh.is_potong_gaji as is_potong_gaji',
                                    'htlgrmh.kode as htlgrmh_kode',
                                    'htlxxrh.is_approve as is_approve'
                                ] )
                                ->join('htpxxmh','htpxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                                ->join('htlgrmh','htlgrmh.id = htpxxmh.id_htlgrmh','LEFT' )
                                ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                                ->where('htlxxrh.is_active', 1)
                                ->where('htlxxrh.tanggal', $tanggal )
                                ->where( function ( $r ) {
                                    $r
                                        ->where('htlxxrh.id_htlxxmh', 1) //IZIN  TERLAMBAT TL
                                        ->or_where('htlxxrh.id_htlxxmh', 5 ); // IZIN DINAS AWAL
                                } )
                                ->where('htlxxrh.jenis', 2 )    // izin
                                ->exec();
                            $rs_htlxxrh_dinas_in = $qs_htlxxrh_dinas_in->fetchAll();
                            // print_r($rs_htlxxrh_dinas_in);
                            
                            $is_late_pot = 0;
                            if(!empty($rs_htlxxrh_dinas_in) ){
                                foreach ($rs_htlxxrh_dinas_in as $key => $izin_dinas_in) {
                                    $kode_izin[] = $izin_dinas_in['htlxxmh_kode'] . " [" . $izin_dinas_in['htlxxrh_kode'] . "]"; //tamnbahkan kode dokumen
                                    if($izin_dinas_in['is_approve'] == 1){

                                        $status_presensi_in = $izin_dinas_in['htlxxmh_kode']; //KODE IZIN TL / DLW
                                        $cek = 0;
                                        //KALAU ADA SURAT IZIN TERLAMBAT MAKA BARU DILAKUKAN POTONGAN JAM
                                        if ($izin_dinas_in['is_potong_gaji'] == 1) { //DIGANTI IS POTONG GAJI
                                            $is_late_pot = 1;
    
                                        }
                                    }else{
                                        // if ($status_presensi_in != "HK" && $st_clock_in != "OK") {
                                            $status_presensi_in = 'Izin Belum Disetujui';
                                            $is_late_pot = 1;
                                            $cek = 1;
                                        // }
                                    }
                                    //FLAG LATE UNTUK YANG TIDAK ADA IZIN, BUKAN DINAS (IZIN DENGAN POTONGAN) & IZIN/DINAS YANG BELUM DI APPROVE
                                    if ($is_late_pot == 1) {
                                        if ($clock_in == null) {
                                            $tanggal_jam_izin_awal = $tanggal . " " . $izin_dinas_in['jam_awal']; //kalau no CO maka diambil jam izin
                                            $carbon_ci = new Carbon($clock_in);
                                            $pot_jam_late_cek     = $carbon_ci->diffInMinutes($tanggaljam_awal_toleransi);

                                            // $pot_jam_late_cek     = 0;
                                        } else {
                                            $carbon_ci = new Carbon($clock_in);
                                            $pot_jam_late_cek     = $carbon_ci->diffInMinutes($tanggaljam_awal_toleransi);
                                        }
                                        // hitung potongan jam late
                                        $pot_jam_late   = ceil($pot_jam_late_cek/60);
                                    }
                                }
                            } else {
                                //JIKS BUKAN OFF
                                if ($jadwal['id_htsxxmh'] != 1) {
                                    //JIKA CEK IN TIDAK OK
                                    if ($st_clock_in != "OK") {
                                        $status_presensi_in = "Belum ada Izin";
                                        $cek = 1;
                                    } else {
                                        if (empty($rs_htlxxrh) ) {
                                            //JIKA ST CLOCK IN OK DAN TIDAK ADA ABSEN MAKA DIBERI HK
                                            $status_presensi_in = "HK";
                                        }
                                        $cek = 0;
                                    }
                                }

                                if ($st_clock_in == "Late 1") {
                                    $status_presensi_in = "TL 1";
                                    $cek = 0;
                                }
                                
                            }
                            //TAMBAHAN CEK 18 SEP
                            if ($st_clock_in == "No CI" && $status_presensi_in != "HK") {
                                $cek = 1;
                            } else {
                               $cek = 0; 
                            }

                            $qs_htlxxrh_dinas_out = $db
                                ->query('select', 'htlxxrh' )
                                ->get([
                                    'htlxxrh.id_htlxxmh as id_htlxxmh',
                                    'htlxxrh.kode as htlxxrh_kode',
                                    'htlxxrh.jam_akhir as jam_akhir',
                                    'htpxxmh.is_potong_gaji as is_potong_gaji',
                                    'htlxxrh.htlxxmh_kode as htlxxmh_kode',
                                    'htlgrmh.kode as htlgrmh_kode',
                                    'htlxxrh.is_approve as is_approve'
                                ] )
                                ->join('htpxxmh','htpxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                                ->join('htlgrmh','htlgrmh.id = htpxxmh.id_htlgrmh','LEFT' )
                                ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                                ->where('htlxxrh.tanggal', $tanggal )
                                ->where( function ( $r ) {
                                    $r
                                        ->where('htlxxrh.id_htlxxmh', 2) //IZIN BUKAN DINAS DENGAN POTONGAN
                                        ->or_where('htlxxrh.id_htlxxmh', 6 ); //IZIN DINAS AKHIR
                                } )
                                ->where('htlxxrh.jenis', 2 )    // izin
                                ->exec();
                            $rs_htlxxrh_dinas_out = $qs_htlxxrh_dinas_out->fetchAll();
                            // print_r($rs_htlxxrh_dinas_out);
                            
                            $is_early_pot = 0;
                            if(!empty($rs_htlxxrh_dinas_out) ){
                                foreach ($rs_htlxxrh_dinas_out as $key => $izin_dinas_out) {
                                    $kode_izin[] = $izin_dinas_out['htlxxmh_kode'] . " [" . $izin_dinas_out['htlxxrh_kode'] . "]";
                                    if($izin_dinas_out['is_approve'] == 1){
                                        
                                        $status_presensi_out = $izin_dinas_out['htlxxmh_kode'];
                                        
                                        $cek = 0;
                                        //KALAU ADA SURAT IZIN PULANG AWAL (BUKAN DINAS) MAKA BARU DILAKUKAN POTONGAN JAM
                                        if ($izin_dinas_out['is_potong_gaji'] == 1) { //IS POTONG GAJI
                                            $is_early_pot = 1;
                                        }
                                    }else{
                                        $status_presensi_out = 'Izin Belum Disetujui'; //ditampilkan juga di keterangan kode dinasnya
                                        $is_early_pot = 1;
                                        $cek = 1;
                                        
                                    }
                                    //FLAG EARLY UNTUK YANG TIDAK ADA IZIN, BUKAN DINAS (IZIN DENGAN POTONGAN) & IZIN/DINAS YANG BELUM DI APPROVE
                                    if ($is_early_pot == 1) {
                                        if ($clock_out == null) {
                                            $tanggal_jam_izin_akhir = $tanggal . " " . $izin_dinas_out['jam_akhir']; //kalau no CO maka diambil jam izin
                                            $karbon_co = new Carbon($tanggal_jam_izin_akhir);
                                            $pot_jam_early_cek     = $karbon_co->diffInMinutes($tanggaljam_akhir);

                                            // $pot_jam_early_cek     = 0;
                                        } else {
                                            $karbon_co = new Carbon($clock_out);
                                            $pot_jam_early_cek     = $karbon_co->diffInMinutes($tanggaljam_akhir);
                                        }
                                        
                                        // hitung potongan jam early
                                        $pot_jam_early   = ceil($pot_jam_early_cek/60);
                                    }
                                }
                            } else {
                                if ($jadwal['id_htsxxmh'] != 1) {
                                    if ($st_clock_out != "OK") {
                                        $status_presensi_out = "Belum ada Izin";
                                        $cek = 1;
                                    } else {
                                        if (empty($rs_htlxxrh)) {
                                            //JIKA ST CLOCK IN OK DAN TIDAK ADA ABSEN MAKA DIBERI HK
                                            $status_presensi_out = "HK";
                                        }
                                        $cek = 0;
                                    }
                                }
                            }

                            if ($st_clock_out == "No CO" && $status_presensi_out != "HK") {
                                $cek = 1;
                            } else {
                               $cek = 0; 
                            }

                            // BEGIN cek izin ditengah jam kerja DENGAN POTONGAN
                            $qs_htlxxrh_izin = $db
                                ->query('select', 'htlxxrh' )
                                ->get([
                                    'htlxxrh.kode as htlxxrh_kode',
                                    'htlxxmh_kode as htlxxmh_kode',
                                    'htlgrmh.kode as htlgrmh_kode',
                                    'htpxxmh.is_potong_gaji as is_potong_gaji',
                                    'htlxxrh.is_approve as is_approve',
                                    'htlxxrh.jam_awal as jam_awal',
                                    'htlxxrh.jam_akhir as jam_akhir'
                                ] )
                                ->join('htpxxmh','htpxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                                ->join('htlgrmh','htlgrmh.id = htpxxmh.id_htlgrmh','LEFT' )
                                ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                                ->where('htlxxrh.tanggal', $tanggal )
                                ->where( function ( $r ) {
                                    $r
                                        ->where('htlxxrh.id_htlxxmh', 3 )
                                        ->or_where('htlxxrh.id_htlxxmh', 4 );
                                } )
                                ->where('htlxxrh.jenis', 2 )    // izin
                                ->exec();
                            $rs_htlxxrh_izin = $qs_htlxxrh_izin->fetchAll();
                            // ada kemungkinan izin lebih dari 1x selama jam kerja
                            foreach ($rs_htlxxrh_izin as $row_htlxxrh_izin) {
                                $kode_izin[] = $row_htlxxrh_izin['htlxxmh_kode'] . " [" . $row_htlxxrh_izin['htlxxrh_kode'] . "]";
                                
                                if ($row_htlxxrh_izin['is_potong_gaji']) {
                                    $tanggaljam_izin_awal    = new Carbon( $tanggal . ' ' . $row_htlxxrh_izin['jam_awal'] );
                                    if( $row_htlxxrh_izin['jam_awal'] < $row_htlxxrh_izin['jam_akhir']){
                                        $temp_tanggaljam_izin_akhir    = new Carbon( $tanggal . ' ' . $row_htlxxrh_izin['jam_akhir'] );
                                    }else{
                                        $temp_tanggaljam_izin_akhir    = new Carbon( $tanggal . ' ' . $row_htlxxrh_izin['jam_akhir'] );
                                        $temp_tanggaljam_izin_akhir->addDays(1);
                                    }
                                    $temp_tanggaljam_akhir_izin = $temp_tanggaljam_izin_akhir;
    
                                    $pot_jam_izin_cek       = $tanggaljam_izin_awal->diffInMinutes($temp_tanggaljam_akhir_izin);
                                    $pot_jam_izin_temp      = ceil($pot_jam_izin_cek/60);
                                    $pot_jam_izin           = $pot_jam_izin + $pot_jam_izin_temp;
                                }
                            
                            }
                        }

                        // print_r($htlxxrh_kode);
                        //JIKA ADA ABSEN DAN CEK
                        if (!empty($rs_htlxxrh) && $st_clock_in == 'No CI' && $st_clock_out == 'No CO') {
                            $cek = 0;
                        }

                        //JIKA CO DAN CI MAKA LANJUT CEK RANGE D JAM 5 DAN D+1 12
                        if(empty($rs_htlxxrh) && $st_clock_in == 'No CI' && $st_clock_out == 'No CO'){
                            //Cek apakah ada check clock antara Tgl 05:00:00 s/d Tgl + 1 12:00:00
                            $besok = date('Y-m-d', strtotime($tanggal . ' +1 day'));
                            // print_r($besok);
                            $qs_ceklok_D5_B12 = $db
                                ->query('select', 'htsprtd')
                                ->get([
                                    'CONCAT(htsprtd.tanggal, " ", htsprtd.jam) as dt_checkclock'
                                ])
                                ->where('htsprtd.kode', $row_hemxxmh['kode_finger'])
                                ->where('htsprtd.nama', '("os", "out", "staff", "pmi")', 'IN', false )
                                ->where('CONCAT(htsprtd.tanggal, " ", htsprtd.jam)', $tanggal . ' 05:00:00', '>=')
                                ->where('CONCAT(htsprtd.tanggal, " ", htsprtd.jam)', $besok . ' 12:00:00', '<=')                                    
                                ->order('CONCAT(htsprtd.tanggal, " ", htsprtd.jam)')
                                ->exec();
                            $rs_ceklok_D5_B12 = $qs_ceklok_D5_B12->fetch();

                            if (!empty($rs_ceklok_D5_B12)) {
                                $status_presensi_in = 'Jadwal Salah';
                                $status_presensi_out = 'Jadwal Salah';
                                $cek = 1;
                            } else {
                                $status_presensi_in = 'AL';
                                $status_presensi_out = 'AL';
                                $cek = 1;
                            }
                        }
                        //JIKA TIDAK ADA ABSEN DAN SALAH SATU STATUS PRESENSI BELUM ADA IZIN MAKA CEK 1
                        if(empty($rs_htlxxrh) && $status_presensi_in == "Belum ada Izin" || $status_presensi_out == "Belum ada Izin"){
                            $cek = 1;
                        }

                        $htlxxrh_kode = implode(', ', $kode_izin);

                        // print_r($pot_jam_late . '<br>');
                        // print_r($pot_jam_early . '<br>');

                        // gabungan dari potongan 
                        $pot_jam = $pot_jam_late + $pot_jam_izin + $pot_jam_early;

                        // SAMPAI LEMBUR KEBAWAH DEFAULT CODING LAMA

                        // BEGIN LEMBUR
                        // Jenis Lembur:
                        //   1: Awal | 2: Akhir | 4: Hari Libur | 5: Istirahat1 | 6: Istirahat2 | 7: Istirahat3

                        // Ambil data laporan lembur htoxxrd sesuai karyawan terpilih
                        // Bisa jadi dalam 1 hari, seorang karyawan lembur > 1 jenis
                        $qs_htoxxrd = $db
                            ->query('select', 'htoxxrd' )
                            ->get([
                                'htoxxrd.id_htotpmh as id_htotpmh',
                                'htoxxrd.tanggal as tanggal',
                                'htoxxrd.jam_awal as jam_awal',
                                'htoxxrd.jam_akhir as jam_akhir',
                                'htoxxrd.durasi_lembur_jam as durasi_lembur_jam',
                                'htoxxrd.durasi_lembur_menit as durasi_lembur_menit',
                                'htoxxrd.is_istirahat as is_istirahat'
                            ] )
                            ->where('htoxxrd.id_hemxxmh', $id_hemxxmh ) 
                            ->where('htoxxrd.is_active', 1 )
                            ->where('htoxxrd.tanggal', $tanggal )
                            ->exec();
            
                        $rs_htoxxrd = $qs_htoxxrd->fetchAll();

                        if(!empty($rs_htoxxrd)){    
                            // BEGIN looping lemburnya apa saja
                            $jam_awal_lembur_awal     = null;
                            $jam_akhir_lembur_awal    = null;
                            $durasi_lembur_awal_jam   = null;
                            $durasi_lembur_awal_menit = null;
                            
                            $jam_awal_lembur_akhir     = null;
                            $jam_akhir_lembur_akhir    = null;
                            $durasi_lembur_akhir_jam   = null;
                            $durasi_lembur_akhir_menit = null;
                            
                            $jam_awal_lembur_libur     = null;
                            $jam_akhir_lembur_libur    = null;
                            $durasi_lembur_libur_jam   = null;
                            $durasi_lembur_libur_menit = null;
                            
                            $jam_awal_lembur_istirahat1     = null;
                            $jam_akhir_lembur_istirahat1    = null;
                            $durasi_lembur_istirahat1_jam   = null;
                            $durasi_lembur_istirahat1_menit = null;

                            $jam_awal_lembur_istirahat2     = null;
                            $jam_akhir_lembur_istirahat2    = null;
                            $durasi_lembur_istirahat2_jam   = null;
                            $durasi_lembur_istirahat2_menit = null;

                            $jam_awal_lembur_istirahat3     = null;
                            $jam_akhir_lembur_istirahat3    = null;
                            $durasi_lembur_istirahat3_jam   = null;
                            $durasi_lembur_istirahat3_menit = null;

                            foreach ($rs_htoxxrd as $row_htoxxrd) {
                                if($row_htoxxrd['id_htotpmh'] == 1){
                                    $jam_awal_lembur_awal 		= $row_htoxxrd['jam_awal'];
                                    $jam_akhir_lembur_awal 		= $row_htoxxrd['jam_akhir'];
                                    $durasi_lembur_awal_jam         = $row_htoxxrd['durasi_lembur_jam'];
                                    $durasi_lembur_awal_menit   = $row_htoxxrd['durasi_lembur_menit'];
                                    
                                }
                                if($row_htoxxrd['id_htotpmh'] == 2){
                                    $jam_awal_lembur_akhir 		= $row_htoxxrd['jam_awal'];
                                    $jam_akhir_lembur_akhir 	= $row_htoxxrd['jam_akhir'];
                                    $durasi_lembur_akhir_jam    = $row_htoxxrd['durasi_lembur_jam'];
                                    $durasi_lembur_akhir_menit  = $row_htoxxrd['durasi_lembur_menit'];

                                }
                                
                                if($row_htoxxrd['id_htotpmh'] == 4){
                                    $jam_awal_lembur_libur 		= $row_htoxxrd['jam_awal'];
                                    $jam_akhir_lembur_libur 	= $row_htoxxrd['jam_akhir'];
                                    $durasi_lembur_libur_jam    = $row_htoxxrd['durasi_lembur_jam'];
                                    $durasi_lembur_libur_menit  = $row_htoxxrd['durasi_lembur_menit'];

                                }
                                
                                if($row_htoxxrd['id_htotpmh'] == 5){
                                    $jam_awal_lembur_istirahat1     = $row_htoxxrd['jam_awal'];
                                    $jam_akhir_lembur_istirahat1    = $row_htoxxrd['jam_akhir'];
                                    $durasi_lembur_istirahat1_jam   = $row_htoxxrd['durasi_lembur_jam'];
                                    $durasi_lembur_istirahat1_menit = $row_htoxxrd['durasi_lembur_menit'];
                                                
                                }
                                
                                if($row_htoxxrd['id_htotpmh'] == 6){
                                    $jam_awal_lembur_istirahat2     = $row_htoxxrd['jam_awal'];
                                    $jam_akhir_lembur_istirahat2    = $row_htoxxrd['jam_akhir'];
                                    $durasi_lembur_istirahat2_jam   = $row_htoxxrd['durasi_lembur_jam'];
                                    $durasi_lembur_istirahat2_menit = $row_htoxxrd['durasi_lembur_menit'];

                                }
                                
                                if($row_htoxxrd['id_htotpmh'] == 7){
                                    $jam_awal_lembur_istirahat3     = $row_htoxxrd['jam_awal'];
                                    $jam_akhir_lembur_istirahat3    = $row_htoxxrd['jam_akhir'];
                                    $durasi_lembur_istirahat3_jam   = $row_htoxxrd['durasi_lembur_jam'];
                                    $durasi_lembur_istirahat3_menit = $row_htoxxrd['durasi_lembur_menit'];
                                    
                                }
                                // if ($row_hemxxmh['id_hemxxmh'] == 1183) {
                                //     print_r($durasi_lembur_istirahat1_jam . '<br>');
                                //     print_r($durasi_lembur_istirahat3_jam);
                                // }
                                if($row_htoxxrd['id_htotpmh'] == 5 > 0 || $row_htoxxrd['id_htotpmh'] == 6 > 0 || $row_htoxxrd['id_htotpmh'] == 7 > 0){
                                    // BEGIN validasi TI
                                    // Ambil data checkclock Istirahat yang ada dalam range tersebut ( $durasi_break_menit )
                                    // untuk menentukan lama waktu istirahat yang diambil oleh karyawan
                                    // $durasi_break_menit = 0;
                                    // $potongan_ti_jam = 0;
                                    $qs_htsprtd_break = $db
                                        ->query('select', 'htsprtd' )
                                        ->get(['TIMESTAMPDIFF(MINUTE,MIN(CONCAT(htsprtd.tanggal," ",htsprtd.jam)),	MAX(CONCAT(htsprtd.tanggal," ",htsprtd.jam))) as durasi_break_menit'
                                        ] )
                                        ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                                        ->where('htsprtd.nama', 'istirahat' )
                                        ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal_istirahat, '>=' )
                                        ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir_istirahat, '<=' )
                                        ->order('concat(htsprtd.tanggal," ",htsprtd.jam)')
                                        ->exec();
                                    $rs_htsprtd_break = $qs_htsprtd_break->fetch();
                                    $durasi_break_menit = $rs_htsprtd_break['durasi_break_menit'];
                                    // if ($id_hemxxmh == 160) {
                                        // print_r($id_hemxxmh . ' == id_hemxxmh<br>');
                                        // print_r($tanggaljam_awal_istirahat . ' == tanggaljam_awal_istirahat<br>');
                                        // print_r($tanggaljam_akhir_istirahat . ' == tanggaljam_akhir_istirahat<br>');
                                        // print_r($durasi_break_menit . ' == durasi_break_menit<br>');
                                    // }
                                    if(!empty($rs_htsprtd_break)){
                                        // $potongan_ti_jam = 0;
                                        // jika $durasi_break_menit > 20, maka TI tidak berlaku
                                        // jika check clock istirahat gitang, dianggap tidak melewati 20 menit
                                        if($row_htoxxrd['is_istirahat'] == 2){
                                            if($durasi_break_menit > 20){
                                                $potongan_ti_menit = 30;
                                                $potongan_ti_jam = 0.5;

                                                $qu_htoemtd = $db
                                                    ->query('update', 'htoxxrd')
                                                    ->set('keterangan', 'Pengajuan TI ditolak')
                                                    ->where('id_hemxxmh',$id_hemxxmh)
                                                    ->where('tanggal',$tanggal)
                                                ->exec();
                                                // $htlxxrh_kode = $htlxxrh_kode . " [Pengajuan TI ditolak]";
                                            }else{
                                                $potongan_ti_menit = 0;
                                                $potongan_ti_jam = 0;
                                            }
                                        }else{
                                            $potongan_ti_menit = 0;
                                            $potongan_ti_jam = 0;
                                        }
                                        // END validasi TI
                                    }else{
                                        $potongan_ti_menit = 0;
                                        $potongan_ti_jam = 0;
                                    }
                                    // END validasi TI
                                }
                                //////// Testing potongan TI 25 JUl Organik
                                // if (in_array($id_hemxxmh, [150, 348, 1319])) {
                                //     print_r($id_hemxxmh . ' == id_hemxxmh<br>');
                                //     print_r($durasi_break_menit . ' == durasi_break_menit<br>');
                                //     print_r($potongan_ti_jam . ' == potongan_ti_jam<br>');
                                //     print_r($row_htoxxrd['is_istirahat'] . ' == is_istirahat<br>');
                                // }
                            } // end foreach $rs_htoxxrd

                            // hitung total lembur kotor
                            $durasi_lembur_total_menit = $durasi_lembur_awal_menit + $durasi_lembur_akhir_menit + $durasi_lembur_libur_menit + $durasi_lembur_istirahat1_menit + $durasi_lembur_istirahat2_menit + $durasi_lembur_istirahat3_menit;

                            $durasi_lembur_total_jam = $durasi_lembur_awal_jam + $durasi_lembur_akhir_jam + $durasi_lembur_libur_jam + $durasi_lembur_istirahat1_jam + $durasi_lembur_istirahat2_jam + $durasi_lembur_istirahat3_jam;
                        
                        }else{
                            $jam_awal_lembur_libur 		= null;
                            $jam_akhir_lembur_libur 	= null;
                            $jam_awal_lembur_awal 		= null;
                            $jam_akhir_lembur_awal 		= null;
                            $jam_awal_lembur_akhir 		= null;
                            $jam_akhir_lembur_akhir 	= null;
                            $jam_awal_lembur_istirahat1 = null;
                            $jam_akhir_lembur_istirahat1= null;
                            $jam_awal_lembur_istirahat2 = null;
                            $jam_akhir_lembur_istirahat2= null;
                            $jam_awal_lembur_istirahat3 = null;
                            $jam_akhir_lembur_istirahat3= null;
                            $durasi_lembur_awal_jam         = 0;
                            $durasi_lembur_akhir_jam        = 0;
                            $durasi_lembur_libur_jam        = 0;
                            $durasi_lembur_istirahat1_jam   = 0;
                            $durasi_lembur_istirahat2_jam   = 0;
                            $durasi_lembur_istirahat3_jam   = 0;

                            $durasi_lembur_awal_menit         = 0;
                            $durasi_lembur_akhir_menit        = 0;
                            $durasi_lembur_libur_menit        = 0;
                            $durasi_lembur_istirahat1_menit   = 0;
                            $durasi_lembur_istirahat2_menit   = 0;
                            $durasi_lembur_istirahat3_menit   = 0;
                            $durasi_lembur_total_jam          = 0;
                        }

                        // POTONGAN JAM
                        // Aturan potongan jam adalah kelipatan 1 jam, pembulatan ke atas. Dilihat dari Jam bulat, contoh 08:00, 09:00, 10:00 dst
                        // kondite yang mempengaruhi potongan adalah jika ada:
                        //  - Terlambat diluar toleransi 5 menit
                        //  - Izin Pribadi ditengah-tengah jam kerja
                        //  - Pulang Awal
                        // Potongan diberlakukan dengan urutan:
                        //  - Potong jam lembur (Jika ada lembur)
                        //  - Potong jam kerja (JIka jam lembur tidak mencukupi untuk dipotong )
                        // Ada juga persyaratan TI
                        //  TI Hangus jika
                        // - Istirahat > 20 menit
                        // - Jam kerja tidak penuh 7 atau 8 jam
                        //
                        // Contoh Kasus:
                        //  Jam kerja: 07:00 s/d 15:00, Istirahat 12:00 s/d 13:00
                        //  Karyawan Lembur: 
                        //      TI (0.5 jam)
                        //      Lembur Akhir (2 jam)
                        //      Maka harusnya total lembur akan menjadi 2.5 jam
                        //  Alternatif 
                        //      - Jika karyawan tidak ada kondite, maka:
                        //          Total Lembur                : 2.5 jam
                        //          Potongan Jam atas Lembur    : 0
                        //          Potongan Jam Diluar Lembur  : 0
                        //          Total Lembur Final          : 2.5 jam
                        //      - Jika Karyawan Terlambat < 5 menit
                        //          Total Lembur                : 2.5 jam
                        //          Potongan Jam atas Lembur    : 0
                        //          Potongan Jam Diluar Lembur  : 0
                        //          Total Lembur Final          : 2.5 jam
                        //      - Jika Karyawan Terlambat > 5 menit
                        //          Akan diberlakukan potongan atas terlambat mulai menit ke 6, berlaku kelipatan 1 jam sejak menit ke-6
                        //          contoh
                        //              Jika Check In: 07:06 : potongan 1 jam
                        //                  Total Lembur                : 2.5 jam
                        //                  Potongan Jam atas Lembur    : 1 jam
                        //                  Potongan Jam Diluar Lembur  : 0
                        //                  Total Lembur Final          : 1.5 jam
                        //              Check In: 08:00 : potongan 1 jam (karena belum 8:05)
                        //                  Total Lembur                : 2.5 jam
                        //                  Potongan Jam atas Lembur    : 1 jam
                        //                  Potongan Jam Diluar Lembur  : 0
                        //                  Total Lembur Final          : 1.5 jam
                        //              Check In: 08:06 : potongan 2 jam
                        //                  Total Lembur                : 2.5 jam
                        //                  Potongan Jam atas Lembur    : 2 jam
                        //                  Potongan Jam Diluar Lembur  : 0
                        //                  Total Lembur Final          : 0.5 jam
                        //      - Jika Karyawan Izin Pribadi ditengah jam kerja
                        //          Akan dihitung izinnya berapa lama, per kelipatan pembulatan keatas 1 jam akan menjadi potongannya
                        //      - Jika Karyawan Pulang Awal
                        //          Akan dihitung selisih jam check out dengan jadwalnya berapa lama, per kelipatan pembulatan keatas 1 jam akan menjadi potongannya
                        
                        // - $potongan_ti     : potongan jika ada TI
                        //      +----+------------------------------------------+----------+
                        //      |    | is_istirahat = 2 artinya lembur TI       | pot mins |
                        //      +----+------------------------------------------+----------+
                        //      |    | is_istirahat = 2 && break > 20           | 30       |
                        //      |    | is_istirahat = 2 && break <= 20          | 0        |
                        //      |    | is_istirahat <> 2                        | 0        |
                        //      +----+------------------------------------------+----------+
            
                        // hitung final durasi lembur
                        // mungkin $potongan_ti masih salah
                        // print_r($durasi_lembur_total_jam);

                        //POTONGAN OVERTIME 
                        //Apabila : Total Potongan(pot_jam) < Durasi total overtime(durasi_lembur_total_jam)
                        //add by ferry 
                        if ($pot_jam < $durasi_lembur_total_jam) {
                            $pot_overtime = $pot_jam - $potongan_ti_jam;
                        } else {
                            $pot_overtime = $durasi_lembur_total_jam - $potongan_ti_jam;
                        }

                        //Potongan Hari Kerja = total potongan - (potongan overtime + potongan TI)		
                        $pot_hk = $pot_jam - $pot_overtime + $potongan_ti_jam;


                        //Overtime = Total durasi lembur - (potongan TI + potongan overtime)		
                        $jam_pengali = $durasi_lembur_total_jam - $potongan_ti_jam + $pot_overtime;
                        $durasi_lembur_final = $jam_pengali;

                        // jika $jam_pengali > 0, maka ada lembur
                        // jika $jam_pengali < 0, maka ada potongan gaji
            
                        // BEGIN hitung nominal lembur
                        // hanya jika $jam_pengali > 0
                        if($jam_pengali > 0){
                            $pot_jam_final = 0;
                            // cek upah lembur per jam
                            //      jika hesxxmh = 3 (pelatihan), maka menggunakan lembur jam mati, data dari htpr_hesxxmh.id_hpcxxmh = 36
                            //      selainnya itu menggunakan lembur jam hidup
                            if($row_hemxxmh['id_hesxxmh'] == 3){
                                // jam mati
                                $lembur1 = $jam_pengali;
                                $lembur2 = 0;
                                $jam_perkalian_lembur = $lembur1 + $lembur2;
                                // ambil upah lembur per jam
                                $qr_htpr_hesxxmh = $db
                                    ->raw()
                                    ->bind(':tanggal_lembur', $tanggal)
                                    ->bind(':id_hesxxmh', 3)
                                    ->exec('
                                        SELECT 
                                            nominal
                                        FROM (
                                            SELECT
                                                id,
                                                tanggal_efektif,
                                                nominal,
                                                ROW_NUMBER() OVER (PARTITION BY id_hesxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                            FROM htpr_hesxxmh
                                            WHERE 
                                                htpr_hesxxmh.id_hpcxxmh = 36 AND
                                                tanggal_efektif < :tanggal_lembur AND
                                                id_hesxxmh = :id_hesxxmh
                                        ) AS subquery
                                        WHERE row_num = 1
                                    ');
                                $rs_htpr_hesxxmh    = $qr_htpr_hesxxmh->fetch();
                                $nominal_lembur_jam = $rs_htpr_hesxxmh['nominal'];
                                $nominal_lembur_final = $nominal_lembur_jam;
                                $tot_komp_lembur = 0;
                            }else{
                                // jam hidup
                                if($jam_pengali > 7){
                                    $lembur1 = 7 * 2;
                                    $lembur2 = ($jam_pengali-7) * 3;
                                }else{
                                    $lembur1 = $jam_pengali * 2;
                                    $lembur2 = 0;
                                }
                                $jam_perkalian_lembur = $lembur1 + $lembur2;
                                
                                // ambil upah lembur per jam
                                // BEGIN select data hpcxxmh is_komp_lembur = 1
                                // GP dan Tunjuangan Jabatan
            
                                //  GP
                                $qr_gp = $db
                                    ->raw()
                                    ->bind(':is_active', 1)
                                    ->bind(':id_hemxxmh', $id_hemxxmh)
                                    ->bind(':tanggal_awal', $tanggal )
                                    ->exec('
                                        SELECT 
                                            subquery.nominal_gp as nominal_gp
                                        FROM
                                        (
                                            SELECT
                                                id,
                                                nominal as nominal_gp,
                                                ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                            FROM htpr_hemxxmh
                                            WHERE 
                                                htpr_hemxxmh.is_active = :is_active AND
                                                htpr_hemxxmh.id_hpcxxmh = 1 AND
                                                htpr_hemxxmh.id_hemxxmh = :id_hemxxmh AND
                                                tanggal_efektif < :tanggal_awal
                                        ) AS subquery
                                        WHERE subquery.row_num = 1
                                    ');
                                $rs_gp = $qr_gp->fetch();
                                if(!empty($rs_gp)){
                                    $nominal_gp = $rs_gp['nominal_gp'];
                                }else{
                                    $nominal_gp = 0;
                                }
            
                                //  Tunjangan Jabatan
                                $qr_tjab = $db
                                    ->raw()
                                    ->bind(':is_active', 1)
                                    ->bind(':id_hemxxmh', $id_hemxxmh)
                                    ->bind(':tanggal_awal', $tanggal )
                                    ->exec('
                                        SELECT 
                                            nominal as nominal_tjab
                                        FROM (
                                            SELECT
                                                htpr_hevxxmh.nominal,
                                                ROW_NUMBER() OVER (PARTITION BY htpr_hevxxmh.id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                                            FROM htpr_hevxxmh
                                            LEFT JOIN hevxxmh ON hevxxmh.id = htpr_hevxxmh.id_hevxxmh
                                            LEFT JOIN hemjbmh ON hemjbmh.id_hevxxmh = hevxxmh.id
                                            WHERE 
                                                htpr_hevxxmh.is_active = :is_active AND
                                                htpr_hevxxmh.id_hpcxxmh = 33 AND
                                                hemjbmh.id_hemxxmh = :id_hemxxmh AND
                                                htpr_hevxxmh.tanggal_efektif < :tanggal_awal
                                        ) AS subquery
                                        WHERE subquery.row_num = 1
                                    ');
                                $rs_tjab = $qr_tjab->fetch();
                                if(!empty($rs_tjab)){
                                    $nominal_tjab = $rs_tjab['nominal_tjab'];
                                }else{
                                    $nominal_tjab = 0;
                                }
            
                                $tot_komp_lembur        = $nominal_gp + $nominal_tjab;
                                $nominal_lembur_jam     = floor($tot_komp_lembur / 173);
                                // END select data hpcxxmh is_komp_lembur = 1

                                $nominal_lembur_final = $jam_perkalian_lembur * $nominal_lembur_jam;
            
                            }
                        }elseif($jam_pengali < 0){
                            // jika disini, nilai $jam_pengali harusnya < 0
                            // artinya total jam izin > dari total lembur, sehingga harus memotong jam kerja normal ($pot_jam_final)
                            // ini di absolute-kan
                            $pot_jam_final = abs($jam_pengali);
                            $durasi_lembur_final = 0;
                            $nominal_lembur_jam = 0;
                            $nominal_lembur_final = 0;
                        }

                        // cek apakah ada makan
                        // ditandai dengan check clock makan
                        // concat(htsprtd.tanggal," ",htsprtd.jam)
                        $qs_htsprtd_makan = $db
                            ->query('select', 'htsprtd' )
                            ->get([
                                'id'
                            ] )
                            ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                            ->where('htsprtd.nama', '("makan", "makan manual")', 'IN', false ) // tambah makan manual
                            ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal_t1, '>=' )
                            ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir_t2, '<=' )
                            ->order('concat(htsprtd.tanggal," ",htsprtd.jam)')
                            ->exec();
                        $rs_htsprtd_makan = $qs_htsprtd_makan->fetch();

                        if( !empty($rs_htsprtd_makan) ){
                            if ($row_hemxxmh['is_pot_makan'] == 1) {
                                $is_makan = 1;
                            }
                        }


                        //INSERT KE REPORT PRESENSI
                        $qi_htsprrd = $db
                            ->query('insert', 'htsprrd')
                            ->set('id_hemxxmh',$id_hemxxmh)
                            ->set('keterangan',$keterangan)
                            ->set('kode_finger', $kode_finger)
                            ->set('tanggal',$tanggal)
                            
                            ->set('shift_in',$shift_in)
                            ->set('shift_out',$shift_out)
                            ->set('st_jadwal',$st_jadwal)
                            ->set('tanggaljam_awal_t1',$tanggaljam_awal_t1)
                            ->set('tanggaljam_awal',$tanggaljam_awal)
                            ->set('tanggaljam_awal_t2',$tanggaljam_awal_t2)
                            ->set('tanggaljam_akhir_t1',$tanggaljam_akhir_t1)
                            ->set('tanggaljam_akhir',$tanggaljam_akhir)
                            ->set('tanggaljam_akhir_t2',$tanggaljam_akhir_t2)
                            ->set('clock_in',$clock_in)
                            ->set('clock_out',$clock_out)
                            ->set('st_clock_in',$st_clock_in)
                            ->set('st_clock_out',$st_clock_out)
                            ->set('status_presensi_in',$status_presensi_in)
                            ->set('status_presensi_out',$status_presensi_out)
                            ->set('htlxxrh_kode',$htlxxrh_kode)
                            
                            ->set('jam_awal_lembur_libur',$jam_awal_lembur_libur)
                            ->set('jam_akhir_lembur_libur',$jam_akhir_lembur_libur)
                            ->set('durasi_lembur_libur',$durasi_lembur_libur_jam)

                            ->set('jam_awal_lembur_awal',$jam_awal_lembur_awal)
                            ->set('jam_akhir_lembur_awal',$jam_akhir_lembur_awal)
                            ->set('durasi_lembur_awal',$durasi_lembur_awal_jam)
                            ->set('jam_awal_lembur_akhir',$jam_awal_lembur_akhir)
                            ->set('jam_akhir_lembur_akhir',$jam_akhir_lembur_akhir)
                            ->set('durasi_lembur_akhir',$durasi_lembur_akhir_jam)
                            ->set('jam_awal_lembur_istirahat1',$jam_awal_lembur_istirahat1)
                            ->set('jam_akhir_lembur_istirahat1',$jam_akhir_lembur_istirahat1)
                            ->set('durasi_lembur_istirahat1',$durasi_lembur_istirahat1_jam)
                            ->set('jam_awal_lembur_istirahat2',$jam_awal_lembur_istirahat2)
                            ->set('jam_akhir_lembur_istirahat2',$jam_akhir_lembur_istirahat2)
                            ->set('durasi_lembur_istirahat2',$durasi_lembur_istirahat2_jam)
                            ->set('jam_awal_lembur_istirahat3',$jam_awal_lembur_istirahat3)
                            ->set('jam_akhir_lembur_istirahat3',$jam_akhir_lembur_istirahat3)
                            ->set('durasi_lembur_istirahat3',$durasi_lembur_istirahat3_jam)
                            ->set('durasi_lembur_total_jam', $durasi_lembur_total_jam)
                            ->set('pot_jam', $pot_jam)
                            ->set('pot_overtime', $pot_overtime)
                            ->set('pot_hk', $pot_hk)
                            ->set('pot_ti', $potongan_ti_jam)
                            ->set('durasi_lembur_final', $durasi_lembur_final)
                            ->set('pot_jam_final', $pot_jam_final)
                            ->set('nominal_lembur_jam', $nominal_lembur_jam)
                            ->set('nominal_lembur_final', $nominal_lembur_final)
                            ->set('is_makan', $is_makan)
                            ->set('cek', $cek)
                        ->exec();
                    }
                }else{
                    // jika jadwal belum dibuat
                    $shift_in  = null;
                    $shift_out = null;
                    $st_jadwal = 'NJ';

                    $htlxxrh_kode           = 'NJ';
                    $status_presensi_in     = 'NJ';
                    $status_presensi_out    = 'NJ';

                    //INSERT ALAKADARNYA KARENA SEDIKIT YANG BISA DILAKUKAN SAAT JADWAL BELUM DIBUAT
                    $qi_htsprrd = $db
                        ->query('insert', 'htsprrd')
                        ->set('id_hemxxmh',$id_hemxxmh)
                        ->set('keterangan',$keterangan)
                        ->set('kode_finger', $kode_finger)
                        ->set('tanggal',$tanggal)
                        
                        ->set('shift_in',$shift_in)
                        ->set('shift_out',$shift_out)
                        ->set('st_jadwal',$st_jadwal)
                        ->set('status_presensi_in',$status_presensi_in)
                        ->set('status_presensi_out',$status_presensi_out)
                        ->set('htlxxrh_kode',$htlxxrh_kode)
                    ->exec();

                }
            }
        }
        // di commit per karyawan
        $qu_hgtprth = $db
            ->query('update', 'hgtprth')
            ->set('generated_on',$timestamp)
            ->where('id_heyxxmh',$id_heyxxmh)
            ->where('tanggal',$tanggal)
        ->exec();
        
        $db->commit();
        
        $akhir = new Carbon();
        
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