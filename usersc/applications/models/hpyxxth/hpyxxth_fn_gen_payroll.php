<?php 
    /**
     * Digunakan untuk melakukan perhitungan payroll karyawan
     * 
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

    /**
     * +---------------------------------------------------------------------+
     * | #id    | KOMPONEN                             | sumber table        |
     * +---------------------------------------------------------------------+
     * | 1      | Gaji Pokok                        OK | htpr_hemxxmh        |
     * | 31     | Tunjangan Masa Kerja                 | htpr_hevgrmh_mk     |
     * | 32     | Tunjangan Jabatan (Level)         OK | htpr_hevxxmh        |
     * | 33     | Premi Absen                          | htpr_hevxxmh        |
     * | 34     | Potongan Uang Makan                  |                     |
     * | 35     | Potongan Absen           (KBM)       |                     |
     * | 37     | Upah Lembur                       OK |                     |
     * | 101    | Var Cost                             |                     |
     * +---------------------------------------------------------------------+
     */
    /* tidak dipakai
    $qs_hpcxxmh = $db
        ->query('select', 'hpcxxmh' )
        ->get([
            'hpcxxmh.kode as kode',
            'hpcxxmh.nama as nama',
            'hpcxxmh.jenis as jenis'
        ] )
        ->where('hpcxxmh.is_active', 1 )
        ->exec();
    $rs_hpcxxmh = $qs_hpcxxmh->fetchAll();
    */

    try{
        $db->transaction();

        $qs_hpyxxth = $db
            ->query('select', 'hpyxxth' )
            ->get([
                'hpyxxth.id as id_hpyxxth',
                'hpyxxth.id_heyxxmh as id_heyxxmh',
                'hpyxxth.tanggal_awal as tanggal_awal',
                'hpyxxth.tanggal_akhir as tanggal_akhir'
            ] )
            ->where('hpyxxth.id', $_POST['id_transaksi_h'] )
            ->exec();
        $rs_hpyxxth = $qs_hpyxxth->fetch();

        $id_hpyxxth = $rs_hpyxxth['id_hpyxxth'];

        /*
        // #1 OK
        // BEGIN GAJI POKOK
        $qd_gp = $db
            ->query('delete', 'hpyemtd')
            ->where('id_hpyxxth',$id_hpyxxth)
            ->where('id_hpcxxmh',1)
            ->exec();
        $qr_gp = $db
            ->raw()
            ->bind(':is_active', 1)
            ->bind(':id_heyxxmh', $rs_hpyxxth['id_heyxxmh'])
            ->bind(':tanggal_awal', $rs_hpyxxth['tanggal_awal'])
            ->bind(':tanggal_akhir', $rs_hpyxxth['tanggal_akhir'])
            ->exec('
            INSERT INTO hpyemtd (
                id_hpyxxth, 
                id_hemxxmh, 
                id_hpcxxmh, 
                nominal
            )
            SELECT 
                ' . $id_hpyxxth . ',
                hemxxmh.id,
                1,
                IFNULL(tbl_htpr_hemxxmh.nominal,0) as nominal
            FROM hemxxmh
            LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = hemxxmh.id
            LEFT JOIN 
            (
                SELECT 
                    id_hemxxmh, 
                    tanggal_efektif, 
                    nominal
                FROM (
                    SELECT
                        id,
                        id_hemxxmh,
                        tanggal_efektif,
                        nominal,
                        ROW_NUMBER() OVER (PARTITION BY id_hemxxmh ORDER BY tanggal_efektif DESC) AS row_num
                    FROM htpr_hemxxmh
                    WHERE 
                        htpr_hemxxmh.id_hpcxxmh = 1 AND
                        tanggal_efektif < :tanggal_awal
                ) AS subquery
                WHERE row_num = 1
            ) tbl_htpr_hemxxmh ON tbl_htpr_hemxxmh.id_hemxxmh = hemxxmh.id
            WHERE 
                hemxxmh.is_active = :is_active AND
                (
                    hemjbmh.tanggal_keluar IS NULL OR 
                    hemjbmh.tanggal_keluar = "0000-00-00" OR
                    (hemjbmh.tanggal_keluar BETWEEN :tanggal_awal AND :tanggal_akhir ) 
                ) AND
                hemjbmh.id_heyxxmh = :id_heyxxmh
            ');
        // END GAJI POKOK
        */
        /*
        // #31 CEK
        // BEGIN Tunjangan Masa Kerja
        // ini looping per karyawan
        $qs_hemjbmh = $db
            ->query('select', 'hemjbmh' )
            ->get([
                'hemjbmh.id_hemxxmh as id_hemxxmh',
                'hevxxmh.hevgrmh as id_hevgrmh',
                'IF(
                    a.tanggal_keluar = NULL OR a.tanggal_keluar = "0000-00-00",
                    TIMESTAMPDIFF(YEAR,a.tanggal_masuk,CURDATE()),
                    TIMESTAMPDIFF(YEAR,a.tanggal_masuk,a.tanggal_keluar)
                ) AS masa_kerja_year'
            ] )
            ->join('hemxxmh','hemxxmh.id = hemjbmh.id_hemxxmh','LEFT' )
            ->join('hevxxmh','hevxxmh.id = hemjbmh.id_hevxxmh','LEFT' )
            ->where('hemjbmh.is_active', 1 )
            ->where( function ( $r ) {
                $r
                    ->where( 'hemjbmh.tanggal_keluar', NULL )
                    ->or_where( 'hemjbmh.tanggal_keluar', '0000-00-00', operator)
                    ->or_where( 'hemjbmh.tanggal_keluar', $rs_hpyxxth['tanggal_awal'], '>=')
                    ->or_where( 'hemjbmh.tanggal_keluar', $rs_hpyxxth['tanggal_akhir'], '<=');
            } )
            ->exec();
        $rs_hemjbmh = $qs_hemjbmh->fetchAll();

        foreach ($rs_hemjbmh as $row_hemjbmh) {
            $qs_htpr_hevgrmh_mk = $db
                ->query('select', 'htpr_hevgrmh_mk' )
                ->get([
                    'htpr_hevgrmh_mk.nominal as nominal'
                ] )
                ->where('htpr_hevgrmh_mk.id_hevgrmh', $row_hemjbmh['id_hevgrmh'] )
                ->where($row_hemjbmh['masa_kerja_year'] , 'htpr_hevgrmh_mk.tahun_min', '>=' )
                ->where( function ( $r ) {
                    $r
                        ->where($row_hemjbmh['masa_kerja_year'] , 'htpr_hevgrmh_mk.tahun_max', '<' )
                        ->or_where('htpr_hevgrmh_mk.tahun_min', 0 );
                } )
                ->exec();
            $rs_htpr_hevgrmh_mk = $qs_htpr_hevgrmh_mk->fetch();

            if( !$rs_htpr_hevgrmh_mk ){
                // delete data lama per karyawan jika ada
                    $qd_hpyemtd_mk = $db
                    ->query('delete', 'hpyemtd')
                    ->where('id_hpyxxth', $id_hpyxxth )
                    ->where('id_hpcxxmh', 31 )
                    ->where('id_hemxxmh', $row_hemjbmh['id_hemxxmh'] )
                    ->exec();
                
                // insert data baru per karyawan
                $qi_hpyemtd = $db
                    ->query('insert', 'hpyemtd')
                    ->set('id_hpyxxth',$id_hpyxxth)
                    ->set('id_hemxxmh',$row_hemjbmh['id_hemxxmh']) 
                    ->set('id_hpcxxmh',31) 
                    ->set('nominal', $rs_htpr_hevgrmh_mk['nominal'] ) 
                    ->exec();
            }
            
        }
        // END Tunjangan Masa Kerja
        */
        /*
        // #32 OK
        // BEGIN Tunjangan Jabatan (Level)
        $qd_hpyemtd_tjab = $db
            ->query('delete', 'hpyemtd')
            ->where('id_hpyxxth',$id_hpyxxth)
            ->where('id_hpcxxmh',32)
            ->exec();

        $qr_hpyemtd_tjab = $db
            ->raw()
            ->bind(':is_active', 1)
            ->bind(':id_heyxxmh', $rs_hpyxxth['id_heyxxmh'])
            ->bind(':tanggal_awal', $rs_hpyxxth['tanggal_awal'])
            ->bind(':tanggal_akhir', $rs_hpyxxth['tanggal_akhir'])
            ->exec('
                INSERT INTO hpyemtd (
                    id_hpyxxth, 
                    id_hemxxmh, 
                    id_hpcxxmh, 
                    nominal
                )
                SELECT 
                    ' . $id_hpyxxth . ',
                    hemxxmh.id,
                    32,
                    IFNULL(tbl_htpr_hevxxmh.nominal,0) as nominal
                FROM hemxxmh
                LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = hemxxmh.id
                LEFT JOIN 
                (
                    SELECT 
                        id_hevxxmh,
                        tanggal_efektif,
                        nominal
                    FROM (
                        SELECT
                            id,
                            id_hevxxmh,
                            tanggal_efektif,
                            nominal,
                            ROW_NUMBER() OVER (PARTITION BY id_hevxxmh ORDER BY tanggal_efektif DESC) AS row_num
                        FROM htpr_hevxxmh
                        WHERE 
                            htpr_hevxxmh.id_hpcxxmh = 33 AND
                            tanggal_efektif < :tanggal_awal
                    ) AS subquery
                ) tbl_htpr_hevxxmh ON 
                    tbl_htpr_hevxxmh.id_hevxxmh = hemjbmh.id_hevxxmh
                WHERE 
                    hemxxmh.is_active = :is_active AND
                    (
                        hemjbmh.tanggal_keluar IS NULL OR 
                        hemjbmh.tanggal_keluar = "0000-00-00" OR
                        (hemjbmh.tanggal_keluar BETWEEN :tanggal_awal AND :tanggal_akhir )
                    ) AND
                    hemjbmh.id_heyxxmh = :id_heyxxmh
                ');
        // END Tunjangan Jabatan (Level)
        */

        // #37
        // BEGIN Upah Lembur
        // sudah dihitung di generate presensi, tinggal insert saja dari sana
        $qd_hpyemtd_overtime = $db
            ->query('delete', 'hpyemtd')
            ->where('id_hpyxxth',$id_hpyxxth)
            ->where('id_hpcxxmh',37)
            ->exec();

        $qr_hpyemtd_overtime = $db
            ->raw()
            ->bind(':is_active', 1)
            ->bind(':id_heyxxmh', $rs_hpyxxth['id_heyxxmh'])
            ->bind(':tanggal_awal', $rs_hpyxxth['tanggal_awal'])
            ->bind(':tanggal_akhir', $rs_hpyxxth['tanggal_akhir'])
            ->exec('
                INSERT INTO hpyemtd (
                    id_hpyxxth, 
                    id_hemxxmh, 
                    id_hpcxxmh, 
                    nominal
                )
                SELECT
                    ' . $id_hpyxxth . ',
                    htsprrd.id_hemxxmh,
                    37,
                    SUM(htsprrd.nominal_lembur_final) as nominal
                FROM htsprrd
                LEFT JOIN hemjbmh ON hemjbmh.id_hemxxmh = htsprrd.id_hemxxmh
                WHERE
                    htsprrd.is_active = :is_active AND
                    htsprrd.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir AND 
                    hemjbmh.id_heyxxmh = :id_heyxxmh
                GROUP BY htsprrd.id_hemxxmh
                HAVING nominal > 0
            ');
        // END Upah Lembur
        

        $db->commit();

        $data = array(
            'message' => 'Generate Payroll Berhasil',
            'type_message' => 'success'
        );  
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        
    }
    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>