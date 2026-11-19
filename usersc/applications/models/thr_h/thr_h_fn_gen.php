
<?php
    include("../../../../users/init.php");
    include("../../../../usersc/lib/DataTables.php");
    include("../../../../usersc/helpers/datatables_fn_debug.php");
    require '../../../../usersc/vendor/autoload.php';

    use Carbon\Carbon;

    use
        DataTables\Editor,
        DataTables\Editor\Query,
        DataTables\Editor\Result;

    // ==========================================
    // DEFINISI VARIABLE AJAX
    // ==========================================
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;

    // ==========================================
    // PARAMETER
    // ==========================================
    $id_thr_h = (int) ($_POST['id_thr_h'] ?? 0);

    if ($id_thr_h <= 0) {
        throw new Exception('ID transaksi THR tidak valid.');
    }

    // ==========================================
    // AMBIL HEADER THR
    // ==========================================
    $qs_thr = $db
        ->query('select', 'thr_h')
        ->get([
            'id',
            'kode',
            'nama',
            'keterangan',
            'tanggal_awal',
            'tanggal_akhir',
            'tanggal_hari_raya',
        ])
        ->where('id', $id_thr_h)
        ->exec();

    $rs_thr = $qs_thr->fetch();

    if (!$rs_thr) {
        throw new Exception('Data header THR tidak ditemukan.');
    }

    // ==========================================
    // VALIDASI TANGGAL
    // ==========================================
    if (
        empty($rs_thr['tanggal_awal']) ||
        empty($rs_thr['tanggal_akhir']) ||
        empty($rs_thr['tanggal_hari_raya'])
    ) {
        throw new Exception('Tanggal periode payroll atau Hari Raya belum diisi.');
    }

    $tanggal_awal      = $rs_thr['tanggal_awal'];
    $tanggal_akhir     = $rs_thr['tanggal_akhir'];
    $tanggal_hari_raya = $rs_thr['tanggal_hari_raya'];

    // Nama detail transaksi payroll
    $nama_thr = 'THR - ' . $id_thr_h;

    // ==========================================
    // AMBIL DATA PEGAWAI DAN HITUNG THR
    // ==========================================
    $qs_thr_detail = $db
        ->raw()
        ->bind(':tanggal_awal', $tanggal_awal)
        ->bind(':tanggal_akhir', $tanggal_akhir)
        ->bind(':tanggal_hari_raya', $tanggal_hari_raya)
        ->exec('

            WITH

            -- ==========================================
            -- PARAMETER THR
            -- ==========================================
            param AS (
                SELECT
                    DATE(:tanggal_awal) AS tanggal_awal,
                    DATE(:tanggal_akhir) AS tanggal_akhir,
                    DATE(:tanggal_hari_raya) AS tanggal_hari_raya,

                    DATE_SUB(
                        DATE(:tanggal_hari_raya),
                        INTERVAL 30 DAY
                    ) AS tanggal_awal_puasa
            ),

            -- ==========================================
            -- KOMPONEN GAJI TERAKHIR
            -- ==========================================
            komponen_terakhir AS (
                SELECT
                    a.id_hemxxmh,
                    a.id_hpcxxmh,
                    a.nominal,

                    ROW_NUMBER() OVER (
                        PARTITION BY
                            a.id_hemxxmh,
                            a.id_hpcxxmh
                        ORDER BY
                            a.tanggal_efektif DESC
                    ) AS urutan

                FROM htpr_hemxxmh a

                CROSS JOIN param p

                WHERE
                    a.id_hpcxxmh IN (1, 32, 133)
                    AND a.tanggal_efektif <= p.tanggal_hari_raya
                    AND a.is_active = 1
            ),

            -- ==========================================
            -- PIVOT KOMPONEN GAJI
            -- ==========================================
            komponen_gaji AS (
                SELECT
                    a.id_hemxxmh,

                    IFNULL(
                        MAX(
                            CASE
                                WHEN a.id_hpcxxmh = 1
                                THEN a.nominal
                            END
                        ), 0
                    ) AS gaji_pokok,

                    IFNULL(
                        MAX(
                            CASE
                                WHEN a.id_hpcxxmh = 32
                                THEN a.nominal
                            END
                        ), 0
                    ) AS tunjangan_jabatan,

                    IFNULL(
                        MAX(
                            CASE
                                WHEN a.id_hpcxxmh = 133
                                THEN a.nominal
                            END
                        ), 0
                    ) AS tunjangan_khusus

                FROM komponen_terakhir a

                WHERE a.urutan = 1

                GROUP BY a.id_hemxxmh
            ),

            -- ==========================================
            -- PEGAWAI AKTIF DALAM PERIODE PAYROLL
            -- ==========================================
            pegawai AS (
                SELECT
                    peg.id,
                    peg.kode,
                    peg.nama,
                    sta.nama AS stats,

                    job.tanggal_masuk,
                    job.tanggal_keluar,

                    p.tanggal_awal,
                    p.tanggal_akhir,
                    p.tanggal_hari_raya,
                    p.tanggal_awal_puasa,

                    DATEDIFF(
                        p.tanggal_hari_raya,
                        job.tanggal_masuk
                    ) AS masa_kerja_hari,

                    TIMESTAMPDIFF(
                        MONTH,
                        job.tanggal_masuk,
                        p.tanggal_hari_raya
                    ) AS masa_kerja_bulan,

                    IFNULL(kg.gaji_pokok, 0) AS gaji_pokok,

                    IFNULL(
                        kg.tunjangan_jabatan, 0
                    ) AS tunjangan_jabatan,

                    IFNULL(
                        kg.tunjangan_khusus, 0
                    ) AS tunjangan_khusus,

                    (
                        IFNULL(kg.gaji_pokok, 0)
                        + IFNULL(kg.tunjangan_jabatan, 0)
                        + IFNULL(kg.tunjangan_khusus, 0)
                    ) AS total_upah_thr

                FROM hemxxmh peg

                JOIN hemjbmh job
                    ON job.id_hemxxmh = peg.id

                JOIN hesxxmh sta
                    ON sta.id = job.id_hesxxmh

                LEFT JOIN komponen_gaji kg
                    ON kg.id_hemxxmh = peg.id

                CROSS JOIN param p

                WHERE
                    job.tanggal_masuk <= p.tanggal_akhir

                    AND (
                        job.tanggal_keluar IS NULL
                        OR job.tanggal_keluar >= p.tanggal_awal
                    )
            ),

            -- ==========================================
            -- PENENTUAN KELAYAKAN THR
            -- ==========================================
            kelayakan AS (
                SELECT
                    a.*,

                    CASE
                        WHEN a.stats = "PELATIHAN"
                            THEN 0

                        WHEN DATE_ADD(
                            a.tanggal_masuk,
                            INTERVAL 1 MONTH
                        ) > a.tanggal_hari_raya
                            THEN 0

                        WHEN a.stats = "KONTRAK"
                            AND (
                                a.tanggal_keluar IS NULL
                                OR a.tanggal_keluar >= a.tanggal_hari_raya
                            )
                            THEN 1

                        WHEN a.stats = "TETAP"
                            THEN 1

                        ELSE NULL
                    END AS is_dapat_thr

                FROM pegawai a
            ),

            -- ==========================================
            -- PERHITUNGAN NOMINAL THR
            -- ==========================================
            perhitungan_thr AS (
                SELECT
                    a.*,

                    CASE
                        WHEN a.is_dapat_thr = 1 THEN
                            ROUND(
                                a.total_upah_thr
                                * LEAST(a.masa_kerja_bulan, 12)
                                / 12,
                                2
                            )
                        ELSE 0
                    END AS nominal_thr

                FROM kelayakan a
            )

            -- ==========================================
            -- HASIL AKHIR
            -- ==========================================
            SELECT
                a.id,
                a.kode,
                a.nama,
                a.stats,

                a.tanggal_masuk,
                a.tanggal_keluar,

                a.masa_kerja_hari,
                a.masa_kerja_bulan,

                a.gaji_pokok,
                a.tunjangan_jabatan,
                a.tunjangan_khusus,

                a.total_upah_thr,
                a.is_dapat_thr,
                a.nominal_thr

            FROM perhitungan_thr a

            WHERE a.is_dapat_thr = 1

            ORDER BY a.nama

        ');

    // ==========================================
    // HAPUS DETAIL THR LAMA
    // ==========================================
    $qd_thr_lama = $db
        ->query('delete', 'thr_d')
        ->where('id_thr_h', $id_thr_h)
        ->exec();

    // ==========================================
    // HAPUS DETAIL PAYROLL THR LAMA
    // ==========================================
    $qd_piutang_lama = $db
        ->query('delete', 'hpy_piutang_d')
        ->where('kode', $rs_thr['kode'])
        ->where('nama', $nama_thr)
        ->where('id_hpcxxmh', 110)
        ->exec();

    // ==========================================
    // INSERT DETAIL THR DAN PAYROLL
    // ==========================================
    while ($rs_detail = $qs_thr_detail->fetch()) {

        // ==========================================
        // INSERT KE THR_D
        // ==========================================
        $qi_thr_d = $db
            ->query('insert', 'thr_d')
            ->set('id_thr_h', $id_thr_h)
            ->set('id_hemxxmh', $rs_detail['id'])
            ->set('kode', $rs_detail['kode'])
            ->set('nama', $rs_detail['nama'])
            ->set('keterangan', $rs_thr['keterangan'])
            ->set('nominal', $rs_detail['nominal_thr'])
            ->set('is_active', 1)
            ->exec();

        // ==========================================
        // INSERT KE HPY_PIUTANG_D
        // ==========================================
        $qi_hpy_piutang_d = $db
            ->query('insert', 'hpy_piutang_d')
            ->set('kode', $rs_thr['kode'])
            ->set('id_hemxxmh', $rs_detail['id'])
            ->set('id_hpcxxmh', 110)
            ->set('nama', $nama_thr)
            ->set('keterangan', $rs_thr['keterangan'])
            ->set('nominal', $rs_detail['nominal_thr'])
            ->set('is_approve', 1)
            ->set('tanggal', $tanggal_hari_raya)
            ->exec();
    }

?>
