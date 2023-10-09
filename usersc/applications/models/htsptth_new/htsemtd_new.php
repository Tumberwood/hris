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
	$show_inactive_status = $_POST['show_inactive_status_htsemtd_new'];
	// -----------
	
	if ( ! isset($_POST['id_htsptth_new']) || ! is_numeric($_POST['id_htsptth_new']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'htsemtd_new' )
			->debug(true)
			->fields(
				Field::inst( 'htsemtd_new.id' ),
				Field::inst( 'htsemtd_new.id_htsptth_new' ),
				Field::inst( 'htsemtd_new.id_hemxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'htsemtd_new.kode' ),
				Field::inst( 'htsemtd_new.nama' ),
				Field::inst( 'htsemtd_new.keterangan' ),
				Field::inst( 'htsemtd_new.is_active' ),
				Field::inst( 'htsemtd_new.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'htsemtd_new.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'htsemtd_new.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'htsemtd_new.grup' ),

				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),

				Field::inst( 'hodxxmh.nama' ),
				Field::inst( 'hetxxmh.nama' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','htsemtd_new.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->where('htsemtd_new.id_htsptth_new',$_POST['id_htsptth_new']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'htsemtd_new.is_active', 1);
		}
		
		include( "htsemtd_new_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>