<script>
    function cariApprove(){
        if (tanggal_select == 0 ) {
            originalDate = edthgtprth.field('hgtprth.tanggal').val();
            var tanggal = moment(originalDate).format('YYYY-MM-DD');
        } else {
            tanggal = moment(tanggal_select).format('YYYY-MM-DD');
        }
        
        // console.log(tanggal);
        $.ajax( {
            url: "../../models/hgtprth/fn_cari_approve_presensi.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal
            },
            success: function ( json ) {
                total_approve = json.data.rs_htsprrd.approve;
            }
        } );
    };
</script>