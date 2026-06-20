<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$tanggal_terpilih =  date('Y-m-d', strtotime($values['tukarhari_shift']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['tukarhari_shift']['tanggal_pengganti']));

			//INSERT PEGAWAI SESUAI SECTION TANGGAL PENGGANTI 27 Dec 2023
			$qs_pengganti = $editor->db()
				->raw()
				->bind(':tanggal', $tanggal_pengganti)
				->bind(':id', $id)
				->exec(' INSERT INTO tukarhari_shift_pegawai
						(
							id_tukarhari_shift,
							id_hemxxmh
						)  
						SELECT
							:id,
							a.id_hemxxmh
						FROM htssctd AS a
						LEFT JOIN hemjbmh as b on b.id_hemxxmh = a.id_hemxxmh
						LEFT JOIN hemxxmh as c on c.id = a.id_hemxxmh
						WHERE a.tanggal = :tanggal 
							AND a.id_htsxxmh <> 1 
							AND a.is_active = 1
							AND c.is_active = 1
							AND (c.is_tukar <> -9 OR c.is_tukar IS NULL)
							AND (
								b.tanggal_keluar IS NULL OR 
								b.tanggal_keluar >= :tanggal
							)
						;
						'
			);
		})
		->on('preEdit',function( $editor, $id, $values ) {

		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			
			$tanggal_terpilih =  date('Y-m-d', strtotime($values['tukarhari_shift']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['tukarhari_shift']['tanggal_pengganti']));

			$qd_tukarhari_shift_pegawai = $editor->db()
				->query('delete', 'tukarhari_shift_pegawai')
				->where('id_tukarhari_shift',$id)
				->exec();
			
			//INSERT PEGAWAI SESUAI SECTION TANGGAL PENGGANTI 27 Dec 2023
			$qs_pengganti = $editor->db()
				->raw()
				->bind(':tanggal', $tanggal_pengganti)
				->bind(':id', $id)
				->exec(' INSERT INTO tukarhari_shift_pegawai
						(
							id_tukarhari_shift,
							id_hemxxmh
						)  
						SELECT
							:id,
							a.id_hemxxmh
						FROM htssctd AS a
						LEFT JOIN hemjbmh as b on b.id_hemxxmh = a.id_hemxxmh
						LEFT JOIN hemxxmh as c on c.id = a.id_hemxxmh
						WHERE a.tanggal = :tanggal 
							AND a.id_htsxxmh <> 1 
							AND a.is_active = 1
							AND c.is_active = 1
							AND (c.is_tukar <> -9 OR c.is_tukar IS NULL)
							AND (
								b.tanggal_keluar IS NULL OR 
								b.tanggal_keluar >= :tanggal
							)
						;
						'
			);
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>