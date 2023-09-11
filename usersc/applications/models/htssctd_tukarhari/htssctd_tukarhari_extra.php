<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {

			//SELECT TANGGAL TERPILIH / LIBUR
			// $qs_terpilih = $editor->db()
			// 	->raw()
			// 	->bind(':tanggal', $tanggal_terpilih)
			// 	->exec(' SELECT
			// 				a.id_hemxxmh
			// 			FROM htssctd AS a
			// 			WHERE a.tanggal = :tanggal 
			// 				AND a.id_htsxxmh <> 1 
			// 				AND a.is_active = 1;
			// 			'
			// 			);
			// $rs_terpilih = $qs_terpilih->fetchAll();
			
			$tanggal_terpilih =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_pengganti']));

			//SELECT TANGGAL PENGGANTI
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
					WHERE a.tanggal = :tanggal 
						AND a.id_htsxxmh <> 1 
						AND a.is_active = 1;
					'
					);
		})
		->on('preEdit',function( $editor, $id, $values ) {

			$tanggal_terpilih =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_pengganti']));

			$qs_tukar_hari = $editor->db()
				->query('select', 'htssctd_tukarhari' )
				->get(['tanggal_pengganti'] )
				->where('id', $id )
				->exec();
			$rs_tukar_hari = $qs_tukar_hari->fetch();	
			// print_r($tanggal_pengganti);
			// print_r($rs_tukar_hari['tanggal_pengganti']);

			if ($tanggal_pengganti != $rs_tukar_hari['tanggal_pengganti']) {

				$qd_htssctd_tukarhari_pegawai = $editor->db()
					->query('delete', 'htssctd_tukarhari_pegawai')
					->where('id_htssctd_tukarhari',$id)
					->exec();
					
				//SELECT TANGGAL PENGGANTI
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
						WHERE a.tanggal = :tanggal 
							AND a.id_htsxxmh <> 1 
							AND a.is_active = 1;
						'
						);
				}
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>