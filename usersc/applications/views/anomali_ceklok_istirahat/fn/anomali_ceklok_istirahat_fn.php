<script>
    
    function fn_sesuai(
        id_anomali_ceklok_istirahat,
        id_hemxxmh,
        is_sesuai
    ){
        $.ajax( {
            url: "../../models/anomali_ceklok_istirahat/fn_sesuai.php",
            type: 'POST',
            dataType: 'json',
            data: {
                tanggal: moment(tanggal).format('YYYY-MM-DD'),
                id_hemxxmh: id_hemxxmh,
                is_sesuai: is_sesuai,
            },
            async: false,
            success: function ( json ) {
                $.notify({
                    message: json.data.message
                },{
                    type: json.data.type_message,
                    allow_dismiss: true,
                    delay: 0
                });

                tblanomali_ceklok_istirahat.ajax.reload(function ( json ) {
                    notifyprogress.close();
                }, false);
            },
        } );
    }
</script>