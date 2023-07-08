<script>
    { data: "_blank.id",visible:false },
    { data: "_blank.kode" },
    { data: "_blank.nama" },

    // untuk field terkait angka
    // ditambahkan thousand separator dan decimal digit untuk mempermudah pembacaan
    { 
        data: "_blank.harga_unit",  
        render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),  // thousand sep, decimal mark, decimal digit, prefix, suffix
        class: "text-right"
    },
    { 
        data: "files.web_path" ,
        render: function (data){
            if(data){
                return '<a href="'+edt_blank.file( 'files', fileId ).web_path+'" target="_blank">'+ edt_blank.file( 'files', fileId ).filename + '</a>';
            }else{
                return '';
            }
        }
    },
    { 
        data: "files",
        render: function (data){
            if(data.length > 0){
                var download_icon = '';
                Object.keys(data).forEach(function(key) {
                    download_icon = download_icon + '<a href="'+ data[key]['web_path'] +'" target="_blank"><i class="fa fa-download"> </i></a>' + ' ';
                });
                return download_icon;
            }else{
                return '';
            }
        }
    },
    {
        data: "_blank.is_active",
        render: function (data){
            if (data == 1){
                return '<i class="fa fa-check text-navy"></i>';
            }else if(data == 0){
                return '<i class="fa fa-check text-danger"></i>';
            }
        }
    },
    { 
        data: "_blank.is_approve" ,
        render: function (data){ 	// render model tulisan
            if (data == 1){
                return 'Approved';
            }else if(data == 2){
                return 'Approval Canceled';
            }else if(data == -1){
                return 'Rejected';
            }else if(data == -2){
                return 'Rejection Canceled';
            }else{
                return '';
            }
        }
        render: function (data){	// render model icon
            if (data == 1){
                return '<i class="fa fa-check text-navy"></i>';
            }else if(data == 2){
                return '<i class="fa fa-undo"></i>';
            }else if(data == -1){
                return '<i class="fa fa-remove text-danger"></i>';
            }else{
                return '';
            }
        }
    },
    { 
        data: null,
        render: function (data, type, row) {
            // console.log(row);
            if(row.table_name.fieldname){
                return row.table_name.fieldname;
            }
        }
    },
    { data: "_blank", render: "[, ].nama" }, // untuk render multi data many to many
	
	</script>