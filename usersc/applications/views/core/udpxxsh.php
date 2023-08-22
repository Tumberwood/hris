<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
	$nama_tabel       = 'udpxxsh';
    $nama_tabels_d    = [];
    $nama_tabels_d[0] = 'udpbrsd';
    $nama_tabels_d[1] = 'udpeysd';
    $nama_tabels_d[2] = 'ucudasd';
?>

<!-- begin content here -->

<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-content">
                <div class="table-responsive">
                    <table id="tbludpxxsh" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Akses Data</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
				</div>
			</div>
		</div>
	</div>
</div>

<legend>Detail Hak Akses</legend>
<div class="row">
	<div class="col">
		<div class="ibox ">
			<div class="ibox-title">
				<h5><?= (($pageTitle != '') ? $pageTitle : ''); ?></h5>
			</div>
			<div class="ibox-content">

                <div class="tabs-container">
                    <ul class="nav nav-tabs" role="tablist">
                        <li><a class="nav-link active" data-toggle="tab" href="#tabudpbrsd">Cabang</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tabudpeysd"> Employee Type</a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#tabucudasd"> CRUD</a></li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" id="tabudpbrsd" class="tab-pane active">
                            <div class="panel-body">
                                <table id="tbludpbrsd" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>id_udpxxsh</th>
                                            <th>Cabang</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" id="tabudpeysd" class="tab-pane">
                            <div class="panel-body">
                                <table id="tbludpeysd" class="table table-striped table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>id_udpxxsh</th>
                                            <th>Employee Type</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" id="tabucudasd" class="tab-pane">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="tblucudasd" class="table table-striped table-bordered table-hover" width="100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>id_udpxxsh</th>
                                                <th>Pages</th>
                                                <th>C</th>
                                                <th>U</th>
                                                <th>D</th>
                                                <th>A</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!-- END of tab-content -->
                </div> <!-- END of tabs-container -->
			</div>
		</div>
	</div>
</div>


<!-- BEGIN JS -->
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>
<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>
<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>


