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
	$show_inactive_status = $_POST['show_inactive_status_hcdjbmd'];
	// -----------
	
	if ( ! isset($_POST['id_hcdxxmh']) || ! is_numeric($_POST['id_hcdxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hcdjbmd' )
			->debug(true)
			->fields(
				Field::inst( 'hcdjbmd.id' ),
				Field::inst( 'hcdjbmd.id_hcdxxmh' ),
				Field::inst( 'hcdjbmd.id_gctxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hcdjbmd.tanggal_awal' )
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
				Field::inst( 'hcdjbmd.tanggal_akhir' )
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
				Field::inst( 'hcdjbmd.kode' ),
				Field::inst( 'hcdjbmd.nama' ),
				Field::inst( 'hcdjbmd.keterangan' ),
				Field::inst( 'hcdjbmd.is_active' ),
				Field::inst( 'hcdjbmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdjbmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hcdjbmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hcdjbmd.alamat' ),
				Field::inst( 'hcdjbmd.jenis' ),
				Field::inst( 'hcdjbmd.gaji' ),
				Field::inst( 'hcdjbmd.jabatan_awal' ),
				Field::inst( 'hcdjbmd.jabatan_akhir' ),
				Field::inst( 'hcdjbmd.nama_atasan' ),

				Field::inst( 'gctxxmh.nama' )
			)
			->leftJoin( 'gctxxmh','gctxxmh.id','=','hcdjbmd.id_gctxxmh' )
			->where('hcdjbmd.id_hcdxxmh',$_POST['id_hcdxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hcdjbmd.is_active', 1);
		}
		
		include( "hcdjbmd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>