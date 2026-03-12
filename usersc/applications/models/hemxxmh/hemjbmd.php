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
	$show_inactive_status = $_POST['show_inactive_status_hemjbmd'];
	// -----------
	
	if ( ! isset($_POST['id_hemxxmh']) || ! is_numeric($_POST['id_hemxxmh']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hemjbmd' )
			->debug(true)
			->fields(
				Field::inst( 'hemjbmd.id' ),
				Field::inst( 'hemjbmd.id_hemxxmh' ),
				Field::inst( 'hemjbmd.id_gctxxmh' )
					->setFormatter( Format::ifEmpty( 0 ) )
					->options( Options::inst()
						->table( 'v_gctxxmh_gpvxxmh' )
						->value( 'id' )
						->label( ['gpvxxmh_nama','nama'] )
						->where( function ($q) {
							$q->where( 'is_active', 1 );
						})
						->render( function ( $row ) {
							return $row['gpvxxmh_nama'] . ' - ' . $row['nama'];
						} )
						->order( 'nama' )
					),
				Field::inst( 'hemjbmd.tanggal_awal' )
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
				Field::inst( 'hemjbmd.tanggal_akhir' )
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
				Field::inst( 'hemjbmd.kode' ),
				Field::inst( 'hemjbmd.nama' ),
				Field::inst( 'hemjbmd.keterangan' ),
				Field::inst( 'hemjbmd.is_active' ),
				Field::inst( 'hemjbmd.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hemjbmd.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hemjbmd.created_on' )
					->set( Field::SET_CREATE ),
				Field::inst( 'hemjbmd.alamat' ),
				Field::inst( 'hemjbmd.jenis' ),
				Field::inst( 'hemjbmd.gaji' ),
				Field::inst( 'hemjbmd.jabatan_awal' ),
				Field::inst( 'hemjbmd.jabatan_akhir' ),
				Field::inst( 'hemjbmd.nama_atasan' ),

				Field::inst( 'gctxxmh.nama' )
			)
			->leftJoin( 'gctxxmh','gctxxmh.id','=','hemjbmd.id_gctxxmh' )
			->where('hemjbmd.id_hemxxmh',$_POST['id_hemxxmh']);
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hemjbmd.is_active', 1);
		}
		
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>