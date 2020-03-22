<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title> 7Dev | <?php echo $judul; ?></title>  
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Favicons -->
 <link href="<?php echo base_url().'/gambar/website/logoku.png'?>" rel="icon">

  <!-- Bootstrap CSS File -->
  <link href="<?php echo base_url(); ?>assets_frontend/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="<?php echo base_url(); ?>assets_frontend/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets_frontend/lib/animate/animate.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets_frontend/lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets_frontend/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="<?php echo base_url(); ?>assets_frontend/lib/lightbox/css/lightbox.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="<?php echo base_url(); ?>assets_frontend/css/style.css" rel="stylesheet">

  <!-- =======================================================
    Theme Name: DevFolio
    Theme URL: https://bootstrapmade.com/devfolio-bootstrap-portfolio-html-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->
</head>

<body id="page-top">

  <!--/ Nav Star /-->
  <nav class="navbar navbar-b navbar-trans navbar-expand-md fixed-top" id="mainNav">
    <div class="container">

      <img src="<?php echo base_url().'/gambar/website/logoku.png'?>" width="30px" class="mr-2">

      <a class="navbar-brand js-scroll" href="<?php echo base_url()?>">7Dev</a>

      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarDefault"
        aria-controls="navbarDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span></span>
        <span></span>
        <span></span>

      </button>
      <div class="navbar-collapse collapse justify-content-end" id="navbarDefault">
        <ul class="navbar-nav">
          
          <li class="nav-item">
            <a class="nav-link js-scroll" href="<?php echo base_url(); ?>">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll" href="<?php echo base_url('welcome/tentang'); ?>">About Me</a>
          </li>
          <li class="nav-item">
            <a class="nav-link js-scroll" href="<?php echo base_url('welcome/service'); ?>">Service</a>
          </li>
          
          <li class="nav-item">
            <a class="nav-link js-scroll" href="<?php echo base_url('welcome/corona'); ?>">Update Corona</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!--/ Nav End /-->