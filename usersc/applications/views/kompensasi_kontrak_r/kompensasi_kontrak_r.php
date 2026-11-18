
<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';

    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }

    $nama_tabel = 'kompensasi_kontrak_r';
    $nama_tabels_d = [];

    $id_hemxxmh = isset($_GET['id_hemxxmh'])
        ? (int) $_GET['id_hemxxmh']
        : 0;

    $awal = isset($_GET['start_date'])
        ? $_GET['start_date']
        : null;
?>

<!-- BEGIN CONTENT -->

<div class="row">
    <div class="col">
        <div class="ibox collapsed" id="iboxfilter">
            <div class="ibox-title">
                <h5 class="text-navy">Filter</h5>&nbsp;
                <button class="btn btn-primary btn-xs collapse-link">
                    <i class="fa fa-chevron-up"></i>
                </button>
            </div>

            <div class="ibox-content">
                <form class="form-horizontal" id="frmkompensasi_kontrak_r">

                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Periode</label>

                        <div class="col-lg-5">
                            <div class="input-group input-daterange" id="periode">
                                <input type="text" id="start_date" class="form-control">

                                <span class="input-group-addon">to</span>

                                <input type="text" id="end_date" class="form-control">

                                <div class="input-group-addon">
                                    <span class="fa fa-calendar"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">
                            Periode Payroll
                        </label>

                        <div class="col-sm-5">
                            <select
                                class="form-control"
                                id="select_periode_payroll"
                                name="select_periode_payroll">
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-lg-4">
                            <button
                                class="btn btn-primary"
                                type="submit"
                                id="go">
                                Submit
                            </button>
                        </div>
                    </div>

                </form>

                <div id="searchPanes1"></div>
            </div>
        </div>
    </div>
</div>

<!-- SUMMARY -->

<div class="row">

    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-content">
                <h5 class="text-muted">TOTAL DATA KONTRAK</h5>
                <h2 class="font-bold text-primary" id="summary_total_data">
                    0
                </h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-content">
                <h5 class="text-muted">JUMLAH KARYAWAN</h5>
                <h2 class="font-bold text-success" id="summary_total_orang">
                    0
                </h2>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="ibox">
            <div class="ibox-content">
                <h5 class="text-muted">GRAND TOTAL NOMINAL</h5>
                <h2 class="font-bold text-navy" id="summary_total_nominal">
                    0
                </h2>
            </div>
        </div>
    </div>

</div>

<!-- TABLE -->

<div class="row">
    <div class="col">
        <div class="ibox">
            <div class="ibox-content">

                <div class="table-responsive">

                    <table
                        id="tblkompensasi_kontrak_r"
                        class="table table-striped table-bordered table-hover nowrap"
                        width="100%">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Nominal</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Akhir Kontrak</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th></th>
                                <th colspan="3" class="text-right">
                                    GRAND TOTAL
                                </th>
                                <th class="text-right" id="footer_total_nominal">
                                    0
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>

                    </table>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- END CONTENT -->

<!-- BEGIN JS -->

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php'; ?>

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php'; ?>

<?php require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php'; ?>

