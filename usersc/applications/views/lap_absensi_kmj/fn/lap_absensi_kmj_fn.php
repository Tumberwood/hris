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
                if (json.data && json.data.length > 0) {
                    var data = json.data;

                    // 1. Dapatkan daftar tanggal unik berdasarkan pasangan key JSON
                    var dateList = [];
                    var keys = Object.keys(data[0]);
                    keys.forEach(function (key) {
                        if (key !== "Shift" && key.indexOf("_Jml_Orang") !== -1) {
                            var dateStr = key.replace("_Jml_Orang", "");
                            dateList.push(dateStr);
                        }
                    });

                    // 2. Susun HTML Tabel dengan Header 2 Baris (Excel Style)
                    var str1 = '<table id="tblhtsprrd1" class="table table-striped table-bordered table-hover nowrap">';
                    str1 += '<thead>';
                    
                    // Baris Header 1 (Tanggal)
                    str1 += '<tr>';
                    str1 += '<th rowspan="2" class="text-center align-middle">Shift</th>';
                    dateList.forEach(function (dateStr) {
                        str1 += '<th colspan="2" class="text-center">' + dateStr + '</th>';
                    });
                    str1 += '</tr>';

                    // Baris Header 2 (Sub-Kolom)
                    str1 += '<tr>';
                    dateList.forEach(function () {
                        str1 += '<th class="text-center">Jml Orang</th>';
                        str1 += '<th class="text-center">Durasi Total</th>';
                    });
                    str1 += '</tr>';

                    str1 += '</thead>';
                    str1 += '<tbody></tbody>';
                    str1 += '</table>';

                    $('#tabel_atas').html(str1);

                    // 3. Susun Array Columns DataTables dengan title Eksplisit
                    var dynamicColumns = [
                        { 
                            data: "Shift", 
                            title: "Shift",
                            className: "fw-bold" 
                        }
                    ];

                    dateList.forEach(function (dateStr) {
                        // Kolom Jml Orang
                        dynamicColumns.push({
                            data: dateStr + "_Jml_Orang",
                            title: dateStr + " Jml Orang",
                            className: "text-center",
                            createdCell: function (td, cellData) {
                                var val = parseInt(cellData);
                                // Highlight pink jika bukan 4 dan bukan 5
                                if (val !== 4 && val !== 5) {
                                    $(td).css({
                                        "background-color": "#f8d7da",
                                        "color": "#721c24",
                                        "font-weight": "bold"
                                    });
                                }
                            }
                        });

                        // Kolom Durasi Total
                        dynamicColumns.push({
                            data: dateStr + "_Durasi_Total",
                            title: dateStr + " Durasi Total",
                            className: "text-end",
                            render: function (data) {
                                return data ? parseFloat(data).toFixed(2) : "0.00";
                            }
                        });
                    });

                    // 4. Inisialisasi DataTables
                    tblhtsprrd1 = $('#tblhtsprrd1').DataTable({
                        data: data,
                        columns: dynamicColumns,
                        responsive: false,
                        paging: false,
                        searching: false,
                        info: false,
                        ordering: false,
                        dom: 
                            "<P>"+
                            "<lf>"+
                            "<B>"+
                            "<rt>"+
                            "<'row'<'col-sm-4'i><'col-sm-8'p>>",
                        buttons: [
                            'copy',
                            'excel'
                            // Note: 'colvis' sengaja dikeluarkan karena tidak support header 2 baris
                        ]
                    });

                } else {
                    notifyprogress = $.notify({
                        message: 'Tidak ada data pada tanggal tersebut!'
                    }, {
                        z_index: 9999,
                        allow_dismiss: false,
                        type: 'danger',
                        delay: 3
                    });
                }
            }
        });
    }
</script>