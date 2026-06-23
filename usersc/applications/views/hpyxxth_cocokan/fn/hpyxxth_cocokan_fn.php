<script>
    function compareField(row, data, index, field) {
        if ((data.hpyemtd_cocokan?.[field] ?? 0) != (data.hpyemtd?.[field] ?? 0)) {
            $(row).find(`td:eq(${index})`).addClass('bg-danger');
        }
    }
</script>