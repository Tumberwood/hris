<?php
    /* 
        last update: 230121
        template untuk datatables editor field
    */
?>

<script>
    /*
        Digunakan untuk keperluan generate kode
            kategori_dokumen
            kategori_dokumen_value
            field_tanggal
    */
    {
        label: "kategori_dokumen",
        name: "kategori_dokumen",
        type: "hidden"
    },	{
        label: "kategori_dokumen_value",
        name: "kategori_dokumen_value",
        type: "hidden"
    },	{
        label: "field_tanggal",
        name: "field_tanggal",
        type: "hidden"
    },	

    /*
        Digunakan untuk form dasar, harus ada di setiap form
        start_on            : untuk keperluan menghitung waktu kerja. data diisi saat event preopen
        nama_tabel          : untuk keperluan hak akses
        _blank.is_active    : untuk keperluan soft delete
    */
    {
        label: "start_on",
        name: "start_on",
        type: "hidden"
    },	{
        label: "nama_tabel",
        name: "nama_tabel",
        def: "_blank",
        type: "hidden"
    },	{
        label: "Active Status",
        name: "_blank.is_active",
        type: "hidden",
        def: 1
    },

    /* 
        fields yang biasanya dipakai
    */
    {
        label: "Kode",
        name: "_blank.kode",
        fieldInfo: "Informasi terkait inputan",
    }, 	
    
    {
        label: "Nama",
        name: "_blank.nama"
    }, 	
    
    {
        label: "Keterangan",
        name: "_blank.keterangan",
        type: "textarea"
    },	
    
    /*
        Digunakan untuk pilihan yang sedikit, karena tidak bisa search
        options dapat juga diambil dari model, atau di generate dari javascript jika memerlukan dependent 
    */
    {
        label: "Select",
        name: "_blank.is_field",
        type: "select",
        placeholder : "Select",
        options: [
            { "label": "Yes", "value": 1 },
            { "label": "No", "value": 0 }
        ]
    },
    
    /*
        Digunakan untuk pilihan yang banya, bisa search dan add options
        options bisa diambil dari model, atau di generate dari javascript jika memerlukan dependent
        reff: 
            https://editor.datatables.net/plug-ins/field-type/editor.selectize
            https://selectize.dev/docs/api
    */
    {
        label: "Selectize",
        name: "_blank.field_name",
        type: "selectize",
        opts: {
            create: false,
            maxItems: 1,
            maxOptions: 20,
            allowEmptyOption: false,
            closeAfterSelect: true,
            placeholder: "Select",
            openOnFocus: false
        }
    },

    /*
        Digunakan sebagai alternatif select field
        Disarangkan menggunakan select atau selectize  
    */
    {
        label: "Select2",
        name: "_blank.id",
        type: "select2",
        opts:{
            placeholder : "Select",
            allowClear: true,
            multiple: true
            // ini digunakan untuk generate options select dari ajax 
            // karena jika dari php akan ter-load semua. loading time nya lama
            ajax: {
                url: 'model/fn_select.php',
                dataType: 'json',
                initialValue: true,
                processResults: function (data) {
                    return {
                        results: $.map(data, function(obj) {
                            return { id: obj.id, text: obj.label};
                        })
                    };
                }
            }
        }
    },

    /* 
        field tanggal
        reff: https://editor.datatables.net/reference/field/datetime
        opts
            disableDays
            firstDay
            maxDate
            minDate
            showWeekNumber
            yearRange

            hoursAvailable
            minutesAvailable
            minutesIncrement
            secondsAvailable
            secondsIncrement

            momentLocale
            momentStrict
            wireFormat

        format 
            dipilih salah satu. jika butuh format lain, dapat lihat di moment.js
            reff: https://momentjs.com/docs/#/displaying/format/
    */ 
    {
        label: "Tanggal",
        name: "_blank.field_name",
        type: "datetime",
        def: function () { 
            return new Date(); 
        },
        opts:{
            minDate: new Date('1900-01-01'),
            firstDay: 0
        },
        format: 'DD MMM YYYY'
        format: 'DD MMM YYYY / HH:mm'
        format: 'HH:mm'
    }, 
    

    /* 
        Field untuk upload file 
    */
    {
        label: "Lampiran",
        name: "_blank.image",
        type: "upload",
        display: function ( fileId, counter ) {
            return '<img src="'+edt_blank.file( 'files', fileId ).web_path+'"/>';
        },
        noFileText: 'Belum ada gambar'
    }, 	
    
    {
        label: "Lampiran Multi:",
        name: "files[].id",
        type: "uploadMany",
        display: function ( fileId, counter ) {
            if (fileId == 0){
                return 'Belum ada lampiran';
            }else{
                return '<a href="'+edt_blank.file( 'files', fileId ).web_path+'" target="_blank">'+ edt_blank.file( 'files', fileId ).filename + '</a>'; 
            }
        }
    }, 	

    
    /*
        Digunakan untuk field model HTML Editor. 
        
        * IMPORTANT! *
        By default, quill akan meng-insert <p></p> jika blank
    */
    {
        label: "Quill",
        name: "_blank.field_name",
        type: "quill",
        toolbar: '#toolbar'
    },

    // jika perlu memberikan tanda required
    // tetapi di event presubmit harus ditambahkan validasi required
    {
        label: "Required <sup class='text-danger'>*<sup>",
    },

    /* 
    Field lain yang bisa digunakan
    Available basic field type lainnya
        checkbox
        hidden
        password
        radio
        readonly
    */


</script>