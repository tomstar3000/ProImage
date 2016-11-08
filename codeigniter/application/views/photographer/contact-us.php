<div class="content-container">
  <h1>Contact Us</h1>
  <form action="#" class="contact-form">
    <label>Contact Who</label>
    <ul class="checkboxes">
      <li>
        <label>
          <input type="checkbox">
          <span class="icon"><i class="fa fa-check"></i></span>
          Contact <?php echo $company;?>
        </label>
      </li>
      <li>
        <label>
          <input type="checkbox">
          <span class="icon"><i class="fa fa-check"></i></span>
          Contact Pro Image Software
        </label>
      </li>
    </ul>
    <div>
      <div class="form-group">
        <label for="first-name">First Name</label>
        <input type="text" name="first-name" id="first-name">
      </div>
      <div class="form-group">
        <label for="last-name">Last Name</label>
        <input type="text" name="last-name" id="last-name">
      </div>
      <div class="form-group">
        <label for="phone-number">Phone Number</label>
        <!-- <input type="text" pattern="[0-9]*" name="phone-number" id="phone-number"> -->
        <input type="tel" name="tel" id="tel">
      </div>
      <div class="form-group">
        <label for="message">Message</label>
        <textarea name="message" id="message" rows="5"></textarea>
      </div>
      <div>
        <button type="submit">submit</button>
      </div>
    </div>
  </form>
</div>