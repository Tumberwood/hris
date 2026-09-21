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
function generateGanttAbsensi(start_date, end_date) {

    $('#gantt_absensi_kmj').empty();

    $.ajax({
        url: "../../models/lap_absensi_kmj/lap_absensi_kmj.php",
        type: "POST",
        dataType: "json",
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

            /*
            |--------------------------------------------------------------------------
            | HANYA DATA YANG ADA CHECK IN / CHECK OUT
            |--------------------------------------------------------------------------
            */

            rows = rows.filter(function (row) {

                return (
                    row['Check In'] &&
                    row['Check Out'] &&
                    row.Shift !== 'OFF'
                );

            });

            if (rows.length === 0) {

                $('#gantt_absensi_kmj').html(
                    '<div class="alert alert-warning">' +
                        'Tidak ada data absensi untuk ditampilkan.' +
                    '</div>'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | CATEGORY = NAMA
            |--------------------------------------------------------------------------
            */

            var categories = [];

            var categoryMap = {};

            $.each(rows, function (index, row) {

                var nik = row.NIK;

                if (categoryMap[nik] === undefined) {

                    categoryMap[nik] = categories.length;

                    categories.push(
                        $.trim(row.Nama)
                    );

                }

            });


            /*
            |--------------------------------------------------------------------------
            | DATA GANTT
            |--------------------------------------------------------------------------
            */

            var seriesData = [];


            $.each(rows, function (index, row) {

                /*
                |--------------------------------------------------------------------------
                | PARSE CHECK IN
                |--------------------------------------------------------------------------
                */

                var checkIn = row['Check In'];

                var checkOut = row['Check Out'];

                var inParts = checkIn.split(' ');

                var outParts = checkOut.split(' ');


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


                var inDate = new Date(
                    parseInt(inParts[2]),
                    monthMap[inParts[1]],
                    parseInt(inParts[0]),
                    parseInt(inParts[3].split(':')[0]),
                    parseInt(inParts[3].split(':')[1]),
                    0
                );


                var outDate = new Date(
                    parseInt(outParts[2]),
                    monthMap[outParts[1]],
                    parseInt(outParts[0]),
                    parseInt(outParts[3].split(':')[0]),
                    parseInt(outParts[3].split(':')[1]),
                    0
                );


                /*
                |--------------------------------------------------------------------------
                | WARNA
                |--------------------------------------------------------------------------
                */

                var color = '#ffc107';


                /*
                |--------------------------------------------------------------------------
                | GANTT DATA
                |--------------------------------------------------------------------------
                */

                seriesData.push({

                    id:
                        row.NIK +
                        '_' +
                        row.tanggal +
                        '_' +
                        index,

                    name:
                        row.Shift,

                    start:
                        inDate.getTime(),

                    end:
                        outDate.getTime(),

                    y:
                        categoryMap[row.NIK],

                    color:
                        color,

                    custom: {

                        nik:
                            row.NIK,

                        nama:
                            $.trim(row.Nama),

                        tanggal:
                            row.tanggal,

                        shift:
                            row.Shift,

                        checkIn:
                            row['Check In'],

                        checkOut:
                            row['Check Out'],

                        durasi:
                            row['Durasi (Jam)']

                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | RENDER GANTT
            |--------------------------------------------------------------------------
            */

            Highcharts.ganttChart(
                'gantt_absensi_kmj',
                {

                    chart: {

                        height:
                            Math.max(
                                500,
                                categories.length * 45 + 180
                            ),

                        spacingLeft: 10,

                        spacingRight: 20

                    },


                    title: {

                        text:
                            'Gantt Chart Absensi KMJ'

                    },


                    subtitle: {

                        text:
                            start_date +
                            ' s/d ' +
                            end_date

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | X AXIS = WAKTU KE KANAN
                    |--------------------------------------------------------------------------
                    */

                    xAxis: {

                        type: 'datetime',

                        grid: {

                            enabled: true

                        },

                        labels: {

                            format:
                                '{value:%d-%b %H:%M}'

                        }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Y AXIS = NAMA
                    |--------------------------------------------------------------------------
                    */

                    yAxis: {

                        type: 'category',

                        categories:
                            categories,

                        reversed:
                            true,

                        title: {

                            text:
                                'Nama'

                        }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | TOOLTIP
                    |--------------------------------------------------------------------------
                    */

                    tooltip: {

                        useHTML: true,

                        pointFormatter: function () {

                            var c =
                                this.custom;

                            return (

                                '<div style="padding:5px;">' +

                                '<b>' +
                                    c.nama +
                                '</b><br>' +

                                'NIK: ' +
                                    c.nik +
                                '<br>' +

                                'Tanggal: ' +
                                    c.tanggal +
                                '<br>' +

                                'Shift: ' +
                                    c.shift +
                                '<br>' +

                                'Check In: ' +
                                    c.checkIn +
                                '<br>' +

                                'Check Out: ' +
                                    c.checkOut +
                                '<br>' +

                                'Durasi: ' +
                                    c.durasi +
                                    ' jam' +

                                '</div>'

                            );

                        }

                    },


                    legend: {

                        enabled: false

                    },


                    navigator: {

                        enabled: true,

                        liveRedraw: true

                    },


                    scrollbar: {

                        enabled: true

                    },


                    series: [

                        {

                            name:
                                'Absensi',

                            data:
                                seriesData

                        }

                    ]

                }
            );

        },


        error: function (xhr) {

            console.log(
                'Gantt Error:',
                xhr.responseText
            );

            $('#gantt_absensi_kmj').html(
                '<div class="alert alert-danger">' +
                    'Gagal mengambil data Gantt.' +
                '</div>'
            );

        }

    });

}

function generateGanttAbsensiV2(start_date, end_date) {

    $('#gantt_absensi_kmj').empty();

    $.ajax({
        url: "../../models/lap_absensi_kmj/lap_absensi_kmj.php",
        type: "POST",
        dataType: "json",

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


            /*
            |--------------------------------------------------------------------------
            | BUAT LIST TANGGAL
            |--------------------------------------------------------------------------
            */

            var tanggalList = [];

            $.each(rows, function (index, row) {

                if ($.inArray(row.tanggal, tanggalList) === -1) {
                    tanggalList.push(row.tanggal);
                }

            });


            /*
            |--------------------------------------------------------------------------
            | SORT TANGGAL
            |--------------------------------------------------------------------------
            */

            tanggalList.sort(function (a, b) {

                var pa = a.split(' ');
                var pb = b.split(' ');

                var ma = {
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

                var da = new Date(
                    parseInt(pa[2]),
                    ma[pa[1]],
                    parseInt(pa[0])
                );

                var db = new Date(
                    parseInt(pb[2]),
                    ma[pb[1]],
                    parseInt(pb[0])
                );

                return da - db;

            });


            if (tanggalList.length === 0) {

                $('#gantt_absensi_kmj').html(
                    '<div class="alert alert-warning">' +
                        'Tidak ada data absensi.' +
                    '</div>'
                );

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | LIST ORANG
            |--------------------------------------------------------------------------
            */

            var people = [];

            var peopleMap = {};


            $.each(rows, function (index, row) {

                var nik = row.NIK;

                if (peopleMap[nik] === undefined) {

                    peopleMap[nik] = people.length;

                    people.push({
                        NIK: nik,
                        Nama: $.trim(row.Nama)
                    });

                }

            });


            /*
            |--------------------------------------------------------------------------
            | SORT NAMA
            |--------------------------------------------------------------------------
            */

            people.sort(function (a, b) {

                return a.Nama.localeCompare(
                    b.Nama
                );

            });


            /*
            |--------------------------------------------------------------------------
            | DATA LOOKUP
            |--------------------------------------------------------------------------
            */

            var dataMap = {};


            $.each(rows, function (index, row) {

                var key =
                    row.NIK +
                    '|' +
                    row.tanggal +
                    '|' +
                    row.Shift;

                dataMap[key] = row;

            });


            /*
            |--------------------------------------------------------------------------
            | HTML
            |--------------------------------------------------------------------------
            */

            var html = '';

            html +=
                '<div class="gantt-v2-wrapper">';

            html +=
                '<table class="gantt-v2-table">';


            /*
            |--------------------------------------------------------------------------
            | HEADER BARIS 1
            |--------------------------------------------------------------------------
            */

            html += '<thead>';

            html += '<tr>';

            html +=
                '<th rowspan="2" class="gantt-v2-nik">' +
                    'NIK' +
                '</th>';

            html +=
                '<th rowspan="2" class="gantt-v2-nama">' +
                    'Nama' +
                '</th>';


            /*
            |--------------------------------------------------------------------------
            | TANGGAL KE KANAN
            |--------------------------------------------------------------------------
            */

            $.each(tanggalList, function (index, tanggal) {

                html +=
                    '<th colspan="3" class="gantt-v2-date">' +
                        tanggal +
                    '</th>';

            });

            html += '</tr>';


            /*
            |--------------------------------------------------------------------------
            | HEADER SHIFT
            |--------------------------------------------------------------------------
            */

            html += '<tr>';

            $.each(tanggalList, function () {

                html +=
                    '<th class="gantt-v2-shift">' +
                        'Pagi' +
                    '</th>';

                html +=
                    '<th class="gantt-v2-shift">' +
                        'Siang' +
                    '</th>';

                html +=
                    '<th class="gantt-v2-shift">' +
                        'Malam' +
                    '</th>';

            });

            html += '</tr>';

            html += '</thead>';


            /*
            |--------------------------------------------------------------------------
            | BODY
            |--------------------------------------------------------------------------
            */

            html += '<tbody>';


            $.each(people, function (personIndex, person) {

                html += '<tr>';


                /*
                |--------------------------------------------------------------------------
                | NIK
                |--------------------------------------------------------------------------
                */

                html +=
                    '<td class="gantt-v2-nik-cell">' +
                        escapeHtml(person.NIK) +
                    '</td>';


                /*
                |--------------------------------------------------------------------------
                | NAMA
                |--------------------------------------------------------------------------
                */

                html +=
                    '<td class="gantt-v2-nama-cell">' +
                        escapeHtml(person.Nama) +
                    '</td>';


                /*
                |--------------------------------------------------------------------------
                | SETIAP TANGGAL
                |--------------------------------------------------------------------------
                */

                $.each(tanggalList, function (dateIndex, tanggal) {


                    /*
                    |--------------------------------------------------------------------------
                    | PAGI
                    |--------------------------------------------------------------------------
                    */

                    var pagiKey =
                        person.NIK +
                        '|' +
                        tanggal +
                        '|Pagi';

                    var pagi =
                        dataMap[pagiKey];


                    html +=
                        '<td class="gantt-v2-bar-cell">' +
                            buildGanttV2Bar(pagi) +
                        '</td>';


                    /*
                    |--------------------------------------------------------------------------
                    | SIANG
                    |--------------------------------------------------------------------------
                    */

                    var siangKey =
                        person.NIK +
                        '|' +
                        tanggal +
                        '|Siang';

                    var siang =
                        dataMap[siangKey];


                    html +=
                        '<td class="gantt-v2-bar-cell">' +
                            buildGanttV2Bar(siang) +
                        '</td>';


                    /*
                    |--------------------------------------------------------------------------
                    | MALAM
                    |--------------------------------------------------------------------------
                    */

                    var malamKey =
                        person.NIK +
                        '|' +
                        tanggal +
                        '|Malam';

                    var malam =
                        dataMap[malamKey];


                    html +=
                        '<td class="gantt-v2-bar-cell">' +
                            buildGanttV2Bar(malam) +
                        '</td>';

                });


                html += '</tr>';

            });


            html += '</tbody>';

            html += '</table>';

            html += '</div>';


            $('#gantt_absensi_kmj').html(html);

        },


        error: function (xhr) {

            console.log(
                'Gantt V2 Error:',
                xhr.responseText
            );

            $('#gantt_absensi_kmj').html(

                '<div class="alert alert-danger">' +
                    'Gagal mengambil data Gantt V2.' +
                '</div>'

            );

        }

    });


    /*
    |--------------------------------------------------------------------------
    | BUILD BAR
    |--------------------------------------------------------------------------
    */

    function buildGanttV2Bar(row) {

        if (!row) {

            return '<div class="gantt-v2-empty"></div>';

        }


        var checkIn =
            row['Check In'];

        var checkOut =
            row['Check Out'];


        if (!checkIn || !checkOut) {

            return '<div class="gantt-v2-empty"></div>';

        }


        /*
        |--------------------------------------------------------------------------
        | PARSE TANGGAL
        |--------------------------------------------------------------------------
        */

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


        var inParts =
            checkIn.split(' ');

        var outParts =
            checkOut.split(' ');


        var inTime =
            inParts[3].split(':');

        var outTime =
            outParts[3].split(':');


        var start =
            new Date(
                parseInt(inParts[2]),
                monthMap[inParts[1]],
                parseInt(inParts[0]),
                parseInt(inTime[0]),
                parseInt(inTime[1]),
                0
            );


        var end =
            new Date(
                parseInt(outParts[2]),
                monthMap[outParts[1]],
                parseInt(outParts[0]),
                parseInt(outTime[0]),
                parseInt(outTime[1]),
                0
            );


        /*
        |--------------------------------------------------------------------------
        | TIMELINE PER SHIFT
        |
        | PAGI  = 07:00 - 15:00
        | SIANG = 15:00 - 23:00
        | MALAM = 23:00 - 07:00
        |--------------------------------------------------------------------------
        */

        var shift =
            row.Shift;


        var shiftStartHour;

        var shiftEndHour;


        if (shift === 'Pagi') {

            shiftStartHour = 7;
            shiftEndHour = 15;

        }
        else if (shift === 'Siang') {

            shiftStartHour = 15;
            shiftEndHour = 23;

        }
        else if (shift === 'Malam') {

            shiftStartHour = 23;
            shiftEndHour = 31;

        }
        else {

            return '<div class="gantt-v2-empty"></div>';

        }


        /*
        |--------------------------------------------------------------------------
        | BASE TIMELINE
        |--------------------------------------------------------------------------
        */

        var base =
            new Date(start);

        base.setHours(
            shiftStartHour % 24,
            0,
            0,
            0
        );


        /*
        |--------------------------------------------------------------------------
        | SHIFT MALAM
        |--------------------------------------------------------------------------
        */

        if (shift === 'Malam') {

            /*
            23:00 → 07:00
            */

            if (end <= base) {

                end.setDate(
                    end.getDate() + 1
                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SHIFT PAGI / SIANG
        |--------------------------------------------------------------------------
        */

        var shiftEnd =
            new Date(base);


        if (shift === 'Malam') {

            shiftEnd.setDate(
                shiftEnd.getDate() + 1
            );

            shiftEnd.setHours(
                7,
                0,
                0,
                0
            );

        }
        else {

            shiftEnd.setHours(
                shiftEnd.getHours() +
                8
            );

        }


        /*
        |--------------------------------------------------------------------------
        | BATAS BAR
        |--------------------------------------------------------------------------
        */

        var barStart =
            new Date(start);

        var barEnd =
            new Date(end);


        if (barStart < base) {

            barStart =
                new Date(base);

        }


        if (barEnd > shiftEnd) {

            barEnd =
                new Date(shiftEnd);

        }


        /*
        |--------------------------------------------------------------------------
        | HITUNG POSISI
        |--------------------------------------------------------------------------
        */

        var totalMinutes =
            8 * 60;


        var startMinutes =
            (
                barStart.getTime() -
                base.getTime()
            ) / 60000;


        var endMinutes =
            (
                barEnd.getTime() -
                base.getTime()
            ) / 60000;


        var durationMinutes =
            endMinutes -
            startMinutes;


        if (durationMinutes <= 0) {

            return '<div class="gantt-v2-empty"></div>';

        }


        var left =
            (
                startMinutes /
                totalMinutes
            ) * 100;


        var width =
            (
                durationMinutes /
                totalMinutes
            ) * 100;


        /*
        |--------------------------------------------------------------------------
        | TOOLTIP
        |--------------------------------------------------------------------------
        */

        var tooltip =
            'NIK: ' +
            escapeHtml(row.NIK || '') +
            '&#10;' +
            'Shift: ' +
            escapeHtml(row.Shift || '') +
            '&#10;' +
            'Check In: ' +
            escapeHtml(checkIn) +
            '&#10;' +
            'Check Out: ' +
            escapeHtml(checkOut) +
            '&#10;' +
            'Durasi: ' +
            escapeHtml(
                row['Durasi (Jam)'] || '-'
            ) +
            ' jam';


        /*
        |--------------------------------------------------------------------------
        | BAR
        |--------------------------------------------------------------------------
        */

        return (

            '<div class="gantt-v2-track">' +

                '<div class="gantt-v2-bar" ' +

                    'style="' +
                        'left:' + left + '%;' +
                        'width:' + width + '%;' +
                    '" ' +

                    'title="' +
                        tooltip.replace(/"/g, '&quot;') +
                    '">' +

                    '<span>' +
                        formatGanttV2Time(start) +
                        ' - ' +
                        formatGanttV2Time(end) +
                    '</span>' +

                '</div>' +

            '</div>'

        );

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT JAM
    |--------------------------------------------------------------------------
    */

    function formatGanttV2Time(date) {

        return (

            String(
                date.getHours()
            ).padStart(2, '0') +

            ':' +

            String(
                date.getMinutes()
            ).padStart(2, '0')

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

}

function generateGanttAbsensiV4(start_date, end_date) {

    $('#gantt_absensi_kmj').empty();

    $.ajax({
        url: "../../models/lap_absensi_kmj/lap_absensi_kmj.php",
        type: "POST",
        dataType: "json",
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


            /*
            |--------------------------------------------------------------------------
            | HANYA DATA YANG ADA ABSENSI
            |--------------------------------------------------------------------------
            */

            rows = rows.filter(function (row) {

                return (
                    row['Check In'] &&
                    row['Check Out'] &&
                    row.Shift !== 'OFF'
                );

            });


            if (rows.length === 0) {

                $('#gantt_absensi_kmj').html(
                    '<div class="alert alert-warning">' +
                        'Tidak ada data absensi.' +
                    '</div>'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | MONTH
            |--------------------------------------------------------------------------
            */

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


            /*
            |--------------------------------------------------------------------------
            | PARSE DATE
            |--------------------------------------------------------------------------
            */

            function parseDate(value) {

                var p =
                    value.trim().split(' ');

                if (p.length < 4) {
                    return null;
                }

                var t =
                    p[3].split(':');

                return new Date(
                    parseInt(p[2], 10),
                    monthMap[p[1]],
                    parseInt(p[0], 10),
                    parseInt(t[0], 10),
                    parseInt(t[1], 10),
                    0
                );

            }


            /*
            |--------------------------------------------------------------------------
            | FORMAT DATE
            |--------------------------------------------------------------------------
            */

            function formatDate(date) {

                var day =
                    String(
                        date.getDate()
                    ).padStart(2, '0');

                var month =
                    date.toLocaleString(
                        'en-US',
                        {
                            month: 'short'
                        }
                    );

                var year =
                    String(
                        date.getFullYear()
                    ).slice(-2);

                return (
                    day +
                    '-' +
                    month +
                    '-' +
                    year
                );

            }


            /*
            |--------------------------------------------------------------------------
            | FORMAT TIME
            |--------------------------------------------------------------------------
            */

            function formatTime(date) {

                return (
                    String(
                        date.getHours()
                    ).padStart(2, '0') +
                    ':' +
                    String(
                        date.getMinutes()
                    ).padStart(2, '0')
                );

            }


            /*
            |--------------------------------------------------------------------------
            | START / END
            |--------------------------------------------------------------------------
            */

            var minDate = null;
            var maxDate = null;


            $.each(rows, function (index, row) {

                var start =
                    parseDate(
                        row['Check In']
                    );

                var end =
                    parseDate(
                        row['Check Out']
                    );


                if (!start || !end) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | MALAM
                |--------------------------------------------------------------------------
                */

                if (
                    row.Shift === 'Malam' &&
                    end <= start
                ) {

                    end.setDate(
                        end.getDate() + 1
                    );

                }


                if (
                    !minDate ||
                    start < minDate
                ) {

                    minDate =
                        new Date(start);

                }


                if (
                    !maxDate ||
                    end > maxDate
                ) {

                    maxDate =
                        new Date(end);

                }

            });


            if (!minDate || !maxDate) {

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | TIMELINE DISET KE 07:00
            |--------------------------------------------------------------------------
            */

            minDate.setHours(
                7,
                0,
                0,
                0
            );


            /*
            |--------------------------------------------------------------------------
            | END TIMELINE
            |--------------------------------------------------------------------------
            */

            maxDate.setHours(
                7,
                0,
                0,
                0
            );

            maxDate.setDate(
                maxDate.getDate() + 1
            );


            /*
            |--------------------------------------------------------------------------
            | CATEGORY ORANG
            |--------------------------------------------------------------------------
            */

            var people = [];
            var peopleMap = {};


            $.each(rows, function (index, row) {

                var nik = row.NIK;

                if (
                    nik &&
                    peopleMap[nik] === undefined
                ) {

                    peopleMap[nik] =
                        people.length;

                    people.push({
                        NIK: nik,
                        Nama:
                            $.trim(
                                row.Nama || '-'
                            )
                    });

                }

            });


            /*
            |--------------------------------------------------------------------------
            | SORT NAMA
            |--------------------------------------------------------------------------
            */

            people.sort(function (a, b) {

                return a.Nama.localeCompare(
                    b.Nama
                );

            });


            /*
            |--------------------------------------------------------------------------
            | UPDATE INDEX SETELAH SORT
            |--------------------------------------------------------------------------
            */

            peopleMap = {};

            $.each(
                people,
                function (index, person) {

                    peopleMap[
                        person.NIK
                    ] = index;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | GANTT DATA
            |--------------------------------------------------------------------------
            */

            var ganttData = [];


            $.each(rows, function (index, row) {

                var start =
                    parseDate(
                        row['Check In']
                    );

                var end =
                    parseDate(
                        row['Check Out']
                    );


                if (!start || !end) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | MALAM
                |--------------------------------------------------------------------------
                */

                if (
                    row.Shift === 'Malam' &&
                    end <= start
                ) {

                    end.setDate(
                        end.getDate() + 1
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | WARNA
                |--------------------------------------------------------------------------
                */

                var color =
                    '#ffc107';


                if (row.Shift === 'Siang') {

                    color =
                        '#ff9800';

                }


                if (row.Shift === 'Malam') {

                    color =
                        '#795548';

                }


                /*
                |--------------------------------------------------------------------------
                | GANTT POINT
                |--------------------------------------------------------------------------
                */

                ganttData.push({

                    id:
                        row.NIK +
                        '-' +
                        row.tanggal +
                        '-' +
                        row.Shift +
                        '-' +
                        index,

                    name:
                        row.Shift,

                    start:
                        start.getTime(),

                    end:
                        end.getTime(),

                    y:
                        peopleMap[row.NIK],

                    color:
                        color,

                    custom: {

                        nik:
                            row.NIK,

                        nama:
                            $.trim(
                                row.Nama
                            ),

                        tanggal:
                            row.tanggal,

                        shift:
                            row.Shift,

                        checkIn:
                            row['Check In'],

                        checkOut:
                            row['Check Out'],

                        durasi:
                            row['Durasi (Jam)']

                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | CHART
            |--------------------------------------------------------------------------
            */

            Highcharts.ganttChart(
                'gantt_absensi_kmj',
                {

                    chart: {

                        height:
                            Math.max(
                                500,
                                people.length * 48 + 150
                            ),

                        spacingLeft: 10,

                        spacingRight: 20,

                        style: {

                            fontFamily:
                                'Arial, sans-serif'

                        }

                    },


                    title: {

                        text:
                            'Gantt Chart Absensi KMJ',

                        align:
                            'left',

                        margin:
                            8

                    },


                    subtitle: {

                        text:
                            start_date +
                            ' — ' +
                            end_date,

                        align:
                            'left'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | X AXIS
                    |--------------------------------------------------------------------------
                    */

                    xAxis: {

                        min:
                            minDate.getTime(),

                        max:
                            maxDate.getTime(),

                        type:
                            'datetime',

                        tickInterval:
                            8 * 60 * 60 * 1000,


                        labels: {

                            useHTML:
                                true,

                            formatter:
                                function () {

                                    var d =
                                        new Date(
                                            this.value
                                        );

                                    var hour =
                                        d.getHours();


                                    var shift =
                                        '';


                                    if (
                                        hour >= 7 &&
                                        hour < 15
                                    ) {

                                        shift =
                                            'Pagi';

                                    }
                                    else if (
                                        hour >= 15 &&
                                        hour < 23
                                    ) {

                                        shift =
                                            'Siang';

                                    }
                                    else {

                                        shift =
                                            'Malam';

                                    }


                                    return (

                                        '<div class="gantt-v4-axis">' +

                                            '<b>' +
                                                formatDate(d) +
                                            '</b>' +

                                            '<span>' +
                                                shift +
                                            '</span>' +

                                            '<small>' +
                                                formatTime(d) +
                                            '</small>' +

                                        '</div>'

                                    );

                                }

                        },


                        grid: {

                            enabled:
                                true,

                            cellWidth:
                                100

                        },


                        plotBands: [

                            /*
                            | Bisa diperpanjang otomatis
                            | berdasarkan range chart.
                            */

                        ]

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Y AXIS
                    |--------------------------------------------------------------------------
                    */

                    yAxis: {

                        type:
                            'category',

                        categories:
                            people.map(
                                function (person) {
                                    return person.Nama;
                                }
                            ),

                        reversed:
                            true,

                        title: {

                            text:
                                'Nama'

                        },

                        labels: {

                            useHTML:
                                true,

                            formatter:
                                function () {

                                    var person =
                                        people[
                                            this.pos
                                        ];

                                    return (

                                        '<div class="gantt-v4-person">' +

                                            '<span class="gantt-v4-avatar">' +
                                                person.Nama
                                                    .charAt(0)
                                                    .toUpperCase() +
                                            '</span>' +

                                            '<span>' +
                                                person.Nama +
                                            '</span>' +

                                        '</div>'

                                    );

                                }

                        }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | TOOLTIP
                    |--------------------------------------------------------------------------
                    */

                    tooltip: {

                        useHTML:
                            true,

                        formatter:
                            function () {

                                var c =
                                    this.point.custom;


                                return (

                                    '<div class="gantt-v4-tooltip">' +

                                        '<div class="gantt-v4-tooltip-name">' +
                                            c.nama +
                                        '</div>' +

                                        '<div class="gantt-v4-tooltip-row">' +
                                            '<span>NIK</span>' +
                                            '<b>' +
                                                c.nik +
                                            '</b>' +
                                        '</div>' +

                                        '<div class="gantt-v4-tooltip-row">' +
                                            '<span>Tanggal</span>' +
                                            '<b>' +
                                                c.tanggal +
                                            '</b>' +
                                        '</div>' +

                                        '<div class="gantt-v4-tooltip-row">' +
                                            '<span>Shift</span>' +
                                            '<b>' +
                                                c.shift +
                                            '</b>' +
                                        '</div>' +

                                        '<div class="gantt-v4-tooltip-row">' +
                                            '<span>Check In</span>' +
                                            '<b>' +
                                                c.checkIn +
                                            '</b>' +
                                        '</div>' +

                                        '<div class="gantt-v4-tooltip-row">' +
                                            '<span>Check Out</span>' +
                                            '<b>' +
                                                c.checkOut +
                                            '</b>' +
                                        '</div>' +

                                        '<div class="gantt-v4-tooltip-duration">' +
                                            c.durasi +
                                            ' jam' +
                                        '</div>' +

                                    '</div>'

                                );

                            }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | NAVIGATOR
                    |--------------------------------------------------------------------------
                    */

                    navigator: {

                        enabled:
                            true,

                        liveRedraw:
                            true,

                        height:
                            35

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | SCROLLBAR
                    |--------------------------------------------------------------------------
                    */

                    scrollbar: {

                        enabled:
                            true

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | RANGE SELECTOR
                    |--------------------------------------------------------------------------
                    */

                    rangeSelector: {

                        enabled:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | LEGEND
                    |--------------------------------------------------------------------------
                    */

                    legend: {

                        enabled:
                            false

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | SERIES
                    |--------------------------------------------------------------------------
                    */

                    series: [

                        {

                            name:
                                'Absensi',

                            data:
                                ganttData,

                            dataLabels: {

                                enabled:
                                    true,

                                format:
                                    '{point.name}',

                                style: {

                                    fontSize:
                                        '9px',

                                    fontWeight:
                                        '600',

                                    textOutline:
                                        'none'

                                }

                            }

                        }

                    ]

                }

            );

        },


        error: function (xhr) {

            console.log(
                'Gantt V4 Error:',
                xhr.responseText
            );

            $('#gantt_absensi_kmj').html(

                '<div class="alert alert-danger">' +
                    'Gagal mengambil data Gantt V4.' +
                '</div>'

            );

        }

    });

}

window.generateGanttAbsensiV5 = function(start_date, end_date) {

    var $container = $('#gantt_absensi_kmj');

    if (!$container.length) {

        console.error(
            'GANTT V5: #gantt_absensi_kmj tidak ditemukan.'
        );

        return;

    }


    $container.empty();


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */

    function pad(value) {

        return String(value).padStart(2, '0');

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(
            value == null
                ? ''
                : value
        )
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');

    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZE DATE
    |--------------------------------------------------------------------------
    */

    function normalizeDate(value) {

        if (!value) {

            return null;

        }


        value = String(value).trim();


        /*
        |--------------------------------------------------------------------------
        | YYYY-MM-DD
        |--------------------------------------------------------------------------
        */

        var m = value.match(
            /^(\d{4})-(\d{1,2})-(\d{1,2})/
        );


        if (m) {

            return (
                m[1] +
                '-' +
                pad(m[2]) +
                '-' +
                pad(m[3])
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DD-MM-YYYY
        |--------------------------------------------------------------------------
        */

        m = value.match(
            /^(\d{1,2})-(\d{1,2})-(\d{4})/
        );


        if (m) {

            return (
                m[3] +
                '-' +
                pad(m[2]) +
                '-' +
                pad(m[1])
            );

        }


        /*
        |--------------------------------------------------------------------------
        | DD/MM/YYYY
        |--------------------------------------------------------------------------
        */

        m = value.match(
            /^(\d{1,2})\/(\d{1,2})\/(\d{4})/
        );


        if (m) {

            return (
                m[3] +
                '-' +
                pad(m[2]) +
                '-' +
                pad(m[1])
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        var d = new Date(value);


        if (!isNaN(d.getTime())) {

            return (
                d.getFullYear() +
                '-' +
                pad(d.getMonth() + 1) +
                '-' +
                pad(d.getDate())
            );

        }


        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | MAKE DATE
    |--------------------------------------------------------------------------
    */

    function makeDate(dateString, hour) {

        var p = dateString.split('-');


        return new Date(

            parseInt(
                p[0],
                10
            ),

            parseInt(
                p[1],
                10
            ) - 1,

            parseInt(
                p[2],
                10
            ),

            hour || 0,

            0,

            0,

            0

        );

    }


    /*
    |--------------------------------------------------------------------------
    | PARSE DATETIME
    |--------------------------------------------------------------------------
    */

    function parseDate(value) {

        if (!value) {

            return null;

        }


        value = String(value).trim();


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


        /*
        |--------------------------------------------------------------------------
        | FORMAT
        | 06 Sep 2026 07:00
        |--------------------------------------------------------------------------
        */

        var p = value.split(/\s+/);


        if (p.length >= 4) {

            var day =
                parseInt(
                    p[0],
                    10
                );


            var month =
                monthMap[p[1]];


            var year =
                parseInt(
                    p[2],
                    10
                );


            var time =
                p[3].split(':');


            if (
                !isNaN(day) &&
                month !== undefined &&
                !isNaN(year)
            ) {

                return new Date(

                    year,

                    month,

                    day,

                    parseInt(
                        time[0],
                        10
                    ) || 0,

                    parseInt(
                        time[1],
                        10
                    ) || 0,

                    0,

                    0

                );

            }

        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        var d = new Date(value);


        if (!isNaN(d.getTime())) {

            return d;

        }


        return null;

    }


    /*
    |--------------------------------------------------------------------------
    | FORMAT DATE
    |--------------------------------------------------------------------------
    */

    function formatDate(date) {

        return (

            date.getFullYear() +
            '-' +
            pad(
                date.getMonth() + 1
            ) +
            '-' +
            pad(
                date.getDate()
            )

        );

    }


    /*
    |--------------------------------------------------------------------------
    | FILTER DATE
    |--------------------------------------------------------------------------
    */

    var filterStart =
        normalizeDate(start_date);


    var filterEnd =
        normalizeDate(end_date);


    if (
        !filterStart ||
        !filterEnd
    ) {

        $container.html(

            '<div class="alert alert-danger">' +

                'Tanggal filter tidak valid.' +

            '</div>'

        );

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | AJAX
    |--------------------------------------------------------------------------
    */

    $.ajax({

        url:
            "../../models/lap_absensi_kmj/lap_absensi_kmj.php",

        type:
            "POST",

        dataType:
            "json",

        data: {

            start_date:
                start_date,

            end_date:
                end_date,

            id_hemxxmh:
                0

        },


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        success:
            function(json) {


                /*
                |--------------------------------------------------------------------------
                | GET DATA
                |--------------------------------------------------------------------------
                */

                var rows = [];


                if (
                    json &&
                    json.data &&
                    json.data.htsprrd
                ) {

                    rows =
                        json.data.htsprrd;

                }


                console.log(
                    'GANTT V5 RAW:',
                    rows
                );


                /*
                |--------------------------------------------------------------------------
                | FILTER DATA
                |--------------------------------------------------------------------------
                */

                rows =
                    rows.filter(
                        function(row) {


                            /*
                            |--------------------------------------------------------------------------
                            | CHECK IN / OUT
                            |--------------------------------------------------------------------------
                            */

                            if (
                                !row['Check In'] ||
                                !row['Check Out']
                            ) {

                                return false;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | OFF DIABAIKAN
                            |--------------------------------------------------------------------------
                            */

                            if (
                                row.Shift === 'OFF'
                            ) {

                                return false;

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | TANGGAL
                            |--------------------------------------------------------------------------
                            */

                            var tanggal =
                                normalizeDate(
                                    row.tanggal
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | FALLBACK CHECK IN
                            |--------------------------------------------------------------------------
                            */

                            if (!tanggal) {

                                var checkIn =
                                    parseDate(
                                        row['Check In']
                                    );


                                if (!checkIn) {

                                    return false;

                                }


                                /*
                                |--------------------------------------------------------------------------
                                | BUSINESS DATE 07:00
                                |--------------------------------------------------------------------------
                                */

                                if (
                                    checkIn.getHours() < 7
                                ) {

                                    checkIn.setDate(

                                        checkIn.getDate() - 1

                                    );

                                }


                                tanggal =
                                    formatDate(
                                        checkIn
                                    );

                            }


                            /*
                            |--------------------------------------------------------------------------
                            | STRICT FILTER
                            |--------------------------------------------------------------------------
                            */

                            return (

                                tanggal >=
                                    filterStart &&

                                tanggal <=
                                    filterEnd

                            );

                        }
                    );


                console.log(
                    'GANTT V5 FILTERED:',
                    rows
                );


                /*
                |--------------------------------------------------------------------------
                | NO DATA
                |--------------------------------------------------------------------------
                */

                if (
                    rows.length === 0
                ) {

                    $container.html(

                        '<div class="alert alert-warning">' +

                            'Tidak ada data absensi pada tanggal yang dipilih.' +

                        '</div>'

                    );

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | TIMELINE
                |--------------------------------------------------------------------------
                */

                var timelineStart =
                    makeDate(
                        filterStart,
                        7
                    );


                var timelineEnd =
                    makeDate(
                        filterEnd,
                        7
                    );


                timelineEnd.setDate(

                    timelineEnd.getDate() + 1

                );


                /*
                |--------------------------------------------------------------------------
                | PEOPLE
                |--------------------------------------------------------------------------
                */

                var people = [];

                var peopleMap = {};


                $.each(

                    rows,

                    function(
                        index,
                        row
                    ) {

                        var nik =
                            row.NIK;


                        if (
                            nik &&
                            peopleMap[nik] === undefined
                        ) {

                            peopleMap[nik] =
                                people.length;


                            people.push({

                                NIK:
                                    nik,

                                Nama:
                                    $.trim(
                                        row.Nama ||
                                        '-'
                                    )

                            });

                        }

                    }

                );


                /*
                |--------------------------------------------------------------------------
                | SORT NAMA
                |--------------------------------------------------------------------------
                */

                people.sort(

                    function(
                        a,
                        b
                    ) {

                        return (

                            a.Nama.localeCompare(
                                b.Nama
                            )

                        );

                    }

                );


                /*
                |--------------------------------------------------------------------------
                | REBUILD MAP
                |--------------------------------------------------------------------------
                */

                peopleMap = {};


                $.each(

                    people,

                    function(
                        index,
                        person
                    ) {

                        peopleMap[
                            person.NIK
                        ] = index;

                    }

                );


                /*
                |--------------------------------------------------------------------------
                | GANTT DATA
                |--------------------------------------------------------------------------
                */

                var ganttData = [];


                $.each(

                    rows,

                    function(
                        index,
                        row
                    ) {

                        var start =
                            parseDate(
                                row['Check In']
                            );


                        var end =
                            parseDate(
                                row['Check Out']
                            );


                        if (
                            !start ||
                            !end
                        ) {

                            return;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SHIFT MALAM
                        |--------------------------------------------------------------------------
                        */

                        if (

                            row.Shift === 'Malam' &&

                            end <= start

                        ) {

                            end.setDate(

                                end.getDate() + 1

                            );

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | WARNA
                        |--------------------------------------------------------------------------
                        */

                        var color =
                            '#ffc107';


                        if (
                            row.Shift === 'Siang'
                        ) {

                            color =
                                '#ff9800';

                        }


                        if (
                            row.Shift === 'Malam'
                        ) {

                            color =
                                '#795548';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | DATA POINT
                        |--------------------------------------------------------------------------
                        */

                        ganttData.push({

                            id:

                                'gantt-' +
                                row.NIK +
                                '-' +
                                index,


                            name:

                                row.Shift,


                            start:

                                start.getTime(),


                            end:

                                end.getTime(),


                            y:

                                peopleMap[
                                    row.NIK
                                ],


                            color:

                                color,


                            custom: {

                                nik:
                                    row.NIK,

                                nama:
                                    $.trim(
                                        row.Nama ||
                                        '-'
                                    ),

                                tanggal:
                                    row.tanggal,

                                shift:
                                    row.Shift,

                                checkIn:
                                    row['Check In'],

                                checkOut:
                                    row['Check Out'],

                                durasi:
                                    row['Durasi (Jam)']

                            }

                        });

                    }

                );


                /*
                |--------------------------------------------------------------------------
                | TOTAL DAYS
                |--------------------------------------------------------------------------
                */

                var totalDays =

                    Math.round(

                        (

                            timelineEnd.getTime() -

                            timelineStart.getTime()

                        ) /

                        86400000

                    );


                /*
                |--------------------------------------------------------------------------
                | CHART WIDTH
                |--------------------------------------------------------------------------
                */

                var chartWidth =

                    Math.max(

                        1400,

                        totalDays * 620

                    );


                /*
                |--------------------------------------------------------------------------
                | CHART HEIGHT
                |--------------------------------------------------------------------------
                */

                var chartHeight =

                    Math.max(

                        500,

                        people.length * 42 + 150

                    );


                /*
                |--------------------------------------------------------------------------
                | CREATE SCROLL CONTENT
                |--------------------------------------------------------------------------
                */

                $container.html(

                    '<div ' +

                        'class="gantt-v5-scroll-content" ' +

                        'style="width:' +
                            chartWidth +
                        'px;">' +

                        '<div ' +

                            'id="gantt_absensi_kmj_chart" ' +

                            'style="width:' +
                                chartWidth +
                            'px;">' +

                        '</div>' +

                    '</div>'

                );


                /*
                |--------------------------------------------------------------------------
                | HIGHCHART GANTT
                |--------------------------------------------------------------------------
                */

                Highcharts.ganttChart(

                    'gantt_absensi_kmj_chart',

                    {

                        /*
                        |--------------------------------------------------------------------------
                        | CHART
                        |--------------------------------------------------------------------------
                        */

                        chart: {

                            width:
                                chartWidth,

                            height:
                                chartHeight,

                            backgroundColor:
                                '#181b20',

                            spacingTop:
                                70,

                            spacingRight:
                                20,

                            spacingBottom:
                                20,

                            spacingLeft:
                                10,

                            style: {

                                fontFamily:
                                    'Arial, sans-serif'

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | TITLE
                        |--------------------------------------------------------------------------
                        */

                        title: {

                            text:
                                'Gantt Chart Absensi KMJ',

                            align:
                                'left',

                            x:
                                10,

                            y:
                                20,

                            style: {

                                color:
                                    '#f1f3f5',

                                fontSize:
                                    '16px',

                                fontWeight:
                                    '700'

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | SUBTITLE
                        |--------------------------------------------------------------------------
                        */

                        subtitle: {

                            text:

                                filterStart +
                                ' — ' +
                                filterEnd,

                            align:
                                'left',

                            x:
                                10,

                            y:
                                42,

                            style: {

                                color:
                                    '#858c96',

                                fontSize:
                                    '11px'

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | X AXIS
                        |--------------------------------------------------------------------------
                        */

                        xAxis: {

                            type:
                                'datetime',

                            min:
                                timelineStart.getTime(),

                            max:
                                timelineEnd.getTime(),

                            tickInterval:

                                6 *
                                60 *
                                60 *
                                1000,

                            opposite:
                                true,

                            startOnTick:
                                false,

                            endOnTick:
                                false,


                            /*
                            |--------------------------------------------------------------------------
                            | GRID
                            |--------------------------------------------------------------------------
                            */

                            grid: {

                                enabled:
                                    true,

                                cellWidth:
                                    100

                            },


                            /*
                            |--------------------------------------------------------------------------
                            | DATE HEADER
                            |--------------------------------------------------------------------------
                            |
                            | HANYA HEADER ATAS.
                            |
                            | TIDAK ADA LAGI LABEL:
                            |
                            | Minggu, 6 September 2026
                            |
                            | di dalam area row.
                            |
                            |--------------------------------------------------------------------------
                            */

                            plotBands:

                                (function() {

                                    var bands = [];

                                    var current =
                                        new Date(
                                            timelineStart
                                        );


                                    while (

                                        current <
                                        timelineEnd

                                    ) {

                                        var bandStart =
                                            new Date(
                                                current
                                            );


                                        var bandEnd =
                                            new Date(
                                                current
                                            );


                                        bandEnd.setDate(

                                            bandEnd.getDate() + 1

                                        );


                                        if (
                                            bandEnd >
                                            timelineEnd
                                        ) {

                                            bandEnd =
                                                new Date(
                                                    timelineEnd
                                                );

                                        }


                                        /*
                                        |--------------------------------------------------------------------------
                                        | HEADER TANGGAL
                                        |--------------------------------------------------------------------------
                                        */

                                        var dateText =

                                            bandStart.toLocaleDateString(

                                                'en-US',

                                                {

                                                    weekday:
                                                        'long',

                                                    month:
                                                        'long',

                                                    day:
                                                        'numeric'

                                                }

                                            );


                                        /*
                                        |--------------------------------------------------------------------------
                                        | BAND
                                        |--------------------------------------------------------------------------
                                        |
                                        | PENTING:
                                        |
                                        | Tidak ada label di sini.
                                        |
                                        | Header tanggal ditampilkan
                                        | oleh axis label bagian atas.
                                        |
                                        |--------------------------------------------------------------------------
                                        */

                                        bands.push({

                                            from:
                                                bandStart.getTime(),

                                            to:
                                                bandEnd.getTime(),

                                            color:
                                                'rgba(0,0,0,0)',

                                            borderColor:
                                                '#444a52',

                                            borderWidth:
                                                1,

                                            zIndex:
                                                0

                                        });


                                        current =
                                            bandEnd;

                                    }


                                    return bands;

                                })(),


                            /*
                            |--------------------------------------------------------------------------
                            | TIME LABEL
                            |--------------------------------------------------------------------------
                            */

                            labels: {

                                useHTML:
                                    true,

                                y:
                                    25,

                                formatter:
                                    function() {

                                        var d =
                                            new Date(
                                                this.value
                                            );


                                        return (

                                            '<div class="gantt-v5-time">' +

                                                '<b>' +

                                                    pad(
                                                        d.getHours()
                                                    ) +

                                                    ':' +

                                                    pad(
                                                        d.getMinutes()
                                                    ) +

                                                '</b>' +

                                            '</div>'

                                        );

                                    }

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | Y AXIS
                        |--------------------------------------------------------------------------
                        */

                        yAxis: {

                            type:
                                'category',

                            categories:

                                people.map(

                                    function(person) {

                                        return person.Nama;

                                    }

                                ),


                            reversed:
                                true,


                            title: {

                                text:
                                    'Nama',

                                style: {

                                    color:
                                        '#8f97a2',

                                    fontSize:
                                        '11px'

                                }

                            },


                            grid: {

                                enabled:
                                    true

                            },


                            labels: {

                                useHTML:
                                    true,

                                formatter:
                                    function() {

                                        var person =

                                            people[
                                                this.pos
                                            ];


                                        if (!person) {

                                            return '';

                                        }


                                        var initial =

                                            person.Nama
                                                .charAt(0)
                                                .toUpperCase();


                                        return (

                                            '<div class="gantt-v5-person">' +

                                                '<span class="gantt-v5-avatar">' +

                                                    escapeHtml(
                                                        initial
                                                    ) +

                                                '</span>' +

                                                '<span class="gantt-v5-name">' +

                                                    escapeHtml(
                                                        person.Nama
                                                    ) +

                                                '</span>' +

                                            '</div>'

                                        );

                                    }

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | TOOLTIP
                        |--------------------------------------------------------------------------
                        */

                        tooltip: {

                            useHTML:
                                true,

                            backgroundColor:
                                '#ffffff',

                            borderColor:
                                '#dfe3e8',

                            borderRadius:
                                6,

                            shadow:
                                true,


                            formatter:
                                function() {

                                    var c =
                                        this.point.custom;


                                    return (

                                        '<div class="gantt-v5-tooltip">' +

                                            '<div class="gantt-v5-tooltip-name">' +

                                                escapeHtml(
                                                    c.nama
                                                ) +

                                            '</div>' +


                                            '<div class="gantt-v5-tooltip-row">' +

                                                '<span>NIK</span>' +

                                                '<b>' +

                                                    escapeHtml(
                                                        c.nik
                                                    ) +

                                                '</b>' +

                                            '</div>' +


                                            '<div class="gantt-v5-tooltip-row">' +

                                                '<span>Tanggal</span>' +

                                                '<b>' +

                                                    escapeHtml(
                                                        c.tanggal
                                                    ) +

                                                '</b>' +

                                            '</div>' +


                                            '<div class="gantt-v5-tooltip-row">' +

                                                '<span>Shift</span>' +

                                                '<b>' +

                                                    escapeHtml(
                                                        c.shift
                                                    ) +

                                                '</b>' +

                                            '</div>' +


                                            '<div class="gantt-v5-tooltip-row">' +

                                                '<span>Check In</span>' +

                                                '<b>' +

                                                    escapeHtml(
                                                        c.checkIn
                                                    ) +

                                                '</b>' +

                                            '</div>' +


                                            '<div class="gantt-v5-tooltip-row">' +

                                                '<span>Check Out</span>' +

                                                '<b>' +

                                                    escapeHtml(
                                                        c.checkOut
                                                    ) +

                                                '</b>' +

                                            '</div>' +


                                            '<div class="gantt-v5-tooltip-duration">' +

                                                escapeHtml(
                                                    c.durasi
                                                ) +

                                                ' jam' +

                                            '</div>' +

                                        '</div>'

                                    );

                                }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | NAVIGATOR
                        |--------------------------------------------------------------------------
                        */

                        navigator: {

                            enabled:
                                false

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | SCROLLBAR
                        |--------------------------------------------------------------------------
                        */

                        scrollbar: {

                            enabled:
                                false

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | RANGE SELECTOR
                        |--------------------------------------------------------------------------
                        */

                        rangeSelector: {

                            enabled:
                                false

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | LEGEND
                        |--------------------------------------------------------------------------
                        */

                        legend: {

                            enabled:
                                false

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | CREDITS
                        |--------------------------------------------------------------------------
                        */

                        credits: {

                            enabled:
                                false

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | SERIES
                        |--------------------------------------------------------------------------
                        */

                        series: [

                            {

                                name:
                                    'Absensi',

                                data:
                                    ganttData,


                                dataLabels: {

                                    enabled:
                                        true,

                                    format:
                                        '{point.name}',

                                    style: {

                                        fontSize:
                                            '9px',

                                        fontWeight:
                                            '700',

                                        color:
                                            '#111827',

                                        textOutline:
                                            'none'

                                    }

                                }

                            }

                        ]

                    }

                );

            },


        /*
        |--------------------------------------------------------------------------
        | ERROR
        |--------------------------------------------------------------------------
        */

        error:
            function(
                xhr,
                status,
                error
            ) {

                console.error(
                    'GANTT V5 AJAX ERROR:',
                    status,
                    error,
                    xhr.responseText
                );


                $container.html(

                    '<div class="alert alert-danger">' +

                        'Gagal mengambil data Gantt.' +

                    '</div>'

                );

            }

    });

};

// window.generateGanttAbsensiV5 = function(start_date, end_date) {

//     var $container = $('#gantt_absensi_kmj');

//     if (!$container.length) {
//         console.error('GANTT V5: #gantt_absensi_kmj tidak ditemukan.');
//         return;
//     }

//     $container.empty();

//     /*
//     |--------------------------------------------------------------------------
//     | HELPER
//     |--------------------------------------------------------------------------
//     */

//     function pad(value) {

//         return String(value).padStart(2, '0');

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | ESCAPE HTML
//     |--------------------------------------------------------------------------
//     */

//     function escapeHtml(value) {

//         return String(
//             value == null ? '' : value
//         )
//         .replace(/&/g, '&amp;')
//         .replace(/</g, '&lt;')
//         .replace(/>/g, '&gt;')
//         .replace(/"/g, '&quot;')
//         .replace(/'/g, '&#039;');

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | NORMALIZE DATE
//     |--------------------------------------------------------------------------
//     */

//     function normalizeDate(value) {

//         if (!value) {
//             return null;
//         }

//         value = String(value).trim();


//         /*
//         |--------------------------------------------------------------------------
//         | YYYY-MM-DD
//         |--------------------------------------------------------------------------
//         */

//         var m = value.match(
//             /^(\d{4})-(\d{1,2})-(\d{1,2})/
//         );

//         if (m) {

//             return (
//                 m[1] +
//                 '-' +
//                 pad(m[2]) +
//                 '-' +
//                 pad(m[3])
//             );

//         }


//         /*
//         |--------------------------------------------------------------------------
//         | DD-MM-YYYY
//         |--------------------------------------------------------------------------
//         */

//         m = value.match(
//             /^(\d{1,2})-(\d{1,2})-(\d{4})/
//         );

//         if (m) {

//             return (
//                 m[3] +
//                 '-' +
//                 pad(m[2]) +
//                 '-' +
//                 pad(m[1])
//             );

//         }


//         /*
//         |--------------------------------------------------------------------------
//         | DD/MM/YYYY
//         |--------------------------------------------------------------------------
//         */

//         m = value.match(
//             /^(\d{1,2})\/(\d{1,2})\/(\d{4})/
//         );

//         if (m) {

//             return (
//                 m[3] +
//                 '-' +
//                 pad(m[2]) +
//                 '-' +
//                 pad(m[1])
//             );

//         }


//         /*
//         |--------------------------------------------------------------------------
//         | FALLBACK
//         |--------------------------------------------------------------------------
//         */

//         var d = new Date(value);

//         if (!isNaN(d.getTime())) {

//             return (
//                 d.getFullYear() +
//                 '-' +
//                 pad(d.getMonth() + 1) +
//                 '-' +
//                 pad(d.getDate())
//             );

//         }

//         return null;

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | DATE OBJECT
//     |--------------------------------------------------------------------------
//     */

//     function makeDate(dateString, hour) {

//         var p = dateString.split('-');

//         return new Date(
//             parseInt(p[0], 10),
//             parseInt(p[1], 10) - 1,
//             parseInt(p[2], 10),
//             hour || 0,
//             0,
//             0,
//             0
//         );

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | PARSE DATETIME
//     |--------------------------------------------------------------------------
//     */

//     function parseDate(value) {

//         if (!value) {
//             return null;
//         }

//         value = String(value).trim();

//         var monthMap = {
//             Jan: 0,
//             Feb: 1,
//             Mar: 2,
//             Apr: 3,
//             May: 4,
//             Jun: 5,
//             Jul: 6,
//             Aug: 7,
//             Sep: 8,
//             Oct: 9,
//             Nov: 10,
//             Dec: 11
//         };


//         /*
//         |--------------------------------------------------------------------------
//         | FORMAT:
//         | 06 Sep 2026 07:00
//         |--------------------------------------------------------------------------
//         */

//         var p = value.split(/\s+/);

//         if (p.length >= 4) {

//             var day = parseInt(p[0], 10);

//             var month = monthMap[p[1]];

//             var year = parseInt(p[2], 10);

//             var time = p[3].split(':');

//             if (
//                 !isNaN(day) &&
//                 month !== undefined &&
//                 !isNaN(year)
//             ) {

//                 return new Date(
//                     year,
//                     month,
//                     day,
//                     parseInt(time[0], 10) || 0,
//                     parseInt(time[1], 10) || 0,
//                     0,
//                     0
//                 );

//             }

//         }


//         /*
//         |--------------------------------------------------------------------------
//         | FALLBACK
//         |--------------------------------------------------------------------------
//         */

//         var d = new Date(value);

//         if (!isNaN(d.getTime())) {
//             return d;
//         }

//         return null;

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | FORMAT DATE
//     |--------------------------------------------------------------------------
//     */

//     function formatDate(date) {

//         return (
//             date.getFullYear() +
//             '-' +
//             pad(date.getMonth() + 1) +
//             '-' +
//             pad(date.getDate())
//         );

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | FILTER DATE
//     |--------------------------------------------------------------------------
//     */

//     var filterStart = normalizeDate(start_date);

//     var filterEnd = normalizeDate(end_date);

//     if (!filterStart || !filterEnd) {

//         $container.html(
//             '<div class="alert alert-danger">' +
//                 'Tanggal filter tidak valid.' +
//             '</div>'
//         );

//         return;

//     }


//     /*
//     |--------------------------------------------------------------------------
//     | AJAX
//     |--------------------------------------------------------------------------
//     */

//     $.ajax({

//         url: "../../models/lap_absensi_kmj/lap_absensi_kmj.php",

//         type: "POST",

//         dataType: "json",

//         data: {

//             start_date: start_date,

//             end_date: end_date,

//             id_hemxxmh: 0

//         },


//         /*
//         |--------------------------------------------------------------------------
//         | SUCCESS
//         |--------------------------------------------------------------------------
//         */

//         success: function(json) {

//             /*
//             |--------------------------------------------------------------------------
//             | GET DATA
//             |--------------------------------------------------------------------------
//             */

//             var rows = [];

//             if (
//                 json &&
//                 json.data &&
//                 json.data.htsprrd
//             ) {

//                 rows = json.data.htsprrd;

//             }

//             console.log(
//                 'GANTT V5 RAW:',
//                 rows
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | FILTER DATA
//             |--------------------------------------------------------------------------
//             |
//             | Hanya ambil absensi yang tanggal bisnisnya
//             | masuk ke filter.
//             |
//             |--------------------------------------------------------------------------
//             */

//             rows = rows.filter(function(row) {

//                 /*
//                 |--------------------------------------------------------------------------
//                 | HARUS PUNYA CHECK IN / OUT
//                 |--------------------------------------------------------------------------
//                 */

//                 if (
//                     !row['Check In'] ||
//                     !row['Check Out']
//                 ) {

//                     return false;

//                 }


//                 /*
//                 |--------------------------------------------------------------------------
//                 | OFF DIABAIKAN
//                 |--------------------------------------------------------------------------
//                 */

//                 if (row.Shift === 'OFF') {

//                     return false;

//                 }


//                 /*
//                 |--------------------------------------------------------------------------
//                 | AMBIL TANGGAL DARI row.tanggal
//                 |--------------------------------------------------------------------------
//                 */

//                 var tanggal = normalizeDate(
//                     row.tanggal
//                 );


//                 /*
//                 |--------------------------------------------------------------------------
//                 | FALLBACK KE CHECK IN
//                 |--------------------------------------------------------------------------
//                 */

//                 if (!tanggal) {

//                     var checkIn = parseDate(
//                         row['Check In']
//                     );

//                     if (!checkIn) {
//                         return false;
//                     }


//                     /*
//                     |--------------------------------------------------------------------------
//                     | BUSINESS DATE DIMULAI JAM 07:00
//                     |--------------------------------------------------------------------------
//                     */

//                     if (checkIn.getHours() < 7) {

//                         checkIn.setDate(
//                             checkIn.getDate() - 1
//                         );

//                     }

//                     tanggal = formatDate(checkIn);

//                 }


//                 /*
//                 |--------------------------------------------------------------------------
//                 | STRICT FILTER
//                 |--------------------------------------------------------------------------
//                 */

//                 return (
//                     tanggal >= filterStart &&
//                     tanggal <= filterEnd
//                 );

//             });


//             console.log(
//                 'GANTT V5 FILTERED:',
//                 rows
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | NO DATA
//             |--------------------------------------------------------------------------
//             */

//             if (rows.length === 0) {

//                 $container.html(

//                     '<div class="alert alert-warning">' +
//                         'Tidak ada data absensi pada tanggal yang dipilih.' +
//                     '</div>'

//                 );

//                 return;

//             }


//             /*
//             |--------------------------------------------------------------------------
//             | TIMELINE
//             |--------------------------------------------------------------------------
//             |
//             | Contoh filter:
//             |
//             | 06 Sep - 07 Sep
//             |
//             | Timeline:
//             |
//             | 06 Sep 07:00
//             | sampai
//             | 08 Sep 07:00
//             |
//             |--------------------------------------------------------------------------
//             */

//             var timelineStart = makeDate(
//                 filterStart,
//                 7
//             );

//             var timelineEnd = makeDate(
//                 filterEnd,
//                 7
//             );

//             timelineEnd.setDate(
//                 timelineEnd.getDate() + 1
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | PEOPLE
//             |--------------------------------------------------------------------------
//             */

//             var people = [];

//             var peopleMap = {};


//             $.each(
//                 rows,
//                 function(index, row) {

//                     var nik = row.NIK;

//                     if (
//                         nik &&
//                         peopleMap[nik] === undefined
//                     ) {

//                         peopleMap[nik] = people.length;

//                         people.push({

//                             NIK: nik,

//                             Nama: $.trim(
//                                 row.Nama || '-'
//                             )

//                         });

//                     }

//                 }
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | SORT NAMA
//             |--------------------------------------------------------------------------
//             */

//             people.sort(
//                 function(a, b) {

//                     return a.Nama.localeCompare(
//                         b.Nama
//                     );

//                 }
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | REBUILD MAP
//             |--------------------------------------------------------------------------
//             */

//             peopleMap = {};

//             $.each(
//                 people,
//                 function(index, person) {

//                     peopleMap[
//                         person.NIK
//                     ] = index;

//                 }
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | GANTT DATA
//             |--------------------------------------------------------------------------
//             */

//             var ganttData = [];


//             $.each(
//                 rows,
//                 function(index, row) {

//                     var start = parseDate(
//                         row['Check In']
//                     );

//                     var end = parseDate(
//                         row['Check Out']
//                     );


//                     if (!start || !end) {
//                         return;
//                     }


//                     /*
//                     |--------------------------------------------------------------------------
//                     | SHIFT MALAM
//                     |--------------------------------------------------------------------------
//                     */

//                     if (
//                         row.Shift === 'Malam' &&
//                         end <= start
//                     ) {

//                         end.setDate(
//                             end.getDate() + 1
//                         );

//                     }


//                     /*
//                     |--------------------------------------------------------------------------
//                     | WARNA
//                     |--------------------------------------------------------------------------
//                     */

//                     var color = '#ffc107';

//                     if (row.Shift === 'Siang') {

//                         color = '#ff9800';

//                     }

//                     if (row.Shift === 'Malam') {

//                         color = '#795548';

//                     }


//                     /*
//                     |--------------------------------------------------------------------------
//                     | DATA POINT
//                     |--------------------------------------------------------------------------
//                     */

//                     ganttData.push({

//                         id:
//                             'gantt-' +
//                             row.NIK +
//                             '-' +
//                             index,

//                         name:
//                             row.Shift,

//                         start:
//                             start.getTime(),

//                         end:
//                             end.getTime(),

//                         y:
//                             peopleMap[row.NIK],

//                         color:
//                             color,

//                         custom: {

//                             nik:
//                                 row.NIK,

//                             nama:
//                                 $.trim(
//                                     row.Nama || '-'
//                                 ),

//                             tanggal:
//                                 row.tanggal,

//                             shift:
//                                 row.Shift,

//                             checkIn:
//                                 row['Check In'],

//                             checkOut:
//                                 row['Check Out'],

//                             durasi:
//                                 row['Durasi (Jam)']

//                         }

//                     });

//                 }
//             );


//             console.log(
//                 'GANTT V5 SERIES:',
//                 ganttData
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | JUMLAH HARI
//             |--------------------------------------------------------------------------
//             */

//             var totalDays = Math.round(

//                 (
//                     timelineEnd.getTime() -
//                     timelineStart.getTime()
//                 ) / 86400000

//             );


//             /*
//             |--------------------------------------------------------------------------
//             | CHART WIDTH
//             |--------------------------------------------------------------------------
//             |
//             | Chart TIDAK mengecil.
//             |
//             | 1 hari  = 620px
//             | 7 hari  = 4340px
//             | 30 hari = 18600px
//             |
//             |--------------------------------------------------------------------------
//             */

//             var chartWidth = Math.max(
//                 1400,
//                 totalDays * 620
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | CHART HEIGHT
//             |--------------------------------------------------------------------------
//             */

//             var chartHeight = Math.max(
//                 500,
//                 people.length * 48 + 160
//             );


//             /*
//             |--------------------------------------------------------------------------
//             | CREATE SCROLL CONTENT
//             |--------------------------------------------------------------------------
//             */

//             $container.html(

//                 '<div ' +
//                     'class="gantt-v5-scroll-content" ' +
//                     'style="width:' +
//                         chartWidth +
//                     'px;">' +

//                     '<div ' +
//                         'id="gantt_absensi_kmj_chart" ' +
//                         'style="width:' +
//                             chartWidth +
//                         'px;">' +

//                     '</div>' +

//                 '</div>'

//             );


//             /*
//             |--------------------------------------------------------------------------
//             | HIGHCHART GANTT
//             |--------------------------------------------------------------------------
//             */

//             Highcharts.ganttChart(

//                 'gantt_absensi_kmj_chart',

//                 {

//                     /*
//                     |--------------------------------------------------------------------------
//                     | CHART
//                     |--------------------------------------------------------------------------
//                     */

//                     chart: {

//                         width:
//                             chartWidth,

//                         height:
//                             chartHeight,

//                         backgroundColor:
//                             '#ffffff',

//                         spacingTop:
//                             70,

//                         spacingRight:
//                             20,

//                         spacingBottom:
//                             20,

//                         spacingLeft:
//                             10,

//                         style: {

//                             fontFamily:
//                                 'Arial, sans-serif'

//                         }

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | TITLE
//                     |--------------------------------------------------------------------------
//                     */

//                     title: {

//                         text:
//                             'Gantt Chart Absensi KMJ',

//                         align:
//                             'left',

//                         x:
//                             10,

//                         y:
//                             20,

//                         style: {

//                             color:
//                                 '#222',

//                             fontSize:
//                                 '16px',

//                             fontWeight:
//                                 '700'

//                         }

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | SUBTITLE
//                     |--------------------------------------------------------------------------
//                     */

//                     subtitle: {

//                         text:
//                             filterStart +
//                             ' — ' +
//                             filterEnd,

//                         align:
//                             'left',

//                         x:
//                             10,

//                         y:
//                             42,

//                         style: {

//                             color:
//                                 '#777',

//                             fontSize:
//                                 '11px'

//                         }

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | X AXIS
//                     |--------------------------------------------------------------------------
//                     */

//                     xAxis: {

//                         type:
//                             'datetime',

//                         min:
//                             timelineStart.getTime(),

//                         max:
//                             timelineEnd.getTime(),

//                         tickInterval:
//                             6 *
//                             60 *
//                             60 *
//                             1000,

//                         opposite:
//                             true,

//                         startOnTick:
//                             false,

//                         endOnTick:
//                             false,

//                         grid: {

//                             enabled:
//                                 true,

//                             cellWidth:
//                                 100

//                         },


//                         /*
//                         |--------------------------------------------------------------------------
//                         | DATE BANDS
//                         |--------------------------------------------------------------------------
//                         */

//                         plotBands:
//                             (function() {

//                                 var bands = [];

//                                 var current =
//                                     new Date(
//                                         timelineStart
//                                     );


//                                 while (
//                                     current <
//                                     timelineEnd
//                                 ) {

//                                     var bandStart =
//                                         new Date(
//                                             current
//                                         );

//                                     var bandEnd =
//                                         new Date(
//                                             current
//                                         );

//                                     bandEnd.setDate(
//                                         bandEnd.getDate() + 1
//                                     );


//                                     if (
//                                         bandEnd >
//                                         timelineEnd
//                                     ) {

//                                         bandEnd =
//                                             new Date(
//                                                 timelineEnd
//                                             );

//                                     }


//                                     /*
//                                     |--------------------------------------------------------------------------
//                                     | FORMAT TANGGAL
//                                     |--------------------------------------------------------------------------
//                                     */

//                                     var dateText =
//                                         bandStart.toLocaleDateString(
//                                             'id-ID',
//                                             {

//                                                 weekday:
//                                                     'long',

//                                                 day:
//                                                     'numeric',

//                                                 month:
//                                                     'long',

//                                                 year:
//                                                     'numeric'

//                                             }
//                                         );


//                                     bands.push({

//                                         from:
//                                             bandStart.getTime(),

//                                         to:
//                                             bandEnd.getTime(),

//                                         color:
//                                             'rgba(255,255,255,0.8)',

//                                         borderColor:
//                                             '#d9d9d9',

//                                         borderWidth:
//                                             1,

//                                         zIndex:
//                                             0,

//                                         label: {

//                                             text:
//                                                 dateText,

//                                             align:
//                                                 'center',

//                                             verticalAlign:
//                                                 'top',

//                                             y:
//                                                 12,

//                                             style: {

//                                                 color:
//                                                     '#333',

//                                                 fontSize:
//                                                     '12px',

//                                                 fontWeight:
//                                                     '600'

//                                             }

//                                         }

//                                     });


//                                     current =
//                                         bandEnd;

//                                 }


//                                 return bands;

//                             })(),


//                         /*
//                         |--------------------------------------------------------------------------
//                         | JAM
//                         |--------------------------------------------------------------------------
//                         */

//                         labels: {

//                             useHTML:
//                                 true,

//                             y:
//                                 25,

//                             formatter:
//                                 function() {

//                                     var d =
//                                         new Date(
//                                             this.value
//                                         );

//                                     return (

//                                         '<div class="gantt-v5-time">' +

//                                             '<b>' +

//                                                 pad(
//                                                     d.getHours()
//                                                 ) +

//                                                 ':' +

//                                                 pad(
//                                                     d.getMinutes()
//                                                 ) +

//                                             '</b>' +

//                                         '</div>'

//                                     );

//                                 }

//                         }

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | Y AXIS
//                     |--------------------------------------------------------------------------
//                     */

//                     yAxis: {

//                         type:
//                             'category',

//                         categories:
//                             people.map(
//                                 function(person) {

//                                     return person.Nama;

//                                 }
//                             ),

//                         reversed:
//                             true,

//                         title: {

//                             text:
//                                 'Nama',

//                             style: {

//                                 color:
//                                     '#333',

//                                 fontSize:
//                                     '11px'

//                             }

//                         },

//                         grid: {

//                             enabled:
//                                 true

//                         },

//                         labels: {

//                             useHTML:
//                                 true,

//                             formatter:
//                                 function() {

//                                     var person =
//                                         people[
//                                             this.pos
//                                         ];


//                                     if (!person) {
//                                         return '';
//                                     }


//                                     var initial =
//                                         person.Nama
//                                             .charAt(0)
//                                             .toUpperCase();


//                                     return (

//                                         '<div class="gantt-v5-person">' +

//                                             '<span class="gantt-v5-avatar">' +

//                                                 escapeHtml(
//                                                     initial
//                                                 ) +

//                                             '</span>' +

//                                             '<span class="gantt-v5-name">' +

//                                                 escapeHtml(
//                                                     person.Nama
//                                                 ) +

//                                             '</span>' +

//                                         '</div>'

//                                     );

//                                 }

//                         }

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | TOOLTIP
//                     |--------------------------------------------------------------------------
//                     */

//                     tooltip: {

//                         useHTML:
//                             true,

//                         backgroundColor:
//                             '#fff',

//                         borderColor:
//                             '#ddd',

//                         borderRadius:
//                             5,

//                         shadow:
//                             true,

//                         formatter:
//                             function() {

//                                 var c =
//                                     this.point.custom;


//                                 return (

//                                     '<div class="gantt-v5-tooltip">' +

//                                         '<div class="gantt-v5-tooltip-name">' +

//                                             escapeHtml(
//                                                 c.nama
//                                             ) +

//                                         '</div>' +


//                                         '<div class="gantt-v5-tooltip-row">' +

//                                             '<span>NIK</span>' +

//                                             '<b>' +

//                                                 escapeHtml(
//                                                     c.nik
//                                                 ) +

//                                             '</b>' +

//                                         '</div>' +


//                                         '<div class="gantt-v5-tooltip-row">' +

//                                             '<span>Tanggal</span>' +

//                                             '<b>' +

//                                                 escapeHtml(
//                                                     c.tanggal
//                                                 ) +

//                                             '</b>' +

//                                         '</div>' +


//                                         '<div class="gantt-v5-tooltip-row">' +

//                                             '<span>Shift</span>' +

//                                             '<b>' +

//                                                 escapeHtml(
//                                                     c.shift
//                                                 ) +

//                                             '</b>' +

//                                         '</div>' +


//                                         '<div class="gantt-v5-tooltip-row">' +

//                                             '<span>Check In</span>' +

//                                             '<b>' +

//                                                 escapeHtml(
//                                                     c.checkIn
//                                                 ) +

//                                             '</b>' +

//                                         '</div>' +


//                                         '<div class="gantt-v5-tooltip-row">' +

//                                             '<span>Check Out</span>' +

//                                             '<b>' +

//                                                 escapeHtml(
//                                                     c.checkOut
//                                                 ) +

//                                             '</b>' +

//                                         '</div>' +


//                                         '<div class="gantt-v5-tooltip-duration">' +

//                                             escapeHtml(
//                                                 c.durasi
//                                             ) +

//                                             ' jam' +

//                                         '</div>' +

//                                     '</div>'

//                                 );

//                             }

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | NAVIGATOR
//                     |--------------------------------------------------------------------------
//                     */

//                     navigator: {

//                         enabled:
//                             false

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | HIGHCHART SCROLLBAR
//                     |--------------------------------------------------------------------------
//                     |
//                     | Dimatikan.
//                     | Scroll X menggunakan container luar.
//                     |
//                     |--------------------------------------------------------------------------
//                     */

//                     scrollbar: {

//                         enabled:
//                             false

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | RANGE SELECTOR
//                     |--------------------------------------------------------------------------
//                     */

//                     rangeSelector: {

//                         enabled:
//                             false

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | LEGEND
//                     |--------------------------------------------------------------------------
//                     */

//                     legend: {

//                         enabled:
//                             false

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | CREDITS
//                     |--------------------------------------------------------------------------
//                     */

//                     credits: {

//                         enabled:
//                             false

//                     },


//                     /*
//                     |--------------------------------------------------------------------------
//                     | SERIES
//                     |--------------------------------------------------------------------------
//                     */

//                     series: [

//                         {

//                             name:
//                                 'Absensi',

//                             data:
//                                 ganttData,

//                             dataLabels: {

//                                 enabled:
//                                     true,

//                                 format:
//                                     '{point.name}',

//                                 style: {

//                                     fontSize:
//                                         '9px',

//                                     fontWeight:
//                                         '600',

//                                     color:
//                                         '#111',

//                                     textOutline:
//                                         'none'

//                                 }

//                             }

//                         }

//                     ]

//                 }

//             );

//         },


//         /*
//         |--------------------------------------------------------------------------
//         | ERROR
//         |--------------------------------------------------------------------------
//         */

//         error: function(
//             xhr,
//             status,
//             error
//         ) {

//             console.error(
//                 'GANTT V5 AJAX ERROR:',
//                 status,
//                 error,
//                 xhr.responseText
//             );


//             $container.html(

//                 '<div class="alert alert-danger">' +
//                     'Gagal mengambil data Gantt.' +
//                 '</div>'

//             );

//         }

//     });

// };
</script>