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
     * processSheetData(1, $sheetData, $db, $dataupload, $datakembar, "PMI");
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
    function processSheetData($index, $sheetData, $db, &$dataupload, &$datakembar, $nama)
    {
        // Index dibuat +1 buat pengganti For looping
        if ($index >= count($sheetData)) {
            return; //Jika Index lebih dari data sheet (baris data excel), maka return atau stop
        }
        
        // $nama = 'PMI';
        // Nama dibuat Dinamis saja
        
        // Str pad 4 digit dengan 0 sebagai default,
        //jadi jika 0 tidak terbaca di excel, maka bisa di akali dengan str pad
        $kode = str_pad($sheetData[$index]['0'], 4, '0', STR_PAD_LEFT); 

        $dt = explode(" ", $sheetData[$index]['2']);
        $str_tanggal = $dt[0];
        $tahun = substr($str_tanggal, 6, 4);
        $bulan = substr($str_tanggal, 3, 2);
        $tgl = substr($str_tanggal, 0, 2);
        $tanggal = $tahun . '-' . $bulan . '-' . $tgl;
        $jam = $dt[1];

        $qs_htsprtd = $db
            ->query('select', 'htsprtd')
            ->get(['htsprtd.id as id_htsprtd'])
            ->where('htsprtd.kode', $kode)
            ->where('htsprtd.nama', $nama)
            ->where('htsprtd.tanggal', $tanggal)
            ->where('htsprtd.jam', $jam)
            ->exec();
        $rs_htsprtd = $qs_htsprtd->fetchAll();
        $c_rs_htsprtd = count($rs_htsprtd);

        if ($c_rs_htsprtd == 0) {
            $qi_htsprtd = $db
                ->query('insert', 'htsprtd')
                ->set('kode', $kode)
                ->set('nama', $nama)
                ->set('tipe', 'upload')
                ->set('tanggal', $tanggal)
                ->set('jam', $jam)
                ->exec();
            $dataupload = $dataupload + 1;
        } else {
            $datakembar = $datakembar + 1;
        }

        // Memanggil lagi function ini agar dapat increment indexnya, pengganti for looping
        processSheetData($index + 1, $sheetData, $db, $dataupload, $datakembar, $nama);
    }
?>