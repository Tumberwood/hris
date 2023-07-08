<?php
	/*
		reff:
		https://editor.datatables.net/docs/1.9.6/php/class-DataTables.Database.Query.html
		https://editor.datatables.net/docs/1.9.6/php/class-DataTables.Database.Result.html
		
		template untuk memanggil raw query bantuan
		biasanya digunakan untuk event postEdit atau postCreate dll
		
		ada 2 cara
		1. Dikerjakan di DALAM Datatables Editor
			query harus dipanggil dengan menggunakan $editor->db()->sql($query);
		2. Dikerjakan di LUAR Datatables Editor
			query harus dipanggil dengan menggunakan $db->sql($query);
	*/
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	use
		DataTables\Editor,
		DataTables\Editor\Query,
		DataTables\Editor\Result;
	
	// try catch
	try{
		$db->transaction();
		// do something here gaes
		$db->commit();
		echo json_encode(array('message'=> 'Pesan Sukses' , 'type_message'=>'success' ));
	}catch(PDOException $e){
		// rollback on error
		$db->rollback();
		echo json_encode(array('message'=>'Pesan Gagal', 'type_message'=>'danger' ));
	}
	
	// BEGIN  of CLASS datatables
	$editor 	= Editor::inst( $db, '' );	// harus dipakai

	// SELECT
	$sql_select = $editor->db()
		->query('select', 'namatable')
		->get('namafield1')
		->get('namafield2 as alias_field') // as harus huruf kecil
		->join('namatable2','namatable2.fieldjoin = namatable.fieldjoin','LEFT')
		->where('namafield',$variable)
        ->group_by('namatable.namafieldgroupby')
		->exec();	// eksekusi query
	
	// result
	// ambil 1 row pertama hasil query
	$result_select = $sql_select->fetch();

	// ambil all row hasil query
	$results_select = $sql_select->fetchAll();

	// count hasil select
	$count = $sql_select->count();
	
	// ambil id hasil insert
	$insertId = $sql_select->insertId();

	// INSERT
	$sql_insert = $editor->db()
		->query('insert', 'namatable')
		->set('namafield1',$valuefield1)
		->set('namafield2',$valuefield2)
		->exec();

	// UPDATE
	$sql_update = $editor->db()
		->query('update', 'namatable')
		->set('namafield1',$valuefield1)
		->where('namafield1',$variable)
		->exec();

	

	// END of CLASS datatables
	
	
	
	// Raw
	/* SELECT */
	/* Buat Query */
	$sql_select = "
		SELECT
			namafield
		FROM 
			namatable
		WHERE 
			namafield = $where
	";
	/* count result */
	$c_select = $editor->db()->sql($sql_select)->count();
	
	/* Tampilkan All Result */
	$result_select = $editor->db()->sql($sql_select)->fetchAll();
	
	/* Tampilkan 1 Result */
	$result_select = $editor->db()->sql($sql_select)->fetch();
	
	/* Ambil last inserted id */
	$result_select = $editor->db()->sql($sql_select)->insertId();
	


	
	
	/* 
		Memindahkan ke variable 
		Jika hasil 1 baris saja, maka cukup menggunakan [0] , artinya index result yang pertama
		Jika hasil lebih dari 1 baris, perlu dilakukan looping untuk menampilkan semua hasil
	*/
	
	/* hasil 1 baris */
	$variable = $result_select[0]['namafield'];
	
	/* hasil multi baris di looping */
	foreach ($result_select as $row) {
		$variable = $row['namafield'];
	}
	
	/* INSERT */
	$sql_insert = "
		INSERT INTO namatable (namafield1, namafield2)
		VALUES (valuefield1, valuefield2) 
	";
	/* Eksekusi Query */
	$result_insert = $editor->db()->sql($sql_insert);
	
	
	
	/* UPDATE */
	$sql_update = "
		UPDATE namatable
		SET 
			namafield = $variable
		WHERE namafield = $where
	";
	/* Eksekusi Query */
	$result_update = $editor->db()->sql($sql_update);
	
	
	/* DELETE */
	$sql_delete = "
		DELETE FROM namatable WHERE condition
	";
	/* Eksekusi Query */
	$result_delete = $editor->db()->sql($sql_delete);
	
	/* INSERT FROM SELECT*/
	$sql_insert = "
		INSERT INTO namatabletujuan (namafield1, namafield2, namafield3)
		SELECT namafield1, namafield2, namafield3 FROM namatableasal
		WHERE namafield = $where
	";
	/* Eksekusi Query */
	$result_insert = $editor->db()->sql($sql_insert);
	
	/* UPDATE FROM SELECT*/
	$sql_update = "
		UPDATE namatabletujuan a
		LEFT JOIN namatableasal b ON b.id = a.idrelasi
		SET namafield1 = namafield1
	";
	/* Eksekusi Query */
	$result_update = $editor->db()->sql($sql_update);
	
	// if you need another looping
	for ($x = 0; $x <= 100; $x+=10) {
		// do something here
	}
	
	$array_variable = array();
	$array_variable[] = array(
		"namafield1" => $result[$x]['namafield1'],
		"namafield2" => $result[$x]['namafield2']
	);
	$data[] = array_push($data,$array_variable);

	// date mysql
	date_format(tanggal,'%d %b %Y');
	
	// date php
	$hariini = date('Y-m-d');
	$tanggal = date('Y-m-d', strtotime($values['table_name']['field_tanggal']));
	$tanggal = date('Y-m-d', strtotime($tanggal. ' + 1 days'));

	// date carbon php
	// now
	$tanggal = new Carbon();

	// formatting
	$tanggal->format('Y-m-d H:i:s');
?>