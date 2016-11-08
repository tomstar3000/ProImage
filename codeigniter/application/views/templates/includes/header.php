<header>
  <a href="#" class="menu-icon">
    <span></span>
  </a>
  <nav id="header-nav">
    <ul class="l-list">
      <li>
        <a href="<?php echo site_url($handle);?>">Home</a>
      </li>
      <li>
        <a href="<?php echo site_url($handle.'/gallery');?>">Gallery</a>
      </li>
      <li>
        <a href="<?php echo site_url($handle.'/contact-us');?>">Contact</a>
      </li>
    </ul>
  </nav>
  <div class="logo">
    <a href="<?php echo site_url($handle);?>"><img src="<?php echo asset_url().'custom/images/logo.png'; ?>" class="img-fluid" alt=""></a>
  </div>
</header>