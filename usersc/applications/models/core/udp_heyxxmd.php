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
	$show_inactive_status = $_POST['show_inactive_status'];
	// -----------
	if ( ! isset($_POST['id_udpxxsh']) || ! is_numeric($_POST['id_udpxxsh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'udp_heyxxmd' )
			->debug(true)
			->fields(
				Field::inst( 'udp_heyxxmd.id' ),
				Field::inst( 'udp_heyxxmd.id_udpxxsh' ),
				Field::inst( 'udp_heyxxmd.id_heyxxmd' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'udp_heyxxmd.kode' ),
				Field::inst( 'udp_heyxxmd.nama' ),
				Field::inst( 'udp_heyxxmd.keterangan' ),
				Field::inst( 'udp_heyxxmd.is_active' ),
				Field::inst( 'udp_heyxxmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'udp_heyxxmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'udp_heyxxmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'heyxxmd.nama' )
			)
			->leftJoin( 'heyxxmd','heyxxmd.id','=','udp_heyxxmd.id_heyxxmd' )
			->where('udp_heyxxmd.id_udpxxsh',$_POST['id_udpxxsh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'udp_heyxxmd.is_active', 1);
		}
		
		// input log
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>