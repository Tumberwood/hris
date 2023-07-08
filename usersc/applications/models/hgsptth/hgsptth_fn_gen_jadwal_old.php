<?php
	require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;
    
    $id_htsptth = $values['hgsptth']['id_htsptth'];

    // ambil detail shift sesuai pola shift terpilih
    $qs_htststd = $editor->db()
        ->query('select', 'htststd' )
        ->get([
            'htststd.id as id_htststd'
        ] )
        ->where('htststd.id_htsptth', $id_htsptth )
        ->exec();
    $rs_htststd = $qs_htststd->fetchAll();
    $c_htststd = count($rs_htststd);

    // ambil detail karyawan sesuai pola shift terpilih
    $qs_htsemtd = $editor->db()
        ->query('select', 'htsemtd' )
        ->get([
            'htsemtd.id_hemxxmh as id_hemxxmh',
            'htsemtd.grup_ke as grup_ke'
        ] )
        ->where('htsemtd.id_htsptth', $id_htsptth )
        ->exec();
    $rs_htsemtd = $qs_htsemtd->fetchAll();

    $tanggal_awal  = new Carbon($values['hgsptth']['tanggal_awal']);
    $tanggal_akhir = new Carbon($values['hgsptth']['tanggal_awal']);
    $jumlah_siklus = $values['hgsptth']['jumlah_siklus'];
    $jumlah_hari   = intval($c_htststd) * intval($jumlah_siklus);

    // update tanggal_akhir
    $qu = $editor->db()
        ->query('update', 'hgsptth')
        ->set('tanggal_akhir', $tanggal_akhir->addDays($jumlah_hari -1) )
        ->where('id', $id )
        ->exec();

    // begin looping karyawan
    foreach ($rs_htsemtd as $row_htsemtd) {
        $tanggal = $tanggal_awal;
        $tanggal->format('Y-m-d');

        $id_hemxxmh = $row_htsemtd['id_hemxxmh'];
        $grup_ke = $row_htsemtd['grup_ke'];

        // ambil urutan mulai id_hemxxmh terkait
        $qs_htststd_d = $editor->db()
            ->query('select', 'htststd' )
            ->get([
                'htststd.id_htsxxmh as id_htsxxmh',
                'htststd.urutan as urutan_awal',
                'htststd.mulai_grup as mulai_grup'
            ] )
            ->where('htststd.id_htsptth', $id_htsptth )
            ->where('htststd.mulai_grup', $grup_ke )
            ->where('htststd.is_active', 1 )
            ->exec();
        $rs_htststd_d = $qs_htststd_d->fetch();
        $urutan_awal = $rs_htststd_d['urutan_awal'];

        // looping tanggal penjadwalan
        for ($w = 0; $w < $jumlah_siklus; $w++ ){
            $urutan = $urutan_awal;
            for ($x = 0; $x < $c_htststd; $x++ ){
                // cek apakah ada data lama, jika ada dihapus
                $qs_htssctd = $editor->db()
                    ->query('select', 'htssctd' )
                    ->get(['htssctd.id as id_htssctd'] )
                    ->where('htssctd.id_hemxxmh', $id_hemxxmh )
                    ->where('htssctd.tanggal', $tanggal )
                    ->exec(); 
                $rs_htssctd = $qs_htssctd->fetchAll();
                $c_htssctd = count($rs_htssctd);

                if($c_htssctd > 0){
                    // jika ditemukan data lama, delete
                    $qd_htssctd = $editor->db()
                        ->query('delete', 'htssctd')
                        ->where('id_hemxxmh', $id_hemxxmh)
                        ->where('tanggal', $tanggal)
                        ->exec();
                }

                // select shift
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
                    ->where('htststd.id_htsptth', $id_htsptth )
                    ->where('htststd.urutan', $urutan )
                    ->where('htststd.is_active', 1 )
                    ->exec();

                $rs_htststd = $qs_htsxxmh->fetch();
                
                $qi_htssctd = $editor->db()
                    ->query('insert', 'htssctd')
                    ->set('id_hemxxmh',$id_hemxxmh)
                    ->set('id_htsxxmh',$rs_htststd['id_htsxxmh'])
                    ->set('tanggal',$tanggal)
                    ->set('jam_awal',$rs_htststd['jam_awal'])
                    ->set('jam_akhir',$rs_htststd['jam_akhir'])
                    ->set('jam_awal_istirahat',$rs_htststd['jam_awal_istirahat'])
                    ->set('jam_akhir_istirahat',$rs_htststd['jam_akhir_istirahat'])
                    ->set('menit_toleransi_awal_in',$rs_htststd['menit_toleransi_awal_in'])
                    ->set('menit_toleransi_akhir_in',$rs_htststd['menit_toleransi_akhir_in'])
                    ->set('menit_toleransi_awal_out',$rs_htststd['menit_toleransi_awal_out'])
                    ->set('menit_toleransi_akhir_out',$rs_htststd['menit_toleransi_akhir_out'])
                    ->exec();

                $tanggal->addDays(1);

                if ( $urutan < $c_htststd){
                    // counter $urutan
                    $urutan = $urutan + 1;
                }else{
                    // balik 1
                    $urutan = 1;
                }
            } // end $x loop

            $x = 0;

        } // end $w loop

        // reset tanggal
        $tanggal->subDays($jumlah_hari);

    }	// END looping karyawan
?>