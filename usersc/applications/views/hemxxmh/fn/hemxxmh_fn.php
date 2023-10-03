<script>
    function get_heyxxmh (){
        id_heyxxmd = edthemxxmh.field('hemjbmh.id_heyxxmd').val();

        $.ajax( {
            url: "../../models/hemxxmh/fn_get_heyxxmh.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_heyxxmd: id_heyxxmd
            },
            success: function ( json ) {
                console.log(json.data.rs_heyxxmd.id_heyxxmh);
                id_heyxxmh_old = json.data.rs_heyxxmd.id_heyxxmh;
                edthemxxmh.field('hemjbmh.id_heyxxmh').val(json.data.rs_heyxxmd.id_heyxxmh);
            }
        } );

    };
</script>