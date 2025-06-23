<script>
    function cekDetail(){
        $.ajax( {
            url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h_fn_cek_detail.php",
            type: 'POST',
            dataType: 'json',
            data: {
                id_htsprrd_htoxxrd_h: id_htsprrd_htoxxrd_h
            },
            async: false,
            success: function ( json ) {
                c_id = json.data.c_id;
            },
        } );
    }

    function breakdownMakan(id_hemxxmh, kode_finger, start_date, end_date){
        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#ceklok_makan')) {
            $('#ceklok_makan').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#ceklok_makan').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h_fn_ceklok_makan.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.kode_finger = kode_finger;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_ceklok_makan || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                { data: 'kode' },
                { data: 'nama' },
                { data: 'mesin' },
                { data: 'jam' },
            ],
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });

        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#pot_makan')) {
            $('#pot_makan').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#pot_makan').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h_fn_cek_pot_makan.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.kode_finger = kode_finger;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_pot_makan || [];
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
                { data: 'nama' },
                { data: 'jadwal' },
                { data: 'is_makan' },
            ],
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });
    }

    function breakdownLembur(id_hemxxmh, start_date, end_date){
        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#lembur_presensi')) {
            $('#lembur_presensi').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#lembur_presensi').DataTable({
            dom: 'lrtip' ,
            ajax: {
                url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h_fn_cek_lembur_presensi.php",
                type: 'POST',
                data: function (d) {
                    d.id_hemxxmh = id_hemxxmh;
                    d.start_date = start_date;
                    d.end_date = end_date;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_lembur_presensi || [];
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
                { 
                    data: 'durasi_lembur_total_jam',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
                { 
                    data: 'pot_jam_final',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
                { 
                    data: 'durasi_lembur_final',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
                { 
                    data: 'lembur15',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
                { 
                    data: 'lembur2',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
                { 
                    data: 'lembur3',
                    render: $.fn.dataTable.render.number( ',', '.', 1,'','' ),
                    class: "text-right"
                },
            ],
            rowCallback: function( row, data, index ) {
                if ( data.durasi_lembur_final == 0 ) {
                    $('td', row).addClass('bg-warning');
                }
            },
            footerCallback: function ( row, data, start, end, display ) {
                var api       = this.api(), data;
                var numFormat = $.fn.dataTable.render.number( '\,', '.', 1, '' ).display; 
                // hitung jumlah 
                for (let i = 3; i <= 8; i++) {
                    sum_durasi_lembur_jam = api.column( i ).data().sum();
                    $( '#subtotal_'+i ).html( numFormat(sum_durasi_lembur_jam) );
                }
            },
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });
    }
</script>