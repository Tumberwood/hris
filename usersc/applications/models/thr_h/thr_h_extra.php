<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {

			$qu_thr_h = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec('UPDATE thr_h a
						LEFT JOIN periode_payroll b on b.id = a.id_periode_payroll
						SET
							a.tanggal_awal = b.tanggal_awal,
							a.tanggal_akhir = b.tanggal_akhir
						WHERE a.id = :id
			');
		})
		->on('preEdit',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {

			$qu_thr_h = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec('UPDATE thr_h a
						LEFT JOIN periode_payroll b on b.id = a.id_periode_payroll
						SET
							a.tanggal_awal = b.tanggal_awal,
							a.tanggal_akhir = b.tanggal_akhir
						WHERE a.id = :id
			');
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>