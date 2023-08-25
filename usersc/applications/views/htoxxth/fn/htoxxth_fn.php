<script>
    function get_htsxxmh (){
        id_htotpmh = edthtoemtd.field('htoemtd.id_htotpmh').val();
        id_hemxxmh = edthtoemtd.field('htoemtd.id_hemxxmh').val();

        if(id_htotpmh > 0 && id_hemxxmh > 0){
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
</script>