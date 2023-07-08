<?php
    /**
     * ambil jumlah grup
     * ambil pegawai & grup ke
     * ambil detail pola
     * 
     */
	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
    
    $id_htsptth         = $values['hgsptth']['id_htsptth'];
    $tanggal_awal       = new Carbon ($values['hgsptth']['tanggal_awal']);
    $tanggal_awal_ymd   = $tanggal_awal->format('Y-m-d');
    $jumlah_siklus      = $values['hgsptth']['jumlah_siklus'];


    // echo 'tanggal dari form: ' . $values['hgsptth']['tanggal_awal'] . '<br>';
    // echo 'tanggal new carbon dari form: ' . new Carbon ($values['hgsptth']['tanggal_awal']). '<br>';
    // echo 'tanggal carbon variable: ' . $tanggal_awal. '<br>';
    // echo 'tanggal carbon variable di format: ' . $tanggal_awal_ymd. '<br>';
    // echo 'tanggal new carbon dari form addDays: ' . (new Carbon ($values['hgsptth']['tanggal_awal']))->addDays(2). '<br>';
    // $tanggal_akhir      = $tanggal_awal->addDays(30);
    // $tanggal_akhir_ymd  = $tanggal_akhir->format('Y-m-d');
    // echo 'tanggal akhir: ' , $tanggal_akhir_ymd;
    

    // BEGIN ambil jumlah_grup dari pola shift htsptth
    $qs_htsptth = $editor->db()
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
    $qs_htsemtd = $editor->db()
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
    $qs_htststd = $editor->db()
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

    $tanggal_akhir      = (new Carbon ($values['hgsptth']['tanggal_awal']) )->addDays($jumlah_hari) ;
    $tanggal_akhir_ymd  = $tanggal_akhir->format('Y-m-d');
    
    // update tanggal_akhir
    $qu = $editor->db()
        ->query('update', 'hgsptth')
        ->set('tanggal_akhir', $tanggal_akhir_ymd)
        ->where('id', $id )
        ->exec();
    // END hitung dan update tanggal akhir

    // BETA
    // BEGIN tentukan iterasi untuk looping insert
    // $max_rows_insert, silakan disesuaikan
    // $jumlah_eksekusi = jumlah karyawan
    // $max_rows_insert = 1000;

    // $totalbaris = intval($c_rs_htsemtd) * intval($c_htststd) * $jumlah_siklus;
    // if($totalbaris > $max_rows_insert){
    //     // jika lebih dari $max_rows_insert, maka akan dibagi berdasarkan jumlah karyawan
    //     $jumlah_eksekusi = $c_rs_htsemtd;
    // }else{
    //     $jumlah_eksekusi = 1;
    // }
    

    // BEGIN looping karyawan
    foreach ($rs_htsemtd as $row_htsemtd) {
        $tanggal        = new Carbon ($values['hgsptth']['tanggal_awal']);
        $tanggal_ymd    = $tanggal->format('Y-m-d');
        
        $id_hemxxmh     = $row_htsemtd['id_hemxxmh'];
        $grup_ke 	    = $row_htsemtd['grup_ke'];
        // $id_hemxxmh     = 122;
        // $grup_ke 	    = 3;

        // ambil urutan mulai
        $qs_htststd_urutan = $editor->db()
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


        for ($siklus_ke = 0; $siklus_ke < $jumlah_siklus; $siklus_ke++ ){
            $urutan             = $urutan_awal;

            for ($tanggal_ke = 0; $tanggal_ke < $c_htststd; $tanggal_ke++ ){
                // echo $tanggal_ymd . '<br>';

                try{
                    
                    
                

                // BEGIN delete data lama
                // cek apakah ada data lama, jika ada dihapus
                $qs_htssctd = $editor->db()
                    ->query('select', 'htssctd' )
                        ->get(['htssctd.id as id_htssctd'] )
                        ->where('htssctd.id_hemxxmh', $id_hemxxmh )
                        ->where('htssctd.tanggal', $tanggal_ymd )
                        ->exec(); 
                $rs_htssctd = $qs_htssctd->fetchAll();
                $c_htssctd = count($rs_htssctd);

                if($c_htssctd > 0){
                    // jika ditemukan data lama, delete
                    $qd_htssctd = $editor->db()
                        ->query('delete', 'htssctd')
                        ->where('id_hemxxmh', $id_hemxxmh)
                        ->where('tanggal', $tanggal_ymd)
                        ->exec();
                }
                // END delete data lama

                $qs_htsxxmh = $editor->db()
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
                    
                // BEGIN insert jadwal htssctd
                $qi_htssctd = $editor->db()
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
                    ->exec();
                // END insert jadwal htssctd

            }catch(PDOException $e){
                // rollback on error
                $editor->db()->rollback();
            }

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

    }
    // END looping karyawan
    
?>