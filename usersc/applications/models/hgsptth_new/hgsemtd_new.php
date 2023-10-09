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
	$show_inactive_status = $_POST['show_inactive_status_hgsemtd_new'];
	// -----------
	
	if ( ! isset($_POST['id_hgsptth_new']) || ! is_numeric($_POST['id_hgsptth_new']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hgsemtd_new' )
			->debug(true)
			->fields(
				Field::inst( 'hgsemtd_new.id' ),
				Field::inst( 'hgsemtd_new.id_hgsptth_new' ),
				Field::inst( 'hgsemtd_new.id_hemxxmh' ),
				Field::inst( 'hgsemtd_new.kode' ),
				Field::inst( 'hgsemtd_new.nama' ),
				Field::inst( 'hgsemtd_new.keterangan' ),
				Field::inst( 'hgsemtd_new.is_active' ),
				Field::inst( 'hgsemtd_new.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hgsemtd_new.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hgsemtd_new.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hgsemtd_new.senin' ),
				Field::inst( 'hgsemtd_new.selasa' ),
				Field::inst( 'hgsemtd_new.rabu' ),
				Field::inst( 'hgsemtd_new.kamis' ),
				Field::inst( 'hgsemtd_new.jumat' ),
				Field::inst( 'hgsemtd_new.sabtu' ),
				Field::inst( 'hgsemtd_new.minggu' ),
				
				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama, " - ",hetxxmh.nama) as hemxxmh_data' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hgsemtd_new.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' )
			->where('hgsemtd_new.id_hgsptth_new',$_POST['id_hgsptth_new']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hgsemtd_new.is_active', 1);
		}
		
		include( "hgsemtd_new_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>