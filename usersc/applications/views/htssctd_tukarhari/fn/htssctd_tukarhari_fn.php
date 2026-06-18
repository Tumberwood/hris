<script>
    var allowedDates = [];

    function cek_tanggal_merah() {
        $.ajax({
            url: "../../models/htssctd_tukarhari/fn_cek_tanggal_merah.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            success: function (json) {

                allowedDates = json.data.rs_libur.map(function(item) {
                    return new Date(item.tanggal).toDateString();
                });

                console.log(allowedDates);
            }
        });
    }
</script>