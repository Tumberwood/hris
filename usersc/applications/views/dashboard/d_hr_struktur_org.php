
<?php
    require_once '../../../../users/init.php';
    require_once $abs_us_root . $us_url_root . 'users/includes/template/prep.php';

    if (!securePage($_SERVER['PHP_SELF'])) {
        die();
    }
?>

<?php
    $nama_tabel    = '';
    $nama_tabels_d = [];
?>

<style>

    /* ========================================
       CHART CONTAINER
    ======================================== */

    #chart-container {
        width: 100%;
        min-height: 600px;
        background: #ffffff;
        text-align: center;
        overflow: auto;
        padding: 30px 20px;
    }

    /* Background OrgChart */
    .orgchart {
        background: #ffffff !important;
    }


    /* ========================================
       NODE
    ======================================== */

    .orgchart .node {
        width: 190px !important;
    }


    /* ========================================
       TITLE / HEADER BIRU
    ======================================== */

    .orgchart .node .title {
        width: 100% !important;
        min-height: 38px !important;
        height: auto !important;

        position: relative !important;

        display: flex !important;
        align-items: center !important;
        justify-content: center !important;

        background-color: #233645 !important;
        border-color: #233645 !important;
        color: #ffffff !important;

        font-size: 14px !important;
        font-weight: 700 !important;
        line-height: 1.4 !important;

        padding: 9px 25px !important;

        white-space: normal !important;
        word-break: break-word !important;
        overflow: visible !important;

        box-sizing: border-box !important;
        text-align: center !important;
    }


    /* ========================================
       ICON TITLE
    ======================================== */

    .orgchart .node .title .symbol {
        position: absolute !important;

        left: 7px !important;
        top: 50% !important;

        transform: translateY(-50%) !important;

        float: none !important;
        margin: 0 !important;

        width: 16px !important;
        height: 16px !important;

        font-size: 13px !important;
        line-height: 16px !important;

        text-align: center !important;
        color: #ffffff !important;

        z-index: 2;
    }


    /* ========================================
       CONTENT / JUMLAH KARYAWAN
    ======================================== */

    .orgchart .node .content {
        width: 100% !important;
        min-height: 30px !important;
        height: auto !important;

        background-color: #ffffff !important;
        border-color: #233645 !important;
        color: #233645 !important;

        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;

        padding: 7px 8px !important;

        white-space: normal !important;
        word-break: break-word !important;
        overflow: visible !important;

        box-sizing: border-box !important;
    }


    /* ========================================
       GARIS PENGHUBUNG
       PERTAHANKAN GEOMETRI BAWAAN ORGCHART
    ======================================== */

    .orgchart .lines .topLine {
        border-top-color: #233645 !important;
    }

    .orgchart .lines .leftLine {
        border-left-color: #233645 !important;
    }

    .orgchart .lines .rightLine {
        border-right-color: #233645 !important;
    }

    .orgchart .lines .downLine {
        background-color: #233645 !important;
    }

</style>


<!-- ========================================
     BEGIN CONTENT
======================================== -->

<div class="row">
    <div class="col">
        <div class="ibox">
            <div class="ibox-content">
                <div class="card-body">
                    <div id="chart-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- END CONTENT -->


<!-- ========================================
     BEGIN JS
======================================== -->

<?php
    require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_load.php';
?>

<!-- Highcharts -->
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/highcharts.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/highcharts-more.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/exporting.js"></script>
<script src="<?=$us_url_root?>usersc/templates/<?=$settings->template?>/assets/js/plugins/highcharts/no-data-to-display.js"></script>

<?php
    require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_setup.php';

    require_once $abs_us_root . $us_url_root . 'usersc/templates/' . $settings->template . '/template_js_datatables_load.php';
?>

<script src="<?=$us_url_root?>usersc/helpers/hakaksescrud_hd_fn.js"></script>

<?php
    require_once $abs_us_root . $us_url_root . 'usersc/applications/views/dashboard/fn/d_hr_profile_fn.php';
?>

<!-- OrgChart JS -->
<!--
<script src="https://cdnjs.cloudflare.com/ajax/libs/orgchart/3.1.3/js/jquery.orgchart.min.js"></script>
-->


<script type="text/javascript">

    $(document).ready(function () {

        $.ajax({

            url: "../../models/dashboard/d_hr_struktur_org.php",

            dataType: 'json',

            type: 'POST',

            data: {},


            beforeSend: function () {

                $('#chart-container').html(
                    '<div class="text-center p-3">' +
                    'Memuat struktur organisasi...' +
                    '</div>'
                );

            },


            success: function (json) {

                if (
                    !json ||
                    !json.data ||
                    !Array.isArray(json.data.struktur_org)
                ) {

                    $('#chart-container').html(
                        '<div class="alert alert-warning">' +
                        'Data struktur organisasi tidak valid.' +
                        '</div>'
                    );

                    return;

                }


                var struktur_org = json.data.struktur_org;

                var root = json.data.root;

                var finalData;


                if (root) {

                    finalData = {

                        name: root.name,

                        title: root.title,

                        children: struktur_org

                    };

                } else {

                    finalData = {

                        name: "ROOT",

                        title: "ROOT",

                        children: struktur_org

                    };

                }


                $('#chart-container').empty();


                $('#chart-container').orgchart({

                    data: finalData,

                    nodeContent: 'title',

                    pan: false,

                    zoom: false

                });

            },


            error: function (xhr, status, error) {

                console.error('OrgChart AJAX Error:', error);

                console.error(xhr.responseText);


                $('#chart-container').html(
                    '<div class="alert alert-danger">' +
                    'Gagal memuat struktur organisasi.' +
                    '</div>'
                );

            }

        });

    });

</script>


<!-- ========================================
     END JS
======================================== -->


<!-- DO NOT ERASE -->

<?php
    require_once $abs_us_root . $us_url_root . 'users/includes/html_footer.php';
?>
