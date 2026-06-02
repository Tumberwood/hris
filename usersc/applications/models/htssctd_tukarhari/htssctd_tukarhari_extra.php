<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$tanggal_terpilih =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_pengganti']));

			//INSERT PEGAWAI SESUAI SECTION TANGGAL PENGGANTI 27 Dec 2023
			$qs_pengganti = $editor->db()
				->raw()
				->bind(':tanggal', $tanggal_pengganti)
				->bind(':id', $id)
				->exec(' INSERT INTO htssctd_tukarhari_pegawai
						(
							id_htssctd_tukarhari,
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
						;
						'
			);
		})
		->on('preEdit',function( $editor, $id, $values ) {

		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			
			$tanggal_terpilih =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_pengganti']));

			$qd_htssctd_tukarhari_pegawai = $editor->db()
				->query('delete', 'htssctd_tukarhari_pegawai')
				->where('id_htssctd_tukarhari',$id)
				->exec();
			
			//INSERT PEGAWAI SESUAI SECTION TANGGAL PENGGANTI 27 Dec 2023
			$qs_pengganti = $editor->db()
				->raw()
				->bind(':tanggal', $tanggal_pengganti)
				->bind(':id', $id)
				->exec(' INSERT INTO htssctd_tukarhari_pegawai
						(
							id_htssctd_tukarhari,
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