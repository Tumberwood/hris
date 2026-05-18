<script>
    function fn_tanggal(id){
        $.ajax( {
            url: "../../models/hpyxxth/hpyxxth_fn_periode.php",
            dataType: 'json',
            type: 'POST',
            data: {
                id: id,
            },
            success: function ( json ) {
                edthpyxxth.field('hpyxxth.tanggal_awal').val(json.data.rs_hemxxmh.tanggal_awal);
                edthpyxxth.field('hpyxxth.tanggal_akhir').val(json.data.rs_hemxxmh.tanggal_akhir);
            }
        } );
    }

    function detail_breakdown(id_transaksi_d){
        if ($.fn.DataTable.isDataTable('#potongan_upah')) {
            $('#potongan_upah').DataTable().destroy();
        }

        $('#potongan_upah').DataTable({
            dom: 'lrtip',
            ajax: {
                url: "../../models/hpyxxth/detail_pot_upah.php",
                type: 'POST',
                data: function (d) {
                    d.id_transaksi_d = id_transaksi_d;
                },
                dataSrc: function (json) {
                    return json.data.rs_potongan_upah || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                { data: 'st_jadwal' },
                { data: 'status_presensi_in' },
                { data: 'status_presensi_out' },
                { data: 'is_pot_upah' },
            ],
            destroy: true,
            responsive: false,
            autoWidth: false,
            lengthChange: true,
        });

        if ($.fn.DataTable.isDataTable('#potongan_premi')) {
            $('#potongan_premi').DataTable().destroy();
        }

        $('#potongan_premi').DataTable({
            dom: 'lrtip',
            ajax: {
                url: "../../models/hpyxxth/detail_pot_premi.php",
                type: 'POST',
                data: function (d) {
                    d.id_transaksi_d = id_transaksi_d;
                },
                dataSrc: function (json) {
                    return json.data.rs_potongan_premi || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                { data: 'st_jadwal' },
                { data: 'status_presensi_in' },
                { data: 'status_presensi_out' },
                { data: 'is_pot_premi' },
            ],
            destroy: true,
            responsive: false,
            autoWidth: false,
            lengthChange: true,
        });

        if ($.fn.DataTable.isDataTable('#data_lembur')) {
            $('#data_lembur').DataTable().destroy();
        }

        $('#data_lembur').DataTable({
            dom: 'lrtip',
            ajax: {
                url: "../../models/hpyxxth/detail_lembur.php",
                type: 'POST',
                data: function (d) {
                    d.id_transaksi_d = id_transaksi_d;
                },
                dataSrc: function (json) {
                    return json.data.rs_lembur || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                { data: 'spkl' },
                { data: 'jenis_lembur' },
                { data: 'status_istirahat' },
                { data: 'durasi_spkl' },
                { data: 'pot_ti' },
                { data: 'pot_overtime' },
                { data: 'pot_hk' },
                { data: 'pot_jam' },
                { data: 'lembur_final' },
            ],
            destroy: true,
            responsive: false,
            autoWidth: false,
            lengthChange: true,
        });
    }
</script>