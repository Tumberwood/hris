<?php
    /*
        230629 - 3.1.0
            Mengubah button menjadi 3 set
                btnSetTools       : terkait tools table
                btnSetAction      : terkait action transaksi
                btnSetApproval    : terkait approval

            untuk membuat setButton:
            if(count($arr_buttons_setName) > 0){
                {
                    extend: 'collection',
                    name: 'btnSetName',
                    text: '<i class="fa fa-wrench">',
                    className: 'btn btn-outline',
                    autoClose: true,
                    buttons: [
                        berisi button-button yang ada
                        foreach ($arr_buttons_tools as $value) {
                            if($value == 'btnName'){

                            }
                        }
                    ]
                }
            }
        230121 - 3.0.1
            Memisahkan tombol tools sehingga bisa lebih flexible lagi
            
        221205 - 3.0.0
            Standarisasi Nama Table Baru

        220827 - 2.0.0
            Menambahkan atribut 'name' pada button, sehingga button bisa di skip urutannya, tidak perlu menggunakan index.
            Default button name:
            set button tools
                table.button( 'btnSetTools:name' );
                    table.button( 'btnCopy:name' );
                    table.button( 'btnExcel:name' );
                    table.button( 'btnPdf:name' );
                    table.button( 'btnShowHide:name' );
                    table.button( 'btnColVis:name' );
                
            set button action
                table.button( 'btnSetAction:name' );
                    table.button( 'btnCreate:name' );
                    table.button( 'btnEdit:name' );
                    table.button( 'btnRemove:name' );
                    table.button( 'btnNonAktif:name' );
                    table.button( 'btnView:name' );

            set button approval
                table.button( 'btnSetApprove:name' );
                    table.button( 'btnApprove:name' );
                    table.button( 'btnCancelApprove:name' );
                    table.button( 'btnVoid:name' );

                    // tidak ada cancel Void
        
                

        Kegunaan:
            - Untuk generate default button pada datatables, khusus untuk table header.
                Button yang di generate adalah:
                - btnSetTools
                - btnColVis
                - btnCreate
                - btnEdit
		
        Parameter:
		    $id_transaksi = 'id_' . $nama_tabel;
            $table    = 'tbl'. $nama_tabel;
            $edt      = 'edt'. $nama_tabel;
    */
?>

<?php
    // BEGIN IF $arr_buttons_tools

    if(count($arr_buttons_tools) > 0){
?>      
        // BEGIN generate button
        {
            extend: 'collection',
            name: 'btnSetTools',
            //text: 'Tools',
            text: '<i class="fa fa-wrench"></i>',
            className: 'btn btn-outline',
            autoClose: true,
            buttons: [
<?php     
                // BEGIN foreach arr_buttons_tools
                foreach ($arr_buttons_tools as $value) {
?>

<?php
    // BEGIN button show_hide
                    if($value == 'show_hide'){
?>
                        {
                            text: '<i class="fa fa-adjust" id="bf_active_status">&nbsp &nbsp Show / Hide Document</i>',
                            name: 'btnShowHide', 
                            className: 'btn',
                            titleAttr: 'Show / Hide Inactive Document',
                            action: function ( e, dt, node, config, tbl_details ) {
                                
                                if (show_inactive_status<?php echo $show_status;?> == 0){
                                    $('#bf_active_status').addClass('text-danger');
                                    show_inactive_status<?php echo $show_status;?> = 1;
                                } else if (show_inactive_status<?php echo $show_status;?> == 1){
                                    $('#bf_active_status').removeClass('text-danger');
                                    show_inactive_status<?php echo $show_status;?> = 0;
                                }

                                <?php echo $table;?>.rows().deselect();
                                <?php echo $table;?>.ajax.reload(null,false);

                            }
                        },

<?php
                    }   // END button show_hide
?>

<?php
    // BEGIN button excel
                    if($value == 'excel'){
?>
                        { 
                            extend: "excel",
                            name: 'btnExcel', 
                            text: '<i class="fa fa-file-excel-o">&nbsp &nbsp Excel</i>', 
                            className: 'btn btn-outline',
                            titleAttr: 'Export to Excel'
                        },
<?php
                    }   // END button excel
?>

<?php
    // BEGIN button pdf
                    if($value == 'pdf'){
?>
                        { 
                            extend: "pdf", 
                            name: 'btnPdf', 
                            text: '<i class="fa fa-file-pdf-o">&nbsp &nbsp pdf</i>', 
                            className: 'btn btn-outline',
                            titleAttr: 'Export to pdf'
                        },
<?php
                    }   // END button pdf
?>

<?php
    // BEGIN button copy
                    if($value == 'copy'){
?>
                        { 
                            extend: "copy", 
                            name: 'btnCopy',
                            text: '<i class="fa fa-copy">&nbsp &nbsp Copy</i>', 
                            className: 'btn btn-outline',
                            titleAttr: 'Copy'
                        },
<?php
                    }   // END button copy
?>

<?php
    // BEGIN button colvis
                    if($value == 'colvis'){
?>
                        { 
                            extend: 'colvis',
                            text: '<i class="fa fa-eye-slash" id="bf_active_status">&nbsp &nbsp Show / Hide Column</i>',
                            name: 'btnColVis',
                            className: 'btn btn-outline',
                            titleAttr: 'Show / Hide Column',
                            columns: colvisCount,
                            columnText: function ( dt, idx, title ) {
                                return title;
                            }
                        },
<?php
                    }   // END button colvis
?>

<?php
                }   // END of foreach arr_buttons_tools
?>

            ]   // END buttons:[]
    
        }
        // END generate button
<?php        
    }   // END IF $arr_buttons_tools
