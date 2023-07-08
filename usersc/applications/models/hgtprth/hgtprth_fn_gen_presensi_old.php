<?php
    /**
     * status_presensi
     *  - NJ 
     * 
     * L01  : Cek apakah jadwal sudah dibuat
     *          - jika belum        : status_presensi = NJ 
     *          - jika sudah        : ke 02
     * L02  : Cek apakah ada absen dari table htlxxrh
     *          - jika ada          : status_presensi = kode absensi ( htlxxth_kode )
     *          - jika tidak ada    : ke 03
     * L03  : Cek apakah check clock sesuai jadwal
     *          - jika sesuai       : status_presensi = OK (atau jam check in, check out)
     *          - jika tidak        : cek apakah ada izin ( htpxxth ), status_presensi = htpxxth_kode (ke 04)
     *      L03-a   : Check In
     *      L03-b   : Check Out
     * L04 : Cek apakan ada Izin
     *      L04-a   : Check Izin Awal
     *      L04-b   : Check Izin Akhir
     * 
     *  	    $st_clock_in dan $st_clock_out kayanya tidak diperlukan lagi
     */
    
	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
    
    $tanggal = new Carbon( $values['hgtprth']['tanggal'] );
    $tanggal = $tanggal->format('Y-m-d');
    
    try{

        // BEGIN delete old data
        $qd_htsprrd = $editor->db()
            ->query('delete', 'htsprrd')
            ->where('htsprrd.tanggal',$tanggal)
            ->exec();
        // END delete old data

        $qs_hemxxmh = $editor->db()
            ->query('select', 'hemxxmh' )
            ->get([
                'hemxxmh.id as id_hemxxmh',
                'hemxxmh.kode_finger as kode_finger'
            ] )
            ->join('hemjbmh','hemjbmh.id_hemxxmh = hemxxmh.id','LEFT' )
            // ->where( function ( $q ) use ($tanggal) {
            //     $q
            //       ->where( 'hemjbmh.tanggal_keluar', '0000-00-00')
            //       ->or_where( 'hemjbmh.tanggal_keluar', $tanggal , '<' );
            // } )
            // ->where('hemxxmh.id', 80 )   // untuk tes
            ->where('hemxxmh.is_active', 1 )
            ->where('hemjbmh.is_checkclock', 1 ) // skip yang tidak perlu checkclock
            ->exec();
        $rs_hemxxmh = $qs_hemxxmh->fetchAll();

        if (count($rs_hemxxmh) > 0){
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
                $clock_in = null;
                $clock_out = null;
                $st_clock_in = "";
                $st_clock_out = "";

                // BEGIN select jadwal
                $qs_htssctd = $editor->db()
                    ->query('select', 'htssctd' )
                    ->get([
                        'htssctd.id_htsxxmh as id_htsxxmh',
                        'htsxxmh.kode as htsxxmh_kode',
                        'htssctd.jam_awal as jam_awal',
                        'htssctd.jam_akhir as jam_akhir',
                        'htssctd.jam_awal_istirahat as jam_awal_istirahat',
                        'htssctd.jam_akhir_istirahat as jam_akhir_istirahat',
                        'htssctd.menit_toleransi_awal_in as menit_toleransi_awal_in',
                        'htssctd.menit_toleransi_akhir_in as menit_toleransi_akhir_in',
                        'htssctd.menit_toleransi_awal_out as menit_toleransi_awal_out',
                        'htssctd.menit_toleransi_akhir_out as menit_toleransi_akhir_out'
                    ] )
                    ->join('htsxxmh','htsxxmh.id = htssctd.id_htsxxmh','LEFT')
                    ->where('htssctd.id_hemxxmh', $id_hemxxmh )
                    ->where('htssctd.tanggal', $tanggal )
                    ->limit(1)
                    ->exec();
                $rs_htssctd = $qs_htssctd->fetchAll();
                
                // L01: cek apakah sudah ada jadwal
                // BEGIN cek jadwal 
                if (count($rs_htssctd) > 0){

                    // jika jadwal sudah dibuat
                    $shift_in  = $rs_htssctd[0]['jam_awal'];
                    $shift_out = $rs_htssctd[0]['jam_akhir'];
                    $st_jadwal = $rs_htssctd[0]['htsxxmh_kode'];

                    // jika OFF
                    if($rs_htssctd[0]['id_htsxxmh'] == 1){
                        $status_presensi_in = $rs_htssctd[0]['htsxxmh_kode'];
                        $status_presensi_out = $rs_htssctd[0]['htsxxmh_kode'];
                    }else{
                        // Jika tidak OFF

                        // L02: Cek Apakah ada Absen
                        $qs_htlxxrh = $editor->db()
                            ->query('select', 'htlxxrh' )
                            ->get([
                                'htlxxrh.kode as htlxxrh_kode',
                                'htlxxmh_kode as htlxxmh_kode',
                                'htlgrmh.kode as htlgrmh_kode'
                            ] )
                            ->join('htlxxmh','htlxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                            ->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
                            ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                            ->where('htlxxrh.tanggal', $tanggal )
                            ->where('htlxxrh.jenis', 1 )    // absensi
                            /**
                             * Bisa ditambahkan exclude where id_htlgrmh atau id_htlxxmh
                             * ->where('htlxxrh.id_htlxxmh', 20, '<>' )
                             * Tergantung urutan pengecekan sesuai kebutuhan klien
                             * Misal, 
                             * case 1:
                             *      hari libur nasional lebih tinggi urutannya dibanding check clock, 
                             *      maka hari libur TIDAK perlu di exclude kan, karena check clock akan diabaikan
                             * 
                             * case 2:
                             *      check clock lebih tinggi urutannya dari hari libur
                             *      maka di bagian ini hari libur harus di exclude kan
                             *      hari libur akan di check di bagian L04 
                             */
                            ->limit(1)
                            ->exec();
                        $rs_htlxxrh = $qs_htlxxrh->fetchAll();
                        if(count($rs_htlxxrh) > 0){
                            // jika ada absen
                            $status_presensi_in = $rs_htlxxrh[0]['htlgrmh_kode'];
                            $status_presensi_out = $rs_htlxxrh[0]['htlgrmh_kode'];

                            $keterangan = $keterangan . " - " . $rs_htlxxrh[0]['htlxxrh_kode'];
                        }else{
                            // jika tidak ada absensi
                            $htlxxrh_kode       = '';

                            // L03: Cek Apakah Check Clock sesuai Jadwal
                            // Ambil data Shift
                            // Hitung Jam Toleransi
                            // Toleransi Masuk
                            $temp_tanggaljam_awal    = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_awal'] );
                            $tanggaljam_awal = $temp_tanggaljam_awal->format('Y-m-d H:i:s');
                            
                            $temp_tanggaljam_awal_t1 = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_awal'] );
                            $tanggaljam_awal_t1 = $temp_tanggaljam_awal_t1
                                ->subMinutes( $rs_htssctd[0]['menit_toleransi_awal_in'] )
                                ->format('Y-m-d H:i:s');
                            
                            $temp_tanggaljam_awal_t2 = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_awal'] );
                            $tanggaljam_awal_t2 = $temp_tanggaljam_awal_t2
                                ->addMinutes($rs_htssctd[0]['menit_toleransi_akhir_in'])
                                ->format('Y-m-d H:i:s');                        

                            // Toleransi Pulang
                            if( $rs_htssctd[0]['jam_awal'] < $rs_htssctd[0]['jam_akhir']){
                                $temp_tanggaljam_akhir    = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_akhir'] );
                            }else{
                                $temp_tanggaljam_akhir    = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_akhir'] );
                                $temp_tanggaljam_akhir->addDays(1);
                            }
                            $tanggaljam_akhir = $temp_tanggaljam_akhir->format('Y-m-d H:i:s');
        
                            $temp_tanggaljam_akhir_t1 = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_akhir'] );
                            $tanggaljam_akhir_t1 = $temp_tanggaljam_akhir_t1
                                ->subMinutes( $rs_htssctd[0]['menit_toleransi_awal_out'] )
                                ->format('Y-m-d H:i:s');
                            
                            $temp_tanggaljam_akhir_t2 = new Carbon( $values['hgtprth']['tanggal'] . ' ' . $rs_htssctd[0]['jam_akhir'] );
                            $tanggaljam_akhir_t2 = $temp_tanggaljam_akhir_t2
                                ->addMinutes($rs_htssctd[0]['menit_toleransi_akhir_out'])
                                ->format('Y-m-d H:i:s');

                            // BEGIN get Data Clock
                            $cekizin_in = 0;
                            $cekizin_out = 0;

                            // L03-a: Check In
                            // BEGIN check clock_in
                            $qs_htsprtd_clock_in = $editor->db()
                                ->query('select', 'htsprtd' )
                                ->get([
                                    'concat(htsprtd.tanggal," ",htsprtd.jam) as dt_checkclock'
                                ] )
                                ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                                ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal_t1, '>=' )
                                ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal, '<=' )
                                ->order('concat(htsprtd.tanggal," ",htsprtd.jam)')
                                ->exec();
                            $rs_htsprtd_clock_in = $qs_htsprtd_clock_in->fetch();

                            if(isset($rs_htsprtd_clock_in['dt_checkclock'])){
                                // clock_in dalam rentang
                                $clock_in           = $rs_htsprtd_clock_in['dt_checkclock'];
                                $st_clock_in        = 'OK';
                                $status_presensi_in = 'OK';
                                
                            }else{
                                // jika tidak ada antara toleransi awal dan jam awal, cek apakah terlambat
                                // clock_in_late
                                $qs_htsprtd_clock_in_late = $editor->db()
                                    ->query('select', 'htsprtd' )
                                    ->get([
                                        'concat(htsprtd.tanggal," ",htsprtd.jam) as dt_checkclock'
                                    ] )
                                    ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                                    ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal, '>=' )
                                    ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_awal_t2, '<=' )
                                    ->order('concat(htsprtd.tanggal," ",htsprtd.jam)')
                                    ->exec();
                                $rs_htsprtd_clock_in_late = $qs_htsprtd_clock_in_late->fetch();

                                if(isset($rs_htsprtd_clock_in_late['dt_checkclock'])){
                                    $clock_in       = $rs_htsprtd_clock_in_late['dt_checkclock'];
                                    $st_clock_in    = 'LATE';  // TERLAMBAT
                                    $status_presensi_in = 'OK';

                                    $cekizin_in = 1; // dipakai di L04
                                }else{
                                    $clock_in       = null;
                                    $st_clock_in    = 'No CI';
                                    $status_presensi_in = 'No CI';
                                }

                            }
                            // END check clock_in

                            // L03-b: Check Out
                            // BEGIN check clock_out
                            $qs_htsprtd_clock_out = $editor->db()
                                ->query('select', 'htsprtd' )
                                ->get([
                                    'concat(htsprtd.tanggal," ",htsprtd.jam) as dt_checkclock'
                                ] )
                                ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                                ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir, '>=' )
                                ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir_t2, '<=' )
                                ->order('concat(htsprtd.tanggal," ",htsprtd.jam)')
                                ->exec();
                            $rs_htsprtd_clock_out = $qs_htsprtd_clock_out->fetch();
                            if(isset($rs_htsprtd_clock_out['dt_checkclock'])){
                                // clock_out dalam rentang
                                $clock_out              = $rs_htsprtd_clock_out['dt_checkclock'];
                                $st_clock_out           = 'OK';
                                $status_presensi_out    = 'OK';
                                
                            }else{
                                // jika tidak ada antara toleransi awal dan jam_akhir, cek apakah pulang awal
                                // clock_out_early
                                $qs_htsprtd_clock_out_early = $editor->db()
                                    ->query('select', 'htsprtd' )
                                    ->get([
                                        'concat(htsprtd.tanggal," ",htsprtd.jam) as dt_checkclock'
                                    ] )
                                    ->where('htsprtd.kode', $row_hemxxmh['kode_finger'] )
                                    ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir_t1, '>=' )
                                    ->where('concat(htsprtd.tanggal," ",htsprtd.jam)', $tanggaljam_akhir, '<=' )
                                    ->order('concat(htsprtd.tanggal," ",htsprtd.jam)')
                                    ->exec();
                                $rs_htsprtd_clock_out_early = $qs_htsprtd_clock_out_early->fetch();

                                if(isset($rs_htsprtd_clock_out_early['dt_checkclock'])){
                                    $clock_out      = $rs_htsprtd_clock_out_early['dt_checkclock'];
                                    $st_clock_out   = 'EARLY';    // PULANG AWAL
                                    $status_presensi_out = 'OK';

                                    $cekizin_out = 1; // dipakai di L04
                                }else{
                                    $clock_out = null;
                                    $st_clock_out = 'No CO';
                                    $status_presensi_out = 'No CO';
                                }

                            }
                            // END check clock_out

                            // L04
                            // BEGIN tentukan status_presensi
                            // untuk yang No CI, No CO
                            if($st_clock_in == 'No CI' && $st_clock_out == 'No CO'){
                                /**
                                 * Lanjutan dari L02
                                 * Jika urutan check clock lebih tinggi dari absensi tertentu, 
                                 * maka dilakukan dilakukan pengencekan di bagian ini
                                 * sesuaikan bagian where $id_htlxxmh_exclude
                                 */

                                /*
                                $qs_htlxxrh_exclude = $editor->db()
                                    ->query('select', 'htlxxrh' )
                                    ->get([
                                        'htlxxrh.kode as htlxxrh_kode',
                                        'htlxxmh_kode as htlxxmh_kode',
                                        'htlgrmh.kode as htlgrmh_kode'
                                    ] )
                                    ->join('htlxxmh','htlxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                                    ->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
                                    ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                                    ->where('htlxxrh.id_htlxxmh', $id_htlxxmh_exclude , '<>' )
                                    ->where('htlxxrh.tanggal', $tanggal )
                                    ->limit(1)
                                    ->exec();
                                $rs_htlxxrh_exclude = $qs_htlxxrh_exclude->fetchAll();

                                if(count($rs_hthhdth) > 0){
                                    $htlxxrh_kode           = $rs_htlxxrh_exclude[0]['htlxxmh_kode];
                                    $status_presensi_in     = $rs_htlxxrh_exclude[0]['htlxxmh_kode];
                                    $status_presensi_out    = $rs_htlxxrh_exclude[0]['htlxxmh_kode];
                                }else{
                                    $htlxxrh_kode           = 'AL';
                                    $status_presensi_in     = "AL";
                                    $status_presensi_out    = "AL";
                                }
                                */

                                /**
                                 * Jika check clock lebih tinggi dari absensi tertentu, 
                                 * maka karyawan dinyatakan Alpa, 
                                 * karena tidak ada check in dan check out
                                 */
                                $htlxxrh_kode           = 'AL';
                                $status_presensi_in     = "AL";
                                $status_presensi_out    = "AL";

                                
                            }else{

                                if($cekizin_in == 1){
                                    // id_htpxxmh = 1
                                    $qs_htlxxrh_in = $editor->db()
                                        ->query('select', 'htlxxrh' )
                                        ->get([
                                            'htlxxrh.kode as htlxxrh_kode',
                                            'htlxxmh_kode as htlxxmh_kode',
                                            'htlgrmh.kode as htlgrmh_kode'
                                        ] )
                                        ->join('htlxxmh','htlxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                                        ->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
                                        ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                                        ->where('htlxxrh.tanggal', $tanggal )
                                        ->where('htlxxrh.id_htlxxmh', 1)
                                        ->where('htlxxrh.jenis', 2 )    // izin
                                        ->exec();
                                    $rs_htlxxrh_in = $qs_htlxxrh_in->fetchAll();

                                    // $qs_htpxxth_in = $editor->db()
                                    //     ->query('select', 'htpxxth' )
                                    //     ->get([
                                    //         'htpxxmh.kode as status_presensi',
                                    //         'htpxxth.kode as htpxxth_kode',
                                    //         'htpxxth.is_approve as is_approve'
                                    //     ] )
                                    //     ->join('htpxxmh','htpxxmh.id = htpxxth.id_htpxxmh','LEFT' )
                                    //     ->where('htpxxth.id_hemxxmh', $id_hemxxmh)
                                    //     ->where('htpxxth.id_htpxxmh', 1)
                                    //     ->where('htpxxth.tanggal', $tanggal )
                                    //     ->limit(1)
                                    //     ->exec();
                                    // $rs_htpxxth_in = $qs_htpxxth_in->fetchAll();

                                    if(count($rs_htlxxrh_in) > 0){
                                        // jika ada data izin
                                        // check apakah disetujui
                                        if($rs_htlxxrh_in[0]['is_approve'] == 1){
                                            $status_presensi_in = $rs_htlxxrh_in[0]['status_presensi'];
                                        }else{
                                            $status_presensi_in = 'Izin Belum Disetujui';
                                        }
                                        $keterangan =  $keterangan . " - " . $rs_htlxxrh_in[0]['htpxxth_kode'];
                                    }else{
                                        // tidak membuat izin
                                        $status_presensi_in = 'Belum ada Izin';
                                        $htpxxth_kode = '';
                                    }

                                }

                                if($cekizin_out == 1){
                                    // id_htpxxmh = 2
                                    $qs_htlxxrh_out = $editor->db()
                                        ->query('select', 'htlxxrh' )
                                        ->get([
                                            'htlxxrh.kode as htlxxrh_kode',
                                            'htlxxmh_kode as htlxxmh_kode',
                                            'htlgrmh.kode as htlgrmh_kode'
                                        ] )
                                        ->join('htlxxmh','htlxxmh.id = htlxxrh.id_htlxxmh','LEFT' )
                                        ->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
                                        ->where('htlxxrh.id_hemxxmh', $id_hemxxmh)
                                        ->where('htlxxrh.tanggal', $tanggal )
                                        ->where('htlxxrh.id_htlxxmh', 2)
                                        ->where('htlxxrh.jenis', 2 )    // izin
                                        ->exec();
                                    $rs_htlxxrh_out = $qs_htlxxrh_out->fetchAll();

                                    // $qs_htpxxth_out = $editor->db()
                                    //     ->query('select', 'htpxxth' )
                                    //     ->get([
                                    //         'htpxxmh.kode as status_presensi',
                                    //         'htpxxth.kode as htpxxth_kode',
                                    //         'htpxxth.is_approve as is_approve'
                                    //     ] )
                                    //     ->join('htpxxmh','htpxxmh.id = htpxxth.id_htpxxmh','LEFT' )
                                    //     ->where('htpxxth.id_hemxxmh', $id_hemxxmh)
                                    //     ->where('htpxxth.id_htpxxmh', 2)
                                    //     ->where('htpxxth.tanggal', $tanggal )
                                    //     ->limit(1)
                                    //     ->exec();
                                    // $rs_htpxxth_out = $qs_htpxxth_out->fetchAll();

                                    if(count($rs_htlxxrh_out) > 0){
                                        // jika ada data izin
                                        // check apakah disetujui
                                        if($rs_htlxxrh_out[0]['is_approve'] == 1){
                                            $status_presensi_out = $rs_htlxxrh_out[0]['status_presensi'];
                                        }else{
                                            $status_presensi_out = 'Izin Belum Disetujui';
                                        }
                                        $keterangan =  $keterangan . " - " . $rs_htlxxrh_out[0]['htpxxth_kode'];
                                    }else{
                                        // tidak membuat izin
                                        $status_presensi_out = 'Belum ada Izin';
                                        $htpxxth_kode = '';
                                    }

                                }
                            }
                            // END tentukan status_presensi
                        }
                    }


                }else{
                    // jika jadwal belum dibuat
                    $shift_in  = null;
                    $shift_out = null;
                    $st_jadwal = 'NJ';

                    $htlxxrh_kode           = 'NJ';
                    $status_presensi_in     = 'NJ';
                    $status_presensi_out    = 'NJ';
                }

                $qi_htsprrd = $editor->db()
                    ->query('insert', 'htsprrd')
                    ->set('id_hemxxmh',$id_hemxxmh)
                    ->set('keterangan',$keterangan)
                    ->set('kode_finger', $kode_finger)
                    ->set('tanggal',$tanggal)
                    ->set('shift_in',$shift_in)
                    ->set('shift_out',$shift_out)
                    ->set('st_jadwal',$st_jadwal)
                    ->set('tanggaljam_awal_t1',$tanggaljam_awal_t1)
                    ->set('tanggaljam_awal_t2',$tanggaljam_awal_t2)
                    ->set('tanggaljam_akhir_t1',$tanggaljam_akhir_t1)
                    ->set('tanggaljam_akhir_t2',$tanggaljam_akhir_t2)
                    ->set('clock_in',$clock_in)
                    ->set('clock_out',$clock_out)
                    ->set('st_clock_in',$st_clock_in)
                    ->set('st_clock_out',$st_clock_out)
                    ->set('status_presensi_in',$status_presensi_in)
                    ->set('status_presensi_out',$status_presensi_out)
                    ->set('htlxxrh_kode',$htlxxrh_kode)
                    ->exec();

            }
        }
        
     
	}catch(PDOException $e){
		
	}

?>