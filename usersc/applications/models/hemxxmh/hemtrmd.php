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
	$show_inactive_status = $_POST['show_inactive_status_hemtrmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemtrmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemtrmd.id' ),
				Field::inst( 'hemtrmd.id_hemxxmh' ),
				Field::inst( 'hemtrmd.tanggal_mulai' )
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
				Field::inst( 'hemtrmd.tanggal_selesai' )
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
				Field::inst( 'hemtrmd.kode' ),
				Field::inst( 'hemtrmd.nama' ),
				Field::inst( 'hemtrmd.keterangan' ),
				Field::inst( 'hemtrmd.is_active' ),
				Field::inst( 'hemtrmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemtrmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemtrmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hemtrmd.lembaga' ),
				Field::inst( 'hemtrmd.bersertifikat' )
			)
			->where('hemtrmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemtrmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>