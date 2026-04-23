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
	$show_inactive_status = $_POST['show_inactive_status_abnormal_istirahat'];
	// -----------
	
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];

	$editor = Editor::inst( $db, 'abnormal_istirahat' )
		->debug(true)
		->fields(
			Field::inst( 'abnormal_istirahat.id' ),
			Field::inst( 'abnormal_istirahat.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
			Field::inst( 'abnormal_istirahat.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'abnormal_istirahat.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'abnormal_istirahat.keterangan' ),
			Field::inst( 'abnormal_istirahat.is_active' ),
			Field::inst( 'abnormal_istirahat.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'abnormal_istirahat.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'abnormal_istirahat.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'abnormal_istirahat.is_approve' ),
			Field::inst( 'abnormal_istirahat.is_defaultprogram' ),
			Field::inst( 'abnormal_istirahat.tanggal' )
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
			
			Field::inst( 'hovxxmh.nama' ),
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hosxxmh.nama' ),
			Field::inst( 'hobxxmh.nama' ),
			Field::inst( 'hevxxmh.nama' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'heyxxmh.nama' ),
			Field::inst( 'heyxxmd.nama' ),
			Field::inst( 'hesxxmh.nama' ),
			Field::inst( 'holxxmd_2.nama' ),
			Field::inst( 'hevgrmh.nama' ),
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','abnormal_istirahat.id_hemxxmh' )

		->leftJoin( 'hemdcmh','hemdcmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hovxxmh','hovxxmh.id','=','hemjbmh.id_hovxxmh' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh' )
		->leftJoin( 'hobxxmh','hobxxmh.id','=','hemjbmh.id_hobxxmh' )
		->leftJoin( 'hevxxmh','hevxxmh.id','=','hemjbmh.id_hevxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
		->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
		->leftJoin( 'hesxxmh','hesxxmh.id','=','hemjbmh.id_hesxxmh' )
		->leftJoin( 'holxxmd_2','holxxmd_2.id','=','hemjbmh.id_holxxmd_2' )
		->leftJoin( 'hevgrmh','hevgrmh.id','=','hemjbmh.id_hevgrmh' )

		->where( 'abnormal_istirahat.tanggal', $start_date, '>=' )
		->where( 'abnormal_istirahat.tanggal', $end_date, '<=' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'abnormal_istirahat.is_active', 1);
	}
	
	if ($_SESSION['user'] > 100){
		$editor
			->where( function ( $q ) {
				$q->where('hemjbmh.id_heyxxmh', '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')', 'IN', false );
			} );
	}
	
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>