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
     * processSheetData_hl($sheetData, $db, $dataupload, $datakembar, "PMI");
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

    function processSheetData_hl($sheetData, $db, &$dataupload, &$datakembar, $nama)
{
    $tanggal = '';
    $arr_kode = '';

    $data_excel = [];

    for ($i = 1; $i < count($sheetData); $i++) {

        // Pastikan kode tidak kosong
        if (!isset($sheetData[$i]['0']) || trim((string) $sheetData[$i]['0']) === '') {
            continue;
        }

        // Format kode menjadi 6 karakter, contoh HL0001
        $kode = 'HL' . str_pad(
            trim((string) $sheetData[$i]['0']),
            4,
            '0',
            STR_PAD_LEFT
        );

        // Ambil waktu dari kolom indeks 3 sesuai kode awal Anda
        $waktu = trim((string) $sheetData[$i]['3']);

        // Pisahkan tanggal dan jam
        $dt = preg_split('/\s+/', $waktu, 2);

        if (count($dt) < 2) {
            continue;
        }

        $str_tanggal = trim($dt[0]);
        $str_jam = trim($dt[1]);

        // Konversi tanggal DD/MM/YYYY menjadi YYYY-MM-DD
        $obj_tanggal = DateTime::createFromFormat('!d/m/Y', $str_tanggal);

        if (!$obj_tanggal || $obj_tanggal->format('d/m/Y') !== $str_tanggal) {
            continue;
        }

        $tanggal = $obj_tanggal->format('Y-m-d');

        // Konversi jam HH:MM menjadi HH:MM:SS
        $obj_jam = DateTime::createFromFormat('!H:i', $str_jam);

        if (!$obj_jam || $obj_jam->format('H:i') !== $str_jam) {
            continue;
        }

        $jam = $obj_jam->format('H:i:s');

        // Simpan sebagai data terpisah, tidak digabung menjadi satu string
        $data_excel[] = [
            'kode'    => $kode,
            'nama'    => $nama,
            'tanggal' => $tanggal,
            'jam'     => $jam
        ];
    }

    if (!empty($data_excel)) {

        // Susun SELECT untuk setiap baris Excel
        $arr_kode = [];

        foreach ($data_excel as $baris) {

            $kode_sql = str_replace("'", "''", $baris['kode']);
            $nama_sql = str_replace("'", "''", $baris['nama']);
            $tanggal_sql = $baris['tanggal'];
            $jam_sql = $baris['jam'];

            $arr_kode[] = "
                SELECT
                    '{$kode_sql}' AS kode,
                    '{$nama_sql}' AS nama,
                    '{$tanggal_sql}' AS tanggal,
                    '{$jam_sql}' AS jam
            ";
        }

        $sql_excel = implode(' UNION ALL ', $arr_kode);

        // Cek duplikasi dan masukkan data yang belum ada
        $qi_ceklok = $db
            ->raw()
            ->bind(':tanggal', $tanggal)
            ->bind(':nama', $nama)
            ->exec("
                INSERT INTO htsprtd
                    (kode, nama, tipe, tanggal, jam)

                WITH ada AS (
                    SELECT
                        kode,
                        nama,
                        tanggal,
                        TIME_FORMAT(jam, '%H:%i:%s') AS jam
                    FROM htsprtd
                    WHERE tanggal BETWEEN
                        DATE_SUB(:tanggal, INTERVAL 1 DAY)
                        AND DATE_ADD(:tanggal, INTERVAL 1 DAY)
                        AND nama = :nama
                        AND tipe = 'upload'
                ),

                excel_data AS (
                    {$sql_excel}
                )

                SELECT
                    e.kode,
                    e.nama,
                    'upload',
                    e.tanggal,
                    e.jam
                FROM excel_data e

                WHERE NOT EXISTS (
                    SELECT 1
                    FROM ada a
                    WHERE a.kode = e.kode
                      AND a.nama = e.nama
                      AND a.tanggal = e.tanggal
                      AND a.jam = e.jam
                )
            ");

        // Hitung jumlah data yang berhasil diunggah
        $qs_tidak_kembar = $db
            ->raw()
            ->exec("
                SELECT ROW_COUNT() AS found_rows
            ");

        $rs_tidak_kembar = $qs_tidak_kembar->fetch();

        $dataupload = (int) $rs_tidak_kembar['found_rows'];

        // Hitung jumlah data duplikat atau tidak diproses
        $datakembar = count($data_excel) - $dataupload;
    } else {
        $dataupload = 0;
        $datakembar = 0;
    }
}
?>