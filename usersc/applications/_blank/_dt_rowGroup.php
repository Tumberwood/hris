<script>
/**
    Membuat rowGroup dengan Sum atau Average
    Script diletakkan di dalam javascript datatables (tbl)
 */


rowGroup: {
    dataSrc: 'blank.nama_field',    // diganti nama field yang akan menjadi group
    
    // startRender, berarti ditampilkan di awal group 
    // endRender, berarti ditampilkan di akhir group 
    endRender: function(rows, group) {
        
        var sumSubtotal = rows
            .data()
            .pluck('blank.nama_field_hitung')   // diganti nama field yang akan dihitung
            .reduce( function (a, b) {
                return parseInt(a) + parseInt(b);
            }, 0);
        sumSubtotal = $.fn.dataTable.render.number(',', '.', 0, '').display( sumSubtotal )  // formatting

        var avgData = rows
            .data()
            .pluck('blank.nama_field_hitung')   // diganti nama field yang akan dihitung
            .reduce( function (a, b) {
                return parseInt(a) + parseInt(b);
            }, 0) / rows.count() ;              // ada count baris untuk membagi menjadi rata-rata
        avgData = $.fn.dataTable.render.number(',', '.', 0, '').display( avgData )  // formatting
        
        // menampilkan ke tabel 
        // silakan disesuaikan kolomnya

        return $('<tr/>')
            .append( '<td class="font-bold text-success">Subtotal</td>' )
            .append( '<td></td>' )
            .append( '<td></td>' )
            .append( '<td></td>' )
            .append( '<td class="text-right font-bold text-success">'+sumSubtotal+'</td>' ); // variable hasil hitung
    }
    
},

</script>