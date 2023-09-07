<script>
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
    };
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
                console.log(json.data.jam_istirahat.jam);
                edthtsprtd.field('htsprtd.jam').val(json.data.jam_istirahat.jam);
            }
        } );
    };
</script>