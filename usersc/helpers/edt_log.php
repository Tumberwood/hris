<?php
	/** 
	 * 230210 - 2.0.1
	 * Mengubah finish_on diisi dari editor form, karena jika beda timezone, perhitungan durasi akan salah
	 * 
	 * 230101 - 2.0.0
	 * Mencatatkan durasi finish_on, untuk mengetahui durasi_detik
	
	*/
	

	$editor
		// input activity_log_ml on insert
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$start_on     = date('Y-m-d H:i:s', strtotime($values['start_on']));
			$finish_on    = date('Y-m-d H:i:s', strtotime($values['finish_on']));
			$durasi_detik = strtotime($finish_on) - strtotime($start_on);
			
			$editor->db()
				->query('insert', 'activity_log_ml')
				->set('id_transaksi',$id)
				->set('kode','CREATE')
				->set('nama',$values['nama_tabel'])
				->set('keterangan',json_encode( $values ))
				->set('created_by',$_SESSION['user'])
				->set('username',$_SESSION['username'])
				->set('start_on',$start_on)
				->set('finish_on', $finish_on)
				->set('durasi_detik',$durasi_detik)
				->exec();
		})
		// input activity_log_ml on edit
		->on('postEdit',function( $editor, $id, $values, $row ) {
			$start_on     = date('Y-m-d H:i:s', strtotime($values['start_on']));
			$finish_on    = date('Y-m-d H:i:s', strtotime($values['finish_on']));
			$durasi_detik = strtotime($finish_on) - strtotime($start_on);
			
			$editor->db()
				->query('insert', 'activity_log_ml')
				->set('id_transaksi',$id)
				->set('kode','EDIT')
				->set('nama',$values['nama_tabel'])
				->set('keterangan',json_encode( $values ))
				->set('created_by',$_SESSION['user'])
				->set('username',$_SESSION['username'])
				->set('start_on',$start_on)
				->set('finish_on', $finish_on)
				->set('durasi_detik',$durasi_detik)
				->exec();
		})
?>