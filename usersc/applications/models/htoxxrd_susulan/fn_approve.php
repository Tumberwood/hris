<?php

include( "../../../../users/init.php" );
include( "../../../../usersc/lib/DataTables.php" );

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

	$editor = Editor::inst( $db, '' );
	
	$id_htoxxrd_susulan   = $_POST['id_htoxxrd_susulan'];
	$is_approve   = $_POST['is_approve'];

    try {
        $db->transaction();

        if ($is_approve == 1) {
            $qs_lembur = $db
                ->raw()
                ->bind(':id_htoxxrd_susulan', $id_htoxxrd_susulan)
                ->exec(' SELECT
                            a.durasi_lembur_jam,
	                        a.tanggal,
	                        a.id_hemxxmh,
                            if(a.id_htotpmh = 4, a.durasi_lembur_jam, 0) AS durasi_lembur_libur_jam,
                            b.id_hesxxmh
                        FROM htoxxrd_susulan AS a
                        LEFT JOIN hemjbmh AS b ON b.id_hemxxmh = a.id_hemxxmh
                        WHERE a.id = :id_htoxxrd_susulan
                        '
                        );
            $rs_lembur = $qs_lembur->fetch();
            $durasi_lembur_final = $rs_lembur['durasi_lembur_jam'];
            $durasi_lembur_libur_jam = $rs_lembur['durasi_lembur_libur_jam'];
            $id_hemxxmh = $rs_lembur['id_hemxxmh'];
            $tanggal = $rs_lembur['tanggal'];
            $id_hesxxmh = $rs_lembur['id_hesxxmh'];

            //lembur 1.5
            $lembur15 = 0;
            $lembur15_final = 0;
            $rp_lembur15 = 0;

            //lembur 2
            $lembur2 = 0;
            $lembur2_final = 0;
            $rp_lembur2 = 0;

            //lembur 3
            $lembur3 = 0;
            $lembur3_final = 0;
            $rp_lembur3 = 0;

            //lembur 4
            $lembur4 = 0;
            $lembur4_final = 0;
            $rp_lembur4 = 0;

            //flag libur
            $is_tgl_merah = 0;

            if ($durasi_lembur_final > 0) {
                //Cari Lembur 1.5 
                if ($durasi_lembur_libur_jam > 0) { //jika ada lembur libur

                    //flag libur
                    $is_tgl_merah = 0;

                    // cari apakah today is hari libur atau tanggal merah
                    $qs_holiday = $db
                        ->query('select', 'hthhdth' )
                        ->get(['id'] )
                        ->where('tanggal', $tanggal )
                        ->exec();
                    $rs_holiday = $qs_holiday->fetch();

                    // cari minggu
                    $qs_minggu = $db
                        ->raw()
                        ->bind(':tanggal', $tanggal)
                        ->exec('SELECT
                                if(DAYNAME(:tanggal) = "Sunday", 1, 0) AS is_minggu;
                                '
                                );
                    $rs_minggu = $qs_minggu->fetch();

                    if (!empty($rs_holiday)) {
                        $is_tgl_merah = 1;
                    }
                    
                    if ($rs_minggu['is_minggu'] == 1) {
                        $is_tgl_merah = 1;
                    }
                    
                    // if ($id_shift == 1) {
                    if ($is_tgl_merah == 1) {
                        $lembur15 = 0;
                        $lembur15_final = 0;

                        // update 12 oktober

                        // lembur 2 libur
                        if ($durasi_lembur_final > 1 && $durasi_lembur_final <= 7 || $durasi_lembur_final >= 7) {
                            if ($durasi_lembur_final > 7) {
                                $lembur2 = 7; 
                            } else if ($durasi_lembur_final < 8) {
                                $lembur2 = 7.5; 
                            } else {
                                $lembur2 = $durasi_lembur_final;
                            }
                        } else {
                            $lembur2 = 0;
                        }

                        // lembur3
                        if ($durasi_lembur_final >= 8) { // sebelumnya 7
                            $lembur3 = $durasi_lembur_final - 7; //sesuai dengan excel Bu Eva sebelumnya 7
                        } else {
                            $lembur3 = 0;
                        }
                        $lembur4 = 0;

                        $lembur2_final = $lembur2 * 2;
                        $lembur3_final = $lembur3 * 3;
                        $lembur4_final = 0;
                    } else {
                        if ($durasi_lembur_final > 1) {
                            $lembur15 = 1;
                        } else {
                            $lembur15 = $durasi_lembur_final;
                        }

                        if ($durasi_lembur_final > 1 && $durasi_lembur_final <= 7 || $durasi_lembur_final >= 7) {
                            if ($durasi_lembur_final > 7) {
                                $lembur2 = 7;
                            } else {
                                $lembur2 = $durasi_lembur_final - 1;
                            }
                        } else {
                            $lembur2 = 0;
                        }
                        
                        // lembur3
                        if ($durasi_lembur_final >= 8) {
                            $lembur3 = $durasi_lembur_final - 8;
                        } else {
                            $lembur3 = 0;
                        }
                        
                        $lembur15_final = $lembur15 * 1.5;
                        $lembur2_final = $lembur2 * 2;
                        $lembur3_final = $lembur3 * 3;
                    }
                } else {
                    // lembur 1.5 bukan libur
                    if ($id_hemxxmh == 67) { //jika Tri Wandono
                        if ($durasi_lembur_final > 2) {
                            $lembur15 = 2;
                        } else {
                            $lembur15 = $durasi_lembur_final;
                        }
                        
                        // lembur 2 bukan libur untuk Tri Wandono
                        if ($durasi_lembur_final > 2) {
                            $lembur2 = $durasi_lembur_final - 2;
                        } else {
                            $lembur2 = 0;
                        }

                    } else { 
                        //jika bukan Tri Wandono
                        if ($durasi_lembur_final > 1) {
                            $lembur15 = 1;
                        } else {
                            $lembur15 = $durasi_lembur_final;
                        }
                        
                        // lembur 2 bukan libur
                        if ($durasi_lembur_final > 1 && $durasi_lembur_final <= 8) {
                            $lembur2 = $durasi_lembur_final - 1;
                        } else {
                            $lembur2 = 0;
                        }
                        
                        // lembur3
                        if ($durasi_lembur_final > 8) {
                            $lembur3 = $durasi_lembur_final - 8;
                        } else {
                            $lembur3 = 0;
                        }
                    }


                    $lembur15_final = $lembur15 * 1.5;
                    
                    // tri wandono cuma sampai lembur2
                    $lembur2_final = $lembur2 * 2;
                    
                    $lembur3_final = $lembur3 * 3;
                    
                }
                
                //Hitung RP Lembur

                //Jika Pelatihan
                if($id_hesxxmh == 3){
                    // jam mati
                    $lembur15 = $durasi_lembur_final;
                    $lembur15_final = $durasi_lembur_final;

                    //lembur 2
                    $lembur2 = 0;
                    $lembur2_final = 0;
                    $rp_lembur2 = 0;

                    //lembur 3
                    $lembur3 = 0;
                    $lembur3_final = 0;
                    $rp_lembur3 = 0;

                    //lembur 4
                    $lembur4 = 0;
                    $lembur4_final = 0;
                    $rp_lembur4 = 0;
                    
                    $nominal_lembur_jam = 0;

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
                    // $rp_lembur15 = round($nominal_lembur_jam);

                    //floor
                    $rp_lembur15 = floor($nominal_lembur_jam);
                }else{
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
                                    htpr_hevxxmh.id_hpcxxmh = 32 AND
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
                    // $nominal_lembur_jam     = round($tot_komp_lembur / 173);

                    // Jika Floor
                    $nominal_lembur_jam     = floor($tot_komp_lembur / 173);

                    $rp_lembur15 = $lembur15_final * $nominal_lembur_jam;
                    $rp_lembur2 = $lembur2_final * $nominal_lembur_jam;
                    $rp_lembur3 = $lembur3_final * $nominal_lembur_jam;
                    $rp_lembur4 = $lembur4_final * $nominal_lembur_jam;

                    $rp_lembur_final = $rp_lembur15 + $rp_lembur2 + $rp_lembur3 + $rp_lembur4;

                }
            }

            // Update Lembur 1.5 s/d 3 dan Approve
            $sql_update = $editor->db()
                ->query('update', 'htoxxrd_susulan')
                ->set('is_approve', 1)
                ->set('rp_lembur_final', $rp_lembur_final)
                //lembur
                ->set('lembur15', $lembur15)
                ->set('rp_lembur15', $rp_lembur15)
                ->set('lembur15_final', $lembur15_final)

                ->set('lembur2', $lembur2)
                ->set('rp_lembur2', $rp_lembur2)
                ->set('lembur2_final', $lembur2_final)

                ->set('lembur3', $lembur3)
                ->set('rp_lembur3', $rp_lembur3)
                ->set('lembur3_final', $lembur3_final)
                
                ->set('approved_by', $_SESSION['user'])
                ->set('approved_on', date("Y-m-d H:i:s"))
                ->where('id', $id_htoxxrd_susulan)
            ->exec();

            $editor->db()
                ->query('insert', 'activity_log_ml')
                ->set('id_transaksi', $id_htoxxrd_susulan)
                ->set('kode', 'APPROVE')
                ->set('nama', 'htoxxrd_susulan')
                ->set('keterangan', 'Approve RAB')
                ->set('created_by', $_SESSION['user'])
                ->set('username', $_SESSION['username'])
                ->set('start_on', date("Y-m-d H:i:s"))
                ->set('finish_on', date("Y-m-d H:i:s"))
                ->set('durasi_detik', 0)
                ->exec();
        } else {
            $sql_update = $editor->db()
                ->query('update', 'htoxxrd_susulan')
                ->set('is_approve', 0)
                ->set('approved_by', $_SESSION['user'])
                ->set('approved_on', date("Y-m-d H:i:s"))
                ->where('id', $id_htoxxrd_susulan)
                ->exec();

            $editor->db()
                ->query('insert', 'activity_log_ml')
                ->set('id_transaksi', $id_htoxxrd_susulan)
                ->set('kode', 'APPROVE')
                ->set('nama', 'htoxxrd_susulan')
                ->set('keterangan', 'Cancel Approve RAB')
                ->set('created_by', $_SESSION['user'])
                ->set('username', $_SESSION['username'])
                ->set('start_on', date("Y-m-d H:i:s"))
                ->set('finish_on', date("Y-m-d H:i:s"))
                ->set('durasi_detik', 0)
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