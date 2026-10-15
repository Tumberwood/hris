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
        $jumlah_data = 0;

        for ($i = 1; $i < count($sheetData); $i++) {

            // Ambil kode pegawai dari Excel.
            $raw_kode = trim((string) ($sheetData[$i][0] ?? ''));

            if ($raw_kode === '') {
                continue;
            }

            $kode = 'HL' . str_pad(
                (string) (int) $raw_kode,
                4,
                '0',
                STR_PAD_LEFT
            );

            // Ambil tanggal dan jam dari kolom Time.
            $raw_datetime = $sheetData[$i][3] ?? '';

            if ($raw_datetime === '' || $raw_datetime === null) {
                continue;
            }

            $dateObj = false;

            // 1. Serial number Excel.
            if (is_numeric($raw_datetime)) {
                try {
                    $dateObj = \PhpOffice\PhpSpreadsheet\Shared\Date
                        ::excelToDateTimeObject((float) $raw_datetime);
                } catch (\Throwable $e) {
                    $dateObj = false;
                }
            } elseif ($raw_datetime instanceof \DateTimeInterface) {
                // 2. Objek tanggal.
                $dateObj = \DateTime::createFromInterface($raw_datetime);
            } else {
                // 3. String tanggal.
                $raw_datetime = trim((string) $raw_datetime);

                $formats = [
                    'd/m/Y H:i:s',
                    'd/m/Y H:i',
                    'd-m-Y H:i:s',
                    'd-m-Y H:i',
                    'Y-m-d H:i:s',
                    'Y-m-d H:i',
                ];

                foreach ($formats as $fmt) {
                    $d = DateTime::createFromFormat('!' . $fmt, $raw_datetime);
                    $errors = DateTime::getLastErrors();

                    if (
                        $d !== false &&
                        ($errors === false ||
                            (
                                $errors['warning_count'] === 0 &&
                                $errors['error_count'] === 0
                            )) &&
                        $d->format($fmt) === $raw_datetime
                    ) {
                        $dateObj = $d;
                        break;
                    }
                }
            }

            // Lewati data jika tanggal tidak valid.
            if ($dateObj === false) {
                continue;
            }

            $tanggal = $dateObj->format('Y-m-d');
            $jam     = $dateObj->format('H:i:s');

            // Pertahankan format excel_value untuk query.
            $excel_value = $kode . ' ' . $nama . ' ' . $tanggal . ' ' . $jam;

            // Escape tanda kutip untuk SQL.
            $excel_value = str_replace('"', '""', $excel_value);

            if ($jumlah_data === 0) {
                $arr_kode = 'SELECT "' . $excel_value . '" AS excel_value ';
            } else {
                $arr_kode .= ' UNION ALL SELECT "' . $excel_value . '" ';
            }

            $jumlah_data++;
        }

        if ($arr_kode !== '') {

            // Gunakan tanggal awal dan akhir dari seluruh data Excel.
            // Untuk pengecekan duplikasi tetap menggunakan rentang +/- 1 hari.
            $qi_ceklok = $db
                ->raw()
                ->bind(':tanggal', $tanggal)
                ->bind(':nama', $nama)
                ->exec('
                    INSERT INTO htsprtd (kode, nama, tipe, tanggal, jam)
                    WITH ada AS (
                        SELECT
                            CONCAT(
                                kode, " ", nama, " ", tanggal, " ",
                                TIME_FORMAT(jam, "%H:%i")
                            ) AS conc_ada
                        FROM htsprtd
                        WHERE tanggal BETWEEN
                            DATE_SUB(:tanggal, INTERVAL 1 DAY)
                            AND DATE_ADD(:tanggal, INTERVAL 1 DAY)
                            AND nama = :nama
                            AND tipe = "upload"
                    ),
                    excel_data AS (
                        ' . $arr_kode . '
                    )
                    SELECT
                        SUBSTRING_INDEX(excel_value, " ", 1) AS kode,
                        SUBSTRING_INDEX(
                            SUBSTRING_INDEX(excel_value, " ", 2),
                            " ",
                            -1
                        ) AS nama,
                        "upload",
                        SUBSTRING_INDEX(
                            SUBSTRING_INDEX(excel_value, " ", 3),
                            " ",
                            -1
                        ) AS tanggal,
                        SUBSTRING_INDEX(
                            SUBSTRING_INDEX(excel_value, " ", 4),
                            " ",
                            -1
                        ) AS jam
                    FROM excel_data
                    WHERE excel_value NOT IN (
                        SELECT conc_ada FROM ada
                    )
                ');

            $qs_tidak_kembar = $db
                ->raw()
                ->exec('
                    SELECT FOUND_ROWS() AS found_rows
                ');

            $rs_tidak_kembar = $qs_tidak_kembar->fetch();

            $dataupload = (int) ($rs_tidak_kembar['found_rows'] ?? 0);
            $datakembar = $jumlah_data - $dataupload;
        }
    }

?>