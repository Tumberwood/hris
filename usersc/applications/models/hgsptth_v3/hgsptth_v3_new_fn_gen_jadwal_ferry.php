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
    $berhasil = 0;
    $awal = new Carbon();

    $tanggal_awal_select = new Carbon($_POST['tanggal_awal']); //gunakan carbon untuk ambil data tanggal
    $tanggal_awal = $tanggal_awal_select->format('Y-m-d'); //format jadi 2023-09-12

    $tanggal_akhir_select = new Carbon($_POST['tanggal_akhir']); //gunakan carbon untuk ambil data tanggal
    $tanggal_akhir = $tanggal_akhir_select->format('Y-m-d'); //format jadi 2023-09-12

    if (!empty($_POST['dari_tanggal'])) {
        $dari_tanggal_select = new Carbon($_POST['dari_tanggal']); //gunakan carbon untuk ambil data tanggal
        $dari_tanggal = $dari_tanggal_select->format('Y-m-d'); //format jadi 2023-09-12
        
    } else {
        $dari_tanggal = null;
    }
    $id_hgsptth_v3     = $_POST['id_hgsptth_v3'];
    $id_htsptth_v3     = $_POST['id_htsptth_v3'];
    $tipe     = $_POST['tipe'];

    $timestamp     = $_POST['timestamp']; //tambah timestamp untuk view

    try{
        $db->transaction();
        
        $qs_peg = $db
        ->raw()
        ->bind(':id_htsptth_v3', $id_htsptth_v3)
        ->exec('SELECT
                    a.id_hemxxmh
                FROM htsemtd_v3 AS a
                WHERE a.id_htsptth_v3 = :id_htsptth_v3
        ');
        $rs_peg = $qs_peg->fetchAll();

        foreach ($rs_peg as $row) {
            $id_hemxxmh_list = $row['id_hemxxmh'];
            
            $qd = $db
            ->raw()
            ->bind(':tanggal_awal', $tanggal_awal)
            ->bind(':tanggal_akhir', $tanggal_akhir)
            ->bind(':id_hemxxmh_list', $id_hemxxmh_list)
            ->exec('DELETE FROM htssctd 
                    WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir 
                        AND id_hemxxmh = :id_hemxxmh_list
            ');
        }

        $qd_detail = $db
        ->raw()
        ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
        ->exec('DELETE FROM hgsemtd_v3 WHERE id_hgsptth_v3 = :id_hgsptth_v3
        ');
        
        if ($tipe == "Copy") {
            if ($dari_tanggal == null) {
                // Begin Insert Jadwal
                $qr_jadwal = $db
                ->raw()
                ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
                ->exec('INSERT INTO htssctd
                    (
                        tanggal,
                        id_hemxxmh,
                        id_htsxxmh,
                        jam_awal,
                        jam_akhir,
                        jam_awal_istirahat,
                        jam_akhir_istirahat,
                        menit_toleransi_awal_in,
                        menit_toleransi_akhir_in,
                        menit_toleransi_awal_out,
                        menit_toleransi_akhir_out,

                        tanggaljam_awal_t1,
                        tanggaljam_awal,
                        tanggaljam_awal_t2,
                        tanggaljam_akhir_t1,
                        tanggaljam_akhir,
                        tanggaljam_akhir_t2,
                        tanggaljam_awal_istirahat,
                        tanggaljam_akhir_istirahat
                    )
                    SELECT
                        if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)) AS tanggal,
                        a.id_hemxxmh,
                        c.id_htsxxmh,
                        d.jam_awal,
                        d.jam_akhir,
                        d.jam_awal_istirahat,
                        d.jam_akhir_istirahat,
                        d.menit_toleransi_awal_in,
                        d.menit_toleransi_awal_out,
                        d.menit_toleransi_akhir_in,
                        d.menit_toleransi_akhir_out,
                        CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", TIME(DATE_SUB(d.jam_awal, INTERVAL d.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY)
                                ELSE if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY))
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
                        
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", d.jam_akhir)
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR d.kode like "malam%" AND d.jam_akhir <= "12:00:00" THEN DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY)
                                ELSE if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY))
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_awal_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", d.jam_awal_istirahat)
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_awal_istirahat)
                        END AS tanggaljam_awal_istirahat,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", d.jam_akhir_istirahat)
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_akhir_istirahat)
                        END AS tanggaljam_akhir_istirahat
                    FROM htsemtd_v3 AS a
                    LEFT JOIN pola_shift_v3 AS b ON b.id_htsptth_v3 = a.id_htsptth_v3 AND b.grup = a.grup
                    LEFT JOIN htststd_v3 AS c ON c.id_htsptth_v3 = a.id_htsptth_v3 AND c.shift = b.shift
                    LEFT JOIN htsxxmh AS d ON d.id = c.id_htsxxmh
                    LEFT JOIN (
                        SELECT
                            id,
                            tanggal_awal,
                            id_htsptth_v3
                        FROM hgsptth_v3
                    ) AS e ON e.id_htsptth_v3 = c.id_htsptth_v3
                    WHERE e.id = :id_hgsptth_v3;

                ');
                // END insert
                $berhasil = 1;
            } else {
                $qs_find_old_gen = $db
                    ->raw()
                    ->bind(':dari_tanggal', $dari_tanggal)
                    ->bind(':id_htsptth_v3', $id_htsptth_v3)
                    ->exec(' SELECT
                                    id
                            FROM hgsptth_v3 AS a
                            WHERE tanggal_awal = :dari_tanggal AND a.id_htsptth_v3 = :id_htsptth_v3
                            '
                            );
                $rs_find_old_gen = $qs_find_old_gen->fetch();
    
                if (!empty($rs_find_old_gen)) {
                    // Begin Insert Jadwal
                    $qr_jadwal = $db
                    ->raw()
                    ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
                    ->bind(':dari_tanggal', $dari_tanggal)
                    ->exec('INSERT INTO htssctd
                        (
                            tanggal,
                            id_hemxxmh,
                            id_htsxxmh,
                            jam_awal,
                            jam_akhir,
                            jam_awal_istirahat,
                            jam_akhir_istirahat,
                            menit_toleransi_awal_in,
                            menit_toleransi_akhir_in,
                            menit_toleransi_awal_out,
                            menit_toleransi_akhir_out,

                            tanggaljam_awal_t1,
                            tanggaljam_awal,
                            tanggaljam_awal_t2,
                            tanggaljam_akhir_t1,
                            tanggaljam_akhir,
                            tanggaljam_akhir_t2,
                            tanggaljam_awal_istirahat,
                            tanggaljam_akhir_istirahat
                        )
                        WITH CopyJadwal AS (
                            SELECT
                                a.id_hemxxmh,
                                a.jam_awal,
                                    a.jam_akhir,
                                    a.jam_awal_istirahat,
                                    a.jam_akhir_istirahat,
                                    a.menit_toleransi_awal_in,
                                    a.menit_toleransi_akhir_in,
                                    a.menit_toleransi_awal_out,
                                    a.menit_toleransi_akhir_out,
                                a.tanggaljam_awal,
                                e.tanggal_awal,
                                ROW_NUMBER() OVER (PARTITION BY a.id_hemxxmh ORDER BY a.tanggal) AS urutan,
                                a.id_htsxxmh,
                                d.kode
                            FROM htssctd AS a
                            LEFT JOIN hgsptth_v3 AS f ON f.tanggal_awal = :dari_tanggal
                            LEFT JOIN htsxxmh AS d ON d.id = a.id_htsxxmh
                            LEFT JOIN hgsptth_v3 AS e ON e.id = :id_hgsptth_v3
                            WHERE a.tanggal BETWEEN f.tanggal_awal AND f.tanggal_akhir
                        )
                        SELECT
                            IF(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)) AS tanggal,
                            id_hemxxmh,
                            id_htsxxmh,
                        jam_awal,
                        jam_akhir,
                        jam_awal_istirahat,
                        jam_akhir_istirahat,
                        menit_toleransi_awal_in,
                        menit_toleransi_awal_out,
                        menit_toleransi_akhir_in,
                        menit_toleransi_akhir_out,
                        CONCAT(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), " ", TIME(DATE_SUB(jam_awal, INTERVAL menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), " ", jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(jam_awal, INTERVAL menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), INTERVAL 1 DAY)
                                ELSE if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY))
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(jam_awal, INTERVAL menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(jam_awal, INTERVAL menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(jam_awal, INTERVAL menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
                        
                        CASE
                            WHEN kode like "malam%" AND jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), INTERVAL 1 DAY), " ", TIME(DATE_SUB(jam_akhir, INTERVAL menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), " ", TIME(DATE_SUB(jam_akhir, INTERVAL menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN kode like "malam%" AND jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), INTERVAL 1 DAY), " ", jam_akhir)
                            ELSE CONCAT(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), " ", jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(jam_akhir, INTERVAL menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR kode like "malam%" AND jam_akhir <= "12:00:00" THEN DATE_ADD(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), INTERVAL 1 DAY)
                                ELSE if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY))
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(jam_akhir, INTERVAL menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(jam_akhir, INTERVAL menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(jam_akhir, INTERVAL menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CASE
                            WHEN kode like "malam%" AND jam_awal_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), INTERVAL 1 DAY), " ", jam_awal_istirahat)
                            ELSE CONCAT(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), " ", jam_awal_istirahat)
                        END AS tanggaljam_awal_istirahat,
                        CASE
                            WHEN kode like "malam%" AND jam_akhir_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), INTERVAL 1 DAY), " ", jam_akhir_istirahat)
                            ELSE CONCAT(if(urutan = 1, tanggal_awal, DATE_ADD(tanggal_awal, INTERVAL (urutan - 1) DAY)), " ", jam_akhir_istirahat)
                        END AS tanggaljam_akhir_istirahat
                        FROM CopyJadwal
                        GROUP BY id_hemxxmh, urutan
                        ORDER BY id_hemxxmh, urutan;


                    ');
                    // END insert
                    $berhasil = 1;
                } else {
                    $berhasil = 2; // flag gagal karena belum diisi dari tanggalnya
                }
            }
        } else {
            $qs_find_old_gen = $db
                ->raw()
                ->bind(':dari_tanggal', $dari_tanggal)
                ->bind(':id_htsptth_v3', $id_htsptth_v3)
                ->exec(' SELECT
                                id
                        FROM hgsptth_v3 AS a
                        WHERE tanggal_awal = :dari_tanggal AND a.id_htsptth_v3 = :id_htsptth_v3
                        '
                        );
            $rs_find_old_gen = $qs_find_old_gen->fetch();

            if (!empty($rs_find_old_gen)) {
                // Begin Insert Jadwal
                $qr_jadwal = $db
                ->raw()
                ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
                ->exec('INSERT INTO htssctd
                    (
                        tanggal,
                        id_hemxxmh,
                        id_htsxxmh,
                        jam_awal,
                        jam_akhir,
                        jam_awal_istirahat,
                        jam_akhir_istirahat,
                        menit_toleransi_awal_in,
                        menit_toleransi_akhir_in,
                        menit_toleransi_awal_out,
                        menit_toleransi_akhir_out,

                        tanggaljam_awal_t1,
                        tanggaljam_awal,
                        tanggaljam_awal_t2,
                        tanggaljam_akhir_t1,
                        tanggaljam_akhir,
                        tanggaljam_akhir_t2,
                        tanggaljam_awal_istirahat,
                        tanggaljam_akhir_istirahat
                    )
                    SELECT
                        if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)) AS tanggal,
                        a.id_hemxxmh,
                        c.id_htsxxmh,
                        d.jam_awal,
                        d.jam_akhir,
                        d.jam_awal_istirahat,
                        d.jam_akhir_istirahat,
                        d.menit_toleransi_awal_in,
                        d.menit_toleransi_awal_out,
                        d.menit_toleransi_akhir_in,
                        d.menit_toleransi_akhir_out,
                        CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", TIME(DATE_SUB(d.jam_awal, INTERVAL d.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
                        CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_awal) AS tanggaljam_awal,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY)
                                ELSE if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY))
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE)
                                END
                            )
                        ) AS tanggaljam_awal_t2,
                        
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
                        END AS tanggaljam_akhir_t1,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", d.jam_akhir)
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_akhir)
                        END AS tanggaljam_akhir,
                        CONCAT(
                            CASE
                                WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
                                    OR d.kode like "malam%" AND d.jam_akhir <= "12:00:00" THEN DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY)
                                ELSE if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY))
                            END,
                            " ",
                            TIME(
                                CASE
                                    WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
                                        TIMEDIFF(DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE), "24:00:00")
                                    ELSE
                                        DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)
                                END
                            )
                        ) AS tanggaljam_akhir_t2,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_awal_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", d.jam_awal_istirahat)
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_awal_istirahat)
                        END AS tanggaljam_awal_istirahat,
                        CASE
                            WHEN d.kode like "malam%" AND d.jam_akhir_istirahat <= "12:00:00"
                            THEN CONCAT(DATE_ADD(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), INTERVAL 1 DAY), " ", d.jam_akhir_istirahat)
                            ELSE CONCAT(if(c.urutan = 1, e.tanggal_awal, DATE_ADD(e.tanggal_awal, INTERVAL (c.urutan - 1) DAY)), " ", d.jam_akhir_istirahat)
                        END AS tanggaljam_akhir_istirahat
                    FROM htsemtd_v3 AS a
                    LEFT JOIN (
                            SELECT
                                id_htsptth_v3,
                                grup,
                            CASE
                                WHEN shift - 1 = 0 THEN (SELECT MAX(shift) FROM pola_shift_v3)
                                ELSE shift - 1
                            END AS shift
                        FROM pola_shift_v3
                    ) AS b ON b.id_htsptth_v3 = a.id_htsptth_v3 AND b.grup = a.grup
                    LEFT JOIN htststd_v3 AS c ON c.id_htsptth_v3 = a.id_htsptth_v3 AND c.shift = b.shift
                    LEFT JOIN htsxxmh AS d ON d.id = c.id_htsxxmh
                    LEFT JOIN (
                        SELECT
                            id,
                            tanggal_awal,
                            id_htsptth_v3
                        FROM hgsptth_v3
                    ) AS e ON e.id_htsptth_v3 = c.id_htsptth_v3
                    WHERE e.id = :id_hgsptth_v3;

                ');
                // END insert
                $berhasil = 1;
            } else {
                $berhasil = 2; // flag gagal karena belum diisi dari tanggalnya
            }
            
        }
        
        foreach ($rs_peg as $row) {
            $id_hemxxmh_list = $row['id_hemxxmh'];
            // Begin Insert Jadwal Detail
            $qr_jadwal = $db
                ->raw()
                ->bind(':id_hgsptth_v3', $id_hgsptth_v3)
                ->bind(':tanggal_awal', $tanggal_awal)
                ->bind(':tanggal_akhir', $tanggal_akhir)
                ->bind(':id_hemxxmh_list', $id_hemxxmh_list)
                ->exec('INSERT INTO hgsemtd_v3
                    (
                        id_hgsptth_v3,
                        id_hemxxmh,
                        minggu,
                        senin,
                        selasa,
                        rabu,
                        kamis,
                        jumat,
                        sabtu
                    )
                    SELECT 
                        :id_hgsptth_v3,
                        a.id_hemxxmh,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Sunday" THEN b.kode END) AS minggu,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Monday" THEN b.kode END) AS senin,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Tuesday" THEN b.kode END) AS selasa,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Wednesday" THEN b.kode END) AS rabu,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Thursday" THEN b.kode END) AS kamis,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Friday" THEN b.kode END) AS jumat,
                        MAX(CASE WHEN DAYNAME(a.tanggal) = "Saturday" THEN b.kode END) AS sabtu
                    FROM htssctd AS a
                    LEFT JOIN htsxxmh AS b ON a.id_htsxxmh = b.id
                    LEFT JOIN hemxxmh AS c ON c.id = a.id_hemxxmh
                    WHERE a.tanggal BETWEEN :tanggal_awal AND :tanggal_akhir
                    AND a.id_hemxxmh = :id_hemxxmh_list
                    GROUP BY a.id_hemxxmh;
    
                    ;
    
                ');
            // END insert
        }
        
        $qu_hpyxxth = $db
            ->query('update', 'htsptth_v3')
            ->set('generated_on',$tanggal_awal)
            ->where('id',$id_htsptth_v3)
        ->exec();
        
        $qu_hgsptth_v3 = $db
            ->query('update', 'hgsptth_v3')
            ->set('generated_on',$timestamp)
            ->where('id',$id_hgsptth_v3)
        ->exec();

        $db->commit();

        $akhir = new Carbon();

        if ($berhasil == 1) {
            $data = array(
                'message' => 'Generate Jadwal Berhasil Dibuat dalam waktu ' . $awal->diffInSeconds($akhir) . ' detik', 
                'type_message' => 'success',
                'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
            ); 
        } else if ($berhasil == 2){
            $data = array(
                'message' => 'Gagal Generate Jadwal, Hasil Pencarian untuk Inputan Dari Tanggal Yang Dipilih, dan Pola Shift yang Sama Tidak Ditemukan' , 
                'type_message' => 'danger',
                'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
            ); 
        } else {
            $data = array(
                'message' => 'Gagal Generate Jadwal' , 
                'type_message' => 'danger',
                'waktu'=> $awal . ' - ' . $akhir . ' /  ' . $awal->diffInSeconds($akhir)
            ); 
        }
    }catch(PDOException $e){
        // rollback on error
        $db->rollback();
        
    }
    

    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>