<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<script type="text/javascript">

    // ==========================================
    // DEFAULT VARIABLE
    // ==========================================

    var tblkompensasi_kontrak_r;
    var show_inactive_status_kompensasi_kontrak_r = 0;

    var id_hemxxmh = 0;
    var id_periode_payroll_old = 0;

    var id_hem_get = <?= json_encode($id_hemxxmh) ?>;
    var tanggal_get = <?= json_encode($awal) ?>;

    var start_date;
    var end_date;

    // ==========================================
    // FORMAT NOMINAL
    // ==========================================

    const formatNominal = function(value) {

        return Number(value || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 1,
            maximumFractionDigits: 1
        });

    };

    // ==========================================
    // PARSE NOMINAL
    // Mendukung:
    // 2595771
    // 2.595.771
    // 2.595.771,0
    // ==========================================

    const parseNominal = function(value) {

        if (value === null || value === undefined || value === '') {
            return 0;
        }

        if (typeof value === 'number') {
            return value;
        }

        let number = String(value)
            .trim()
            .replace(/[^\d,.-]/g, '');

        if (number.includes(',')) {
            number = number
                .replace(/\./g, '')
                .replace(',', '.');
        }

        return parseFloat(number) || 0;

    };

    // ==========================================
    // DATEPICKER
    // ==========================================

    $('#periode').datepicker({
        setDate: new Date(),
        autoclose: true,
        todayHighlight: true,
        clearBtn: true,
        format: "dd M yyyy",
        minViewMode: 'month'
    });

    if (tanggal_get === '' || tanggal_get === null) {

        $('#start_date').datepicker('setDate', tanggal_hariini_dmy);
        $('#end_date').datepicker('setDate', tanggal_hariini_dmy);

    } else {

        $('#start_date').datepicker('setDate', new Date(tanggal_get));
        $('#end_date').datepicker('setDate', new Date(tanggal_get));

    }

    // ==========================================
    // SELECT2 PERIODE PAYROLL
    // ==========================================

    $("#select_periode_payroll").select2({

        placeholder: 'Ketik atau Tekan Tanda Panah Kanan',
        allowClear: true,

        ajax: {

            url: "../../models/periode_payroll/periode_payroll_fn_opt.php",
            dataType: 'json',

            data: function(params) {

                return {
                    id_periode_payroll_old: id_periode_payroll_old,
                    search: params.term || '',
                    page: params.page || 1
                };

            },

            processResults: function(data, params) {

                if (id_hem_get > 0) {

                    var options = data.results.map(function(result) {

                        return {
                            id: result.id,
                            text: result.text
                        };

                    });

                    if (params.page && params.page === 1) {

                        $('#select_periode_payroll')
                            .empty()
                            .select2({
                                data: options
                            });

                    } else if (options.length > 0) {

                        $('#select_periode_payroll')
                            .append(
                                new Option(
                                    options[0].text,
                                    options[0].id,
                                    false,
                                    false
                                )
                            )
                            .trigger('change');

                    }

                    return {
                        results: options,
                        pagination: {
                            more: true
                        }
                    };

                }

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
            minimumResultsForSearch: -1

        }

    });

    // ==========================================
    // OVERRIDE TANGGAL DARI PERIODE PAYROLL
    // ==========================================

    $('#select_periode_payroll').on('select2:select', async function(e) {

        const val = $(this).val();

        await autofillField(
            'periode_payroll',
            val,
            'DATE_FORMAT(tanggal_awal, "%d %b %Y") AS tanggal_awal, DATE_FORMAT(tanggal_akhir, "%d %b %Y") AS tanggal_akhir'
        );

        const tanggal_awal = autofillData.tanggal_awal;
        const tanggal_akhir = autofillData.tanggal_akhir;

        $('#start_date').datepicker('setDate', tanggal_awal);
        $('#end_date').datepicker('setDate', tanggal_akhir);

    });

    // ==========================================
    // DOCUMENT READY
    // ==========================================

    $(document).ready(function() {

        start_date = moment($('#start_date').val()).format('YYYY-MM-DD');
        end_date = moment($('#end_date').val()).format('YYYY-MM-DD');

        id_periode_payroll_old = id_hem_get;

        $('#select_periode_payroll').select2('open');

        setTimeout(function() {
            $('#select_periode_payroll').select2('close');
        }, 5);

        // ==========================================
        // DATATABLES
        // ==========================================

        tblkompensasi_kontrak_r = $('#tblkompensasi_kontrak_r').DataTable({

            processing: true,

            searchPanes: {
                layout: 'columns-3'
            },

            dom:
                "<P>" +
                "<lf>" +
                "<B>" +
                "<rt>" +
                "<'row'<'col-sm-4'i><'col-sm-8'p>>",

            columnDefs: [

                {
                    searchPanes: {
                        show: true
                    },
                    targets: [1, 2, 6]
                },

                {
                    searchPanes: {
                        show: false
                    },
                    targets: '_all'
                }

            ],

            ajax: {

                url: "../../models/kompensasi_kontrak_r/kompensasi_kontrak_r.php",

                type: 'POST',

                data: function(d) {

                    d.start_date = start_date;
                    d.end_date = end_date;
                    d.id_hemxxmh = id_hemxxmh;

                },

                dataSrc: 'data.htsprrd'

            },

            order: [
                [1, 'asc'],
                [3, 'desc']
            ],

            responsive: false,

            columns: [

                // ID
                {
                    data: "id",
                    visible: false
                },

                // KODE
                {
                    data: "kode",
                    defaultContent: '-'
                },

                // NAMA
                {
                    data: "nama",
                    defaultContent: '-',

                    render: function(data, type, row) {

                        if (type !== 'display') {
                            return data;
                        }

                        var id = row.id_hemxxmh || '';
                        var tanggal = row.tanggal || '';

                        var url =
                            "../dashboard/d_hr_report_presensi.php?id_hemxxmh=" +
                            encodeURIComponent(id) +
                            "&start_date=" +
                            encodeURIComponent(tanggal);

                        return '<a href="' + url + '" target="_blank">' +
                            data +
                            '</a>';

                    }
                },

                // TANGGAL
                {
                    data: "tanggal",
                    defaultContent: '-'
                },

                // NOMINAL
                {
                    data: "nominal",
                    className: "text-right",

                    render: function(data, type, row) {

                        var nominal = parseNominal(data);

                        if (type === 'sort' || type === 'type') {
                            return nominal;
                        }

                        if (type === 'filter') {
                            return String(data || '');
                        }

                        return formatNominal(nominal);

                    }
                },

                // TANGGAL MASUK
                {
                    data: "tanggal_masuk",
                    defaultContent: '-'
                },

                // TANGGAL AKHIR KONTRAK
                {
                    data: "tanggal_akhir_kontrak",
                    defaultContent: '-'
                }

            ],

            // ==========================================
            // BUTTONS
            // ==========================================

            buttons: [

                <?php

                    $id_table    = 'id_kompensasi_kontrak_r';
                    $table       = 'tblkompensasi_kontrak_r';
                    $edt         = 'edtkompensasi_kontrak_r';
                    $show_status = '_kompensasi_kontrak_r';
                    $table_name  = $nama_tabel;

                    $arr_buttons_tools = ['copy', 'excel', 'colvis'];
                    $arr_buttons_action = [];
                    $arr_buttons_approve = [];

                    include $abs_us_root.$us_url_root.
                        'usersc/helpers/button_fn_generate.php';

                ?>
                ,{
                    text: '<i class="fa fa-print">WOI</i>',
                    name: 'btnPrint',
                    className: 'btn btn-outline',
                    titleAttr: 'Print Slip Gaji',
                    action: function ( e, dt, node, config ) {
                        e.preventDefault(); 
                        var url = $(this).attr('href'); 
                        window.open('hpyxxth_print.php?id_hpyxxth=' + id_hpyxxth + '&id_heyxxmd=3', 'hpyxxth');
                    }
                },

            ],

            // ==========================================
            // FOOTER SUMMARY
            // ==========================================

            footerCallback: function(row, data, start, end, display) {

                const api = this.api();

                // Data sesuai filter/search yang aktif
                const filteredData = api
                    .rows({
                        search: 'applied'
                    })
                    .data()
                    .toArray();

                let totalNominal = 0;

                let uniqueKode = new Set();

                let totalData = filteredData.length;

                filteredData.forEach(function(item) {

                    // TOTAL NOMINAL
                    totalNominal += parseNominal(item.nominal);

                    // KARYAWAN UNIK
                    if (item.kode !== null &&
                        item.kode !== undefined &&
                        item.kode !== '') {

                        uniqueKode.add(String(item.kode).trim());

                    }

                });

                // FOOTER GRAND TOTAL
                $(api.column(4).footer()).html(
                    '<strong>' + formatNominal(totalNominal) + '</strong>'
                );

                // SUMMARY TOTAL DATA
                $('#summary_total_data').text(
                    totalData.toLocaleString('id-ID')
                );

                // SUMMARY KARYAWAN
                $('#summary_total_orang').text(
                    uniqueKode.size.toLocaleString('id-ID')
                );

                // SUMMARY NOMINAL
                $('#summary_total_nominal').text(
                    formatNominal(totalNominal)
                );

            },

            // ==========================================
            // INIT COMPLETE
            // ==========================================

            initComplete: function() {

                this.api().searchPanes.rebuildPane();

            }

        });

        // ==========================================
        // SEARCH PANES
        // ==========================================

        tblkompensasi_kontrak_r
            .searchPanes
            .container()
            .appendTo('#searchPanes1');

        // ==========================================
        // FILTER SUBMIT
        // ==========================================

        $("#frmkompensasi_kontrak_r")
            .submit(function(e) {

                e.preventDefault();

            })
            .validate({

                rules: {},

                submitHandler: function(form) {

                    start_date = moment(
                        $('#start_date').val()
                    ).format('YYYY-MM-DD');

                    end_date = moment(
                        $('#end_date').val()
                    ).format('YYYY-MM-DD');

                    if ($('#select_periode_payroll').val() > 0) {

                        id_hemxxmh = $('#select_periode_payroll').val();

                    } else {

                        if (id_hem_get != 0) {

                            id_hemxxmh = id_hem_get;

                        } else {

                            id_hemxxmh = $('#select_periode_payroll').val();

                        }

                    }

                    notifyLoadingDomba();

                    tblkompensasi_kontrak_r.rows().deselect();

                    tblkompensasi_kontrak_r.ajax.reload(function(json) {

                        notifyprogress.close();

                    }, false);

                    return false;

                }

            });

        // ==========================================
        // AUTO SUBMIT
        // ==========================================

        if (id_hem_get > 0) {

            $("#frmkompensasi_kontrak_r").submit();

        }

    });

</script>

<!-- END JS -->

<?php require_once $abs_us_root.$us_url_root.'users/includes/html_footer.php'; ?>
