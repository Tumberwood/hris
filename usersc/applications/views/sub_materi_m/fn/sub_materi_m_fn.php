<script>
    function val_edit(tabel, kolom, is_delete){
        $.ajax( {
            url: "../../models/sub_materi_m/fn_sub_materi_m.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tabel: tabel,
                id: kolom,
                is_delete: is_delete
            },
            success: function ( json ) {
                edit_val = json.data.r_val_edit;
            }
        } );
    }
</script>