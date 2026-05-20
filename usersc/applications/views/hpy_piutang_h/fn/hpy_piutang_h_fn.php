<script>
    function hitung_tanggal_akhir() {
        tanggal_mulai = edthpy_piutang_h.field('hpy_piutang_h.tanggal_mulai').val();
        tenor = edthpy_piutang_h.field('hpy_piutang_h.tenor').val();

        // akhir = moment(tanggal_mulai).add(tenor, 'month').format('DD MMM YYYY');
        akhir = moment(tanggal_mulai)
        .add(tenor - 1, 'month')
        .format('DD MMM YYYY');
        
        edthpy_piutang_h.field('hpy_piutang_h.tanggal_akhir').val(akhir);
    }

    // function hitung_cicilan() {
    //     nominal = edthpy_piutang_h.field('hpy_piutang_h.nominal').val();
    //     tenor = edthpy_piutang_h.field('hpy_piutang_h.tenor').val();

    //     if (tenor > 0) {
    //         per_bulan = Math.ceil(nominal / tenor / 1000) * 1000;
    //         if (tenor == 1) {
    //             cicilan_akhir = 0;
    //         } else{
    //             cicilan_akhir = nominal - (per_bulan * (tenor - 1));
    //         }
    //     } else {
    //         per_bulan = 0;
    //         cicilan_akhir = 0;
    //     }


    //     edthpy_piutang_h.field('hpy_piutang_h.cicilan_per_bulan').val(per_bulan);
    //     edthpy_piutang_h.field('hpy_piutang_h.cicilan_terakhir').val(cicilan_akhir);
    // }

function hitung_cicilan() {

    let nominal = parseFloat(edthpy_piutang_h.field('hpy_piutang_h.nominal').val()) || 0;
    let tenor = parseFloat(edthpy_piutang_h.field('hpy_piutang_h.tenor').val()) || 0;
    let per_bulan = parseFloat(edthpy_piutang_h.field('hpy_piutang_h.cicilan_per_bulan').val()) || 0;

    let cicilan_akhir = 0;

    if (nominal > 0) {

        let active_id = $(document.activeElement).attr('id') || '';

        // jika edit cicilan per bulan
        if (active_id == 'DTE_Field_hpy_piutang_h-cicilan_per_bulan') {

            if (per_bulan > 0) {

                tenor = Math.ceil(nominal / per_bulan);

                edthpy_piutang_h
                    .field('hpy_piutang_h.tenor')
                    .val(tenor);

            }

        } 
        
        // jika edit nominal / tenor
        else {

            if (tenor > 0) {

                per_bulan = Math.ceil((nominal / tenor) / 1000) * 1000;

            }

        }

        if (tenor == 1) {

            cicilan_akhir = nominal;

        } else {

            cicilan_akhir = nominal - (per_bulan * (tenor - 1));

        }

    } else {

        per_bulan = 0;
        cicilan_akhir = 0;

    }

    edthpy_piutang_h
        .field('hpy_piutang_h.cicilan_per_bulan')
        .val(per_bulan);

    edthpy_piutang_h
        .field('hpy_piutang_h.cicilan_terakhir')
        .val(cicilan_akhir);
}
    
</script>