<script>
    function get_heyxxmh (){
        id_heyxxmd = edthemxxmh.field('hemjbmh.id_heyxxmd').val();

        $.ajax( {
            url: "../../models/hemxxmh/fn_get_heyxxmh.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_heyxxmd: id_heyxxmd
            },
            success: function ( json ) {
                console.log(json.data.rs_heyxxmd.id_heyxxmh);
                id_heyxxmh_old = json.data.rs_heyxxmd.id_heyxxmh;
                edthemxxmh.field('hemjbmh.id_heyxxmh').val(json.data.rs_heyxxmd.id_heyxxmh);
            }
        } );

    };

    function get_tgl_keluar(){
        id_hesxxmh = edthemxxmh.field('hemjbmh.id_hesxxmh').val();
        tanggal_masuk = edthemxxmh.field('hemjbmh.tanggal_masuk').val();
        // console.log(id_hemxxmh);

        $.ajax( {
            url: "../../models/hemxxmh/fn_get_tgl_resign n_terminasi.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                tgl_akhir = json.data.tgl_akhir;
                if(tgl_akhir != null){
                    tanggal_akhir = moment(tgl_akhir).format('DD MMM YYYY');
                    edthemxxmh.field('hemjbmh.tanggal_keluar').val(tanggal_akhir);
                } else {
                    if (id_hesxxmh == 1 || id_hesxxmh  == 5 || id_hesxxmh  == 4) {
                        edthemxxmh.field('hemjbmh.tanggal_keluar').hide();
                        edthemxxmh.field('hemjbmh.tanggal_keluar').val(null);
                    } else {
                        if (id_hesxxmh != 3) {
                            edthemxxmh.field('hemjbmh.tanggal_keluar').show();
                            tanggal_akhir = moment(tanggal_masuk).add('month', 6).format('DD MMM YYYY');
                            edthemxxmh.field('hemjbmh.tanggal_keluar').val(tanggal_akhir);
                        } else {
                            edthemxxmh.field('hemjbmh.tanggal_keluar').val();
                        }
                    }
                }
            }
        } );

    }

    function tanggal_akhir_kontrak(){
        id_hesxxmh = edthemxxmh.field('hemjbmh.id_hesxxmh').val();
        tanggal_masuk = edthemxxmh.field('hemjbmh.tanggal_masuk').val();
        tanggal_keluar = edthemxxmh.field('hemjbmh.tanggal_keluar').val();
        console.log(tanggal_keluar_old);
        console.log(id_hesxxmh);
        console.log('tanggal_keluar = '+tanggal_keluar);

        if (tanggal_keluar_old != '') {
            edthemxxmh.field('hemjbmh.tanggal_keluar').val();
        } else {
            if (id_hesxxmh == 1 || id_hesxxmh  == 5 || id_hesxxmh  == 4) {
                edthemxxmh.field('hemjbmh.tanggal_keluar').hide();
                // if (tanggal_keluar_old == null) {
                    edthemxxmh.field('hemjbmh.tanggal_keluar').val(null);
                // } else {
                //     edthemxxmh.field('hemjbmh.tanggal_keluar').val(tanggal_keluar_old);
                // }
            } else {
                if (id_hesxxmh != 3) {
                    console.log(123);
                    edthemxxmh.field('hemjbmh.tanggal_keluar').show();
                    tanggal_akhir = moment(tanggal_masuk).add('month', 6).format('DD MMM YYYY');
                    edthemxxmh.field('hemjbmh.tanggal_keluar').val(tanggal_akhir);
                } else {
                    edthemxxmh.field('hemjbmh.tanggal_keluar').val();
                }
            }
        }
    }
</script>