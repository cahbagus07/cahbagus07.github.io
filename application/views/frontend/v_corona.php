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
      <div class="col-md-12">
        <div class="widget-sidebar">
          <h5 class="sidebar-title">Perkembangan Virus Corona</h5>
          <p align="justify">Akhir tahun 2019 kita dihebohkan virus baru yang muncul di Wuhan, China. Hingga saat ini virus corona telah menyebar luas di dunia termasuk di Indonesia, dengan itu kami turut peduli dengan perkembangan cirus corona, maka kami memeberikan update perkembangan virus corona didunia.</p>
          <table id="table_id" class="table table-bordered">
            <thead align="center">
              <th >No</th>
              <th>Negara</th>
              <th>Total Kasus</th>
              <th>Kasus hari ini</th>
              <th>Meninggal</th>
              <th>Meninggal hari ini</th>
              <th>Sembuh</th>
              <th>Aktif</th>
              <th>Kritis</th>
            </thead>
            <tbody>
            <?php $no=1; foreach ($list as $a) {?>
              <tr>
                <td align="center"><?php echo $no++ ?></td>
                <td><?php echo $a->country ?></td>
                <td align="center"><?php echo $a->cases ?></td>
                <td align="center"><?php echo $a->todayCases ?></td>
                <td align="center"><?php echo $a->deaths ?></td>
                <td align="center"><?php echo $a->todayDeaths ?></td>
                <td align="center"><?php echo $a->recovered ?></td>
                <td align="center"><?php echo $a->active ?></td>
                <td align="center"><?php echo $a->critical ?></td>
              </tr>
            <?php  } ?>
            </tbody>
          </table>

            

          
          <div class="sidebar-content">
            <ul class="list-sidebar">
              
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
  <!--/ Section Blog-Single End /
