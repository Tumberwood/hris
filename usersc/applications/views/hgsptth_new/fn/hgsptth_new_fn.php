<script>
    function PolaShift (){
        id_htsptth_new = edthgsptth_new.field('hgsptth_new.id_htsptth_new').val();
        tanggal = edthgsptth_new.field('hgsptth_new.tanggal_awal').val();
        var tanggal_awal = moment(tanggal).format('YYYY-MM-DD');

        $.ajax( {
            url: "../../models/hgsptth_new/fn_cek_pola.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal_awal: tanggal_awal,
                id_htsptth_new: id_htsptth_new
            },
            success: function ( json ) {
                tanggal_akhir = json.data.rs_pola.tanggal_akhir;
                status_hari_pola = json.data.rs_pola.status;
                edthgsptth_new.field('hgsptth_new.tanggal_akhir').val(moment(tanggal_akhir).format('DD MMM YYYY'));
            }
        } );
    }

    function hari() {
        $.ajax( {
            url: "../../models/hgsptth_new/fn_cek_hari_detail.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_hgsptth_new: id_hgsptth_new
            },
            success: function ( json ) {
                minggu = json.data.rs_pola.minggu;
                senin = json.data.rs_pola.senin;
                selasa = json.data.rs_pola.selasa;
                rabu = json.data.rs_pola.rabu;
                kamis = json.data.rs_pola.kamis;
                jumat = json.data.rs_pola.jumat;
                sabtu = json.data.rs_pola.sabtu;

                if (minggu == null) {
                    tblhgsemtd_new.column(3).visible(false);
                } else {
                    tblhgsemtd_new.column(3).visible(true);
                    if (senin == null) {
                        // Move the column at index 3 (column 4) to a new position (e.g., after column at index 9)
                        // tblhgsemtd_new.colReorder.move( 3, 9 );
                    }
                }

                if (senin == null) {
                    tblhgsemtd_new.column(4).visible(false);
                } else {
                    tblhgsemtd_new.column(4).visible(true);
                }

                if (selasa == null) {
                    tblhgsemtd_new.column(5).visible(false);
                } else {
                    tblhgsemtd_new.column(5).visible(true);
                }

                if (rabu == null) {
                    tblhgsemtd_new.column(6).visible(false);
                } else {
                    tblhgsemtd_new.column(6).visible(true);
                }

                if (kamis == null) {
                    tblhgsemtd_new.column(7).visible(false);
                } else {
                    tblhgsemtd_new.column(7).visible(true);
                }

                if (jumat == null) {
                    tblhgsemtd_new.column(8).visible(false);
                } else {
                    tblhgsemtd_new.column(8).visible(true);
                }

                if (sabtu == null) {
                    tblhgsemtd_new.column(9).visible(false);
                } else {
                    tblhgsemtd_new.column(9).visible(true);
                }

            }
        } );
    }
</script>