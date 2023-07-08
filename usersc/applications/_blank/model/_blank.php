<?php
	// userspice
	include( "../../../../users/init.php" );

	// datatables
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

	// inspinia raw query debug
	include( "../../../../usersc/helpers/fn_debug_datatables.php" );

	// composer
	require '../../../../usersc/vendor/autoload.php';
	
	// carbon
	use Carbon\Carbon;
	
	// ----------- do not erase
	$show_inactive_status = $_POST['show_inactive_status'];
	// -----------
	
	$editor = Editor::inst( $db, '_blank' )
		->debug(true)
		->fields(
			Field::inst( '_blank.kode' ),
			Field::inst( '_blank.nama' ),
			Field::inst( '_blank.keterangan' ),

			// id_company_m dan id_cabang_m digunakan di transaksi
			Field::inst( '_blank.id_company_m' )
                ->set( Field::SET_CREATE )
				->setValue($_SESSION['id_company_m']),
            Field::inst( '_blank.id_cabang_m' )
                ->set( Field::SET_CREATE )
				->setValue($_SESSION['id_cabang_m']),

			Field::inst( '_blank.tanggal' )
				// formatter tanggal, sepasang get dan set, untuk mengubah datetime js ke datetime mysql dan sebaliknya
				// jika readonly, set nya tidak perlu digunakan
				
				// BEGIN model tanggal saja
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === "0000-00-00" || $val === null){
						echo "";
					}else{
						return date( 'd M Y', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y',
					'to' =>   'Y-m-d'
				) )
				// END model tanggal saja
				
				// mengubah blank menjadi 0000-00-00
				->setFormatter( function ( $val ) {
					if ($val == '') {
						return '0000-00-00';
					} else {
						return date( 'Y-m-d', strtotime( $val ) );
					}
				} )
				
				// BEGIN model tanggal jam
				->getFormatter( 'Format::datetime', array(
					'from' => 'Y-m-d H:i:s',
					'to' =>   'd M Y / H:i'
				) )
				->setFormatter( 'Format::datetime', array(
					'from' => 'd M Y / H:i',
					'to' =>   'Y-m-d H:i:s'
				) )
				// END model tanggal jam

				// BEGIN model jam saja
				->getFormatter( function ( $val, $data, $opts ) {
					if ($val === "00:00:00" || $val === null){
						echo "";
					}else{
						return date( 'H:i', strtotime( $val ) );
					}
				} )
				->setFormatter( 'Format::datetime', array(
					'from' => 'H:i',
					'to' =>   'H:i:s'
				) )
				// END model jam saja
				
				// string conversion
				// gunakan php function 
				// - strtolower()	: ke huruf kecil semua
				// - strtoupper() 	: ke huruf besar semua
				// - lcfirst() 		: huruf pertama ke kecil
				// - ucfirst()		: huruf pertama ke besar
				// - ucwords()		: huruf pertama setiap kata ke besar
				->setFormatter( function ( $val ) {
					return strtoupper($val);
				} ),
			
			// BEGIN field image
			Field::inst( '_blank.image' )
				->setFormatter( Format::ifEmpty( 0 ) )
				->upload( Upload::inst(  $abs_us_root.$us_url_root.'usersc/img/upload/__ID__.__EXTN__' )
					->db( 'files', 'id', array(
						'filename'    => Upload::DB_FILE_NAME,
						'filesize'    => Upload::DB_FILE_SIZE,
						'web_path'    => Upload::DB_WEB_PATH,
						'system_path' => Upload::DB_SYSTEM_PATH,
						'extn' 		  => Upload::DB_EXTN
					) )
					->dbClean( function ( $data ) {
						// Remove the files from the file system
						for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
							unlink( $data[$i]['system_path'] );
						}
		 
						// Have Editor remove the rows from the database
						return true;
					} )
					->validator( Validate::fileSize( 500000, 'Ukuran lampiran maksimal 500Kb' ) )
					->validator( Validate::fileExtensions( array( 'png', 'jpg', 'jpeg'), "Hanya boleh format png, jpg atau jpeg" ) )
				),
			// END field image
				
			// BEGIN contoh validator serverside
			// sudah tidak digunakan, diganti di front end, karena ada bug jika dipasangkan dengan select2
			// validator, bisa digunakan lebih dari 1, sesuai kebutuhan
			Field::inst( '_blank' )
				->validator( Validate::notEmpty( ValidateOptions::inst()
					->message( 'Wajib diisi!' )
				))
				->validator( Validate::unique( ValidateOptions::inst()
					->message( 'Data tidak boleh kembar!' )
				))
				->validator( 
					Validate::maxLen( 
						200,
						ValidateOptions::inst()->message( 'Maksimal 200 karakter!' )
					)
				)
				->validator( 
					Validate::minLen( 
						1,
						ValidateOptions::inst()->message( 'Minimal 1 karakter!' )
					)
				)
				->validator( 
					Validate::maxNum( 
						200,
						ValidateOptions::inst()->message( 'Angka maksimal  = 200!' )
					)
				)
				->validator( Validate::minNum(
					10,
					'.',
					ValidateOptions::inst()
						->message( 'Stock name must be at least 10 characters long' )
				) )
				->validator( Validate::numeric() )
				->validator( Validate::email( ValidateOptions::inst()
						->message( 'Harap masukkan alamat email yang benar!' )  
				) )
				->validator( 'Validate::dateFormat', array(
					'format' => 'd M Y'
				) ),
			// END contoh validator serverside
				
			// BEGIN untuk menampilkan simbol & di form
			// tetapi ini ada hubungannya dengan security, hati-hati, belum tahu efeknya 
			Field::inst( '_blank' )
				->xss(false),
			// END untuk menampilkan & 
			
			// BEGIN manipulasi field value
			// formatter untuk mengubah input menjadi huruf besar semua
			// setValue, digunakan untuk menginput data dari script, bukan dari inputan form
			// set , biasanya digunakan bersamaan dengan setValue. 
			// menentukan kondisi apa setValue akan digunakan, saat create saja, atau edit saja, create dan edit, atau tidak sama sekali
			Field::inst( '_blank' )	
				->setFormatter( function ( $val, $data ) {
					return strtoupper($val);
				} )
				->setValue(date("Y-m-d H:i:s"))
				->setValue($_SESSION['user'])
				->set( Field::SET_CREATE )
				->set( Field::SET_EDIT )
				->set( Field::SET_BOTH )
				->set( Field::SET_NONE ),
			// END manipulasi field value	
					
				
			// BEGIN field select
			Field::inst( '_blank' )		
				->setFormatter( Format::ifEmpty( 0 ) )
				->options( Options::inst()
					->table( 'table_parent' )
					->value( 'id' )
					->label( ['kode','nama'] )
					->where( function ($q) {
						$q->where( 'is_active', 1 );
					})
					->render( function ( $row ) {
						return $row['kode'] . ' - ' . $row['nama'];
					} )
					->order( 'nama' )
				),
			// END field select

			// menampilan field dari join table 
			Field::inst( 'table_parent.namafield' )
		)
		// leftJoin, gunakan jika perlu. Harus digunakan jika menggunakan select di form
		->leftJoin( 'table_parent','table_parent.id','=','_blank.id_table_parent' )

		// leftJoin dengan table alias
		->leftJoin( 'users as a', 'a.id', '=', '_blank.field_x' )
		->leftJoin( 'users as b', 'b.id', '=', '_blank.field_y' )
		
		// BEGIN upload multi file
		// table files dan lk_transaksi_files adalah bawaan framework, jangan diganti. cukup ganti _blank nya saja dan folder 
		->join(
				Mjoin::inst( 'files' )
					->link( '_blank.id', 'lk_transaksi_files.idtransaksi' )
					->link( 'files.id', 'lk_transaksi_files.idfiles' )
					->fields(
						Field::inst( 'id' )
							->upload( Upload::inst( $abs_us_root.$us_url_root. '/usersc/uploads/__ID__.__EXTN__' )
								->db( 'files', 'id', array(
									'filename'    => Upload::DB_FILE_NAME,
									'filesize'    => Upload::DB_FILE_SIZE,
									'web_path'    => Upload::DB_WEB_PATH,
									'system_path' => Upload::DB_SYSTEM_PATH,
									'extn' 		  => Upload::DB_EXTN
								) )
								->validator( Validate::fileSize( 1000000, 'Ukuran file lampiran maksimal 1mb' ) )
								->validator( Validate::fileExtensions( array( 'pdf', 'png', 'jpg', 'jpeg' ), "Hanya diperbolehkan jenis pdf, png, jpg, atau jpeg" ) )
							),
						Field::inst( 'web_path' )
					)
			)
		// END upload multi file
		
		
		// where, gunakan jika perlu. Default operator adalah '=' , bisa tidak diisi jika '='
		->where( 'field_name', kondisi, operator )
		// where or
		->where( function ( $q ) {
			$q
				->where( function ( $r ) {
					$r
						->where( 'field_name', kondisi, operator)
						->or_where( 'field_name', kondisi, operator);
				} );
		} );

		// jangan lupa titik-koma nya
		;
	
	// do not erase
	// function show / hide inactive document
	if ($show_inactive_status == 0){
		$editor
			->where( '_blank.is_active', 1);
	}
	
	// BEGIN hak akses data
	// users 1 s/d 100 untuk keperluan user admin
	// register baru akan mendapatkan id_users mulai 101
	// $_SESSION['where_data_permission'] == 1 artinya self data. user hanya boleh lihat datanya sendiri
	// jika bisa melihat selain datanya sendiri, diatur lagi hak aksesnya lewat hak akses cabang misalnya
	if ($_SESSION['user'] > 100){
		if ($_SESSION['where_data_permission'] == 1){
			$editor
				->where( function ( $q ) {
					$q->where( '_blank.created_by', $_SESSION['user'] );
				} );
		}else{
			// company
			$editor
				->where( function ( $q ) {
					$q->where( '_blank.id_gcpxxmh', '(' . $_SESSION['str_arr_ha_gcpxxmh'] . ')', 'IN', false );
				} );
			
			// branch
			$editor
				->where( function ( $q ) {
					$q->where( '_blank.id_gbrxxmh', '(' . $_SESSION['str_arr_ha_gbrxxmh'] . ')', 'IN', false );
				} );
		}
	}
	// END hak akses data
	
	// BEGIN generate kode
	// memerlukan tambahan script di front end
	include( "../../../helpers/kode_fn_generate_c.php" );
	// END generate kode

	$editor
		->on('postCreate',function( $editor, $id, $values, $row ) {
			// BEGIN UPDATE kode & nama parent
			$arr_field_parent = array('table_parent');
			include( "../../../../usersc/helpers/fn_get_kode_nama_parent.php" );
			// END UPDATE kode & nama parent
		})
		->on('postEdit',function( $editor, $id, $values, $row ) {
			// BEGIN UPDATE kode & nama parent
			$arr_field_parent = array('table_parent');
			include( "../../../../usersc/helpers/fn_get_kode_nama_parent.php" );
			// END UPDATE kode & nama parent
		});
	
	// BEGIN input log
	include( "../../../helpers/edt_log.php" );
	// END input log

	$editor
		->process( $_POST )
		->json();
?>