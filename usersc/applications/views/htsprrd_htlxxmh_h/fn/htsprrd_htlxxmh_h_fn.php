<script>
    function cekDetail(){
        $.ajax( {
            url: "../../models/htsprrd_htlxxmh_h/htsprrd_htlxxmh_h_fn_cek_detail.php",
            type: 'POST',
            dataType: 'json',
            data: {
                id_htsprrd_htlxxmh_h: id_htsprrd_htlxxmh_h
            },
            async: false,
            success: function ( json ) {
                c_id = json.data.c_id;
            },
        } );
    };

    
    function breakdown(id_hemxxmh, start_date, end_date){
        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#table_al')) {
            $('#table_al').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#table_al').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htlxxmh_h/htsprrd_htlxxmh_h_fn_cek_al.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_table_al || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                {
                    data: "kode",
                    render: function(data, type, row) {
                        return '<a target="_blank" href="../dashboard/d_hr_report_presensi.php?id_hemxxmh=' + row.id_hemxxmh + '&start_date=' + row.tanggal + '">' + data + '</a>';
                    }
                },
                { data: 'nama'},
                { data: 'status_presensi_in'},
                { data: 'status_presensi_out'},
                { data: 'htlxxrh_kode'},
            ],
            rowCallback: function( row, data, index ) {
                
            },
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });

        // IP
        if ($.fn.DataTable.isDataTable('#table_ip')) {
            $('#table_ip').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#table_ip').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htlxxmh_h/htsprrd_htlxxmh_h_fn_cek_ip.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_table_ip || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                {
                    data: "kode",
                    render: function(data, type, row) {
                        return '<a target="_blank" href="../dashboard/d_hr_report_presensi.php?id_hemxxmh=' + row.id_hemxxmh + '&start_date=' + row.tanggal + '">' + data + '</a>';
                    }
                },
                { data: 'nama'},
                { data: 'status_presensi_in'},
                { data: 'status_presensi_out'},
                { data: 'htlxxrh_kode'},
                { 
                    data: 'pot_hk',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
            ],
            rowCallback: function( row, data, index ) {
                
            },
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });

        // sdl
        if ($.fn.DataTable.isDataTable('#table_sdl')) {
            $('#table_sdl').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#table_sdl').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htlxxmh_h/htsprrd_htlxxmh_h_fn_cek_sdl.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_table_sdl || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                {
                    data: "kode",
                    render: function(data, type, row) {
                        return '<a target="_blank" href="../dashboard/d_hr_report_presensi.php?id_hemxxmh=' + row.id_hemxxmh + '&start_date=' + row.tanggal + '">' + data + '</a>';
                    }
                },
                { data: 'nama'},
                { data: 'htlxxrh_kode'},
            ],
            rowCallback: function( row, data, index ) {
                
            },
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });

        // sdl
        if ($.fn.DataTable.isDataTable('#table_absen_sdl')) {
            $('#table_absen_sdl').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#table_absen_sdl').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htlxxmh_h/htsprrd_htlxxmh_h_fn_cek_sdl.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_table_absen_sdl || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                {
                    data: "kode",
                    render: function(data, type, row) {
                        return '<a target="_blank" href="../dashboard/d_hr_report_presensi.php?id_hemxxmh=' + row.id_hemxxmh + '&start_date=' + row.tanggal + '">' + data + '</a>';
                    }
                },
                { data: 'nama'},
                { data: 'kode_absen'},
                { data: 'keterangan'},
            ],
            rowCallback: function( row, data, index ) {
                
            },
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });
    }
</script>