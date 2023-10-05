<script>
    function hitung_tanggal_akhir() {
        tanggal_mulai = edthpy_piutang_h.field('hpy_piutang_h.tanggal_mulai').val();
        tenor = edthpy_piutang_h.field('hpy_piutang_h.tenor').val();

        akhir = moment(tanggal_mulai).add(tenor, 'month').format('DD MMM YYYY');
        edthpy_piutang_h.field('hpy_piutang_h.tanggal_akhir').val(akhir);
    }

    function hitung_cicilan() {
        nominal = edthpy_piutang_h.field('hpy_piutang_h.nominal').val();
        tenor = edthpy_piutang_h.field('hpy_piutang_h.tenor').val();

        if (tenor > 0) {
            per_bulan = Math.ceil(nominal / tenor / 1000) * 1000;
            cicilan_akhir = nominal - (per_bulan * (tenor - 1));
        } else {
            per_bulan = 0;
            cicilan_akhir = 0;
        }
        edthpy_piutang_h.field('hpy_piutang_h.cicilan_per_bulan').val(per_bulan);
        edthpy_piutang_h.field('hpy_piutang_h.cicilan_terakhir').val(cicilan_akhir);
    }
    
</script>