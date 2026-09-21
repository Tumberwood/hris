<script>
    function generateTable(start_date, end_date) {
        $('#tabel_atas').empty();

        $.ajax({
            url: "../../models/lap_absensi_kmj/pivot_kmj.php",
            dataType: 'json',
            type: 'POST',
            data: {
                start_date: start_date,
                end_date: end_date
            },
            success: function (json) {

                if (json.data.length > 0) {

                    var str1 = '';

                    str1 += '<table id="tblhtsprrd1" class="table table-striped table-bordered table-hover nowrap" style="width:100%;">';

                    /*
                    |--------------------------------------------------------------------------
                    | HEADER
                    |--------------------------------------------------------------------------
                    */

                    str1 += '<thead>';

                    // ================================================================
                    // BARIS HEADER 1
                    // ================================================================
                    str1 += '<tr>';

                    // Shift
                    str1 += '<th rowspan="2" style="vertical-align:middle; text-align:center;">';
                    str1 += 'Shift';
                    str1 += '</th>';

                    // Ambil tanggal dari nama column
                    var tanggalColumns = [];

                    $.each(json.columns, function (k, colObj) {

                        var columnName = colObj.data;

                        if (columnName !== 'Shift') {

                            var parts = columnName.split('_');

                            var tanggal = parts[0];

                            if ($.inArray(tanggal, tanggalColumns) === -1) {
                                tanggalColumns.push(tanggal);
                            }
                        }
                    });

                    // Header tanggal
                    $.each(tanggalColumns, function (index, tanggal) {

                        str1 += '<th colspan="2" style="text-align:center;">';
                        str1 += tanggal;
                        str1 += '</th>';

                    });

                    str1 += '</tr>';


                    // ================================================================
                    // BARIS HEADER 2
                    // ================================================================
                    str1 += '<tr>';

                    $.each(tanggalColumns, function (index, tanggal) {

                        str1 += '<th style="text-align:center;">';
                        str1 += 'Jml Orang';
                        str1 += '</th>';

                        str1 += '<th style="text-align:center;">';
                        str1 += 'Durasi Total';
                        str1 += '</th>';

                    });

                    str1 += '</tr>';

                    str1 += '</thead>';


                    /*
                    |--------------------------------------------------------------------------
                    | BODY
                    |--------------------------------------------------------------------------
                    */

                    str1 += '<tbody>';

                    $.each(json.data, function (index, rowData) {

                        str1 += '<tr>';

                        // ============================================================
                        // SHIFT
                        // ============================================================
                        var shiftValue = rowData['Shift'];

                        if (shiftValue === undefined || shiftValue === null || shiftValue === '') {
                            shiftValue = '-';
                        }

                        str1 += '<td style="font-weight:500;">';
                        str1 += shiftValue;
                        str1 += '</td>';


                        // ============================================================
                        // DATA PER TANGGAL
                        // ============================================================
                        $.each(tanggalColumns, function (index, tanggal) {

                            var columnJml = tanggal + '_Jml_Orang';
                            var columnDurasi = tanggal + '_Durasi_Total';

                            var jmlOrang = rowData[columnJml];
                            var durasiTotal = rowData[columnDurasi];


                            // --------------------------------------------------------
                            // JML ORANG
                            // --------------------------------------------------------
                            if (
                                jmlOrang === undefined ||
                                jmlOrang === null ||
                                jmlOrang === ''
                            ) {
                                jmlOrang = 0;
                            }

                            str1 += '<td style="text-align:center;">';
                            str1 += jmlOrang;
                            str1 += '</td>';


                            // --------------------------------------------------------
                            // DURASI TOTAL
                            // --------------------------------------------------------
                            if (
                                durasiTotal === undefined ||
                                durasiTotal === null ||
                                durasiTotal === ''
                            ) {
                                durasiTotal = '0.00';
                            }

                            str1 += '<td style="text-align:center;">';
                            str1 += durasiTotal;
                            str1 += '</td>';

                        });

                        str1 += '</tr>';

                    });

                    str1 += '</tbody>';
                    str1 += '</table>';


                    /*
                    |--------------------------------------------------------------------------
                    | TAMPILKAN TABLE
                    |--------------------------------------------------------------------------
                    */

                    $('#tabel_atas').html(str1);


                    /*
                    |--------------------------------------------------------------------------
                    | DATATABLE
                    |--------------------------------------------------------------------------
                    */

                    tblhtsprrd1 = $('#tblhtsprrd1').DataTable({

                        responsive: false,

                        scrollX: true,

                        ordering: false,

                        paging: false,

                        searching: false,

                        info: false,

                        autoWidth: false,

                        dom:
                            "<lf>" +
                            "<B>" +
                            "<rt>" +
                            "<'row'<'col-sm-4'i><'col-sm-8'p>>",

                        buttons: [

                            <?php
                                $id_table    = 'id_htoxxth';
                                $table       = 'tblhtoxxth';
                                $edt         = 'edthtoxxth';
                                $show_status = '_htoxxth';

                                $table_name  = $nama_tabel;

                                $arr_buttons_tools  = ['copy', 'excel', 'colvis'];
                                $arr_buttons_action = [];
                                $arr_buttons_approve = [];

                                include $abs_us_root . $us_url_root .
                                    'usersc/helpers/button_fn_generate.php';
                            ?>

                        ],

                        columnDefs: [

                            {
                                targets: 0,
                                className: 'text-left',
                                width: '150px'
                            },

                            {
                                targets: '_all',
                                className: 'text-center'
                            }

                        ]

                    });

                } else {

                    notifyprogress = $.notify(
                        {
                            message: 'Tidak ada data pada tanggal tersebut!'
                        },
                        {
                            z_index: 9999,
                            allow_dismiss: false,
                            type: 'danger',
                            delay: 3
                        }
                    );

                }

            },

            error: function (xhr, status, error) {

                console.log('AJAX Error:', xhr.responseText);

                notifyprogress = $.notify(
                    {
                        message: 'Terjadi kesalahan saat mengambil data!'
                    },
                    {
                        z_index: 9999,
                        allow_dismiss: false,
                        type: 'danger',
                        delay: 3
                    }
                );

            }

        });
    }
