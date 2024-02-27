<?php
	use Carbon\Carbon;
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			// script diletakkan disini
			$id_hesxxmh = $values['hemjbmh']['id_hesxxmh'];

			$tanggal_join = new Carbon($values['hemjbmh']['tanggal_masuk']);
			$tanggal_masuk = $tanggal_join->format('Y-m-d');

			// Hanya Kontrak, Staff dan Tetap yang dipotong bpjs 3 bulan 
			// Join Juni dipotong October (untuk estimasi program == 4 bulan)

			if ($id_hesxxmh == 1 || $id_hesxxmh == 2 || $id_hesxxmh == 5) {
				$qi_bpjs_tk_first = $editor->db()
					->raw()
					->bind(':tanggal_masuk', $tanggal_masuk)
					->exec('INSERT INTO bpjs_tk_exclude
							(
								id_hemxxmh,
								tanggal
							)
							SELECT
							' . $id . ',
							DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 4 MONTH), "%Y-%m-01")
							;
							'
							);

				$qi_bpjs_kes_first = $editor->db()
					->raw()
					->bind(':tanggal_masuk', $tanggal_masuk)
					->exec('INSERT INTO bpjs_kes_exclude
							(
								id_hemxxmh,
								tanggal
							)
							SELECT
							' . $id . ',
							DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 4 MONTH), "%Y-%m-01")
							;
							'
							);
			}
			

			// $qi_bpjs_tk_first = $editor->db()
			// 	->raw()
			// 	->bind(':tanggal_masuk', $tanggal_masuk)
			// 	->exec('INSERT INTO bpjs_tk_exclude
			// 			(
			// 				id_hemxxmh,
			// 				tanggal
			// 			)
			// 			SELECT
			// 			' . $id . ',
			// 			DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 0 MONTH), "%Y-%m-01")
			// 			;
			// 			'
			// 			);

			// $qi_bpjs_kes_first = $editor->db()
			// 	->raw()
			// 	->bind(':tanggal_masuk', $tanggal_masuk)
			// 	->exec('INSERT INTO bpjs_kes_exclude
			// 			(
			// 				id_hemxxmh,
			// 				tanggal
			// 			)
			// 			SELECT
			// 			' . $id . ',
			// 			DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 0 MONTH), "%Y-%m-01")
			// 			;
			// 			'
			// 			);

			// // insert ke BPJS Kesehatan
			// $qi_bpjs_kes = $editor->db()
			// ->raw()
			// ->bind(':tanggal_masuk', $tanggal_masuk)
			// ->exec('INSERT INTO bpjs_kes_exclude
			// 		(
			// 			id_hemxxmh,
			// 			tanggal
			// 		)
			// 		SELECT
			// 		' . $id . ',
			// 			CASE
			// 				WHEN DAY(:tanggal_masuk) > 20 THEN DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 2 MONTH), "%Y-%m-01")
			// 				ELSE DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 1 MONTH), "%Y-%m-01")
			// 			END AS Result;
			// 		'
			// 		);
					
			// // insert ke BPJS TK
			// $qi_bpjs_tk = $editor->db()
			// ->raw()
			// ->bind(':tanggal_masuk', $tanggal_masuk)
			// ->exec('INSERT INTO bpjs_tk_exclude
			// 		(
			// 			id_hemxxmh,
			// 			tanggal
			// 		)
			// 		SELECT
			// 		' . $id . ',
			// 			CASE
			// 				WHEN DAY(:tanggal_masuk) > 20 THEN DATE_FORMAT(DATE_ADD(:tanggal_masuk, INTERVAL 1 MONTH), "%Y-%m-01")
			// 				ELSE NULL
			// 			END AS Result;
			// 		'
			// 		);

			$tanggal_akhir = $values['hemjbmh']['tanggal_keluar'];
			$tanggal_akhir_kontrak = date("Y-m-d", strtotime($tanggal_akhir));
			// print_r($tanggal_akhir_kontrak);
			$qs_hemjbmh = $editor->db()
				->query('select', 'hemjbmh' )
				->get(['id_hesxxmh'] )
				->get(['id_hodxxmh'] )
				->get(['id_hovxxmh'] )
				->get(['id_hosxxmh'] )
				->get(['id_hevxxmh'] )
				->get(['id_hetxxmh'] )
				->get(['tanggal_masuk'] )
				->where('id_hemxxmh', $id )
				->exec();
			$rs_hemjbmh = $qs_hemjbmh->fetch();
			// $tanggal_masuk = Carbon::parse($rs_hemjbmh['tanggal_masuk']);

			// Add 6 months to the date
			// $tanggal_akhir_kontrak = $tanggal_masuk->addMonths(6);

			$qi_hemjbrd = $editor->db()
				->query('insert', 'hemjbrd')
				->set('id_harxxmh',1)
				->set('is_email_status',1)
				->set('id_hesxxmh',$rs_hemjbmh['id_hesxxmh'])
				->set('id_hovxxmh_awal',$rs_hemjbmh['id_hovxxmh'])
				->set('id_hovxxmh_akhir',$rs_hemjbmh['id_hovxxmh'])
				->set('id_hodxxmh_awal',$rs_hemjbmh['id_hodxxmh'])
				->set('id_hodxxmh_akhir',$rs_hemjbmh['id_hodxxmh'])
				->set('id_hosxxmh_awal',$rs_hemjbmh['id_hosxxmh'])
				->set('id_hosxxmh_akhir',$rs_hemjbmh['id_hosxxmh'])
				->set('id_hevxxmh_awal',$rs_hemjbmh['id_hevxxmh'])
				->set('id_hevxxmh_akhir',$rs_hemjbmh['id_hevxxmh'])
				->set('id_hetxxmh_awal',$rs_hemjbmh['id_hetxxmh'])
				->set('id_hetxxmh_akhir',$rs_hemjbmh['id_hetxxmh'])
				->set('tanggal_awal',$rs_hemjbmh['tanggal_masuk'])
				->set('tanggal_akhir',$tanggal_akhir_kontrak)
				->set('id_hemxxmh',$id)
				->exec();
		})
		->on('preEdit',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			// script diletakkan disini
			// $tanggal_akhir = $values['hemjbmh']['tanggal_keluar']
			// $tanggal_akhir_kontrak = date("Y-m-d", strtotime($tanggal_akhir));

			// print_r($tanggal_akhir_kontrak);
			// $qs_hemjbmh = $editor->db()
			// 	->query('select', 'hemjbmh' )
			// 	->get(['id_hesxxmh'] )
			// 	->get(['id_hodxxmh'] )
			// 	->get(['id_hovxxmh'] )
			// 	->get(['id_hosxxmh'] )
			// 	->get(['id_hevxxmh'] )
			// 	->get(['id_hetxxmh'] )
			// 	->get(['tanggal_masuk'] )
			// 	->where('id_hemxxmh', $id )
			// 	->exec();
			// $rs_hemjbmh = $qs_hemjbmh->fetch();
			// $tanggal_masuk = Carbon::parse($rs_hemjbmh['tanggal_masuk']);

			// Add 6 months to the date
			// $tanggal_akhir_kontrak = $tanggal_masuk->addMonths(6);

			// $qu_hemjbrd = $editor->db()
			// 	->query('update', 'hemjbrd')
			// 	->set('id_hesxxmh',$rs_hemjbmh['id_hesxxmh'])
			// 	->set('id_hovxxmh_awal',$rs_hemjbmh['id_hovxxmh'])
			// 	->set('id_hovxxmh_akhir',$rs_hemjbmh['id_hovxxmh'])
			// 	->set('id_hodxxmh_awal',$rs_hemjbmh['id_hodxxmh'])
			// 	->set('id_hodxxmh_akhir',$rs_hemjbmh['id_hodxxmh'])
			// 	->set('id_hosxxmh_awal',$rs_hemjbmh['id_hosxxmh'])
			// 	->set('id_hosxxmh_akhir',$rs_hemjbmh['id_hosxxmh'])
			// 	->set('id_hevxxmh_awal',$rs_hemjbmh['id_hevxxmh'])
			// 	->set('id_hevxxmh_akhir',$rs_hemjbmh['id_hevxxmh'])
			// 	->set('id_hetxxmh_awal',$rs_hemjbmh['id_hetxxmh'])
			// 	->set('id_hetxxmh_akhir',$rs_hemjbmh['id_hetxxmh'])
			// 	->set('tanggal_awal',$rs_hemjbmh['tanggal_masuk'])
			// 	->set('tanggal_akhir',$tanggal_akhir_kontrak)
			// 	->where('id_hemxxmh',$id)
			// 	->exec();
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>