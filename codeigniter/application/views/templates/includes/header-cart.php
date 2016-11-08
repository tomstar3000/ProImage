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
    <a href="<?php echo site_url($handle);?>"><img src="<?php echo asset_url().'custom/images/logo.png'; ?>" class="img-fluid"></a>
  </div>
  <a href="#" class="shopping-cart-icon">
    <!-- blam!<span class="label-default">5</span> -->
    <img src="<?php echo asset_url().'custom/images/svg/shopping-cart.svg'; ?>" class="img-fluid">
    <!-- <span class="label label-default">5</span> -->
    <!-- blam!<span class="label label-pill label-default">5</span> -->
    <!-- blam!<span class="tag tag-default tag-pill">5</span> 
    -->
    <!-- blam!<label class="tag tag-default tag-pill">5</label> -->
  </a>
</header>