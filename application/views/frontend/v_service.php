
<div class="intro intro-single route bg-image" style="background-image: url(img/overlay-bg.jpg)">
  <div class="overlay-mf"></div>
  <div class="intro-content display-table">
    <div class="table-cell">
      <div class="container">
        <h2 class="intro-title mb-4"><?php echo $judul; ?></h2>
        <ol class="breadcrumb d-flex justify-content-center">
          <li class="breadcrumb-item">
            <a href="<?php echo base_url(); ?>">Dashboard</a>
          </li>
          <li class="breadcrumb-item active"><?php echo $judul; ?></li>
        </ol>
      </div>
    </div>
  </div>
</div>
<!--/ Intro Skew End /-->

<!--/ Section Blog-Single Star /-->

<section class="blog-wrapper sect-pt4" id="blog">
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="widget-sidebar">
          <h5 class="sidebar-title">Service</h5>
          <p>Berikut adalah layanan kami,</p>

            <ol style="text-align: justify;">
              <li>Jasa Pembuatan Aplikasi<br />
              Kami menerima jasa pembuatan Aplikasi berbasis web sesuai dengan kebutuhan anda, kami akan memberikan yang terbaik untuk anda dan sesuai dengan yang anda harapkan. Dalam pengerjaannya kami biasa menggunakan bahasa pemrograman PHP, javascript, framework dan juga dengan database MYSQL.<br />
              &nbsp;</li>
              <li>Jasa Pembuatan Website<br />
              Selain pemuatan aplikasi kami juga menerima jasa pembuatan website, baik website profile perusahaan ataupun perorangan.<br />
              &nbsp;</li>
              <li>Service<br />
              Selain kedua hal diatas kami juga menerima perbaikan bug yang ada pada aplikasi atau website anda, kami juga menyediakan layanan penambahan fitur yang ada pada aplikasi lama anda tanpa membuat aplikasi yang baru.<br />
            </ol>

          
          <div class="sidebar-content">
            <ul class="list-sidebar">
              
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <?php $this->load->view('frontend/v_sidebar'); ?>
      </div>
    </div>
  </div>
</section>
  <!--/ Section Blog-Single End /