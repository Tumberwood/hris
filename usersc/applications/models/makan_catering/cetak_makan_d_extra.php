<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$id_cetak_makan_h = $values['cetak_makan_d']['id_cetak_makan_h'];

			$qu_jumlah_orang = $editor->db()
				->raw()
				->bind(':id_cetak_makan_h', $id_cetak_makan_h)
				->exec('UPDATE cetak_makan_h a
						LEFT JOIN (
							SELECT
								id_cetak_makan_h,
								COUNT(id) c_id
							FROM cetak_makan_d
							WHERE is_active = 1 AND id_cetak_makan_h = :id_cetak_makan_h
						) b ON b.id_cetak_makan_h = a.id
						SET
							jumlah_orang = c_id
						WHERE a.id = :id_cetak_makan_h
			');
		})
		->on('preEdit',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			$id_cetak_makan_h = $values['cetak_makan_d']['id_cetak_makan_h'];

			$qu_jumlah_orang = $editor->db()
				->raw()
				->bind(':id_cetak_makan_h', $id_cetak_makan_h)
				->exec('UPDATE cetak_makan_h a
						LEFT JOIN (
							SELECT
								id_cetak_makan_h,
								COUNT(id) c_id
							FROM cetak_makan_d
							WHERE is_active = 1 AND id_cetak_makan_h = :id_cetak_makan_h
						) b ON b.id_cetak_makan_h = a.id
						SET
							jumlah_orang = c_id
						WHERE a.id = :id_cetak_makan_h
			');
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>