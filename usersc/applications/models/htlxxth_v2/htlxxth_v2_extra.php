<?php
    use Carbon\Carbon;
    $editor
		->on('preCreate',function( $editor, $values ) {
			// script diletakkan disini
		})
		->on('postCreate',function( $editor, $id, $values, $row ) {
			$id_htlxxth = $id;

			$qs_htlxxth = $editor->db()
				->query('select', 'htlxxth' )
				->get([
					'htlxxth.id as id_transaksi',
					'htlxxth.id_hemxxmh as id_hemxxmh',
					'htlxxth.id_htlxxmh as id_htlxxmh',
					'ifnull(htlgrmh.id, 0) as id_htlgrmh',
					'htlxxth.kode as kode',
					'htlxxth.tanggal_awal as tanggal_awal',
					'htlxxth.tanggal_akhir as tanggal_akhir',
					'htlxxth.keterangan as keterangan',
					'htlxxmh.kode as htlxxmh_kode',
					'htlxxmh.is_cuti_khusus as is_cuti_khusus',
					'ifnull(htlgrmh.kode, "") as htlgrmh_kode'
				] )
				->join('hemxxmh','hemxxmh.id = htlxxth.id_hemxxmh','LEFT' )
				->join('htlxxmh','htlxxmh.id = htlxxth.id_htlxxmh','LEFT' )
				->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
				->where('htlxxth.id', $id_htlxxth )
				->exec();

			$rs_htlxxth = $qs_htlxxth->fetch();
			
			$tanggal_awal  = Carbon::parse($rs_htlxxth['tanggal_awal']);
			$tanggal_akhir = Carbon::parse($rs_htlxxth['tanggal_akhir']);

			$jumlah_hari = $tanggal_awal->diffInDays($tanggal_akhir) + 1;
			
			$tanggal = $tanggal_awal;
			$tt = '';

			$qs_htlxxmh = $editor->db()
				->query('select', 'htlxxmh' )
				->get(['is_potongcuti'] )
				->where('id', $rs_htlxxth['id_htlxxmh'] )
				->exec();
			$rs_htlxxmh = $qs_htlxxmh->fetch();

			if ($rs_htlxxmh['is_potongcuti'] == 1) {
				$pot_cuti = -1;
			} else {
				$pot_cuti = 0;
			}

			for ($x = 0; $x < $jumlah_hari; $x++) {
				$tanggal_ymd = $tanggal;

				//Cek apakah ada sakit atau absen khusus pada tanggal dan pegawai terpilih, maka cek apakah ada Cuti
				if (
					in_array($rs_htlxxth['id_htlxxmh'], [3]) || 
					$rs_htlxxth['is_cuti_khusus'] == 1
				) {
					$qs_cuti = $editor->db()
						->raw()
						->bind(':tanggal_ymd', $tanggal_ymd->format('Y-m-d'))
						->bind(':id_hemxxmh', $rs_htlxxth['id_hemxxmh'])
						->exec('SELECT
									a.id
								FROM htlxxrh a
								WHERE a.tanggal = :tanggal_ymd
								AND a.id_hemxxmh = :id_hemxxmh
								AND a.htlxxmh_kode = "CB"
					');
					$rs_cuti = $qs_cuti->fetch();
	
					//Jika ada cuti, maka hapus cuti
					if (!empty($rs_cuti) && $rs_cuti['id'] > 0) {
						$qd_cuti = $editor->db()
							->query('delete', 'htlxxrh')
							->where('id', $rs_cuti['id'] )
							->exec();
					}
				}

				$qs_htlxxrh = $editor->db()
					->raw()
					->bind(':tanggal', $tanggal_ymd->format('Y-m-d'))
					->bind(':id_hemxxmh', $rs_htlxxth['id_hemxxmh'])
					->bind(':kode', $rs_htlxxth['kode'])
					->exec('SELECT
								id
							FROM htlxxrh
							WHERE tanggal = :tanggal
								AND id_hemxxmh = :id_hemxxmh
								AND kode = :kode
				');

				$rs_htlxxrh = $qs_htlxxrh->fetch();

				if (!isset($rs_htlxxrh['id'])) {

					$qi = $editor->db()
						->query('insert', 'htlxxrh')
						->set('id_transaksi', $rs_htlxxth['id_transaksi'] )
						->set('id_htlgrmh', $rs_htlxxth['id_htlgrmh'] )
						->set('id_htlxxmh', $rs_htlxxth['id_htlxxmh'] )
						->set('id_hemxxmh', $rs_htlxxth['id_hemxxmh'] )
						->set('kode', $rs_htlxxth['kode'] )
						->set('is_approve', 1 )
						->set('tanggal', $tanggal_ymd->format('Y-m-d') )
						->set('keterangan', $rs_htlxxth['keterangan'] )
						->set('jenis', 1 )
						->set('htlxxmh_kode', $rs_htlxxth['htlxxmh_kode'] )
						->set('htlgrmh_kode', $rs_htlxxth['htlgrmh_kode'] )
						->set('jumlah', 1 )
						->set('jam_awal', null )
						->set('jam_akhir', null )
						->set('saldo', $pot_cuti )
						->exec();

				}
				$tt = $tt . ' - ' . $tanggal_ymd->format('Y-m-d');
				$tanggal->add(1, 'days');
			}
		})
		->on('preEdit',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			$id_htlxxth = $id;
			
			$qd_htlxxrh = $editor->db()
				->query('delete', 'htlxxrh')
				->where('id_transaksi', $id_htlxxth )
				->where('jenis', 1 )
				->exec();

			$qs_htlxxth = $editor->db()
				->query('select', 'htlxxth' )
				->get([
					'htlxxth.id as id_transaksi',
					'htlxxth.id_hemxxmh as id_hemxxmh',
					'htlxxth.id_htlxxmh as id_htlxxmh',
					'ifnull(htlgrmh.id, 0) as id_htlgrmh',
					'htlxxth.kode as kode',
					'htlxxth.tanggal_awal as tanggal_awal',
					'htlxxth.tanggal_akhir as tanggal_akhir',
					'htlxxth.keterangan as keterangan',
					'htlxxmh.kode as htlxxmh_kode',
					'htlxxmh.is_cuti_khusus as is_cuti_khusus',
					'ifnull(htlgrmh.kode, "") as htlgrmh_kode'
				] )
				->join('hemxxmh','hemxxmh.id = htlxxth.id_hemxxmh','LEFT' )
				->join('htlxxmh','htlxxmh.id = htlxxth.id_htlxxmh','LEFT' )
				->join('htlgrmh','htlgrmh.id = htlxxmh.id_htlgrmh','LEFT' )
				->where('htlxxth.id', $id_htlxxth )
				->exec();

			$rs_htlxxth = $qs_htlxxth->fetch();
			
			$tanggal_awal  = Carbon::parse($rs_htlxxth['tanggal_awal']);
			$tanggal_akhir = Carbon::parse($rs_htlxxth['tanggal_akhir']);

			$jumlah_hari = $tanggal_awal->diffInDays($tanggal_akhir) + 1;
			
			$tanggal = $tanggal_awal;
			$tt = '';

			$qs_htlxxmh = $editor->db()
				->query('select', 'htlxxmh' )
				->get(['is_potongcuti'] )
				->where('id', $rs_htlxxth['id_htlxxmh'] )
				->exec();
			$rs_htlxxmh = $qs_htlxxmh->fetch();

			if ($rs_htlxxmh['is_potongcuti'] == 1) {
				$pot_cuti = -1;
			} else {
				$pot_cuti = 0;
			}

			for ($x = 0; $x < $jumlah_hari; $x++) {
				$tanggal_ymd = $tanggal;

				//Cek apakah ada sakit atau absen khusus pada tanggal dan pegawai terpilih, maka cek apakah ada Cuti
				if (
					in_array($rs_htlxxth['id_htlxxmh'], [3]) || 
					$rs_htlxxth['is_cuti_khusus'] == 1
				) {
					$qs_cuti = $editor->db()
						->raw()
						->bind(':tanggal_ymd', $tanggal_ymd->format('Y-m-d'))
						->bind(':id_hemxxmh', $rs_htlxxth['id_hemxxmh'])
						->exec('SELECT
									a.id
								FROM htlxxrh a
								WHERE a.tanggal = :tanggal_ymd
								AND a.id_hemxxmh = :id_hemxxmh
								AND a.htlxxmh_kode = "CB"
					');
					$rs_cuti = $qs_cuti->fetch();
	
					//Jika ada cuti, maka hapus cuti
					if (!empty($rs_cuti) && $rs_cuti['id'] > 0) {
						$qd_cuti = $editor->db()
							->query('delete', 'htlxxrh')
							->where('id', $rs_cuti['id'] )
							->exec();
					}
				}

				$qs_htlxxrh = $editor->db()
					->raw()
					->bind(':tanggal', $tanggal_ymd->format('Y-m-d'))
					->bind(':id_hemxxmh', $rs_htlxxth['id_hemxxmh'])
					->bind(':kode', $rs_htlxxth['kode'])
					->exec('SELECT
								id
							FROM htlxxrh
							WHERE tanggal = :tanggal
								AND id_hemxxmh = :id_hemxxmh
								AND kode = :kode
				');

				$rs_htlxxrh = $qs_htlxxrh->fetch();

				if (!isset($rs_htlxxrh['id'])) {

					$qi = $editor->db()
						->query('insert', 'htlxxrh')
						->set('id_transaksi', $rs_htlxxth['id_transaksi'] )
						->set('id_htlgrmh', $rs_htlxxth['id_htlgrmh'] )
						->set('id_htlxxmh', $rs_htlxxth['id_htlxxmh'] )
						->set('id_hemxxmh', $rs_htlxxth['id_hemxxmh'] )
						->set('kode', $rs_htlxxth['kode'] )
						->set('is_approve', 1 )
						->set('tanggal', $tanggal_ymd->format('Y-m-d') )
						->set('keterangan', $rs_htlxxth['keterangan'] )
						->set('jenis', 1 )
						->set('htlxxmh_kode', $rs_htlxxth['htlxxmh_kode'] )
						->set('htlgrmh_kode', $rs_htlxxth['htlgrmh_kode'] )
						->set('jumlah', 1 )
						->set('jam_awal', null )
						->set('jam_akhir', null )
						->set('saldo', $pot_cuti )
						->exec();

				}
				$tt = $tt . ' - ' . $tanggal_ymd->format('Y-m-d');
				$tanggal->add(1, 'days');
			}
		})
		->on('preRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		})
		->on('postRemove',function( $editor, $id, $values ) {
			// script diletakkan disini
		});
?>