?> 



<?php
    // BEGIN IF $arr_buttons_tools
    if(count($arr_buttons_action) > 0){
?>
        // BEGIN generate button
        ,{
            extend: 'collection',
            name: 'btnSetTools',
            text: 'Action',
            className: 'btn btn-outline',
            autoClose: true,
            buttons: [
<?php
    // BEGIN foreach
                foreach ($arr_buttons_action as $value) {
?>

<?php
    // BEGIN button create
                    if($value == 'create'){
?>
                        { 
                            extend: 'create',
                            name: 'btnCreate', 
                            editor: <?php echo $edt;?>, 
                            text: '<i class="fa fa-plus">&nbsp &nbsp Add New</i>', 
                            className: 'btn btn-outline', 
                            titleAttr: 'Add New',
                            key: {
                                key: 'n',
                                ctrlKey : true,
                                altKey : true
                            }
                        },
<?php
                    }   // END button create
?>

<?php
    // BEGIN  button edit
                    if($value == 'edit'){
?>
                        { 
                            extend: 'edit', 
                            name: 'btnEdit', 
                            editor: <?php echo $edt;?>,
                            text: '<i class="fa fa-edit">&nbsp &nbsp Edit</i>', 
                            className: 'btn btn-outline',
                            titleAttr: 'Edit',
                            key: {
                                key: 'e',
                                ctrlKey : true,
                                altKey : true
                            }
                        },
<?php
                    }   // END button edit
?>

<?php
    // BEGIN button remove
                    if($value == 'remove'){
?>
                        {
                            extend: 'remove', 
                            name: 'btnRemove',
                            editor: <?php echo $edt;?>, 
                            text: '<i class="fa fa-trash">&nbsp &nbsp Delete</i>', 
                            className: 'btn btn-danger',
                            titleAttr: 'Remove',
                            key: {
                                key: 'd',
                                ctrlKey : true,
                                altKey : true
                            }
                        },
<?php
                    }   // END button remove
?>

<?php
    // BEGIN button view
                    if($value == 'view'){
?>
                        {
                            extend: "selected",
                            name: 'BtnView',
                            text: '<i class="fa fa-window-maximize">&nbsp &nbsp View Data</i>', 
                            className: 'btn btn-outline',
                            titleAttr: 'View Data',
                            action: function(e, dt, node, config) {
                                act = 'view';
                                <?php echo $edt;?>.edit(<?php echo $table;?>.row({
                                    selected: true
                                }).index(), {
                                    title: 'View'
                                });
                                <?php echo $edt;?>.disable();
                                e.preventDefault();
                            }
                        },
<?php
                    }   // END button view
?>

<?php
    // BEGIN button nonaktif_h
                    if($value == 'nonaktif_h'){
?>
                        { 
                            text: '<i class="fa fa-trash" id="btnNonAktif">&nbsp &nbsp Delete</i>', 
                            name: 'btnNonAktif',
                            className: 'btn btn-danger',
                            titleAttr: 'Remove',
                            action: function ( e, dt, node, config ) {
                                if(is_active == 1){
                                    // jika data aktif, maka akan di non aktifkan
                                    state_active = 1;
                                    message_is_delete = "Apakah Anda Yakin Akan Menghapus Data?";
                                    $('#btnNonAktif').removeClass('btn-danger');
                                }else{
                                    // jika data non aktif, maka akan di aktifkan
                                    state_active = 0;
                                    message_is_delete = "Apakah Anda Yakin Akan Mengaktifkan Kembali Data?"
                                    $( '#btnNonAktif' ).addClass('btn-danger');
                                }

                                is_delete = confirm(message_is_delete);
                                if(is_delete == true){
                                    $.ajax( {
                                        url: '../../../helpers/fn_nonaktif.php',
                                        dataType: 'json',
                                        type: 'POST',
                                        data: {
                                            table_name	 : '<?php echo $table_name;?>',
                                            id_transaksi : id_transaksi_h,
                                            state_active : state_active
                                        },
                                        success: function ( json ) {
                    
                                            $.notify({
                                                message: json.message
                                            },{
                                                type: json.type_message
                                            });
                                            
                                            <?php echo $table;?>.ajax.reload(null, false);
                                            <?php echo $table;?>.rows().deselect();
                                        }
                                    } );
                                }

                            }
                        },
<?php
                    }   // END button nonaktif_h
?>

<?php
    // BEGIN button nonaktif_d
                    if($value == 'nonaktif_d'){
?>
                        { 
                            text: '<i class="fa fa-trash" id="btnNonAktif">&nbsp &nbsp Delete</i>', 
                            name: 'btnNonAktif',
                            className: 'btn btn-danger',
                            titleAttr: 'Remove',
                            action: function ( e, dt, node, config ) {
                                var rows = <?php echo $table;?>.rows( {selected: true} ).indexes();
                                
                                if(is_active_d == 1){
                                    // jika data aktif, maka akan di non aktifkan
                                    state_active = 1;
                                    is_active_new = 0;
                                    message_is_delete = "Apakah Anda Yakin Akan Menghapus Data?";
                                    // $('#btnNonAktif').removeClass('btn-danger');
                                }else{
                                    // jika data non aktif, maka akan di aktifkan
                                    state_active = 0;
                                    is_active_new = 1;
                                    message_is_delete = "Apakah Anda Yakin Akan Mengaktifkan Kembali Data?"
                                    // $( '#btnNonAktif' ).addClass('btn-danger');
                                }

                                is_delete = confirm(message_is_delete); 

                                if(is_delete == true){
                                    $.ajax( {
                                        url: '../../../helpers/fn_nonaktif.php',
                                        dataType: 'json',
                                        type: 'POST',
                                        data: {
                                            table_name	 : '<?php echo $table_name;?>',
                                            id_transaksi : id_transaksi_d,
                                            state_active : state_active
                                        },
                                        success: function ( json ) {
                    
                                            $.notify({
                                                message: json.message
                                            },{
                                                type: json.type_message
                                            });
                                            
                                            <?php echo $table;?>.ajax.reload(null, false);
                                            <?php echo $table;?>.rows().deselect();
                                        }
                                    } );
                                }

                            }
                        },
<?php
                    }   // END button nonaktif_d
?>

<?php
    // BEGIN button nonaktif only
    // dipakai di transaksi detail
    // TRANSAKSI TIDAK BOLEH DIAKTIFKAN KEMBALI
                    if($value == 'nonaktif_o_d'){
?>
                        { 
                            text: '<i class="fa fa-trash" id="btnNonAktifOnly">&nbsp &nbsp Delete</i>', 
                            name: 'btnNonAktifOnly',
                            className: 'btn btn-danger',
                            titleAttr: 'Remove',
                            action: function ( e, dt, node, config ) {
                                var rows          = <?php echo $table;?>.rows( {selected: true} ).indexes();
                                message_is_delete = "Apakah Anda Yakin Akan Menghapus Data?";

                                is_delete = confirm(message_is_delete);
                                if(is_delete == true){
                                    <?php echo $edt;?>
                                    .edit( rows, false )
                                    .set( '<?php echo $table_name;?>.is_active', 0 )
                                    .submit();    
                                }

                            }
                        },
<?php
                    }   // END button nonaktif only
?>

<?php
                }   // END foreach
?>

            ]   // END buttons:[]
        },  
        // END generate button

<?php
    }   // END IF $arr_buttons_tools