<!-- BEGIN datatables here -->
<script type="text/javascript">
		// ------------- default variable, do not erase
		var edtudpxxsh, tbludpxxsh, show_inactive_status = 0, id_udpxxsh;
		var edtudpbrsd, tbludpbrsd, show_inactive_status_udpbrsd = 0, udpbrsd;
		var edtudpeysd, tbludpeysd, show_inactive_status_udpeysd = 0, udpeysd;
        var edtucudasd, tblucudasd, show_inactive_status_ucudasd = 0, ucudasd;
		// ------------- end of default variable

        var id_users_old = 0;
        var id_users;
        var id_gbrxxmh_old = 0;
        var id_heyxxmh_old = 0;
		
		$(document).ready(function() {
        
            // start header
			edtudpxxsh = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/core/udpxxsh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status = show_inactive_status;
					}
				},
				table: "#tbludpxxsh",
				fields: [ 
					{
						label: "start_on",
						name: "start_on",
						type: "hidden"
					},	{
						label: "finish_on",
						name: "finish_on",
						type: "hidden"
					},	{
						label: "nama_tabel",
						name: "nama_tabel",
						def: "udpxxsh",
						type: "hidden"
					},  {
                        label: "Username",
						name: "udpxxsh.id_users",
                        type: "select2",
                        opts: {
                            placeholder : "Select",
                            allowClear: true,
                            multiple: false,
                            ajax: {
                                url: "../../models/core/users_fn_opt.php",
                                dataType: 'json',
                                data: function (params) {
                                    var query = {
                                        id_users_old: id_users_old,
                                        search: params.term || '',
                                        page: params.page || 1
                                    }
                                        return query;
                                },
                                processResults: function (data, params) {
                                    return {
                                        results: data.results,
                                        pagination: {
                                            more: true
                                        }
                                    };
                                },
                                cache: true,
                                minimumInputLength: 1,
                                maximum: 10,
                                delay: 500,
                                maximumSelectionLength: 5,
                                minimumResultsForSearch: -1,
                            },
                        }
                    },  {
						label: "Akses Data",
						name: "udpxxsh.data_permission",
						type: "select",
						def: 1,
						options: [
							{ "label": "Self", "value": 1 },
							{ "label": "All", "value": 0 }
						]
					},  {
						label: "Boleh Setting",
						name: "udpxxsh.is_setting",
						type: "select",
						def: 0,
						options: [
							{ "label": "Ya", "value": 1 },
                            { "label": "Tidak", "value": 0 }
						]
					}, 	{
						label: "Keterangan",
						name: "udpxxsh.keterangan",
						type: "textarea"
					}
				]
			} );
			
			edtudpxxsh.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtudpxxsh.field('start_on').val(start_on);
				
				if (action == 'create'){
					tbludpxxsh.rows().deselect();
					edtudpxxsh.field('udpxxsh.id_users').enable();
				}else if(action == 'edit'){
                    edtudpxxsh.field('udpxxsh.id_users').disable();
				}
			});

            edtudpxxsh.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtudpxxsh.field('finish_on').val(finish_on);
			});
			
			edtudpxxsh.on( 'postSubmit', function (e, json, data, action, xhr) {
				tbludpxxsh.ajax.reload(null,false);
				tblucudasd.ajax.reload(null,false);
				if(action == 'remove'){
					tbludpbrsd.ajax.reload(null,false);
				}
			} );
			
			tbludpxxsh = $('#tbludpxxsh').DataTable( {
				ajax: {
					url: "../../models/core/udpxxsh.php",
					type: 'POST',
					data: function (d){
						d.show_inactive_status = show_inactive_status;
					}
				},
				order: [[ 1, "asc" ]],
				columns: [
					{ data: "udpxxsh.id",visible:false },
					{ data: "users.username" },
					{ data: "hemxxmh.nama" },
					{ 
						data: "udpxxsh.data_permission",
						render: function (data){
							if (data == 0){
								return 'All';
							}else if (data == 1){
								return 'Self';
							}else{
								return '';
							}
						}
					},
					{ data: "udpxxsh.keterangan" }
				],
				buttons: [
                    // BEGIN breaking generate button
					<?php
						$id_table    = 'id_udpxxsh';
						$table       = 'tbludpxxsh';
						$edt         = 'edtudpxxsh';
						$show_status = '_udpxxsh';
						$table_name  = $nama_tabel;

                        $arr_buttons_tools      = ['show_hide','copy','excel','colvis'];;
                        $arr_buttons_action     = ['create', 'edit', 'nonaktif_h'];
                        $arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
				],
				rowCallback: function( row, data, index ) {
					if ( data.udpxxsh.is_active == 0 ) {
						$('td', row).addClass('text-danger');
					}
				}
			} );

            tbludpxxsh.on( 'init', function () {
				// atur hak akses
				tbl_details = [tbludpbrsd, tbludpeysd, tblucudasd];
				CekInitHeaderHD(tbludpxxsh, tbl_details);

                tblucudasd.button( 'btnRefreshPages:name' ).disable();
			} );
			
			tbludpxxsh.on( 'select', function( e, dt, type, indexes ) {
                udpxxsh_data = tbludpxxsh.row( { selected: true } ).data().udpxxsh;

				id_udpxxsh     = udpxxsh_data.id;
				id_transaksi_h = id_udpxxsh; // dipakai untuk general
				is_approve     = udpxxsh_data.is_approve;
				is_nextprocess = udpxxsh_data.is_nextprocess;
				is_jurnal      = udpxxsh_data.is_jurnal;
				is_active      = udpxxsh_data.is_active;
                id_users       = udpxxsh_data.id_users;
                id_users_old   = udpxxsh_data.id_users;
				
				// atur hak akses
				tbl_details = [tbludpbrsd, tbludpeysd, tblucudasd];
				CekSelectHeaderHD(tbludpxxsh, tbl_details);

                tblucudasd.button( 'btnRefreshPages:name' ).enable();

			} );
			
			tbludpxxsh.on( 'deselect', function () {
				// reload dipanggil di function CekDeselectHeader
				id_udpxxsh = 0;
                id_users = 0;
                id_users_old = 0;

				// atur hak akses
				tbl_details = [tbludpbrsd, tbludpeysd, tblucudasd];
				CekDeselectHeaderHD(tbludpxxsh, tbl_details);

                tblucudasd.button( 'btnRefreshPages:name' ).disable();
			} );

			
            // end header
			
            // start detail cabang
            edtudpbrsd = new $.fn.dataTable.Editor( {
                ajax: {
                    url: "../../models/core/udpbrsd.php",
                    type: 'POST',
                    data: function (d){
                        d.show_inactive_status = show_inactive_status;
                        d.id_udpxxsh = id_udpxxsh;
                    }
                },
                table: "#tbludpbrsd",
                fields: [ 
                    {
                        label: "start_on",
                        name: "start_on",
                        type: "hidden"
                    },	{
						label: "finish_on",
						name: "finish_on",
						type: "hidden"
					},	{
                        label: "nama_tabel",
                        name: "nama_tabel",
                        def: "udpbrsd",
                        type: "hidden"
                    },	{
                        label: "id_udpxxsh",
                        name: "udpbrsd.id_udpxxsh",
                        type: "hidden"
                    },	{
                        label: "Cabang",
                        name: "udpbrsd.id_gbrxxmh",
                        type: "select2",
                        opts: {
                            placeholder : "Select",
                            allowClear: true,
                            multiple: false,
                            ajax: {
                                url: "../../models/core/gbrxxmh_fn_opt.php",
                                dataType: 'json',
                                data: function (params) {
                                    var query = {
                                        id_gbrxxmh_old: id_gbrxxmh_old,
                                        search: params.term || '',
                                        page: params.page || 1
                                    }
                                        return query;
                                },
                                processResults: function (data, params) {
                                    return {
                                        results: data.results,
                                        pagination: {
                                            more: true
                                        }
                                    };
                                },
                                cache: true,
                                minimumInputLength: 1,
                                maximum: 10,
                                delay: 500,
                                maximumSelectionLength: 5,
                                minimumResultsForSearch: -1,
                            },
                        }
                    }, 	{
                        label: "Keterangan",
                        name: "udpbrsd.keterangan",
                        type: "textarea"
                    }
                ]
            } );
            
            edtudpbrsd.on( 'preOpen', function( e, mode, action ) {
                edtudpbrsd.field('udpbrsd.id_udpxxsh').val(id_udpxxsh);
                
                start_on = moment().format('YYYY-MM-DD HH:mm:ss');
                edtudpbrsd.field('start_on').val(start_on);
                
                if (action == 'create'){
                    tbludpbrsd.rows().deselect();
                }
            });

            edtudpbrsd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtudpbrsd.field('finish_on').val(finish_on);
			});
            
            tbludpbrsd = $('#tbludpbrsd').DataTable( {
                ajax: {
                    url: "../../models/core/udpbrsd.php",
                    type: 'POST',
                    data: function (d){
                        d.show_inactive_status = show_inactive_status;
                        d.id_udpxxsh = id_udpxxsh;
                    }
                },
                order: [[ 1, "asc" ]],
                columns: [
                    { data: "udpbrsd.id",visible:false },
                    { data: "udpbrsd.id_udpxxsh",visible:false },
                    { data: "gbrxxmh.nama" },
                    { data: "udpbrsd.keterangan" }
                ],
                buttons: [
                    // BEGIN breaking generate button
					<?php
						$id_table    = 'id_udpbrsd';
						$table       = 'tbludpbrsd';
						$edt         = 'edtudpbrsd';
						$show_status = '_udpbrsd';
						$table_name  = $nama_tabels_d[0];

                        $arr_buttons_tools      = ['show_hide','copy','excel','colvis'];;
                        $arr_buttons_action     = ['create', 'edit', 'nonaktif_d'];
                        $arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
                ],
                rowCallback: function( row, data, index ) {
                    if ( data.udpbrsd.is_active == 0 ) {
                        $('td', row).addClass('text-danger');
                    }
                }
            } );

            tbludpbrsd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tbludpxxsh, tbludpbrsd, 'udpbrsd' );
				CekDrawDetailHDFinal(tbludpxxsh);
			} );

			tbludpbrsd.on( 'select', function( e, dt, type, indexes ) {
				// shorting variable
				udpbrsd_data = tbludpbrsd.row( { selected: true } ).data().udpbrsd;

				// set variable on select
				id_udpbrsd        = udpbrsd_data.id;
				id_transaksi_d    = id_udpbrsd; // dipakai untuk general
				is_active_d       = udpbrsd_data.is_active;

                id_gbrxxmh_old = udpbrsd_data.id_gbrxxmh; 

				// atur hak akses
				CekSelectDetailHD(tbludpxxsh, tbludpbrsd );
			} );

			tbludpbrsd.on( 'deselect', function() {
				// set variable on deselect
				id_udpbrsd  = 0;
				is_active_d = 0;

                id_gbrxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tbludpxxsh, tbludpbrsd );
			} );

            
            // end detail cabang

            // start detail employee type
            edtudpeysd = new $.fn.dataTable.Editor( {
                ajax: {
                    url: "../../models/core/udpeysd.php",
                    type: 'POST',
                    data: function (d){
                        d.show_inactive_status = show_inactive_status;
                        d.id_udpxxsh = id_udpxxsh;
                    }
                },
                table: "#tbludpeysd",
                fields: [ 
                    {
                        label: "start_on",
                        name: "start_on",
                        type: "hidden"
                    },	{
						label: "finish_on",
						name: "finish_on",
						type: "hidden"
					},	{
                        label: "nama_tabel",
                        name: "nama_tabel",
                        def: "udpeysd",
                        type: "hidden"
                    },	{
                        label: "id_udpxxsh",
                        name: "udpeysd.id_udpxxsh",
                        type: "hidden"
                    },	{
                        label: "Employee Type",
                        name: "udpeysd.id_heyxxmh",
                        type: "select2",
                        opts: {
                            placeholder : "Select",
                            allowClear: true,
                            multiple: false,
                            ajax: {
                                url: "../../models/heyxxmh/heyxxmh_fn_opt.php",
                                dataType: 'json',
                                data: function (params) {
                                    var query = {
                                        id_heyxxmh_old: id_heyxxmh_old,
                                        search: params.term || '',
                                        page: params.page || 1
                                    }
                                        return query;
                                },
                                processResults: function (data, params) {
                                    return {
                                        results: data.results,
                                        pagination: {
                                            more: true
                                        }
                                    };
                                },
                                cache: true,
                                minimumInputLength: 1,
                                maximum: 10,
                                delay: 500,
                                maximumSelectionLength: 5,
                                minimumResultsForSearch: -1,
                            },
                        }
                    }, 	{
                        label: "Keterangan",
                        name: "udpeysd.keterangan",
                        type: "textarea"
                    }
                ]
            } );
            
            edtudpeysd.on( 'preOpen', function( e, mode, action ) {
                edtudpeysd.field('udpeysd.id_udpxxsh').val(id_udpxxsh);
                
                start_on = moment().format('YYYY-MM-DD HH:mm:ss');
                edtudpeysd.field('start_on').val(start_on);
                
                if (action == 'create'){
                    tbludpeysd.rows().deselect();
                }
            });

            edtudpeysd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtudpeysd.field('finish_on').val(finish_on);
			});
            
            tbludpeysd = $('#tbludpeysd').DataTable( {
                ajax: {
                    url: "../../models/core/udpeysd.php",
                    type: 'POST',
                    data: function (d){
                        d.show_inactive_status = show_inactive_status;
                        d.id_udpxxsh = id_udpxxsh;
                    }
                },
                order: [[ 1, "asc" ]],
                columns: [
                    { data: "udpeysd.id",visible:false },
                    { data: "udpeysd.id_udpxxsh",visible:false },
                    { data: "heyxxmh.nama" },
                    { data: "udpeysd.keterangan" }
                ],
                buttons: [
                    // BEGIN breaking generate button
					<?php
						$id_table    = 'id_udpeysd';
						$table       = 'tbludpeysd';
						$edt         = 'edtudpeysd';
						$show_status = '_udpeysd';
						$table_name  = $nama_tabels_d[0];

                        $arr_buttons_tools      = ['show_hide','copy','excel','colvis'];;
                        $arr_buttons_action     = ['create', 'edit', 'nonaktif_d'];
                        $arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button
                ],
                rowCallback: function( row, data, index ) {
                    if ( data.udpeysd.is_active == 0 ) {
                        $('td', row).addClass('text-danger');
                    }
                }
            } );

            tbludpeysd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 1;
				CekDrawDetailHD(tbludpxxsh, tbludpeysd, 'udpeysd' );
				CekDrawDetailHDFinal(tbludpxxsh);
			} );

			tbludpeysd.on( 'select', function( e, dt, type, indexes ) {
				// shorting variable
				udpeysd_data = tbludpeysd.row( { selected: true } ).data().udpeysd;

				// set variable on select
				id_udpeysd        = udpeysd_data.id;
				id_transaksi_d    = id_udpeysd; // dipakai untuk general
				is_active_d       = udpeysd_data.is_active;

                id_heyxxmh_old = udpeysd_data.id_heyxxmh; console.log(id_heyxxmh_old);

				// atur hak akses
				CekSelectDetailHD(tbludpxxsh, tbludpeysd );
			} );

			tbludpeysd.on( 'deselect', function() {
				// set variable on deselect
				id_udpeysd  = 0;
				is_active_d = 0;

                id_heyxxmh_old = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tbludpxxsh, tbludpeysd );
			} );

            
            // end detail employee type

            // start detail crud
            edtucudasd = new $.fn.dataTable.Editor( {
                ajax: {
                    url: "../../models/core/ucudasd.php",
                    type: 'POST',
                    data: function (d){
                        d.show_inactive_status = show_inactive_status;
                        d.id_udpxxsh = id_udpxxsh;
                    }
                },
                table: "#tblucudasd",
                fields: [ 
                    {
                        label: "start_on",
                        name: "start_on",
                        type: "hidden"
                    },	{
						label: "finish_on",
						name: "finish_on",
						type: "hidden"
					},	{
                        label: "nama_tabel",
                        name: "nama_tabel",
                        def: "ucudasd",
                        type: "hidden"
                    },	{
                        label: "id_udpxxsh",
                        name: "ucudasd.id_udpxxsh",
                        type: "hidden"
                    },	{
                        label: "Create",
                        name: "ucudasd.hak_c",
                        type: "select",
                        placeholder: "Pilih",
                        options: [
                            { "label": "Ya", "value": 1 },
                            { "label": "Tidak", "value": 0 }
                        ]
                    },	{
                        label: "Update",
                        name: "ucudasd.hak_u",
                        type: "select",
                        placeholder: "Pilih",
                        options: [
                            { "label": "Ya", "value": 1 },
                            { "label": "Tidak", "value": 0 }
                        ]
                    },	{
                        label: "Delete",
                        name: "ucudasd.hak_d",
                        type: "select",
                        placeholder: "Pilih",
                        options: [
                            { "label": "Ya", "value": 1 },
                            { "label": "Tidak", "value": 0 }
                        ]
                    },	{
                        label: "Approve",
                        name: "ucudasd.hak_a",
                        type: "select",
                        placeholder: "Pilih",
                        options: [
                            { "label": "Ya", "value": 1 },
                            { "label": "Tidak", "value": 0 }
                        ]
                    }
                ]
            } );
            
            edtucudasd.on( 'preOpen', function( e, mode, action ) {
                edtucudasd.field('ucudasd.id_udpxxsh').val(id_udpxxsh);
                
                start_on = moment().format('YYYY-MM-DD HH:mm:ss');
                edtucudasd.field('start_on').val(start_on);
                
                if (action == 'create'){
                    tblucudasd.rows().deselect();
                }
            });

            edtucudasd.on('initSubmit', function(e, action) {
				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edtucudasd.field('finish_on').val(finish_on);
			});
            
            tblucudasd = $('#tblucudasd').DataTable( {
                ajax: {
                    url: "../../models/core/ucudasd.php",
                    type: 'POST',
                    data: function (d){
                        d.show_inactive_status = show_inactive_status;
                        d.id_udpxxsh = id_udpxxsh;
                    }
                },
                order: [[ 1, "asc" ]],
                columns: [
                    { data: "ucudasd.id",visible:false },
                    { data: "ucudasd.id_udpxxsh",visible:false },
                    { data: "pages.title"},
                    { 
                        data: "ucudasd.hak_c",
                        render: function (data){
                            if (data == 1){
                                return '<i class="fa fa-check text-navy"></i>';
                            }else if(data == 0){
                                return '<i class="fa fa-remove text-danger"></i>';
                            }
                        }
                    },
                    { 
                        data: "ucudasd.hak_u" ,
                        render: function (data){
                            if (data == 1){
                                return '<i class="fa fa-check text-navy"></i>';
                            }else if(data == 0){
                                return '<i class="fa fa-remove text-danger"></i>';
                            }
                        }
                    },
                    { 
                        data: "ucudasd.hak_d" ,
                        render: function (data){
                            if (data == 1){
                                return '<i class="fa fa-check text-navy"></i>';
                            }else if(data == 0){
                                return '<i class="fa fa-remove text-danger"></i>';
                            }
                        }
                    },
                    { 
                        data: "ucudasd.hak_a" ,
                        render: function (data){
                            if (data == 1){
                                return '<i class="fa fa-check text-navy"></i>';
                            }else if(data == 0){
                                return '<i class="fa fa-remove text-danger"></i>';
                            }
                        }
                    }
                ],
                buttons: [
                    // BEGIN breaking generate button
					<?php
						$id_table    = 'id_ucudasd';
						$table       = 'tblucudasd';
						$edt         = 'edtucudasd';
						$show_status = '_ucudasd';
						$table_name  = $nama_tabels_d[1];

                        $arr_buttons_tools      = ['show_hide','copy','excel','colvis'];;
                        $arr_buttons_action     = ['create', 'edit', 'nonaktif_d'];
                        $arr_buttons_approve 	= [];
						include $abs_us_root.$us_url_root. 'usersc/helpers/button_fn_generate.php'; 
					?>
					// END breaking generate button

                    {
                        name: 'btnRefreshPages',
                        text: '<i class="fa fa-refresh"></i>',
                        className: 'btn btn-outline',
                        titleAttr: 'Recheck',
                        action: function ( e, dt, node, config ) {
                            $.ajax( {
                                url: '../../models/core/ucudasd_fn_generate.php',
                                dataType: 'json',
                                type: 'POST',
                                data: {
                                    id_udpxxsh: id_udpxxsh,
                                    id_users: id_users
                                },
                                success: function ( json ) {
                                    $.notify({
                                        message: json.message
                                    },{
                                        type: json.type_message
                                    });
                                    tblucudasd.ajax.reload(null,false);
                                }
                            } );
                        }
                    }
                    // END additional button

                ],
                rowCallback: function( row, data, index ) {
                    if ( data.ucudasd.is_active == 0 ) {
                        $('td', row).addClass('text-danger');
                    }
                }
            } );

            tblucudasd.on( 'draw', function( e, settings ) { 
				// atur hak akses
				cek_c_detail= 0;
				CekDrawDetailHD(tbludpxxsh, tblucudasd, 'ucudasd' );
				CekDrawDetailHDFinal(tbludpxxsh);
			} );

			tblucudasd.on( 'select', function( e, dt, type, indexes ) {
				id_ucudasd     = tblucudasd.row( { selected: true } ).data().ucudasd.id;
				id_transaksi_d = id_ucudasd; // dipakai untuk general
				is_active_d    = tblucudasd.row( { selected: true } ).data().ucudasd.is_active;
				
				// atur hak akses
				CekSelectDetailHD(tbludpxxsh, tblucudasd );
			} );

			tblucudasd.on( 'deselect', function() {
				id_ucudasd = '';
				is_active_d = 0;
				
				// atur hak akses
				CekDeselectDetailHD(tbludpxxsh, tblucudasd );
			} );
            // end detail crud
			
		} );// end of document.ready
	
	</script>

<!-- END datatables here -->

<!-- end content here -->

<!-- do not erase -->
<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
