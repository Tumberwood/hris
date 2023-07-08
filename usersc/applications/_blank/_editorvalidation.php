<?php
    /* 
        last update: 230121
        template untuk validasi datatables editor di javascript (client side)
    */
?>

<script>
    // script diletakkan di dalam event preSubmit
    // BEGIN validasi
    // boleh ditambahkan action

    if(action != 'remove'){
        // letalkkan validasi disini
    }

    // return false jika error
    if ( edt_blank.inError() ) {
        return false;
    }
    // END validasi

    // jenis validasi

    //  validasi blank
    if(!variablecek || variablecek == ''){
        edt_blank.field('_blank.namafield').error( 'Wajib diisi!' );
    }

    // terkait angka
    //  validasi min atau max angka
    if(variablecek <= 0 ){
        edt_blank.field('_blank.namafield').error( 'Inputan harus > 0' );
    }
    // validasi angka
    if(isNaN(variablecek) ){
        edt_blank.field('_blank.namafield').error( 'Inputan harus berupa Angka!' );
    }
    

    // validasi length
    variablecek   = namafield.length;
    if(variablecek != 10){
        edt_blank.field('_blank.namafield').error( 'Inputan harus 10 digit angka!' );
    }
    
    // validasi tanggal (menggunakan moment.js)
    // data dari field harus diubah ke moment js dulu dengan cara moment()

    tanggal1 = moment(edt_blank.field('_blank.namafield').val()).format('YYYY-MM-DD');
    tanggal2 = moment().format('YYYY-MM-DD');

    // memastikan inputan tanggal sudah benar formatnya
    // in case user mengetik
    if(moment(tanggal, 'DD MMM YYYY').isValid() == false){
        edt_blank.field('_blank.namafield').error( 'Format Tanggal Salah' );
    }
    // end validasi tanggal

    // tanggal lebih besar atau kecil
    if( tanggal1 < tanggal2 ){
        edt_blank.field('_blank.namafield').error( 'Tanggal harus lebih besar dari sekarang!' );
    }

    // selisih tanggal
    selisih	= tanggal2.diff(tanggal1,'years');	// years, months, weeks, days, hours, minutes, seconds, 
    if( selisih < 15 ){ // disesuaikan
        edt_blank.field('_blank.namafield').error( 'Tanggal salah!' );
    }

    


</script>