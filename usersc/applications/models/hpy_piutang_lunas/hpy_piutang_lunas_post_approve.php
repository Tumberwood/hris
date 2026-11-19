
<?php
    include( "../../../../users/init.php" );
    include( "../../../../usersc/lib/DataTables.php" );
    include( "../../../../usersc/helpers/datatables_fn_debug.php" );
    require '../../../../usersc/vendor/autoload.php';

    use Carbon\Carbon;

    use
        DataTables\Editor,
        DataTables\Editor\Query,
        DataTables\Editor\Result;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

    $id_hpy_piutang_lunas = $_POST['id_transaksi_h'];
    $state = $_POST['state'];

    // ==========================================
    // AMBIL DATA PELUNASAN
    // ==========================================
    $qs_piutang = $db
        ->query('select', 'hpy_piutang_lunas')
        ->get([
            'kode',
            'id_hpy_piutang_h',
            'id_hemxxmh',
            'id_hpcxxmh',
            'keterangan',
            'nominal',
            'tanggal_mulai'
        ])
        ->where('id', $id_hpy_piutang_lunas)
        ->exec();

    $rs_piutang = $qs_piutang->fetch();

    $id_hpy_piutang_h = $rs_piutang['id_hpy_piutang_h'];
    $tanggal = $rs_piutang['tanggal_mulai'];

    if($state == 1){

        // ==========================================
        // INSERT DETAIL PELUNASAN
        // ==========================================
        $qi_hpy_piutang_d = $db
            ->query('insert', 'hpy_piutang_d')
            ->set('kode', $rs_piutang['kode'])
            ->set('id_hemxxmh', $rs_piutang['id_hemxxmh'])
            ->set('id_hpcxxmh', $rs_piutang['id_hpcxxmh'])
            ->set('id_hpy_piutang_lunas', $id_hpy_piutang_lunas)
            ->set('nama', 'Pelunasan')
            ->set('keterangan', $rs_piutang['keterangan'])
            ->set('nominal', $rs_piutang['nominal'])
            ->set('is_approve', 1)
            ->set('tanggal', $tanggal)
            ->exec();

        // ==========================================
        // NONAKTIFKAN DETAIL PIUTANG
        // ==========================================
        $qu_ = $db
            ->raw()
            ->bind(':id_hpy_piutang_h', $id_hpy_piutang_h)
            ->bind(':tanggal', $tanggal)
            ->exec("
                UPDATE hpy_piutang_d
                SET is_active = 0
                WHERE id_hpy_piutang_h = :id_hpy_piutang_h
                    AND tanggal >= :tanggal
                    AND is_active = 1
                    AND id_hpy_piutang_lunas = 0
            ");

    } else {

        // ==========================================
        // HAPUS DETAIL PELUNASAN
        // ==========================================
        $qd_piutang = $db
            ->query('delete', 'hpy_piutang_d')
            ->where('id_hpy_piutang_lunas', $id_hpy_piutang_lunas)
            ->exec();

        // ==========================================
        // AKTIFKAN KEMBALI DETAIL PIUTANG
        // ==========================================
        $qu_ = $db
            ->raw()
            ->bind(':id_hpy_piutang_h', $id_hpy_piutang_h)
            ->bind(':tanggal', $tanggal)
            ->exec("
                UPDATE hpy_piutang_d
                SET is_active = 1
                WHERE id_hpy_piutang_h = :id_hpy_piutang_h
                    AND tanggal >= :tanggal
                    AND is_active = 0
                    AND id_hpy_piutang_lunas = 0
            ");

    }

?>
