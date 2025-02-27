<script>
    function cekDetail(){
        $.ajax( {
            url: "../../models/htsprrd_htoxxrd_h/htsprrd_htoxxrd_h_fn_cek_detail.php",
            type: 'POST',
            dataType: 'json',
            data: {
                id_htsprrd_htoxxrd_h: id_htsprrd_htoxxrd_h
            },
            async: false,
            success: function ( json ) {
                c_id = json.data.c_id;
            },
        } );
    };
</script>