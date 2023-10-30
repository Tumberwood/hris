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
	$select_bagian = 0;
	$show_inactive_status = 0;
	if (isset($_POST['select_bagian'])) {
		$select_bagian = $_POST['select_bagian'];
	}
	// -----------
	// print_r($_POST['id_hgsptth_v3']);
	if ( ! isset($_POST['id_hgsptth_v3']) || ! is_numeric($_POST['id_hgsptth_v3']) ) {
		echo json_encode( [ "data" => [] ] );
	}else{
		$editor = Editor::inst( $db, 'hgsemtd_v3' )
			->debug(true)
			->fields(
				Field::inst( 'hgsemtd_v3.id' ),
				Field::inst( 'hgsemtd_v3.id_hgsptth_v3' ),
				Field::inst( 'hgsemtd_v3.id_htsxxmh' ),
				Field::inst( 'hgsemtd_v3.id_htsptth_v3' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hgsemtd_v3.id_hemxxmh' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hgsemtd_v3.id_holxxmd' )
				->setFormatter( Format::ifEmpty( 0 ) ),
				Field::inst( 'hgsemtd_v3.kode' ),
				Field::inst( 'hgsemtd_v3.nama' ),
				Field::inst( 'hgsemtd_v3.shift' ),
				Field::inst( 'hgsemtd_v3.keterangan' ),
				Field::inst( 'hgsemtd_v3.is_active' ),
				Field::inst( 'hgsemtd_v3.created_by' )
					->set( Field::SET_CREATE )
					->setValue($_SESSION['user']),
				Field::inst( 'hgsemtd_v3.last_edited_by' )
					->set( Field::SET_EDIT )
					->setValue($_SESSION['user']),
				Field::inst( 'hgsemtd_v3.created_on' )
				->set( Field::SET_CREATE ),
				Field::inst( 'hgsemtd_v3.senin' ),
				Field::inst( 'hgsemtd_v3.selasa' ),
				Field::inst( 'hgsemtd_v3.rabu' ),
				Field::inst( 'hgsemtd_v3.kamis' ),
				Field::inst( 'hgsemtd_v3.jumat' ),
				Field::inst( 'hgsemtd_v3.sabtu' ),
				Field::inst( 'hgsemtd_v3.minggu' ),
				Field::inst( 'hgsemtd_v3.jam' ),
				
				Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' ),
				Field::inst( 'concat(htsxxmh.jam_awal," - ",htsxxmh.jam_akhir) as htsxxmh_data' )
			)
			->leftJoin( 'hemxxmh','hemxxmh.id','=','hgsemtd_v3.id_hemxxmh' )
			->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
			->leftJoin( 'htsxxmh','htsxxmh.id','=','hgsemtd_v3.id_htsxxmh' )
			->where('hgsemtd_v3.id_hgsptth_v3',$_POST['id_hgsptth_v3'])
			->where( function ( $q ) {
				$q
					->where('hgsemtd_v3.nama',"sabtu")
					->where( function ( $r ) {
						$r
							->where('hgsemtd_v3.shift', 1)
							->or_where('hgsemtd_v3.shift', 4)
							->or_where('hgsemtd_v3.shift', 5);
					} );
			} )
			;
		
		// do not erase
		// function show / hide inactive document
		if ($show_inactive_status == 0){
			$editor
				->where( 'hgsemtd_v3.is_active', 1);
		}

		if ($select_bagian > 0){
			$editor
			->where('hgsemtd_v3.id_holxxmd',$select_bagian);
		}
		
		include( "hgsemtd_v3_sabtu_extra.php" );
		include( "../../../helpers/edt_log.php" );
		
		$editor
			->process( $_POST )
			->json();
	}
?>