<script>

    start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
    end_date   = moment($('#start_date').val()).format('YYYY-MM-DD');

    function htsprtd_get_hemxxmh_kode (){
        $.ajax( {
            url: "../../models/htsprtd/htsprtd_fn_hemxxmh.php",
            dataType: 'json',
            async: false,
            type: 'POST',
            data: {
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                kode_finger_temp = json.data.hemxxmh.kode_finger;
                if(kode_finger_temp.toString().length == 1){
                    kode_finger = '000' + kode_finger_temp;
                }else if(kode_finger_temp.toString().length == 2){
                    kode_finger = '00' + kode_finger_temp;
                }else if(kode_finger_temp.toString().length == 3){
                    kode_finger = '0' + kode_finger_temp;
                }else if(kode_finger_temp.toString().length == 4){
                    kode_finger = kode_finger_temp;
                }
                
            }
        } );
    }
    function jamMakanManual(){
        nama = edthtsprtd.field('htsprtd.nama').val();
        var originalDate = edthtsprtd.field('htsprtd.tanggal').val();
        var tanggal = moment(originalDate).format('YYYY-MM-DD');
        id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();

        $.ajax( {
            url: "../../models/htsprtd/fn_jam_istirahat.php",
            dataType: 'json',
            async: false,
            type: 'POST',
            data: {
                tanggal: tanggal,
                id_hemxxmh: id_hemxxmh
            },
            success: function ( json ) {
                jam = json.data.jam_istirahat.jam;
                console.log(jam);
                if (jam == undefined) {
                    edthtsprtd.field('htsprtd.jam').val('');
                } else {
                    edthtsprtd.field('htsprtd.jam').val(jam);
                }

            }
        } );
    }

    function unikMakan(jam) {
        htsprtd_get_hemxxmh_kode();
        tanggal = edthtsprtd.field('htsprtd.tanggal').val();
        let tanggal_ymd = moment(tanggal).format('YYYY-MM-DD');
        // id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();
        nama = edthtsprtd.field('htsprtd.nama').val();
        console.log(jam);

        $.ajax( {
            url: "../../models/htsprtd/fn_cek_unik_makan.php",
            dataType: 'json',
            async: false,
            type: 'POST',
            data: {
                tanggal_ymd: tanggal_ymd,
                kode_finger: kode_finger,
                jam: jam
            },
            success: function ( json ) {
                status = json.data.peg_makan.status;
                range_awal = json.data.peg_makan.range_awal;
                range_akhir = json.data.peg_makan.range_akhir;
                console.log(json.data.peg_makan.range_awal);
                console.log(range_akhir);

                if (nama == "makan" || nama == "makan manual") {
                    // console.log(c_peg);
                    if(status == "invalid"){
                        edthtsprtd.field('htsprtd.id_hemxxmh').error( 'Pegawai Sudah Pernah Diinput pada Mesin Makan/Makan Manual!' );
                    }
                }
            }
        } );
    }

	function addCeklok(id_hemxxmh) {
		
/////////// START OF CEKLOK ////////////

edthtsprtd = new $.fn.dataTable.Editor( {
				ajax: {
					url: "../../models/htsprtd/htsprtd_report.php",
					type: 'POST',
					data: function (d){
					}
				},
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
						def: "htsprtd",
						type: "hidden"
					},	{
						label: "Active Status",
						name: "htsprtd.is_active",
                        type: "hidden",
						def: 1
					},	
					{
						label: "htsprtd.kode",
						name: "htsprtd.kode",
                        type: "hidden"
					},
					{
						label: "Mesin <sup class='text-danger'>*<sup>",
						name: "htsprtd.nama",
						type: "select",
						placeholder : "Select",
						options: [
							// { "label": "Istirahat", "value": "istirahat" },
							// { "label": "Makan", "value": "makan" },
							{ "label": "Makan Manual", "value": "makan manual" },
							{ "label": "Outsourcing", "value": "os" },
							{ "label": "PMI", "value": "pmi" },
							{ "label": "Staff", "value": "staff" }
						]
					},
					{
						label: "Employee <sup class='text-danger'>*<sup>",
						name: "htsprtd.id_hemxxmh",
						type: "select2",
						id: "peg_ceklok",
						opts: {
							placeholder : "Select",
							allowClear: true,
							multiple: false,
							ajax: {
								url: "../../models/hemxxmh/hemxxmh_fn_opt.php",
								dataType: 'json',
								data: function (params) {
									var query = {
										id_hemxxmh_old: id_hemxxmh_old,
										search: params.term || '',
										page: params.page || 1
									}
										return query;
								},
								processResults: function (data, params) {
									var options = data.results.map(function (result) {
										return {
											id: result.id,
											text: result.text
										};
									});

									//add by ferry agar auto select 07 sep 23
									if (params.page && params.page === 1) {
										$('#peg_ceklok').empty().select2({ data: options });
									} else {
                                        $('#peg_ceklok').append(new Option(options[0].text, options[0].id, false, false)).trigger('change');
									}

									return {
										results: options,
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
					},
					{
						label: "Tanggal <sup class='text-danger'>*<sup>",
						name: "htsprtd.tanggal",
						type: "datetime",
						def: function () { 
							return moment($('#start_date').val()).format('DD MMM YYYY'); 
						},
						opts:{
							minDate: new Date('1900-01-01'),
							firstDay: 0
						},
						format: 'DD MMM YYYY'
					},
					{
						label: "Jam <sup class='text-danger'>*<sup>",
						name: "htsprtd.jam",
						type: "datetime",
						format: 'HH:mm'
					},
					{
						label: "Keterangan <sup class='text-danger'>*<sup>",
						name: "htsprtd.keterangan",
						type: "textarea"
					}
				]
			} );

			edthtsprtd.on( 'preOpen', function( e, mode, action ) {
				start_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('start_on').val(start_on);
				id_hemxxmh_old = id_hemxxmh;
				edthtsprtd.field('htsprtd.id_hemxxmh').val(id_hemxxmh_old);
				
			});

			edthtsprtd.on("open", function (e, mode, action) {
				$(".modal-dialog").addClass("modal-lg");
				
				$('#peg_ceklok').select2('open');

				setTimeout(function() {
					$('#peg_ceklok').select2('close');
				}, 5);
			});

			edthtsprtd.dependent( 'htsprtd.nama', function ( val, data, callback ) {
				nama = edthtsprtd.field('htsprtd.nama').val();
				if (nama ==  "makan manual") {
					jamMakanManual();
					// edthtsprtd.field('htsprtd.jam').disable();
				}else {
            		edthtsprtd.field('htsprtd.jam').enable();
				}
				return {}
			}, {event: 'keyup change'});

			edthtsprtd.dependent( 'htsprtd.id_hemxxmh', function ( val, data, callback ) {
				nama = edthtsprtd.field('htsprtd.nama').val();
				if (nama ==  "makan manual") {
					jamMakanManual();
					// edthtsprtd.field('htsprtd.jam').disable();
				}else {
            		edthtsprtd.field('htsprtd.jam').enable();
					edthtsprtd.field('htsprtd.jam').val('');
				}
				return {}
			}, {event: 'keyup change'});

			edthtsprtd.dependent( 'htsprtd.tanggal', function ( val, data, callback ) {
				nama = edthtsprtd.field('htsprtd.nama').val();
				if (nama ==  "makan manual") {
					jamMakanManual();
					// edthtsprtd.field('htsprtd.jam').disable();
				}else {
            		edthtsprtd.field('htsprtd.jam').enable();
					edthtsprtd.field('htsprtd.jam').val('');
				}
				return {}
			}, {event: 'keyup change'});

            edthtsprtd.on( 'preSubmit', function (e, data, action) {
				if(action != 'remove'){
					// BEGIN of validasi htsprtd.nama
					nama = edthtsprtd.field('htsprtd.nama').val();
					if(!nama || nama == ''){
						edthtsprtd.field('htsprtd.nama').error( 'Wajib diisi!' );
					}
					// END of validasi htsprtd.nama

					// BEGIN of validasi htsprtd.id_hemxxmh
					id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();
					if ( ! edthtsprtd.field('htsprtd.id_hemxxmh').isMultiValue() ) {
						if(!id_hemxxmh || id_hemxxmh == ''){
							edthtsprtd.field('htsprtd.id_hemxxmh').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsprtd.id_hemxxmh

					// BEGIN of validasi htsprtd.tanggal
					if ( ! edthtsprtd.field('htsprtd.tanggal').isMultiValue() ) {
						tanggal = edthtsprtd.field('htsprtd.tanggal').val();
						if(!tanggal || tanggal == ''){
							edthtsprtd.field('htsprtd.tanggal').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsprtd.tanggal

					jam = edthtsprtd.field('htsprtd.jam').val();
					// unikMakan(jam);
					if (nama != "makan manual") {
						// BEGIN of validasi htsprtd.jam
						if ( ! edthtsprtd.field('htsprtd.jam').isMultiValue() ) {
							if(!jam || jam == ''){
								edthtsprtd.field('htsprtd.jam').error( 'Wajib diisi!' );
							}
						}
						// END of validasi htsprtd.jam
					} else {
						if (jam == '' || jam == null) {
							edthtsprtd.field('htsprtd.jam').error( 'Jam Kosong Karena Jadwal Belum Dibuat!' );
						}
					}

					// BEGIN of validasi htsprtd.keterangan
					if ( ! edthtsprtd.field('htsprtd.keterangan').isMultiValue() ) {
						keterangan = edthtsprtd.field('htsprtd.keterangan').val();
						if(!keterangan || keterangan == ''){
							edthtsprtd.field('htsprtd.keterangan').error( 'Wajib diisi!' );
						}
					}
					// END of validasi htsprtd.keterangan
				}
				
				if ( edthtsprtd.inError() ) {
					return false;
				}
			});
			
			edthtsprtd.on('initSubmit', function(e, action) {
				// update kode finger
				id_hemxxmh = edthtsprtd.field('htsprtd.id_hemxxmh').val();
				htsprtd_get_hemxxmh_kode();
				console.log(kode_finger);
				edthtsprtd.field('htsprtd.kode').val(kode_finger);

				finish_on = moment().format('YYYY-MM-DD HH:mm:ss');
				edthtsprtd.field('finish_on').val(finish_on);
			});

			edthtsprtd.on( 'postSubmit', function (e, json, data, action, xhr) {
				generateTable(counter);
			});
//////////// END OF CEKLOK /////////////
	}

</script>