<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	// ----------- do not erase
	$show_inactive_status = $_POST['show_inactive_status_htoxxrd'];
	// -----------

	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	
	$editor = Editor::inst( $db, 'htoemtd' )
		->debug(true)
		->fields(
			Field::inst( 'htoemtd.id' ),
			Field::inst( 'htoemtd.id_hemxxmh' ),
			Field::inst( 'htoxxth.id_heyxxmh' ),
			Field::inst( 'htoxxth.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htoemtd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htoemtd.keterangan' ),
			Field::inst( 'htoemtd.is_active' ),
			Field::inst( 'htoemtd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htoemtd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htoemtd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htoemtd.is_approve' ),
			Field::inst( 'htoemtd.is_defaultprogram' ),

			Field::inst( 'htoxxth.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} ),
			Field::inst( 'htoemtd.jam_awal' ),
			Field::inst( 'htoemtd.jam_akhir' ),
			Field::inst( 'htoemtd.durasi_lembur_jam' ),
			Field::inst( 'htoemtd.is_istirahat' ),

			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'hosxxmh.nama' ),
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'htotpmh.nama' )

		)
		->leftJoin( 'htoxxth','htoxxth.id','=','htoemtd.id_htoxxth' )
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htoemtd.id_hemxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'htotpmh','htotpmh.id','=','htoemtd.id_htotpmh' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		->leftJoin( 'hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh' )
		->where( 'htoxxth.is_active', 1 )
		->where( 'htoxxth.is_approve', 0 )
		->where( 'htoemtd.is_active', 1 )
		->where( 'htoxxth.tanggal', $start_date, '>=' )
		->where( 'htoxxth.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htoemtd.is_active', 1);
	}
	
	if($_POST['id_hemxxmh'] > 0){
		$editor->where( 'htoemtd.id_hemxxmh', $_POST['id_hemxxmh'] );
	}
	
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>