?> 

<?php
    // BEGIN IF arr_buttons_approve
    if(count($arr_buttons_approve) > 0){
?>
    // BEGIN generate button  
                {
                    extend: 'collection',
                    name: 'btnSetApprove',
                    text: 'Approval',
                    className: 'btn btn-outline',
                    autoClose: true,
                    buttons: [
<?php
    // BEGIN foreach arr_buttons_approve
                        foreach ($arr_buttons_approve as $value) {
?>
<?php
                            if($value == 'approve'){
?>

                                { 
                                    text: '<i class="fa fa-check">&nbsp &nbsp Approve</i>', 
                                    name: 'btnApprove',
                                    className: 'btn btn-primary',
                                    titleAttr: 'Approve',
                                    action: function ( e, dt, node, config ) {

                                        $.ajax( {
                                            url: '../../../helpers/tr_fn_approve.php',
                                            dataType: 'json',
                                            type: 'POST',
                                            data: {
                                                state : 1,
                                                table_name: '<?php echo $table_name;?>',
                                                id_transaksi: id_transaksi_h,
                                            },
                                            success: function ( json ) {

                                                $.notify({
                                                    message: json.data.message
                                                },{
                                                    type: json.data.type_message
                                                });

                                                if(is_need_generate_kode == 1){
                                                    $.ajax( {
                                                        url: '../../../helpers/kode_fn_generate_a.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state                   : 1,
                                                            nama_tabel              : '<?php echo $table_name;?>',
                                                            kategori_dokumen        : kategori_dokumen,
                                                            kategori_dokumen_value  : kategori_dokumen_value,
                                                            id_transaksi            : id_transaksi_h
                                                        },
                                                        success: function ( json ) {
                                                            $.notify({
                                                                message: json.data.message
                                                            },{
                                                                type: json.data.type_message
                                                            });
                                                        }
                                                    } );
                                                }
                                                
                                                if(is_need_inventory == 1){
                                                    $.ajax( {
                                                        url: '../../../helpers/fn_inventory_r.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state          : 1,
                                                            id_transaksi_h : id_transaksi_h,
                                                            imtxxmh_kode   : imtxxmh_kode
                                                        },
                                                        success: function ( json ) {
                                                            console.log('Inventory');
                                                        }
                                                    } );
                                                }

                                                if(is_need_jurnal == 1){
                                                    $.ajax( {
                                                        url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_jv.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state           : 1,
                                                            id_transaksi_h  : id_transaksi_h,
                                                            imtxxmh_kode    : imtxxmh_kode
                                                        },
                                                        success: function ( json ) {
                                                            console.log('Jurnal');
                                                        }
                                                    } );
                                                }
                                                
                                                <?php 
                                                    if(file_exists("../../models/" . $table_name . '/' . $table_name . "_post_approve.php")) {
                                                ?>
                                                    $.ajax( {
                                                        url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_post_approve.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state           : 1,
                                                            id_transaksi_h  : id_transaksi_h
                                                        },
                                                        success: function ( json ) {
                                                            $.notify({
                                                                message: json.data.message
                                                            },{
                                                                type: json.data.type_message
                                                            });
                                                        }
                                                    } );
                                                <?php
                                                    }
                                                ?>

                                                <?php echo $table;?>.ajax.reload(null, false);

                                            }
                                        });
                                    }
                                },
<?php
                            }   // END 
