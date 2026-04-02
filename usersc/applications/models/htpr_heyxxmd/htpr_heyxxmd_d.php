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
	$show_inactive_status = $_POST['show_inactive_status_htpr_heyxxmd'];
	// -----------
	
	if ( ! isset($_POST['id_heyxxmd']) || ! is_numeric($_POST['id_heyxxmd']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htpr_heyxxmd' )
			->debug(true)
			->fields(
				Field::inst( 'htpr_heyxxmd.id' ),
				Field::inst( 'htpr_heyxxmd.id_heyxxmd' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_heyxxmd.id_hpcxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htpr_heyxxmd.kode' ),
				Field::inst( 'htpr_heyxxmd.nama' ),
				Field::inst( 'htpr_heyxxmd.keterangan' ),
				Field::inst( 'htpr_heyxxmd.is_active' ),
				Field::inst( 'htpr_heyxxmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_heyxxmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htpr_heyxxmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htpr_heyxxmd.tanggal_efektif' )
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
				Field::inst( 'htpr_heyxxmd.nominal' ),

				Field::inst( 'hpcxxmh.nama' )
			)
			->leftJoin( 'hpcxxmh','hpcxxmh.id','=','htpr_heyxxmd.id_hpcxxmh' )
			->where('htpr_heyxxmd.id_heyxxmd',$_POST['id_heyxxmd']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htpr_heyxxmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>