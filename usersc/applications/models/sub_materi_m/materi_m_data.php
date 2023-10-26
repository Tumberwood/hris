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
	
	if ( ! isset($_GET['id_sub_materi_m']) || ! is_numeric($_GET['id_sub_materi_m']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'materi_m' )
			->debug(true)
			->fields(
				Field::inst( 'materi_m.id' ),
				Field::inst( 'materi_m.id_sub_materi_m' ),
				Field::inst( 'materi_m.kode' ),
				Field::inst( 'materi_m.nama' ),
				Field::inst( 'materi_m.link_yt' ),
				Field::inst( 'materi_m.keterangan' ),
				Field::inst( 'materi_m.is_active' ),
				Field::inst( 'materi_m.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'materi_m.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'materi_m.created_on' )
					->set( Field::SET_CREATE )
			)
			->where('materi_m.id_sub_materi_m',$_GET['id_sub_materi_m'])
			;
		
		// include( "materi_m_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_GET )
			->json();
	}
?>