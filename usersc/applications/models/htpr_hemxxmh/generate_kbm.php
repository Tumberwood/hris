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
	
		$editor = Editor::inst( $db, 'htpr_hemxxmh' )
			->debug(true)
			->fields(
				Field::inst( 'htpr_hemxxmh.id' )
				->set( Field::SET_NONE ),
			);

		$editor
			->on('preCreate',function( $editor, $values ) {
				// print_r( $values);
				$id_hpcxxmh = $values['htpr_hemxxmh']['id_hpcxxmh'];
				$nominal = $values['htpr_hemxxmh']['nominal'];
				$tanggal_efektif = date('Y-m-d', strtotime($values['htpr_hemxxmh']['tanggal_efektif']));
				// print_r( $id_hpcxxmh);

				$qi_htpr_hemxxmh = $editor->db()
					->raw()
					->bind(':id_hpcxxmh', $id_hpcxxmh)
					->bind(':nominal', $nominal)
					->bind(':tanggal_efektif', $tanggal_efektif)
					->exec('INSERT INTO htpr_hemxxmh (
								id_hemxxmh,
								id_hpcxxmh,
								nominal,
								tanggal_efektif
							)
							SELECT
								a.id,
								:id_hpcxxmh,
								:nominal,
								:tanggal_efektif
							FROM hemxxmh a
							LEFT JOIN hemjbmh b ON b.id_hemxxmh = a.id
							WHERE (b.tanggal_keluar IS NULL OR b.tanggal_keluar > :tanggal_efektif) AND b.id_heyxxmd = 1 
							AND a.id NOT IN (
								SELECT 
									id_hemxxmh
								FROM htpr_hemxxmh 
								WHERE id_hpcxxmh = :id_hpcxxmh AND tanggal_efektif = :tanggal_efektif
							)
				');

		});
		
		$editor
			->process( $_POST )
			->json();
?>