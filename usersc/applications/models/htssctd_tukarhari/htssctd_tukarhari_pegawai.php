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
	$show_inactive_status = $_POST['show_inactive_status_htssctd_tukarhari_pegawai'];
	// -----------
	
	if ( ! isset($_POST['id_htssctd_tukarhari']) || ! is_numeric($_POST['id_htssctd_tukarhari']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htssctd_tukarhari_pegawai' )
			->debug(true)
			->fields(
				Field::inst( 'htssctd_tukarhari_pegawai.id' ),
				Field::inst( 'htssctd_tukarhari_pegawai.id_htssctd_tukarhari' ),
				Field::inst( 'htssctd_tukarhari_pegawai.kode' ),
				Field::inst( 'htssctd_tukarhari_pegawai.nama' ),
				Field::inst( 'htssctd_tukarhari_pegawai.keterangan' ),
				Field::inst( 'htssctd_tukarhari_pegawai.is_active' ),
				Field::inst( 'htssctd_tukarhari_pegawai.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htssctd_tukarhari_pegawai.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htssctd_tukarhari_pegawai.id_hemxxmh' ),

				Field::inst( 'hemxxmh.nama' ),
				Field::inst( 'hemxxmh.kode' ),
				Field::inst( 'hetxxmh.nama' ),
				Field::inst( 'hodxxmh.nama' )
			)
			->where('htssctd_tukarhari_pegawai.id_htssctd_tukarhari',$_POST['id_htssctd_tukarhari'])
			->leftJoin( 'hemxxmh','hemxxmh.id','=','htssctd_tukarhari_pegawai.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htssctd_tukarhari_pegawai.is_active', 1);
		}
		
		include( "htssctd_tukarhari_pegawai_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>