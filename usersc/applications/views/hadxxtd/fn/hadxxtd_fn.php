<script>
    function havxxmh_load_hadxxmh(){
        id_havxxmh = edthadxxtd.field('hadxxtd.id_havxxmh').val();
        if(id_havxxmh > 0){
            $.ajax( {
                url: "../../models/hadxxtd/havxxmh_fn_hadxxmh.php",
                dataType: 'json',
                type: 'POST',
                async: false,
                data: {
                    id_havxxmh: id_havxxmh
                },
                success: function ( json ) {
                    id_hadxxmh_saran = json.data.rs_havxxmh.id_hadxxmh;
                    if(id_hadxxmh_old == 0){
                        edthadxxtd.field('hadxxtd.id_hadxxmh').val(json.data.rs_havxxmh.id_hadxxmh);
                    }
                }
            } );
        }
        
    };

    function hadxxmh_fn_tanggal_akhir(){
        id_hadxxmh = edthadxxtd.field('hadxxtd.id_hadxxmh').val();
        tanggal_awal = edthadxxtd.field('hadxxtd.tanggal_awal').val();
        if(id_hadxxmh > 0 && tanggal_awal != ''){
            $.ajax( {
                url: "../../models/hadxxtd/hadxxmh_fn_tanggal_akhir.php",
                dataType: 'json',
                type: 'POST',
                async: false,
                data: {
                    id_hadxxmh: id_hadxxmh,
                    tanggal_awal: tanggal_awal
                },
                success: function ( json ) {
                    tanggal_akhir = json.data.tanggal_akhir;
                    edthadxxtd.field('hadxxtd.tanggal_akhir').val(tanggal_akhir);
                }
            } );
        }
    };
</script>