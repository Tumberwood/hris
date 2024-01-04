<script>
    function sisaCuti (){
        tanggal = edthtlxxth.field('htlxxth.tanggal_awal').val();
        id_hemxxmh = edthtlxxth.field('htlxxth.id_hemxxmh').val();

        $.ajax( {
            url: "../../models/htlxxth/fn_sisa_saldo_cuti.php",
            dataType: 'json',
            type: 'POST',
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                saldo = json.data.rs_saldo.sisa_saldo;
                // console.log(saldo);
                edthtlxxth.field('sisa_saldo_cuti').val(saldo);
            }
        } );

    };
</script>