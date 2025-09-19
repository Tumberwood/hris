<script>
    /**
     * Untuk load data-data dashboard
     */

    // BEGIN highchart generate options
    Highcharts.setOptions({
        chart: {
            height: 500
        },
        lang: {
            thousandsSep: ','
        },
        colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
        credits: {
			enabled: false
		},
        legend:{
            align: 'center',
            verticalAlign: 'bottom'
        }
    })
    
    function chartEmpIzin() {
        var izin = 0;
        var dept = 0;

        var optionschartEmpIzin = {
            chart: {
                type: 'column',
                events: {
                    drilldown: function (e) {
                        console.log("Drilldown ke:", e.point.name);
                        tableIzin(0, 0, start_date, end_date);
                    },
                    drillup: function (e) {
                        console.log("Kembali dari drilldown");
                        tableIzin(0, 0, start_date, end_date);
                    }
                }
            },
            title: {
                text: 'Karyawan Izin',
                align: 'left'
            },
            xAxis: {
                type: 'category',
                crosshair: true,
                accessibility: {
                    description: ''
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                valueSuffix: ' orang'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    point: {
                        events: {
                            click: function (e) {
                                if (this.series.chart.drilldownLevels.length > 0) {
                                    console.log("Total orang di", this.name, ":", this.y);

                                    izin = this.series.userOptions.id || this.series.userOptions.name;
                                    dept = e.point.name;

                                    console.log("Departemen:", dept, " | Izin:", izin);

                                    tableIzin(izin, dept, start_date, end_date);
                                }
                            }
                        }
                    }
                }
            },
            series: [],
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                }
            }
        };

        $.ajax({
            url: "../../models/dashboard/d_hr_presensi_empIzin.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                start_date: start_date,
                end_date: end_date
            },
            success: function (json) {
                optionschartEmpIzin.series[0] = json.data.results_emp_izin[1];

                // isi drilldown dengan data dari json
                optionschartEmpIzin.drilldown = json.data.results_emp_izin[2];

                Highcharts.chart('chartEmpIzin', optionschartEmpIzin);
            }
        });
    }

    function tableIzin(izin, dept, start_date, end_date) {
        $.ajax({
            url: "../../models/dashboard/d_hr_presensi_table_izin.php",
            dataType: 'json',
            type: 'POST',
            data: {
                izin: izin,
                dept: dept,
                start_date: start_date,
                end_date: end_date
            },
            success: function (json) {
                // kalau table sudah ada → reset dulu
                if ($.fn.dataTable.isDataTable('#tblhtlxxrh')) {
                    $('#tblhtlxxrh').DataTable().clear().destroy();
                    $('#tblhtlxxrh tbody').empty();
                }

                // build DataTable baru
                $('#tblhtlxxrh').DataTable({
                    data: json.data.result, // dari fn_ajax_results.php otomatis "data"
                    columns: [
                        { data: "id",visible: false },         // pastikan query SELECT punya id (kalau perlu tambahin a.id di PHP)
                        { data: "tanggal" },
                        { data: "kode" },
                        { data: "nama" },
                        { data: "departemen" },
                        { data: "jenis" },
                        { data: "jam_awal" },
                        { data: "jam_akhir" },
                        { data: "keterangan" },
                    ],
                    destroy: true,
                    responsive: false,
                    scrollX: true
                });
            }
        });
    }
    
    function chartEmpAbsen() {
        var absen = 0;
        var dept = 0;

        var optionschartEmpAbsen = {
            chart: {
                type: 'column',
                events: {
                    drilldown: function (e) {
                        console.log("Drilldown ke:", e.point.name);
                        tableAbsen(0, 0, start_date, end_date);
                    },
                    drillup: function (e) {
                        console.log("Kembali dari drilldown");
                        tableAbsen(0, 0, start_date, end_date);
                    }
                }
            },
            title: {
                text: 'Karyawan Absen',
                align: 'left'
            },
            xAxis: {
                type: 'category',
                crosshair: true,
                accessibility: {
                    description: ''
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah'
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                valueSuffix: ' orang'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series: {
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true
                    },
                    point: {
                        events: {
                            click: function (e) {
                                if (this.series.chart.drilldownLevels.length > 0) {
                                    console.log("Total orang di", this.name, ":", this.y);

                                    absen = this.series.userOptions.id || this.series.userOptions.name;
                                    dept = e.point.name;

                                    console.log("Departemen:", dept, " | Absen:", absen);

                                    tableAbsen(absen, dept, start_date, end_date);
                                }
                            }
                        }
                    }
                }
            },
            series: [],
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                }
            }
        };

        $.ajax({
            url: "../../models/dashboard/d_hr_presensi_empAbsen.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                start_date: start_date,
                end_date: end_date
            },
            success: function (json) {
                optionschartEmpAbsen.series[0] = json.data.results_emp_absen[1];

                // isi drilldown dengan data dari json
                optionschartEmpAbsen.drilldown = json.data.results_emp_absen[2];

                Highcharts.chart('chartEmpAbsen', optionschartEmpAbsen);
            }
        });
    }

    function tableAbsen(absen, dept, start_date, end_date) {
        $.ajax({
            url: "../../models/dashboard/d_hr_presensi_table_absen.php",
            dataType: 'json',
            type: 'POST',
            data: {
                absen: absen,
                dept: dept,
                start_date: start_date,
                end_date: end_date
            },
            success: function (json) {
                // kalau table sudah ada → reset dulu
                if ($.fn.dataTable.isDataTable('#tblhtlxxrh_absen')) {
                    $('#tblhtlxxrh_absen').DataTable().clear().destroy();
                    $('#tblhtlxxrh_absen tbody').empty();
                }

                // build DataTable baru
                $('#tblhtlxxrh_absen').DataTable({
                    data: json.data.result, // dari fn_ajax_results.php otomatis "data"
                    columns: [
                        { data: "id",visible: false },         // pastikan query SELECT punya id (kalau perlu tambahin a.id di PHP)
                        { data: "tanggal" },
                        { data: "kode" },
                        { data: "nama" },
                        { data: "departemen" },
                        { data: "jenis" },
                        { data: "jam_awal" },
                        { data: "jam_akhir" },
                        { data: "keterangan" },
                    ],
                    destroy: true,
                    responsive: false,
                    scrollX: true
                });
            }
        });
    }
</script>