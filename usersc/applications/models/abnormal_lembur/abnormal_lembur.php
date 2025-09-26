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
	$show_inactive_status = $_POST['show_inactive_status_abnormal_lembur'];
	// -----------
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$editor = Editor::inst( $db, 'abnormal_lembur' )
		->debug(true)
		->fields(
			Field::inst( 'abnormal_lembur.id' ),
			Field::inst( 'abnormal_lembur.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'abnormal_lembur.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'abnormal_lembur.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'abnormal_lembur.keterangan' ),
			Field::inst( 'abnormal_lembur.is_active' ),
			Field::inst( 'abnormal_lembur.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'abnormal_lembur.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'abnormal_lembur.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'abnormal_lembur.is_approve' ),
			Field::inst( 'abnormal_lembur.is_defaultprogram' ),
			Field::inst( 'abnormal_lembur.lembur_program' ),
			Field::inst( 'abnormal_lembur.lembur_seharusnya' ),
			Field::inst( 'abnormal_lembur.selisih' ),
			Field::inst( 'abnormal_lembur.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) ),

			Field::inst( 'hemxxmh.kode' ),
			Field::inst( 'hemxxmh.nama' ),
			Field::inst( 'hesxxmh.nama' ),
			Field::inst( 'hosxxmh.nama' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','abnormal_lembur.id_hemxxmh' )

		->leftJoin('hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id','LEFT' )
		->leftJoin('hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh','LEFT' )
		->leftJoin('hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh','LEFT' )

		->where( 'abnormal_lembur.tanggal', $start_date, '>=' )
		->where( 'abnormal_lembur.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'abnormal_lembur.is_active', 1);
	}
	
	if ($_SESSION['user'] > 100){
		$editor
			->where( function ( $q ) {
				$q->where('hemjbmh.id_heyxxmh', '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')', 'IN', false );
			} );
	}
	
	include( "abnormal_lembur_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>