<script>
    function find_status() {
        id_hemxxmh = edthesxxtd.field('hesxxtd.id_hemxxmh').val();
        id_hesxxmh = edthesxxtd.field('hesxxtd.id_hesxxmh').val();
        keputusan = edthesxxtd.field('hesxxtd.keputusan').val();
        
        $.ajax( {
            url: "../../models/hesxxtd/hesxxtd_fn_status_ke.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_hemxxmh: id_hemxxmh,
                id_hesxxmh: id_hesxxmh
            },
            success: function ( json ) {
                status_ke = json.data.status_ke;
                tanggal_keluar = json.data.rs_hemxxmh.tanggal_keluar;
                tanggal_masuk = json.data.rs_hemxxmh.tanggal_masuk;
                
					edthesxxtd.field('hesxxtd.status_ke').val(status_ke);
					edthesxxtd.field('tanggal_keluar').val(moment(tanggal_keluar).format('DD MMM YYYY'));
					edthesxxtd.field('tanggal_masuk').val(moment(tanggal_masuk).format('DD MMM YYYY'));
                    
                if (keputusan == 'Kontrak') {
					if (tanggal_keluar != null) {
						tanggal_awal_baru = moment(tanggal_keluar).add('day', 2).format('DD MMM YYYY');
						tanggal_akhir_baru = moment(tanggal_awal_baru).add('month', 6).format('DD MMM YYYY');
						edthesxxtd.field('hesxxtd.tanggal_mulai').val(tanggal_awal_baru);
						edthesxxtd.field('hesxxtd.tanggal_selesai').val(tanggal_akhir_baru);
					} else {
						tanggal_awal_baru = moment(tanggal_masuk).add('month', 6).add('day', 2).format('DD MMM YYYY');
						tanggal_akhir_baru = moment(tanggal_awal_baru).add('month', 6).format('DD MMM YYYY');
						edthesxxtd.field('hesxxtd.tanggal_mulai').val(tanggal_awal_baru);
						edthesxxtd.field('hesxxtd.tanggal_selesai').val(tanggal_akhir_baru);
					}
					edthesxxtd.field('hesxxtd.tanggal_mulai').val();
					edthesxxtd.field('hesxxtd.tanggal_selesai').val();
				}
            }
        } );
    }
</script>