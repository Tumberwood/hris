<script>
    function shift(){
        originalDate = edthtssctd.field('htssctd.tanggal').val();
        var tanggal = moment(originalDate).format('YYYY-MM-DD');
        
        id_htsxxmh = edthtssctd.field('htssctd.id_htsxxmh').val();
        
        $.ajax( {
            url: "../../models/htssctd/fn_cari_jam_shift.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                tanggal: tanggal,
                id_htsxxmh: id_htsxxmh
            },
            success: function ( json ) {
                tanggal_jam_awal = json.data.rs_htsxxmh.tanggal_jam_awal;
                tanggal_jam_akhir = json.data.rs_htsxxmh.tanggal_jam_akhir;
                tanggaljam_awal_istirahat = json.data.rs_htsxxmh.tanggaljam_awal_istirahat;
                tanggaljam_akhir_istirahat = json.data.rs_htsxxmh.tanggaljam_akhir_istirahat;

                edthtssctd.field('htssctd.tanggaljam_awal').val(moment(tanggal_jam_awal).format('DD MMM YYYY HH:mm'));
                edthtssctd.field('htssctd.tanggaljam_akhir').val(moment(tanggal_jam_akhir).format('DD MMM YYYY HH:mm'));
                edthtssctd.field('htssctd.tanggaljam_awal_istirahat').val(moment(tanggaljam_awal_istirahat).format('DD MMM YYYY HH:mm'));
                edthtssctd.field('htssctd.tanggaljam_akhir_istirahat').val(moment(tanggaljam_akhir_istirahat).format('DD MMM YYYY HH:mm'));
                
            }
        } );
    };
</script>