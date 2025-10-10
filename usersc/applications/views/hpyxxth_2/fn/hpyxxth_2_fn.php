<script>
    
    function outstandingApproval(id_hpyxxth_2){
        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#report_presensi')) {
            $('#report_presensi').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#report_presensi').DataTable({
            dom: 'Bfrtip' ,
            ajax: {
                url: "../../models/hpyxxth_2/outstanding_approval_report.php",
                type: 'POST',
                data: function (d) {
                    d.id_hpyxxth_2 = id_hpyxxth_2;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_report || [];
                }
            },
            columns: [
                { data: 'tanggal' },
                { data: 'status' },
            ],
            buttons:[
                {
                    text: 'Approve All',
                    name: 'btnApproveAll',
                    className: 'btn btn-outline',
                    titleAttr: 'Outstanding Approval',
                    action: function (e, dt, node, config) {
                        e.preventDefault();

                        if (confirm('Yakin ingin melakukan Approve All Outstanding Presensi?')) {
                            $.ajax({
                                url: "../../models/hpyxxth_2/update_outstanding_presensi.php",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    id_hpyxxth_2: id_hpyxxth_2 
                                },
                                success: function (json) {
                                    if (json.data.type_message == 'success') {
                        				$.notify({
                                            message: json.data.message
                                        }, {
                                            type: json.data.type_message,
                                            z_index: 999999,             
                                            placement: {
                                                from: "top",
                                                align: "center"
                                            },
                                            delay: 3000,                 
                                            animate: {
                                                enter: 'animated fadeInDown',
                                                exit: 'animated fadeOutUp'
                                            }
                                        });
                                        
                                        dt.ajax.reload(); 
                                    } else {
                                        alert('Gagal approve: ' + json.message);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error AJAX:', error);
                                    alert('Terjadi kesalahan saat melakukan Approve All.');
                                }
                            });
                        }
                    }
                }
            ],
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });

        // Destroy the existing DataTable instance if it exists
        if ($.fn.DataTable.isDataTable('#payroll_lain')) {
            $('#payroll_lain').DataTable().destroy();
        }
        
        // Initialize DataTable
        $('#payroll_lain').DataTable({
            dom: 'Bfrtip' ,
            ajax: {
                url: "../../models/hpyxxth_2/outstanding_approval_payroll_lain.php",
                type: 'POST',
                data: function (d) {
                    d.id_hpyxxth_2 = id_hpyxxth_2;
                },
                dataSrc: function (json) {
                    // console.log(json); // Optional debugging
                    return json.data.rs_payroll_lain || [];
                }
            },
            columns: [
                { data: "id", visible:false },
                { data: "hemxxmh_data" },
                { data: "hpcxxmh_data" },
                { 
                    data: "nominal",
                    render: $.fn.dataTable.render.number( ',', '.', 0,'','' ),
                    class: "text-right" 
                },
                { data: "plus_min" },
                { data: "tanggal" },
                { data: "keterangan" },
                { data: "status" },
            ],
            buttons:[
                {
                    text: 'Approve All',
                    name: 'btnApproveAll',
                    className: 'btn btn-outline',
                    titleAttr: 'Outstanding Approval',
                    action: function (e, dt, node, config) {
                        e.preventDefault();

                        if (confirm('Yakin ingin melakukan Approve All Outstanding Payroll Lain-lain?')) {
                            $.ajax({
                                url: "../../models/hpyxxth_2/update_outstanding_payroll_lain.php",
                                type: "POST",
                                dataType: "json",
                                data: {
                                    id_hpyxxth_2: id_hpyxxth_2 
                                },
                                success: function (json) {
                                    if (json.data.type_message == 'success') {
                        				$.notify({
                                            message: json.data.message
                                        }, {
                                            type: json.data.type_message,
                                            z_index: 999999,             
                                            placement: {
                                                from: "top",
                                                align: "center"
                                            },
                                            delay: 3000,                 
                                            animate: {
                                                enter: 'animated fadeInDown',
                                                exit: 'animated fadeOutUp'
                                            }
                                        });

                                        dt.ajax.reload(); 
                                    } else {
                                        alert('Gagal approve: ' + json.message);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error AJAX:', error);
                                    alert('Terjadi kesalahan saat melakukan Approve All.');
                                }
                            });
                        }
                    }
                }
            ],
            destroy: true, // Reinitialize allowed
            responsive: false, // Enable responsive layout
            autoWidth: false, // Disable automatic column width adjustment
            lengthChange: true, // Allow users to select number of rows
        });
    }
</script>