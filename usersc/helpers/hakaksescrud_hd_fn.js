// ----- README -----
//  ReloadTableDetail
//      Digunakan untuk reload all Table Detail
//  CekInitHeader
//      Digunakan untuk melakukan pengecekan Table Header

var status_select;
var arr_is_valid_cek_detail = [];
var c_is_valid_cek_detail = 0;

function ReloadTableDetail(){
    // reload table detail
    for (let i = 0; i < tbl_details.length; i++) {
        tbl_details[i].ajax.reload(null,false);
    }
};

// ----- BEGIN transaksi header -----

function CekInitHeaderH(tbl_header){
    // digunakan untuk inisiasi table di halaman yang hanya berisi table header saja
    if(hak_c == 1){
        tbl_header.button( 'btnCreate:name' ).enable();
    }else{
        tbl_header.button( 'btnCreate:name' ).disable();
    }

    tbl_header.button( 'btnNonAktif:name' ).disable();
    tbl_header.button( 'btnSetApprove:name' ).disable();
};

function CekSelectHeaderH(tbl_header){
    
    if(is_active == 0){
        tbl_header.button( 'btnView:name' ).disable();
        tbl_header.button( 'btnEdit:name' ).disable();
        tbl_header.button( 'btnNonAktif:name' ).text('<i class="fa fa-undo">&nbsp &nbsp Restore</i>');
        tbl_header.button( 'btnNonAktif:name' ).enable();
    }else{
        tbl_header.button( 'btnView:name' ).enable();
        tbl_header.button( 'btnNonAktif:name' ).text('<i class="fa fa-trash">&nbsp &nbsp Delete</i>');

        if(is_defaultprogram == 1){
            tbl_header.button( 'btnEdit:name' ).disable();
            tbl_header.button( 'btnNonAktif:name' ).disable();
        }else{

            // BEGIN cek hak akses update
            if(hak_u == 1){
                if (is_approve == 1 || is_approve == -9){
                    // HEADER
                    // tidak boleh edit atau delete
                    tbl_header.button( 'btnEdit:name' ).disable();
                }else{
                    // HEADER
                    // boleh ada edit data header
                    tbl_header.button( 'btnEdit:name' ).enable(); 
                }
            }else{
                tbl_header.button( 'btnEdit:name' ).disable();
            }
            // END cek hak akses update

            // BEGIN cek hak akses delete
            if(is_need_delete == 1){
                if(hak_d == 1){
                    // jika ada hak akses delete
                    if (is_approve == 1 || is_approve == -9){
                        tbl_header.button( 'btnNonAktif:name' ).disable();
                    }else{
                        tbl_header.button( 'btnNonAktif:name' ).enable();
                    }
                }else{
                    // jika TIDAK ada hak akses delete
                    tbl_header.button( 'btnNonAktif:name' ).disable();
                }
            }
            // END cek hak akses delete
        }

        // BEGIN cek apakah form perlu approval
        if(is_need_approval == 1){
            if(hak_a == 1){
                // jika ada hak akses approval
                if(is_nextprocess > 0){
                    // jika sudah ditarik ke proses selanjutnya
                    // ***** REMARK *****
                    // mungkin perlu ditambahkan parameter lain

                    tbl_header.button( 'btnSetApprove:name' ).disable();
                }else{
                    // jika belum ditarik ke proses selanjutnya
                    /**
                     * status approve
                     * 0: draft
                     * 1: approved
                     * 2: approve cancelled
                     * -9: void
                     */
                    if (is_approve == 0 || is_approve == 2){
                        // jika belum approve, hanya boleh approve
                        tbl_header.button( 'btnSetApprove:name' ).enable();
                        tbl_header.button( 'btnApprove:name' ).enable();
                        tbl_header.button( 'btnCancelApprove:name' ).disable();
                        tbl_header.button( 'btnVoid:name' ).disable();
                    }else if(is_approve == 1){
                        // jika sudah approve, boleh cancel approve atau void
                        tbl_header.button( 'btnSetApprove:name' ).enable();
                        tbl_header.button( 'btnApprove:name' ).disable();
                        tbl_header.button( 'btnCancelApprove:name' ).enable();
                        tbl_header.button( 'btnVoid:name' ).enable();
                    }else{
                        // jika sudah void, tidak boleh apa-apa
                        tbl_header.button( 'btnSetApprove:name' ).disable();
                    }
                }
            }else{
                // jika TIDAK ada hak akses approval
                tbl_header.button( 'btnSetApprove:name' ).disable();
            }
        }
        // END cek apakah form perlu approval
    }
};

