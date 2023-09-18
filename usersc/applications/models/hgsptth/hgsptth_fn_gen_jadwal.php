<?php 
    /**
     * Digunakan untuk INSERT data hari libur nasional hthhdth ke table htlxxrh
     * Notes:
     *  Masih kurang where karyawan yang aktif berdasarkan tanggal resign
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

    $qs_hgsptth = $db
        ->query('select', 'hgsptth' )
        ->get([
            'hgsptth.id_htsptth as id_htsptth',
            'hgsptth.tanggal_awal as tanggal_awal',
            'hgsptth.jumlah_siklus as jumlah_siklus'
        ] )
        ->where('hgsptth.id', $_POST['id_transaksi_h'] )
        ->exec();
    
    $rs_hgsptth = $qs_hgsptth->fetch();

    $id_htsptth         = $rs_hgsptth['id_htsptth'];
    $jumlah_siklus      = $rs_hgsptth['jumlah_siklus'];
    $tanggal_awal       = new Carbon ($rs_hgsptth['tanggal_awal']);
    $tanggal_awal_ymd   = $tanggal_awal->format('Y-m-d');

    // BEGIN ambil jumlah_grup dari pola shift htsptth
    $qs_htsptth = $db
        ->query('select', 'htsptth' )
        ->get([
            'htsptth.jumlah_grup as jumlah_grup'
        ] )
        ->where('htsptth.id', $id_htsptth )
        ->exec();
    $rs_htsptth = $qs_htsptth->fetch();
    $jumlah_grup = $rs_htsptth['jumlah_grup'];
    // END ambil jumlah_grup dari pola shift htsptth

    // BEGIN ambil detail karyawan sesuai pola shift terpilih
    $qs_htsemtd = $db
        ->query('select', 'htsemtd' )
        ->get([
            'htsemtd.id_hemxxmh as id_hemxxmh',
            'htsemtd.grup_ke as grup_ke'
        ] )
        ->where('htsemtd.is_active', 1)
        ->where('htsemtd.id_htsptth', $id_htsptth )
        ->exec();
    $rs_htsemtd = $qs_htsemtd->fetchAll();
    // END ambil detail karyawan sesuai pola shift terpilih
    $c_rs_htsemtd = count($rs_htsemtd);

    // BEGIN ambil detail shift sesuai pola shift terpilih
    // ini cukup get id saja kayanya
    $qs_htststd = $db
        ->query('select', 'htststd' )
        ->get([
            'htststd.id_htsxxmh as id_htsxxmh',
            'htststd.id_htsxxmh as id_htsxxmh',
            'htststd.urutan as urutan',
            'htststd.mulai_grup as mulai_grup'
        ] )
        ->where('htststd.is_active', 1 )
        ->where('htststd.id_htsptth', $id_htsptth )
        ->exec();
    $rs_htststd = $qs_htststd->fetchAll();
    $c_htststd = count($rs_htststd);
    // END  ambil detail shift sesuai pola shift terpilih

    // BEGIN hitung dan update tanggal akhir
    $jumlah_hari        = (intval($c_htststd) * intval($jumlah_siklus)) - 1;

    $tanggal_akhir      = ( new Carbon ($rs_hgsptth['tanggal_awal']) ) ->addDays($jumlah_hari) ;
    $tanggal_akhir_ymd  = $tanggal_akhir->format('Y-m-d');

    // update tanggal_akhir
    $qu = $db
        ->query('update', 'hgsptth')
        ->set('tanggal_akhir', $tanggal_akhir_ymd)
        ->where('id', $_POST['id_transaksi_h'] )
        ->exec();
    // END hitung dan update tanggal akhir

    $hasil = 0;

    // BEGIN looping karyawan
    foreach ($rs_htsemtd as $row_htsemtd) {    

        $tanggal        = new Carbon ($rs_hgsptth['tanggal_awal']);
        $tanggal_ymd    = $tanggal->format('Y-m-d');
            
        $id_hemxxmh     = $row_htsemtd['id_hemxxmh'];
        $grup_ke 	    = $row_htsemtd['grup_ke'];
        // $id_hemxxmh     = 122;
        // $grup_ke 	    = 3;

        // ambil urutan mulai
        $qs_htststd_urutan = $db
            ->query('select', 'htststd' )
            ->get([
                'htststd.id_htsxxmh as id_htsxxmh',
                'htststd.id_htsxxmh as id_htsxxmh',
                'htststd.urutan as urutan',
                'htststd.mulai_grup as mulai_grup'
            ] )
            ->where('htststd.is_active', 1 )
            ->where('htststd.id_htsptth', $id_htsptth )
            ->where('htststd.mulai_grup', $grup_ke )
            ->exec();
        $rs_htststd_urutan  = $qs_htststd_urutan->fetch();
        $urutan_awal        = $rs_htststd_urutan['urutan'];

        // echo 'idhem: ' . $id_hemxxmh . ' - grupke: ' . $grup_ke . ' - urutan awal: '  . $urutan_awal . '- tanggal aw: '. $tanggal. '<br>';

        try{
            $db->transaction();

            for ($siklus_ke = 0; $siklus_ke < $jumlah_siklus; $siklus_ke++ ){
                $urutan             = $urutan_awal;

                for ($tanggal_ke = 0; $tanggal_ke < $c_htststd; $tanggal_ke++ ){
                    // echo $tanggal_ymd . '<br>';

                        // BEGIN delete data lama
                        // cek apakah ada data lama, jika ada dihapus
                        $qs_htssctd = $db
                            ->query('select', 'htssctd' )
                                ->get(['htssctd.id as id_htssctd'] )
                                ->where('htssctd.id_hemxxmh', $id_hemxxmh )
                                ->where('htssctd.tanggal', $tanggal_ymd )
                                ->exec(); 
                        $rs_htssctd = $qs_htssctd->fetchAll();
                        $c_htssctd = count($rs_htssctd);

                        if($c_htssctd > 0){
                            // jika ditemukan data lama, delete
                            $qd_htssctd = $db
                                ->query('delete', 'htssctd')
                                ->where('id_hemxxmh', $id_hemxxmh)
                                ->where('tanggal', $tanggal_ymd)
                                ->exec();
                        }
                        // END delete data lama

                        $qs_htsxxmh = $db
                            ->query('select', 'htststd' )
                            ->get([
                                'htststd.id_htsxxmh as id_htsxxmh',
                                'htsxxmh.jam_awal as jam_awal',
                                'htsxxmh.jam_akhir as jam_akhir',
                                'htsxxmh.jam_awal_istirahat as jam_awal_istirahat',
                                'htsxxmh.jam_akhir_istirahat as jam_akhir_istirahat',
                                'htsxxmh.menit_toleransi_awal_in as menit_toleransi_awal_in',
                                'htsxxmh.menit_toleransi_akhir_in as menit_toleransi_akhir_in',
                                'htsxxmh.menit_toleransi_awal_out as menit_toleransi_awal_out',
                                'htsxxmh.menit_toleransi_akhir_out as menit_toleransi_akhir_out'
                            ] )
                            ->join('htsxxmh','htsxxmh.id = htststd.id_htsxxmh','LEFT' )
                            ->where('htststd.is_active', 1 )
                            ->where('htststd.id_htsptth', $id_htsptth )
                            ->where('htststd.urutan', $urutan )
                            ->exec();

                        $rs_htststd = $qs_htsxxmh->fetch();

$temp_tanggaljam_awal = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_awal'] );
$tanggaljam_awal = $temp_tanggaljam_awal->format('Y-m-d H:i:s');

$temp_tanggaljam_awal_t1 = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_awal'] );
$tanggaljam_awal_t1 = $temp_tanggaljam_awal_t1->subMinutes( $rs_htststd['menit_toleransi_awal_in'] )->format('Y-m-d H:i:s');

$temp_tanggaljam_awal_t2 = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_awal'] );
$tanggaljam_awal_t2 = $temp_tanggaljam_awal_t2->addMinutes($rs_htststd['menit_toleransi_akhir_in'])->format('Y-m-d H:i:s');

    //DITAMBAHKAN SHIFT OFF TIDAK PERLU ADD 1 DAYS
if( $rs_htststd['jam_awal'] < $rs_htststd['jam_akhir'] || $rs_htststd['id_htsxxmh'] == 1){
    $temp_tanggaljam_akhir    = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_akhir'] );
}else{
    $temp_tanggaljam_akhir    = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_akhir'] );
    $temp_tanggaljam_akhir->addDays(1);
}
$tanggaljam_akhir = $temp_tanggaljam_akhir->format('Y-m-d H:i:s');

$temp_tanggaljam_akhir_t1 = $temp_tanggaljam_akhir;
$tanggaljam_akhir_t1 = $temp_tanggaljam_akhir_t1->subMinutes( $rs_htststd['menit_toleransi_awal_out'] )->format('Y-m-d H:i:s');

// resetting $temp_tanggaljam_akhir
// agak aneh, harusnya tidak perlu sih
if( $rs_htststd['jam_awal'] < $rs_htststd['jam_akhir'] || $rs_htststd['id_htsxxmh'] == 1){
    $temp_tanggaljam_akhir    = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_akhir'] );
}else{
    $temp_tanggaljam_akhir    = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_akhir'] );
    $temp_tanggaljam_akhir->addDays(1);
}
$temp_tanggaljam_akhir_t2 = $temp_tanggaljam_akhir;
$tanggaljam_akhir_t2 = $temp_tanggaljam_akhir_t2->addMinutes($rs_htststd['menit_toleransi_akhir_out'])->format('Y-m-d H:i:s');

// BEGIN concat tanggal jam istirahat (tanpa toleransi)
$tanggaljam_awal_istirahat = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_awal_istirahat'] );

if( $rs_htststd['jam_awal_istirahat'] < $rs_htststd['jam_akhir_istirahat']){
    $temp_tanggaljam_akhir_istirahat    = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_akhir_istirahat'] );
}else{
    $temp_tanggaljam_akhir_istirahat    = new Carbon( $tanggal_ymd . ' ' . $rs_htststd['jam_akhir_istirahat'] );
    $temp_tanggaljam_akhir_istirahat->addDays(1);
}
$tanggaljam_akhir_istirahat = $temp_tanggaljam_akhir_istirahat->format('Y-m-d H:i:s');
// END concat tanggal jam istirahat (tanpa toleransi)



                        // BEGIN insert jadwal htssctd
                        $qi_htssctd = $db
                            ->query('insert', 'htssctd')
                            ->set('id_hemxxmh',$id_hemxxmh)
                            ->set('id_htsxxmh',$rs_htststd['id_htsxxmh'])
                            ->set('tanggal',$tanggal_ymd)
                            ->set('jam_awal',$rs_htststd['jam_awal'])
                            ->set('jam_akhir',$rs_htststd['jam_akhir'])
                            ->set('jam_awal_istirahat',$rs_htststd['jam_awal_istirahat'])
                            ->set('jam_akhir_istirahat',$rs_htststd['jam_akhir_istirahat'])
                            ->set('menit_toleransi_awal_in',$rs_htststd['menit_toleransi_awal_in'])
                            ->set('menit_toleransi_akhir_in',$rs_htststd['menit_toleransi_akhir_in'])
                            ->set('menit_toleransi_awal_out',$rs_htststd['menit_toleransi_awal_out'])
                            ->set('menit_toleransi_akhir_out',$rs_htststd['menit_toleransi_akhir_out'])

                            ->set('tanggaljam_awal_t1',$tanggaljam_awal_t1)
                            ->set('tanggaljam_awal',$tanggaljam_awal)
                            ->set('tanggaljam_awal_t2',$tanggaljam_awal_t2)
                            ->set('tanggaljam_akhir_t1',$tanggaljam_akhir_t1)
                            ->set('tanggaljam_akhir',$tanggaljam_akhir)
                            ->set('tanggaljam_akhir_t2',$tanggaljam_akhir_t2)
                            ->set('tanggaljam_awal_istirahat',$tanggaljam_awal_istirahat)
                            ->set('tanggaljam_akhir_istirahat',$tanggaljam_akhir_istirahat)

                            ->exec();
                        // END insert jadwal htssctd

                        // increment tanggal
                        $tanggal->addDays(1);
                        $tanggal_ymd = $tanggal->format('Y-m-d');

                        if ( $urutan < $c_htststd){
                            // counter $urutan
                            $urutan = $urutan + 1;
                        }else{
                            // balik 1
                            $urutan = 1;
                        }

                }
                $tanggal_ke = 0;
            }
            $siklus_ke = 0;

            // di commit per karyawan
            $db->commit(); 
            $hasil = $hasil + 1;
            
        }catch(PDOException $e){
            $hasil = 0;
            // rollback on error
            $db->rollback();
            $data = array(
                'message'=>'Data Gagal Dibuat', 
                'type_message'=>'danger',
                'error' => $e
            );
        }

    }
    // END looping karyawan
  
    $akhir = new Carbon();

    if($hasil > 0){
        $data = array(
            'message'=> $hasil . ' Jadwal Karyawan Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik',
            'type_message'=>'success',
            'tanggal awal'=> $tanggal_awal,
            'tanggal away ymd'=> $tanggal_awal_ymd,
            'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
        );
    }
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );

?>