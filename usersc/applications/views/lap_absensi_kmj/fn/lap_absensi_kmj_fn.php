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
</script>