function CekDeselectHeaderH(tbl_header){

    // // BEGIN cek apakah form perlu delete
    // if(is_need_delete == 1){
    //     tbl_header.button( 'btnNonAktif:name' ).disable();
    // }
    // // END cek apakah form perlu delete

    // // BEGIN cek apakah form perlu approval
	// if(is_need_approval == 1){
	// 	tbl_header.button( 'btnSetApprove:name' ).disable();
	// }
    // // END cek apakah form perlu approval

    tbl_header.button( 'btnNonAktif:name' ).disable();
    tbl_header.button( 'btnSetApprove:name' ).disable();

};

// ----- END transaksi header -----

// ----- BEGIN transaksi header detail -----

function CekInitHeaderHD(tbl_header, tbl_details){
    status_select = 'deselect';

    if(hak_c == 1){
        tbl_header.button( 'btnCreate:name' ).enable();
    }else{
        tbl_header.button( 'btnCreate:name' ).disable();
    }

    if(hak_d == 1){
        if(is_need_delete == 1){
			tbl_header.button( 'btnNonAktif:name' ).disable();
		}
    }else{
        tbl_header.button( 'btnNonAktif:name' ).disable();
    }

    // looping all detail table
    for (let i = 0; i < tbl_details.length; i++) {
        tbl_details[i].button( 'btnCreate:name' ).disable();

        if(is_need_delete == 1){
			tbl_details[i].button( 'btnNonAktif:name' ).disable();
			tbl_details[i].button( 'btnNonAktifOnly:name' ).disable();
		}
    }
};

function CekSelectHeaderHD(tbl_header, tbl_details){
    // reset
    arr_is_valid_cek_detail = [];
    c_is_valid_cek_detail = 0;
    // console.log('-------');
    // console.log('CekSelectHeaderHD');
    // console.log('arr_is_valid_cek_detail: ' + arr_is_valid_cek_detail);
    // console.log('c_is_valid_cek_detail: ' + c_is_valid_cek_detail);
    // console.log('-------');

    status_select = 'select';
    ReloadTableDetail();
    
    if(is_active == 0){
        tbl_header.button( 'btnEdit:name' ).disable();
        tbl_header.button( 'btnView:name' ).disable();
        tbl_header.button( 'btnNonAktif:name' ).text('<i class="fa fa-undo">&nbsp &nbsp Restore</i>');
        tbl_header.button( 'btnNonAktif:name' ).enable();
    }else{
        tbl_header.button( 'btnView:name' ).enable();
        tbl_header.button( 'btnNonAktif:name' ).text('<i class="fa fa-trash">&nbsp &nbsp Delete</i>');

        if(hak_u == 1){
            if (is_approve == 1 || is_approve == -9){
                // HEADER
                // tidak boleh edit atau delete
                tbl_header.button( 'btnEdit:name' ).disable();

                // DETAIL
                // tidak boleh create
                // looping all detail table
                for (let i = 0; i < tbl_details.length; i++) {
                    tbl_details[i].button( 'btnCreate:name' ).disable();
                }

            }else{
                // HEADER
                // boleh ada edit data header
                tbl_header.button( 'btnEdit:name' ).enable(); 

                // DETAIL
                // boleh create
                // looping all detail table
                for (let i = 0; i < tbl_details.length; i++) {
                    tbl_details[i].button( 'btnCreate:name' ).enable();
                }
            }
        }else{
            tbl_header.button( 'btnEdit:name' ).disable();
        }

        if(is_need_delete == 1){
            if (is_approve == 1 || is_approve == -9){
                tbl_header.button( 'btnNonAktif:name' ).disable();
            }else{
                tbl_header.button( 'btnNonAktif:name' ).enable();
            }
        }
        // console.log(is_need_approval);
        // BEGIN cek apakah form perlu approval
	    if(is_need_approval == 1){
		    tbl_header.button( 'btnSetApprove:name' ).enable();
	    }else{
            tbl_header.button( 'btnSetApprove:name' ).disable();
        }
        // END cek apakah form perlu approval
    }
};

function CekDeselectHeaderHD(tbl_header, tbl_details){
    status_select = 'deselect';
    ReloadTableDetail();

    if(is_need_delete == 1){
        tbl_header.button( 'btnNonAktif:name' ).disable();
    }

	if(is_need_approval == 1){
		tbl_header.button( 'btnSetApprove:name' ).disable();
	}

    // looping semua table detail
    for (let i = 0; i < tbl_details.length; i++) {
        tbl_details[i].button( 'btnCreate:name' ).disable();
        if(is_need_delete == 1){
            tbl_details[i].button( 'btnNonAktif:name' ).disable();
            tbl_details[i].button( 'btnNonAktifOnly:name' ).disable();
        }

    } // end foreach

};


