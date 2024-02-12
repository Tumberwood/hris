<?php

	// Untuk Function upload 12 feb 2024

    /**
     *                      : nama
     * 0: no akun           : kode
     * 1: Nama              
     * 2: Waktu             : slip jadi tanggal dan jam
     * 3: Kondisi
     * 4: Kondisi Baru
     * 5: Status
     * 6: Operasi
     */
    
    // Paramter dengan tanda (&) itu digunakan untuk mengambil variable yang telah dikalkulasi di dalam function
    // lalu digunakan kembali di parameter
    //jadi habis diolah di dalam function, parameter itu akan diambil lagi di parameter function
    
    /**
     * CARA PENGGUNAAN:
     * Tambahkan required once
     * require_once( "../../../../usersc/helpers/fn_upload_checkclock.php" );
     * 
     * Pada db Transaction tambahkan:
     * processSheetData($sheetData, $db, $dataupload, $datakembar, "PMI");
     * 
     * Penjelasan Parameter:
     * 
     * index        = 1;
     * $sheetData   = data dari excel ($sheetData = $spreadsheet->getActiveSheet()->toArray();)
     * $db          = datatables editor supaya bisa insert dsb, kalau gaada ini maka gabisa insert data dan select
     * &$dataupload = data upload yang berhasil, tanda & ini berfungsi untuk mengambil value yang udah di kelola di dalam function terus di kembalikan jadi parameter lagi
     * &$datakembar = sama seperti dataupload
     * $nama        = nama Mesin pakai string = "PMI"
     */

    function processSheetData($sheetData, $db, &$dataupload, &$datakembar, $nama)
    {
        $tanggal = '';
        $arr_kode = "";
        for($i = 1;$i < count($sheetData);$i++){
            // Str pad 4 digit dengan 0 sebagai default,
            //jadi jika 0 tidak terbaca di excel, maka bisa di akali dengan str pad
            $kode = str_pad($sheetData[$i]['0'], 4, '0', STR_PAD_LEFT);

            $dt = explode(" ", $sheetData[$i]['2']);
            $str_tanggal = $dt[0];
            $tahun = substr($str_tanggal, 6, 4);
            $bulan = substr($str_tanggal, 3, 2);
            $tgl = substr($str_tanggal, 0, 2);
            $tanggal = $tahun . '-' . $bulan . '-' . $tgl;
            $jam = $dt[1];

            if ($i == 1) {
                $arr_kode = 'SELECT "' . $kode . ' ' . $nama . ' ' . $tanggal . ' ' . $jam . '" AS excel_value ';
            } else {
                $arr_kode .= 'UNION ALL SELECT "' . $kode . ' ' . $nama . ' ' . $tanggal . ' ' . $jam . '" ';
            }

            // $qs_htsprtd = $db
            //     ->query('select', 'htsprtd')
            //     ->get(['count(id) as c_id'])
            //     ->where('htsprtd.kode', $kode)
            //     ->where('htsprtd.nama', $nama)
            //     ->where('htsprtd.tanggal', $tanggal)
            //     ->where('htsprtd.jam', $jam)
            //     ->exec();
            // $rs_htsprtd = $qs_htsprtd->fetch();
            // $c_rs_htsprtd = $rs_htsprtd['c_id'];

            // if ($c_rs_htsprtd == 0) {

            //     $dataupload = $dataupload + 1;
            // } else {
            //     $datakembar = $datakembar + 1;
            // }
        }
        // $arr_kode = rtrim($arr_kode, ',');
        // print_r($arr_kode);
        
        if ($arr_kode != "") {
            $qi_ceklok = $db
            ->raw()
            ->bind(':tanggal', $tanggal)
            ->bind(':nama', $nama)
            ->exec('    INSERT INTO htsprtd (kode, nama, tipe, tanggal, jam)
                        WITH ada AS (
                            SELECT
                                CONCAT(kode," ", nama, " ", tanggal, " " ,jam) AS conc_ada
                            FROM htsprtd
                            WHERE tanggal = :tanggal AND nama = :nama AND tipe = "upload"
                        ),
                        excel_data AS (
                            '.$arr_kode.'
                        )
                        SELECT
                            SUBSTRING(excel_value, 1, 4) AS kode,
                            SUBSTRING_INDEX(SUBSTRING_INDEX(excel_value, " ", 2), " ", -1) AS nama,
                            "upload",
                            SUBSTRING_INDEX(SUBSTRING_INDEX(excel_value, " ", 3), " ", -1) AS tanggal,
                            SUBSTRING_INDEX(SUBSTRING_INDEX(excel_value, " ", 4), " ", -1) AS jam
                        FROM excel_data
                        WHERE excel_value NOT IN (SELECT conc_ada FROM ada);

                    '
                    );

            // $qs_tidak_kembar = $db
            // ->raw()
            // ->bind(':tanggal', $tanggal)
            // ->bind(':nama', $nama)
            // ->exec(' WITH ada AS (
            //             SELECT
            //                 CONCAT(kode," ", nama, " ", tanggal, " " ,jam) AS conc_ada
            //             FROM htsprtd
            //             WHERE tanggal = :tanggal AND nama = :nama AND tipe = "upload"
            //         ),
            //         excel_data AS (
            //             '.$arr_kode.'
            //         )
            //         SELECT
            //             COUNT(excel_value) AS c_id
            //         FROM excel_data
            //         WHERE excel_value NOT IN (SELECT conc_ada FROM ada);
            //         '
            //         );
            // $rs_tidak_kembar = $qs_tidak_kembar->fetch();
            // $dataupload = $rs_tidak_kembar['c_id'];

            // $qs_kembar = $db
            // ->raw()
            // ->bind(':tanggal', $tanggal)
            // ->bind(':nama', $nama)
            // ->exec(' WITH ada AS (
            //             SELECT
            //                 CONCAT(kode," ", nama, " ", tanggal, " " ,jam) AS conc_ada
            //             FROM htsprtd
            //             WHERE tanggal = :tanggal AND nama = :nama AND tipe = "upload"
            //         ),
            //         excel_data AS (
            //             '.$arr_kode.'
            //         )
            //         SELECT
            //             COUNT(excel_value) AS c_id
            //         FROM excel_data
            //         WHERE excel_value IN (SELECT conc_ada FROM ada);
            //         '
            //         );
            // $rs_kembar = $qs_kembar->fetch();
            // $datakembar = $rs_kembar['c_id'];
        }
    }
?>