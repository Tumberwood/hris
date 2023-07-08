<script>
    function get_htsxxmh (){
        $.ajax( {
            url: "../../models/htoxxth/htoemtd_fn_htsxxmh.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                if(json.data.rs_htsxxmh){
                    edthtoemtd.field('jadwal').val(json.data.rs_htsxxmh.kode + ' (' + json.data.rs_htsxxmh.jam_awal + ' - ' + json.data.rs_htsxxmh.jam_akhir + ')');
                }else{
                    edthtoemtd.field('jadwal').val('');
                }
            }
        } );
    };
</script>