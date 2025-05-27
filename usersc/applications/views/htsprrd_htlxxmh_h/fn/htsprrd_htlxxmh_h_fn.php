<script>
    function cekDetail(){
        $.ajax( {
            url: "../../models/htsprrd_htlxxmh_h/htsprrd_htlxxmh_h_fn_cek_detail.php",
            type: 'POST',
            dataType: 'json',
            data: {
                id_htsprrd_htlxxmh_h: id_htsprrd_htlxxmh_h
            },
            async: false,
            success: function ( json ) {
                c_id = json.data.c_id;
            },
        } );
    };
</script>