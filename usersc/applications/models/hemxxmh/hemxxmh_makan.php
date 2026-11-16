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
	$show_inactive_status = $_POST['show_inactive_status_hemxxmh_makan'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemxxmh_makan' )
			->debug(true)
			->fields(
				Field::inst( 'hemxxmh_makan.id' ),
				Field::inst( 'hemxxmh_makan.id_hemxxmh' ),
				Field::inst( 'hemxxmh_makan.kode' ),
				Field::inst( 'hemxxmh_makan.nama' ),
				Field::inst( 'hemxxmh_makan.keterangan' ),
				Field::inst( 'hemxxmh_makan.is_active' ),
				Field::inst( 'hemxxmh_makan.is_makan_catering' ),
				Field::inst( 'hemxxmh_makan.tanggal_efektif' )
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
				Field::inst( 'hemxxmh_makan.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemxxmh_makan.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemxxmh_makan.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('hemxxmh_makan.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemxxmh_makan.is_active', 1);
		}
		
		include( "hemxxmh_makan_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>