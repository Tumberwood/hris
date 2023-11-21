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

    //pengaju
    $id_hemxxmh_pengaju = 0;
    $tanggaljam_akhir_toleransi_pengaju = null;
    $tanggaljam_awal_t1_pengaju = null;
    $tanggaljam_awal_pengaju = null;
    $tanggaljam_awal_t2_pengaju = null;
    $tanggaljam_akhir_t1_pengaju = null;
    $tanggaljam_akhir_pengaju = null;
    $tanggaljam_akhir_t2_pengaju = null;
    $ceklok_out_pengaju = null;
    $st_ceklok_out_pengaju = '';
    $pot_pengaju = 0;
    $pot_jam_early_pengaju = 0;

    //pengganti
    $id_hemxxmh_pengganti = 0;
    $tanggaljam_akhir_toleransi_pengganti = null;
    $tanggaljam_awal_t1_pengganti = null;
    $tanggaljam_awal_pengganti = null;
    $tanggaljam_awal_t2_pengganti = null;
    $tanggaljam_akhir_t1_pengganti = null;
    $tanggaljam_akhir_pengganti = null;
    $tanggaljam_akhir_t2_pengganti = null;
    $ceklok_out_pengganti = null;
    $st_ceklok_out_pengganti = '';
    $pot_pengganti = 0;
    $pot_jam_early_pengganti = 0;
    
    $pot_jam = 0;

    try {
        $db->transaction();

        $kode = array();
        $flag = 0;
            
        //SELECT REPLACEMENT pengaju
        $qs_htsrptd_pengaju = $db
            ->query('select', 'htsrptd' )
            ->get([
                    'concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama',
                    'id_hemxxmh_pengganti'
            ] )
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

            //ambil id pengganti
            $id_hemxxmh_pengganti = $rs_htsrptd_pengaju['id_hemxxmh_pengganti'];
        }

        //SELECT REPLACEMENT pengganti
        $qs_htsrptd_pengganti = $db
            ->query('select', 'htsrptd' )
            ->get([
                    'concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama',
                    'id_hemxxmh_pengaju'
            ] )
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

            //ambil id pengaju
            $id_hemxxmh_pengaju = $rs_htsrptd_pengganti['id_hemxxmh_pengaju'];
        }

        //SELECT Tukar Jadwal pengaju
        $qs_htscctd_pengaju = $db
            ->query('select', 'htscctd' )
            ->get([
                    'concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama',
                    'id_hemxxmh_pengganti'
            ] )
            ->join('hemxxmh','hemxxmh.id = htscctd.id_hemxxmh_pengganti','LEFT' )//pengganti
            ->where('htscctd.tanggal', $tanggal )
            ->where('htscctd.is_approve', 1 )
            ->where('htscctd.id_hemxxmh_pengaju', $id_hemxxmh_select )//peg sekarang = pengaju
        ->exec();
        $rs_htscctd_pengaju = $qs_htscctd_pengaju->fetch();
        
        if (!empty($rs_htscctd_pengaju)) {
            $kode[] = "Tukar Jadwal [" . $rs_htscctd_pengaju['nama'] . "]";
            $flag = 1;

            //ambil id pengganti
            $id_hemxxmh_pengganti = $rs_htscctd_pengaju['id_hemxxmh_pengganti'];
        }

        //SELECT Tukar Jadwal pengganti
        $qs_htscctd_pengganti = $db
            ->query('select', 'htscctd' )
            ->get([
                    'concat(hemxxmh.kode, " - ", hemxxmh.nama) as nama',
                    'id_hemxxmh_pengaju'
            ] )
            ->join('hemxxmh','hemxxmh.id = htscctd.id_hemxxmh_pengaju','LEFT' )//pengganti
            ->where('htscctd.tanggal', $tanggal )
            ->where('htscctd.is_approve', 1 )
            ->where('htscctd.id_hemxxmh_pengganti', $id_hemxxmh_select )//peg sekarang = pengaju
        ->exec();
        $rs_htscctd_pengganti = $qs_htscctd_pengganti->fetch();
        
        if (!empty($rs_htscctd_pengganti)) {
            $kode[] = "Tukar Jadwal [" . $rs_htscctd_pengganti['nama'] . "]";
            $flag = 1;
            
            //ambil id pengaju
            $id_hemxxmh_pengaju = $rs_htscctd_pengganti['id_hemxxmh_pengaju'];
        }