function CekDrawDetailHD(tbl_header, tbl_detail, nama_tabel ){

    if(status_select == 'deselect'){
        is_approve            = -1;
        is_nextprocess        = -1;
        is_jurnal             = -1;
        is_valid_harga_d      = 0;
        is_valid_ED           = 0;
        is_valid_batch_number = 0;
        is_active 	          = 0
    }
    
    if(cek_c_detail == 0){
        // cek_c_detail = 0, artinya data dalam datatables tidak wajib diisi
        // cek_c_detail = 1, artinya data dalam datatables WAJIB  diisi
        
        // jika table detail tidak wajib diisi
        // c_id_detail = 0;
        // is_valid_harga_d = 1;
        // is_valid_ED == 1;
        // is_valid_batch_number == 1;
        
        is_valid_cek_detail = 1;
    }else{
        // jika table detail wajib diisi
        // cek tiap baris data, apakah sudah memenuhi syarat
        //      - ada baris data
        //      - jika menggunakan Expired Date, maka sudah diisi semua
        //      - jika menggunakan Batch Number, maka sudah diisi semua

        c_id_detail = tbl_detail.rows().count();

        if(c_id_detail > 0){
            
            // jika ada detail nya
            // cek is_valid_harga_d, is_valid_ED, is_valid_batch_number
    
            // jika detail ada isinya, maka header tidak dapat dihapus
            if(hak_d == 1){
                tbl_header.button( 'btnNonAktif:name' ).disable();
            }
    
            for ( let i = 0; i < c_id_detail; i++ ) {
                var d      = tbl_detail.row(i).data();
                key = nama_tabel;
                
                // cek apakah barang menggunakan expired_date dan batch_number
                // jika table tidak memerlukan expired_date atau batch_number, maka tidak perlu didefinisikan di model
                // jika belum menggunakan harus di cek lengkap expired_date dan batch_number, baru boleh approve
    
                if (d.hasOwnProperty(key)) {
    
                    // BEGIN validasi expired_date
                    expired_date = d[key]['expired_date'];
                    if( expired_date == undefined ){
                        is_valid_ED = 1;
                        is_valid_cek_detail = 1;
                    }else{
                        if( expired_date == null || expired_date == '0000-00-00'){
                            is_valid_ED = 0;
                            is_valid_cek_detail = 0;
                            return false;
                        }else{
                            is_valid_ED = 1;
                            is_valid_cek_detail = 1;
                        }
                    }
                    // END validasi expired_date
    
                    // BEGIN validasi batch_number
                    batch_number = d[key]['batch_number'];
                    if( batch_number == undefined ){
                        is_valid_batch_number = 1;
                        is_valid_cek_detail = 1;
                    }else{
                        if( batch_number == null ||batch_number == ''){
                            is_valid_batch_number = 0;
                            is_valid_cek_detail = 0;
                            return false;
                        }else{
                            is_valid_batch_number = 1;
                            is_valid_cek_detail = 1;
                        }
                    }
                    // END validasi batch_number
    
                    // BEGIN validasi harga_unit
                    harga_unit = d[key]['harga_unit'];
                    if( harga_unit == undefined ){
                        is_valid_harga_d = 1;
                        is_valid_cek_detail = 1;
                    }else{
                        if( harga_unit == null || harga_unit == '' || harga_unit <= 0 ){
                            is_valid_harga_d = 0;
                            is_valid_cek_detail = 0;
                            return false;
                        }else{
                            is_valid_harga_d = 1;
                            is_valid_cek_detail = 1;
                        }
                    }
                    // END validasi harga_unit
    
                }
    
            }
    
        }else{
            is_valid_cek_detail = 0;
        }
    }
    
    // console.log('-------');
    // console.log('CekDrawDetailHD');
    // console.log('is_active: ' + is_active);
    // console.log('hak_a: ' + hak_a);
    // console.log('is_valid_harga_d: ' + is_valid_harga_d);
    // console.log('is_valid_ED: ' + is_valid_ED);
    // console.log('is_valid_batch_number: ' + is_valid_batch_number);
    // console.log('is_jurnal: ' + is_jurnal);
    // console.log('is_nextprocess: ' + is_nextprocess);
    // console.log('is_valid_cek_detail: ' + is_valid_cek_detail);
    // console.log('-------');

    arr_is_valid_cek_detail[c_is_valid_cek_detail] = is_valid_cek_detail;
    c_is_valid_cek_detail = c_is_valid_cek_detail + 1;
    
};

