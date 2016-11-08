<footer>
  <div class="contact-info">
    <h4><?php echo $company; ?></h4>
    <ul class="l-list">
      <li><a href="mailto:<?php echo $company_email; ?>"><?php echo $company_email; ?></a></li>
      <li>(<?php echo substr( $company_phone, 0, 3 ); ?>) <?php echo substr( $company_phone, 3, 3 ); ?>-<?php echo substr( $company_phone, 6, 4 ); ?></li>
    </ul>
  </div>

  <div class="contact-help">
    <h4>Need Help?</h4>
    <ul class="l-list">
      <li><a href="<?php echo site_url($handle.'/contact-us');?>">Contact us</a></li>
    </ul>
  </div>

  <ul class="l-list" id="footer-menu">
    <li><a href="#">Conditions of Use</a></li><li><a href="#">Privacy Notice</a></li><li>&copy;2016 Pro. Image Software</li>
  </ul>
</footer>