<?php
	// Template untuk membuat tampilan Tab Header Detail
	// Silakan disesuaikan untuk layoutnya, atas bawah atah kanan kiri
	// Template ini menggunakan kanan kiri
?>

<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 p-w-xs">
		<div class="ibox ">
			<div class="ibox-title">
				<?php require_once $abs_us_root.$us_url_root.'usersc/templates/inspinia/template_page_title.php';?>
				<?php require_once $abs_us_root.$us_url_root.'usersc/templates/inspinia/template_page_toolbox.php';?>
			</div>
			<div class="ibox-content">
				<div class="table-responsive">
					<table id="tbl_blankheader" class="table table-striped table-bordered table-hover nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
				</div>
			</div>
		</div>
	</div> <!-- end of left col -->

	<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 p-w-xs">
		<div class="tabs-container">
			<ul class="nav nav-tabs" role="tablist">
				<li><a class="nav-link active" data-toggle="tab" href="#tab_blankdetail_1"> Tab 1</a></li>
				<li><a class="nav-link" data-toggle="tab" href="#tab_blankdetail_2"> Tab 2</a></li>
			</ul>
			<div class="tab-content">
				<div role="tabpanel" id="tab_blankdetail_1" class="tab-pane active">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tbl_blankdetail_1" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id__blankheader</th>
										<th>Kode</th>
										<th>Nama</th>
										<th>Keterangan</th>
									</tr>
								</thead>
							</table>
						</div> <!-- end of table -->

					</div>
				</div>
				<div role="tabpanel" id="tab_blankdetail_2" class="tab-pane">
					<div class="panel-body">
						<div class="table-responsive">
							<table id="tbl_blankdetail_2" class="table table-striped table-bordered table-hover nowrap" width="100%">
								<thead>
									<tr>
										<th>ID</th>
										<th>id__blankheader</th>
										<th>Kode</th>
										<th>Nama</th>
										<th>Keterangan</th>
									</tr>
								</thead>
							</table>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div> <!-- end of right col -->

</div>