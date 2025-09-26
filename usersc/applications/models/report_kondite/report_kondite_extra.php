<?php
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$selisih = $values['abnormal_lembur']['selisih'];
			$tanggal_ymd = $values['abnormal_lembur']['tanggal'];
			$tanggal = date('Y-m-d', strtotime($tanggal_ymd));

			$id_hemxxmh = $values['abnormal_lembur']['id_hemxxmh'];

			$qu_htsprrd = $editor->db()
				->query('update', 'htsprrd')
				->set('abnormal',$selisih)
				->where('id_hemxxmh',$id_hemxxmh)
				->where('tanggal',$tanggal)
				->exec();

		})
		->on('preEdit',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			$selisih = $values['abnormal_lembur']['selisih'];
			$tanggal_ymd = $values['abnormal_lembur']['tanggal'];
			$tanggal = date('Y-m-d', strtotime($tanggal_ymd));
			
			$id_hemxxmh = $values['abnormal_lembur']['id_hemxxmh'];

			$qu_htsprrd = $editor->db()
				->query('update', 'htsprrd')
				->set('abnormal',$selisih)
				->where('id_hemxxmh',$id_hemxxmh)
				->where('tanggal',$tanggal)
				->exec();
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>