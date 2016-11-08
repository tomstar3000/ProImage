<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $the_view_title; ?></title>
    <meta name="author" content="Armando Torres (http://region-zero.com)">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:300,400,700' type='text/css'>
    <link rel="stylesheet" href="<?php echo asset_url().'custom/css/main.css'; ?>" type='text/css'>
  </head>
  <body>
    <?php $this->load->view('templates/includes/header-cart'); ?>
    <div class="wrapper">
      <?php echo $the_view_content; ?>
    </div>
    <?php $this->load->view('templates/includes/footer'); ?> 
    
    <script src="<?php echo asset_url().'vendor/jquery/dist/jquery.min.js'; ?>"></script>
    <script src="<?php echo asset_url().'vendor/imagesloaded/imagesloaded.pkgd.js'; ?>"></script>
    <script src="<?php echo asset_url().'vendor/masonry/dist/masonry.pkgd.js'; ?>"></script>
    <script src="<?php echo asset_url().'custom/js/app-cart.js'; ?>"></script>
  </body>
</html>