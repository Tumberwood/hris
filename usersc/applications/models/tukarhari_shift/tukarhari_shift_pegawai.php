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
	$show_inactive_status = $_POST['show_inactive_status_tukarhari_shift_pegawai'];
	// -----------
	
	if ( ! isset($_POST['id_tukarhari_shift']) || ! is_numeric($_POST['id_tukarhari_shift']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'tukarhari_shift_pegawai' )
			->debug(true)
			->fields(
				Field::inst( 'tukarhari_shift_pegawai.id' ),
				Field::inst( 'tukarhari_shift_pegawai.id_tukarhari_shift' ),
				Field::inst( 'tukarhari_shift_pegawai.kode' ),
				Field::inst( 'tukarhari_shift_pegawai.nama' ),
				Field::inst( 'tukarhari_shift_pegawai.keterangan' ),
				Field::inst( 'tukarhari_shift_pegawai.is_active' ),
				Field::inst( 'tukarhari_shift_pegawai.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'tukarhari_shift_pegawai.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'tukarhari_shift_pegawai.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),

				Field::inst( 'hemxxmh.nama' ),
				Field::inst( 'hemxxmh.kode' ),
				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'heyxxmd.nama' ),
				Field::inst( 'hosxxmh.nama' )
			)
			->where('tukarhari_shift_pegawai.id_tukarhari_shift',$_POST['id_tukarhari_shift'])
			->leftJoin( 'hemxxmh','hemxxmh.id','=','tukarhari_shift_pegawai.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->leftJoin( 'heyxxmd','heyxxmd.id','=','hemjbmh.id_heyxxmd' )
			->leftJoin( 'hosxxmh','hosxxmh.id','=','hemjbmh.id_hosxxmh' )
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'tukarhari_shift_pegawai.is_active', 1);
		}
		
		include( "tukarhari_shift_pegawai_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>