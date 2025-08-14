<script>
    function harxxth_load_hemxxmh (){
        id_hemxxmh = edtharxxth.field('harxxth.id_hemxxmh').val();
        $.ajax( {
            url: "../../models/harxxth/harxxth_fn_load_hemxxmh.php",
            dataType: 'json',
            type: 'POST',
            data: {
                id_hemxxmh: id_hemxxmh,
                id_harxxth: id_harxxth,
            },
            success: function ( json ) {
                edtharxxth.field('hovxxmh_awal_nama').val(json.data.rs_hemxxmh.hovxxmh_awal_nama);
                edtharxxth.field('hodxxmh_awal_nama').val(json.data.rs_hemxxmh.hodxxmh_awal_nama);
                edtharxxth.field('hosxxmh_awal_nama').val(json.data.rs_hemxxmh.hosxxmh_awal_nama);
                edtharxxth.field('hevxxmh_awal_nama').val(json.data.rs_hemxxmh.hevxxmh_awal_nama);
                edtharxxth.field('hetxxmh_awal_nama').val(json.data.rs_hemxxmh.hetxxmh_awal_nama);
                edtharxxth.field('holxxmd_2_awal_nama').val(json.data.rs_hemxxmh.holxxmd_2_awal_nama);

                id_hovxxmh = json.data.rs_hemxxmh.id_hovxxmh_awal;
                id_hodxxmh = json.data.rs_hemxxmh.id_hodxxmh_awal;
                id_hosxxmh = json.data.rs_hemxxmh.id_hosxxmh_awal;
                id_hevxxmh = json.data.rs_hemxxmh.id_hevxxmh_awal;
                id_hetxxmh = json.data.rs_hemxxmh.id_hetxxmh_awal;
                id_holxxmd_2 = json.data.rs_hemxxmh.id_holxxmd_2_awal;

                if(id_harxxth == 0){
                    edtharxxth.field('harxxth.id_hovxxmh_awal').val(id_hovxxmh);
                }
                if(id_harxxth == 0){
                    edtharxxth.field('harxxth.id_hodxxmh_awal').val(id_hodxxmh);
                }
                if(id_harxxth == 0){
                    edtharxxth.field('harxxth.id_hosxxmh_awal').val(id_hosxxmh);
                }
                if(id_harxxth == 0){
                    edtharxxth.field('harxxth.id_hevxxmh_awal').val(id_hevxxmh);
                }
                if(id_harxxth == 0){
                    edtharxxth.field('harxxth.id_hetxxmh_awal').val(id_hetxxmh);
                }
                if(id_harxxth == 0){
                    edtharxxth.field('harxxth.id_holxxmd_2_awal').val(id_holxxmd_2);
                }
            }
        } );
    };
</script>