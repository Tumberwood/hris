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
	$show_inactive_status = $_POST['show_inactive_status_gdnxxsh'];
	// -----------
	
	$editor = Editor::inst( $db, 'gdnxxsh' )
		->debug(true)
		->fields(
			Field::inst( 'gdnxxsh.id' ),
			Field::inst( 'gdnxxsh.mode' )
				->validator( Validate::notEmpty( ValidateOptions::inst()
					->message( 'Wajib Diisi!' )
				)),
			Field::inst( 'gdnxxsh.nama' )
				->validator( Validate::notEmpty( ValidateOptions::inst()
					->message( 'Wajib Diisi!' )
				)),
			Field::inst( 'gdnxxsh.nama_tabel' )
				->setFormatter( Format::ifEmpty( 0 ) )
				->options( Options::inst()
					->table( 'v_tablelist' )
					->value( 'id' )
					->label( 'nama' )
				)
				->validator( Validate::unique( ValidateOptions::inst()
					->message( 'Data tidak boleh kembar!' )
				)),
			Field::inst( 'gdnxxsh.kategori_dokumen' ),
			Field::inst( 'gdnxxsh.kategori_dokumen_value' ),
			Field::inst( 'gdnxxsh.prefix' ),
			Field::inst( 'gdnxxsh.suffix' ),
			Field::inst( 'gdnxxsh.total_digit' )
				->validator( Validate::notEmpty( ValidateOptions::inst()
					->message( 'Wajib Diisi!' )
				)),
			Field::inst( 'gdnxxsh.reset_by' ),
			Field::inst( 'gdnxxsh.separator' ),
			Field::inst( 'gdnxxsh.by_company' ),
			Field::inst( 'gdnxxsh.by_cabang' ),
			Field::inst( 'gdnxxsh.keterangan' ),
			Field::inst( 'gdnxxsh.is_active' ),
			Field::inst( 'gdnxxsh.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'gdnxxsh.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'gdnxxsh.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'gdnxxsh.field_tanggal' )
				->validator( Validate::notEmpty( ValidateOptions::inst()
					->message( 'Wajib Diisi!' )
				)),

			Field::inst( 'v_tablelist.nama' )
			
		)
		->leftJoin( 'v_tablelist','v_tablelist.id','=','gdnxxsh.nama_tabel' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'gdnxxsh.is_active', 1);
	}
	
	// input log
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>