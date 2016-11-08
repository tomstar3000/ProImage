<div class="content-container">
  <h1><?php echo $code; ?></h1>
  
  <!-- <form action='<?php //echo site_url().'/'.$handle.'/'.$code.'/process';?>' method='post'> -->
  <div class="photographer-event-login">
    <p class="instructions">A valid e-mail is required to choose favorites, take advantage of discounts, and experience full site functionality.</p>
    <form action='<?php echo $_SERVER['PHP_SELF'];?>' method='post'>
      <?php 
      if( isset($msg)){
        echo $msg.'<br/>';
      }
      ?> 
      <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" name="first-name" id="first-name">
      </div> 
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email">
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-xs-1">
            <ul class="checkboxes">
              <li>
                <label>
                  <input type="checkbox" name="opt-in" id="opt-in" checked>
                  <span class="icon"><i class="fa fa-check"></i></span>
                </label>
              </li>
            </ul>
          </div>
          <div class="col-xs-11">
            <label for="opt-in">
              We encourage you to leave this box check marked and supply a valid email address to take advantage of the functions of the site and specials that may be offered such as discount codes, special pricing offers and event viewing expiration. We will not sell or share your email address with any other entity and you will receive communication ONLY regarding this event. Thank you for your trust.
            </label>
          </div>
        </div>
      </div>
      <div>
        <button type="submit">submit</button>
      </div>
      <!-- <label for='email'>Email</label>
      <input type='text' name='email' size='25' /><br />
      <input type="checkbox" name="promo" checked="checked" value="y" />I can haz cheezeburger?<br />
      <input type='Submit'/> -->
    </form>
  </div>
</div>