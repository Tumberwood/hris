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
	$show_inactive_status = $_POST['show_inactive_status_htsemtd'];
	// -----------
	
	if ( ! isset($_POST['id_htsptth']) || ! is_numeric($_POST['id_htsptth']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htsemtd' )
			->debug(true)
			->fields(
				Field::inst( 'htsemtd.id' ),
				Field::inst( 'htsemtd.id_htsptth' ),
				Field::inst( 'htsemtd.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htsemtd.kode' ),
				Field::inst( 'htsemtd.nama' ),
				Field::inst( 'htsemtd.keterangan' ),
				Field::inst( 'htsemtd.is_active' ),
				Field::inst( 'htsemtd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htsemtd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htsemtd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htsemtd.grup_ke' ),

				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),

				Field::inst( 'hodxxmh.nama' ),
				Field::inst( 'hetxxmh.nama' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','htsemtd.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->where('htsemtd.id_htsptth',$_POST['id_htsptth']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htsemtd.is_active', 1);
		}
		
		include( "htsemtd_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>