?>
<?php
                            if($value == 'cancel_approve'){
?>
                                { 
                                    text: '<i class="fa fa-undo">&nbsp &nbsp Cancel Approve</i>', 
                                    name: 'btnCancelApprove',
                                    className: 'btn btn-outline',
                                    titleAttr: 'Cancel Approve',
                                    action: function ( e, dt, node, config ) {

                                        $.ajax( {
                                            url: '../../../helpers/tr_fn_approve.php',
                                            dataType: 'json',
                                            type: 'POST',
                                            data: {
                                                state : 2,
                                                table_name: '<?php echo $table_name;?>',
                                                id_transaksi: id_transaksi_h,
                                            },
                                            success: function ( json ) {

                                                $.notify({
                                                    message: json.data.message
                                                },{
                                                    type: json.data.type_message
                                                });

                                                if(is_need_generate_kode == 1){
                                                    $.ajax( {
                                                        url: '../../../helpers/kode_fn_generate_a.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state                   : 2,
                                                            nama_tabel              : '<?php echo $table_name;?>',
                                                            kategori_dokumen        : kategori_dokumen,
                                                            kategori_dokumen_value  : kategori_dokumen_value,
                                                            id_transaksi            : id_transaksi_h
                                                        },
                                                        success: function ( json ) {
                                                            $.notify({
                                                                message: json.data.message
                                                            },{
                                                                type: json.data.type_message
                                                            });
                                                        }
                                                    } );
                                                }
                                                
                                                if(is_need_inventory == 1){
                                                    $.ajax( {
                                                        url: '../../../helpers/fn_inventory_r.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state          : 2,
                                                            id_transaksi_h : id_transaksi_h,
                                                            imtxxmh_kode   : imtxxmh_kode
                                                        },
                                                        success: function ( json ) {
                                                            console.log('Inventory');
                                                        }
                                                    } );
                                                }

                                                if(is_need_jurnal == 1){
                                                    $.ajax( {
                                                        url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_jv.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state           : 2,
                                                            id_transaksi_h  : id_transaksi_h,
                                                            imtxxmh_kode    : imtxxmh_kode
                                                        },
                                                        success: function ( json ) {
                                                            console.log('Jurnal');
                                                        }
                                                    } );
                                                }
                                                
                                                <?php 
                                                    if(file_exists("../../models/" . $table_name . '/' . $table_name . "_post_approve.php")) {
                                                ?>
                                                    $.ajax( {
                                                        url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_post_approve.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state           : 2,
                                                            id_transaksi_h  : id_transaksi_h
                                                        },
                                                        success: function ( json ) {
                                                            $.notify({
                                                                message: json.data.message
                                                            },{
                                                                type: json.data.type_message
                                                            });
                                                        }
                                                    } );
                                                <?php
                                                    }
                                                ?>

                                                <?php echo $table;?>.ajax.reload(null, false);

                                            }
                                        });
                                    }
                                },
