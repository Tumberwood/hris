<?php 

/** Tutorial Membuat Field Item, UoM, Konversi Jumlah
 * Tujuan:
 * - Generate Options UoM (iumxxmh) berdasarkan Item (iimxxmh) terpilih
 * - Get nilai id_iumxxmh_terkecil dan jumlah_konversi
 * - Menghitung jumlah_terkecil
 * 
 * Cara Membuat
 * 1. Tambahkan function di file views/fn/_blank_vn.php
 * 2. Tambahkan dependent di 3 fields:
 *      - id_iimxxmh
 *      - id_iumxxmh_unit
 *      - jumlah_unit
 */

?>

<script>
    
    // BEGIN file views/fn/_blank_vn.php
    // copy 2 funtions ini ke file _fn

    // Tujuan::
    // generate options UoM (id_iumxxmh_unit) berdasarkan Item (id_iimxxmh) terpilih
    // dipanggil di dependent id_iimxxmh
    function get_opt_iumxxmh(){
        $.ajax( {
            url: "../../../helpers/tr_fn_uom_opt.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_iimxxmh: id_iimxxmh
            },
            success: function ( json ) {
                edt_blank.field('_blank.id_iumxxmh_unit').update(json);
            }
        } );
    };

    // Tujuan: 
    // - get nilai id_iumxxmh_terkecil dan jumlah_konversi
    // - menghitung jumlah_terkecil
    // dipanggil di dependent id_iimxxmh, id_iumxxmh_unit, jumlah_unit
    function get_iimummd(){
        $.ajax( {
            url: "../../../helpers/tr_fn_uom_konversi.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_iimxxmh: id_iimxxmh,
                id_iumxxmh_unit: id_iumxxmh_unit,
                jumlah_unit: jumlah_unit
            },
            success: function ( json ) {
                edt_blank.field('_blank.jumlah_konversi').val(json.jumlah_konversi);
                edt_blank.field('_blank.jumlah_terkecil').val(json.jumlah_terkecil);
                edt_blank.field('_blank.id_iumxxmh_terkecil').val(json.id_iumxxmh_terkecil);
            }
        } );
    };
    // END file views/fn/_blank_vn.php

    // BEGIN DEPENDENT FIELDS
    edt_blank.dependent( '_blank.id_iimxxmh', function ( val, data, callback ) {
        id_iumxxmh_unit = edt_blank.field('_blank.id_iumxxmh_unit').val();
        jumlah_unit = edt_blank.field('_blank.jumlah_unit').val();

        if(val > 0){
            id_iimxxmh = val;
            get_opt_iumxxmh();

            if( id_iumxxmh_unit > 0 && jumlah_unit > 0 ){
                get_iimummd();
            }
        }
        return {}
    }, {event: 'keyup change'});

    edt_blank.dependent( '_blank.id_iumxxmh_unit', function ( val, data, callback ) {
        id_iimxxmh = edt_blank.field('_blank.id_iimxxmh').val();
        jumlah_unit = edt_blank.field('_blank.jumlah_unit').val();

        if(val > 0 && id_iimxxmh > 0 && jumlah_unit > 0){
            id_iumxxmh_unit = val;
            get_iimummd();
        }
        return {}
    }, {event: 'keyup change'});

    edt_blank.dependent( '_blank.jumlah_unit', function ( val, data, callback ) {
        id_iimxxmh = edt_blank.field('_blank.id_iimxxmh').val();
        id_iumxxmh_unit = edt_blank.field('_blank.id_iumxxmh_unit').val();

        if(val > 0 && id_iimxxmh > 0 && id_iumxxmh_unit > 0){
            jumlah_unit = val;
            get_iimummd();
        }
        return {}
    }, {event: 'keyup change'});
    
    // END DEPENDENT FIELDS

</script>