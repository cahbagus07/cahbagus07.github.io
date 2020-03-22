
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
          <h5 class="sidebar-title">Yudi Setyawan</h5>
          <p align="justify">Saya adalah seorang mahasiswa Politeknik Negeri Lampung, Perjalanan saya di bidang Web Development dimulai saat saya berada di semester III hingga saat ini. Sejauh ini saya telah menyelesaikan lebih dari 10 aplikasi berbasis web. untuk lebih jauh mengenal siapa saya berikut biodata yang saya miliki.</p>
          <table>
            <tr>
              <td width="">Nama Lengkap</td>
              <td>:</td>
              <td>Yudi Setyawan</td>
            </tr>
            <tr>
              <td width="">Tempat, Tanggal Lahir</td>
              <td>:</td>
              <td>7 November 1998</td>
            </tr>
             <tr>
              <td width="">Agama</td>
              <td>:</td>
              <td>Islam</td>
            </tr>
            <tr>
              <td width="">Jenis Kelamin</td>
              <td>:</td>
              <td>Laki - Laki</td>
            </tr>
            <tr>
              <td width="">Alamat</td>
              <td>:</td>
              <td>PT.Gunung Madu, Gunung Batin Baru, Terusan Nunyai, Lampung Tengah, Pos 34167</td>
            </tr>
            <tr>
              <td width="">Status</td>
              <td>:</td>
              <td>Pelajar</td>
            </tr>
            <tr>
              <td width="">Pendidikan</td>
              <td>:</td>
              
            </tr>
            <tr>
              <td width="30%" style="padding-left: 10px;">
                1. 2004 - 2005<br>
                2. 2005 - 2011<br>
                3. 2011 - 2014<br>
                4. 2014 - 2017<br>
                5. 2017 - Sekarang
              </td>
              <td></td>
              <td>
                
                  TK Satya Dharma Sudjana<br>
                  SDN 1 Gunung Madu<br>
                  SMP Satya Dharma Sudjana<br>
                  SMKN 2 Terbanggi Besar<br>
                  Politeknik Negeri Lampung<br>
                
              </td>
            </tr>
            <tr>
              <td width="">Sertifikasi</td>
              <td>:</td>
              <td>MTCNA (MikroTik Certified Network Associate)</td>
            </tr>
            <tr>
              <td >
                <img src="<?php echo base_url('gambar/website/mtcna.PNG') ?>"  width='350%' height='400px'/>
            </td>
            </tr>
              
              
            
          </table>
          
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