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
                edtharxxth.field('hevgrmh_awal_nama').val(json.data.rs_hemxxmh.hevgrmh_awal_nama);

                id_hovxxmh = json.data.rs_hemxxmh.id_hovxxmh_awal;
                id_hodxxmh = json.data.rs_hemxxmh.id_hodxxmh_awal;
                id_hosxxmh = json.data.rs_hemxxmh.id_hosxxmh_awal;
                id_hevxxmh = json.data.rs_hemxxmh.id_hevxxmh_awal;
                id_hetxxmh = json.data.rs_hemxxmh.id_hetxxmh_awal;
                id_holxxmd_2 = json.data.rs_hemxxmh.id_holxxmd_2_awal;
                id_hevgrmh = json.data.rs_hemxxmh.id_hevgrmh_awal;
                is_berubah = json.data.is_berubah;

                if(is_berubah == 1){
                    edtharxxth.field('harxxth.id_hovxxmh_awal').val(id_hovxxmh);
                    edtharxxth.field('harxxth.id_hodxxmh_awal').val(id_hodxxmh);
                    edtharxxth.field('harxxth.id_hosxxmh_awal').val(id_hosxxmh);
                    edtharxxth.field('harxxth.id_hevxxmh_awal').val(id_hevxxmh);
                    edtharxxth.field('harxxth.id_hetxxmh_awal').val(id_hetxxmh);
                    edtharxxth.field('harxxth.id_holxxmd_2_awal').val(id_holxxmd_2);
                    edtharxxth.field('harxxth.id_hevgrmh_awal').val(id_hevgrmh);
                }
            }
        } );
    }

    function grup_jabatan(){
        id_hetxxmh = edtharxxth.field('harxxth.id_hetxxmh_akhir').val();
        $.ajax( {
            url: "../../models/harxxth/harxxth_fn_grup_jabatan.php",
            dataType: 'json',
            type: 'POST',
            data: {
                id_hetxxmh: id_hetxxmh,
            },
            success: function ( json ) {
                id_hevgrmh_akhir_old = json.data.rs_hemxxmh.id_hevgrmh;
                edtharxxth.field('harxxth.id_hevgrmh_akhir').val(id_hevgrmh_akhir_old);
            }
        } );
    }
</script>