function CekDrawDetailHDFinal(tbl_header){
    // console.log('-------');
    // console.log('CekDrawDetailHDFinal');
    // console.log('c_is_valid_cek_detail: ' + c_is_valid_cek_detail);
    // console.log('nama_tabels_d: ' + nama_tabels_d.length);
    // console.log('arr_is_valid_cek_detail: ' + arr_is_valid_cek_detail);
    // console.log('arr_is_valid_cek_detail tipe:' + typeof arr_is_valid_cek_detail);
    // console.log('-------');
    
    if(nama_tabels_d.length == arr_is_valid_cek_detail.length){
        Object.keys(arr_is_valid_cek_detail).every(key => {
            if( arr_is_valid_cek_detail[key] == 0){
                is_valid_cek_detail_final = 0;
                return false;
            }else{
                is_valid_cek_detail_final = 1;
            }
        });
    }else{
        is_valid_cek_detail_final = 0;
    }
    
    if(is_valid_cek_detail_final == 0){
        tbl_header.button( 'btnSetApprove:name' ).disable();
    }else{
        if(is_active == 1){
            if(hak_a == 1 && is_need_approval == 1){
                if(is_nextprocess > 0){
                    // jika sudah ditarik, maka tidak bisa melakukan approve atau void
                    tbl_header.button( 'btnSetApprove:name' ).disable();
                }else{
                    if (is_approve == 0 || is_approve == 2){
                        tbl_header.button( 'btnSetApprove:name' ).enable();
                        tbl_header.button( 'btnApprove:name' ).enable();
                        tbl_header.button( 'btnCancelApprove:name' ).disable();
                        tbl_header.button( 'btnVoid:name' ).disable();
                        // }
                    }else if(is_approve == 1){
                        tbl_header.button( 'btnSetApprove:name' ).enable();
                        tbl_header.button( 'btnApprove:name' ).disable();
                        tbl_header.button( 'btnCancelApprove:name' ).enable();
                        tbl_header.button( 'btnVoid:name' ).enable();
                    }else{
                        tbl_header.button( 'btnSetApprove:name' ).disable();
                    }
                }
            }else{
                tbl_header.button( 'btnSetApprove:name' ).disable();
            }
        }else{
            tbl_header.button( 'btnSetApprove:name' ).disable();
        }
    }
    
    // console.log('-------');
    // console.log('is_valid_cek_detail_final:' + is_valid_cek_detail_final);
    // console.log('-------');
    
};

function CekSelectDetailHD(tbl_header, tbl_detail ){	
    status_select = 'select';
    
    if(is_active_d == 0){
        tbl_detail.button( 'btnEdit:name' ).disable(); 
        tbl_detail.button( 'btnNonAktif:name' ).text('<i class="fa fa-undo">&nbsp &nbsp Restore</i>');
        tbl_detail.button( 'btnNonAktif:name' ).enable();
        
        tbl_detail.button( 'btnNonAktifOnly:name' ).disable();
    }else{
        
        // cek hak akses edit detail
        if(hak_u == 1){
            if (is_approve == 1 || is_approve == -9){
                tbl_detail.button( 'btnEdit:name' ).disable();
            }else{
                tbl_detail.button( 'btnEdit:name' ).enable();
            }
        }else{
            tbl_detail.button( 'btnEdit:name' ).disable();
        }

        // cek hak akses remove detail
        tbl_detail.button( 'btnNonAktif:name' ).text('<i class="fa fa-trash">&nbsp &nbsp Delete</i>');
        if(hak_d == 1){
            if (is_approve == 1 || is_approve == -9){
                tbl_detail.button( 'btnNonAktif:name' ).disable();
                tbl_detail.button( 'btnNonAktifOnly:name' ).disable();
            }else{
                tbl_detail.button( 'btnNonAktif:name' ).enable();
                tbl_detail.button( 'btnNonAktifOnly:name' ).enable();
            }
        }else{
            tbl_detail.button( 'btnNonAktif:name' ).disable();
            tbl_detail.button( 'btnNonAktifOnly:name' ).disable();
        }
    }

}

function CekDeselectDetailHD(tbl_header, tbl_detail ){
    // ReloadTableDetail();

    status_select = 'deselect';

    // cek hak akses remove detail
    if(hak_d == 1){
        tbl_detail.button( 'btnNonAktif:name' ).disable();
        tbl_detail.button( 'btnNonAktifOnly:name' ).disable();
    }

};

// ----- END transaksi header detail -----