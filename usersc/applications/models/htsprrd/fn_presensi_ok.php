<?php

include( "../../../../users/init.php" );
include( "../../../../usersc/lib/DataTables.php" );
use Carbon\Carbon;
use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
	
	$id_htsprrd   = $_POST['id_htsprrd'];
	$htlxxrh_kode   = $_POST['htlxxrh_kode'];
	$id_hemxxmh_select   = $_POST['id_hemxxmh_select'];

    $tanggal_select = new Carbon($_POST['tanggal']); //gunakan carbon untuk ambil data tanggal
    $tanggal = $tanggal_select->format('Y-m-d'); //format jadi 2023-09-12

    try {
        $db->transaction();

        $kode = array();
        $flag = 0;
        //SELECT REPLACEMENT pengaju
        $qs_htsrptd_pengaju = $db
            ->query('select', 'htsrptd' )
            ->get(['concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama'] )
            ->join('hemxxmh','hemxxmh.id = htsrptd.id_hemxxmh_pengganti','LEFT' ) //pengganti
            ->where('htsrptd.tanggal', $tanggal )
            ->where('htsrptd.is_approve', 1 )
            ->where('htsrptd.id_hemxxmh_pengaju', $id_hemxxmh_select ) //peg sekarang = pengaju
        ->exec();
        $rs_htsrptd_pengaju = $qs_htsrptd_pengaju->fetch();
        // print_r($rs_htsrptd['nama']);
        if (!empty($rs_htsrptd_pengaju)) {
            $kode[] = "Replacement [" . $rs_htsrptd_pengaju['nama'] . "]";
            $flag = 1;
        }

        //SELECT REPLACEMENT pengganti
        $qs_htsrptd_pengganti = $db
            ->query('select', 'htsrptd' )
            ->get(['concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama'] )
            ->join('hemxxmh','hemxxmh.id = htsrptd.id_hemxxmh_pengaju','LEFT' ) //pengganti
            ->where('htsrptd.tanggal', $tanggal )
            ->where('htsrptd.is_approve', 1 )
            ->where('htsrptd.id_hemxxmh_pengganti', $id_hemxxmh_select ) //peg sekarang = pengaju
        ->exec();
        $rs_htsrptd_pengganti = $qs_htsrptd_pengganti->fetch();
        // print_r($rs_htsrptd_pengganti['nama']);
        if (!empty($rs_htsrptd_pengganti)) {
            $kode[] = "Replacement [" . $rs_htsrptd_pengganti['nama'] . "]";
            $flag = 1;
        }

        //SELECT Tukar Jadwal pengaju
        $qs_htscctd_pengaju = $db
            ->query('select', 'htscctd' )
            ->get(['concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama'] )
            ->join('hemxxmh','hemxxmh.id = htscctd.id_hemxxmh_pengganti','LEFT' )//pengganti
            ->where('htscctd.tanggal', $tanggal )
            ->where('htscctd.is_approve', 1 )
            ->where('htscctd.id_hemxxmh_pengaju', $id_hemxxmh_select )//peg sekarang = pengaju
        ->exec();
        $rs_htscctd_pengaju = $qs_htscctd_pengaju->fetch();
        
        if (!empty($rs_htscctd_pengaju)) {
            $kode[] = "Replacement [" . $rs_htscctd_pengaju['nama'] . "]";
            $flag = 1;
        }

        //SELECT Tukar Jadwal pengganti
        $qs_htscctd_pengganti = $db
            ->query('select', 'htscctd' )
            ->get(['concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama'] )
            ->join('hemxxmh','hemxxmh.id = htscctd.id_hemxxmh_pengaju','LEFT' )//pengganti
            ->where('htscctd.tanggal', $tanggal )
            ->where('htscctd.is_approve', 1 )
            ->where('htscctd.id_hemxxmh_pengganti', $id_hemxxmh_select )//peg sekarang = pengaju
        ->exec();
        $rs_htscctd_pengganti = $qs_htscctd_pengganti->fetch();
        
        if (!empty($rs_htscctd_pengganti)) {
            $kode[] = "Replacement [" . $rs_htscctd_pengganti['nama'] . "]";
            $flag = 1;
        }

        $katerangan = $htlxxrh_kode . " " . implode(', ', $kode);

        print_r($htlxxrh_kode);
        // print_r($tanggal);
        // print_r($id_hemxxmh_select);
        if ($flag == 1) {
            $sql_update = $editor->db()
                ->query('update', 'htsprrd')
                ->set('htlxxrh_kode', $katerangan)
                ->set('cek', 0)
                ->where('id', $id_htsprrd)
            ->exec();
        }
            

        $db->commit();
        echo json_encode(array('message' => 'Transaksi Berhasil Diproses', 'type_message' => 'success'));
    } catch (PDOException $e) {
        // rollback on error
        $db->rollback();
        echo json_encode(array('message' => 'Transaksi Gagal Diproses! ' . $e->getMessage(), 'type_message' => 'danger'));
    }
    
	
?>