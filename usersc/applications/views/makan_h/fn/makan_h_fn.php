<script>
    function jumlahMakan(mesin, edt){
        tanggal = edt.field('makan_h.tanggal').val();

        nominal_s1 = edt.field('makan_h.nominal_s1').val();
        nominal_s2 = edt.field('makan_h.nominal_s2').val();
        nominal_s3 = edt.field('makan_h.nominal_s3').val();

        $.ajax( {
            url: "../../models/makan_h/fn_jumlah_mesin_makan.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                nama: mesin,
            },
            success: function ( json ) {
                shift1 = json.data.rs_htpxxmh.shift1;
                shift2 = json.data.rs_htpxxmh.shift2;
                shift3 = json.data.rs_htpxxmh.shift3;

                edt.field('makan_h.jumlah_ceklok_s1').val(shift1);
                edt.field('makan_h.jumlah_ceklok_s2').val(shift2);
                edt.field('makan_h.jumlah_ceklok_s3').val(shift3);

                subtotal1 = shift1 * nominal_s1;
                subtotal2 = shift2 * nominal_s2;
                subtotal3 = shift3 * nominal_s3;

                subtotal_all = parseFloat(subtotal1) + parseFloat(subtotal2) + parseFloat(subtotal3);

                edt.field('makan_h.subtotal_s1').val(subtotal1);
                edt.field('makan_h.subtotal_s2').val(subtotal2);
                edt.field('makan_h.subtotal_s3').val(subtotal3);

                edt.field('makan_h.subtotal_all').val(subtotal_all);
            }
        } );
    };

    function subtotal(edt){
        tanggal = edt.field('makan_h.tanggal').val();

        nominal_s1 = edt.field('makan_h.nominal_s1').val();
        nominal_s2 = edt.field('makan_h.nominal_s2').val();
        nominal_s3 = edt.field('makan_h.nominal_s3').val();

        shift1 = edt.field('makan_h.jumlah_ceklok_s1').val();
        shift2 = edt.field('makan_h.jumlah_ceklok_s2').val();
        shift3 = edt.field('makan_h.jumlah_ceklok_s3').val();

        subtotal1 = shift1 * nominal_s1;
        subtotal2 = shift2 * nominal_s2;
        subtotal3 = shift3 * nominal_s3;

        subtotal_all = parseFloat(subtotal1) + parseFloat(subtotal2) + parseFloat(subtotal3);

        edt.field('makan_h.subtotal_s1').val(subtotal1);
        edt.field('makan_h.subtotal_s2').val(subtotal2);
        edt.field('makan_h.subtotal_s3').val(subtotal3);

        edt.field('makan_h.subtotal_all').val(subtotal_all);
    };
</script>