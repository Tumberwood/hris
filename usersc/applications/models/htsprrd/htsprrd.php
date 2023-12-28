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
	$show_inactive_status = $_POST['show_inactive_status_htsprrd'];
	// -----------
	
	$editor = Editor::inst( $db, 'htsprrd' )
		->debug(true)
		->fields(
			Field::inst( 'htsprrd.id' ),
			Field::inst( 'htsprrd.kode' )
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			Field::inst( 'htsprrd.nama' )
				->setFormatter( function ( $val ) {
					return ucwords($val);
				} ),
			Field::inst( 'htsprrd.keterangan' ),
			Field::inst( 'htsprrd.is_active' ),
			Field::inst( 'htsprrd.id_hemxxmh' ),
			Field::inst( 'htsprrd.created_by' )
				->set( Field::SET_CREATE )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprrd.created_on' )
				->set( Field::SET_CREATE ),
			Field::inst( 'htsprrd.last_edited_by' )
				->set( Field::SET_EDIT )
				->setValue($_SESSION['user']),
			Field::inst( 'htsprrd.is_approve' ),
			Field::inst( 'htsprrd.is_defaultprogram' ),
			Field::inst( 'htsprrd.kode_finger' ),
			Field::inst( 'htsprrd.tanggal' )
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === '0000-00-00' || $val === null){
						echo '';
						}else{
							return date( 'd M Y', strtotime( $val ) );
					}
				} ),
			Field::inst( 'htsprrd.st_jadwal' ),
			Field::inst( 'htsprrd.shift_in' ),
			Field::inst( 'htsprrd.shift_out' ),
			// Field::inst( 'htsprrd.tanggaljam_awal_t1' ),
			// Field::inst( 'htsprrd.tanggaljam_awal_t2' ),
			// Field::inst( 'htsprrd.tanggaljam_akhir_t1' ),
			// Field::inst( 'htsprrd.tanggaljam_akhir_t2' ),
			Field::inst( 'htsprrd.clock_in')
				->getFormatter( 'Format::datetime', array(
					'from' => 'Y-m-d H:i:s',
					'to' =>   'd M Y H:i:s'
				) )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y H:i:s',
					'to' =>   'Y-m-d H:i:s'
				) ),
			Field::inst( 'htsprrd.clock_out' )
				->getFormatter( 'Format::datetime', array(
					'from' => 'Y-m-d H:i:s',
					'to' =>   'd M Y H:i:s'
				) )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y H:i:s',
					'to' =>   'Y-m-d H:i:s'
				) ),
			Field::inst( 'htsprrd.st_clock_in' ),
			Field::inst( 'htsprrd.st_clock_out' ),
			Field::inst( 'htsprrd.status_presensi_in' ),
			Field::inst( 'htsprrd.status_presensi_out' ),
			Field::inst( 'htsprrd.htlxxrh_kode' ),
			Field::inst( 'htsprrd.cek' ),
			Field::inst( 'htsprrd.pot_jam' ),
			Field::inst( 'htsprrd.pot_ti' ),
			Field::inst( 'htsprrd.pot_overtime' ),
			Field::inst( 'htsprrd.pot_hk' ),
			Field::inst( 'htsprrd.durasi_lembur_final' ),
			Field::inst( 'htsprrd.is_makan' ),

			Field::inst( 'htsprrd.jam_awal_lembur_libur' ),
			Field::inst( 'htsprrd.jam_akhir_lembur_libur' ),
			Field::inst( 'htsprrd.durasi_lembur_libur' ),
			Field::inst( 'htsprrd.jam_awal_lembur_awal' ),
			Field::inst( 'htsprrd.jam_akhir_lembur_awal' ),
			Field::inst( 'htsprrd.durasi_lembur_awal' ),
			Field::inst( 'htsprrd.jam_awal_lembur_akhir' ),
			Field::inst( 'htsprrd.jam_akhir_lembur_akhir' ),
			Field::inst( 'htsprrd.durasi_lembur_akhir' ),
			Field::inst( 'htsprrd.jam_awal_lembur_istirahat1' ),
			Field::inst( 'htsprrd.jam_akhir_lembur_istirahat1' ),
			Field::inst( 'htsprrd.durasi_lembur_istirahat1' ),
			Field::inst( 'htsprrd.jam_awal_lembur_istirahat2' ),
			Field::inst( 'htsprrd.jam_akhir_lembur_istirahat2' ),
			Field::inst( 'htsprrd.durasi_lembur_istirahat2' ),
			Field::inst( 'htsprrd.jam_awal_lembur_istirahat3' ),
			Field::inst( 'htsprrd.jam_akhir_lembur_istirahat3' ),
			Field::inst( 'htsprrd.durasi_lembur_istirahat3' ),
			Field::inst( 'htsprrd.durasi_lembur_total_jam' ),
			
			Field::inst( 'hodxxmh.nama' ),
			Field::inst( 'hemjbmh.id_heyxxmd' ),
			Field::inst( 'hetxxmh.nama' ),
			Field::inst( 'heyxxmh.nama' ),

			Field::inst( 'concat(hemxxmh.kode," - ",hemxxmh.nama) as hemxxmh_data' )
		)
		->leftJoin( 'hemxxmh','hemxxmh.id','=','htsprrd.id_hemxxmh' )
		->leftJoin( 'hemjbmh','hemjbmh.id_hemxxmh','=','hemxxmh.id' )
		->leftJoin( 'hodxxmh','hodxxmh.id','=','hemjbmh.id_hodxxmh' )
		->leftJoin( 'heyxxmh','heyxxmh.id','=','hemjbmh.id_heyxxmh' )
		->leftJoin( 'hetxxmh','hetxxmh.id','=','hemjbmh.id_hetxxmh' );
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( 'htsprrd.is_active', 1);
	}

	if ($_SESSION['user'] > 100){
		$editor
			->where( function ( $q ) {
				$q->where('hemjbmh.id_heyxxmh', '(' . $_SESSION['str_arr_ha_heyxxmh'] . ')', 'IN', false );
			} );
	}

	if($_POST['id_hemxxmh'] > 0){
		$editor->where( 'htsprrd.id_hemxxmh', $_POST['id_hemxxmh'] );
	}

	if($_POST['start_date'] != '' && $_POST['end_date'] != ''){
		$editor
			->where( 'htsprrd.tanggal', $_POST['start_date'], '>=' )
			->where( 'htsprrd.tanggal', $_POST['end_date'], '<=' );
	}


	
	include( "htsprrd_extra.php" );
	include( "../../../helpers/edt_log.php" );
	
	$editor
		->process( $_POST )
		->json();
?>