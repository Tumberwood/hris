<script>
    function get_htsxxmh (){
        id_htotpmh = edthtoemtd.field('htoemtd.id_htotpmh').val();
        id_hemxxmh = edthtoemtd.field('htoemtd.id_hemxxmh').val();

        if(id_htotpmh > 0 && id_hemxxmh > 0){
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
                    jam_awal_jadwal = '';
                    jam_akhir_jadwal = '';
                    dayname = '';

                    if(json.data.rs_htsxxmh.id > 0){
                        jam_awal_jadwal = json.data.rs_htsxxmh.jam_awal;
                        jam_akhir_jadwal = json.data.rs_htsxxmh.jam_akhir;
                        dayname = json.data.rs_htsxxmh.dayname;
                        // console.log(dayname);
                        edthtoemtd.field('jadwal').val(json.data.rs_htsxxmh.kode + ' (' + json.data.rs_htsxxmh.jam_awal + ' - ' + json.data.rs_htsxxmh.jam_akhir + ')');
                    }else{
                        edthtoemtd.field('jadwal').val('');
                    }
                }
            } );
        }

    };

    // tidak dipakai
    function check_valid_ti(){
        // digunakan untuk mengambil data checkclock masuk kerja
        // jika > 5 menit dari jadwal, maka TI akan dipotong
        $.ajax( {
            url: "../../models/htoxxth/htoemtd_check_valid_ti.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                status_ti = json.data.status_ti;
            }
        } );
    }

    // percobaan, mungkin tidak dipakai disini
    function check_valid_checkclock(){
        $.ajax( {
            url: "../../models/htoxxth/htoemtd_check_valid_checkclock.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                is_valid_checkclock = json.data.is_valid_checkclock;
            }
        } );
    }
    
    function lemburLibur(id_hemxxmh, tanggal, id_htotpmh){
        $.ajax( {
            url: "../../models/htoxxth/fn_lembur_libur.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_hemxxmh: id_hemxxmh,
                tanggal: tanggal,
                id_htotpmh: id_htotpmh
            },
            success: function ( json ) {
                jadwal = json.data.jadwal;
                jabatan = json.data.jabatan;
                is_holiday = json.data.is_holiday;
                
                //Jika Bukan Security atau  koor satpam maka ikut validasi harus off atau ada public holiday saat mengajukan lembur libur
                if(jabatan != 48 || jabatan != 99) {
                    if (id_htotpmh == 4) {
                        if (jadwal != 1) {
                            if (is_holiday == 1) {
                                // console.log('Add');
                            } else {
                                edthtoemtd.field('htoemtd.id_htotpmh').error( 'Lembur Libur Tidak Bisa dipilih karena Shift Pegawai ini bukan OFF dan tanggal bukan public holiday ataupun Cuti Bersama!' );
                            }
                        }
                    }
                }

            }
        });
    }

    function cekApproveTanggal() {
        $.ajax( {
            url: '../../models/htoxxth/fn_approve_per_tanggal.php',
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                end_date: end_date,
                status: "cek"
            },
            success: function ( json ) {
                // console.log(json);
                c_approve = json.data.c_approve;
                // console.log('c_approve = ' +c_approve);
                if (c_approve == 0 && start_date == end_date) {
				    tblhtoxxth.button('btnApproveTanggal:name').enable();
                } else {
				    tblhtoxxth.button('btnApproveTanggal:name').disable();
                }
            }
        });
    }
</script>