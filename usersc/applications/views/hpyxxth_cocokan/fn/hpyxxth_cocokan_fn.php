<script>
function formatNumber(value) {
        return $.fn.dataTable.render
            .number(',', '.', 0)
            .display(value);
    }

    function compareField(row, data, index, field) {

        const nilaiCocokan = Number(data.hpyemtd_cocokan?.[field] ?? 0);
        const nilaiAsli = Number(data.hpyemtd?.[field] ?? 0);

        if (nilaiCocokan != nilaiAsli) {

            const selisih = nilaiCocokan - nilaiAsli;

            $(row).find(`td:eq(${index})`)
                .addClass('bg-danger')
                .attr(
                    'style',
                    'background:#dc3545 !important;color:#fff !important;'
                )
                .attr('data-toggle', 'tooltip')
                .attr(
                    'title',
                    `Selisih: ${formatNumber(selisih)}`
                );
        }
    }

</script>