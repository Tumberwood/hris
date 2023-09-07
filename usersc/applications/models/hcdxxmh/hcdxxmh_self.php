<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	
	use
		DataTables\Editor,
		DataTables\Editor\Field,
		DataTables\Editor\Format,
		DataTables\Editor\Mjoin,
		DataTables\Editor\Options,
		DataTables\Editor\Upload,
		DataTables\Editor\Validate,
		DataTables\Editor\ValidateOptions,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
		$id_users = $_SESSION['user'];
        $sql_select = $db
        ->raw()
		->bind(':id_users', $id_users)
		->exec('SELECT 
					a.id AS id_hcd,
					a.nama AS nama,
					a.id_gcrxxmh AS id_gcrxxmh,
					a.agama AS agama,
					a.gender AS gender,
					a.tanggal_lahir AS tanggal_lahir,
					a.perkawinan AS perkawinan,
					a.suku AS suku,
					b.gol_darah AS gol_darah,
					a.berat AS berat,
					a.tinggi AS tinggi,
					a.no_sepatu AS no_sepatu,
					a.ukuran_seragam AS ukuran_seragam,
					d.email_personal AS email_personal,
					d.facebook AS facebook,
					d.twitter AS twitter,
					d.linkedin AS linkedin,
					d.instagram AS instagram,
					d.tiktok AS tiktok,
					c.ktp_no AS ktp_no,
					c.ktp_alamat AS ktp_alamat,
					c.id_gctxxmh_ktp AS tempat_ktp,
					c.is_npwp AS is_npwp,
					c.sim_a AS sim_a,
					c.sim_b AS sim_b,
					c.sim_c AS sim_c,
					c.npwp_no AS npwp_no,
					c.npwp_alamat AS npwp_alamat,
					c.id_gctxxmh_npwp AS id_gctxxmh_npwp,
					a.id_gctxxmh_lahir AS tempat_lahir,
					d.handphone AS handphone,
					d.whatsapp AS whatsapp,
					d.alamat_tinggal AS alamat_tinggal,
					c.rt AS rt,
					c.rw AS rw,
					c.kelurahan AS kelurahan,
					c.kecamatan AS kecamatan,
					c.id_gtxpkmh AS id_gtxpkmh,
					d.id_gctxxmh_tinggal AS kota_tinggal,
					a.hobby AS hobby,
					foto.web_path AS foto,
					ktp.web_path AS ktp,
					sim.web_path AS sim,
					cv.web_path AS cv,
					e.bpjskes_no AS bpjskes_no,
					e.bpjstk_no AS bpjstk_no,
					e.tempat_tinggal AS tempat_tinggal,
					e.kendaraan AS kendaraan,
					e.kendaraan_milik AS kendaraan_milik,
					e.intrv_self_1 AS intrv_self_1,
					e.intrv_self_2 AS intrv_self_2,
					e.intrv_self_3 AS intrv_self_3,
					e.intrv_self_4 AS intrv_self_4,
					e.intrv_self_5 AS intrv_self_5,
					e.intrv_self_6 AS intrv_self_6,
					e.intrv_self_7 AS intrv_self_7,
					e.intrv_self_8 AS intrv_self_8,
					e.intrv_self_9 AS intrv_self_9,
					e.intrv_self_10 AS intrv_self_10,
					e.intrv_self_11 AS intrv_self_11,
					e.intrv_self_12 AS intrv_self_12,
					e.intrv_self_13 AS intrv_self_13
					
					
				FROM hcdxxmh AS a
				LEFT JOIN hcdmdmh AS b ON b.id_hcdxxmh = a.id 
				LEFT JOIN hcddcmh AS c ON c.id_hcdxxmh = a.id
				LEFT JOIN hcdcsmh AS d ON d.id_hcdxxmh = a.id
				LEFT JOIN hcdjbmh AS e ON e.id_hcdxxmh = a.id
				LEFT JOIN files AS foto ON foto.id = a.id_files_foto
				LEFT JOIN files AS cv ON cv.id = a.id_files_cv
				LEFT JOIN files AS sim ON sim.id = c.id_files_sim
				LEFT JOIN files AS ktp ON ktp.id = c.id_files_ktp
				WHERE a.id_users = :id_users
				'
			);
	    $results_select = $sql_select->fetch();
        echo json_encode($results_select);
?>