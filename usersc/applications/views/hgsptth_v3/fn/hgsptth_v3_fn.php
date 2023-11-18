<script>
    function jamKerja(edt){
        shift = edt.field('hgsemtd_v3.shift').val();
        id_htsptth_v3 = edt.field('hgsemtd_v3.id_htsptth_v3').val();
        
        if (shift <= 3) {
            $.ajax( {
                url: "../../models/hgsptth_v3/fn_cek_pola.php",
                dataType: 'json',
                type: 'POST',
                async: false,
                data: {
                    shift: shift,
                    id_htsptth_v3: id_htsptth_v3
                },
                success: function ( json ) {
                    id_htsxxmh = json.data.rs_jam.id;
                    id_htsxxmh_old = id_htsxxmh
                    edt.field('hgsemtd_v3.id_htsxxmh').val(id_htsxxmh);
                }
            } ); 
        }
        
        if (shift == 4) {
            id_htsxxmh_old = 1;
            edt.field('hgsemtd_v3.id_htsxxmh').val(1);
        }
    }

    function validasiSubmit(action, edt, hari) {
        var id_hemxxmh = edt.field('hgsemtd_v3.id_hemxxmh').val();
        // console.log(id_hemxxmh);
        // console.log(edt);
        // console.log(hari);
        $.ajax({
            url: "../../models/hgsptth_v3/fn_cek_jadwal.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal_awal: tanggal_awal_select,
                tanggal_akhir: tanggal_akhir_select,
                id_hemxxmh: id_hemxxmh,
                id_hgsptth_v3: id_hgsptth_v3,
                hari: hari
            },
            success: function (json) {
                var c_jadwal = json.data.c_jadwal;
                if(action == 'create'){
                    if (c_jadwal > 0) {
                        edt.field('hgsemtd_v3.id_hemxxmh').error('Jadwal Pegawai Ini Sudah Pernah Dibuat!');
                    }
                }
            }
        });
        
        if(!id_hemxxmh || id_hemxxmh == ''){
            edt.field('hgsemtd_v3.id_hemxxmh').error( 'Wajib diisi!' );
        }

        shift = edt.field('hgsemtd_v3.shift').val();
        if(!shift || shift == ''){
            edt.field('hgsemtd_v3.shift').error( 'Wajib diisi!' );
        }

        id_htsxxmh = edt.field('hgsemtd_v3.id_htsxxmh').val();
        if(!id_htsxxmh || id_htsxxmh == ''){
            edt.field('hgsemtd_v3.id_htsxxmh').error( 'Wajib diisi!' );
        }

        id_holxxmd = edt.field('hgsemtd_v3.id_holxxmd').val();
        if(!id_holxxmd || id_holxxmd == ''){
            edt.field('hgsemtd_v3.id_holxxmd').error( 'Wajib diisi!' );
        }

        id_htsptth_v3 = edt.field('hgsemtd_v3.id_htsptth_v3').val();
        if(!id_htsptth_v3 || id_htsptth_v3 == ''){
            edt.field('hgsemtd_v3.id_htsptth_v3').error( 'Wajib diisi!' );
        }
    }
</script>