<?php
                            }
?>
<?php
                            if($value == 'void'){
?>
                                {
                                    text: '<i class="fa fa-remove">&nbsp &nbsp Void</i>', 
                                    name: 'btnVoid',
                                    className: 'btn btn-danger',
                                    titleAttr: 'Void',
                                    action: function ( e, dt, node, config ) {
                                        $.ajax( {
                                            url: '../../../helpers/tr_fn_void.php',
                                            dataType: 'json',
                                            type: 'POST',
                                            data: {
                                                state : -9,
                                                table_name: '<?php echo $table_name;?>',
                                                id_transaksi: id_transaksi_h,
                                            },
                                            success: function ( json ) {
                                                
                                                if(is_need_inventory == 1){
                                                    $.ajax( {
                                                        url: '../../../helpers/fn_inventory_r.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state          : -9,
                                                            id_transaksi_h : id_transaksi_h,
                                                            imtxxmh_kode   : imtxxmh_kode
                                                        },
                                                        success: function ( json ) {
                                                            console.log('Inventory');
                                                        }
                                                    } );
                                                }

                                                if(is_need_jurnal == 1){
                                                    $.ajax( {
                                                        url: '../../models/'+ "<?php echo $table_name . '/' . $table_name;?>" +'_jv.php',
                                                        dataType: 'json',
                                                        type: 'POST',
                                                        data: {
                                                            state           : -9,
                                                            id_transaksi_h  : id_transaksi_h,
                                                            imtxxmh_kode    : imtxxmh_kode
                                                        },
                                                        success: function ( json ) {
                                                            console.log('Jurnal');
                                                        }
                                                    } );
                                                }
                                                
                                                <?php echo $table;?>.ajax.reload(null, false);
                                            }
                                        } );
                                    }
                                }

<?php
                            }
?>
<?php
                        } // END foreach arr_buttons_approve
?>
                    ]
                },
    // END generate button
<?php
    }   // END IF $arr_buttons_approve
?>