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
</script>