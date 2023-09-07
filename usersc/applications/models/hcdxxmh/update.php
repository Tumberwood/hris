<?php
	include( "../../../../users/init.php" );
	include( "../../../../usersc/lib/DataTables.php" );
	include( "../../../../usersc/helpers/datatables_fn_debug.php" );
	
    require_once('../../../../usersc/vendor/autoload.php');
    use
        DataTables\Editor,
        DataTables\Editor\Query,
        DataTables\Editor\Result;

    // BEGIN definisi variable untuk fn_ajax_results.php
    $data      = array();
    $rs_opt    = array();
    $c_rs_opt  = 0;
    $morePages = 0;
    // END definisi variable untuk fn_ajax_results.php

        try{
            $db->transaction();
            $files_foto = $_FILES['hcdxxmh_id_files_foto']; 
            $files_cv = $_FILES['hcdxxmh_id_files_cv']; 
            $files_ktp = $_FILES['hcddcmh_id_files_ktp']; 
            $files_sim = $_FILES['hcddcmh_id_files_sim']; 
            $id_hcd = $_POST['id_hcd'];

            //SELECT FOTO
            $s_files = $db
            ->raw()
            ->bind(':id_hcd', $id_hcd)
            ->exec(' SELECT 
                        a.id_files_foto AS id_foto,
                        foto.web_path AS foto_path,
                        a.id_files_cv AS id_cv,
                        cv.web_path AS cv_path,
                        doc.id_files_ktp AS id_ktp,
                        ktp.web_path AS ktp_path,
                        doc.id_files_sim AS id_sim,
                        sim.web_path AS sim_path
                        
                    FROM hcdxxmh AS a
                    LEFT JOIN files AS foto ON foto.id = a.id_files_foto
                    LEFT JOIN files AS cv ON cv.id = a.id_files_cv
                    LEFT JOIN hcddcmh AS doc ON doc.id_hcdxxmh = a.id
                    LEFT JOIN files AS ktp ON ktp.id = doc.id_files_ktp
                    LEFT JOIN files AS sim ON sim.id = doc.id_files_sim
                    WHERE a.id = :id_hcd
                    '
                    );
            $select_files = $s_files->fetch();
            
            //FOTO Kandidat
            if ($files_foto['size'] > 0) {
                if ($select_files['id_foto'] > 0) {
                    $existingPhotoPath = $_SERVER['DOCUMENT_ROOT'] . $select_files['foto_path'];
                    if (file_exists($existingPhotoPath)) {
                        unlink($existingPhotoPath);
                    }
                }
                $tempFilePath = $files_foto['tmp_name'];
                $fileName = $files_foto['name'];

                //DIREKTORI
                $system_path = $abs_us_root.$us_url_root.'usersc/files/kandidat/';
                $web_path = $us_url_root.'usersc/files/kandidat/';

                // Get the file extension
                $extension = pathinfo($files_foto['name'], PATHINFO_EXTENSION);

                // Create the full path where the file will be stored
                $targetsystem_path = $system_path . $fileName . '.' . $extension;
                $targetweb_path = $web_path . $fileName . '.' . $extension;

                // Move the temporary file to the target path
                move_uploaded_file($tempFilePath, $targetsystem_path);

                // INSERT
                $ins_files = $db
                    ->query('insert', 'files')
                    ->set('filename', $fileName . '.' . $extension)
                    ->set('filesize', $files_foto['size'])
                    ->set('web_path', $targetweb_path)
                    ->set('system_path', $targetsystem_path)
                    ->set('extn', $extension)
                    ->exec();

                $id_files_foto = $ins_files->insertId();
            }
            
            //cv Kandidat
            if ($files_cv['size'] > 0) {
                if ($select_files['id_cv'] > 0) {
                    $existingPhotoPath = $_SERVER['DOCUMENT_ROOT'] . $select_files['cv_path'];
                    if (file_exists($existingPhotoPath)) {
                        unlink($existingPhotoPath);
                    }
                }
                $tempFilePath = $files_cv['tmp_name'];
                $fileName = $files_cv['name'];

                //DIREKTORI
                $system_path = $abs_us_root.$us_url_root.'usersc/files/kandidat/';
                $web_path = $us_url_root.'usersc/files/kandidat/';

                // Get the file extension
                $extension = pathinfo($files_cv['name'], PATHINFO_EXTENSION);

                // Create the full path where the file will be stored
                $targetsystem_path = $system_path . $fileName . '.' . $extension;
                $targetweb_path = $web_path . $fileName . '.' . $extension;

                // Move the temporary file to the target path
                move_uploaded_file($tempFilePath, $targetsystem_path);

                // INSERT
                $i_cv = $db
                    ->query('insert', 'files')
                    ->set('filename', $fileName . '.' . $extension)
                    ->set('filesize', $files_cv['size'])
                    ->set('web_path', $targetweb_path)
                    ->set('system_path', $targetsystem_path)
                    ->set('extn', $extension)
                    ->exec();

                $id_files_cv = $i_cv->insertId();
            }
            
            //ktp Kandidat
            if ($files_ktp['size'] > 0) {
                if ($select_files['id_ktp'] > 0) {
                    $existingPhotoPath = $_SERVER['DOCUMENT_ROOT'] . $select_files['ktp_path'];
                    if (file_exists($existingPhotoPath)) {
                        unlink($existingPhotoPath);
                    }
                }
                $tempFilePath = $files_ktp['tmp_name'];
                $fileName = $files_ktp['name'];

                //DIREKTORI
                $system_path = $abs_us_root.$us_url_root.'usersc/files/kandidat/';
                $web_path = $us_url_root.'usersc/files/kandidat/';

                // Get the file extension
                $extension = pathinfo($files_ktp['name'], PATHINFO_EXTENSION);

                // Create the full path where the file will be stored
                $targetsystem_path = $system_path . $fileName . '.' . $extension;
                $targetweb_path = $web_path . $fileName . '.' . $extension;

                // Move the temporary file to the target path
                move_uploaded_file($tempFilePath, $targetsystem_path);

                // INSERT
                $i_ktp = $db
                    ->query('insert', 'files')
                    ->set('filename', $fileName . '.' . $extension)
                    ->set('filesize', $files_ktp['size'])
                    ->set('web_path', $targetweb_path)
                    ->set('system_path', $targetsystem_path)
                    ->set('extn', $extension)
                    ->exec();

                $id_files_ktp = $i_ktp->insertId();
            }
            
            //sim Kandidat
            if ($files_sim['size'] > 0) {
                if ($select_files['id_sim'] > 0) {
                    $existingPhotoPath = $_SERVER['DOCUMENT_ROOT'] . $select_files['sim_path'];
                    if (file_exists($existingPhotoPath)) {
                        unlink($existingPhotoPath);
                    }
                }
                $tempFilePath = $files_sim['tmp_name'];
                $fileName = $files_sim['name'];

                //DIREKTORI
                $system_path = $abs_us_root.$us_url_root.'usersc/files/kandidat/';
                $web_path = $us_url_root.'usersc/files/kandidat/';

                // Get the file extension
                $extension = pathinfo($files_sim['name'], PATHINFO_EXTENSION);

                // Create the full path where the file will be stored
                $targetsystem_path = $system_path . $fileName . '.' . $extension;
                $targetweb_path = $web_path . $fileName . '.' . $extension;

                // Move the temporary file to the target path
                move_uploaded_file($tempFilePath, $targetsystem_path);

                // INSERT
                $i_sim = $db
                    ->query('insert', 'files')
                    ->set('filename', $fileName . '.' . $extension)
                    ->set('filesize', $files_sim['size'])
                    ->set('web_path', $targetweb_path)
                    ->set('system_path', $targetsystem_path)
                    ->set('extn', $extension)
                    ->exec();

                $id_files_sim = $i_sim->insertId();
            }

            //HCDXXMH KANDIDAT
            $nama = $_POST['hcdxxmh_nama'];
            $nama = ucwords(strtolower($nama));
            $gender = $_POST['hcdxxmh_gender'];
            $tanggal_lahir = $_POST['hcdxxmh_tanggal_lahir'];
            $id_gctxxmh_lahir = $_POST['selectid_gctxxmh_lahir'];
            $agama = $_POST['hcdxxmh_agama'];
            $perkawinan = $_POST['hcdxxmh_perkawinan'];
            $suku = $_POST['hcdxxmh_suku'];
            $gol_darah = $_POST['hcdmdmh_gol_darah'];
            // $berat = $_POST['hcdxxmh_berat'];
            // $tinggi = $_POST['hcdxxmh_tinggi'];
            // $no_sepatu = $_POST['hcdxxmh_no_sepatu'];
            // $ukuran_seragam = $_POST['hcdxxmh_ukuran_seragam'];
            // $hobby = $_POST['hobby'];

            // $rt = $_POST['rt'];
            // $rw = $_POST['rw'];
            // $kelurahan = $_POST['kelurahan'];
            // $kecamatan = $_POST['kecamatan'];

            //HCDSCMH KONTAK SOSMED
            $email_personal = $_POST['hcdcsmh_email_personal'];
            $twitter = $_POST['hcdcsmh_twitter'];
            $facebook = $_POST['hcdcsmh_facebook'];
            $linkedin = $_POST['hcdcsmh_linkedin'];
            $instagram = $_POST['hcdcsmh_instagram'];
            $tiktok = $_POST['hcdcsmh_tiktok'];
            $handphone = $_POST['hcdcsmh_handphone'];
            $whatsapp = $_POST['hcdcsmh_whatsapp'];
            $alamat_tinggal = $_POST['hcdcsmh_alamat_tinggal'];
            $id_gctxxmh_tinggal = $_POST['kota_tinggal'];

            //HCDDCMH DOKUMEN
            $ktp_no = $_POST['hcddcmh_ktp_no'];
            $id_gctxxmh_ktp = $_POST['kota_ktp'];
            $ktp_alamat = $_POST['hcddcmh_ktp_alamat'];
            $sim_a = $_POST['hcddcmh_sim_a'];
            $sim_b = $_POST['hcddcmh_sim_b'];
            $sim_c = $_POST['hcddcmh_sim_c'];
            $is_npwp = $_POST['hcddcmh_is_npwp'];
            $npwp_no = $_POST['hcddcmh_npwp_no'];
            $npwp_alamat = $_POST['hcddcmh_npwp_alamat'];
            $id_gctxxmh_npwp = $_POST['hcddcmh_id_gctxxmh_npwp'];
            $id_gtxpkmh = $_POST['hcddcmh_id_gtxpkmh'];

            //LAIN LAIN
            $bpjskes_no = $_POST['hcdjbmh_bpjskes_no'];
            $bpjstk_no = $_POST['hcdjbmh_bpjstk_no'];
            $tempat_tinggal = $_POST['hcdjbmh_tempat_tinggal'];
            $kendaraan = $_POST['hcdjbmh_kendaraan'];
            $kendaraan_milik = $_POST['hcdjbmh_kendaraan_milik'];

            // UPDATE hcdxxmh Data Diri Karyawan
            $qu_hcdxxmh = $db
            ->query('update', 'hcdxxmh')
            ->set('nama', $nama)
            ->set('gender', $gender)
            ->set('tanggal_lahir', $tanggal_lahir)
            ->set('id_gctxxmh_lahir', $id_gctxxmh_lahir)
            ->set('agama', $agama)
            ->set('perkawinan', $perkawinan)
            ->set('suku', $suku)
            ;
            // ->set('berat', $berat)
            // ->set('tinggi', $tinggi)
            // ->set('no_sepatu', $no_sepatu)
            // ->set('ukuran_seragam', $ukuran_seragam);

            if (!empty($id_files_foto)) {
            $qu_hcdxxmh->set('id_files_foto', $id_files_foto);
            }

            if (!empty($id_files_cv)) {
            $qu_hcdxxmh->set('id_files_cv', $id_files_cv);
            }

            $qu_hcdxxmh->where('id', $id_hcd)
            ->exec();

            //UPDATE hcdmdmh Medic
            $qu_hcdmdmh = $db
            ->query('update', 'hcdmdmh')
            ->set('gol_darah', $gol_darah)
            ->where('id_hcdxxmh',$id_hcd)
            ->exec();

            //UPDATE hcdcsmh Social Media
            $qu_hcdcsmh = $db
            ->query('update', 'hcdcsmh')
            ->set('email_personal', $email_personal)
            ->set('twitter', $twitter)
            ->set('facebook', $facebook)
            ->set('linkedin', $linkedin)
            ->set('instagram', $instagram)
            ->set('tiktok', $tiktok)
            ->set('handphone', $handphone)
            ->set('whatsapp', $whatsapp)
            ->set('alamat_tinggal', $alamat_tinggal)
            ->set('id_gctxxmh_tinggal', $id_gctxxmh_tinggal)
            ->where('id_hcdxxmh',$id_hcd)
            ->exec();

            //UPDATE hcddcmh Document
            $qu_hcddcmh = $db
            ->query('update', 'hcddcmh')
            ->set('ktp_no', $ktp_no)
            ->set('id_gctxxmh_ktp', $id_gctxxmh_ktp)
            ->set('ktp_alamat', $ktp_alamat)
            ->set('sim_a', $sim_a)
            ->set('sim_b', $sim_b)
            ->set('sim_c', $sim_c)
            ->set('is_npwp', $is_npwp)
            ->set('npwp_no', $npwp_no)
            ->set('npwp_alamat', $npwp_alamat)
            ->set('id_gctxxmh_npwp', $id_gctxxmh_npwp)
            ->set('id_gtxpkmh', $id_gtxpkmh)
            ->where('id_hcdxxmh',$id_hcd)
            ->exec();
            // ->set('rt', $rt)
            // ->set('rw', $rw)
            // ->set('kelurahan', $kelurahan)
            // ->set('kecamatan', $kecamatan)
            
            //UPDATE hcdjbmh Lain-lain
            $qu_hcdjbmh = $db
            ->query('update', 'hcdjbmh')
            ->set('bpjskes_no', $bpjskes_no)
            ->set('bpjstk_no', $bpjstk_no)
            ->set('tempat_tinggal', $tempat_tinggal)
            ->set('kendaraan', $kendaraan)
            ->set('kendaraan_milik', $kendaraan_milik);

            for ($i = 1; $i <= 13; $i++) {
                $qu_hcdjbmh->set("intrv_self_$i", $_POST["hcdjbmh_intrv_self_$i"]);
            }
            $qu_hcdjbmh->where('id_hcdxxmh',$id_hcd)
            ->exec();

            $db->commit();
            // $data = array(
            //     'id_hcd'=> $_POST['id_hcd'],
            //     'ktp'=> $ktp_no,
            //     'qu_hcdjbmh'=> $qu_hcdjbmh,
            //     'qu_hcddcmh'=> $qu_hcddcmh
            // );

            echo json_encode(array('message'=> 'Update Berhasil' , 'type_message'=>'success' ));
        }catch (PDOException $e){
            $db->rollback();
            echo json_encode(array('message'=> 'Update Gagal ' . $e , 'type_message'=>'danger' ));
        }
    // tampilkan results
    require_once( "../../../../usersc/helpers/fn_ajax_results.php" );
?>