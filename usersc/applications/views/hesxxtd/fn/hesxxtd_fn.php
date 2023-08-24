<script>
    function find_status() {
        id_hemxxmh = edthesxxtd.field('hesxxtd.id_hemxxmh').val();
        id_hesxxmh = edthesxxtd.field('hesxxtd.id_hesxxmh').val();
        console.log(id_hemxxmh);
        console.log(id_hesxxmh);

        $.ajax( {
            url: "../../models/hesxxtd/hesxxtd_fn_status_ke.php",
            dataType: 'json',
            type: 'POST',
            // async: false,
            data: {
                id_hemxxmh: id_hemxxmh,
                id_hesxxmh: id_hesxxmh
            },
            success: function ( json ) {
                console.log(json.data.rs_hemxxmh.status_ke);
                edthesxxtd.field('hesxxtd.status_ke').val(json.data.rs_hemxxmh.status_ke);
            }
        } );
    }
</script>