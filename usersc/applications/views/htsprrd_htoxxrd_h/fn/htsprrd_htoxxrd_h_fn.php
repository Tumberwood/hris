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
                { data: 'kode' },
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
</script>