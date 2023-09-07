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
	$show_inactive_status = $_POST['show_inactive_status_hcdfmmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdfmmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdfmmd.id' ),
				Field::inst( 'hcdfmmd.id_hcdxxmh' ),
				Field::inst( 'hcdfmmd.id_gedxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hcdfmmd.id_gctxxmh_lahir' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hcdfmmd.kode' ),
				Field::inst( 'hcdfmmd.nama' ),
				Field::inst( 'hcdfmmd.keterangan' ),
				Field::inst( 'hcdfmmd.is_active' ),
				Field::inst( 'hcdfmmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdfmmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdfmmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hcdfmmd.tanggal_lahir' )
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
				Field::inst( 'hcdfmmd.gender' ),
				Field::inst( 'hcdfmmd.hubungan' ),
				Field::inst( 'hcdfmmd.pekerjaan' ),

				Field::inst( 'gedxxmh.nama' ),
				Field::inst( 'gctxxmh.nama' )
			)
			->leftJoin( 'gedxxmh','gedxxmh.id','=','hcdfmmd.id_gedxxmh' )
			->leftJoin( 'gctxxmh','gctxxmh.id','=','hcdfmmd.id_gctxxmh_lahir' )
			->where('hcdfmmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdfmmd.is_active', 1);
		}
		
		include( "hcdfmmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>