/////////////////////// HITUNG POTONGAN MAKAN ///////////////////////


        if ($id_hemxxmh_pengaju == 0) {
            $id_hemxxmh_pengaju = $id_hemxxmh_select;
        }

        if ($id_hemxxmh_pengganti == 0) {
            $id_hemxxmh_pengganti = $id_hemxxmh_select;
        }

        // print_r($id_hemxxmh_pengaju);
        // print_r($id_hemxxmh_pengganti);

        //AMBIL JADWAL PENGAJU
        $qs_htssctd_pengaju = $db
        ->raw()
        ->bind(':id_hemxxmh_pengaju', $id_hemxxmh_pengaju)
        ->bind(':tanggal', $tanggal)
        ->exec(' SELECT 
                    c.nama,
                    c.kode_finger,
                    shift.kode AS shift,
                    jadwal.tanggaljam_awal_t1,
                    jadwal.tanggaljam_awal,
                    DATE_ADD(jadwal.tanggaljam_awal, INTERVAL 5 MINUTE) AS tanggaljam_akhir_toleransi,
                    jadwal.tanggaljam_awal_t2,
                    jadwal.tanggaljam_akhir_t1,
                    jadwal.tanggaljam_akhir,
                    jadwal.tanggaljam_akhir_t2

                FROM  htssctd AS jadwal
                LEFT JOIN hemxxmh AS c ON c.id = jadwal.id_hemxxmh
                LEFT JOIN htsxxmh AS shift ON shift.id = jadwal.id_htsxxmh
                WHERE jadwal.id_hemxxmh = :id_hemxxmh_pengaju AND jadwal.tanggal = :tanggal
                LIMIT 1
                '
                );
        $rs_htssctd_pengaju = $qs_htssctd_pengaju->fetch();

        $tanggaljam_akhir_toleransi_pengaju = $rs_htssctd_pengaju['tanggaljam_akhir_toleransi'];
        $tanggaljam_awal_t1_pengaju = $rs_htssctd_pengaju['tanggaljam_awal_t1'];
        $tanggaljam_awal_pengaju = $rs_htssctd_pengaju['tanggaljam_awal'];
        $tanggaljam_akhir_t1_pengaju = $rs_htssctd_pengaju['tanggaljam_akhir_t1'];
        $tanggaljam_akhir_pengaju = $rs_htssctd_pengaju['tanggaljam_akhir'];
        

        //AMBIL JADWAL PENGGANTI
        $qs_htssctd_pengganti = $db
        ->raw()
        ->bind(':id_hemxxmh_pengganti', $id_hemxxmh_pengganti)
        ->bind(':tanggal', $tanggal)
        ->exec(' SELECT 
                    c.nama,
                    c.kode_finger,
                    shift.kode AS shift,
                    jadwal.tanggaljam_awal_t1,
                    jadwal.tanggaljam_awal,
                    DATE_ADD(jadwal.tanggaljam_awal, INTERVAL 5 MINUTE) AS tanggaljam_akhir_toleransi,
                    jadwal.tanggaljam_awal_t2,
                    jadwal.tanggaljam_akhir_t1,
                    jadwal.tanggaljam_akhir,
                    jadwal.tanggaljam_akhir_t2

                FROM  htssctd AS jadwal
                LEFT JOIN hemxxmh AS c ON c.id = jadwal.id_hemxxmh
                LEFT JOIN htsxxmh AS shift ON shift.id = jadwal.id_htsxxmh
                WHERE jadwal.id_hemxxmh = :id_hemxxmh_pengganti AND jadwal.tanggal = :tanggal
                LIMIT 1
                '
                );
        $rs_htssctd_pengganti = $qs_htssctd_pengganti->fetch();

        $tanggaljam_akhir_toleransi_pengganti = $rs_htssctd_pengganti['tanggaljam_akhir_toleransi'];
        $tanggaljam_awal_t1_pengganti = $rs_htssctd_pengganti['tanggaljam_awal_t1'];
        $tanggaljam_awal_pengganti = $rs_htssctd_pengganti['tanggaljam_awal'];
        $tanggaljam_akhir_t1_pengganti = $rs_htssctd_pengganti['tanggaljam_akhir_t1'];
        $tanggaljam_akhir_pengganti = $rs_htssctd_pengganti['tanggaljam_akhir'];

        //AMBIL CEKLOK PENGAJU (CAR BATAS EARLY)
        $qs_htsprtd_clock_out_pengaju = $db
            ->raw()
            ->bind(':kode_finger', $rs_htssctd_pengaju['kode_finger'])
            ->bind(':tanggal', $tanggal)
            ->bind(':tanggaljam_akhir_t1_pengaju', $tanggaljam_akhir_t1_pengaju)
            ->bind(':tanggaljam_akhir_pengaju', $tanggaljam_akhir_pengaju)
            ->exec(' SELECT 
                        concat(ceklok.tanggal," ",ceklok.jam) as dt_checkclock,
                        CASE
                            WHEN CONCAT(ceklok.tanggal, " ", ceklok.jam) BETWEEN :tanggaljam_akhir_t1_pengaju AND :tanggaljam_akhir_pengaju THEN "Valid"
                            ELSE "Invalid"
                        END AS hasil_ceklok

                    FROM  htsprtd AS ceklok
                    WHERE ceklok.kode = :kode_finger AND ceklok.tanggal = :tanggal AND ceklok.nama IN  ("os", "out", "staff", "pmi")
                    GROUP BY ceklok.id
                    ORDER BY ceklok.id DESC
                    '
                );
        $rs_htsprtd_clock_out_pengaju = $qs_htsprtd_clock_out_pengaju->fetch();
        
        //AMBIL CEKLOK PENGGANTI (CAR BATAS EARLY)
        $qs_htsprtd_clock_out_pengganti = $db
            ->raw()
            ->bind(':kode_finger', $rs_htssctd_pengganti['kode_finger'])
            ->bind(':tanggal', $tanggal)
            ->bind(':tanggaljam_akhir_t1_pengganti', $tanggaljam_akhir_t1_pengganti)
            ->bind(':tanggaljam_akhir_pengganti', $tanggaljam_akhir_pengganti)
            ->exec(' SELECT 
                        concat(ceklok.tanggal," ",ceklok.jam) as dt_checkclock,
                        CASE
                            WHEN CONCAT(ceklok.tanggal, " ", ceklok.jam) BETWEEN :tanggaljam_akhir_t1_pengganti AND :tanggaljam_akhir_pengganti THEN "Valid"
                            ELSE "Invalid"
                        END AS hasil_ceklok

                    FROM  htsprtd AS ceklok
                    WHERE ceklok.kode = :kode_finger AND ceklok.tanggal = :tanggal AND ceklok.nama IN  ("os", "out", "staff", "pmi")
                    GROUP BY ceklok.id
                    ORDER BY ceklok.id DESC
                    '
                );
        $rs_htsprtd_clock_out_pengganti = $qs_htsprtd_clock_out_pengganti->fetch();

        if (!empty($rs_htsprtd_clock_out_pengaju)) {
            $ceklok_out_pengaju = $rs_htsprtd_clock_out_pengaju['dt_checkclock'];
            $st_ceklok_out_pengaju = $rs_htsprtd_clock_out_pengaju['hasil_ceklok'];

            $karbon_out_pengaju = new Carbon($ceklok_out_pengaju);

            if ($st_ceklok_out_pengaju == "Valid") {
                $pot_jam_early_cek_pengaju     = $karbon_out_pengaju->diffInMinutes($tanggaljam_akhir_pengaju);
                $pot_jam_early_pengaju   = ceil($pot_jam_early_cek_pengaju/60);
            } else {
                if ($karbon_out_pengaju < $tanggaljam_akhir_pengganti ) {
                    $pot_jam_early_cek_pengaju     = $karbon_out_pengaju->diffInMinutes($tanggaljam_akhir_pengganti);
                    $pot_jam_early_pengganti   = ceil($pot_jam_early_cek_pengaju/60);
                }
            }
        } 

        if (!empty($rs_htsprtd_clock_out_pengganti)) {
            $ceklok_out_pengganti = $rs_htsprtd_clock_out_pengganti['dt_checkclock'];
            $st_ceklok_out_pengganti = $rs_htsprtd_clock_out_pengganti['hasil_ceklok'];
            
            $karbon_out_pengganti = new Carbon($ceklok_out_pengganti);

            if ($st_ceklok_out_pengganti == "Valid") {
                $pot_jam_early_cek_pengganti     = $karbon_out_pengganti->diffInMinutes($tanggaljam_akhir_pengganti);
                $pot_jam_early_pengganti   = ceil($pot_jam_early_cek_pengganti/60);
            } else {
                if ($karbon_out_pengganti < $tanggaljam_akhir_pengaju) {
                    $pot_jam_early_cek_pengganti     = $karbon_out_pengganti->diffInMinutes($tanggaljam_akhir_pengaju);
                    $pot_jam_early_pengaju   = ceil($pot_jam_early_cek_pengganti/60);
                }
            }
        } 

        //HITUNG POTONGAN
        $pot_pengganti = $pot_jam_early_pengganti;
        $pot_pengaju = $pot_jam_early_pengaju;
        
        // print_r($rs_htssctd_pengaju['nama'] . " Pot pengaju = " . $pot_pengaju . '<br>');
        // print_r($rs_htssctd_pengganti['nama'] . " Pot Pengganti = " . $pot_pengganti . '<br>');
        // $pot_pengaju

        //CEK HEMXXMH YANG DISELECT INI PENGAJU ATAU PENGGANTI
        if ($id_hemxxmh_select == $id_hemxxmh_pengaju) {
            $pot_jam = $pot_pengaju;
            $shift = $rs_htssctd_pengganti['shift'];
            if ($ceklok_out_pengaju == null) {
                $ceklok = "Tidak Checkclock";
            } else {
                $ceklok = date("d M Y H:i:s", strtotime($ceklok_out_pengaju));
            }
            $flag = 1;
        } else {
            $pot_jam = $pot_pengganti;
            $shift = $rs_htssctd_pengaju['shift'];
            if ($ceklok_out_pengganti == null) {
                $ceklok = "";
            } else {
                $ceklok = date("d M Y H:i:s", strtotime($ceklok_out_pengganti));

            }
            $flag = 1;
            
        }
        
        if ($pot_jam > 0) {
            // $info_shift = ' - Dipotong ' . $pot_jam . ' Jam';
            $info_shift = " [" . $shift . "] " . $ceklok;

        } else {
            $info_shift = " [" . $shift . "] " . $ceklok;
        }
        
        // print_r($htlxxrh_kode);
        // print_r($tanggal);
        // print_r($id_hemxxmh_select);

/////////////////////// END OF HITUNG POTONGAN MAKAN ///////////////////////
        $katerangan = $htlxxrh_kode . " " . implode(', ', $kode) . $info_shift;
        
        // print_r($katerangan);

        if ($flag == 1) {
            $sql_update = $editor->db()
                ->query('update', 'htsprrd')
                ->set('htlxxrh_kode', $katerangan)
                ->set('cek', 0)
                // ->set('pot_jam', $pot_jam)
                ->set('status_presensi_in', "HK")
                ->set('status_presensi_out', "HK")
                ->where('id', $id_htsprrd)
            ->exec();
        }

        if ($id_hemxxmh_select = 130) {
            $sql_update = $editor->db()
                ->query('update', 'htsprrd')
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