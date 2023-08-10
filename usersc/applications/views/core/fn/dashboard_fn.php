<script>
    // BEGIN highchart generate options
    // Highcharts.setOptions({
    //     chart: {
    //         height: 500
    //     },
    //     lang: {
    //         thousandsSep: ','
    //     },
    //     colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
    //     credits: {
	// 		enabled: false
	// 	},
    //     legend:{
    //         align: 'center',
    //         verticalAlign: 'bottom'
    //     }
    // })
    // END highchart generate options

    function loadProfile (){
        $.ajax( {
            url: "../../models/core/d_dashboard_hemxxmh.php",
            dataType: 'json',
            type: 'POST',
            data: {
                // id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                console.log(json);
                hemxxmh_kode = json.data.hemxxmh['hemxxmh_kode'];
                hemxxmh_nama = json.data.hemxxmh['hemxxmh_nama']; 
                hodxxmh_nama = json.data.hemxxmh['hodxxmh_nama']; 
                hetxxmh_nama = json.data.hemxxmh['hetxxmh_nama']; 
                masakerja = json.data.hemxxmh['masakerja']; 
                // c_perempuan = json.data.hemxxmh_gender[1]['c_gender'];
                // c_total = c_laki  + c_perempuan;

                // p_laki = (c_laki / c_total * 100).toFixed(2);
                // p_perempuan = (c_perempuan / c_total * 100).toFixed(2) ;
                
                $("#hemxxmh_nama").html(hemxxmh_nama);
                $("#hodxxmh_nama").html(hodxxmh_nama);
                $("#hetxxmh_nama").html(hetxxmh_nama);
                $("#masakerja").html(masakerja);
                // $("#c_perempuan").html(c_perempuan);

                // $("#p_laki").html(p_laki + "%");
                // $("#p_perempuan").html(p_perempuan + "%");

            }
        } );
    };

    // function chartEmpType() {
    //     var optionschartEmpType = {
    //         chart: {
    //             type: "pie",
    //             height: 300
    //         },
    //         title: {
    //             text: 'Karyawan per Tipe',
    //             align: 'left'
    //         },
    //         // subtitle: {
    //         //     text:
    //         //         ''
    //         //     align: 'left'
    //         // },
    //         xAxis: {
    //             categories: [],
    //             crosshair: true,
    //             accessibility: {
    //                 description: ''
    //             }
    //         },
    //         yAxis: {
    //             min: 0,
    //             title: {
    //                 text: 'Jumlah'
    //             }
    //         },
    //         tooltip: {
    //             // valueSuffix: ' orang',
    //             pointFormat: '{series.name}: <br>{point.y:,.0f} <br>({point.percentage:.1f}%) '
    //         },
    //         plotOptions: {
    //             pie: {
    //                 size:'100%',
    //                 allowPointSelect: true,
    //                 cursor: true,
    //                 innerSize: "60%",
    //                 dataLabels: {
    //                     enabled: true,
    //                     style: {
    //                         fontWeight: "bold",
    //                         textOutline: "",
    //                         color: "black"
    //                     },
    //                     distance: -20
    //                 }
    //             }
    //         },
    //         series: [
    //             {
    //                 name: "Jumlah",
    //                 turboThreshold: 0
    //             }
    //         ]
    //     };

    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empType.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         async: false,
    //         data: {
                
    //         },
    //         success: function ( json ) {
    //             optionschartEmpType.series[0].data = json.data.d_hr_profile_empType;
	// 		    Highcharts.chart('chartEmpType', optionschartEmpType);
    //         }
    //     } );
        
    // }

    // function chartEmpStatus() {
    //     var optionschartEmpStatus = {
    //         chart: {
    //             type: "pie",
    //             height: 300
    //         },
    //         title: {
    //             text: 'Karyawan per Status',
    //             align: 'left'
    //         },
    //         // subtitle: {
    //         //     text:
    //         //         ''
    //         //     align: 'left'
    //         // },
    //         xAxis: {
    //             categories: [],
    //             crosshair: true,
    //             accessibility: {
    //                 description: ''
    //             }
    //         },
    //         yAxis: {
    //             min: 0,
    //             title: {
    //                 text: 'Jumlah'
    //             }
    //         },
    //         tooltip: {
    //             // valueSuffix: ' orang',
    //             pointFormat: '{series.name}: <br>{point.y:,.0f} <br>({point.percentage:.1f}%) '
    //         },
    //         plotOptions: {
    //             pie: {
    //                 size:'100%',
    //                 allowPointSelect: true,
    //                 cursor: true,
    //                 innerSize: "60%",
    //                 dataLabels: {
    //                     enabled: true,
    //                     style: {
    //                         fontWeight: "bold",
    //                         textOutline: "",
    //                         color: "black"
    //                     },
    //                     distance: -20
    //                 }
    //             }
    //         },
    //         series: [
    //             {
    //                 name: "Jumlah",
    //                 turboThreshold: 0
    //             }
    //         ]
    //     };

    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empStatus.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         async: false,
    //         data: {
                
    //         },
    //         success: function ( json ) {
    //             optionschartEmpStatus.series[0].data = json.data.d_hr_profile_empStatus;
	// 		    Highcharts.chart('chartEmpStatus', optionschartEmpStatus);
    //         }
    //     } );
        
    // }

    // function chartEmpLevel() {
    //     var optionschartEmpLevel = {
    //         chart: {
    //             type: 'bar',
    //             height: 300
    //         },
    //         title: {
    //             text: 'Karyawan per Level',
    //             align: 'left'
    //         },
    //         // subtitle: {
    //         //     text:
    //         //         ''
    //         //     align: 'left'
    //         // },
    //         xAxis: {
    //             categories: [],
    //             crosshair: true,
    //             accessibility: {
    //                 description: ''
    //             }
    //         },
    //         yAxis: {
    //             min: 0,
    //             title: {
    //                 text: 'Jumlah'
    //             }
    //         },
    //         tooltip: {
    //             valueSuffix: ' orang'
    //         },
    //         plotOptions: {
    //             column: {
    //                 pointPadding: 0.2,
    //                 borderWidth: 0
    //             },
    //             series: {
    //                 dataLabels: {
    //                     enabled: true
    //                 }
    //             }
    //         },
    //         series: []
    //     };

    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empLevel.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         async: false,
    //         data: {
                
    //         },
    //         success: function ( json ) {
    //             optionschartEmpLevel.xAxis.categories = json.data.results_emp_level[0]['data'];
    //             optionschartEmpLevel.series[0] = json.data.results_emp_level[1];
    //             Highcharts.chart('chartEmpLevel', optionschartEmpLevel);
    //         }
    //     } );
        
    // }

    // function chartEmpDept() {
    //     var optionschartEmpDept = {
    //         chart: {
    //             type: 'column',
    //             // marginBottom: 75,
    //             // spacingBottom: 50
    //         },
    //         title: {
    //             text: 'Karyawan per Department',
    //             align: 'left'
    //         },
    //         // subtitle: {
    //         //     text:
    //         //         ''
    //         //     align: 'left'
    //         // },
    //         xAxis: {
    //             categories: [],
    //             crosshair: true,
    //             accessibility: {
    //                 description: ''
    //             }
    //         },
    //         yAxis: {
    //             min: 0,
    //             title: {
    //                 text: 'Jumlah'
    //             }
    //         },
    //         tooltip: {
    //             valueSuffix: ' orang'
    //         },
    //         plotOptions: {
    //             column: {
    //                 pointPadding: 0.2,
    //                 borderWidth: 0
    //             },
    //             series: {
    //                 dataLabels: {
    //                     enabled: true
    //                 }
    //             }
    //         },
    //         series: []
    //     };

    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empDept.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         async: false,
    //         data: {
                
    //         },
    //         success: function ( json ) {
    //             optionschartEmpDept.xAxis.categories = json.data.results_empdept[0]['data'];
    //             optionschartEmpDept.series[0] = json.data.results_empdept[1];
    //             optionschartEmpDept.series[1] = json.data.results_empdept[2];
    //             Highcharts.chart('chartEmpDept', optionschartEmpDept);
    //         }
    //     } );
        
    // }

    // function chartEmpAge() {
    //     var optionschartEmpAge = {
    //         chart: {
    //             type: 'column'
    //         },
    //         title: {
    //             text: 'Karyawan per Usia',
    //             align: 'left'
    //         },
    //         // subtitle: {
    //         //     text:
    //         //         ''
    //         //     align: 'left'
    //         // },
    //         xAxis: {
    //             categories: [],
    //             crosshair: true,
    //             accessibility: {
    //                 description: ''
    //             }
    //         },
    //         yAxis: {
    //             min: 0,
    //             title: {
    //                 text: 'Jumlah'
    //             }
    //         },
    //         tooltip: {
    //             valueSuffix: ' orang'
    //         },
    //         plotOptions: {
    //             column: {
    //                 pointPadding: 0.2,
    //                 borderWidth: 0
    //             },
    //             series: {
    //                 dataLabels: {
    //                     enabled: true
    //                 }
    //             }
    //         },
    //         series: []
    //     };

    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empAge.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         async: false,
    //         data: {
                
    //         },
    //         success: function ( json ) {
    //             optionschartEmpAge.xAxis.categories = json.data.results_emp_age[0]['data'];
    //             optionschartEmpAge.series[0] = json.data.results_emp_age[1];
    //             Highcharts.chart('chartEmpAge', optionschartEmpAge);
    //         }
    //     } );
        
    // }

    // function chartEmpMK() {
    //     var optionschartEmpMK = {
    //         chart: {
    //             type: 'column'
    //         },
    //         title: {
    //             text: 'Karyawan per Masa Kerja',
    //             align: 'left'
    //         },
    //         // subtitle: {
    //         //     text:
    //         //         ''
    //         //     align: 'left'
    //         // },
    //         xAxis: {
    //             categories: [],
    //             crosshair: true,
    //             accessibility: {
    //                 description: ''
    //             }
    //         },
    //         yAxis: {
    //             min: 0,
    //             title: {
    //                 text: 'Jumlah'
    //             }
    //         },
    //         tooltip: {
    //             valueSuffix: ' orang'
    //         },
    //         plotOptions: {
    //             column: {
    //                 pointPadding: 0.2,
    //                 borderWidth: 0
    //             },
    //             series: {
    //                 dataLabels: {
    //                     enabled: true
    //                 }
    //             }
    //         },
    //         series: []
    //     };

    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empMK.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         async: false,
    //         data: {
                
    //         },
    //         success: function ( json ) {
    //             optionschartEmpMK.xAxis.categories = json.data.d_hr_profile_empMK[0]['data'];
    //             optionschartEmpMK.series[0] = json.data.d_hr_profile_empMK[1];
    //             Highcharts.chart('chartEmpMK', optionschartEmpMK);
    //         }
    //     } );
        
    // }

</script>