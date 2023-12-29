<script>

    start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
    end_date   = moment($('#start_date').val()).format('YYYY-MM-DD');

    function htsprtd_get_hemxxmh_kode (){
        $.ajax( {
            url: "../../models/htsprtd/htsprtd_fn_hemxxmh.php",
            dataType: 'json',
            async: false,
            type: 'POST',
            data: {
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                kode_finger_temp = json.data.hemxxmh.kode_finger;
                if(kode_finger_temp.toString().length == 1){
                    kode_finger = '000' + kode_finger_temp;
                }else if(kode_finger_temp.toString().length == 2){
                    kode_finger = '00' + kode_finger_temp;
                }else if(kode_finger_temp.toString().length == 3){
                    kode_finger = '0' + kode_finger_temp;
                }else if(kode_finger_temp.toString().length == 4){
                    kode_finger = kode_finger_temp;
                }
                
            }
        } );
    }
    function jamMakanManual(){
        nama = edthtsprtd.field('htsprtd.nama').val();
        var originalDate = edthtsprtd.field('htsprtd.tanggal').val();
        var tanggal = moment(originalDate).format('YYYY-MM-DD');
        id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();

        $.ajax( {
            url: "../../models/htsprtd/fn_jam_istirahat.php",
            dataType: 'json',
            async: false,
            type: 'POST',
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                jam = json.data.jam_istirahat.jam;
                // console.log(jam);
                if (jam == undefined) {
                    edthtsprtd.field('htsprtd.jam').val('');
                } else {
                    edthtsprtd.field('htsprtd.jam').val(jam);
                }

            }
        } );
    }

    function unikMakan(jam) {
        htsprtd_get_hemxxmh_kode();
        tanggal = edthtsprtd.field('htsprtd.tanggal').val();
        let tanggal_ymd = moment(tanggal).format('YYYY-MM-DD');
        // id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();
        nama = edthtsprtd.field('htsprtd.nama').val();
        // console.log(jam);

        $.ajax( {
            url: "../../models/htsprtd/fn_cek_unik_makan.php",
            dataType: 'json',
            async: false,
            type: 'POST',
            data: {
                tanggal_ymd: tanggal_ymd,
                kode_finger: kode_finger,
                jam: jam
            },
            success: function ( json ) {
                status = json.data.peg_makan.status;
                range_awal = json.data.peg_makan.range_awal;
                range_akhir = json.data.peg_makan.range_akhir;
                // console.log(json.data.peg_makan.range_awal);
                // console.log(range_akhir);

                if (nama == "makan" || nama == "makan manual") {
                    console.log(c_peg);
                    if(status == "invalid"){
                        edthtsprtd.field('htsprtd.id_hemxxmh').error( 'Pegawai Sudah Pernah Diinput pada Mesin Makan/Makan Manual!' );
                    }
                }
            }
        } );
    }
	
    function shift(edt){
        originalDate = edt.field('htssctd.tanggal').val();
        var tanggal = moment(originalDate).format('YYYY-MM-DD');
        
        id_htsxxmh = edt.field('htssctd.id_htsxxmh').val();
		// console.log(id_htsxxmh);
        
        $.ajax( {
            url: "../../models/htssctd/fn_cari_jam_shift.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_htsxxmh: id_htsxxmh
            },
            success: function ( json ) {
                tanggal_jam_awal = json.data.rs_htsxxmh.tanggal_jam_awal;
                tanggal_jam_akhir = json.data.rs_htsxxmh.tanggal_jam_akhir;
                tanggaljam_awal_istirahat = json.data.rs_htsxxmh.tanggaljam_awal_istirahat;
                tanggaljam_akhir_istirahat = json.data.rs_htsxxmh.tanggaljam_akhir_istirahat;

                edt.field('htssctd.tanggaljam_awal').val(moment(tanggal_jam_awal).format('DD MMM YYYY HH:mm'));
                edt.field('htssctd.tanggaljam_akhir').val(moment(tanggal_jam_akhir).format('DD MMM YYYY HH:mm'));
                edt.field('htssctd.tanggaljam_awal_istirahat').val(moment(tanggaljam_awal_istirahat).format('DD MMM YYYY HH:mm'));
                edt.field('htssctd.tanggaljam_akhir_istirahat').val(moment(tanggaljam_akhir_istirahat).format('DD MMM YYYY HH:mm'));
                
            }
        } );
    };

</script>