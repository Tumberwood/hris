<script>
    function get_htpxxmh (){
        $.ajax( {
            url: "../../models/htpxxth/htpxxth_fn_htpxxmh.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_htpxxmh: id_htpxxmh
            },
            success: function ( json ) {
                jenis_jam = json.data.rs_htpxxmh.jenis_jam;
            }
        } );
    };
</script>