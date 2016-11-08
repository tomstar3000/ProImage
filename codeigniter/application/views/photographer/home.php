
  <div class="hero-image-container">
    <img class="img-fluid" src="<?php echo asset_url().'custom/images/home/hero.png'; ?>">
  </div>
  <div class="content-container">
    <div class="event-code-search">
      <p class="instructions">code not working? <a href="contact-us">contact us</a></p>
      <!-- <form action="<?php //echo site_url($handle);?>" method='POST'> -->
      <form action="<?php echo $_SERVER['PHP_SELF'];?>" method='POST'>
        <div>
          <div class="form-group">
            <input type="text" name="event-code" placeholder="ENTER YOUR EVENT CODE">
          </div>
          <div>
            <button type="submit">view event</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="content-container">
    <div class="photographer-copy">
      <div class="headers">
        <h2>Curabitur vel augue enim</h2>
        <h3>Subtitle goes here</h3>
      </div>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque tincidunt, felis in sodales cursus, turpis erat dapibus ipsum, nec sodales est felis hendrerit sapien. Aenean dapibus lacus velit, sed faucibus dolor fermentum eget. Curabitur vel augue enim. Donec tempor eros in malesuada eleifend. Sed tellus massa, euismod eu pulvinar sit amet, accumsan sit amet nibh. Nullam facilisis, enim et dictum ullamcorper, velit quam luctus nibh, non egestas nunc purus lacinia mi. Curabitur sit amet odio id libero mollis ornare.</p>
      <!-- <p><?php echo $description; ?></p> -->
    </div>
  </div>
  <div class="content-container">
    <h2>Gallery <a href="gallery">See All</a></h2>
  </div>
  <ul class="images-container">
    <li>
      <a href="#">
        <img class="img-fluid img-rounded" src="<?php echo asset_url().'custom/images/gallery/gallery-1.png'; ?>">
        Event Post</a>
    </li><li>
      <a href="#">
        <img class="img-fluid img-rounded" src="<?php echo asset_url().'custom/images/gallery/gallery-2.png'; ?>">
        Event Post</a>
    </li><li>
      <a href="#">
        <img class="img-fluid img-rounded" src="<?php echo asset_url().'custom/images/gallery/gallery-3.png'; ?>">
        Event Post</a>
    </li><li>
      <a href="#">
        <img class="img-fluid img-rounded" src="<?php echo asset_url().'custom/images/gallery/gallery-4.png'; ?>">
        Event Post</a>
    </li>
  </ul>