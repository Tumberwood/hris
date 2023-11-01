<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {

			$qs_jadwal = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec(' SELECT
							b.tanggal_awal,
							b.tanggal_akhir,
							a.id_hemxxmh,
							a.id_htsxxmh
						FROM hgsemtd_v3 AS a
						LEFT JOIN hgsptth_v3 AS b ON b.id = a.id_hgsptth_v3
						WHERE a.id = :id
						'
						);
			$rs_jadwal = $qs_jadwal->fetch();
			$tanggal_awal = $rs_jadwal['tanggal_awal'];
			$tanggal_akhir = $rs_jadwal['tanggal_akhir'];
			$id_hemxxmh = $rs_jadwal['id_hemxxmh'];
			
			//DELETE SCHEDULE LAMA
			$qd_schedule = $editor->db()
				->raw()
				->bind(':tanggal_awal', $tanggal_awal)
				->bind(':tanggal_akhir', $tanggal_akhir)
				->bind(':id_hemxxmh', $id_hemxxmh)
				->exec('DELETE FROM htssctd
						WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir 
							AND DAYOFWEEK(tanggal) = 7
							AND id_hemxxmh = :id_hemxxmh 
							;
							'
				);

			$qi_schedule = $editor->db()
				->raw()
				->bind(':id_hemxxmh', $id_hemxxmh)
				->bind(':tanggal_awal', $tanggal_awal)
				->bind(':tanggal_akhir', $tanggal_akhir)
				->exec(' INSERT INTO htssctd
						(
							tanggal,
							id_hemxxmh,
							id_htsxxmh,
							jam_awal,
							jam_akhir,
							jam_awal_istirahat,
							jam_akhir_istirahat,
							menit_toleransi_awal_in,
							menit_toleransi_akhir_in,
							menit_toleransi_awal_out,
							menit_toleransi_akhir_out,

							tanggaljam_awal_t1,
							tanggaljam_awal,
							tanggaljam_awal_t2,
							tanggaljam_akhir_t1,
							tanggaljam_akhir,
							tanggaljam_akhir_t2,
							tanggaljam_awal_istirahat,
							tanggaljam_akhir_istirahat
						)
						WITH RECURSIVE date_range AS (
							SELECT DATE(:tanggal_awal) AS sabtu
							UNION ALL
							SELECT DATE_ADD(sabtu, INTERVAL 1 DAY)
							FROM date_range
							WHERE sabtu < :tanggal_akhir
						)
						SELECT
							c.sabtu,
							a.id_hemxxmh,
							a.id_htsxxmh,
							d.jam_awal,
							d.jam_akhir,
							d.jam_awal_istirahat,
							d.jam_akhir_istirahat,
							d.menit_toleransi_awal_in,
							d.menit_toleransi_awal_out,
							d.menit_toleransi_akhir_in,
							d.menit_toleransi_akhir_out,
							concat(c.sabtu, " ", TIME(DATE_SUB(d.jam_awal, INTERVAL d.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
							concat(c.sabtu, " ", d.jam_awal) AS tanggaljam_awal,
							CONCAT(
								CASE
									WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(c.sabtu, INTERVAL 1 DAY)
									ELSE c.sabtu
								END,
								" ",
								TIME(
									CASE
										WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
											TIMEDIFF(DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE), "24:00:00")
										ELSE
											DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE)
									END
								)
							) AS tanggaljam_awal_t2,

							CASE
								WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
								THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
								ELSE CONCAT(c.sabtu, " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
							END AS tanggaljam_akhir_t1,
							CASE
								WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
								THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_akhir)
								ELSE CONCAT(c.sabtu, " ", d.jam_akhir)
							END AS tanggaljam_akhir,
							CONCAT(
								CASE
									WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
										OR d.kode like "malam%" AND d.jam_akhir <= "12:00:00" THEN DATE_ADD(c.sabtu, INTERVAL 1 DAY)
									ELSE c.sabtu
								END,
								" ",
								TIME(
									CASE
										WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
											TIMEDIFF(DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE), "24:00:00")
										ELSE
											DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)
									END
								)
							) AS tanggaljam_akhir_t2,
							CASE
								WHEN d.kode like "malam%" AND d.jam_awal_istirahat <= "12:00:00"
								THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_awal_istirahat)
								ELSE CONCAT(c.sabtu, " ", d.jam_awal_istirahat)
							END AS tanggaljam_awal_istirahat,
							CASE
								WHEN d.kode like "malam%" AND d.jam_akhir_istirahat <= "12:00:00"
								THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_akhir_istirahat)
								ELSE CONCAT(c.sabtu, " ", d.jam_akhir_istirahat)
							END AS tanggaljam_akhir_istirahat
						FROM hgsemtd_v3 AS a
						LEFT JOIN hgsptth_v3 AS b ON b.id = a.id_hgsptth_v3
						LEFT JOIN date_range AS c ON c.sabtu BETWEEN b.tanggal_awal AND b.tanggal_akhir
						LEFT JOIN htsxxmh AS d ON d.id = a.id_htsxxmh
						WHERE a.nama = "sabtu" AND DAYOFWEEK(c.sabtu) = 7 AND a.id_hemxxmh = :id_hemxxmh;
						'
				);
		})
		->on('preEdit',function( $editor, $id, $values ) {
			$qs_jadwal = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec(' SELECT
							b.tanggal_awal,
							b.tanggal_akhir,
							a.id_hemxxmh,
							a.id_htsxxmh
						FROM hgsemtd_v3 AS a
						LEFT JOIN hgsptth_v3 AS b ON b.id = a.id_hgsptth_v3
						WHERE a.id = :id
						'
						);
			$rs_jadwal = $qs_jadwal->fetch();
			$tanggal_awal = $rs_jadwal['tanggal_awal'];
			$tanggal_akhir = $rs_jadwal['tanggal_akhir'];
			$id_hemxxmh = $rs_jadwal['id_hemxxmh'];
			$id_htsxxmh = $rs_jadwal['id_htsxxmh'];
			
			$id_hemxxmh_new = $values['hgsemtd_v3']['id_hemxxmh'];
			$id_htsxxmh_new = $values['hgsemtd_v3']['id_htsxxmh'];

			if ($id_hemxxmh_new != $id_hemxxmh || $id_htsxxmh_new != $id_htsxxmh) {
				
				$qd_schedule = $editor->db()
					->raw()
					->bind(':tanggal_awal', $tanggal_awal)
					->bind(':tanggal_akhir', $tanggal_akhir)
					->bind(':id_hemxxmh', $id_hemxxmh)
					->exec('DELETE FROM htssctd
							WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir 
								AND DAYOFWEEK(tanggal) = 7 
								AND id_hemxxmh = :id_hemxxmh 
								;
								'
					);

				
				$qi_schedule = $editor->db()
					->raw()
					->bind(':id_hemxxmh_new', $id_hemxxmh_new)
					->bind(':id_htsxxmh_new', $id_htsxxmh_new)
					->bind(':tanggal_awal', $tanggal_awal)
					->bind(':tanggal_akhir', $tanggal_akhir)
					->exec(' INSERT INTO htssctd
							(
								tanggal,
								id_hemxxmh,
								id_htsxxmh,
								jam_awal,
								jam_akhir,
								jam_awal_istirahat,
								jam_akhir_istirahat,
								menit_toleransi_awal_in,
								menit_toleransi_akhir_in,
								menit_toleransi_awal_out,
								menit_toleransi_akhir_out,

								tanggaljam_awal_t1,
								tanggaljam_awal,
								tanggaljam_awal_t2,
								tanggaljam_akhir_t1,
								tanggaljam_akhir,
								tanggaljam_akhir_t2,
								tanggaljam_awal_istirahat,
								tanggaljam_akhir_istirahat
							)
							WITH RECURSIVE date_range AS (
								SELECT DATE(:tanggal_awal) AS sabtu
								UNION ALL
								SELECT DATE_ADD(sabtu, INTERVAL 1 DAY)
								FROM date_range
								WHERE sabtu < :tanggal_akhir
							)
							SELECT
								c.sabtu,
								:id_hemxxmh_new,
								:id_htsxxmh_new,
								d.jam_awal,
								d.jam_akhir,
								d.jam_awal_istirahat,
								d.jam_akhir_istirahat,
								d.menit_toleransi_awal_in,
								d.menit_toleransi_awal_out,
								d.menit_toleransi_akhir_in,
								d.menit_toleransi_akhir_out,
								concat(c.sabtu, " ", TIME(DATE_SUB(d.jam_awal, INTERVAL d.menit_toleransi_awal_in MINUTE))) AS tanggaljam_awal_t1,
								concat(c.sabtu, " ", d.jam_awal) AS tanggaljam_awal,
								CONCAT(
									CASE
										WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN DATE_ADD(c.sabtu, INTERVAL 1 DAY)
										ELSE c.sabtu
									END,
									" ",
									TIME(
										CASE
											WHEN DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE) >= "24:00:00" THEN
												TIMEDIFF(DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE), "24:00:00")
											ELSE
												DATE_ADD(d.jam_awal, INTERVAL d.menit_toleransi_akhir_in MINUTE)
										END
									)
								) AS tanggaljam_awal_t2,

								CASE
									WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
									THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
									ELSE CONCAT(c.sabtu, " ", TIME(DATE_SUB(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)))
								END AS tanggaljam_akhir_t1,
								CASE
									WHEN d.kode like "malam%" AND d.jam_akhir <= "12:00:00"
									THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_akhir)
									ELSE CONCAT(c.sabtu, " ", d.jam_akhir)
								END AS tanggaljam_akhir,
								CONCAT(
									CASE
										WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" 
											OR d.kode like "malam%" AND d.jam_akhir <= "12:00:00" THEN DATE_ADD(c.sabtu, INTERVAL 1 DAY)
										ELSE c.sabtu
									END,
									" ",
									TIME(
										CASE
											WHEN DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE) >= "24:00:00" THEN
												TIMEDIFF(DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE), "24:00:00")
											ELSE
												DATE_ADD(d.jam_akhir, INTERVAL d.menit_toleransi_akhir_out MINUTE)
										END
									)
								) AS tanggaljam_akhir_t2,
								CASE
									WHEN d.kode like "malam%" AND d.jam_awal_istirahat <= "12:00:00"
									THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_awal_istirahat)
									ELSE CONCAT(c.sabtu, " ", d.jam_awal_istirahat)
								END AS tanggaljam_awal_istirahat,
								CASE
									WHEN d.kode like "malam%" AND d.jam_akhir_istirahat <= "12:00:00"
									THEN CONCAT(DATE_ADD(c.sabtu, INTERVAL 1 DAY), " ", d.jam_akhir_istirahat)
									ELSE CONCAT(c.sabtu, " ", d.jam_akhir_istirahat)
								END AS tanggaljam_akhir_istirahat
							FROM htsxxmh AS d 
							LEFT JOIN date_range AS c ON c.sabtu BETWEEN :tanggal_awal AND :tanggal_akhir
							WHERE DAYOFWEEK(c.sabtu) = 7 and d.id = :id_htsxxmh_new;
							'
					);
			}
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			
		})
		->on('preRemove',function( $editor, $id, $values ) {
			$qs_jadwal = $editor->db()
				->raw()
				->bind(':id', $id)
				->exec(' SELECT
							b.tanggal_awal,
							b.tanggal_akhir,
							a.id_hemxxmh,
							a.id_htsxxmh
						FROM hgsemtd_v3 AS a
						LEFT JOIN hgsptth_v3 AS b ON b.id = a.id_hgsptth_v3
						WHERE a.id = :id
						'
						);
			$rs_jadwal = $qs_jadwal->fetch();
			$tanggal_awal = $rs_jadwal['tanggal_awal'];
			$tanggal_akhir = $rs_jadwal['tanggal_akhir'];
			$id_hemxxmh = $rs_jadwal['id_hemxxmh'];
			$id_htsxxmh = $rs_jadwal['id_htsxxmh'];

			$qd_schedule = $editor->db()
				->raw()
				->bind(':tanggal_awal', $tanggal_awal)
				->bind(':tanggal_akhir', $tanggal_akhir)
				->bind(':id_hemxxmh', $id_hemxxmh)
				->exec('DELETE FROM htssctd
						WHERE tanggal BETWEEN :tanggal_awal AND :tanggal_akhir 
							AND DAYOFWEEK(tanggal) = 7
							AND id_hemxxmh = :id_hemxxmh 
							;
							'
				);
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>