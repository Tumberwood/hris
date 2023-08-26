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
    // END highchart generate options

    // function boxEmpGender (){
    //     $.ajax( {
    //         url: "../../models/dashboard/d_hr_profile_empGender.php",
    //         dataType: 'json',
    //         type: 'POST',
    //         data: {
    //             // tanggal_akhir: tanggal_akhir
    //         },
    //         success: function ( json ) {
    //             c_laki = json.data.hemxxmh_gender[0]['c_gender'];
    //             c_perempuan = json.data.hemxxmh_gender[1]['c_gender'];
    //             c_total = c_laki  + c_perempuan;

    //             p_laki = (c_laki / c_total * 100).toFixed(2);
    //             p_perempuan = (c_perempuan / c_total * 100).toFixed(2) ;
                
    //             $("#c_laki").html(c_laki);
    //             $("#c_perempuan").html(c_perempuan);

    //             $("#p_laki").html(p_laki + "%");
    //             $("#p_perempuan").html(p_perempuan + "%");

    //         }
    //     } );
    // };

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

    function chartEmpIzin() {
        var optionschartEmpIzin = {
            chart: {
                type: 'column',
                //  marginTop: 10, 
            },
            title: {
                text: 'Karyawan Izin',
                align: 'left'
            },
            // subtitle: {
            //     text:
            //         ''
            //     align: 'left'
            // },
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
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [],
            //ADD BY FERRY 26 AUG 23
            //tambahkan drilldown cukup seperti dibawah ini, TIDAK PERLU menambahkan series didalamnya.
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                },
            }
        };

        $.ajax( {
            url: "../../models/dashboard/d_hr_presensi_empIzin.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                start_date: start_date,
                end_date: end_date
            },
            success: function ( json ) {
                optionschartEmpIzin.series[0] = json.data.results_emp_izin[1];
              
                console.log(optionschartEmpIzin.series[0]);

                //isi drilldown dengan variable dari json yang isinya adalah series dan data
                optionschartEmpIzin.drilldown = json.data.results_emp_izin[2];
                console.log(optionschartEmpIzin.drilldown); 
                console.log(optionschartEmpIzin); 
                Highcharts.chart('chartEmpIzin', optionschartEmpIzin);
            }
        } );
        
    }
    
    function chartEmpAbsen() {
        var optionschartEmpAbsen = {
            chart: {
                type: 'column',
                //  marginTop: 10, 
            },
            title: {
                text: 'Karyawan Absen',
                align: 'left'
            },
            // subtitle: {
            //     text:
            //         ''
            //     align: 'left'
            // },
            xAxis: {
                type: 'category',
                crosshair: true,
                accessibility: {
                    description: ''
                },
                labels: {
                    style: {
                        whiteSpace: 'nowrap' // fix wrap label biar tidak kebawah add by ferry 25-aug-23
                    }
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
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [],
            //ADD BY FERRY 26 AUG 23
            //tambahkan drilldown cukup seperti dibawah ini, TIDAK PERLU menambahkan series didalamnya.
            drilldown: {
                breadcrumbs: {
                    position: {
                        align: 'right'
                    }
                },
            }
        };

        $.ajax( {
            url: "../../models/dashboard/d_hr_presensi_empAbsen.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                start_date: start_date,
                end_date: end_date
            },
            success: function ( json ) {
                optionschartEmpAbsen.series[0] = json.data.results_emp_absen[1];
              
                console.log(optionschartEmpAbsen.series[0]);

                //isi drilldown dengan variable dari json yang isinya adalah series dan data
                optionschartEmpAbsen.drilldown = json.data.results_emp_absen[2];
                console.log(optionschartEmpAbsen.drilldown); 
                console.log(optionschartEmpAbsen); 
                Highcharts.chart('chartEmpAbsen', optionschartEmpAbsen);
            }
        } );
        
    }

    

</script>