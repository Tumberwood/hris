<?php
	use Carbon\Carbon;
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$id_hemxxmh = $values['hemxxmh_medical_leave']['id_hemxxmh'];

			$qs_medic = $editor->db()
				->raw()
				->bind(':id_hemxxmh', $id_hemxxmh)
				->exec('UPDATE hemxxmh a
						JOIN (
							SELECT id_hemxxmh, medical_leave
							FROM hemxxmh_medical_leave
							WHERE id_hemxxmh = :id_hemxxmh
							ORDER BY tanggal_efektif DESC
							LIMIT 1
						) b ON a.id = b.id_hemxxmh
						SET a.medical_leave = b.medical_leave
						WHERE a.id = :id_hemxxmh
				');
		})
		->on('preEdit',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			$id_hemxxmh = $values['hemxxmh_medical_leave']['id_hemxxmh'];

			$qs_medic = $editor->db()
				->raw()
				->bind(':id_hemxxmh', $id_hemxxmh)
				->exec('UPDATE hemxxmh a
						JOIN (
							SELECT id_hemxxmh, medical_leave
							FROM hemxxmh_medical_leave
							WHERE id_hemxxmh = :id_hemxxmh
							ORDER BY tanggal_efektif DESC
							LIMIT 1
						) b ON a.id = b.id_hemxxmh
						SET a.medical_leave = b.medical_leave
						WHERE a.id = :id_hemxxmh
				');
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>