<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {

			//SELECT TANGGAL TERPILIH / LIBUR
			$qs_section = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec(' SELECT
							id_hosxxmh
						FROM hosxxmh_htssctd_tukarhari AS a
						WHERE a.id_htssctd_tukarhari = :id;
						'
						);
			$rs_section = $qs_section->fetchAll();
			
			$tanggal_terpilih =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_terpilih']));
			$tanggal_pengganti =  date('Y-m-d', strtotime($values['htssctd_tukarhari']['tanggal_pengganti']));
			
			foreach ($rs_section as $key => $section) {
				$id_hosxxmh = $section['id_hosxxmh'];

				//INSERT PEGAWAI SESUAI SECTION TANGGAL PENGGANTI 27 Dec 2023
				$qs_pengganti = $editor->db()
					->raw()
					->bind(':tanggal', $tanggal_pengganti)
					->bind(':id_hosxxmh', $id_hosxxmh)
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
								AND b.id_hosxxmh = :id_hosxxmh
								AND c.is_tukar = 1;
							'
				);
			}
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
			
			$qs_section = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec(' SELECT
							id_hosxxmh
						FROM hosxxmh_htssctd_tukarhari AS a
						WHERE a.id_htssctd_tukarhari = :id;
						'
						);
			$rs_section = $qs_section->fetchAll();
			foreach ($rs_section as $key => $section) {
				$id_hosxxmh = $section['id_hosxxmh'];

				//INSERT PEGAWAI SESUAI SECTION TANGGAL PENGGANTI 27 Dec 2023
				$qs_pengganti = $editor->db()
					->raw()
					->bind(':tanggal', $tanggal_pengganti)
					->bind(':id_hosxxmh', $id_hosxxmh)
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
								AND b.id_hosxxmh = :id_hosxxmh
								AND c.is_tukar = 1;
							'
				);
			}
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>