</script>

<script>

function generateDetailGantt(start_date, end_date) {

    $('#tabel_detail_gantt').empty();

    $.ajax({
        url: "../../models/lap_absensi_kmj/lap_absensi_kmj.php",
        dataType: 'json',
        type: 'POST',

        data: {
            start_date: start_date,
            end_date: end_date,
            id_hemxxmh: 0
        },

        success: function (json) {

            var rows = [];

            if (
                json &&
                json.data &&
                json.data.htsprrd
            ) {
                rows = json.data.htsprrd;
            }

            if (rows.length === 0) {

                $('#tabel_detail_gantt').html(
                    '<div class="alert alert-warning">' +
                        'Tidak ada data detail pada tanggal tersebut.' +
                    '</div>'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | GROUP BERDASARKAN TANGGAL
            |--------------------------------------------------------------------------
            */

            var groupedData = {};

            $.each(rows, function (index, row) {

                var tanggal = row.tanggal;

                if (!groupedData[tanggal]) {
                    groupedData[tanggal] = [];
                }

                groupedData[tanggal].push(row);

            });


            /*
            |--------------------------------------------------------------------------
            | BUILD HTML
            |--------------------------------------------------------------------------
            */

            var html = '';

            $.each(groupedData, function (tanggal, rowsTanggal) {

                html += buildGanttTanggal(
                    tanggal,
                    rowsTanggal
                );

            });


            $('#tabel_detail_gantt').html(html);

        },

        error: function (xhr, status, error) {

            console.log(
                'Gantt Detail Error:',
                xhr.responseText
            );

            $('#tabel_detail_gantt').html(
                '<div class="alert alert-danger">' +
                    'Terjadi kesalahan saat mengambil data detail.' +
                '</div>'
            );

        }

    });

}


/*
|--------------------------------------------------------------------------
| BUILD GANTT PER TANGGAL
|--------------------------------------------------------------------------
*/

function buildGanttTanggal(tanggal, rows) {

    var html = '';

    html += '<div class="gantt-wrapper mb-4">';

    html += '<table class="table table-bordered table-sm gantt-table">';


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    html += '<thead>';

    html += '<tr>';

    html +=
        '<th rowspan="2" style="width:70px;">' +
            'Tanggal' +
        '</th>';

    html +=
        '<th rowspan="2" style="width:70px;">' +
            'Shift' +
        '</th>';

    html +=
        '<th rowspan="2" style="width:250px;">' +
            'Nama' +
        '</th>';

    html +=
        '<th rowspan="2" style="width:140px;">' +
            'Check In' +
        '</th>';

    html +=
        '<th rowspan="2" style="width:140px;">' +
            'Check Out' +
        '</th>';

    html +=
        '<th rowspan="2" style="width:80px;">' +
            'Durasi' +
        '</th>';

    html +=
        '<th colspan="24" class="text-center">' +
            'Bar Chart' +
        '</th>';

    html += '</tr>';


    /*
    |--------------------------------------------------------------------------
    | JAM
    |--------------------------------------------------------------------------
    */

    html += '<tr>';

    for (var hour = 7; hour < 31; hour++) {

        var displayHour = hour % 24;

        var hourText =
            String(displayHour).padStart(2, '0') +
            ':00';

        html +=
            '<th class="gantt-hour">' +
                hourText +
            '</th>';

    }

    html += '</tr>';

    html += '</thead>';


    /*
    |--------------------------------------------------------------------------
    | BODY
    |--------------------------------------------------------------------------
    */

    html += '<tbody>';


    /*
    |--------------------------------------------------------------------------
    | GROUP SHIFT
    |--------------------------------------------------------------------------
    */

    var groupedShift = {
        'Pagi': [],
        'Siang': [],
        'Malam': [],
        'OFF': []
    };


    $.each(rows, function (index, row) {

        var shift = row.Shift;

        if (!groupedShift[shift]) {

            groupedShift[shift] = [];

        }

        groupedShift[shift].push(row);

    });


    /*
    |--------------------------------------------------------------------------
    | URUTAN SHIFT
    |--------------------------------------------------------------------------
    */

    var shiftOrder = [
        'Pagi',
        'Siang',
        'Malam',
        'OFF'
    ];


    $.each(shiftOrder, function (index, shiftName) {

        var shiftRows = groupedShift[shiftName];

        if (!shiftRows || shiftRows.length === 0) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | ROW
        |--------------------------------------------------------------------------
        */

        $.each(shiftRows, function (rowIndex, row) {

            html += '<tr>';


            /*
            |--------------------------------------------------------------------------
            | TANGGAL
            |--------------------------------------------------------------------------
            */

            if (rowIndex === 0) {

                html +=
                    '<td rowspan="' +
                        shiftRows.length +
                    '" class="font-weight-bold text-center">' +
                        escapeHtml(tanggal) +
                    '</td>';

            }


            /*
            |--------------------------------------------------------------------------
            | SHIFT
            |--------------------------------------------------------------------------
            */

            if (rowIndex === 0) {

                html +=
                    '<td rowspan="' +
                        shiftRows.length +
                    '" class="font-weight-bold">' +
                        escapeHtml(shiftName) +
                    '</td>';

            }


            /*
            |--------------------------------------------------------------------------
            | NAMA
            |--------------------------------------------------------------------------
            */

            html +=
                '<td>' +
                    escapeHtml(row.Nama || '-') +
                '</td>';


            /*
            |--------------------------------------------------------------------------
            | CHECK IN
            |--------------------------------------------------------------------------
            */

            html +=
                '<td>' +
                    escapeHtml(row['Check In'] || '-') +
                '</td>';


            /*
            |--------------------------------------------------------------------------
            | CHECK OUT
            |--------------------------------------------------------------------------
            */

            html +=
                '<td>' +
                    escapeHtml(row['Check Out'] || '-') +
                '</td>';


            /*
            |--------------------------------------------------------------------------
            | DURASI
            |--------------------------------------------------------------------------
            */

            html +=
                '<td class="text-center">' +
                    (
                        row['Durasi (Jam)'] !== null &&
                        row['Durasi (Jam)'] !== undefined
                            ? row['Durasi (Jam)']
                            : '-'
                    ) +
                '</td>';


            /*
            |--------------------------------------------------------------------------
            | GANTT BAR
            |--------------------------------------------------------------------------
            */

            html += buildGanttBar(row);


            html += '</tr>';

        });

    });


    html += '</tbody>';

    html += '</table>';

    html += '</div>';


    return html;

}


/*
|--------------------------------------------------------------------------
| BUILD BAR
|--------------------------------------------------------------------------
*/

function buildGanttBar(row) {

    var checkIn = row['Check In'];

    var checkOut = row['Check Out'];


    var html = '';

    html +=
        '<td colspan="24" class="gantt-cell">';

    html +=
        '<div class="gantt-track">';


    /*
    |--------------------------------------------------------------------------
    | GRID 24 JAM
    |--------------------------------------------------------------------------
    */

    for (var i = 0; i < 24; i++) {

        html +=
            '<div class="gantt-grid"></div>';

    }


    /*
    |--------------------------------------------------------------------------
    | OFF / TIDAK ADA ABSENSI
    |--------------------------------------------------------------------------
    */

    if (!checkIn || !checkOut) {

        html += '</div></td>';

        return html;

    }


    /*
    |--------------------------------------------------------------------------
    | PARSE
    |--------------------------------------------------------------------------
    */

    var start = parseGanttDate(checkIn);

    var end = parseGanttDate(checkOut);


    if (!start || !end) {

        html += '</div></td>';

        return html;

    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE:
    |
    | 07:00 hari ini
    | sampai
    | 07:00 hari berikutnya
    |--------------------------------------------------------------------------
    */

    var base = new Date(start);

    base.setHours(7, 0, 0, 0);


    var nextDay = new Date(base);

    nextDay.setDate(
        nextDay.getDate() + 1
    );


    /*
    |--------------------------------------------------------------------------
    | CHECK IN
    |--------------------------------------------------------------------------
    */

    var startTime = new Date(start);


    /*
    |--------------------------------------------------------------------------
    | CHECK IN SEBELUM 07:00
    |--------------------------------------------------------------------------
    */

    if (startTime < base) {

        startTime = new Date(base);

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK OUT
    |--------------------------------------------------------------------------
    */

    var endTime = new Date(end);


    /*
    | Jika checkout <= base,
    | berarti checkout hari berikutnya.
    */

    if (endTime <= base) {

        endTime.setDate(
            endTime.getDate() + 1
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BATAS TIMELINE
    |--------------------------------------------------------------------------
    */

    if (endTime > nextDay) {

        endTime = new Date(nextDay);

    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG MENIT
    |--------------------------------------------------------------------------
    */

    var totalMinutes = 1440;


    var startMinutes =
        (startTime.getTime() - base.getTime()) /
        60000;


    var endMinutes =
        (endTime.getTime() - base.getTime()) /
        60000;


    var durationMinutes =
        endMinutes - startMinutes;


    if (durationMinutes <= 0) {

        html += '</div></td>';

        return html;

    }


    /*
    |--------------------------------------------------------------------------
    | PERSENTASE
    |--------------------------------------------------------------------------
    */

    var left =
        (startMinutes / totalMinutes) * 100;


    var width =
        (durationMinutes / totalMinutes) * 100;


    /*
    |--------------------------------------------------------------------------
    | CLASS SHIFT
    |--------------------------------------------------------------------------
    */

    var shiftClass =
        'gantt-bar-default';


    if (row.Shift === 'Pagi') {

        shiftClass =
            'gantt-bar-pagi';

    } else if (row.Shift === 'Siang') {

        shiftClass =
            'gantt-bar-siang';

    } else if (row.Shift === 'Malam') {

        shiftClass =
            'gantt-bar-malam';

    }


    /*
    |--------------------------------------------------------------------------
    | BAR
    |--------------------------------------------------------------------------
    */

    html +=
        '<div class="gantt-bar ' +
            shiftClass +
        '" ' +
        'style="' +
            'left:' + left + '%;' +
            'width:' + width + '%;' +
        '">' +

            '<span>' +
                formatGanttJam(start) +
                ' - ' +
                formatGanttJam(end) +
            '</span>' +

        '</div>';


    html += '</div>';

    html += '</td>';


    return html;

}


/*
|--------------------------------------------------------------------------
| PARSE:
| 06 Sep 2026 14:46
|--------------------------------------------------------------------------
*/

function parseGanttDate(value) {

    if (!value) {
        return null;
    }


    var parts =
        value.trim().split(' ');


    if (parts.length < 4) {
        return null;
    }


    var day =
        parseInt(parts[0], 10);


    var monthText =
        parts[1];


    var year =
        parseInt(parts[2], 10);


    var timeParts =
        parts[3].split(':');


    var monthMap = {

        Jan: 0,
        Feb: 1,
        Mar: 2,
        Apr: 3,
        May: 4,
        Jun: 5,
        Jul: 6,
        Aug: 7,
        Sep: 8,
        Oct: 9,
        Nov: 10,
        Dec: 11

    };


    if (
        monthMap[monthText] === undefined
    ) {

        return null;

    }


    return new Date(

        year,

        monthMap[monthText],

        day,

        parseInt(timeParts[0], 10),

        parseInt(timeParts[1], 10),

        0,

        0

    );

}


/*
|--------------------------------------------------------------------------
| FORMAT JAM
|--------------------------------------------------------------------------
*/

function formatGanttJam(date) {

    if (!date) {
        return '-';
    }


    return (
        String(date.getHours()).padStart(2, '0') +
        ':' +
        String(date.getMinutes()).padStart(2, '0')
    );

}


/*
|--------------------------------------------------------------------------
| ESCAPE HTML
|--------------------------------------------------------------------------
*/

function escapeHtml(value) {

    if (
        value === null ||
        value === undefined
    ) {
        return '';
    }


    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

}

</script>