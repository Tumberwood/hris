<script>
    function fn_tanggal(id){
        $.ajax( {
            url: "../../models/hpyxxth/hpyxxth_fn_periode.php",
            dataType: 'json',
            type: 'POST',
            data: {
                id: id,
            },
            success: function ( json ) {
                edthpyxxth.field('hpyxxth.tanggal_awal').val(json.data.rs_hemxxmh.tanggal_awal);
                edthpyxxth.field('hpyxxth.tanggal_akhir').val(json.data.rs_hemxxmh.tanggal_akhir);
            }
        } );
    };
</script>