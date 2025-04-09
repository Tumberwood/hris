<?php
	include_once( "../../../../users/init.php" );
	include_once( "../../../../usersc/lib/DataTables.php" );
	include_once( "../../../../usersc/vendor/autoload.php");
	
	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\SearchPaneOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result,
		Carbon\Carbon,
		SolusiIndonesia\Utilities\General;
	
	
	$editor = Editor::inst( $db, 'htsprtd' )
		->debug(true)
		->fields(
			Field::inst( 'htsprtd.id' )
				->set( Field::SET_NONE ),
			Field::inst( 'htsprtd.id_hemxxmh' )
				->set( Field::SET_NONE )
		)
		;
	
	$editor
		->on('preCreate',function( $editor, $values ) {
			$id_hemxxmh = $values['htsprtd']['id_hemxxmh'];
			$nama = $values['htsprtd']['nama'];
			$tanggal = date('Y-m-d', strtotime($values['htsprtd']['tanggal']));
			$jam = $values['htsprtd']['jam'];
			$keterangan = $values['htsprtd']['keterangan'];
			// print_r($tanggal);

			foreach ($id_hemxxmh as $key => $id_hem) {
				$qd_htsprtd = $editor->db()
					->query('delete', 'htsprtd')
					->where('id_hemxxmh', $id_hem)
					->where('nama', $nama)
					->where('tanggal', $tanggal)
					->where('jam', $jam)
					->where('keterangan', $keterangan)
					->exec();

				$qi_hemxxmh = $editor->db()
					->raw()
					->bind(':id_hem', $id_hem)
					->bind(':nama', $nama)
					->bind(':tanggal', $tanggal)
					->bind(':jam', $jam)
					->bind(':keterangan', $keterangan)
					->exec('INSERT INTO htsprtd (
								id_hemxxmh,
								kode,
								nama,
								tanggal,
								jam,
								keterangan
							)
							SELECT 
								id,
								kode_finger,
								:nama,
								:tanggal,
								:jam,
								:keterangan
							FROM hemxxmh 
							WHERE id = :id_hem
					');
			}
			
		});
	
	$json = $editor
		->process( $_POST )
		->data();

	echo json_encode( $json );
?>