<script>
    function get_htsxxmh (){
        tanggal = edthtoxxrd_susulan.field('htoxxrd_susulan.tanggal').val();
        id_hemxxmh = edthtoxxrd_susulan.field('htoxxrd_susulan.id_hemxxmh').val();

        console.log(id_hemxxmh);
        console.log(tanggal);

        if(id_hemxxmh > 0){
            $.ajax( {
                url: "../../models/htoxxth/htoemtd_fn_htsxxmh.php",
                dataType: 'json',
                type: 'POST',
                data: {
                    tanggal: tanggal,
                    id_hemxxmh: id_hemxxmh
                },
                success: function ( json ) {
                    if(json.data.rs_htsxxmh.id > 0){
                        edthtoxxrd_susulan.field('jadwal').val(json.data.rs_htsxxmh.kode + ' (' + json.data.rs_htsxxmh.jam_awal + ' - ' + json.data.rs_htsxxmh.jam_akhir + ')');
                    }else{
                        edthtoxxrd_susulan.field('jadwal').val('');
                    }
                }
            } );
        }

    };
</script>