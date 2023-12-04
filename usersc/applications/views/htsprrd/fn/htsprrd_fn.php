<script>
    function cariApprove(){
        start_date 		= moment($('#start_date').val()).format('YYYY-MM-DD');
        end_date 		= moment($('#end_date').val()).format('YYYY-MM-DD');
        id_hemxxmh = $('#select_hemxxmh').val();

        $.ajax( {
            url: "../../models/htsprrd/fn_cari_approve.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                start_date: start_date
            },
            success: function ( json ) {
                total_approve = json.data.rs_htsprrd.approve;
                total_data = json.data.rs_htsprrd_total.total;
                is_payroll = json.data.rs_htsprrd_total.is_payroll;
                cek_data = json.data.rs_cek.cek;
                console.log(cek_data);
                // console.log(json.data.rs_htsprrd_total.total);

                if (start_date == end_date && total_data > 0 && is_payroll == 0 && cek_data == 0) {
                    tblhtsprrd.button('btnSetApprovePresensi:name').enable();
                    if (total_approve > 0) {
                        tblhtsprrd.button('btnApprovePresensi:name').enable();
                        tblhtsprrd.button('btnCancelApprovePresensi:name').disable();
                    } else {
                        
                        tblhtsprrd.button('btnCancelApprovePresensi:name').enable();
                        tblhtsprrd.button('btnApprovePresensi:name').disable();
                    }
                } else {
                    tblhtsprrd.button('btnSetApprovePresensi:name').disable();
                }
            }
        } );
    };
    function cariKMJ(){

        $.ajax( {
            url: "../../models/htsprrd/fn_cari_os_kmj.php",
            dataType: 'json',
            type: 'POST',
            async: false,
            data: {
                id_hemxxmh_select: id_hemxxmh_select
            },
            success: function ( json ) {
                id_heyxxmd = json.data.rs_kmj.id_heyxxmd;
                
                if (id_heyxxmd == 4 && cek == 1) {
				    tblhtsprrd.button('btnPresensiOK:name').enable();
                } else {
				    tblhtsprrd.button('btnPresensiOK:name').disable();
                }
            }
        } );
    };
</script>