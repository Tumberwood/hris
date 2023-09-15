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
        //SELECT REPLACEMENT
        $qs_htsrptd = $db
            ->query('select', 'htsrptd' )
            ->get(['concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama'] )
            ->join('hemxxmh','hemxxmh.id = htsrptd.id_hemxxmh_pengganti','LEFT' ) //pengganti
            ->where('htsrptd.tanggal', $tanggal )
            ->where('htsrptd.is_approve', 1 )
            ->where('htsrptd.id_hemxxmh_pengaju', $id_hemxxmh_select ) //peg sekarang = pengaju
        ->exec();
        $rs_htsrptd = $qs_htsrptd->fetch();
        // print_r($rs_htsrptd['nama']);
        if (!empty($rs_htsrptd)) {
            $kode[] = "Replacement - " . $rs_htsrptd['nama'];
            $flag = 1;
        }

        //SELECT Tukar Jadwal
        $qs_htscctd = $db
            ->query('select', 'htscctd' )
            ->get(['concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama'] )
            ->join('hemxxmh','hemxxmh.id = htscctd.id_hemxxmh_pengganti','LEFT' )//pengganti
            ->where('htscctd.tanggal', $tanggal )
            ->where('htscctd.is_approve', 1 )
            ->where('htscctd.id_hemxxmh_pengaju', $id_hemxxmh_select )//peg sekarang = pengaju
        ->exec();
        $rs_htscctd = $qs_htscctd->fetch();
        
        if (!empty($rs_htscctd)) {
            $kode[] = "Replacement - " . $rs_htscctd['nama'];
            $flag = 1;
        }

        $katerangan = $htlxxrh_kode . " " . implode(', ', $kode);

        // print_r($flag);
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