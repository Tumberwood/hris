<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$id_periode_payroll = $values['bpjs_h']['id_periode_payroll'];
			$qu_bpjs_h = $editor->db()
				->raw()
				->bind(':id', $id)
				->bind(':id_periode_payroll', $id_periode_payroll)
				->exec('UPDATE bpjs_h a
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
			$id_periode_payroll = $values['bpjs_h']['id_periode_payroll'];
			$qu_bpjs_h = $editor->db()
				->raw()
				->bind(':id', $id)
				->bind(':id_periode_payroll', $id_periode_payroll)
				->exec('UPDATE bpjs_h a
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