<?php
  require_once($abs_us_root . $us_url_root . 'users/includes/template/database_navigation_prep.php');
?>

      <div id="wrapper">
      <?php
              require_once $abs_us_root.$us_url_root.'usersc/templates/inspinia-side/template_sidebar.php';
            /**
             * Bagian ini dipindahkan ke template_sidebar.php
             * Berisi Menu Kiri
             * 
             */
            ?>

        <div id="page-wrapper" class="gray-bg">
            <?php
              require_once $abs_us_root.$us_url_root.'usersc/templates/inspinia-side/template_topbar.php';
            /**
             * Bagian ini no 1 dan 2 dipindahkan ke template_topbar.php
             * 3 ada di file ini, tetapi isi nya nanti ada di masing-masing halaman
             * 
             * di dalam page-wrapper ada 3 row utama
             * 1. menu atas: <div class="row border-bottom">
             * 2. judul dan breadcrumb: <div class="row wrapper border-bottom white-bg page-heading">
             * 3. content: <div class="fh-no-breadcrumb">
             */
            ?>

            <!-- fh full height -->
            <div class="">
                <?php
                  /**
                   * di dalam fh-no-breadbrumb bisa berisi 2 bagian kiri dan kanan
                   * contoh di http://webapplayers.com/inspinia_admin-v2.9.4/full_height.html
                   * ini kolom kiri nya, tetapi tidak dipakai
                   * <div class="fh-column">
                   * <div class="full-height-scroll">
                   * </div>
                   * </div>
                   * 
                   * full-height dibawah ini adalah kolom kanan, yang dipakai
                   * 
                   * <div class="full-height">
                   * <div class="full-height-scroll white-bg"> 
                   * class full-height dihilangkan
                  */
                ?>
                <div class="">
                  <div class="white-bg">
                    
                    <?php
                    /**
                     * Bagian ini merupakan content
                     * lanjut di masing-masing halaman
                     * Closing div nya ada di container_close
                     */
                    ?>
                      
                  
                      <?php
                      /**
                       * Penutup
                       * Terdapat closing div dan footer. semuanya dipindahkan ke file container_close
                       * 
                       * 1. </div> dari full-height-scroll white-bg
                       * 2. </div> dari full-height (content)
                       * 3. </div> dari fh-no-breadcrumb
                       * 4. grup div footer (start dan end)
                       * 5. </div> dari page-wrapper
                       * 6. </div> dari end wrapper
                       */
                      ?>

<?php
    if(isset($_GET['err'])){
      err("<font color='red'>".$err."</font>");
    }

    if(isset($_GET['msg'])){
      err($msg);
    }