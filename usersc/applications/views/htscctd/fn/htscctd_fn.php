<script>
    function get_htsxxmh_pengaju (){
        $.ajax( {
            url: "../../models/htscctd/htscctd_fn_htsxxmh.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh_pengaju
            },
            success: function ( json ) {
                edthtscctd.field('htsxxmh_pengaju_data').val(json.data.rs_htsxxmh.kode + ' (' + json.data.rs_htsxxmh.jam_awal + ' - ' + json.data.rs_htsxxmh.jam_akhir + ')');
                edthtscctd.field('htscctd.id_htsxxmh_pengaju').val(json.data.rs_htsxxmh.id);
            }
        } );
    };

    function get_htsxxmh_pengganti (){
        $.ajax( {
            url: "../../models/htscctd/htscctd_fn_htsxxmh.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh_pengganti
            },
            success: function ( json ) {
                edthtscctd.field('htsxxmh_pengganti_data').val(json.data.rs_htsxxmh.kode + ' (' + json.data.rs_htsxxmh.jam_awal + ' - ' + json.data.rs_htsxxmh.jam_akhir + ')');
                edthtscctd.field('htscctd.id_htsxxmh_pengganti').val(json.data.rs_htsxxmh.id);
            }
        } );
    };;
</script>