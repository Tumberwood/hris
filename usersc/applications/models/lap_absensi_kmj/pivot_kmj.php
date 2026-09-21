<?php
include("../../../../users/init.php");
include("../../../../usersc/lib/DataTables.php");

use
    DataTables\Editor,
    DataTables\Editor\Query,
    DataTables\Editor\Result;

$start_date = isset($_POST['start_date']) ?$_POST['start_date'] : date('Y-m-d');
$end_date   = isset($_POST['end_date']) ? $_POST['end_date'] : date('Y-m-d', strtotime('+6 days', strtotime($start_date)));

// Generate Loop Tanggal Dinamis
$period = new DatePeriod(
    new DateTime($start_date),
    new DateInterval('P1D'),
    (new DateTime($end_date))->modify('+1 day')
);

$select_fields = [];
foreach ($period as $dt) {$tgl = $dt->format('Y-m-d');$label = $dt->format('d-M-y'); // Format key: 06-Sep-26          // Pilih Jml Orang DAN Durasi Total$select_fields[] = "COUNT(CASE WHEN a.tanggal = '{$tgl}' THEN 1 END) AS `{$label}_Jml_Orang`";
    $select_fields[] = "ROUND(SUM(CASE WHEN a.tanggal = '{$tgl}' THEN COALESCE(TIMESTAMPDIFF(MINUTE, a.clock_in, a.clock_out)/60.0, 0) ELSE 0 END), 2) AS `{$label}_Durasi_Total`";
}

$sql_fields = implode(",\n    ", $select_fields);

$sql = "
SELECT 
    CASE 
        WHEN a.st_jadwal LIKE '%PAGI%' THEN 'Shift Pagi'
        WHEN a.st_jadwal LIKE '%SORE%' OR a.st_jadwal LIKE '%SIANG%' THEN 'Shift Siang'
        WHEN a.st_jadwal LIKE '%MALAM%' THEN 'Shift Malam'
    END AS Shift,
    {$sql_fields}
FROM htsprrd a
JOIN hemxxmh b ON b.id = a.id_hemxxmh
JOIN hemjbmh c ON c.id_hemxxmh = a.id_hemxxmh
WHERE a.tanggal BETWEEN :start_date AND :end_date
  AND c.id_heyxxmd = 4
  AND (a.st_jadwal LIKE '%PAGI%' OR a.st_jadwal LIKE '%SORE%' OR a.st_jadwal LIKE '%SIANG%' OR a.st_jadwal LIKE '%MALAM%')
GROUP BY 
    CASE 
        WHEN a.st_jadwal LIKE '%PAGI%' THEN 'Shift Pagi'
        WHEN a.st_jadwal LIKE '%SORE%' OR a.st_jadwal LIKE '%SIANG%' THEN 'Shift Siang'
        WHEN a.st_jadwal LIKE '%MALAM%' THEN 'Shift Malam'
    END
ORDER BY 
    FIELD(Shift, 'Shift Pagi', 'Shift Siang', 'Shift Malam')
";

$qs_payroll =$db
    ->raw()
    ->bind(':start_date', $start_date)
    ->bind(':end_date', $end_date)
    ->exec($sql);

$rs_payroll =$qs_payroll->fetchAll();

if (count($rs_payroll) > 0) {
    echo json_encode(['data' => $rs_payroll]);
} else {
    echo json_encode(['data' => []]);
}
?>