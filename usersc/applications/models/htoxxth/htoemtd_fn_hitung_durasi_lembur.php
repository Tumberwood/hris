<?php
    require '../../../../usersc/vendor/autoload.php';
	use Carbon\Carbon;

    /**
     * 0: Tidak Istirahat Full
     * 1: Istirahat Full
     * 2: TI (30 menit)
     */
    $is_istirahat   = $values['htoemtd']['is_istirahat'];
    $id_htotpmh     = $values['htoemtd']['id_htotpmh'];

    if($id_htotpmh == 1 || $id_htotpmh == 2 || $id_htotpmh == 4){
        /**
         * jika lembur awal atau akhir atau hari libur
         * cek jenis istirahat
         * is_istirahat
         *  0: Tidak    : tidak ada potongan
         *  1: Ya       : potong 60 menit
         *  2: TI       : potong 30 menit
         */

        if($is_istirahat == 0){
            $subminutes = 0;
        }elseif($is_istirahat == 1){
            $subminutes = 60;
        }elseif($is_istirahat == 2){
            $subminutes = 30; //ditambah menit sesuai dengan is_istirahat yang dipilih
        } else {
            $subminutes = 120;
        }

        $jam_awal	= new Carbon($values['htoemtd']['jam_awal']);
        $jam_akhir	= new Carbon($values['htoemtd']['jam_akhir']);

        if($jam_akhir < $jam_awal){
            // artinya beda hari
            $jam_akhir_final = $jam_akhir->addHours(24);
        }else{
            $jam_akhir_final = $jam_akhir;
        }

        $durasi_menit = $jam_akhir_final->diffInMinutes($jam_awal) + $subminutes;

    }elseif($id_htotpmh == 5 || $id_htotpmh == 6 || $id_htotpmh == 7){
        /* jika lembur istirahat shift */
        $durasi_menit = 30;
    }

    $durasi_jam = $durasi_menit / 60;

    $qu_htoemtd = $editor->db()
        ->query('update', 'htoemtd')
        ->set('durasi_lembur_menit',$durasi_menit)
        ->set('durasi_lembur_jam',$durasi_jam)
        ->where('id',$id)
        ->exec();
?>