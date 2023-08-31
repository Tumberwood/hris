<script>
    function loadHemjb() {
        id_hemxxmh = edthtpr_no_hemxxmh.field('htpr_no_hemxxmh.id_hemxxmh').val();
        
        $.ajax( {
            url: "../../models/htpr_no_hemxxmh/htpr_no_hemxxmh_fn_load.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                id_het = json.data.rs_hemxxmh.id_het;
                nama_het = json.data.rs_hemxxmh.nama_het;
                id_hod = json.data.rs_hemxxmh.id_hod;
                nama_hod = json.data.rs_hemxxmh.nama_hod;
                
                edthtpr_no_hemxxmh.field('htpr_no_hemxxmh.id_hetxxmh').val(id_het);
                edthtpr_no_hemxxmh.field('id_hetxxmh').val(nama_het);
                edthtpr_no_hemxxmh.field('htpr_no_hemxxmh.id_hodxxmh').val(id_hod);
                edthtpr_no_hemxxmh.field('id_hodxxmh').val(nama_hod);
            }
        } );
    }
</script>